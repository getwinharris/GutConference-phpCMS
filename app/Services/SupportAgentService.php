<?php
namespace App\Services;

final class SupportAgentService {
    private string $failureFile;

    public function __construct(
        private JsonStoreService $store = new JsonStoreService(),
        private AgentContextService $context = new AgentContextService(),
        private GeminiModelRouter $router = new GeminiModelRouter(),
        private UserContextService $userContext = new UserContextService()
    ) {
        $this->failureFile = storage_path('support-ai-routing.json');
    }

    public function reply(string $message, array $user = []): array {
        $message = trim($message);
        $email = (string)($user['email'] ?? '');
        $context = $this->context->forUserEmail($email);
        $role = (string)($user['role'] ?? ($email === '' ? 'guest' : 'customer'));
        if ($role !== 'guest') {
            $context['account_context'] = $this->userContext->snapshot($user);
        }
        $context['role_rules'] = $this->roleRules($role);
        $answer = $this->localAnswer($message, $context, $user);
        $ticket = null;
        $proposal = null;
        $links = $this->suggestedLinks($message, $context);

        if ($this->shouldCreateTicket($message)) {
            $ticket = $this->createTicket($message, $user);
            $answer .= "\n\nI also created a support ticket for the admin team: " . $ticket['id'] . ".";
        }

        if ($role === 'admin' && $this->looksLikeAdminCmsRequest($message)) {
            $answer = $this->adminCmsGuidance($message);
            $proposal = $this->adminProposal($message);
        }

        if (!in_array(strtolower($message), ['intro', '__intro'], true)) {
            $aiAnswer = $this->remoteAnswer($message, $context, $user);
            if ($aiAnswer !== '') {
                $answer = $aiAnswer;
            }
        }
        $this->userContext->appendConversation($user, $message, $answer);

        return [
            'answer' => $answer,
            'ticket' => $ticket ? ['id' => $ticket['id'], 'status' => $ticket['status']] : null,
            'proposal' => $proposal,
            'links' => $links,
        ];
    }

    private function localAnswer(string $message, array $context, array $user): string {
        $lower = strtolower($message);
        $site = $context['site'] ?? [];
        $events = $site['events'] ?? [];
        $registrations = $context['registrations'] ?? [];

        if (in_array($lower, ['intro', '__intro', 'hello', 'hi'], true)) {
            $owner = $context['site']['owner']['name'] ?? 'Dr. Praveen Jacob';
            $event = $events[0] ?? [];
            return 'Welcome to GutConference support. This portal presents ' . $owner . '\'s clinical profile, gut-health education, consultation pathways, and microbiome-focused events. Current featured event: ' . ($event['name'] ?? 'Global Gut Summit') . (!empty($event['date_label']) ? ' on ' . $event['date_label'] : '') . (!empty($event['time_label']) ? ' at ' . $event['time_label'] : '') . '. You can ask about speakers, agenda, booking, certificates, or contact options.';
        }

        if (str_contains($lower, 'speaker')) {
            $speakers = $this->store->read('speakers');
            $lines = array_slice(array_map(fn($speaker) => trim((string)($speaker['name'] ?? '') . (!empty($speaker['credentials']) ? ', ' . $speaker['credentials'] : '') . (!empty($speaker['topic']) ? ' - ' . $speaker['topic'] : '') . (!empty($speaker['session_time']) ? ' (' . $speaker['session_time'] . ')' : '')), $speakers), 0, 12);
            $answer = "The current speaker lineup includes:\n- " . implode("\n- ", array_filter($lines));
            if (str_contains($lower, 'agenda') || str_contains($lower, 'time') || str_contains($lower, 'schedule')) {
                $sessions = $this->store->read('sessions');
                $agenda = array_slice(array_map(fn($session) => trim(($session['time_label'] ?? '') . ' - ' . ($session['title'] ?? '') . (!empty($session['speaker_name']) ? ' by ' . $session['speaker_name'] : '')), $sessions), 0, 10);
                $answer .= "\n\nAgenda highlights:\n- " . implode("\n- ", array_filter($agenda));
            }
            return $answer . "\n\nOpen the event page for photos, topics, session timings, credentials, and booking.";
        }

        if (str_contains($lower, 'founder') || str_contains($lower, 'owner') || str_contains($lower, 'owns') || str_contains($lower, 'praveen') || str_contains($lower, 'portal')) {
            $settings = $this->store->read('settings')[0] ?? [];
            return ($settings['product_owner_name'] ?? 'Dr. Praveen Jacob') . ' is the profile owner for GutConference. The public site presents his clinical profile, gut-health education, consultation pathways, and microbiome-focused events.';
        }

        if (str_contains($lower, 'agenda') || str_contains($lower, 'schedule')) {
            $sessions = $this->store->read('sessions');
            $lines = array_slice(array_map(fn($session) => trim(($session['time_label'] ?? '') . ' - ' . ($session['title'] ?? '') . (!empty($session['speaker_name']) ? ' by ' . $session['speaker_name'] : '')), $sessions), 0, 8);
            return "Agenda highlights:\n- " . implode("\n- ", array_filter($lines));
        }

        if (str_contains($lower, 'course') || str_contains($lower, 'joined') || str_contains($lower, 'certificate') || str_contains($lower, 'completed')) {
            if (empty($user)) {
                return 'Please log in, then open Dashboard to see joined events/courses, payment status, and certificate status. I can answer account-specific questions after login.';
            }
            if (!$registrations) {
                return 'I do not see any event or course registrations linked to your account yet. Use Events to join a program, then Dashboard will show pending, paid, completed, and certificate status.';
            }
            $lines = array_map(fn($registration) => trim(($registration['event_slug'] ?? 'event') . ' - payment: ' . ($registration['payment_status'] ?? 'pending') . ', certificate: ' . ($registration['certificate_status'] ?? 'pending')), $registrations);
            return "Your account records:\n- " . implode("\n- ", $lines);
        }

        if ($events) {
            $event = $events[0];
            return 'GutConference can help with the portal owner, speakers, agenda, event/course signup, payment status, certificates, and support tickets. Current featured event: ' . ($event['name'] ?? 'Global Gut Summit') . ' on ' . ($event['date_label'] ?? 'the listed date') . (!empty($event['time_label']) ? ' at ' . $event['time_label'] : '') . '.';
        }

        return 'GutConference support can help with event enquiries, speaker details, founder profile, agenda, joined courses, certificates, and site improvement tickets.';
    }

    private function remoteAnswer(string $message, array $context, array $user): string {
        $focusedContext = $this->focusedContext($message, $context);
        $payload = json_encode([
            'systemInstruction' => [
                'parts' => [[
                    'text' => 'You are GutConference support. Use the provided CMS JSON as your source of truth. Be useful and specific: mention Dr. Praveen Jacob when asked who owns/hosts the portal, list speakers and agenda from context when asked, and include relevant page paths for booking/contact/events. Never expose secrets, scripts, code execution, admin-only data to non-admins, or other users. Guest and customer roles are read-only. Admin CMS requests must ask for missing schema fields and propose JSON changes only; do not claim changes were applied unless backend confirms. If asked about your model or internal implementation, say the portal uses a Google AI Studio powered assistant, but focus on helping with GutConference.',
                ]],
            ],
            'contents' => [[
                'role' => 'user',
                'parts' => [[
                    'text' => "Context JSON:\n" . json_encode($focusedContext, JSON_UNESCAPED_SLASHES) . "\n\nUser question:\n" . $message,
                ]],
            ]],
        ], JSON_UNESCAPED_SLASHES);
        return $this->router->answer('vision_language', json_decode($payload, true) ?: []);
    }

    private function focusedContext(string $message, array $context): array {
        $lower = strtolower($message);
        $site = $context['site'] ?? [];
        $focused = [
            'settings' => $context['settings'] ?? [],
            'role_rules' => $context['role_rules'] ?? [],
            'site' => [
                'pages' => $site['pages'] ?? [],
                'events' => $site['events'] ?? [],
                'owner' => $site['owner'] ?? [],
                'booking_help' => $site['booking_help'] ?? [],
                'support_scope' => $site['support_scope'] ?? '',
            ],
        ];
        if (str_contains($lower, 'speaker') || str_contains($lower, 'event') || str_contains($lower, 'about')) {
            $focused['site']['speakers'] = $site['speakers'] ?? [];
        }
        if (str_contains($lower, 'agenda') || str_contains($lower, 'schedule') || str_contains($lower, 'time') || str_contains($lower, 'speaker')) {
            $focused['site']['agenda'] = $site['agenda'] ?? [];
        }
        if (!empty($context['account_context'])) {
            $focused['account_context'] = $context['account_context'];
        } elseif (!empty($context['registrations'])) {
            $focused['registrations'] = $context['registrations'];
        }
        return $focused;
    }

    private function modelCandidates(string $value): array {
        return array_values(array_unique(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $value) ?: []))));
    }

    private function defaultGoogleModels(): string {
        return 'gemini-3.5-flash, gemini-3.1-pro-preview, gemini-3-flash-preview, gemini-3.1-flash-lite, gemma-4-31b-it, gemma-4-26b-a4b-it, gemma-4-12b-it, gemini-2.5-flash, gemini-2.5-flash-lite, gemini-2.5-pro';
    }

    private function roleRules(string $role): array {
        return match ($role) {
            'admin' => ['access' => 'admin', 'writes' => 'proposal_then_confirmation', 'allowed_write_surface' => 'schema_backed_json_only'],
            'customer' => ['access' => 'own_account_only', 'writes' => 'none'],
            default => ['access' => 'public_only', 'writes' => 'none'],
        };
    }

    private function looksLikeAdminCmsRequest(string $message): bool {
        $lower = strtolower($message);
        foreach (['create event', 'add event', 'edit event', 'add speaker', 'edit speaker', 'add reel', 'remove reel', 'article link', 'publisher', 'hero section'] as $needle) {
            if (str_contains($lower, $needle)) return true;
        }
        return false;
    }

    private function adminCmsGuidance(string $message): string {
        return "I can prepare schema-backed JSON changes for admin review. I will not edit PHP, CSS, scripts, or layouts from chat. Please provide the required fields for the record, and I will return a structured proposal for confirmation before any JSON file is changed.";
    }

    private function suggestedLinks(string $message, array $context): array {
        $lower = strtolower($message);
        $links = [];
        $events = $context['site']['events'] ?? [];
        $featured = $events[0] ?? null;
        if ($featured) {
            $links[] = ['label' => 'View Event', 'url' => (string)($featured['url'] ?? '/events')];
            $links[] = ['label' => 'Book This Event', 'url' => (string)($featured['booking_url'] ?? (($featured['url'] ?? '/events') . '#registration'))];
        }
        if (str_contains($lower, 'contact') || str_contains($lower, 'book') || str_contains($lower, 'support')) {
            $links[] = ['label' => 'Contact Team', 'url' => '/contact'];
        }
        if (str_contains($lower, 'event') || str_contains($lower, 'speaker') || str_contains($lower, 'agenda')) {
            $links[] = ['label' => 'All Events', 'url' => '/events'];
        }
        return array_values(array_unique($links, SORT_REGULAR));
    }

    private function adminProposal(string $message): array {
        $collection = $this->collectionFromPrompt($message);
        $schema = (new SchemaService())->collection($collection);
        $fields = $schema['fields'] ?? [];
        $required = array_keys(array_filter($fields, fn($field) => !empty($field['required'])));
        return [
            'mode' => 'review_then_apply',
            'collection' => $collection,
            'allowed' => in_array($collection, ['events','speakers','sessions','event_sections','publishers','venues'], true),
            'required_fields' => $required,
            'record' => [],
            'apply_endpoint' => '/support/admin/apply',
            'note' => 'Admin must confirm and submit schema-backed JSON to apply_endpoint before storage changes.',
        ];
    }

    private function collectionFromPrompt(string $message): string {
        $lower = strtolower($message);
        return match (true) {
            str_contains($lower, 'speaker') => 'speakers',
            str_contains($lower, 'agenda') || str_contains($lower, 'session') => 'sessions',
            str_contains($lower, 'article') || str_contains($lower, 'publisher') || str_contains($lower, 'news link') => 'publishers',
            str_contains($lower, 'section') || str_contains($lower, 'hero') => 'event_sections',
            str_contains($lower, 'venue') => 'venues',
            default => 'events',
        };
    }

    private function failureCounts(): array {
        if (!is_file($this->failureFile)) return [];
        return json_decode((string)file_get_contents($this->failureFile), true) ?: [];
    }

    private function recordModelResult(string $model, bool $success): void {
        $failures = $this->failureCounts();
        if ($success) {
            $failures[$model] = 0;
        } else {
            $failures[$model] = (int)($failures[$model] ?? 0) + 1;
        }
        file_put_contents($this->failureFile, json_encode($failures, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function shouldCreateTicket(string $message): bool {
        $lower = strtolower($message);
        foreach (['support ticket', 'improve', 'improvement', 'issue', 'problem', 'bug', 'not working', 'feedback'] as $needle) {
            if (str_contains($lower, $needle)) return true;
        }
        return false;
    }

    private function createTicket(string $message, array $user): array {
        $ticket = [
            'id' => uniqid('ticket_', true),
            'name' => (string)($user['name'] ?? 'Website visitor'),
            'email' => (string)($user['email'] ?? ''),
            'subject' => 'AI support request',
            'message' => $message,
            'status' => 'open',
            'created_at' => time(),
        ];
        $tickets = $this->store->read('support_tickets');
        $tickets[] = $ticket;
        $this->store->write('support_tickets', $tickets);
        return $ticket;
    }
}

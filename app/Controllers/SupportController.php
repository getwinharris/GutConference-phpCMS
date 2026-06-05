<?php
namespace App\Controllers;

use App\Services\{AuditLogService,AuthService,ResourceService,SchemaService,SupportAgentService};

final class SupportController extends BaseController {
    public function chat(): void {
        $message = trim((string)($_POST['message'] ?? ''));
        if ($message === '') {
            $this->jsonResponse(['answer' => 'Please type a question about speakers, events, agenda, certificates, or support.'], 422);
        }
        $user = (new AuthService())->user() ?? [];
        $this->jsonResponse((new SupportAgentService())->reply($message, $user));
    }

    public function adminApply(): void {
        (new AuthService())->requireAdmin();
        $collection = trim((string)($_POST['collection'] ?? ''));
        $record = json_decode((string)($_POST['record'] ?? '{}'), true) ?: [];
        $allowed = ['events','speakers','sessions','event_sections','publishers','venues'];
        if (!in_array($collection, $allowed, true)) {
            $this->jsonResponse(['ok' => false, 'error' => 'This collection is not editable by the assistant.'], 422);
        }
        $schema = (new SchemaService())->collection($collection);
        $fields = $schema['fields'] ?? [];
        foreach ($fields as $field => $spec) {
            if (!empty($spec['required']) && trim((string)($record[$field] ?? '')) === '') {
                $this->jsonResponse(['ok' => false, 'error' => 'Missing required field: ' . $field], 422);
            }
        }
        $saved = (new ResourceService($collection))->save($record);
        (new AuditLogService())->record('ai-confirmed-save', $collection, (string)($saved['id'] ?? ''), ['fields' => array_keys($saved)]);
        $this->jsonResponse(['ok' => true, 'record' => $saved]);
    }
}

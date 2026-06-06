<?php
namespace App\Services;

final class GeminiModelRouter {
    private string $failureFile;

    public function __construct() {
        $this->failureFile = storage_path('gemini-routing-failures.json');
    }

    public function answer(string $category, array $payload): string {
        $secrets = (new SecretService())->all();
        $key = trim((string)($secrets['google_ai_api_key'] ?? getenv('GOOGLE_AI_STUDIO_API_KEY') ?: ''));
        if ($key === '') return '';
        $endpointBase = rtrim(trim((string)($secrets['google_ai_endpoint'] ?? getenv('GOOGLE_AI_ENDPOINT_BASE') ?: 'https://generativelanguage.googleapis.com/v1beta')), '/');
        $tries = max(1, (int)($secrets['google_ai_retries'] ?? getenv('GOOGLE_AI_MODEL_RETRIES') ?: 3));
        foreach ($this->models($category) as $model) {
            for ($attempt = 1; $attempt <= $tries; $attempt++) {
                $answer = $this->generate($endpointBase, $key, $model, $payload);
                if ($answer !== '') {
                    $this->record($category, $model, true, '');
                    return $answer;
                }
                $this->record($category, $model, false, 'empty_or_error_response');
            }
        }
        return '';
    }

    public function diagnostics(): array {
        $secrets = (new SecretService())->all();
        $key = trim((string)($secrets['google_ai_api_key'] ?? getenv('GOOGLE_AI_STUDIO_API_KEY') ?: ''));
        if ($key === '') return ['configured' => false, 'models' => [], 'failures' => $this->failures()];
        $endpointBase = rtrim(trim((string)($secrets['google_ai_endpoint'] ?? getenv('GOOGLE_AI_ENDPOINT_BASE') ?: 'https://generativelanguage.googleapis.com/v1beta')), '/');
        $response = @file_get_contents($endpointBase . '/models?key=' . rawurlencode($key));
        $json = $response ? (json_decode($response, true) ?: []) : [];
        return [
            'configured' => true,
            'models' => array_map(fn($model) => [
                'name' => (string)($model['name'] ?? ''),
                'display_name' => (string)($model['displayName'] ?? ''),
                'methods' => $model['supportedGenerationMethods'] ?? [],
                'supports_generate_content' => in_array('generateContent', $model['supportedGenerationMethods'] ?? [], true),
            ], $json['models'] ?? []),
            'failures' => $this->failures(),
        ];
    }

    private function generate(string $endpointBase, string $key, string $model, array $payload): string {
        $url = $endpointBase . '/models/' . rawurlencode($model) . ':generateContent?key=' . rawurlencode($key);
        $response = @file_get_contents($url, false, stream_context_create(['http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($payload, JSON_UNESCAPED_SLASHES),
            'timeout' => 14,
            'ignore_errors' => true,
        ]]));
        if (!$response) return '';
        $json = json_decode($response, true) ?: [];
        if (!empty($json['error'])) return '';
        return trim((string)($json['candidates'][0]['content']['parts'][0]['text'] ?? ''));
    }

    private function models(string $category): array {
        $secrets = (new SecretService())->all();
        $env = match ($category) {
            'audio' => 'google_ai_audio_models',
            'tts' => 'google_ai_tts_models',
            default => 'google_ai_vision_language_models',
        };
        $envLegacy = match ($category) {
            'audio' => 'GOOGLE_AI_AUDIO_MODELS',
            'tts' => 'GOOGLE_AI_TTS_MODELS',
            default => 'GOOGLE_AI_VISION_LANGUAGE_MODELS',
        };
        $fallback = 'gemini-2.5-flash, gemini-2.5-flash-lite, gemini-2.5-pro';
        $value = (string)($secrets[$env] ?? getenv($envLegacy) ?: $fallback);
        return array_values(array_unique(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $value) ?: []))));
    }

    private function failures(): array {
        return is_file($this->failureFile) ? (json_decode((string)file_get_contents($this->failureFile), true) ?: []) : [];
    }

    private function record(string $category, string $model, bool $success, string $error): void {
        $failures = $this->failures();
        $key = $category . ':' . $model;
        $failures[$key] = [
            'category' => $category,
            'model' => $model,
            'failure_count' => $success ? 0 : (int)($failures[$key]['failure_count'] ?? 0) + 1,
            'last_error' => $success ? '' : $error,
            'last_tried_at' => date('c'),
        ];
        file_put_contents($this->failureFile, json_encode($failures, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

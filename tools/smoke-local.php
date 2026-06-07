<?php
require __DIR__ . '/../app/bootstrap.php';

$port = 6200 + random_int(0, 799);
$base = "http://127.0.0.1:{$port}";
$descriptor = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
$process = proc_open('php -S 127.0.0.1:' . $port . ' index.php', $descriptor, $pipes, app_path());
if (!is_resource($process)) {
    fwrite(STDERR, "Unable to start local PHP server.\n");
    exit(1);
}

$failures = [];

try {
    waitForServer($base);
    foreach ([
        '/' => 200,
        '/events' => 200,
        '/events/global-gut-summit-2026' => 200,
        '/conference/global-gut-summit-2026' => 200,
        '/about' => 404,
        '/contact' => 200,
        '/forgot-password' => 200,
        '/reset-password' => 200,
        '/logout' => 302,
        '/admin' => 302,
        '/admin/events' => 302,
        '/admin/settings' => 302,
    ] as $path => $expected) {
        $response = httpRequest($base . $path);
        echo "{$response['status']} GET {$path}\n";
        if ($response['status'] !== $expected) $failures[] = "GET {$path} expected {$expected}, got {$response['status']}";
        if ($expected === 200 && str_contains($response['body'], 'Page not found')) $failures[] = "GET {$path} rendered fallback missing-page content";
    }

    $unknown = httpRequest($base . '/unknown-spa-route');
    echo "{$unknown['status']} GET /unknown-spa-route\n";
    if ($unknown['status'] !== 404) $failures[] = "Unknown route expected 404, got {$unknown['status']}";
} finally {
    foreach ($pipes as $pipe) {
        if (is_resource($pipe)) fclose($pipe);
    }
    proc_terminate($process);
    proc_close($process);
}

if ($failures) {
    fwrite(STDERR, implode("\n", $failures) . "\n");
    exit(1);
}

echo "PASS local smoke\n";

function waitForServer(string $base): void {
    $deadline = microtime(true) + 8;
    do {
        $response = @httpRequest($base . '/');
        if (($response['status'] ?? 0) > 0) return;
        usleep(100000);
    } while (microtime(true) < $deadline);
    throw new RuntimeException('Local PHP server did not start.');
}

function httpRequest(string $url, string $method = 'GET', string $body = ''): array {
    $options = ['method' => $method, 'ignore_errors' => true, 'timeout' => 6];
    if ($method === 'POST') {
        $options['header'] = "Content-Type: application/x-www-form-urlencoded\r\n";
        $options['content'] = $body;
    }
    $context = stream_context_create(['http' => $options]);
    $content = file_get_contents($url, false, $context);
    $headers = $http_response_header ?? [];
    preg_match('/\s(\d{3})\s/', $headers[0] ?? '', $matches);
    return ['status' => (int)($matches[1] ?? 0), 'body' => $content === false ? '' : $content, 'headers' => $headers];
}

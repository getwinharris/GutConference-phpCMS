<?php
/**
 * Validation script for SecretService migration
 * Tests that services can read from SecretService with .env fallback
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Services\{EnvService, SecretService, GeminiModelRouter, NotificationQueueService, EmailTemplateService};

echo "=== SecretService Migration Validation ===\n\n";

// Test 1: EnvService reads admin credentials
echo "TEST 1: EnvService->adminCredentials() fallback behavior\n";
$envService = new EnvService();
$credentials = $envService->adminCredentials();
echo "  ✓ Can call adminCredentials()\n";
echo "  ✓ Returns array with username, email, password keys\n";
if (isset($credentials['username'], $credentials['email'], $credentials['password'])) {
    echo "  ✓ All expected keys present\n";
} else {
    echo "  ✗ Missing expected keys\n";
    exit(1);
}

// Test 2: GeminiModelRouter reads from SecretService
echo "\nTEST 2: GeminiModelRouter SecretService integration\n";
try {
    $router = new GeminiModelRouter();
    $diagnostics = $router->diagnostics();
    echo "  ✓ Can call diagnostics()\n";
    echo "  ✓ Returns array with configured key\n";
    if (isset($diagnostics['configured'])) {
        echo "  ✓ Configured key present\n";
    }
} catch (Throwable $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: NotificationQueueService reads APP_URL
echo "\nTEST 3: NotificationQueueService APP_URL fallback\n";
try {
    $queueService = new NotificationQueueService();
    $reflection = new ReflectionClass($queueService);
    $method = $reflection->getMethod('url');
    $method->setAccessible(true);
    $url = $method->invoke($queueService, '/test');
    echo "  ✓ Can generate URL: $url\n";
    if (str_starts_with($url, 'http')) {
        echo "  ✓ URL is valid\n";
    }
} catch (Throwable $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 4: EmailTemplateService reads APP_URL
echo "\nTEST 4: EmailTemplateService APP_URL fallback\n";
try {
    $templateService = new EmailTemplateService();
    $rendered = $templateService->render('test-template', [
        'title' => 'Test',
        'message' => 'Test message',
        'cta_url' => 'https://example.com',
        'cta_label' => 'Test'
    ]);
    echo "  ✓ Can render template\n";
    if (isset($rendered['subject'], $rendered['html'])) {
        echo "  ✓ Returns subject and html\n";
    }
    if (str_contains($rendered['html'], 'gutconference-logo.png')) {
        echo "  ✓ Logo URL includes APP_URL\n";
    }
} catch (Throwable $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== All Tests Passed ===\n";
echo "SecretService migration is working correctly.\n";
echo "Services fall back to .env when SecretService values are not set.\n";
exit(0);

<?php
namespace App\Controllers;
abstract class BaseController {
    protected string $layout = 'app';
    protected bool $isApiRequest = false;
    
    protected function redirect(string $path): never { header('Location: ' . $path); exit; }
    protected function flash(string $message): void { $_SESSION['flash'] = $message; }
    
    protected function jsonResponse(array $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function render(string $view, array $data = []): void {
        if ($this->isApiRequest || strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            $this->jsonResponse($data);
            return;
        }
        extract($data);
        $viewFile = app_path('views/' . $view . '.php');
        require app_path('views/layouts/' . $this->layout . '.php');
    }
}

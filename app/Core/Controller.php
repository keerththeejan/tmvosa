<?php

namespace App\Core;

class Controller
{
    protected function view(string $template, array $data = []): void
    {
        View::render($template, $data);
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function validateCsrf(): bool
    {
        $token = $_POST[App::config('app.csrf_token_name')] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return Security::validateCsrf($token);
    }
}

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
        $name = App::config('app.csrf_token_name');
        $token = $_POST[$name] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if ($token === '' && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }

        return Security::validateCsrf(is_string($token) ? $token : '');
    }

    protected function rejectCsrf(string $context = ''): void
    {
        $name = App::config('app.csrf_token_name');
        $hasSession = Session::has('csrf_token');
        $hasPost = isset($_POST[$name]) && $_POST[$name] !== '';
        $hasHeader = !empty($_SERVER['HTTP_X_CSRF_TOKEN']);

        error_log(sprintf(
            'CSRF rejected%s: uri=%s session_token=%s post_token=%s header_token=%s cookie_path=%s',
            $context !== '' ? " ({$context})" : '',
            $_SERVER['REQUEST_URI'] ?? '',
            $hasSession ? 'yes' : 'no',
            $hasPost ? 'yes' : 'no',
            $hasHeader ? 'yes' : 'no',
            App::sessionCookiePath()
        ));

        $this->json([
            'success' => false,
            'code' => 'csrf_failed',
            'message' => 'Your session has expired or the form security token is invalid. Please refresh the page and submit again.',
        ], 403);
    }

    protected function wantsJson(): bool
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return str_contains($accept, 'application/json');
    }

    protected function respond(array $payload, int $code = 200, ?string $redirectOnSuccess = null): void
    {
        if ($this->wantsJson()) {
            $this->json($payload, $code);
        }

        if (!empty($payload['success'])) {
            Session::flash('success', $payload['message'] ?? 'Success.');
            $this->redirect($redirectOnSuccess ?? App::baseUrl() . '/dashboard');
        }

        Session::flash('error', $payload['message'] ?? 'Request failed.');
        $this->redirect(App::baseUrl() . '/settings/password');
    }
}

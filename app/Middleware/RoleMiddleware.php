<?php

namespace App\Middleware;

use App\Core\Auth;

class RoleMiddleware
{
    private array $roles;

    public function __construct(array $roles = [])
    {
        $this->roles = $roles;
    }

    public function handle(): void
    {
        if (!Auth::check()) {
            $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
            header("Location: {$base}/login");
            exit;
        }

        if (!empty($this->roles) && !Auth::hasRole(...$this->roles)) {
            http_response_code(403);
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Access denied']);
            } else {
                echo 'Access denied';
            }
            exit;
        }
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

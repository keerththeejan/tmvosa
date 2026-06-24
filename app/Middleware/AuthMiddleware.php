<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::check()) {
            $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
            header("Location: {$base}/login");
            exit;
        }

        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $allowedPaths = [
            $base . '/settings/password',
            $base . '/logout',
        ];

        foreach ($allowedPaths as $path) {
            if ($uri === $path || str_starts_with($uri, $path . '?')) {
                return;
            }
        }

        $user = Auth::user();
        if ($user && !empty($user['force_password_change'])) {
            header('Location: ' . $base . '/settings/password?required=1');
            exit;
        }
    }
}

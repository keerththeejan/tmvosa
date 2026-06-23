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
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Session;
use App\Models\Member;
use App\Models\Application;
use App\Models\Payment;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect(\App\Core\App::routeUrl('dashboard'));
        }
        $this->view('auth/login', ['pageScript' => 'login.js']);
    }

    public function login(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $username = Security::sanitize($this->input('username', ''));
        $password = $this->input('password', '');

        if (Auth::attempt($username, $password)) {
            $this->json(['success' => true, 'redirect' => \App\Core\App::routeUrl('dashboard')]);
        }

        $this->json(['success' => false, 'message' => 'Invalid credentials.']);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect(\App\Core\App::routeUrl('login'));
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\Mailer;

class PasswordController extends Controller
{
    public function form(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect(\App\Core\App::routeUrl('login'));
        }

        $this->view('settings/password', [
            'passwordChangedAt' => $user['password_changed_at'] ?? null,
            'forceRequired' => !empty($user['force_password_change']),
        ]);
    }

    public function update(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $user = Auth::user();
        if (!$user) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $currentPassword = $this->input('current_password', '');
        $newPassword = $this->input('new_password', '');
        $confirmPassword = $this->input('confirm_password', '');

        if (!Security::verifyPassword($currentPassword, $user['password'])) {
            $this->json(['success' => false, 'message' => 'Current password is incorrect.']);
        }

        if (strlen($newPassword) < 8) {
            $this->json(['success' => false, 'message' => 'New password must be at least 8 characters.']);
        }

        if ($newPassword !== $confirmPassword) {
            $this->json(['success' => false, 'message' => 'New password and confirmation do not match.']);
        }

        if (Security::verifyPassword($newPassword, $user['password'])) {
            $this->json(['success' => false, 'message' => 'New password must be different from the current password.']);
        }

        User::updatePassword((int) $user['id'], Security::hashPassword($newPassword));
        AuditLog::log('password_changed', 'users', (int) $user['id']);

        if (!empty($user['email'])) {
            Mailer::sendTemplate($user['email'], 'password_changed_confirmation', [
                'full_name' => $user['full_name'],
                'changed_at' => date('d M Y, h:i A'),
            ], $user['full_name']);
        }

        $this->json([
            'success' => true,
            'message' => 'Password updated successfully.',
            'redirect' => \App\Core\App::routeUrl('dashboard'),
        ]);
    }
}

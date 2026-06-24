<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Session;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\Mailer;

class PasswordController extends Controller
{
    public function form(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect(App::routeUrl('login'));
        }

        if (isset($_GET['current_password']) || isset($_GET['new_password']) || isset($_GET['confirm_password'])) {
            Session::flash(
                'error',
                'Passwords in the address bar are not applied. Sign in, fill in the form below, and click Update Password.'
            );
        }

        $this->view('settings/password', [
            'passwordChangedAt' => $user['password_changed_at'] ?? null,
            'forceRequired' => !empty($user['force_password_change']),
            'pageScript' => 'password.js',
        ]);
    }

    public function update(): void
    {
        if (!$this->validateCsrf()) {
            $this->respond(['success' => false, 'message' => 'Invalid request. Please refresh the page and try again.'], 403);
        }

        $user = Auth::user();
        if (!$user) {
            $this->respond(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $currentPassword = $this->input('current_password', '');
        $newPassword = $this->input('new_password', '');
        $confirmPassword = $this->input('confirm_password', '');

        if (!Security::verifyPassword($currentPassword, $user['password'])) {
            $this->respond(['success' => false, 'message' => 'Current password is incorrect.']);
        }

        if (strlen($newPassword) < 8) {
            $this->respond(['success' => false, 'message' => 'New password must be at least 8 characters.']);
        }

        if ($newPassword !== $confirmPassword) {
            $this->respond(['success' => false, 'message' => 'New password and confirmation do not match.']);
        }

        if (Security::verifyPassword($newPassword, $user['password'])) {
            $this->respond(['success' => false, 'message' => 'New password must be different from the current password.']);
        }

        try {
            User::updatePassword((int) $user['id'], Security::hashPassword($newPassword));
        } catch (\Throwable $e) {
            error_log('Password update failed: ' . $e->getMessage());
            $this->respond([
                'success' => false,
                'message' => 'Could not save password. Ensure database migration 003_password_fields.sql has been applied.',
            ]);
        }

        AuditLog::log('password_changed', 'users', (int) $user['id']);

        if (!empty($user['email'])) {
            try {
                Mailer::sendTemplate($user['email'], 'password_changed_confirmation', [
                    'full_name' => $user['full_name'],
                    'changed_at' => date('d M Y, h:i A'),
                ], $user['full_name']);
            } catch (\Throwable $e) {
                error_log('Password change email failed: ' . $e->getMessage());
            }
        }

        $redirect = App::baseUrl() . '/dashboard';
        $this->respond([
            'success' => true,
            'message' => 'Password updated successfully.',
            'redirect' => $redirect,
        ], 200, $redirect);
    }
}

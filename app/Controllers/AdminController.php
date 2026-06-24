<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Models\User;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Models\EmailTemplate;
use App\Models\Member;
use App\Core\Database;
use App\Helpers\Mailer;

class AdminController extends Controller
{
    public function users(): void
    {
        $users = User::getAll();
        $roles = Database::fetchAll("SELECT * FROM roles");
        $this->view('users/index', compact('users', 'roles'));
    }

    public function createUser(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $data = [
            'role_id' => (int) $this->input('role_id'),
            'username' => Security::sanitize($this->input('username', '')),
            'email' => filter_var($this->input('email', ''), FILTER_SANITIZE_EMAIL),
            'password' => Security::hashPassword($this->input('password', '')),
            'full_name' => Security::sanitize($this->input('full_name', '')),
            'mobile' => Security::sanitize($this->input('mobile', '')),
        ];

        $id = User::create($data);
        AuditLog::log('create_user', 'users', $id);
        $this->json(['success' => true, 'message' => 'User created.']);
    }

    public function resetPassword(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        if (!Auth::hasRole('super_admin')) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $userId = (int) $id;
        $target = User::findById($userId);
        if (!$target) {
            $this->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $newPassword = $this->input('new_password', '');
        if (strlen($newPassword) < 8) {
            $this->json(['success' => false, 'message' => 'Password must be at least 8 characters.']);
        }

        $forceChange = (bool) $this->input('force_password_change', false);
        User::updatePassword($userId, Security::hashPassword($newPassword));
        if ($forceChange) {
            User::update($userId, ['force_password_change' => 1]);
        }

        AuditLog::log('password_reset', 'users', $userId, null, [
            'reset_by' => Auth::id(),
            'force_password_change' => $forceChange ? 1 : 0,
        ]);

        if (!empty($target['email'])) {
            Mailer::sendTemplate($target['email'], 'password_reset', [
                'full_name' => $target['full_name'],
                'temporary_password' => $newPassword,
            ], $target['full_name']);
        }

        $this->json(['success' => true, 'message' => 'Password reset successfully.']);
    }

    public function forcePasswordChange(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        if (!Auth::hasRole('super_admin')) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $userId = (int) $id;
        $target = User::findById($userId);
        if (!$target) {
            $this->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $force = (bool) $this->input('force', false);
        User::update($userId, ['force_password_change' => $force ? 1 : 0]);

        AuditLog::log('profile_updated', 'users', $userId, null, [
            'force_password_change' => $force ? 1 : 0,
            'updated_by' => Auth::id(),
        ]);

        $this->json([
            'success' => true,
            'message' => $force ? 'User must change password on next login.' : 'Forced password change removed.',
        ]);
    }

    public function passwordLogs(): void
    {
        if (!Auth::hasRole('super_admin')) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $page = (int) $this->input('page', 1);
        $logs = AuditLog::getByActions(['password_changed', 'password_reset'], $page);
        $this->view('settings/password-logs', compact('logs'));
    }

    public function settings(): void
    {
        $settings = Setting::getAll();
        unset($settings['email']);
        $this->view('settings/index', compact('settings') + ['pageScript' => 'settings-admin.js']);
    }

    public function emailSettings(): void
    {
        if (!Auth::hasRole('super_admin')) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $emailSettings = Mailer::getSettings();
        $mailDiagnostics = Mailer::getDiagnostics();
        $this->view('settings/email', [
            'emailSettings' => $emailSettings,
            'mailDiagnostics' => $mailDiagnostics,
            'pageScript' => 'email-settings.js',
        ]);
    }

    public function updateEmailSettings(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        if (!Auth::hasRole('super_admin')) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $allowed = ['smtp_host', 'smtp_port', 'smtp_encryption', 'from_name', 'from_email', 'admin_notification_email', 'smtp_username'];
        foreach ($allowed as $key) {
            $value = $this->input($key);
            if ($value !== null && $value !== '') {
                Setting::set($key, Security::sanitize($value));
            }
        }

        AuditLog::log('update_settings', 'email_settings');
        $this->json(['success' => true, 'message' => 'Email settings saved. SMTP password must be set in .env (SMTP_PASSWORD).']);
    }

    public function testEmail(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        if (!Auth::hasRole('super_admin')) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $to = filter_var($this->input('test_email', ''), FILTER_VALIDATE_EMAIL);
        if (!$to) {
            $to = Auth::user()['email'] ?? '';
        }
        if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Please provide a valid test email address.']);
            return;
        }

        try {
            $result = Mailer::sendTest($to);
        } catch (\Throwable $e) {
            error_log('Email test exception: ' . $e->getMessage());
            $this->json([
                'success' => false,
                'message' => 'Email test error: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
            return;
        }

        AuditLog::log('email_test', 'email_settings', null, null, ['to' => $to, 'success' => $result['success']]);
        $this->json($result, $result['success'] ? 200 : 500);
    }

    public function emailTemplates(): void
    {
        if (!Auth::hasRole('super_admin')) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $templates = EmailTemplate::getAll();
        $this->view('settings/email-templates', compact('templates') + ['pageScript' => 'email-templates.js']);
    }

    public function updateEmailTemplate(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        if (!Auth::hasRole('super_admin')) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $template = EmailTemplate::findById((int) $id);
        if (!$template) {
            $this->json(['success' => false, 'message' => 'Template not found.'], 404);
        }

        $subject = Security::sanitize($this->input('subject', ''));
        $body = $this->input('body', '');
        $isActive = (bool) $this->input('is_active', true);

        if ($subject === '' || trim($body) === '') {
            $this->json(['success' => false, 'message' => 'Subject and body are required.']);
        }

        EmailTemplate::update((int) $id, [
            'subject' => $subject,
            'body' => $body,
            'is_active' => $isActive ? 1 : 0,
        ]);

        AuditLog::log('update_email_template', 'email_templates', (int) $id);
        $this->json(['success' => true, 'message' => 'Email template updated.']);
    }

    public function sendExpiryReminders(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        if (!Auth::hasRole('super_admin')) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $days = (int) $this->input('days', 30);
        $sent = self::dispatchExpiryReminders($days);
        AuditLog::log('send_expiry_reminders', 'members', null, null, ['sent' => $sent, 'days' => $days]);
        $this->json(['success' => true, 'message' => "Sent {$sent} membership expiry reminder(s).", 'sent' => $sent]);
    }

    public static function dispatchExpiryReminders(int $days = 30): int
    {
        $members = Member::getExpiringWithinDays($days);
        $sent = 0;
        foreach ($members as $member) {
            if (empty($member['email'])) {
                continue;
            }
            $ok = Mailer::sendTemplate($member['email'], 'membership_expiry_reminder', [
                'full_name' => $member['full_name_english'],
                'membership_number' => $member['membership_number'],
                'expiry_date' => date('d M Y', strtotime($member['membership_expiry_date'])),
            ], $member['full_name_english'], [
                'related_type' => 'members',
                'related_id' => (int) $member['id'],
            ]);
            if ($ok) {
                $sent++;
            }
        }
        return $sent;
    }

    public function updateSettings(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $settings = $_POST['settings'] ?? [];
        $emailKeys = ['smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_username', 'smtp_password', 'from_email', 'from_name', 'admin_notification_email'];
        foreach ($settings as $key => $value) {
            if (in_array($key, $emailKeys, true)) {
                continue;
            }
            Setting::set($key, Security::sanitize($value));
        }

        AuditLog::log('update_settings', 'settings');
        $this->json(['success' => true, 'message' => 'Settings updated.']);
    }

    public function auditLogs(): void
    {
        $page = (int) $this->input('page', 1);
        $logs = AuditLog::getAll($page);
        $this->view('settings/audit', compact('logs'));
    }

    public function backup(): void
    {
        $config = require \App\Core\App::basePath() . '/config/database.php';
        $filename = 'backup_' . date('Y-m-d_His') . '.sql';
        $path = \App\Core\App::basePath() . '/storage/backups/' . $filename;

        $tables = Database::fetchAll("SHOW TABLES");
        $output = "-- OSA Database Backup\n-- Date: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            $create = Database::fetch("SHOW CREATE TABLE `{$tableName}`");
            $output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $output .= $create['Create Table'] . ";\n\n";

            $rows = Database::fetchAll("SELECT * FROM `{$tableName}`");
            foreach ($rows as $row) {
                $values = array_map(fn($v) => $v === null ? 'NULL' : "'" . addslashes($v) . "'", $row);
                $output .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
            }
            $output .= "\n";
        }

        file_put_contents($path, $output);
        AuditLog::log('database_backup', 'system');

        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($path);
        exit;
    }
}

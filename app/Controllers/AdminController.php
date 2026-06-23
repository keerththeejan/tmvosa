<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Models\User;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Core\Database;

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

    public function settings(): void
    {
        $settings = Setting::getAll();
        $this->view('settings/index', compact('settings'));
    }

    public function updateSettings(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $settings = $_POST['settings'] ?? [];
        foreach ($settings as $key => $value) {
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

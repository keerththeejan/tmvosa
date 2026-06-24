<?php

namespace App\Helpers;

use App\Core\App;
use App\Core\Database;
use App\Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private static ?string $lastError = null;

    public static function getLastError(): ?string
    {
        return self::$lastError;
    }

    public static function send(string $to, string $subject, string $body, ?string $toName = null, array $logContext = []): bool
    {
        self::$lastError = null;

        if (!class_exists(PHPMailer::class)) {
            self::$lastError = 'PHPMailer is not installed. Upload the vendor/ folder or run composer install on the server.';
            self::recordSendLog($to, $subject, false, self::$lastError, $logContext);
            return false;
        }

        if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            self::$lastError = 'Invalid recipient email address.';
            self::recordSendLog($to, $subject, false, self::$lastError, $logContext);
            return false;
        }

        $settings = self::getSettings();
        $configError = self::validateConfiguration($settings);
        if ($configError) {
            self::$lastError = $configError;
            self::recordSendLog($to, $subject, false, self::$lastError, $logContext);
            return false;
        }

        $profiles = self::connectionProfiles($settings);
        $lastHost = '';

        foreach ($profiles as $profile) {
            $lastHost = $profile['host'] . ':' . $profile['port'];
            $mail = new PHPMailer(true);
            try {
                self::configureMailer($mail, $settings, $profile);
                $mail->setFrom($settings['from_email'], $settings['from_name']);
                $mail->addAddress($to, $toName ?? '');
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = self::wrapHtmlBody($body, $settings['from_name']);
                $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));

                if ($mail->send()) {
                    self::recordSendLog($to, $subject, true, 'OK via ' . $lastHost, $logContext);
                    return true;
                }

                self::$lastError = $mail->ErrorInfo ?: 'Unknown mail error.';
            } catch (Exception $e) {
                self::$lastError = $e->getMessage();
                if (!self::shouldTryNextProfile(self::$lastError)) {
                    break;
                }
            }
        }

        if (self::$lastError) {
            error_log('Mail error (' . $lastHost . '): ' . self::$lastError);
        }

        self::recordSendLog($to, $subject, false, self::$lastError ?? 'Send failed.', $logContext);

        return false;
    }

    public static function sendTemplate(string $to, string $templateName, array $vars = [], ?string $toName = null, array $logContext = []): bool
    {
        $template = Database::fetch(
            "SELECT * FROM email_templates WHERE name = ? AND is_active = 1",
            [$templateName]
        );

        if (!$template) {
            self::$lastError = 'Email template not found: ' . $templateName;
            error_log(self::$lastError);
            self::recordSendLog($to, $templateName, false, self::$lastError, array_merge($logContext, [
                'template' => $templateName,
            ]));
            return false;
        }

        $subject = self::replaceVars($template['subject'], $vars);
        $body = self::replaceVars($template['body'], $vars);

        return self::send($to, $subject, $body, $toName, array_merge($logContext, [
            'template' => $templateName,
            'subject' => $subject,
        ]));
    }

    private static function recordSendLog(
        string $to,
        string $subject,
        bool $sent,
        ?string $response,
        array $context
    ): void {
        \App\Models\EmailLog::record(
            $to,
            $sent,
            $response,
            $context['template'] ?? null,
            $context['subject'] ?? $subject,
            $context['related_type'] ?? null,
            isset($context['related_id']) ? (int) $context['related_id'] : null
        );
    }

    public static function sendTest(string $to): array
    {
        $settings = self::getSettings();
        $configError = self::validateConfiguration($settings);
        if ($configError) {
            return ['success' => false, 'message' => $configError, 'error' => $configError];
        }

        $subject = 'OSA Email Test - ' . date('d M Y H:i');
        $body = '<p>This is a test email from the OSA Membership Management System.</p>'
            . '<p><strong>SMTP Host:</strong> ' . htmlspecialchars($settings['smtp_host']) . '<br>'
            . '<strong>Port:</strong> ' . htmlspecialchars((string) $settings['smtp_port']) . '<br>'
            . '<strong>Encryption:</strong> ' . htmlspecialchars($settings['smtp_encryption']) . '</p>'
            . '<p>If you received this message, email configuration is working correctly.</p>';

        $sent = self::send($to, $subject, $body);
        $error = self::humanizeError(self::$lastError ?? 'Failed to send test email.', $settings);

        return [
            'success' => $sent,
            'message' => $sent ? 'Test email sent successfully to ' . $to . '.' : $error,
            'error' => $sent ? null : $error,
        ];
    }

    public static function getDiagnostics(): array
    {
        $settings = self::getSettings();
        $envPath = App::basePath() . '/.env';

        return [
            'env_file_exists' => file_exists($envPath),
            'env_file_readable' => is_readable($envPath),
            'vendor_installed' => file_exists(App::basePath() . '/vendor/autoload.php'),
            'phpmailer_installed' => class_exists(PHPMailer::class),
            'smtp_password_set' => !empty($settings['smtp_password']),
            'openssl_loaded' => extension_loaded('openssl'),
            'smtp_host' => $settings['smtp_host'],
            'smtp_port' => (string) $settings['smtp_port'],
            'smtp_encryption' => $settings['smtp_encryption'],
            'smtp_username' => $settings['smtp_username'],
            'from_email' => $settings['from_email'],
            'config_error' => self::validateConfiguration($settings),
            'cpanel_hint' => self::isLikelyCpanel() ? 'On cPanel, try SMTP host localhost with port 587 (TLS) if mail.vkitnet.info fails.' : null,
        ];
    }

    public static function notifyAdmin(string $templateName, array $vars = []): bool
    {
        $settings = self::getSettings();
        $adminEmail = $settings['admin_notification_email'] ?? $settings['from_email'];
        if (empty($adminEmail)) {
            self::$lastError = 'Admin notification email is not configured.';
            return false;
        }
        return self::sendTemplate($adminEmail, $templateName, $vars, 'OSA Admin');
    }

    public static function getSettings(): array
    {
        $db = [];
        try {
            $rows = Database::fetchAll("SELECT setting_key, setting_value FROM settings WHERE setting_group = 'email'");
            foreach ($rows as $row) {
                $db[$row['setting_key']] = $row['setting_value'];
            }
        } catch (\Throwable $e) {
            error_log('Mail settings DB read failed: ' . $e->getMessage());
        }

        $defaultFromName = "Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students' Association";

        return [
            'smtp_host' => $db['smtp_host'] ?? self::env('SMTP_HOST', 'mail.vkitnet.info'),
            'smtp_port' => (int) ($db['smtp_port'] ?? self::env('SMTP_PORT', '465')),
            'smtp_encryption' => strtolower($db['smtp_encryption'] ?? self::env('SMTP_ENCRYPTION', 'ssl')),
            'smtp_username' => self::env('SMTP_USERNAME', $db['smtp_username'] ?? 'tmvosa@vkitnet.info'),
            'smtp_password' => self::env('SMTP_PASSWORD', ''),
            'from_email' => $db['from_email'] ?? self::env('MAIL_FROM_ADDRESS', 'tmvosa@vkitnet.info'),
            'from_name' => $db['from_name'] ?? self::env('MAIL_FROM_NAME', $defaultFromName),
            'admin_notification_email' => $db['admin_notification_email'] ?? self::env('ADMIN_EMAIL', 'tmvosa@vkitnet.info'),
        ];
    }

    private static function env(string $key, string $default = ''): string
    {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return (string) $_ENV[$key];
        }
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return (string) $_SERVER[$key];
        }
        $value = getenv($key);
        if ($value === false || $value === null || $value === '') {
            return $default;
        }
        return (string) $value;
    }

    private static function validateConfiguration(array $settings): ?string
    {
        if (empty($settings['smtp_host'])) {
            return 'SMTP host is not configured.';
        }
        if (empty($settings['smtp_username'])) {
            return 'SMTP username is not configured. Set SMTP_USERNAME in .env on the server.';
        }
        if (empty($settings['smtp_password'])) {
            return 'SMTP password is not configured. Upload .env to the server and set SMTP_PASSWORD for tmvosa@vkitnet.info.';
        }
        if (in_array($settings['smtp_password'], ['your_smtp_password_here', 'your_password', 'changeme'], true)) {
            return 'SMTP_PASSWORD in .env is still a placeholder. Set the real mailbox password on the server.';
        }
        if (empty($settings['from_email'])) {
            return 'Sender email (from_email) is not configured.';
        }
        if (!extension_loaded('openssl')) {
            return 'PHP openssl extension is required for SMTP on port 465/587. Enable it in cPanel MultiPHP INI Editor.';
        }
        return null;
    }

    private static function connectionProfiles(array $settings): array
    {
        $primary = [
            'host' => trim($settings['smtp_host']),
            'port' => (int) $settings['smtp_port'],
            'encryption' => strtolower($settings['smtp_encryption']),
        ];

        $cpanelLocal = ['host' => 'localhost', 'port' => 587, 'encryption' => 'tls'];
        $host = strtolower($primary['host']);

        if (self::isLikelyCpanel()) {
            // On cPanel, localhost:587 (TLS) is usually the most reliable option.
            $profiles = [$cpanelLocal, $primary];
        } else {
            $profiles = [$primary];
        }

        if (self::isLikelyCpanel() || str_contains($host, 'vkitnet.info')) {
            $profiles[] = ['host' => 'localhost', 'port' => 465, 'encryption' => 'ssl'];
            $profiles[] = ['host' => '127.0.0.1', 'port' => 587, 'encryption' => 'tls'];
        }

        if ($host !== 'mail.vkitnet.info') {
            $profiles[] = ['host' => 'mail.vkitnet.info', 'port' => 465, 'encryption' => 'ssl'];
        }

        $unique = [];
        foreach ($profiles as $profile) {
            $key = $profile['host'] . ':' . $profile['port'] . ':' . $profile['encryption'];
            $unique[$key] = $profile;
        }

        return array_values($unique);
    }

    private static function configureMailer(PHPMailer $mail, array $settings, array $profile): void
    {
        $mail->isSMTP();
        $mail->Host = $profile['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $settings['smtp_username'];
        $mail->Password = $settings['smtp_password'];
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 45;
        $mail->SMTPKeepAlive = false;

        self::applyEncryption($mail, $profile);
        self::applySslOptions($mail);
    }

    private static function applyEncryption(PHPMailer $mail, array $profile): void
    {
        $encryption = $profile['encryption'];
        $mail->Port = (int) $profile['port'];

        if (in_array($encryption, ['ssl', 'smtps'], true)) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            return;
        }

        if (in_array($encryption, ['tls', 'starttls'], true)) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            return;
        }

        $mail->SMTPSecure = '';
        $mail->SMTPAutoTLS = false;
    }

    private static function applySslOptions(PHPMailer $mail): void
    {
        $verify = strtolower(self::env('SMTP_SSL_VERIFY', 'false'));
        if (in_array($verify, ['1', 'true', 'yes'], true)) {
            return;
        }

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
    }

    private static function shouldTryNextProfile(string $message): bool
    {
        $message = strtolower($message);
        return self::isConnectionError($message)
            || str_contains($message, 'authenticate')
            || str_contains($message, 'could not instantiate mail');
    }

    private static function isConnectionError(string $message): bool
    {
        $message = strtolower($message);
        return str_contains($message, 'connect')
            || str_contains($message, '10060')
            || str_contains($message, 'timed out')
            || str_contains($message, 'could not connect')
            || str_contains($message, 'connection refused');
    }

    private static function isLikelyCpanel(): bool
    {
        return isset($_SERVER['DOCUMENT_ROOT'])
            && (str_contains($_SERVER['DOCUMENT_ROOT'], 'public_html')
                || str_contains($_SERVER['DOCUMENT_ROOT'], 'home/'));
    }

    private static function humanizeError(string $error, array $settings): string
    {
        if (stripos($error, 'authenticate') !== false) {
            return 'SMTP authentication failed. On the server .env file, set SMTP_USERNAME=tmvosa@vkitnet.info and the correct SMTP_PASSWORD (use quotes if the password contains special characters).';
        }

        if (self::isConnectionError($error)) {
            return 'Could not connect to SMTP server (' . $settings['smtp_host'] . ':' . $settings['smtp_port'] . '). '
                . 'On cPanel, edit .env and try: SMTP_HOST=localhost, SMTP_PORT=587, SMTP_ENCRYPTION=tls. '
                . 'Technical detail: ' . $error;
        }

        return $error;
    }

    private static function replaceVars(string $text, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $text = str_replace('{{' . $key . '}}', (string) $value, $text);
        }
        return $text;
    }

    private static function wrapHtmlBody(string $body, string $fromName): string
    {
        if (stripos($body, '<html') !== false) {
            return $body;
        }

        return '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:Arial,sans-serif;line-height:1.6;color:#333;max-width:600px;margin:0 auto;padding:20px;">'
            . $body
            . '<hr style="border:none;border-top:1px solid #eee;margin:24px 0;">'
            . '<p style="font-size:12px;color:#666;">' . htmlspecialchars($fromName) . '<br>'
            . '<a href="mailto:tmvosa@vkitnet.info">tmvosa@vkitnet.info</a> | 077 887 0135</p>'
            . '</body></html>';
    }
}

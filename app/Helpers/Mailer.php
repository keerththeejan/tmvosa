<?php

namespace App\Helpers;

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

    public static function send(string $to, string $subject, string $body, ?string $toName = null): bool
    {
        self::$lastError = null;

        if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            self::$lastError = 'Invalid recipient email address.';
            return false;
        }

        $settings = self::getSettings();
        $configError = self::validateConfiguration($settings);
        if ($configError) {
            self::$lastError = $configError;
            return false;
        }

        $hosts = self::resolveSmtpHosts($settings['smtp_host']);
        $lastException = null;

        foreach ($hosts as $host) {
            $mail = new PHPMailer(true);
            try {
                self::configureMailer($mail, $settings, $host);
                $mail->setFrom($settings['from_email'], $settings['from_name']);
                $mail->addAddress($to, $toName ?? '');
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = self::wrapHtmlBody($body, $settings['from_name']);
                $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));

                if ($mail->send()) {
                    return true;
                }

                self::$lastError = $mail->ErrorInfo ?: 'Unknown mail error.';
            } catch (Exception $e) {
                $lastException = $e;
                self::$lastError = $e->getMessage();
                if (!self::isConnectionError(self::$lastError)) {
                    break;
                }
            }
        }

        if ($lastException && self::$lastError) {
            error_log('Mail error: ' . self::$lastError);
        }

        return false;
    }

    public static function sendTemplate(string $to, string $templateName, array $vars = [], ?string $toName = null): bool
    {
        $template = Database::fetch(
            "SELECT * FROM email_templates WHERE name = ? AND is_active = 1",
            [$templateName]
        );

        if (!$template) {
            self::$lastError = 'Email template not found: ' . $templateName;
            error_log(self::$lastError);
            return false;
        }

        $subject = self::replaceVars($template['subject'], $vars);
        $body = self::replaceVars($template['body'], $vars);

        return self::send($to, $subject, $body, $toName);
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
        $error = self::$lastError ?? 'Failed to send test email.';

        if (!$sent && stripos($error, 'authenticate') !== false) {
            $error = 'SMTP authentication failed. Verify SMTP_USERNAME and SMTP_PASSWORD in your .env file.';
        } elseif (!$sent && self::isConnectionError($error)) {
            $error = 'Could not connect to SMTP server. Use host mail.vkitnet.info on port 465 (SSL). '
                . 'Note: some providers block outbound SMTP from localhost — test again on the live server.';
        }

        return [
            'success' => $sent,
            'message' => $sent ? 'Test email sent successfully to ' . $to . '.' : $error,
            'error' => $sent ? null : $error,
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
        $value = $_ENV[$key] ?? getenv($key);
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
            return 'SMTP username is not configured. Set SMTP_USERNAME in .env.';
        }
        if (empty($settings['smtp_password'])) {
            return 'SMTP password is not configured. Set SMTP_PASSWORD in your .env file.';
        }
        if (in_array($settings['smtp_password'], ['your_smtp_password_here', 'your_password', 'changeme'], true)) {
            return 'SMTP_PASSWORD in .env is still a placeholder. Set the real mailbox password for tmvosa@vkitnet.info.';
        }
        if (empty($settings['from_email'])) {
            return 'Sender email (from_email) is not configured.';
        }
        return null;
    }

    private static function configureMailer(PHPMailer $mail, array $settings, string $host): void
    {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $settings['smtp_username'];
        $mail->Password = $settings['smtp_password'];
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 30;
        $mail->SMTPKeepAlive = false;

        self::applyEncryption($mail, $settings);
        self::applySslOptions($mail);
    }

    private static function applyEncryption(PHPMailer $mail, array $settings): void
    {
        $encryption = $settings['smtp_encryption'];
        $mail->Port = (int) $settings['smtp_port'];

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

    private static function resolveSmtpHosts(string $host): array
    {
        $host = trim($host);
        $hosts = [$host];

        if (!str_starts_with($host, 'mail.') && !str_starts_with($host, 'smtp.')) {
            $hosts[] = 'mail.' . $host;
        }

        if ($host === 'vkitnet.info') {
            $hosts = ['mail.vkitnet.info', 'vkitnet.info'];
        }

        return array_values(array_unique($hosts));
    }

    private static function isConnectionError(string $message): bool
    {
        $message = strtolower($message);
        return str_contains($message, 'connect')
            || str_contains($message, '10060')
            || str_contains($message, 'timed out')
            || str_contains($message, 'could not connect');
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

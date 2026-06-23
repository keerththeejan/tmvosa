<?php

namespace App\Helpers;

use App\Core\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send(string $to, string $subject, string $body): bool
    {
        $settings = self::getSettings();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $settings['smtp_host'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $settings['smtp_username'] ?? '';
            $mail->Password = $settings['smtp_password'] ?? '';
            $mail->SMTPSecure = $settings['smtp_encryption'] ?? 'tls';
            $mail->Port = (int) ($settings['smtp_port'] ?? 587);
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(
                $settings['from_email'] ?? 'noreply@osa-alumni.org',
                $settings['from_name'] ?? 'OSA Alumni'
            );
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log('Mail error: ' . $e->getMessage());
            return false;
        }
    }

    public static function sendTemplate(string $to, string $templateName, array $vars = []): bool
    {
        $template = Database::fetch(
            "SELECT * FROM email_templates WHERE name = ? AND is_active = 1",
            [$templateName]
        );

        if (!$template) {
            return false;
        }

        $subject = $template['subject'];
        $body = $template['body'];

        foreach ($vars as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }

        return self::send($to, $subject, $body);
    }

    private static function getSettings(): array
    {
        $rows = Database::fetchAll("SELECT setting_key, setting_value FROM settings WHERE setting_group = 'email'");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }
}

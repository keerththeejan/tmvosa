<?php

namespace App\Models;

use App\Core\Database;

class EmailLog
{
    public static function record(
        string $recipientEmail,
        bool $sent,
        ?string $smtpResponse = null,
        ?string $templateName = null,
        ?string $subject = null,
        ?string $relatedType = null,
        ?int $relatedId = null
    ): void {
        try {
            Database::insert('email_logs', [
                'recipient_email' => $recipientEmail,
                'template_name' => $templateName,
                'subject' => $subject,
                'related_type' => $relatedType,
                'related_id' => $relatedId,
                'email_sent' => $sent ? 1 : 0,
                'smtp_response' => $smtpResponse,
                'sent_at' => $sent ? date('Y-m-d H:i:s') : null,
            ]);
        } catch (\Throwable $e) {
            error_log('email_logs insert failed: ' . $e->getMessage());
        }
    }
}

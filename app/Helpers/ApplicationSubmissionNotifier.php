<?php

namespace App\Helpers;

class ApplicationSubmissionNotifier
{
    public static function send(array $data): void
    {
        try {
            if (!empty($data['email'])) {
                $sent = Mailer::sendTemplate($data['email'], 'application_received', [
                    'full_name' => $data['full_name_english'],
                    'application_number' => $data['application_number'],
                ], $data['full_name_english'], [
                    'related_type' => 'member_applications',
                    'related_id' => $data['application_id'] ?? null,
                ]);
                if (!$sent) {
                    error_log('Application received email failed: ' . (Mailer::getLastError() ?? 'unknown'));
                }
            }

            $notified = Mailer::notifyAdmin('admin_notification', [
                'application_number' => $data['application_number'],
                'full_name' => $data['full_name_english'],
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?? 'N/A',
            ]);
            if (!$notified) {
                error_log('Admin notification email failed: ' . (Mailer::getLastError() ?? 'unknown'));
            }
        } catch (\Throwable $e) {
            error_log('Application submit email error: ' . $e->getMessage());
        }
    }
}

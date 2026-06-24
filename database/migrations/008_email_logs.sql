USE osa_membership;

CREATE TABLE IF NOT EXISTS email_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipient_email VARCHAR(100) NOT NULL,
    template_name VARCHAR(100) DEFAULT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    related_type VARCHAR(50) DEFAULT NULL,
    related_id INT UNSIGNED DEFAULT NULL,
    email_sent TINYINT(1) NOT NULL DEFAULT 0,
    smtp_response TEXT DEFAULT NULL,
    sent_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_logs_recipient (recipient_email),
    INDEX idx_email_logs_related (related_type, related_id),
    INDEX idx_email_logs_sent_at (sent_at)
) ENGINE=InnoDB;

INSERT INTO email_templates (name, subject, body, is_active) VALUES
(
    'profile_updated',
    'Your OSA Profile Has Been Updated',
    '<p>Dear {{full_name}},</p><p>Your membership profile has been updated successfully.</p><p><strong>Membership Number:</strong> {{membership_number}}<br><strong>Email:</strong> {{email}}</p><p>Contact: 077 887 0135 | tmvosa@vkitnet.info</p>',
    1
)
ON DUPLICATE KEY UPDATE
    subject = VALUES(subject),
    body = VALUES(body),
    is_active = VALUES(is_active);

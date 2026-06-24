-- Official OSA email configuration defaults
UPDATE settings SET setting_value = 'mail.vkitnet.info' WHERE setting_key = 'smtp_host';
UPDATE settings SET setting_value = '465' WHERE setting_key = 'smtp_port';
UPDATE settings SET setting_value = 'ssl' WHERE setting_key = 'smtp_encryption';
UPDATE settings SET setting_value = 'tmvosa@vkitnet.info' WHERE setting_key = 'smtp_username';
UPDATE settings SET setting_value = 'tmvosa@vkitnet.info' WHERE setting_key = 'from_email';
UPDATE settings SET setting_value = 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students'' Association' WHERE setting_key = 'from_name';

INSERT INTO settings (setting_key, setting_value, setting_group, description) VALUES
('admin_notification_email', 'tmvosa@vkitnet.info', 'email', 'Admin notification email address')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Do not store SMTP password in database; use SMTP_PASSWORD in .env

INSERT INTO email_templates (name, subject, body, variables, is_active) VALUES
('welcome_email', 'Welcome to OSA Membership',
 '<p>Dear {{full_name}},</p><p>Welcome to the Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students'' Association.</p><p>Your membership number is <strong>{{membership_number}}</strong>.</p><p>Thank you for staying connected with our alumni community.</p>',
 '["full_name","membership_number"]', 1),
('membership_activated', 'Membership Activated - {{membership_number}}',
 '<p>Dear {{full_name}},</p><p>Your OSA membership is now <strong>active</strong>.</p><p>Membership Number: <strong>{{membership_number}}</strong><br>Valid Until: <strong>{{expiry_date}}</strong></p><p>Thank you,<br>OSA Secretariat</p>',
 '["full_name","membership_number","expiry_date"]', 1),
('password_reset', 'Your OSA Account Password Has Been Reset',
 '<p>Dear {{full_name}},</p><p>Your account password was reset by an administrator.</p><p><strong>Temporary Password:</strong> {{temporary_password}}</p><p>Please log in and change your password immediately.</p>',
 '["full_name","temporary_password"]', 1),
('password_changed_confirmation', 'Password Changed Successfully',
 '<p>Dear {{full_name}},</p><p>This confirms that your OSA account password was changed successfully on {{changed_at}}.</p><p>If you did not make this change, please contact the secretary immediately.</p>',
 '["full_name","changed_at"]', 1),
('membership_expiry_reminder', 'Membership Expiry Reminder - {{membership_number}}',
 '<p>Dear {{full_name}},</p><p>Your OSA membership (<strong>{{membership_number}}</strong>) will expire on <strong>{{expiry_date}}</strong>.</p><p>Please renew your membership to remain active in the alumni association.</p><p>Contact: 077 887 0135 | tmvosa@vkitnet.info</p>',
 '["full_name","membership_number","expiry_date"]', 1),
('admin_notification', 'New Membership Application - {{application_number}}',
 '<p>A new membership application has been submitted.</p><p><strong>Application Number:</strong> {{application_number}}<br><strong>Applicant:</strong> {{full_name}}<br><strong>Mobile:</strong> {{mobile}}<br><strong>Email:</strong> {{email}}</p><p>Please review the application in the admin panel.</p>',
 '["application_number","full_name","mobile","email"]', 1)
ON DUPLICATE KEY UPDATE
 subject = VALUES(subject),
 body = VALUES(body),
 variables = VALUES(variables),
 is_active = VALUES(is_active);

UPDATE email_templates SET
 subject = 'Application Submitted - {{application_number}}',
 body = '<p>Dear {{full_name}},</p><p>Thank you for submitting your OSA membership application.</p><p><strong>Application Number:</strong> {{application_number}}</p><p>Your application is under review. We will notify you once a decision is made.</p><p>Contact: 077 887 0135 | tmvosa@vkitnet.info</p>'
WHERE name = 'application_received';

UPDATE email_templates SET
 subject = 'Application Approved - {{membership_number}}',
 body = '<p>Dear {{full_name}},</p><p>Congratulations! Your membership application has been <strong>approved</strong>.</p><p><strong>Membership Number:</strong> {{membership_number}}</p><p>Welcome to the Old Students'' Association.</p>'
WHERE name = 'application_approved';

UPDATE email_templates SET
 subject = 'Application Rejected',
 body = '<p>Dear {{full_name}},</p><p>We regret to inform you that your membership application could not be approved at this time.</p><p><strong>Reason:</strong> {{reason}}</p><p>For inquiries, contact 077 887 0135 or tmvosa@vkitnet.info</p>'
WHERE name = 'application_rejected';

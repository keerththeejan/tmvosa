-- Seed Data for OSA Membership System
USE osa_membership;

-- Roles
INSERT INTO roles (name, slug, description, permissions) VALUES
('Super Admin', 'super_admin', 'Full system access', '["*"]'),
('Secretary', 'secretary', 'Member and application management', '["members.view","members.create","members.edit","members.approve","applications.view","applications.edit","applications.approve","reports.view"]'),
('Treasurer', 'treasurer', 'Payment management', '["payments.view","payments.verify","payments.edit","receipts.generate","reports.financial"]'),
('Alumni Member', 'member', 'Alumni member access', '["profile.view","profile.edit","card.view","application.submit"]');

-- Default Super Admin (password: password)
INSERT INTO users (role_id, username, email, password, full_name, mobile, is_active) VALUES
(1, 'admin', 'admin@osa-alumni.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', '0770000000', 1);

-- Membership Types (sort: half_year, ordinary, ten_year via application ORDER BY)
INSERT INTO membership_types (name, slug, fee, duration_years, description) VALUES
('Half-Year Membership', 'half_year', 500.00, 0, 'Half-Year Membership (அரை ஆண்டு உறுப்பினர்) — Rs. 500.00 | 6 Months (180 Days)'),
('Ordinary Member', 'ordinary', 1000.00, 1, 'Ordinary Member (சாதாரண உறுப்பினர்) — Rs. 1,000.00 | 1 Year'),
('10-Year Membership', 'ten_year', 10000.00, 10, '10-Year Membership (10 ஆண்டு உறுப்பினர்) — Rs. 10,000.00 | 10 Years');

-- Countries (sample)
INSERT INTO countries (name, code, phone_code) VALUES
('Sri Lanka', 'LK', '+94'),
('India', 'IN', '+91'),
('United Kingdom', 'GB', '+44'),
('United States', 'US', '+1'),
('Canada', 'CA', '+1'),
('Australia', 'AU', '+61'),
('United Arab Emirates', 'AE', '+971'),
('Qatar', 'QA', '+974'),
('Saudi Arabia', 'SA', '+966'),
('Singapore', 'SG', '+65'),
('Malaysia', 'MY', '+60'),
('Germany', 'DE', '+49'),
('France', 'FR', '+33'),
('Japan', 'JP', '+81'),
('Other', 'OT', NULL);

-- Settings
INSERT INTO settings (setting_key, setting_value, setting_group, description) VALUES
('site_name', 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA', 'general', 'Organization name'),
('site_name_tamil', 'கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம்', 'general', 'Organization name in Tamil'),
('membership_prefix', 'OSA', 'membership', 'Membership number prefix'),
('receipt_prefix', 'REC', 'payment', 'Receipt number prefix'),
('session_timeout', '3600', 'security', 'Session timeout in seconds'),
('max_upload_size', '5242880', 'upload', 'Max file upload size in bytes (5MB)'),
('allowed_file_types', 'jpg,jpeg,png,pdf', 'upload', 'Allowed file extensions'),
('smtp_host', 'smtp.gmail.com', 'email', 'SMTP host'),
('smtp_port', '587', 'email', 'SMTP port'),
('smtp_encryption', 'tls', 'email', 'SMTP encryption'),
('smtp_username', '', 'email', 'SMTP username'),
('smtp_password', '', 'email', 'SMTP password'),
('from_email', 'noreply@osa-alumni.org', 'email', 'From email address'),
('from_name', 'OSA Alumni', 'email', 'From name'),
('school_logo', 'assets/img/school-logo.png', 'branding', 'School logo path'),
('alumni_logo', 'assets/img/alumni-logo.png', 'branding', 'Alumni logo path');

-- Email Templates
INSERT INTO email_templates (name, subject, body, variables) VALUES
('application_received', 'Membership Application Received - {{application_number}}',
 '<p>Dear {{full_name}},</p><p>Your membership application ({{application_number}}) has been received and is under review.</p><p>Thank you,<br>OSA Alumni</p>',
 '["full_name","application_number"]'),
('application_approved', 'Membership Approved - {{membership_number}}',
 '<p>Dear {{full_name}},</p><p>Congratulations! Your membership has been approved. Your membership number is <strong>{{membership_number}}</strong>.</p><p>Thank you,<br>OSA Alumni</p>',
 '["full_name","membership_number"]'),
('application_rejected', 'Membership Application Update',
 '<p>Dear {{full_name}},</p><p>We regret to inform you that your application has not been approved at this time.</p><p>Reason: {{reason}}</p><p>Thank you,<br>OSA Alumni</p>',
 '["full_name","reason"]'),
('payment_verified', 'Payment Verified - {{receipt_number}}',
 '<p>Dear {{full_name}},</p><p>Your payment of Rs. {{amount}} has been verified. Receipt number: {{receipt_number}}</p><p>Thank you,<br>OSA Alumni</p>',
 '["full_name","amount","receipt_number"]');

-- Number Sequences
INSERT INTO number_sequences (sequence_type, year, last_number, prefix) VALUES
('membership', 2026, 0, 'OSA'),
('receipt', 2026, 0, 'REC'),
('application', 2026, 0, 'APP');

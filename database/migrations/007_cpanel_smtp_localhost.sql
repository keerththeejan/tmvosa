-- Set cPanel-friendly SMTP defaults in admin settings (host/port/encryption only; password stays in .env)
UPDATE settings SET setting_value = 'localhost' WHERE setting_key = 'smtp_host';
UPDATE settings SET setting_value = '587' WHERE setting_key = 'smtp_port';
UPDATE settings SET setting_value = 'tls' WHERE setting_key = 'smtp_encryption';

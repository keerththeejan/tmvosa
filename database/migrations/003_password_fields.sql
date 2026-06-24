-- Password change and force-reset support
ALTER TABLE users
    ADD COLUMN password_changed_at TIMESTAMP NULL DEFAULT NULL AFTER password,
    ADD COLUMN force_password_change TINYINT(1) NOT NULL DEFAULT 0 AFTER password_changed_at;

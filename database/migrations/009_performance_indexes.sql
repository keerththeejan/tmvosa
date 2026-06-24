USE osa_membership;

-- Email lookups (duplicate checks, notifications)
ALTER TABLE members
    ADD INDEX idx_members_email (email);

ALTER TABLE member_applications
    ADD INDEX idx_applications_email (email),
    ADD INDEX idx_applications_mobile (mobile);

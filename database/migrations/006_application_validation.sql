-- Application validation: duplicate settings and NIC uniqueness

INSERT INTO settings (setting_key, setting_value, setting_group, description) VALUES
('block_duplicate_mobile', '0', 'membership', 'Block application if mobile number already exists (1=yes, 0=warning only)'),
('block_duplicate_email', '0', 'membership', 'Block application if email already exists (1=yes, 0=warning only)')
ON DUPLICATE KEY UPDATE description = VALUES(description);

-- Normalize empty NIC values before unique indexes
UPDATE members SET nic_number = NULL WHERE nic_number = '';
UPDATE member_applications SET nic_number = NULL WHERE nic_number = '';

-- Unique NIC (multiple NULL values allowed in MySQL)
ALTER TABLE members
    ADD UNIQUE INDEX uq_members_nic_number (nic_number);

ALTER TABLE member_applications
    ADD UNIQUE INDEX uq_member_applications_nic_number (nic_number);

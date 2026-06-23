-- Add optional proposer fields to membership applications
USE osa_membership;

ALTER TABLE member_applications
    ADD COLUMN proposer_name VARCHAR(150) DEFAULT NULL AFTER company,
    ADD COLUMN proposer_contact VARCHAR(20) DEFAULT NULL AFTER proposer_name;

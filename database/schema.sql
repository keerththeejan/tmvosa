-- OSA Membership Management System - Database Schema
-- Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students' Association

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS osa_membership
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE osa_membership;

-- Roles
CREATE TABLE roles (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL,
    permissions JSON DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Users
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id TINYINT UNSIGNED NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    mobile VARCHAR(20) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) DEFAULT NULL,
    remember_token VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

-- Countries
CREATE TABLE countries (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(3) NOT NULL UNIQUE,
    phone_code VARCHAR(10) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- Membership Types
CREATE TABLE membership_types (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    fee DECIMAL(10,2) NOT NULL DEFAULT 0,
    duration_years INT UNSIGNED DEFAULT 1,
    description TEXT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Members
CREATE TABLE members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED DEFAULT NULL,
    membership_number VARCHAR(20) NOT NULL UNIQUE,
    full_name_tamil VARCHAR(200) DEFAULT NULL,
    full_name_english VARCHAR(200) NOT NULL,
    gender ENUM('male','female','other') DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    nic_number VARCHAR(20) DEFAULT NULL,
    current_address TEXT DEFAULT NULL,
    permanent_address TEXT DEFAULT NULL,
    country_id SMALLINT UNSIGNED DEFAULT NULL,
    mobile VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    studied_from_year YEAR DEFAULT NULL,
    studied_to_year YEAR DEFAULT NULL,
    grade_stream VARCHAR(100) DEFAULT NULL,
    teacher_name VARCHAR(150) DEFAULT NULL,
    occupation VARCHAR(150) DEFAULT NULL,
    company VARCHAR(200) DEFAULT NULL,
    membership_type_id TINYINT UNSIGNED NOT NULL,
    status ENUM('pending','under_review','payment_verified','approved','active','suspended','expired') DEFAULT 'pending',
    photo VARCHAR(255) DEFAULT NULL,
    membership_start_date DATE DEFAULT NULL,
    membership_expiry_date DATE DEFAULT NULL,
    batch VARCHAR(20) GENERATED ALWAYS AS (studied_to_year) STORED,
    notes TEXT DEFAULT NULL,
    created_by INT UNSIGNED DEFAULT NULL,
    approved_by INT UNSIGNED DEFAULT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_membership_number (membership_number),
    INDEX idx_nic (nic_number),
    INDEX idx_mobile (mobile),
    INDEX idx_country (country_id),
    INDEX idx_membership_type (membership_type_id),
    INDEX idx_batch (studied_to_year),
    INDEX idx_expiry (membership_expiry_date),
    FULLTEXT idx_search (full_name_english, full_name_tamil, occupation, company),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (country_id) REFERENCES countries(id),
    FOREIGN KEY (membership_type_id) REFERENCES membership_types(id),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Member Applications
CREATE TABLE member_applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_number VARCHAR(20) NOT NULL UNIQUE,
    member_id INT UNSIGNED DEFAULT NULL,
    full_name_tamil VARCHAR(200) DEFAULT NULL,
    full_name_english VARCHAR(200) NOT NULL,
    gender ENUM('male','female','other') DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    nic_number VARCHAR(20) DEFAULT NULL,
    current_address TEXT DEFAULT NULL,
    permanent_address TEXT DEFAULT NULL,
    country_id SMALLINT UNSIGNED DEFAULT NULL,
    mobile VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    studied_from_year YEAR DEFAULT NULL,
    studied_to_year YEAR DEFAULT NULL,
    grade_stream VARCHAR(100) DEFAULT NULL,
    teacher_name VARCHAR(150) DEFAULT NULL,
    occupation VARCHAR(150) DEFAULT NULL,
    company VARCHAR(200) DEFAULT NULL,
    proposer_name VARCHAR(150) DEFAULT NULL,
    proposer_contact VARCHAR(20) DEFAULT NULL,
    membership_type_id TINYINT UNSIGNED NOT NULL,
    amount_paid DECIMAL(10,2) DEFAULT 0,
    payment_method VARCHAR(50) DEFAULT NULL,
    transaction_number VARCHAR(100) DEFAULT NULL,
    payment_date DATE DEFAULT NULL,
    status ENUM('pending','under_review','payment_verified','approved','rejected','active') DEFAULT 'pending',
    rejection_reason TEXT DEFAULT NULL,
    reviewed_by INT UNSIGNED DEFAULT NULL,
    reviewed_at TIMESTAMP NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_application_number (application_number),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (country_id) REFERENCES countries(id),
    FOREIGN KEY (membership_type_id) REFERENCES membership_types(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Member Documents
CREATE TABLE member_documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id INT UNSIGNED DEFAULT NULL,
    application_id INT UNSIGNED DEFAULT NULL,
    document_type ENUM('nic_copy','passport_photo','payment_slip','other') NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT UNSIGNED DEFAULT 0,
    mime_type VARCHAR(100) DEFAULT NULL,
    uploaded_by INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_member (member_id),
    INDEX idx_application (application_id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES member_applications(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Payments
CREATE TABLE payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id INT UNSIGNED NOT NULL,
    application_id INT UNSIGNED DEFAULT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_number VARCHAR(100) DEFAULT NULL,
    payment_date DATE NOT NULL,
    status ENUM('pending','verified','rejected') DEFAULT 'pending',
    verified_by INT UNSIGNED DEFAULT NULL,
    verified_at TIMESTAMP NULL,
    notes TEXT DEFAULT NULL,
    created_by INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_member (member_id),
    INDEX idx_status (status),
    INDEX idx_payment_date (payment_date),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES member_applications(id) ON DELETE SET NULL,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Payment Receipts
CREATE TABLE payment_receipts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_id INT UNSIGNED NOT NULL,
    receipt_number VARCHAR(20) NOT NULL UNIQUE,
    member_id INT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    issued_by INT UNSIGNED DEFAULT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    pdf_path VARCHAR(500) DEFAULT NULL,
    INDEX idx_receipt_number (receipt_number),
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Membership Cards
CREATE TABLE membership_cards (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id INT UNSIGNED NOT NULL,
    card_number VARCHAR(30) NOT NULL UNIQUE,
    qr_code_data TEXT NOT NULL,
    qr_code_path VARCHAR(500) DEFAULT NULL,
    pdf_path VARCHAR(500) DEFAULT NULL,
    image_path VARCHAR(500) DEFAULT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at DATE DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Audit Logs
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL,
    entity_id INT UNSIGNED DEFAULT NULL,
    old_values JSON DEFAULT NULL,
    new_values JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Settings
CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT DEFAULT NULL,
    setting_group VARCHAR(50) DEFAULT 'general',
    description VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Notifications
CREATE TABLE notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED DEFAULT NULL,
    member_id INT UNSIGNED DEFAULT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    link VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_member (member_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Email Templates
CREATE TABLE email_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    variables JSON DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Number Sequences (for membership numbers and receipts)
CREATE TABLE number_sequences (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sequence_type VARCHAR(50) NOT NULL,
    year YEAR NOT NULL,
    last_number INT UNSIGNED DEFAULT 0,
    prefix VARCHAR(20) NOT NULL,
    UNIQUE KEY uk_sequence (sequence_type, year)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

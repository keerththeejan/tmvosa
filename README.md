# OSA Membership Management System

**Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students' Association (OSA)**

A complete, mobile-first alumni membership management system built with PHP 8.2+, MySQL 8, Bootstrap 5.3, and PWA support.

## Features

- **Multi-step membership application wizard** (7 steps with camera upload)
- **Role-based access control** (Super Admin, Secretary, Treasurer, Alumni Member)
- **Member directory** with mobile card layout and advanced search
- **Digital QR membership cards** (PDF, image, WhatsApp/email share)
- **Payment management** with auto-generated receipt numbers (REC-2026-0001)
- **Auto membership numbers** (OSA-2026-0001, sequential, never reused)
- **Dashboard** with Chart.js analytics
- **Reports** exportable to PDF, Excel, CSV
- **Audit logging** for all critical actions
- **PWA** — installable, offline mode, push notification ready
- **Email notifications** via PHPMailer

## Requirements

- PHP 8.2+
- MySQL 8.0+
- Apache with mod_rewrite (or Nginx)
- Composer
- Extensions: PDO, GD, mbstring, fileinfo, openssl

## Installation

### 1. Clone / copy project

```bash
cd C:\Users\Dell\osa-membership-system
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

```bash
copy .env.example .env
```

Edit `.env` with your database credentials:

```
DB_HOST=localhost
DB_NAME=osa_membership
DB_USER=root
DB_PASS=your_password
APP_URL=http://localhost/osa-membership-system/public
```

### 4. Create database

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/migrations/001_seed_data.sql
```

### 5. Set permissions

```bash
chmod -R 775 storage/
chmod -R 775 storage/uploads/
```

### 6. Configure web server

Point your document root to the `public/` folder, or access via:
`http://localhost/osa-membership-system/public`

## Default Login

| Field    | Value        |
|----------|--------------|
| Username | `admin`      |
| Password | `password`   |

> Change the default password immediately after first login.

## User Roles

| Role         | Capabilities                                              |
|--------------|-----------------------------------------------------------|
| Super Admin  | Full system access, users, settings, backup               |
| Secretary    | Applications, members, approvals, reports                 |
| Treasurer    | Payment verification, receipts, financial reports         |
| Alumni       | Apply, track application, view membership card            |

## Membership Types

| Type              | Fee          | Duration |
|-------------------|--------------|----------|
| Ordinary Member   | Rs. 1,000    | 1 year   |
| 10-Year Membership| Rs. 10,000   | 10 years |

## Project Structure

```
osa-membership-system/
├── app/
│   ├── Controllers/     # MVC Controllers
│   ├── Core/            # Framework (Router, DB, Auth, Security)
│   ├── Helpers/         # QR, PDF, Mail, File upload
│   ├── Middleware/      # Auth & Role middleware
│   ├── Models/          # Database models
│   └── Views/           # PHP templates (mobile-first)
├── config/              # App & database config
├── database/
│   ├── schema.sql       # Full database schema
│   └── migrations/      # Seed data
├── public/              # Web root
│   ├── assets/          # CSS, JS, images
│   ├── manifest.json    # PWA manifest
│   └── service-worker.js
└── storage/             # Uploads, logs, backups
```

## API Endpoints (AJAX)

| Method | Endpoint                        | Description           |
|--------|---------------------------------|-----------------------|
| POST   | `/apply`                        | Submit application    |
| POST   | `/track`                        | Track application     |
| POST   | `/applications/{id}/approve`    | Approve application   |
| POST   | `/payments/{id}/verify`         | Verify payment        |
| GET    | `/api/chart-data`               | Dashboard chart data  |
| GET    | `/verify/{membership_number}`   | Verify membership QR  |

## Security

- CSRF protection on all POST requests
- PDO prepared statements (SQL injection prevention)
- XSS escaping via `htmlspecialchars`
- Bcrypt password hashing
- Session timeout (1 hour)
- File upload validation (type, size, MIME)
- Role-based access control
- Security headers via `.htaccess`

## PWA

The app is installable on Android and iOS. Users can add it to their home screen for a native app experience with offline support for cached pages.

## License

Proprietary — Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA

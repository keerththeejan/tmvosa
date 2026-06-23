# OSA Membership System — cPanel Deployment

## 1. Upload files

Upload the entire project to your hosting account, for example:

```
/home/username/osa/
```

Keep `app/`, `config/`, `database/`, `public/`, `storage/`, `vendor/`, `.env`, etc. together.

## 2. Set document root (recommended)

In cPanel → **Domains** → your domain → **Document Root**, set:

```
/home/username/osa/public
```

This is the safest setup. The `app/`, `config/`, and `storage/` folders stay outside the web root.

## 3. Alternative: install in public_html

If you cannot change the document root:

1. Upload project to `public_html/osa/`
2. The included root `.htaccess` and `index.php` route requests into `public/`
3. Your site URL will be: `https://yourdomain.com/osa/`

## 4. Create MySQL database

In cPanel → **MySQL Databases**:

1. Create database: `username_osa`
2. Create user and assign ALL PRIVILEGES
3. Open **phpMyAdmin** → Import:
   - `database/schema.sql`
   - `database/migrations/001_seed_data.sql`

## 5. Configure environment

Copy `.env.example` to `.env` and edit:

```
DB_HOST=localhost
DB_NAME=username_osa
DB_USER=username_osauser
DB_PASS=your_db_password
APP_URL=https://yourdomain.com
APP_DEBUG=false
```

Use your real domain. Do not include `/public` in `APP_URL` when document root is `public/`.

## 6. Folder permissions

Set writable permissions on:

```
storage/
storage/uploads/
storage/logs/
storage/backups/
storage/cache/
```

In cPanel File Manager: right-click → **Change Permissions** → `755` or `775`.

## 7. PHP version

In cPanel → **MultiPHP Manager**, select **PHP 8.2** or **8.3**.

Required extensions: PDO, pdo_mysql, mbstring, gd, fileinfo, openssl.

## 8. Default login

| Username | Password  |
|----------|-----------|
| admin    | password  |

Change this immediately after first login.

## 9. Test

- Home: `https://yourdomain.com/` → membership application
- Login: `https://yourdomain.com/login`
- Dashboard: `https://yourdomain.com/dashboard`

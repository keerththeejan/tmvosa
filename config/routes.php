<?php

use App\Core\Router;

$router = new Router();
$base = '';

// Public routes
$router->get('/', ['HomeController', 'index']);
$router->get('/login', ['AuthController', 'loginForm']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/logout', ['AuthController', 'logout'], ['AuthMiddleware']);
$router->get('/apply', ['ApplicationController', 'form']);
$router->post('/apply', ['ApplicationController', 'submit']);
$router->post('/track', ['ApplicationController', 'track']);
$router->get('/verify/{number}', ['CardController', 'verify']);
$router->get('/files/{folder}/{filename}', ['FileController', 'serve'], ['AuthMiddleware']);

// Authenticated routes
$router->get('/dashboard', ['DashboardController', 'index'], ['AuthMiddleware']);
$router->get('/api/chart-data', ['DashboardController', 'chartData'], ['AuthMiddleware']);

// Applications (Secretary+)
$router->get('/applications', ['ApplicationController', 'index'], ['AuthMiddleware']);
$router->get('/applications/{id}', ['ApplicationController', 'show'], ['AuthMiddleware']);
$router->post('/applications/{id}/approve', ['ApplicationController', 'approve'], ['AuthMiddleware']);
$router->post('/applications/{id}/reject', ['ApplicationController', 'reject'], ['AuthMiddleware']);
$router->post('/applications/{id}/documents', ['ApplicationController', 'uploadDocument'], ['AuthMiddleware']);

// Members
$router->get('/members', ['MemberController', 'index'], ['AuthMiddleware']);
$router->get('/members/create', ['MemberController', 'createForm'], ['AuthMiddleware']);
$router->post('/members', ['MemberController', 'store'], ['AuthMiddleware']);
$router->get('/members/{id}', ['MemberController', 'show'], ['AuthMiddleware']);
$router->get('/members/{id}/edit', ['MemberController', 'editForm'], ['AuthMiddleware']);
$router->post('/members/{id}/update', ['MemberController', 'update'], ['AuthMiddleware']);

// Payments
$router->get('/payments', ['PaymentController', 'index'], ['AuthMiddleware']);
$router->post('/payments/{id}/verify', ['PaymentController', 'verify'], ['AuthMiddleware']);
$router->get('/receipts/{id}', ['PaymentController', 'receipt'], ['AuthMiddleware']);

// Membership Cards
$router->get('/card/{memberId}', ['CardController', 'show'], ['AuthMiddleware']);
$router->get('/card/{memberId}/pdf', ['CardController', 'downloadPdf'], ['AuthMiddleware']);
$router->get('/card/{memberId}/image', ['CardController', 'downloadImage'], ['AuthMiddleware']);

// Reports
$router->get('/reports', ['ReportController', 'index'], ['AuthMiddleware']);
$router->get('/reports/members', ['ReportController', 'members'], ['AuthMiddleware']);
$router->get('/reports/financial', ['ReportController', 'financial'], ['AuthMiddleware']);
$router->get('/reports/alumni', ['ReportController', 'alumni'], ['AuthMiddleware']);

// Admin
$router->get('/settings/password', ['PasswordController', 'form'], ['AuthMiddleware']);
$router->post('/settings/password', ['PasswordController', 'update'], ['AuthMiddleware']);
$router->get('/admin/users', ['AdminController', 'users'], ['AuthMiddleware']);
$router->post('/admin/users', ['AdminController', 'createUser'], ['AuthMiddleware']);
$router->post('/admin/users/{id}/reset-password', ['AdminController', 'resetPassword'], ['AuthMiddleware']);
$router->post('/admin/users/{id}/force-password-change', ['AdminController', 'forcePasswordChange'], ['AuthMiddleware']);
$router->get('/admin/settings', ['AdminController', 'settings'], ['AuthMiddleware']);
$router->post('/admin/settings', ['AdminController', 'updateSettings'], ['AuthMiddleware']);
$router->get('/admin/email-settings', ['AdminController', 'emailSettings'], ['AuthMiddleware']);
$router->post('/admin/email-settings', ['AdminController', 'updateEmailSettings'], ['AuthMiddleware']);
$router->post('/admin/email-settings/test', ['AdminController', 'testEmail'], ['AuthMiddleware']);
$router->get('/admin/email-templates', ['AdminController', 'emailTemplates'], ['AuthMiddleware']);
$router->post('/admin/email-templates/{id}', ['AdminController', 'updateEmailTemplate'], ['AuthMiddleware']);
$router->post('/admin/email/send-expiry-reminders', ['AdminController', 'sendExpiryReminders'], ['AuthMiddleware']);
$router->get('/admin/audit-logs', ['AdminController', 'auditLogs'], ['AuthMiddleware']);
$router->get('/admin/password-logs', ['AdminController', 'passwordLogs'], ['AuthMiddleware']);
$router->get('/admin/backup', ['AdminController', 'backup'], ['AuthMiddleware']);
$router->get('/membership-cards', ['CardController', 'index'], ['AuthMiddleware']);

return $router;

<?php

use App\Core\Router;

$router = new Router();
$base = '';

// Public routes
$router->get('/', ['HomeController', 'index']);
$router->get('/clear-site-data', ['HomeController', 'clearSiteData']);
$router->get('/login', ['AuthController', 'loginForm']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/logout', ['AuthController', 'logout'], ['AuthMiddleware']);
$router->get('/apply', ['ApplicationController', 'form']);
$router->post('/apply', ['ApplicationController', 'submit']);
$router->get('/apply/success', ['ApplicationController', 'success']);
$router->post('/apply/validate-field', ['ApplicationController', 'validateField']);
$router->post('/track', ['ApplicationController', 'track']);
$router->get('/verify/{number}', ['CardController', 'verify']);
$router->get('/files/{folder}/{filename}', ['FileController', 'serve'], ['AuthMiddleware']);

// Authenticated routes
$router->get('/dashboard', ['DashboardController', 'index'], ['AuthMiddleware']);
$router->get('/api/chart-data', ['DashboardController', 'chartData'], ['AuthMiddleware']);

// Applications — static paths before {id}
$router->get('/applications', ['ApplicationController', 'index'], ['AuthMiddleware']);
$router->get('/applications/duplicates', ['ApplicationController', 'duplicates'], ['AuthMiddleware']);
$router->get('/applications/export', ['ApplicationController', 'export'], ['AuthMiddleware']);
$router->get('/applications/{id}/edit', ['ApplicationController', 'editForm'], ['AuthMiddleware']);
$router->post('/applications/{id}/update', ['ApplicationController', 'update'], ['AuthMiddleware']);
$router->get('/applications/{id}/print', ['ApplicationController', 'print'], ['AuthMiddleware']);
$router->get('/applications/{id}', ['ApplicationController', 'show'], ['AuthMiddleware']);
$router->post('/applications/{id}/approve', ['ApplicationController', 'approve'], ['AuthMiddleware']);
$router->post('/applications/{id}/reject', ['ApplicationController', 'reject'], ['AuthMiddleware']);
$router->post('/applications/{id}/delete', ['ApplicationController', 'destroy'], ['AuthMiddleware']);
$router->post('/applications/{id}/documents', ['ApplicationController', 'uploadDocument'], ['AuthMiddleware']);

// Members — static paths before {id}
$router->get('/members', ['MemberController', 'index'], ['AuthMiddleware']);
$router->get('/members/create', ['MemberController', 'createForm'], ['AuthMiddleware']);
$router->get('/members/export', ['MemberController', 'export'], ['AuthMiddleware']);
$router->post('/members', ['MemberController', 'store'], ['AuthMiddleware']);
$router->get('/members/{id}/edit', ['MemberController', 'editForm'], ['AuthMiddleware']);
$router->post('/members/{id}/update', ['MemberController', 'update'], ['AuthMiddleware']);
$router->post('/members/{id}/suspend', ['MemberController', 'suspend'], ['AuthMiddleware']);
$router->post('/members/{id}/activate', ['MemberController', 'activate'], ['AuthMiddleware']);
$router->post('/members/{id}/deactivate', ['MemberController', 'deactivate'], ['AuthMiddleware']);
$router->post('/members/{id}/renew', ['MemberController', 'renew'], ['AuthMiddleware']);
$router->get('/members/{id}/print', ['MemberController', 'printProfile'], ['AuthMiddleware']);
$router->get('/members/{id}', ['MemberController', 'show'], ['AuthMiddleware']);

// Payments
$router->get('/payments', ['PaymentController', 'index'], ['AuthMiddleware']);
$router->get('/payments/create', ['PaymentController', 'createForm'], ['AuthMiddleware']);
$router->post('/payments', ['PaymentController', 'store'], ['AuthMiddleware']);
$router->post('/payments/{id}/verify', ['PaymentController', 'verify'], ['AuthMiddleware']);
$router->post('/payments/{id}/reject', ['PaymentController', 'reject'], ['AuthMiddleware']);
$router->get('/receipts/{id}', ['PaymentController', 'receipt'], ['AuthMiddleware']);

// Membership Cards
$router->get('/membership-cards', ['CardController', 'index'], ['AuthMiddleware']);
$router->post('/membership-cards/bulk-print', ['CardController', 'bulkPrint'], ['AuthMiddleware']);
$router->get('/card/{memberId}', ['CardController', 'show'], ['AuthMiddleware']);
$router->get('/card/{memberId}/pdf', ['CardController', 'downloadPdf'], ['AuthMiddleware']);
$router->get('/card/{memberId}/image', ['CardController', 'downloadImage'], ['AuthMiddleware']);

// Reports
$router->get('/reports', ['ReportController', 'index'], ['AuthMiddleware']);
$router->get('/reports/members', ['ReportController', 'members'], ['AuthMiddleware']);
$router->get('/reports/financial', ['ReportController', 'financial'], ['AuthMiddleware']);
$router->get('/reports/alumni', ['ReportController', 'alumni'], ['AuthMiddleware']);
$router->get('/reports/audit', ['ReportController', 'audit'], ['AuthMiddleware']);

// Password (any authenticated user)
$router->get('/settings/password', ['PasswordController', 'form'], ['AuthMiddleware']);
$router->post('/settings/password', ['PasswordController', 'update'], ['AuthMiddleware']);

// Admin (super_admin)
$router->get('/admin/users', ['AdminController', 'users'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/users', ['AdminController', 'createUser'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/users/{id}/update', ['AdminController', 'updateUser'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/users/{id}/toggle', ['AdminController', 'toggleUser'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/users/{id}/reset-password', ['AdminController', 'resetPassword'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/users/{id}/force-password-change', ['AdminController', 'forcePasswordChange'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->get('/admin/settings', ['AdminController', 'settings'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/settings', ['AdminController', 'updateSettings'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->get('/admin/email-settings', ['AdminController', 'emailSettings'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/email-settings', ['AdminController', 'updateEmailSettings'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/email-settings/test', ['AdminController', 'testEmail'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->get('/admin/email-templates', ['AdminController', 'emailTemplates'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/email-templates/{id}', ['AdminController', 'updateEmailTemplate'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->post('/admin/email/send-expiry-reminders', ['AdminController', 'sendExpiryReminders'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->get('/admin/audit-logs', ['AdminController', 'auditLogs'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->get('/admin/password-logs', ['AdminController', 'passwordLogs'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);
$router->get('/admin/backup', ['AdminController', 'backup'], ['AuthMiddleware', 'RoleMiddleware:super_admin']);

return $router;

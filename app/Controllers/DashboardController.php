<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\App;
use App\Core\Database;
use App\Models\Member;
use App\Models\Application;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index(): void
    {
        $memberStats = Member::getStats();
        $revenueStats = Payment::getRevenueStats();
        $applicationStats = Application::getStatusCounts();
        $recentPayments = Payment::getRecent(5);
        $recentApplications = Application::getRecent(5);
        $recentMembers = Database::fetchAll(
            "SELECT id, membership_number, full_name_english, status, created_at
             FROM members ORDER BY created_at DESC LIMIT 5"
        );
        $recentAudits = AuditLog::getAll(1, 8)['data'] ?? [];
        $cardsPrinted = (int) (Database::fetch("SELECT COUNT(*) as cnt FROM membership_cards")['cnt'] ?? 0);
        $totalApplications = (int) array_sum($applicationStats);

        $stats = array_merge($memberStats, $revenueStats, [
            'applications_total' => $totalApplications,
            'applications_pending' => $applicationStats['pending'] ?? 0,
            'applications_approved' => $applicationStats['approved'] ?? 0,
            'applications_rejected' => $applicationStats['rejected'] ?? 0,
            'members_pending' => (int) ($memberStats['pending'] ?? 0),
            'cards_printed' => $cardsPrinted,
            'pending_renewals' => (int) ($memberStats['expiring'] ?? 0),
            'today_revenue' => $revenueStats['today_revenue'] ?? 0,
            'annual_revenue' => $revenueStats['annual_revenue'] ?? 0,
        ]);

        $systemStatus = [
            'database' => true,
            'vendor' => file_exists(App::basePath() . '/vendor/autoload.php'),
            'smtp' => (Setting::get('smtp_host', '') !== '' || (getenv('SMTP_HOST') ?: '') !== ''),
            'uploads_writable' => is_writable(App::config('app.upload_path') ?: App::basePath() . '/storage/uploads'),
        ];
        try {
            Database::fetch('SELECT 1');
        } catch (\Throwable $e) {
            $systemStatus['database'] = false;
        }

        $this->view('dashboard/index', [
            'stats' => $stats,
            'recentPayments' => $recentPayments,
            'recentApplications' => $recentApplications,
            'recentMembers' => $recentMembers,
            'recentAudits' => $recentAudits,
            'growth' => Member::getGrowthData(),
            'revenue' => Payment::getRevenueGrowth(),
            'countries' => Member::getCountryDistribution(),
            'types' => Member::getTypeDistribution(),
            'genders' => Member::getGenderDistribution(),
            'batches' => Member::getBatchDistribution(),
            'systemStatus' => $systemStatus,
        ]);
    }

    public function chartData(): void
    {
        $this->json([
            'growth' => Member::getGrowthData(),
            'revenue' => Payment::getRevenueGrowth(),
            'countries' => Member::getCountryDistribution(),
            'types' => Member::getTypeDistribution(),
            'genders' => Member::getGenderDistribution(),
            'batches' => Member::getBatchDistribution(),
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Member;
use App\Models\Application;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index(): void
    {
        $memberStats = Member::getStats();
        $revenueStats = Payment::getRevenueStats();
        $applicationStats = Application::getStatusCounts();
        $recentPayments = Payment::getRecent(5);
        $recentApplications = Application::getRecent(5);

        $this->view('dashboard/index', [
            'stats' => array_merge($memberStats, $revenueStats, [
                'applications_pending' => $applicationStats['pending'],
                'applications_approved' => $applicationStats['approved'],
                'applications_rejected' => $applicationStats['rejected'],
                'members_pending' => (int) ($memberStats['pending'] ?? 0),
            ]),
            'recentPayments' => $recentPayments,
            'recentApplications' => $recentApplications,
            'growth' => Member::getGrowthData(),
            'revenue' => Payment::getRevenueGrowth(),
            'countries' => Member::getCountryDistribution(),
            'types' => Member::getTypeDistribution(),
        ]);
    }

    public function chartData(): void
    {
        $this->json([
            'growth' => Member::getGrowthData(),
            'revenue' => Payment::getRevenueGrowth(),
            'countries' => Member::getCountryDistribution(),
            'types' => Member::getTypeDistribution(),
        ]);
    }
}

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
        $pendingApps = Application::getPendingCount();

        $this->view('dashboard/index', [
            'stats' => array_merge($memberStats, $revenueStats, ['pending_applications' => $pendingApps]),
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

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        $countries = Database::fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name");
        $membershipTypes = Database::fetchAll("SELECT * FROM membership_types WHERE is_active = 1");
        $this->view('home/index', compact('countries', 'membershipTypes') + ['pageScript' => 'application-wizard.js']);
    }
}

<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index(): void
    {
        $countries = Database::fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name");
        $membershipTypes = \App\Helpers\MembershipType::allActive();
        $validationConfig = [
            'blockDuplicateMobile' => Setting::get('block_duplicate_mobile', '0') === '1',
            'blockDuplicateEmail' => Setting::get('block_duplicate_email', '0') === '1',
        ];
        $this->view('home/index', compact('countries', 'membershipTypes', 'validationConfig') + [
            'pageScript' => 'application-wizard.js',
        ]);
    }

    public function clearSiteData(): void
    {
        if (Auth::check()) {
            Auth::logout();
        } else {
            Session::destroy();
        }

        $redirect = App::routeUrl('');
        require App::basePath() . '/app/Views/site-reset.php';
        exit;
    }
}

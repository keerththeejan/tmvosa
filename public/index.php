<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$vendorAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

use App\Core\App;

App::init();

$router = require dirname(__DIR__) . '/config/routes.php';
$router->dispatch();

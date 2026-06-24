<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Core\App;

App::init();

$router = require dirname(__DIR__) . '/config/routes.php';
$router->dispatch();

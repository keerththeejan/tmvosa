<?php
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Core/App.php';
\App\Core\App::init();

use App\Helpers\Mailer;

$to = $argv[1] ?? 'keerththeejan@gmail.com';
$result = Mailer::sendTest($to);
echo json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;

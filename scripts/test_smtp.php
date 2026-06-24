<?php
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Core/App.php';
\App\Core\App::init();

use App\Helpers\Mailer;

$result = Mailer::sendTest('tmvosa@vkitnet.info');
print_r($result);

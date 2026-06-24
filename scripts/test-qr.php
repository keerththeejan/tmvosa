<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Core\App;
use App\Helpers\QrGenerator;

App::init();

$path = QrGenerator::generate('https://tmvosa.vkitnet.info/test', 'test-qr-' . time() . '.png');
echo "OK path={$path}\n";

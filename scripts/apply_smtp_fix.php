<?php
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../app/Core/App.php';
\App\Core\App::init();
$config = require __DIR__ . '/../config/database.php';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['database']);
$pdo = new PDO($dsn, $config['username'], $config['password']);
$pdo->exec(file_get_contents(__DIR__ . '/../database/migrations/005_fix_smtp_host.sql'));
echo "SMTP host updated to mail.vkitnet.info\n";

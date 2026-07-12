<?php
/**
 * Simulate request with language cookie and dump key homepage strings.
 */
$_COOKIE['osa_lang'] = $argv[1] ?? 'ta';
$_COOKIE['language'] = $argv[1] ?? 'ta';

require dirname(__DIR__) . '/bootstrap.php';
App\Core\App::init();

echo "LOCALE=" . App\Helpers\Lang::locale() . PHP_EOL;
echo "SESSION=" . ($_SESSION['language'] ?? '') . PHP_EOL;
$keys = ['home', 'about', 'membership', 'apply_now', 'hero_system_title', 'about_title', 'mission', 'footer_contact'];
foreach ($keys as $k) {
    echo $k . '=' . __($k) . PHP_EOL;
}

<?php
require dirname(__DIR__) . '/bootstrap.php';
App\Core\App::init();

echo 'default_locale=' . App\Helpers\Lang::locale() . PHP_EOL;
echo 'home_ta=' . __('home') . PHP_EOL;
echo 'about_ta=' . __('about') . PHP_EOL;

App\Helpers\Lang::setLocale('en');
echo 'locale_en=' . App\Helpers\Lang::locale() . PHP_EOL;
echo 'home_en=' . __('home') . PHP_EOL;
echo 'about_en=' . __('about') . PHP_EOL;
echo 'hero_en=' . __('hero_system_title') . PHP_EOL;
echo 'session=' . ($_SESSION['language'] ?? '') . PHP_EOL;
echo 'cookie=' . ($_COOKIE['osa_lang'] ?? '') . PHP_EOL;

App\Helpers\Lang::setLocale('ta');
echo 'back_ta=' . __('home') . PHP_EOL;

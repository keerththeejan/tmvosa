<?php
$path = dirname(__DIR__) . '/public/assets/css/home.css';
$lines = file($path);
$out = [];
$skip = false;
foreach ($lines as $line) {
    if (str_contains($line, '/* Hero — premium full-width')) {
        $skip = true;
        $out[] = "/* Hero styles moved to assets/css/hero.css + hero-responsive.css */\n";
        continue;
    }
    if ($skip && str_contains($line, '/* Legacy stats')) {
        $skip = false;
    }
    if (!$skip) {
        $out[] = $line;
    }
}
file_put_contents($path, implode('', $out));
echo 'done ' . count($lines) . ' -> ' . count($out) . PHP_EOL;

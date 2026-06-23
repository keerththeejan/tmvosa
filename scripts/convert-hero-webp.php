<?php
$jpg = __DIR__ . '/../public/assets/img/osa-hero-banner.jpg';
$webp = __DIR__ . '/../public/assets/img/osa-hero-banner.webp';
if (!file_exists($jpg)) {
    fwrite(STDERR, "JPG not found\n");
    exit(1);
}
$img = imagecreatefromjpeg($jpg);
if (!$img) {
    fwrite(STDERR, "Failed to load PNG\n");
    exit(1);
}
imagepalettetotruecolor($img);
imagealphablending($img, true);
imagesavealpha($img, true);
if (!function_exists('imagewebp')) {
    fwrite(STDERR, "WebP not supported\n");
    exit(0);
}
imagewebp($img, $webp, 85);
imagedestroy($img);
echo file_exists($webp) ? "Created WebP\n" : "Failed\n";

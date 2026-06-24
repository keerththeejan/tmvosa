<?php

namespace App\Helpers;

class ImageCompressor
{
    private const DEFAULT_MAX_DIMENSION = 1920;
    private const THUMB_DIMENSION = 320;

    /**
     * Compress an image file in place (or to $destPath). Returns final path and metadata.
     *
     * @return array{path: string, mime: string, size: int, thumbnail?: string}|null
     */
    public static function compress(
        string $sourcePath,
        int $maxBytes,
        ?string $destPath = null,
        bool $generateThumbnail = false,
        int $maxDimension = self::DEFAULT_MAX_DIMENSION
    ): ?array {
        if (!is_file($sourcePath) || !extension_loaded('gd')) {
            return null;
        }

        $info = @getimagesize($sourcePath);
        if ($info === false) {
            return null;
        }

        [$width, $height, $type] = $info;
        $image = self::loadImage($sourcePath, $type);
        if ($image === null) {
            return null;
        }

        $image = self::fixOrientation($image, $sourcePath, $type);
        $image = self::resizeToFit($image, $width, $height, $maxDimension);

        $destPath = $destPath ?? $sourcePath;
        $useWebp = function_exists('imagewebp');
        $basePath = preg_replace('/\.[^.]+$/', '', $destPath) ?? $destPath;
        $targetPath = $useWebp ? $basePath . '.webp' : $basePath . '.jpg';
        $mime = $useWebp ? 'image/webp' : 'image/jpeg';

        $quality = $useWebp ? 82 : 85;
        $saved = self::saveUnderBudget($image, $targetPath, $mime, $maxBytes, $quality);

        if (!$saved) {
            imagedestroy($image);
            return null;
        }

        if ($targetPath !== $sourcePath && is_file($sourcePath)) {
            @unlink($sourcePath);
        }

        $result = [
            'path' => $targetPath,
            'mime' => $mime,
            'size' => (int) filesize($targetPath),
        ];

        if ($generateThumbnail) {
            $thumbPath = self::createThumbnail($image, dirname($targetPath), basename($targetPath, '.' . ($useWebp ? 'webp' : 'jpg')));
            if ($thumbPath) {
                $result['thumbnail'] = $thumbPath;
            }
        }

        imagedestroy($image);

        return $result;
    }

    public static function isRasterImage(string $mime): bool
    {
        return in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true);
    }

    private static function loadImage(string $path, int $type): ?\GdImage
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path) ?: null,
            IMAGETYPE_PNG => @imagecreatefrompng($path) ?: null,
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? (@imagecreatefromwebp($path) ?: null) : null,
            IMAGETYPE_GIF => @imagecreatefromgif($path) ?: null,
            default => null,
        };
    }

    private static function fixOrientation(\GdImage $image, string $path, int $type): \GdImage
    {
        if ($type !== IMAGETYPE_JPEG || !function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        if (empty($exif['Orientation'])) {
            return $image;
        }

        return match ((int) $exif['Orientation']) {
            3 => imagerotate($image, 180, 0) ?: $image,
            6 => imagerotate($image, -90, 0) ?: $image,
            8 => imagerotate($image, 90, 0) ?: $image,
            default => $image,
        };
    }

    private static function resizeToFit(\GdImage $image, int $width, int $height, int $maxDimension): \GdImage
    {
        if ($width <= $maxDimension && $height <= $maxDimension) {
            return $image;
        }

        $ratio = min($maxDimension / $width, $maxDimension / $height);
        $newW = max(1, (int) round($width * $ratio));
        $newH = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newW, $newH);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $transparent);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($image);

        return $resized;
    }

    private static function saveUnderBudget(
        \GdImage $image,
        string $path,
        string $mime,
        int $maxBytes,
        int $startQuality
    ): bool {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        for ($q = $startQuality; $q >= 50; $q -= 5) {
            if ($mime === 'image/webp') {
                imagewebp($image, $path, $q);
            } else {
                imagejpeg($image, $path, $q);
            }

            if (is_file($path) && filesize($path) <= $maxBytes) {
                return true;
            }
        }

        return is_file($path);
    }

    private static function createThumbnail(\GdImage $source, string $dir, string $basename): ?string
    {
        $thumbDir = $dir . '/thumbs';
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }

        $w = imagesx($source);
        $h = imagesy($source);
        $ratio = min(self::THUMB_DIMENSION / max($w, 1), self::THUMB_DIMENSION / max($h, 1), 1.0);
        $tw = max(1, (int) round($w * $ratio));
        $th = max(1, (int) round($h * $ratio));

        $thumb = imagecreatetruecolor($tw, $th);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $tw, $th, $w, $h);

        $useWebp = function_exists('imagewebp');
        $thumbPath = $thumbDir . '/' . $basename . '_thumb.' . ($useWebp ? 'webp' : 'jpg');

        if ($useWebp) {
            imagewebp($thumb, $thumbPath, 78);
        } else {
            imagejpeg($thumb, $thumbPath, 80);
        }

        imagedestroy($thumb);

        return is_file($thumbPath) ? $thumbPath : null;
    }
}

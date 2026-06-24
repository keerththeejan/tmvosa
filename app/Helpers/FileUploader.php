<?php

namespace App\Helpers;

use App\Core\App;
use App\Core\Security;

class FileUploader
{
    public const PROFILE_MAX_BYTES = 307200;   // 300 KB
    public const DOCUMENT_MAX_BYTES = 512000;  // 500 KB

    public static function upload(array $file, string $subfolder = 'documents', array $options = []): ?array
    {
        $errors = Security::validateUpload($file);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $filename = Security::generateSecureFilename($file['name']);
        $uploadDir = App::config('app.upload_path') . '/' . $subfolder;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filepath = $uploadDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['errors' => ['Failed to save file.']];
        }

        $mime = mime_content_type($filepath) ?: ($file['type'] ?? '');
        $maxBytes = (int) ($options['max_bytes'] ?? 0);
        $thumbnail = !empty($options['thumbnail']);

        if ($maxBytes > 0 && ImageCompressor::isRasterImage($mime)) {
            $compressed = ImageCompressor::compress($filepath, $maxBytes, null, $thumbnail);
            if ($compressed) {
                $filepath = $compressed['path'];
                $mime = $compressed['mime'];
                $filename = basename($filepath);
            }
        }

        $relativePath = $subfolder . '/' . $filename;

        return [
            'file_name' => $file['name'],
            'file_path' => $relativePath,
            'file_size' => (int) filesize($filepath),
            'mime_type' => $mime,
        ];
    }

    public static function getFullPath(string $relativePath): string
    {
        return App::config('app.upload_path') . '/' . $relativePath;
    }
}

<?php

namespace App\Helpers;

use App\Core\App;
use App\Core\Security;

class FileUploader
{
    public static function upload(array $file, string $subfolder = 'documents'): ?array
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

        return [
            'file_name' => $file['name'],
            'file_path' => $subfolder . '/' . $filename,
            'file_size' => $file['size'],
            'mime_type' => mime_content_type($filepath),
        ];
    }

    public static function getFullPath(string $relativePath): string
    {
        return App::config('app.upload_path') . '/' . $relativePath;
    }
}

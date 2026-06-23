<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\App;
use App\Core\Auth;

class FileController extends Controller
{
    public function serve(string $folder, string $filename): void
    {
        if (!Auth::check()) {
            http_response_code(403);
            exit('Access denied');
        }

        $allowedFolders = ['documents', 'photos', 'qr', 'cards', 'receipts', 'reports'];
        if (!in_array($folder, $allowedFolders)) {
            http_response_code(404);
            exit;
        }

        $path = App::config('app.upload_path') . '/' . $folder . '/' . $filename;
        if (!file_exists($path)) {
            http_response_code(404);
            exit;
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}

<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Core\App;

class QrGenerator
{
    public static function generate(string $data, string $filename): string
    {
        $qrCode = QrCode::create($data)->setSize(300)->setMargin(10);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $dir = App::config('app.upload_path') . '/qr';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = 'qr/' . $filename;
        $fullPath = App::config('app.upload_path') . '/' . $path;
        $result->saveToFile($fullPath);

        return $path;
    }
}

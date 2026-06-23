<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Core\App;

class PdfGenerator
{
    public static function generateFromHtml(string $html, string $filename, string $subfolder = 'pdfs'): string
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dir = App::config('app.upload_path') . '/' . $subfolder;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $subfolder . '/' . $filename;
        file_put_contents(App::config('app.upload_path') . '/' . $path, $dompdf->output());

        return $path;
    }
}

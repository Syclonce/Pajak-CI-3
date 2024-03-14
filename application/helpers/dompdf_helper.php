<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

if (!function_exists('generate_pdf')) {
    function generate_pdf($html, $filename = 'document.pdf', $paper = 'A4', $orientation = 'landscape')
    {
        $options = new Options();
        $options->set('chroot', realpath(''));

        $dompdf = new Dompdf($options);
        $dompdf->setPaper($paper, $orientation);

        $dompdf->loadHtml($html);
        // Set paper size and orientation
        $dompdf->render();
        $dompdf->stream($filename . '.pdf', array("Attachment" => false));
    }
}

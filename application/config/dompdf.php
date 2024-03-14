<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['dompdf'] = array(
    'enabled' => TRUE, // Aktifkan atau nonaktifkan Dompdf
    'PDF_backend' => 'auto', // Gunakan auto, cpdf, atau gd
    'enable_php' => TRUE, // Izinkan tag PHP dalam HTML
    'enable_html5_parser' => TRUE, // Aktifkan parser HTML5
    'isPhpEnabled' => TRUE, // Aktifkan PHP
);

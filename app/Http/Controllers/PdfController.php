<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function loadTempleate($pdf)
    {
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();

        //For Header
        $header = $canvas->open_object();
        $canvas->page_text(30, 20, 'Caja Familiar', null, 10, array(0, 0, 0));
        $canvas->page_text(440, 20, "Fecha de impresión: " . date('d/m/Y', strtotime('+5 hours')), null, 8, array(0, 0, 0));
        $canvas->line(30, 55, 560, 55, array(.3, .3, .3), 1);
        $canvas->close_object();
        $canvas->add_object($header, "all");

        //For Footer
        $footer = $canvas->open_object();
        $canvas->line(30, 805, 560, 805, array(.3, .3, .3), 1);
        // $canvas->page_text(30, 810, "www.auditwhole.com | Contabilidad en línea.", null, 8, array(0, 0, 0));
        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0, 0, 0));
        $canvas->close_object();
        $canvas->add_object($footer, "all");
    }
}

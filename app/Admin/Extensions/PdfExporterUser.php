<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;

class PdfExporterUser extends AbstractExporter
{
    public function export()
    {
    	dd($this->getData());
        $pdf = PDF::loadView('pdf.invoice', $data);
		return $pdf->stream();
    }
}
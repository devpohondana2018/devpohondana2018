<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporterDetailsInstallment extends AbstractExporter
{
    public function export()
    {
        Excel::create('Installment Details', function($excel) {

            $excel->sheet('Installment Details', function($sheet) {

                // dd($this->getData());
            	$rows = array();
                $header['head'] = "Installment Details";
                array_push($rows, $header);
                $title['installment_id'] = "Installment ID";
            	$title['amount'] = "Amount";
            	$title['balance'] = "Balance";
                $title['tenor'] = "Tenor";
                $title['status'] = "Status";
                $title['due_date'] = "Due date";
            	array_push($rows, $title);
                $amount = 0;
            	foreach ($this->getData() as $item) {
                    $response['installment_id'] = 'Installment '.$item['id'];
                    $response['amount'] = $item['amount'];
                    $amount += $item['amount'];
                    $response['balance'] = $item['balance'];
                    $response['tenor'] = $item['tenor'];
                    $response['status'] = $item['paid'] == true ? 'Paid' : 'Unpaid';
                    $response['due_date'] = $item['due_date'];

                    array_push($rows, $response);
            	}
                $footer['installment_id'] = "Total";
                $footer['amount'] = $amount;
                $footer['balance'] = "";
                $footer['tenor'] = "";
                $footer['status'] = "";
                $footer['due_date'] = "";
                array_push($rows, $footer);
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}
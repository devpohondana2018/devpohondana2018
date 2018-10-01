<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporterInvestment extends AbstractExporter
{
    public function export()
    {
        Excel::create('Investment', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // dd($this->getData());
            	$rows = array();
                $header['head'] = "Investment";
                array_push($rows, $header);

            	$title['id'] = "Invest ID";
                $title['user'] = "User";
            	$title['loanid'] = "Loan ID";
            	$title['jumlah'] = "Jumlah";
            	$title['status'] = "Status";
            	$title['investment_date'] = "Investment Date";
            	array_push($rows, $title);

                $total = 0;
            	foreach ($this->getData() as $item) {
	                $response['id'] = $item['code'];
                    $response['name'] = $item['user']['name'];
                    $response['loanid'] = $item['loan_id'];
                    $response['amount_invested'] = $item['amount_invested'];
                    $response['status'] = $item['status']['name'];
                    $response['created_at'] = $item['created_at'];

                    $total +=  $item['amount_invested'];

                    array_push($rows, $response);
            	}

                $footer['id'] = "Total";
                $footer['user'] = "";
                $footer['loanid'] = "";
                $footer['jumlah'] = "$total";
                $footer['status'] = "";
                $footer['investment_date'] = "";
                array_push($rows, $footer);


                // This logic get the columns that need to be exported from the table data
                /*$rows = collect($this->getData())->map(function ($item) {
                	// dd($this->get());
                	// $header = $this->getHeaders();
                	// dd($header);
                    $response['id'] = $item['id'];
                    $response['user'] = $item['user']['name'];
                    // dd($response);
                	// $dummy = array_only($item, ['id', 'user', 'Pokok Pinjaman', 'Tenor', 'Status']);
                	// dd($headers);
                    return ($response);
                });*/
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}
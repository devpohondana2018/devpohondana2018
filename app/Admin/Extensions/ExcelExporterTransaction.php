<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use App\Status;

class ExcelExporterTransaction extends AbstractExporter
{
    public function export()
    {
        Excel::create('Transaction', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                $rows = array();
                $header['head'] = "Transaction";
                array_push($rows, $header);

                $title['id'] = "ID";
                $title['user'] = "User";
                $title['amount'] = "Amount";
                $title['type'] = "Type";
                $title['transaction'] = "Transaction";
                $title['status'] = "Status";
                $title['created_at'] = "Created at";
                array_push($rows, $title);

                foreach ($this->getData() as $item) {
                // dd($this->getData());
                    $response['id'] = $item['id'];
                    $response['name'] = $item['user']['name'];
                    $response['amount'] = $item['amount'];
                    $response['type'] = $item['type'];
                    $response['transaction'] = str_replace('App\\', '', $item['transactionable_type']);
                    $response['status_id'] = Status::find($item['status_id'])->name;
                    $response['created_at'] = $item['created_at'];
                    array_push($rows, $response);
            	}


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
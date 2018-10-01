<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Loans', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // dd($this->getData());
            	$rows = array();
                $header['head'] = "Loans";
                array_push($rows, $header);

            	$title['id'] = "Loan ID";
                $title['company'] = "Company";
            	$title['peminjam'] = "Peminjam";
            	$title['pokokpinjaman'] = "Pokok Pinjaman";
            	$title['tenor'] = "Tenor";
            	$title['grade'] = "Grade";
                $title['status'] = "Status";
            	$title['accepted_at'] = "Accepted At";
            	$title['created_at'] = "Created at";
            	array_push($rows, $title);

                $total = 0;
            	foreach ($this->getData() as $item) {
                    $user = \App\User::find($item['user_id']);
                    $company = @$user->company ? $user->company->name : '';
                    $total +=  $item['amount_requested'];
                    $response['id'] = $item['code'];
                    $response['company'] = $company;
                    $response['peminjam'] = $item['user']['name'];
                    $response['pokokpinjaman'] = number_format($item['amount_requested'] ,2, ',', '');
                    $response['tenor'] = $item['tenor']['month'];
                    $response['grade'] = $item['grade']['rank'];
                    $response['status'] = ($item['status']['name'] == 'Pending' ? 
                                                ($item['user']['verified'] == 1 ? 'Verified' : 'Unverified') : 
                                                $item['status']['name']);
                    $response['accepted_at'] = $item['accepted_at'];
                    $response['created_at'] = $item['created_at'];
                    array_push($rows, $response);
            	}

                $footer['id'] = "Total";
                $footer['peminjam'] = "";
                $footer['pokokpinjaman'] = number_format($total ,2, ',', '');
                $footer['tenor'] = "";
                $footer['grade'] = "";
                $footer['status'] = "";
                $footer['created_at'] = "";
                array_push($rows, $footer);

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}
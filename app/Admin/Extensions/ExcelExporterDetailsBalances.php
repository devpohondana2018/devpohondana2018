<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporterDetailsBalances extends AbstractExporter
{
    public function export()
    {
        Excel::create('Details Balances', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // dd($this->getData());
            	$rows = array();
                $header['head'] = "Details Balances";
                array_push($rows, $header);
                $title['user'] = "User";
            	$title['loan_id'] = "Loan ID";
            	$title['amount_borrowed'] = "Amount borrowed";
            	$title['total_paid'] = "Total Paid";
                $title['balance'] = "Balance";
            	array_push($rows, $title);
                $amount_borrowed = 0;
                $total_paid = 0;
                $balance = 0;
            	foreach ($this->getData() as $item) {
                    $loans = \App\Loan::where('id', '=', $item['id'])
                                    ->first();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                        $loantenor = \App\LoanTenor::where('id', '=', $loans->loan_tenor_id)->first();
                        $installments = \App\Installment::where('installmentable_id', '=', $loans->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                        $loanpaid = $loans->amount_borrowed / $loantenor->month * $installments;
                        $totalpaid = $totalpaid + $loanpaid;
                    $response['user'] = \App\User::find($item['user_id'])->name;
                    $response['loan_id'] = 'Loan '.$item['id'];
                    $response['amount_borrowed'] = $item['amount_borrowed'];
                    $amount_borrowed += $item['amount_borrowed'];
                    $response['total_paid'] = $totalpaid;
                    $total_paid += $totalpaid;
                    $response['balance'] = $item['amount_borrowed'] - $totalpaid;
                    $balance += $item['amount_borrowed'] - $totalpaid;

                    array_push($rows, $response);
            	}


                $footer['user'] = "Total";
                $footer['loan_id'] = "";
                $footer['amount_borrowed'] = $amount_borrowed;
                $footer['total_paid'] = $total_paid;
                $footer['balance'] = $balance;
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
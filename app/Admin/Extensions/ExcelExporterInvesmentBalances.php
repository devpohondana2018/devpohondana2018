<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporterInvesmentBalances extends AbstractExporter
{
    public function export()
    {
        Excel::create('Investment Balances', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // dd($this->getData());
            	$rows = array();
                $header['head'] = "Investment Balances";
                array_push($rows, $header);

                $title['id'] = "User ID";
                $title['name'] = "Name";
            	$title['total_invested'] = "Total Invested";
            	$title['total_loan_paid'] = "Total Loan Paid";
            	$title['balance'] = "Balance";
            	array_push($rows, $title);
                $footertotal_invested = 0;
                $footertotal_loan_paid = 0;
                $footerbalance = 0;
            	foreach ($this->getData() as $item) {
                    $investments = \App\Investment::where('user_id', '=', $item['id']);
                    if (isset($_GET['company_id'])) {
                            $filter = $_GET['company_id'];
                            if (!empty($filter))
                            $investments->whereHas('user', function ($query) use ($filter) {
                                $query->where('company_id', '=', $filter);
                            });  
                    }
                    $investments = $investments->get();
                        $totalinvested = 0;
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                        $totalborrowed = 0;
                        foreach ($investments as $investment) {
                            $totalinvested = $totalinvested + $investment->amount_invested;
                            $loans = \App\Loan::where('id', '=', $investment->loan_id)
                                    ->get();
                                foreach ($loans as $loan) {
                                    $totalborrowed = $totalborrowed + $investment->amount_invested;
                                    $loantenor = \App\LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                                    $installments = \App\Installment::where('installmentable_id', '=', $investment->id)->where('installmentable_type', '=', 'App\Investment')->where('paid', '=', 1)->count();
                                    $loanpaid = $investment->amount_invested / $loantenor->month * $installments;
                                    $totalpaid = $totalpaid + $loanpaid;
                                }
                        }
                    $response['id'] = $item['id'];
                    $response['name'] = $item['name'];
                    $response['total_invested'] = $totalinvested;
                    $response['total_loan_paid'] = $totalpaid;
                    $response['balance'] = $totalinvested - $totalpaid;

                    $footertotal_invested += $totalinvested;
                    $footertotal_loan_paid += $totalpaid;
                    $footerbalance += $totalinvested - $totalpaid;
                    array_push($rows, $response);
            	}

                $footer['id'] = "Total";
                $footer['name'] = "";
                $footer['total_invested'] = $footertotal_invested;
                $footer['total_loan_paid'] = $footertotal_loan_paid;
                $footer['balance'] = $footerbalance;
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
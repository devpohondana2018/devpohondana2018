<?php

namespace App\Admin\Extensions;
use App\User;
use App\Installment;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExcelExpoterLoanBalances extends AbstractExporter
{
    public function export()
    {
        Excel::create('Loan Balances', function($excel) {

            $excel->sheet('Loan Balances', function($sheet) {

                // dd($this->getData());
                $rows = array();
                $header['head'] = "Loan Balances";
                array_push($rows, $header);
                $title['name'] = "Name";
                $title['total_borrowed'] = "Total Borrowed";
                $title['total_paid'] = "Total Paid";
                $title['balance'] = "Balance";
                array_push($rows, $title);
                $total_borrowed = 0;
                $totalpaidfooter = 0;
                $balance = 0;
                    foreach ($this->getData() as $item) {
                        $loans = \App\Loan::where('user_id', '=', $item['id'])->where('loans.status_id','=', 3);
                        if (isset($_GET['company_id'])) {
                            $filter = $_GET['company_id'];
                            if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('company_id', '=', $filter);
                            });
                        }
                        $response['name'] = $item['name'];
                        $response['total_borrowed'] = $loans->sum('loans.amount_borrowed');
                        $total_borrowed += $loans->sum('loans.amount_borrowed'); 
                        $loans = $loans->get();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                        $totalborrowed = 0;
                        foreach ($loans as $loan) {
                            $totalborrowed = $totalborrowed + $loan->amount_borrowed;
                            $loantenor = \App\LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                            $installments = \App\Installment::where('installmentable_id', '=', $loan->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                            $loanpaid = $loan->amount_borrowed / $loantenor->month * $installments;
                            $totalpaid = $totalpaid + $loanpaid;
                        }
                        $response['total_paid'] = $totalpaid;
                        $totalpaidfooter += $totalpaid;
                        $response['balance'] = $totalborrowed - $totalpaid;
                        $balance += $totalborrowed - $totalpaid;
                        array_push($rows, $response);
                    }
                $footer['name'] = "Total";
                $footer['total_borrowed'] = number_format($total_borrowed,2, ',', '');
                $footer['total_paid'] = number_format($totalpaidfooter,2, ',', '');
                $footer['balance'] = number_format($balance,2, ',', '');
                array_push($rows, $footer);          
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}
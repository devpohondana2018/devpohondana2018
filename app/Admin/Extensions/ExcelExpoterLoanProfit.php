<?php

namespace App\Admin\Extensions;
use App\User;
use App\LoanGrade;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoterLoanProfit extends AbstractExporter
{
    public function export()
    {
        Excel::create('Loan Profit', function($excel) {

            $excel->sheet('Loan Profit', function($sheet) {

                // dd($this->getData());
            	$rows = array();
                $header['head'] = "Loan Profit";
                array_push($rows, $header);

                $title['id'] = "Loan ID";
            	$title['user'] = "User";
            	$title['grade'] = "Grade";
            	$title['basicloan'] = "Basic Loan";
            	$title['interestrate'] = "Interest Rate";
            	$title['interestamount'] = "Interest Amount";
            	$title['platformrate'] = "Platform Rate";
                $title['platform_amount'] = "Platform Amount";
                $title['invest_rate'] = "Invest Rate";
                $title['invest_amount'] = "Invest Amount";
                // $title['profit_rate'] = "Profit Rate";
            	$title['profit_amount'] = "Profit Amount";
            	array_push($rows, $title);

                $totalbasic = 0;
                $totalinterestamount = 0;
                $totalplatform_amount = 0;
                $totalinvest_amount = 0;
                $totalprofit_amount = 0;
                foreach ($this->getData() as $item) {
                    $response['id'] = $item['id'];
                    $response['user'] = User::where('id', '=', $item['user_id'])->first()->name;
                    $response['grade'] = LoanGrade::where('id', '=', $item['loan_grade_id'])->first()->rank;
                    $response['basicloan'] = $item['amount_requested'];
                    $response['interestrate'] = $item['interest_rate'];
                    $response['interestamount'] = $item['interest_fee'];
                    $response['platformrate'] = $item['provision_rate'];
                    $response['platform_amount'] = $item['provision_fee'];
                    $response['invest_rate'] = $item['invest_rate'];
                    $response['invest_amount'] = $item['invest_fee'];
                    // $response['profit_rate'] = $item['interest_rate'] + $item['provision_rate'] - $item['invest_rate'];
                    $response['profit_amount'] = $item['interest_fee'] + $item['provision_fee'] - $item['invest_fee'];
                    // $response['grade'] = $item['grade']['rank'];
                    // $response['status'] = $item['status']['name'];
                    // $response['created_at'] = $item['created_at'];
                    $totalbasic += $item['amount_requested'];
                    $totalinterestamount += $item['interest_fee'];
                    $totalplatform_amount += $item['provision_fee'];
                    $totalinvest_amount += $item['invest_fee'];
                    $totalprofit_amount += $item['interest_fee'] + $item['provision_fee'] - $item['invest_fee'];

                    array_push($rows, $response);
            	}


                $footer['id'] = "Total";
                $footer['user'] = "";
                $footer['grade'] = "";
                $footer['basicloan'] = number_format($totalbasic,2, ',', '');
                $footer['interestrate'] = "";
                $footer['interestamount'] = number_format($totalinterestamount,2, ',', '');
                $footer['platformrate'] = "";
                $footer['platform_amount'] = number_format($totalplatform_amount,2, ',', '');
                $footer['invest_rate'] = "";
                $footer['invest_amount'] = number_format($totalinvest_amount,2, ',', '');
                // $footer['profit_rate'] = "";
                $footer['profit_amount'] = number_format($totalprofit_amount,2, ',', '');
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
<?php

namespace App\Admin\Extensions;
use App\User;
use App\Installment;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExcelExpoterAging extends AbstractExporter
{
    public function export()
    {
        Excel::create('Agings', function($excel) {

            $excel->sheet('Agings', function($sheet) {

                // dd($this->getData());
            	$rows = array();
                $header['head'] = "Agings";
                array_push($rows, $header);
                $now = Carbon::now()->format('Y-m-d');
                $thirty = Carbon::now()->addDays(-30)->format('Y-m-d');
                $thirtyone = Carbon::now()->addDays(-31)->format('Y-m-d');
                $sixty = Carbon::now()->addDays(-60)->format('Y-m-d');;
                $sixtyone = Carbon::now()->addDays(-61)->format('Y-m-d');
                $ninety = Carbon::now()->addDays(-90)->format('Y-m-d');
                $ninetyone = Carbon::now()->addDays(-91)->format('Y-m-d');
                $title['name'] = "Name";
                $title['zerotothiry'] = "0 - 30";
                $title['thietyonetosixty'] = "31 - 60";
                $title['sixtyonetoninety'] = "61 - 90";
                $title['morethanninetyone'] = "> 91";
                $title['total'] = "Total";
                array_push($rows, $title);
                $zerotothiry = 0;
                $thirtyonetosixty = 0;
                $sixtyonetoninety = 0;
                $morethanninetyone = 0;
                $total = 0;
                if (isset($_GET['company_id'])) {
                    foreach ($this->getData() as $item) {
                        $response['user'] = User::where('id', '=', $item['id'])->first()->name;
                        $response['zerotothiry'] = Installment::where('due_date', '<', Carbon::now()->format('Y-m-d'))->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $response['thirtyonetosixty'] = Installment::whereBetween('due_date', [$sixty,$thirtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )
                        ->where('installments.paid', 0)->where('loans.user_id', '=', $item['id'])->sum('amount');
                        $response['sixtyonetoninety'] = Installment::whereBetween('due_date', [$ninety,$sixtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->whereBetween('due_date', [$thirty,$now])->where('users.company_id', '=', $_GET['company_id'] )
                        ->where('installments.paid', 0)->where('loans.user_id', '=', $item['id'])->sum('amount');
                        $response['morethanninetyone'] = Installment::where('due_date', '<', $ninetyone)->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $response['total'] = Installment::where('due_date', '<', $now)->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $zerotothiry += Installment::where('due_date', '<', Carbon::now()->format('Y-m-d'))->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $thirtyonetosixty += Installment::whereBetween('due_date', [$sixty,$thirtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $sixtyonetoninety += Installment::whereBetween('due_date', [$ninety,$sixtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->whereBetween('due_date', [$thirty,$now])->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $morethanninetyone += Installment::where('due_date', '<', $ninetyone)->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])->sum('amount');
                        $total += Installment::where('due_date', '<', $now)->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id'] )->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        array_push($rows, $response);
                    }
                }else{
                    foreach ($this->getData() as $item) {
                        $response['user'] = User::where('id', '=', $item['id'])->first()->name;
                        $response['zerotothiry'] = Installment::where('due_date', '<', Carbon::now()->format('Y-m-d'))->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->whereBetween('due_date', [$thirty,$now])->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $response['thietyonetosixty'] = Installment::whereBetween('due_date', [$sixty,$thirtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $response['sixtyonetoninety'] = Installment::whereBetween('due_date', [$ninety,$sixtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $response['morethanninetyone'] = Installment::where('due_date', '<', $ninetyone)->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $response['total'] = Installment::where('due_date', '<', $now)->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $zerotothiry += Installment::where('due_date', '<', Carbon::now()->format('Y-m-d'))->join('loans', 'loans.id', '=', 'installmentable_id')->join('users', 'users.id', '=', 'loans.user_id')->whereBetween('due_date', [$thirty,$now])->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $thirtyonetosixty += Installment::whereBetween('due_date', [$sixty,$thirtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $sixtyonetoninety += Installment::whereBetween('due_date', [$ninety,$sixtyone])->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $morethanninetyone += Installment::where('due_date', '<', $ninetyone)->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');
                        $total += Installment::where('due_date', '<', $now)->join('loans', 'loans.id', '=', 'installmentable_id')->where('loans.user_id', '=', $item['id'])
                        ->where('installments.paid', 0)->sum('amount');

                        array_push($rows, $response);
                    }
                $footer['user'] = "Total";
                $footer['zerotothiry'] = number_format($zerotothiry,2, ',', '');
                $footer['thietyonetosixty'] = number_format($thirtyonetosixty,2, ',', '');
                $footer['sixtyonetoninety'] = number_format($sixtyonetoninety,2, ',', '');
                $footer['morethanninetyone'] = number_format($morethanninetyone,2, ',', '');
                $footer['total'] = number_format($total,2, ',', '');
                array_push($rows, $footer);                    
                }
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}
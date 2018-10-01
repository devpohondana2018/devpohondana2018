<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\BankAccount;

class ExcelExporterInstallment extends AbstractExporter
{
    public function export()
    {
        Excel::create('Installment', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // dd($this->getData());
                $rows = array();
                $header['head'] = "Installment";
                array_push($rows, $header);
                $title['id'] = "Installment ID";
                $title['nama'] = "Nama / Alamat";
                $title['norek'] = "Nomor Rekening";
                $title['tenor'] = "Jangka Waktu";
                $title['jatuhtempo'] = "Jatuh Tempo";
                $title['plafond'] = "Plafond";
                $title['balance'] = "Saldo Akhir";
                 if ($_GET['installmentable_type'] == 'App\Loan') {
                    $title['pokok'] = "Pokok Pinjaman";
                    $title['bunga'] = "Bunga";
                }  
                $title['amount'] = "Total Angsuran";
                $title['status'] = "Status Pembayaran";
                array_push($rows, $title);
                $total = 0;
                $totalplafond = 0;
                $totalpokok = 0;
                $totalbunga = 0;
                // dd($this->getData());
                foreach ($this->getData() as $item) {
                        if (isset($_GET['installmentable_type'])) {
                            $companysource = $_GET['installmentable_type'];
                            if ($companysource == 'App\Loan') {# code...
                                    $installmentstable = DB::table('installments');
                                    $companiestable =   $installmentstable->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                                        ->join('users', 'loans.user_id', '=', 'users.id')
                                                        ->join('companies', 'users.company_id', '=', 'companies.id')
                                                        ->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id')
                                                        ->select('companies.affiliate', 'companies.name', 'users.name as username', 'users.home_address', 'installments.balance', 'loans.amount_borrowed as plafond', 'loans.interest_fee as invest_fee', 'loans.amount_total', 'users.id as user_id', 'installments.due_date', 'loan_tenors.month', 'loans.amount_requested as amount_requested', 'installments.amount as amount')
                                                        ->where('installments.id', '=', $item['id'])
                                                        ->first();
                                                        // dd($companiestable);

                                            if (!empty($companiestable)) {
                                                $companiestable->name = $companiestable->affiliate == 0 ? 'Non Rekanan': $companiestable->name;
                                            }
                            }elseif($companysource == 'App\Investment'){
                                    $installmentstable = DB::table('installments');
                                    $companiestable =   $installmentstable->join('investments', 'installments.installmentable_id', '=', 'investments.id')
                                                        ->join('users', 'investments.user_id', '=', 'users.id')
                                                        ->join('companies', 'users.company_id', '=', 'companies.id')
                                                        ->select('companies.affiliate', 'companies.name', 'users.name as username', 'users.home_address', 'installments.balance', 'investments.amount_invested as plafond', 'investments.invest_fee as invest_fee', 'investments.amount_total', 'users.id as user_id', 'installments.due_date')
                                                        ->where('installments.id', '=', $item['id'])
                                                        ->first();
                                            if (!empty($companiestable)) {
                                                $companiestable->name = $companiestable->affiliate == 0 ? 'Non Rekanan': $companiestable->name;
                                            }
                            }
                        }else{
                            $companysource = '';
                        }
                        if (!empty($companiestable)) {
                            $bankaccount = BankAccount::where('user_id', $companiestable->user_id)->first();
                        }
                    // dd($companiestable);
                    // dd($bankaccount);
                    $status = $item['paid'] == true ? 'Paid' : 'Unpaid';
                    $response['id'] = $item['code'];
                    $response['name'] = !empty($companiestable) ? "$companiestable->username / $companiestable->home_address" : '' ;
                    $response['norek'] = !empty($bankaccount) ? $bankaccount->account_number : '' ;
                    $response['tenor'] = $item['tenor'];
                    $response['jatuhtempo'] = !empty($companiestable) ? $companiestable->due_date : '' ;
                    $response['plafond'] = !empty($companiestable) ? $companiestable->amount_total : '' ;
                    $response['balance'] = !empty($companiestable) ? $companiestable->balance : '' ;
                    if ($_GET['installmentable_type'] == 'App\Loan') {
                        $installmentstable = DB::table('installments');
                        $interest_fee =   $installmentstable->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                            ->join('loan_tenors as lt', 'lt.id', '=', 'loans.loan_tenor_id')
                                            ->where('installments.id', '=', $item['id'])
                                            ->first();

                        $response['pokok'] = number_format($item['amount'] - ($interest_fee->amount - ($interest_fee->amount_requested / $interest_fee->month)) ,2, ',', '');
                        $response['bunga'] =  $interest_fee->amount - ($interest_fee->amount_requested / $interest_fee->month);
                        $totalpokok += $response['pokok'];
                        $totalbunga += $response['bunga'];
                    }
                    $response['amount'] = number_format($item['amount'],2, ',', '');
                    $total += $item['amount'];
                    $totalplafond += !empty($companiestable) ? $companiestable->amount_total : 0;
                    $response['status'] = ($item['paid'] == 1) ? "Paid" : "Unpaid" ;

                    array_push($rows, $response);
                }

                $footer['id'] = "Total";
                $footer['name'] = "";
                $footer['norek'] = "";
                $footer['tenor'] = ""; 
                $footer['jatuhtempo'] = ""; 
                $footer['plafond'] = '';//$totalplafond; 
                $footer['balance'] = ""; 
                if ($_GET['installmentable_type'] == 'App\Loan') {
                        $footer['pokok'] = number_format($total - $totalbunga,2, ',', '');
                        $footer['bunga'] = number_format($totalbunga,2, ',', '');
                    }
                $footer['amount'] = number_format($total,2, ',', '');
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
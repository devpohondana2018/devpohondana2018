<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Alert;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Callout;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Loan;
use App\LoanTenor;
use App\LoanGrade;
use App\Investment;
use App\Installment;
use App\Transaction;
use App\Status;
use App\User;
use App\Company;
use DB;
use PDF;
use Carbon\Carbon;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use App\Libraries\CurrencyFormat;

class ReportController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Report');

            $content->row(function ($row) {
                $row->column(4, new InfoBox('Total Users', 'users', 'aqua', '/admin/users', User::count()));
                $row->column(4, new InfoBox('Total Loans', 'shopping-cart', 'green', '/admin/loans', Loan::count()));
                $row->column(4, new InfoBox('Total Investments', 'book', 'yellow', '/admin/investments', Investment::count()));
            });

            $content->row(function ($row) {
                $row->column(4, new InfoBox('User Report', 'users', 'aqua', '/admin/reports/users','30 Days'));
                // $row->column(4, new InfoBox('Report', 'shopping-cart', 'green', '/admin/reports/loans','Loan'));
                // $row->column(4, new InfoBox('Report', 'book', 'yellow', '/admin/reports/investments','Investment'));
            });

        });
    }

    public function users(Request $request)
    {   
        return Admin::content(function (Content $content) use ($request){
            $content->header('7 Days User Report');
            if ($request->input('days')) {
                $due_date_days = $request->input('days');
            }else{
                $due_date_days = Carbon::now()->addDays(7)->format('Y-m-d');
            }
            $company   = $request->input('company');
            $companyId = $request->input('company_id');
            $content->breadcrumb(
             ['text' => 'Report OJK', 'url' => '/ojk']
            );            

            // $due_date_days = Carbon::now()->addDays($daysstr)->format('Y-m-d');
            $users = DB::table('users')
                        ->join('loans', 'users.id', '=', 'loans.user_id')
                        ->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')
                        ->join('companies', 'companies.id', '=', 'users.company_id')
                        ->join('bank_accounts', 'users.id', '=', 'bank_accounts.user_id')
                        ->join('banks', 'banks.id', '=', 'bank_accounts.bank_id')
                        ->where('loans.status_id', 3)
                        ->join('installments', function ($join) use ($due_date_days) {
                            $join->on('installments.installmentable_id', '=', 'loans.id')
                                 ->where('installments.installmentable_type', '=', 'App\\Loan')
                                 ->where('paid',0)
                                 ->whereDate('due_date','<',$due_date_days);
                        })
                        ->orderBy('companies.name','installments.due_date')
                         ->select([
                            'users.name as user_name', 
                            'loans.interest_rate as interest_rate', 
                            'loans.amount_total as original_balance', 
                            'loans.amount_paid as current_balance', 
                            'users.email as user_email',
                            'bank_accounts.account_number as no_rek',
                            'users.npwp_no as user_npwp_no',
                            'users.pob as user_pob',
                            'users.dob as user_dob',
                            'users.id_no as user_id_no',
                            'users.home_phone as user_home_phone',
                            'users.mobile_phone as user_mobile_phone', 
                            'users.home_postal_code as user_poscode', 
                            'users.home_address as user_address', 
                            'companies.name as company_name',
                            'loans.amount_total as loan_total',
                            'loan_tenors.month as loan_tenor',
                            'installments.*',
                            'banks.name as bank_name',
                            'bank_accounts.account_number as bank_account_number', 
                            'companies.affiliate', 
                            DB::raw('(select due_date from installments where installmentable_id = loans.id order by id asc limit 1) as loan_start_date'), 
                            DB::raw('(select due_date from installments where installmentable_id = loans.id order by id desc limit 1) as loan_end_date')
                        ]);

            if ($company == 'rekanan') {
                $users = $users->where('companies.affiliate', 1);
            }else if ($company == 'nonrekanan') {
                $users = $users->where('companies.affiliate', 0);
            }

            if (!empty($companyId)) {
                $users = $users->where('companies.id', $companyId);
            }
              if (isset($_GET['report_'])) {
                    $formatUser = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total User Installment</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmount = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Amount</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>'; 

                    $formatAmountBalance = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Original Balance</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>'; 

                    $formatAmountLoan = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Borrowed</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>'; 

                    $contentUser   = sprintf($formatUser, count($users->get()));
                    $contentAmountBalance = sprintf($formatAmountBalance, CurrencyFormat::rupiah($users->sum('loans.amount_total'), true));
                    $contentAmountLoan = sprintf($formatAmountLoan, CurrencyFormat::rupiah($users->sum('loans.amount_total'), true));
                    $contentAmount = sprintf($formatAmount, CurrencyFormat::rupiah($users->sum('amount'), true));

                    $content->row(function($row) use ($contentUser, $contentAmount, $contentAmountBalance, $contentAmountLoan) {
                        $row->column(4, $contentUser);
                        $row->column(4, $contentAmountBalance);
                        $row->column(4, $contentAmountLoan);
                        $row->column(4, $contentAmount);
                    });
                }
            $users = $users->get();

            $companies = Company::select('id', 'name')
                       ->where('affiliate', 1)
                       ->get();
            // dd($users);
            $content->row(view('admin.reports.users',compact('users', 'companies')));
        });
    }

    public function loans()
    {
        return Admin::content(function (Content $content) {
            $content->header('Loan Report');

            $content->row(function ($row) {
                $row->column(3, new InfoBox('New Users', 'users', 'aqua', '/demo/users', '1024'));
                $row->column(3, new InfoBox('New Orders', 'shopping-cart', 'green', '/demo/orders', '150%'));
                $row->column(3, new InfoBox('Articles', 'book', 'yellow', '/demo/articles', '2786'));
                $row->column(3, new InfoBox('Documents', 'file', 'red', '/demo/files', '698726'));
            });
        });
    }

    public function investments()
    {
        return Admin::content(function (Content $content) {
            $content->header('Investment Report');

            $content->row(function ($row) {
                $row->column(3, new InfoBox('New Users', 'users', 'aqua', '/demo/users', '1024'));
                $row->column(3, new InfoBox('New Orders', 'shopping-cart', 'green', '/demo/orders', '150%'));
                $row->column(3, new InfoBox('Articles', 'book', 'yellow', '/demo/articles', '2786'));
                $row->column(3, new InfoBox('Documents', 'file', 'red', '/demo/files', '698726'));
            });
        });
    }
    public function exportuserojk(Request $request)
    {
        Excel::create('Report', function($excel) use ($request){

            $excel->sheet('Sheetname', function($sheet) use ($request) {

                if ($request->input('days')) {
                $due_date_days = $request->input('days');
                        
                    }else{
                        $due_date_days = Carbon::now()->addDays(7)->format('Y-m-d');
                }
                $company   = $request->input('company');
                $companyId = $request->input('company_id');
                $users = DB::table('users')
                        ->join('loans', 'users.id', '=', 'loans.user_id')
                        ->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')
                        ->join('companies', 'companies.id', '=', 'users.company_id')
                        ->join('bank_accounts', 'users.id', '=', 'bank_accounts.user_id')
                        ->join('banks', 'banks.id', '=', 'bank_accounts.bank_id')
                        ->where('loans.status_id', 3)
                        ->join('installments', function ($join) use ($due_date_days) {
                            $join->on('installments.installmentable_id', '=', 'loans.id')
                                 ->where('installments.installmentable_type', '=', 'App\\Loan')
                                 ->where('paid',0)
                                 ->whereDate('due_date','<',$due_date_days);
                        })
                        ->orderBy('companies.name','installments.due_date')
                         ->select('users.name as user_name', 'loans.interest_rate as interest_rate', 'loans.amount_total as original_balance', 'loans.amount_paid as current_balance', 'users.email as user_email','bank_accounts.account_number as no_rek','users.npwp_no as user_npwp_no','users.pob as user_pob','users.dob as user_dob','users.id_no as user_id_no','users.home_phone as user_home_phone','users.mobile_phone as user_mobile_phone', 'users.home_postal_code as user_poscode', 'users.home_address as user_address', 'companies.name as company_name','loans.amount_total as loan_total','loan_tenors.month as loan_tenor','installments.*','banks.name as bank_name','bank_accounts.account_number as bank_account_number', 'companies.affiliate', DB::raw('(select due_date from installments where installmentable_id = loans.id order by id asc limit 1) as loan_start_date'), DB::raw('(select due_date from installments where installmentable_id = loans.id order by id desc limit 1) as loan_end_date'));

            if ($company == 'rekanan') {
                $users = $users->where('companies.affiliate', 1);
            }else if ($company == 'nonrekanan') {
                $users = $users->where('companies.affiliate', 0);
            }

            if (!empty($companyId)) {
                $users = $users->where('companies.id', $companyId);
            }
                        $users = $users->get();
            // dd($users);

               // dd($users->get());
                $rows = array();
                $header['head'] = "Report";
                array_push($rows, $header);

                $title['rekening'] = "Rekening";
                $title['nama_debitur'] = "Nama Debitur";
                // $title['bank'] = "Bank";
                // $title['perusahaan'] = "Perusahaan";
                $title['alamat'] = "Alamat";
                $title['kode_pos'] = "Kode POS";
                $title['nomor_hp'] = "Nomor HP";
                $title['telepon_rumah'] = "Telepon Rumah";
                $title['nomor_ktp'] = "Nomor KTP/SIM";
                $title['tempat_lahir'] = "Tempat Lahir";
                $title['tanggal_lahir'] = "Tanggal Lahir";
                $title['no_npwp'] = "No NPWP";
                // $title['no_rekening'] = "No Rekening";
                $title['tanggal_mulai'] = "Tanggal Mulai";
                $title['tanggal_jatuh_tempo'] = "Tanggal Jatuh Tempo";
                $title['jangka_waktu'] = "Jangka Waktu";
                $title['original_balance'] = "Original Balance";
                $title['current_balance'] = "Current Balance";
                $title['interest_rate'] = "Interest Rate";
                $title['total_pinjaman'] = "Total Pinjaman";
                $title['tenor_cicilan'] = "Tenor Cicilan";
                $title['tanggal_angsuran'] = "Tanggal Angsuran";
                $title['jumlah_sngsuran'] = "Jumlah Angsuran";
                array_push($rows, $title);
                $totaloriginal = 0;
                $totalcurrent = 0;
                $totalpinjaman = 0;
                $totalangsuran = 0;
                foreach ($users as $item) {
                    $item = (array) $item;
                    // $response['bank_name'] = $item['bank_name'];
                    $response['bank_account_number'] = decrypt($item['bank_account_number']);
                    // $response['company'] = $item['affiliate'] == 1 ? $item['company_name'] : 'Non Rekanan';
                    $response['user_name'] = $item['user_name'];
                    $response['user_address'] = $item['user_address'];
                    $response['user_poscode'] = $item['user_poscode'];
                    $response['user_mobile_phone'] = $item['user_mobile_phone'];
                    $response['user_home_phone'] = $item['user_home_phone'];
                    $response['user_id_no'] = $item['user_id_no'];
                    $response['user_pob'] = $item['user_pob'];
                    $response['user_dob'] = $item['user_dob'];
                    $response['user_npwp_no'] = $item['user_npwp_no'];
                    // $response['no_rek'] = $item['no_rek'];
                    $response['loan_start_date'] = $item['loan_start_date'];
                    $response['loan_end_date'] = $item['loan_end_date'];
                    $response['loan_tenor'] = $item['loan_tenor'];
                    $response['original_balance'] = $item['original_balance'];
                    $response['current_balance'] = $item['original_balance'] - $item['current_balance'];
                    $response['interest_rate'] = $item['interest_rate'];
                    $response['loan_total'] = $item['loan_total'];
                    $response['tenor'] = $item['tenor'];
                    $response['due_date'] = $item['due_date'];
                    $response['amount'] = $item['amount'];

                    $totaloriginal += $item['original_balance'];
                    $totalcurrent += $item['original_balance'] - $item['current_balance'];
                    $totalpinjaman += $item['loan_total'];
                    $totalangsuran += $item['amount'];
                    array_push($rows, $response);
                }


                    $footer['bank_account_number'] = "Total";
                    $footer['user_name'] = "";
                    $footer['user_address'] = "";
                    $footer['user_poscode'] = "";
                    $footer['user_mobile_phone'] = "";
                    $footer['user_home_phone'] = "";
                    $footer['user_id_no'] = "";
                    $footer['user_pob'] = "";
                    $footer['user_dob'] = "";
                    $footer['user_npwp_no'] ="";
                    $footer['loan_start_date'] = "";
                    $footer['loan_end_date'] = "";
                    $footer['loan_tenor'] = "";
                    $footer['original_balance'] = number_format($totaloriginal,2, ',', '');
                    $footer['current_balance'] = number_format($totalcurrent,2, ',', '');
                    $footer['interest_rate'] = "";
                    $footer['loan_total'] = number_format($totalpinjaman,2, ',', '');
                    $footer['tenor'] = "";
                    $footer['due_date'] = "";
                    $footer['amount'] = number_format($totalangsuran,2, ',', '');
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
    public function exportuserpdf(Request $request)
    {
        $urlparams = $request->all();
        $company   = $request->input('company');
        $companyId = $request->input('company_id');
        if ($request->input('days')) {
                $due_date_days = $request->input('days');
                
            }else{
                $due_date_days = Carbon::now()->addDays(7)->format('Y-m-d');
        }
        // $due_date_days = Carbon::now()->addDays(7)->format('Y-m-d');
        $users = DB::table('users')
                    ->join('loans', 'users.id', '=', 'loans.user_id')
                    ->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')
                    ->join('companies', 'companies.id', '=', 'users.company_id')
                    ->join('bank_accounts', 'users.id', '=', 'bank_accounts.user_id')
                    ->join('banks', 'banks.id', '=', 'bank_accounts.bank_id')
                    ->where('loans.status_id', 3)
                    ->join('installments', function ($join) use ($due_date_days) {
                        $join->on('installments.installmentable_id', '=', 'loans.id')
                             ->where('installments.installmentable_type', '=', 'App\\Loan')
                             ->where('paid',0)
                             ->whereDate('due_date','<',$due_date_days);
                    })
                    ->orderBy('companies.name','installments.due_date')
                     ->select('users.name as user_name', 'loans.interest_rate as interest_rate', 'loans.amount_total as original_balance', 'loans.amount_paid as current_balance', 'users.email as user_email','bank_accounts.account_number as no_rek','users.npwp_no as user_npwp_no','users.pob as user_pob','users.dob as user_dob','users.id_no as user_id_no','users.home_phone as user_home_phone','users.mobile_phone as user_mobile_phone', 'users.home_postal_code as user_poscode', 'users.home_address as user_address', 'companies.name as company_name','loans.amount_total as loan_total','loan_tenors.month as loan_tenor','installments.*','banks.name as bank_name','bank_accounts.account_number as bank_account_number', 'companies.affiliate', DB::raw('(select due_date from installments where installmentable_id = loans.id order by id asc limit 1) as loan_start_date'), DB::raw('(select due_date from installments where installmentable_id = loans.id order by id desc limit 1) as loan_end_date'));

        if ($company == 'rekanan') {
            $users = $users->where('companies.affiliate', 1);
        }else if ($company == 'nonrekanan') {
            $users = $users->where('companies.affiliate', 0);
        }

        if (!empty($companyId)) {
            $users = $users->where('companies.id', $companyId);
        }
        $users = $users->get();
        // return response()->json($installments);
        view()->share('users',$users);
        $pdf = PDF::loadView('admin.users.export.ojk');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('ojk.pdf');
        // return $users;
        // return view('admin.users.export.pdf', compact('users'));

    }
}

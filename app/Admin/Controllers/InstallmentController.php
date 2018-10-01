<?php

namespace App\Admin\Controllers;

use DB;
use PDF;
use Exception;
use Carbon\Carbon;
use App\Loan;
use App\User;
use App\Company;
use App\LoanTenor;
use App\Status;
use App\Installment;
use App\BankAccount;
use App\Investment;
use App\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Admin\Extensions\ExcelExporterInstallment;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CashInInstallment;
use App\Events\CashOutInstallment;
use App\Libraries\CurrencyFormat;

class InstallmentController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Installments');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Installments', 'url' => '/installments']
            );
            if (isset($_GET['report_'])) {

                    $installments = DB::table('installments')
                            ->whereNull('installments.deleted_at');

                    $installmentssumbunga = 0;
                    $installmentssumpokokpinjaman = 0;
                    if (isset($_GET['installmentable_type'])) {
                        $companysource = $_GET['installmentable_type'];
                        if ($companysource == 'App\Loan') {
                            if (!isset($_GET['direct'])) {
                                $installments->join('loans', 'loans.id', '=', 'installments.installmentable_id')
                                            ->join('users', 'users.id', '=', 'loans.user_id')
                                            ->whereNull('loans.deleted_at')
                                            ->where('installments.installmentable_type', '=', 'App\Loan')
                                            ->where('users.verified', '=', 1)
                                            ->where('users.active', '=', 1)
                                            ->where('loans.status_id', '=', 3);
                            }
                        }else {
                            if (!isset($_GET['direct'])) {
                                $installments->join('investments', 'investments.id', '=', 'installments..installmentable_id')
                                            ->where('installments.installmentable_type', '=', 'App\Investment')
                                        ->where('investments.paid', '=', '1');
                            }
                        }
                    }

                    if (isset($_GET['installmentable_id'])) {
                        $filter = $_GET['installmentable_id'];
                        if (!empty($filter))
                            $installments->where('installmentable_id', '=', $filter);
                    }

                    if (isset($_GET['Tenor'])) {
                        $filter = $_GET['Tenor'];
                        if (!empty($filter))
                            if ($_GET['installmentable_type'] == "App\Loan" ) {
                                $installments->join('loans as loanstenorfilter', 'installments.installmentable_id', '=', 'loanstenorfilter.id')
                                            ->where('loans.loan_tenor_id','=',$filter);
                    //             ->join('companies', 'users.company_id', '=', 'companies.id')
                                    // dd($installments->get());
                            }else{
                                $installments->join('investments as investmentstenorfilter', 'installments.installmentable_id', '=', 'investmentstenorfilter.id')
                                            ->join('loans', 'investmentstenorfilter.loan_id', '=', 'loans.id')
                                            ->where('loans.loan_tenor_id', '=', $filter);
                                // dd($installments->get());
                            }
                    }

                    if (isset($_GET['due_date'])) {
                        $filter = $_GET['due_date'];
                        if (!empty($filter))
                        $installments = $this->InstallmentBeetwen($installments, 'due_date', $filter);
                    }else{
                        $filter = array("start" => Carbon::now()->addDays(-7)->format('Y-m-d'),"end" => Carbon::now()->format('Y-m-d') );
                        if (!empty($filter))
                        // dd($filter);
                        $installments = $this->InstallmentBeetwen($installments, 'due_date', $filter);
                    }

                    if (isset($_GET['paid'])) {
                        $filter = $_GET['paid'];
                        if (!empty($filter))
                            $installments->where('installments.paid', '=', $filter);
                    }

                    if (isset($_GET['date_expired'])) {
                        $filter = $_GET['date_expired'];
                        if (!empty($filter))
                        $installments = $this->loanBeetwen($installments, 'date_expired', $filter);
                    }
                    if (isset($_GET['User'])) {
                        $filter = $_GET['User'];
                        if (!empty($filter))
                            if ($_GET['installmentable_type'] == "App\Loan" ) {
                                $installments->join('loans as loans2', 'loans2.id', '=', 'installments.installmentable_id')
                                    ->join('users as users2', 'loans2.user_id', '=', 'users2.id')
                                    ->where('users2.id', '=', $filter);
                            }else{
                                $installments->join('investments as invest2', 'invest2.id', '=', 'installments.installmentable_id')
                                    ->join('users as users2', 'invest2.user_id', '=', 'users2.id')
                                    ->where('users2.id', '=', $filter);
                            }
                    }

                    if (isset($_GET['Company'])) {
                        $filter = $_GET['Company'];
                        if (!empty($filter))
                            if ($_GET['installmentable_type'] == "App\Loan" ) {
                                $installments->join('loans as loanski', 'loanski.id', '=', 'installments.installmentable_id')
                                    ->join('users as userscompany', 'loanski.user_id', '=', 'userscompany.id')
                                    ->join('companies', 'userscompany.company_id', '=', 'companies.id')
                                    ->where('companies.id', '=', $filter);
                            }else{
                                $installments->join('investments as investki', 'investki.id', '=', 'installments.installmentable_id')
                                    ->join('users', 'investki.user_id', '=', 'users.id')
                                    ->join('companies', 'users.company_id', '=', 'companies.id')
                                    ->where('companies.id', '=', $filter);
                            }
                    }
                    if (isset($_GET['installmentable_type'])) {
                        if ($_GET['installmentable_type'] == "App\Loan" ) {
                            $installmentstotals = $installments->join('loans as l', 'installments.installmentable_id', '=', 'l.id')
                                    ->join('users as u', 'l.user_id', '=', 'u.id')
                                    ->join('companies as c', 'u.company_id', '=', 'c.id')
                                    ->join('loan_tenors as lt', 'lt.id', '=', 'l.loan_tenor_id')
                                    ->where('installments.installmentable_type', '=', 'App\Loan')
                                    ->select([
                                        'c.affiliate', 
                                        'c.name', 
                                        'u.name as username', 
                                        'u.home_address', 
                                        'installments.balance', 
                                        'l.amount_borrowed as plafond', 
                                        'l.interest_fee as invest_fee', 
                                        'l.amount_total', 
                                        'u.id as user_id', 
                                        'installments.due_date', 
                                        'lt.month', 
                                        'installments.tenor', 
                                        'installments.amount',
                                        'c.name as companyname',
                                        'lt.month as totaltenor',
                                        'l.amount_requested',
                                    ])
                                    ->get();
                        }else{
                            $installmentstotals = $installments->join('investments as invest', 'installments.installmentable_id', '=', 'invest.id')
                                    ->join('loans as l', 'invest.loan_id', '=', 'l.id')
                                    ->join('loan_tenors as lt', 'lt.id', '=', 'l.loan_tenor_id')
                                    ->join('users as u', 'invest.user_id', '=', 'u.id')
                                    ->join('companies as c', 'u.company_id', '=', 'c.id')
                                    ->where('installments.installmentable_type', '=', 'App\Investment')
                                    ->select('c.affiliate', 'c.name', 'u.name as username', 'u.home_address', 'installments.balance', 'invest.amount_invested as plafond', 'invest.invest_fee as invest_fee', 'invest.amount_total', 'u.id as user_id', 'installments.due_date', 'installments.tenor', 'installments.amount','c.name as companyname','lt.month as totaltenor')
                                    ->get();
                        } 
                    }

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
                                      <span class="info-box-text">Total Loan</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';        

                    $formatAmountBalance = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Balance</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmountBasicInstallment = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Basic Installment</span>
                                      <span class="info-box-number">Rp. %s</span>
                                    </div>
                                  </div>';

                    $formatAmountInterestFee = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Interest Fee</span>
                                      <span class="info-box-number">Rp. %s</span>
                                    </div>
                                  </div>';

                    $formatAmountTotalInstallment = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Installment</span>
                                      <span class="info-box-number">Rp. %s</span>
                                    </div>
                                  </div>';
                    if (isset($_GET['installmentable_type'])) {
                        foreach ($installmentstotals as $installmentstotal) {
                            $installmentssumbunga += $installmentstotal->amount - ($installmentstotal->amount_requested / $installmentstotal->month);
                        }
                        foreach ($installmentstotals as $installmentstotal) {
                            if (!empty($installmentstotal)) {
                                $plus = $installmentstotal->amount - ($installmentstotal->invest_fee / $installmentstotal->month);
                            }else{
                                $plus = 0;
                            }
                            $installmentssumpokokpinjaman = $installmentssumpokokpinjaman + $plus;
                        }
                    }
                    $contentUser   = sprintf($formatUser, count($installments->get()));

                    if (isset($_GET['installmentable_type'])) {
                        $contentAmount = sprintf($formatAmount, number_format($installmentstotals->sum('amount_total'),2,',','.'));
                    }else{
                        $contentAmount = sprintf($formatAmount, 'Rp. ' . '');
                    }
                    // $contentAmountBalance = sprintf($formatAmountBalance, 'Rp. ' . number_format($installments->sum('balance'), 0, '.', '.'));
                    $contentAmountBasicInstallment = sprintf($formatAmountBasicInstallment, number_format($installments->sum('amount') - $installmentssumbunga,2,',','.'));
                    $contentAmountInterestFee = sprintf($formatAmountInterestFee, number_format($installmentssumbunga,2,',','.'));
                    $contentTotalInstallment = sprintf($formatAmountTotalInstallment, number_format($installments->sum('amount'),2,',','.'));

                    $content->row(function($row) use ($contentUser, $contentAmountBasicInstallment, $contentAmountInterestFee, $contentTotalInstallment) {
                        $row->column(4, $contentUser);
                        // $row->column(4, $contentAmount);
                        // $row->column(4, $contentAmountBalance);
                        $row->column(4, $contentAmountBasicInstallment);
                        $row->column(4, $contentAmountInterestFee);
                        $row->column(4, $contentTotalInstallment);
                    });
                    
                }
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Installments');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Installments', 'url' => '/installments'],
             ['text' => 'Edit', 'url' => '/installments/edit']
            );
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Installments');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Installments', 'url' => '/installments'],
             ['text' => 'Create', 'url' => '/installments/create']
            );
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Installment::class, function (Grid $grid) {

            if(Admin::user()->isRole('auditor')) {
                $grid->disableCreateButton();
                // $grid->disableActions();
                // $grid->disableFilter();
                // $grid->disableExport();
            }

            if (isset($_GET['report_'])) {
                $grid->disableActions();
            } 

            if(!Admin::user()->inRoles(['administrator', 'super_administrator'])) {
                $grid->tools(function ($tools) {
                    $tools->batch(function ($batch) {
                        $batch->disableDelete();
                    });
                });
                $grid->actions(function ($actions) {
                    $actions->disableDelete();
                });
            }
            if (isset($_GET['installmentable_type'])) {
                $companysource = $_GET['installmentable_type'];
                if ($companysource == 'App\Loan') {
                    if (!isset($_GET['direct'])) {
                        $grid->model()->whereHas('loans', function ($query) {
                            $query->join('users', 'users.id', '=', 'loans.user_id')
                                    ->where('users.verified', '=', 1)
                                            ->where('users.active', '=', 1)
                                            ->where('loans.status_id', '=', 3);
                        });
                    }
                }else {
                    if (!isset($_GET['direct'])) {
                        $grid->model()->whereHas('investments', function ($query) {
                            $query->join('users', 'users.id', '=', 'investments.user_id')
                                    ->where('users.verified', '=', 1)
                                            ->where('users.active', '=', 1)
                                            ->where('investments.status_id', '=', 3);
                        });
                    }
                }
            }
            if (isset($_GET['installmentable_type'])) {
                if ((isset($_GET['_export_'])) && (isset($_GET['installmentable_type']))) {
                    if ((!isset($_GET['User'])) && (!isset($_GET['Company'])) && (!isset($_GET['paid'])) && (!isset($_GET['due_date'])) && (!isset($_GET['Tenor'])) && (!isset($_GET['installmentable_id']))) {
                        $start = Carbon::now()->addDays(-7)->format('Y-m-d');
                            $end = Carbon::now()->format('Y-m-d');
                                $grid->model()->whereBetween('due_date', [$start, $end]);
                                // dd('x');
                    }
                   }     
            }
            // if (isset($_GET['due_date'])) {
            //     $start = Carbon::now()->addDays(-7)->format('Y-m-d');
            //         $end = Carbon::now()->format('Y-m-d');
            //             $grid->model()->whereBetween('due_date', [$start, $end]);
            // }   
            if (isset($_GET['due_date'])) {
                $filter = $_GET['due_date'];
                if (!empty($filter))
                  $start = $_GET['due_date']['start'];
                    $end = $_GET['due_date']['end'];
                        $grid->model()->whereBetween('due_date', [$start, $end]);
            }
            // else{
            //      $start = Carbon::now()->addDays(-7)->format('Y-m-d');
            //         $end = Carbon::now()->format('Y-m-d');
            //             $grid->model()->whereBetween('due_date', [$start, $end]);
            // }
            $grid->exporter(new ExcelExporterInstallment());
            // $grid->tools(function ($tools) {
            //     $params = '';
            //     if (isset($_SERVER['QUERY_STRING'])) {
            //         $params = $_SERVER['QUERY_STRING'];
            //     }
            //     $tools->append('<a target="_blank" href="installments/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            // });

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->equal('installmentable_type', 'Reference')->select(['App\Loan' => 'Loan','App\Investment' => 'Investment']);
                $filter->equal('installmentable_id', 'Reference ID');
                $filter->where(function ($query) {
                    if(isset($_GET['installmentable_type'])) {
                        $url = $_GET['installmentable_type'];
                    } else {
                        $url = '';
                    }
                    if ($url == 'App\Loan') {
                        $query->whereHas('loans', function ($query) {
                            $query->where('loans.status_id', '=', "{$this->input}");
                        });
                    }else{
                        $query->whereHas('investments', function ($query) {
                            $query->where('investments.status_id', '=', "{$this->input}");
                        });
                    }
                }, 'Reference Status')->select(Status::get()->pluck('name','id'));

                $filter->where(function ($query) {
                    if(isset($_GET['installmentable_type'])) {
                        $url = $_GET['installmentable_type'];
                    } else {
                        $url = '';
                    }
                    if ($url == 'App\Loan') {
                        $query->whereHas('loans', function ($query) {
                            $query->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id')
                                    ->where('loan_tenors.id', '=', "{$this->input}");
                        });
                    }else{
                        $query->whereHas('investments', function ($query) {
                            $query->join('loans', 'investments.loan_id', '=', 'loans.id')
                                ->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id')
                                ->where('loans.loan_tenor_id', '=', "{$this->input}");
                        });
                    }
                }, 'Tenor')->select(LoanTenor::get()->pluck('month','id'));
                $filter->between('due_date', 'Due Date')->date();
                $filter->equal('paid', 'Paid')->select(['0' => 'Unpaid','1' => 'Paid']);
                $filter->where(function ($query) {
                    if(isset($_GET['installmentable_type'])) {
                        $url = $_GET['installmentable_type'];
                    } else {
                        $url = '';
                    }
                    if ($url == 'App\Loan') {
                        $query->whereHas('loans', function ($query) {
                            $query->join('users', 'loans.user_id', '=', 'users.id')
                            ->join('companies', 'users.company_id', '=', 'companies.id')
                            ->where('companies.id', '=', "{$this->input}");
                        });
                    }else{
                        $query->whereHas('investments', function ($query) {
                            $query->join('users', 'investments.user_id', '=', 'users.id')
                            ->join('companies', 'users.company_id', '=', 'companies.id')
                            ->where('companies.id', '=', "{$this->input}");
                        });
                    }
                }, 'Company')->select(Company::get()->pluck('name','id'));
               $filter->where(function ($query) {
                    if(isset($_GET['installmentable_type'])) {
                        $url = $_GET['installmentable_type'];
                    } else {
                        $url = '';
                    }
                    if ($url == 'App\Loan') {
                        $query->whereHas('loans', function ($query) {
                            $query->join('users', 'loans.user_id', '=', 'users.id')
                            ->where('users.id', '=', "{$this->input}");
                        });
                    }else{
                        $query->whereHas('investments', function ($query) {
                            $query->join('users', 'investments.user_id', '=', 'users.id')
                            ->where('users.id', '=', "{$this->input}");
                        });
                    }
                }, 'User')->select(User::get()->pluck('name','id'));
            });
            $grid->code('Installment ID')->sortable();
             if (isset($_GET['installmentable_type'])) {
                    $companysource = $_GET['installmentable_type'];
                    if ($companysource == 'App\Loan') {
                        $grid->column('Name / Address')->display(function () {
                            $installmentstable = DB::table('installments');
                            $name =   $installmentstable->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                                ->join('users', 'loans.user_id', '=', 'users.id')
                                                ->where('installments.id', '=', "{$this->id}")
                                                ->first();
                            return !empty($name) ? '<a href="'.url('admin/users/'.$name->id).'">'.$name->name.'</a> / '.$name->home_address : '-';
                        });
                    }elseif($companysource == 'App\Investment'){
                        $grid->column('Name / Address')->display(function () {
                            $installmentstable = DB::table('installments');
                            $name =   $installmentstable->join('investments', 'installments.installmentable_id', '=', 'investments.id')
                                                ->join('users', 'investments.user_id', '=', 'users.id')
                                                ->where('installments.id', '=', "{$this->id}")
                                                ->first();
                            return !empty($name) ? '<a href="'.url('admin/users/'.$name->id).'">'.$name->name.'</a> / '.$name->home_address : '-';
                        });
                    }
                }else{
                    $companysource = '';
                }
            if (isset($_GET['installmentable_type'])) {
                $companysource = $_GET['installmentable_type'];
                if ($companysource == 'App\Loan') {# code...
                    $grid->column('Company')->display(function () {
                        $installments = DB::table('installments');
                        $installmentscompany = $installments->join('loans as l', 'installments.installmentable_id', '=', 'l.id')
                                    ->join('users as u', 'l.user_id', '=', 'u.id')
                                    ->join('companies as c', 'u.company_id', '=', 'c.id')
                                    ->where('installments.id', '=', "{$this->id}")
                                    ->select('c.name as cname')
                                    ->first(); 
                        return !empty($installmentscompany) ? $installmentscompany->cname : '-';
                    });
                }elseif($companysource == 'App\Investment'){
                    $grid->column('Company')->display(function () {
                        $installments = DB::table('installments');
                        $installmentscompany =   $installments->join('investments as invest', 'installments.installmentable_id', '=', 'invest.id')
                                    ->join('users as u', 'invest.user_id', '=', 'u.id')
                                    ->join('companies as c', 'u.company_id', '=', 'c.id')
                                    ->where('installments.id', '=', "{$this->id}")
                                    ->select('c.name as cname')
                                    ->first(); 
                        return !empty($installmentscompany) ? $installmentscompany->cname : '-';
                    });
                }
            }else{
                $companysource = '';
            } 
            $grid->id('Reference')->sortable()->display(function ($id) {
                $installment = Installment::find($id);
                if($installment->installmentable_type == 'App\Loan') {
                    return '<a href="'.url('admin/loans/'.$installment->installmentable_id).'">Loan #'.$installment->installmentable_id.'</a>';
                } else {
                    return '<a href="'.url('admin/investments/'.$installment->installmentable_id).'">Investment #'.$installment->installmentable_id.'</a>';
                }
            });
            if (isset($_GET['installmentable_type'])) {
                $companysource = $_GET['installmentable_type'];
                if ($companysource == 'App\Loan') {# code...
                    $grid->column('Tenor')->display(function () {
                        $installments = DB::table('installments');
                        $installmentstenor = $installments->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                            ->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();
                        return !empty($installmentstenor) ? $installmentstenor->tenor . '/' . $installmentstenor->month : '-';
                    });
                }elseif($companysource == 'App\Investment'){
                    $grid->column('Tenor')->display(function () {
                        $installmentstable = DB::table('installments');
                        $installmentstenor =   $installmentstable->join('investments', 'installments.installmentable_id', '=', 'investments.id')
                                            ->join('loans', 'loans.id', '=', 'investments.loan_id')
                                            ->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();
                        return !empty($installmentstenor) ? $installmentstenor->tenor . '/' . $installmentstenor->month : '-';
                    });
                }
            }else{
                $companysource = '';
            }
            $grid->column('Due Date')->display(function () {
                        $installmentstable = DB::table('installments')->where('id','=',"{$this->id}")->first();
                        return $installmentstable->due_date;
                    });
            if (isset($_GET['installmentable_type'])) {
                $companysource = $_GET['installmentable_type'];
                if ($companysource == 'App\Loan') {# code...
                    $grid->column('Total Loan')->display(function () {
                        $installmentstable = DB::table('installments');
                        $amounttotal =   $installmentstable->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();
                        return CurrencyFormat::rupiah($amounttotal->amount_total);
                    });
                }elseif($companysource == 'App\Investment'){
                    $grid->column('Total Loan')->display(function () {
                        $installmentstable = DB::table('installments');
                        $amounttotal =   $installmentstable->join(
                                            'investments', 
                                            'installments.installmentable_id', 
                                            '=', 
                                            'investments.id')
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();
                        return !empty($amounttotal) ? CurrencyFormat::rupiah($amounttotal->amount_total) : '-';
                    });
                }
            }else{
                $companysource = '';
            }
            $grid->balance()->sortable()->setAttributes(['align' => 'right'])->display(function ($amount) {
                return CurrencyFormat::rupiah($amount);
            });
            if (isset($_GET['installmentable_type'])) {
                $companysource = $_GET['installmentable_type'];
                if ($companysource == 'App\Loan') {# code...
                    $grid->column('Basic Installment')->display(function () {
                        $installmentstable = DB::table('installments');
                        $interest_fee =   $installmentstable->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                            ->join('loan_tenors as lt', 'lt.id', '=', 'loans.loan_tenor_id')
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();
                        return CurrencyFormat::rupiah( ($interest_fee->amount_requested / $interest_fee->month));
                    });
                }elseif($companysource == 'App\Investment'){
                    $grid->column('Basic Installment')->display(function () {
                        $installmentstable = DB::table('installments');
                        $interest_fee =   $installmentstable->join(
                                            'investments', 
                                            'installments.installmentable_id', 
                                            '=', 
                                            'investments.id')
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();
                        $basicInstallment = 0;
                        if (!empty($interest_fee)) {
                            $investmentTotal = Installment::where('installmentable_id', $interest_fee->installmentable_id)->count();
                            $basicInstallment = $interest_fee->amount_invested / $investmentTotal;
                        }
                        return CurrencyFormat::rupiah($basicInstallment);
                    });
                }
            }else{
                $companysource = '';
            }
            if (isset($_GET['installmentable_type'])) {
                $companysource = $_GET['installmentable_type'];
                if ($companysource == 'App\Loan') {# code...
                    $grid->column('Interest Fee')->display(function () {
                        $installmentstable = DB::table('installments');
                        $interest_fee =   $installmentstable->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                            ->join('loan_tenors as lt', 'lt.id', '=', 'loans.loan_tenor_id')
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();
                        $basicInstallment = $interest_fee->amount_requested / $interest_fee->month;
                        return CurrencyFormat::rupiah($interest_fee->amount - $basicInstallment);
                    });
                }elseif($companysource == 'App\Investment'){
                    $grid->column('Invest Fee')->display(function () {
                        $interest_fee = Installment::join(
                                                'investments', 
                                                'installments.installmentable_id', 
                                                '=', 
                                                'investments.id'
                                            )
                                            ->where('installments.id', '=', "{$this->id}")
                                            ->first();

                        $basicInstallment = 0;
                        if (!empty($interest_fee)) {
                            $investmentTotal = Installment::where('installmentable_id', $interest_fee->installmentable_id)->count();
                            $basicInstallment = $interest_fee->amount_invested / $investmentTotal;
                        }
                        return !empty($interest_fee) ? CurrencyFormat::rupiah($interest_fee->amount - $basicInstallment) : '-';
                    });
                }
            }else{
                $companysource = '';
            }
            $grid->amount('Total Installment')->sortable()->setAttributes(['align' => 'right'])->display(function ($amount) {
                return CurrencyFormat::rupiah($amount);
            });
            $grid->paid('Status')->sortable()->display(function ($paid) {
                return $paid ? 'Paid' : 'Unpaid';
            });

            $grid->created_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Installment::class, function (Form $form) {

            $installment = Installment::find(request()->route('installment'));

            $installmentstable = DB::table('installments');
            $interest_fee =   $installmentstable->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                ->join('loan_tenors as lt', 'lt.id', '=', 'loans.loan_tenor_id')
                                ->where('installments.id', request()->route('installment'))
                                ->first();

            $basicInstallmentValue = $interest_fee;//12;//CurrencyFormat::rupiah( ($interest_fee->amount_requested / $interest_fee->month));

            $form->display('id', 'ID');
            $form->select('tenor', 'Tenor')->options(LoanTenor::get()->pluck('month','id'));
            $form->text('amount')->attribute(['value' => CurrencyFormat::rupiah($installment->amount), 'class' => 'form-control mask_money']);
            /*$form->text('basic installment')->attribute(['value' => $basicInstallmentValue, 'class' => 'form-control mask_money']);*/
            $form->date('due_date');
            if ($installment->installmentable_type == 'App\Loan') {
                $form->image('payment_image', 'Payment Image')->attribute(['readonly' => 'true']);
            }
            $form->date('payment_date', 'Payment Date')->attribute(['readonly' => 'true']);
            $states = [
                'on'  => ['value' => 1, 'text' => 'Paid', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'Unpaid', 'color' => 'danger'],
            ];


            $form->switch('paid', 'Status')->states($states);
            $form->display('created_at', 'Created At');

            $form->disableReset();

            $form->saving(function (Form $form) {
                $form->amount = str_replace(',','.',str_replace('.','',$form->amount));

            });

            $form->saved(function (Form $form) {
                $installment = $form->model();
                if ($form->model()->paid == 1) {

                    DB::beginTransaction();

                    if ($installment->installmentable_type == 'App\Loan') {
                        $loan = Loan::find($installment->installmentable_id);
                        $investments = Investment::where([
                            'loan_id' => $installment->installmentable_id,
                            'paid' => 1,
                            'status_id' => 3,
                        ])
                        ->select('id', 'user_id')
                        ->get();

                        $lastTenor = $loan->tenor->month;

                        try {
                            $hasTransaction = Transaction::where([
                                'user_id' => $loan->user_id, 
                                'transactionable_id' => $installment->id,
                                'transactionable_type' => 'App\Installment',
                            ])->first();

                            if (empty($hasTransaction)) {
                                $transactionid = Transaction::insertGetId([
                                    'amount' => $installment->amount,
                                    'user_id' => $loan->user_id,
                                    'transactionable_id' => $installment->id,
                                    'transactionable_type' => 'App\Installment',
                                    'type' => 'Cash In',
                                    'status_id' => 7,
                                    'created_at' => Carbon::now()
                                ]);

                                $transaction1 = Transaction::find($transactionid);
                                event(new CashInInstallment($transaction1));
                            }

                            foreach ($investments as $investment) {
                                $investmentInstallment = Installment::where([
                                    'installmentable_type' => 'App\Investment',
                                    'installmentable_id' => $investment->id,
                                    'paid' => 0,
                                    'tenor' => $installment->tenor,
                                ])
                                ->first();

                                if (!empty($investmentInstallment)) {
                                    $investmentInstallment->paid = 1;
                                    $investmentInstallment->save();

                                    $transactionid = Transaction::insertGetId([
                                        'amount' => $investmentInstallment->amount,
                                        'user_id' => $investment->user_id,
                                        'transactionable_id' => $investmentInstallment->id,
                                        'transactionable_type' => 'App\Installment',
                                        'type' => 'Cash Out',
                                        'status_id' => 1,
                                        'created_at' => Carbon::now()
                                    ]);


                                    $transaction2 = Transaction::find($transactionid);
                                    event(new CashOutInstallment($transaction2));
                                }
                            }

                            //amount total diambil dari jumlah installment yang paid

                            $paidInstallmentCount= Installment::where([
                                'installmentable_id' => $loan->id,
                                'installmentable_type' => 'App\Loan',
                                'paid' => 1
                            ])->count();

                            $paidInstallmentPaidValue = Installment::where([
                                'installmentable_id' => $loan->id,
                                'installmentable_type' => 'App\Loan',
                                'paid' => 1
                            ])->sum('amount');

                            $loan->amount_paid = $paidInstallmentPaidValue;

                            if ($paidInstallmentCount == $lastTenor) {
                                $loan->status_id = 7;
                            }
                            $loan->save();


                        } catch (Exception $e) {
                            DB::rollback();
                            admin_toastr('Something went wrong please try again ' . (!config('app.debug') ?: $e->getMessage()) );
                            return redirect('/admin/installments/'.request()->route('installment').'/edit');
                        }
                    }else{
                        try {
                            $investment = Investment::join('loans as l', 'l.id', '=', 'investments.loan_id')
                                        ->join('loan_tenors as lt', 'lt.id', '=', 'l.loan_tenor_id')
                                        ->select('investments.user_id', 'lt.month', 'investments.amount_invested')
                                        ->find($installment->installmentable_id);
                            $hasTransaction = Transaction::where([
                                'user_id' => $investment->user_id, 
                                'transactionable_id' => $installment->id,
                                'transactionable_type' => 'App\Installment',
                            ])->first();

                            $installmentAmount = $investment->amount_invested / $investment->month;

                            //$investmentLastTenor = $investment->installments->count();
                            $investmentLastTenor = Installment::where([
                                'installmentable_id' => $installment->installmentable_id,
                                'installmentable_type' => 'App\Investment',
                            ])->count();

                            if (empty($hasTransaction)) {
                                $transactionid = Transaction::insertGetId([
                                    'amount' => $installmentAmount,
                                    'user_id' => $investment->user_id,
                                    'transactionable_id' => $installment->id,
                                    'transactionable_type' => 'App\Installment',
                                    'type' => 'Cash Out',
                                    'status_id' => 7,
                                    'created_at' => Carbon::now()
                                ]);
                                $transaction3 = Transaction::find($transactionid);
                                event(new CashOutInstallment($transaction3));
                            }

                            $paidInstallmentCount2= Installment::where([
                                'installmentable_id' => $installment->installmentable_id,
                                'installmentable_type' => 'App\Investment',
                                'paid' => 1
                            ])->count();

                            if ($paidInstallmentCount2 == $investmentLastTenor) {
                                Investment::where('id', $installment->installmentable_id)->update(['status_id' => 7]);
                                $investment->save();
                            }
                        } catch (Exception $e) {
                            DB::rollback();
                            admin_toastr('Something went wrong please try again ' .  $e->getMessage() );
                            return redirect('/admin/installments/'.request()->route('installment').'/edit');
                        }
                    }
                }
                
                DB::commit();
                activity()
                    ->performedOn($installment)
                    ->withProperties(['amount_borrowed' => $installment->amount])
                    ->causedBy(Admin::user())
                    ->log('Installment Updated');
                admin_toastr('Update succeeded!');

                return redirect('/admin/installments/'.request()->route('installment').'/edit');
            });
        });
    }
    
    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        // dd($urlparams);
        $installments = DB::table('installments');
        if ($request->input('installmentable_type') != null) {
                        $companysource = $_GET['installmentable_type'];
                        if ($companysource == 'App\Loan') {
                            if ($request->input('direct') == null) {
                                $installments->join('loans as loanspdf', 'loanspdf.id', '=', 'installments.installmentable_id')
                                            ->join('users as userspdf', 'userspdf.id', '=', 'loanspdf.user_id')
                                            ->where('loanspdf.status_id', '=', '3');
                            }
                        }else {
                            if ($request->input('direct') == null) {
                                $installments->join('investments as investmentpdf', 'investmentpdf.id', '=', 'installments.installmentable_id')
                                        ->where('investmentpdf.paid', '=', '1');
                            }
                        }
        }
        if ($request->input('installmentable_type') != null) {
            $installments = $installments->where('installmentable_type', $request->input('installmentable_type'));
        }
        if ($request->input('installmentable_id') != null) {
            $installments = $installments->where('installmentable_id', $request->input('installmentable_id'));
        }
        if ($request->input('User') != null) {
            if ($request->input('installmentable_type') == "App\Loan" ) {
                $installments = $installments->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                ->join('users', 'loans.user_id', '=', 'users.id')
                                ->where('users.id', '=', $request->input('User'));
            }elseif($request->input('installmentable_type') == "App\Investment"){
                $installments = $installments->join('investments', 'installments.installmentable_id', '=', 'investments.id')
                                ->join('users', 'investments.user_id', '=', 'users.id')
                                ->where('users.id', '=', $request->input('User'));
            }else{

            }
        }
        if (isset($_GET['due_date'])) {
                        $filter = $_GET['due_date'];
                        if (!empty($filter))
                        $installments = $this->InstallmentBeetwen($installments, 'due_date', $filter);
                    }else{
                        $filter = array("start" => Carbon::now()->addDays(-7)->format('Y-m-d'),"end" => Carbon::now()->format('Y-m-d') );
                        if (!empty($filter))
                        // dd($filter);
                        $installments = $this->InstallmentBeetwen($installments, 'due_date', $filter);
                    }
        if ($request->input('paid') != null) {
            $installments = $installments->where('paid', $request->input('paid'));
        }
        if ($request->input('Company') != null) {
            if ($request->input('installmentable_type') == "App\Loan" ) {
                $installments = $installments->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                ->join('users', 'loans.user_id', '=', 'users.id')
                                ->join('companies', 'users.company_id', '=', 'companies.id')
                                ->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id')
                                ->where('companies.id', '=', $request->input('Company'));
            }elseif($request->input('installmentable_type') == "App\Investment"){
                $installments = $installments->join('investments', 'installments.installmentable_id', '=', 'investments.id')
                                ->join('users', 'investments.user_id', '=', 'users.id')
                                ->join('companies', 'users.company_id', '=', 'companies.id')
                                ->where('companies.id', '=', $request->input('Company'));
            }else{

            }
        }
        if ($request->input('installmentable_type') == "App\Loan" ) {
            $installments = $installments->join('loans as l', 'installments.installmentable_id', '=', 'l.id')
                            ->join('users as u', 'l.user_id', '=', 'u.id')
                            ->join('companies as c', 'u.company_id', '=', 'c.id')
                            ->join('loan_tenors as lt', 'lt.id', '=', 'l.loan_tenor_id')
                            ->whereNull('installments.deleted_at')
                            ->select([
                                'installments.code',
                                DB::raw('IF( (select count(id) from bank_accounts where user_id = u.id) > 0, (select account_number from bank_accounts where user_id = u.id), "" ) as bank_account'),
                                DB::raw('installments.amount - (l.amount_requested / lt.month)as installment_rate'),
                                DB::raw('(installments.amount - (l.interest_fee / lt.month)) as installment_basic'),
                                'c.affiliate', 
                                'c.name', 
                                'u.name as username', 
                                'u.home_address', 
                                'installments.balance', 
                                'l.amount_borrowed as plafond', 
                                'l.interest_fee as invest_fee2', 
                                'l.amount_total', 
                                'u.id as user_id', 
                                'installments.due_date', 
                                'l.amount_requested', 
                                'lt.month', 
                                'installments.tenor', 
                                'installments.amount',
                                'c.name as companyname',
                                'lt.month as totaltenor', 
                                'installments.id', 
                                'installments.paid'
                            ]);

                            $installmentssumbalance = $installments->sum('installments.balance'); 
                            $installmentssumplafond = $installments->sum('l.amount_borrowed');
                            $installmentssuminvest_fee = $installments->sum('l.interest_fee');
                            $installmentssumamount_total = $installments->sum('l.amount_total');
                            $installmentssumamount = $installments->sum('installments.amount');

        }elseif($request->input('installmentable_type') == "App\Investment"){
            $installments = $installments->join('investments as invest', 'installments.installmentable_id', '=', 'invest.id')
                            ->join('users as u', 'invest.user_id', '=', 'u.id')
                            ->join('loans as l', 'invest.loan_id', '=', 'l.id')
                            ->join('loan_tenors as lt', 'lt.id', '=', 'l.loan_tenor_id')
                            ->join('companies as c', 'u.company_id', '=', 'c.id')
                            ->select([
                                'installments.code',
                                DB::raw('IF( (select count(id) from bank_accounts where user_id = u.id) > 0, (select account_number from bank_accounts where user_id = u.id), "" ) as bank_account'),
                                DB::raw('(invest.invest_fee / lt.month)as installment_rate'),
                                DB::raw('(installments.amount - (invest.invest_fee / lt.month)) as installment_basic'),
                                'c.affiliate', 
                                'c.name', 
                                'u.name as username', 
                                'u.home_address', 
                                'l.amount_requested', 
                                'installments.balance', 
                                'invest.amount_invested as plafond', 
                                'invest.invest_fee as invest_fee2',
                                'invest.amount_total', 
                                'u.id as user_id', 
                                'installments.due_date', 
                                'installments.tenor', 
                                'installments.amount',
                                'c.name as companyname',
                                'lt.month as totaltenor', 
                                'installments.id', 
                                'installments.paid'
                            ]);
                           
                            $installmentssumbalance = $installments->sum('installments.balance'); 
                            $installmentssumplafond = $installments->sum('invest.amount_invested');
                            $installmentssuminvest_fee = $installments->sum('invest.invest_fee');
                            $installmentssumamount_total = $installments->sum('invest.amount_total');
                            $installmentssumamount = $installments->sum('installments.amount');
        }else{
            
        }

        if ($request->input('Tenor') != null) {
            $installments = $installments->where('lt.id', $request->input('Tenor'));
        }

        $installments = $installments->get();
        $installmentssumbunga = 0;
        $installmentssumpokokpinjaman = 0;

        //return response()->json($installments);

        // $installmentssum = $installmentssum->get();
        /*foreach ($installments as $installment) {
            $bank_accounts = BankAccount::where('user_id', 
                $installment->user_id)->first();
            $installment->bank_account = !empty($bank_accounts) ? $bank_accounts->account_number : '';
            $installmentssumbunga = $installment->invest_fee2 / $installment->month;
            $installmentssumpokokpinjaman = $installment->amount - ($installment->invest_fee2 / $installment->month);
        }

        foreach ($installments as $installment) {
            if (!empty($installment)) {
                $plus = $installment->invest_fee2 / $installment->month;
            }else{
                $plus = 0;
            }
            $installmentssumbunga = $installmentssumbunga + $plus;
        }
        foreach ($installments as $installment) {
            if (!empty($installment)) {
                $plus = $installment->amount - ($installment->invest_fee2 / $installment->month);
            }else{
                $plus = 0;
            }
            $installmentssumpokokpinjaman = $installmentssumpokokpinjaman + $plus;
        }*/



        // return response()->json($installments);
        view()->share('installments',$installments);
        $pdf = PDF::loadView(
            'admin.installments.export.pdf',
            compact(
                'installmentssumbalance',
                'installmentssumplafond',
                'installmentssuminvest_fee',
                'installmentssumamount_total',
                'installmentssumamount'
                )
            );
        return $pdf->stream('installments.pdf');
        // return $users;
        // return view('admin.users.export.pdf', compact('users'));

    }    
    private function InstallmentBeetwen($installments, $column, $filter)
    {
        if (!empty($filter)) {
            $start = $filter['start']; 
            $end   = $filter['end']; 
            if ($start != null) {
                $installments->where($column, '>=', $start);
            }

            if ($end != null) {
                $installments->where($column, '<=', $end);
            }

            return $installments;
        }

        return $installments;
    }
}

<?php

namespace App\Admin\Controllers;

use App\User;
use App\Loan;
use App\LoanTenor;
use App\Company;
use App\Installment;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use PDF;
use App\Admin\Extensions\ExcelExpoterLoanBalances;
use App\Libraries\CurrencyFormat;

class BalanceController extends Controller
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

            $content->header('Loans Balances');
            $content->breadcrumb(
             ['text' => 'Balances', 'url' => '/balances']
            );
            if (isset($_GET['report_'])) {

                    $loanpaid = 0;
                    $totalpaid = 0;
                    $loantenor = 0;
                    $users = User::where('verified', '=', 1)->where('users.active', '=', 1);
			        $users = $users->join('loans', 'loans.user_id', '=', 'users.id')->where('loans.status_id', '=', 3)->whereNull('loans.deleted_at')->whereNotNull('loans.loan_grade_id');
			        $users = $users->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id');
			        $users = $users->select('users.name as name', 'loan_tenors.month', 'loans.id as loansid', 'users.id as userid');
                    $loans = Loan::where('status_id', '=', 3)->select('loans.id', 'loans.amount_requested', 'loans.loan_tenor_id');
                    if (isset($_GET['company_id'])) {
                        $companyId = $_GET['company_id'];
                        if (!empty($companyId)) {
                            $users = $users->where('users.company_id', '=', $companyId);
                            $loans = $loans->join('users', 'users.id', '=', 'user_id')
                                    ->where('users.company_id', '=', $companyId);
                        }
                    }
                    $users = $users->groupBy('users.id')->get();
                    // dd($users);
                    foreach ($users as $user) {
                        $user->amount_requested = Loan::where('user_id', '=', $user->userid)->where('loans.status_id', '=', 3)->select('amount_requested as amount_pinjaman')
                                                ->sum('amount_requested');
                        $user->installmentcount = Installment::where('installmentable_id', '=', $user->loansid)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                    }
                    $loans = $loans->get();
                    $formatUser = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total User</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmount = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Borrowed</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';    

                    $formatAmountPaid = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Paid</span>
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

                    foreach ($loans as $loan) {
                        $loantenor = LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                        $installments = Installment::where('installmentable_id', '=', $loan->id)
                                    ->where('installmentable_type', '=', 'App\\Loan')
                                    ->where('paid', '=', 1)
                                    ->count();
                        $loanpaid = ($loan->amount_requested / 
                            $loantenor->month) * 
                        $installments;
                        $totalpaid += $loanpaid;
                    }

                    $contentUser   = sprintf($formatUser, count($users));
                    //$contentAmount = sprintf($formatAmount, 'Rp. ' . CurrencyFormat::rupiah($users->sum('amount_requested')));
                    $contentAmount = sprintf($formatAmount, 'Rp. ' . CurrencyFormat::rupiah($users->sum('amount_requested')));
                    $contentAmountPaid = sprintf($formatAmountPaid, 'Rp. ' . CurrencyFormat::rupiah($totalpaid));
                    $contentAmountBalance = sprintf($formatAmountBalance, 'Rp. ' . CurrencyFormat::rupiah($users->sum('amount_requested') - $totalpaid));
                    $content->row(function($row) use ($contentUser, $contentAmount, $contentAmountPaid, $contentAmountBalance) {
                        $row->column(4, $contentUser);
                        $row->column(4, $contentAmount);
                        $row->column(4, $contentAmountPaid);
                        $row->column(4, $contentAmountBalance);
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

            $content->header('Balances');
            $content->breadcrumb(
             ['text' => 'Balances', 'url' => '/balances'],
             ['text' => 'Edit', 'url' => '/balances/edit']
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

            $content->header('Balances');
            $content->breadcrumb(
             ['text' => 'Balances', 'url' => '/balances'],
             ['text' => 'Create', 'url' => '/balances/create']
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
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->actions(function ($actions) {
                $actions->disableEdit();
            });
            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="loansbalance/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });

            if (isset($_GET['report_'])) {
                $grid->disableActions();
            } 

            $grid->model()->whereHas('loans', function ($query) {
                $query->where('status_id', '=', 3);
            });
            $grid->id('User ID')->sortable();
            $grid->name();
            /*$grid->column('Total Borrowed')->display(function () {
                        $loans = \App\Loan::where('user_id', '=', "{$this->id}")
                                    ->where('loans.status_id', '=', 3)
                                    ->get();
                        $totalborrowed = 0;
                        foreach ($loans as $loan) {
                            $totalborrowed += $loan->amount_requested;
                        }
                        return CurrencyFormat::rupiah($totalborrowed, true);
            });*/
            $grid->column('Total Borrowed')->display(function () {
                        $loans = \App\Loan::where('user_id', '=', "{$this->id}")
                                    ->where('loans.status_id', '=', 3)
                                    ->get();
                        $totalborrowed = 0;
                        foreach ($loans as $loan) {
                            $totalborrowed += $loan->amount_requested;
                        }
                        return CurrencyFormat::rupiah($totalborrowed, true);
            });
            $grid->column('Total Paid')->display(function () {
                        $loans = \App\Loan::where('user_id', '=', "{$this->id}")
                                    ->where('loans.status_id', '=', 3)
                                    ->get();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                        foreach ($loans as $loan) {
                            $loantenor = \App\LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                            $installments = \App\Installment::where('installmentable_id', '=', $loan->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                            $loanpaid = $loan->amount_requested / $loantenor->month * $installments;
                            $totalpaid = $totalpaid + $loanpaid;
                        }
                        return CurrencyFormat::rupiah($totalpaid, true);
            });
            $grid->column('Balance')->display(function () {
                        $loans = \App\Loan::where('user_id', '=', "{$this->id}")
                                    ->where('loans.status_id', '=', 3)
                                    ->get();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                        $totalborrowed = 0;
                        foreach ($loans as $loan) {
                            $totalborrowed = $totalborrowed + $loan->amount_requested;
                            $loantenor = \App\LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                            $installments = \App\Installment::where('installmentable_id', '=', $loan->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                            $loanpaid = $loan->amount_requested / $loantenor->month * $installments;
                            $totalpaid = $totalpaid + $loanpaid;
                        }
                        return CurrencyFormat::rupiah($totalborrowed - $totalpaid, true);
            });
            $grid->column('Details')->display(function () {
                        return '<a target="_blank" href="/admin/detailsbalances?report_=1&user_id='.$this->id.'"><span class="label label-success">Details</span></a>';
            });
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->equal('company_id', 'Company')->select(Company::get()->pluck('name','id'));
            });
            $grid->exporter(new ExcelExpoterLoanBalances());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Loan::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }    
    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        $users = User::whereHas('loans', function ($query) {
                $query->where('status_id', '=', '3');
            });
        if ($request->input('company_id') != null) {
            $filter = $request->input('company_id');
            $users = $users->where('company_id', '=', $filter);
        }
        $users = $users->join('loans', 'loans.user_id', '=', 'users.id')->where('loans.status_id', '=', 3);
        $users = $users->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id');
        $users = $users->select('users.id', 'users.name as name', 'loan_tenors.month', 'loans.id as loansid', 'users.id as userid')->groupBy('users.id')->get();
        foreach ($users as $user) {
            $user->amount_requested = Loan::where('user_id', '=', $user->userid)->where('loans.status_id', '=', 3)
                ->select('amount_requested as amount_pinjaman')
                ->sum('amount_requested');
            $user->installmentcount = Installment::where('installmentable_id', '=', $user->loansid)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
        }
        $loanpaid = 0;
        $totalpaid = 0;
        $loantenor = 0;
        $loans = Loan::where('status_id', '=', '3');
        if (isset($_GET['company_id'])) {
            $loans = $loans->join('users', 'users.id', '=', 'user_id')
                    ->where('users.company_id', '=', $_GET['company_id'])
                    ->select('loans.id', 'loans.amount_requested', 'loans.loan_tenor_id');
        }
        $loans = $loans->get();
        foreach ($loans as $loan) {
                        $loantenor = LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                        $installments = Installment::where('installmentable_id', '=', $loan->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                        $loanpaid = $loan->amount_requested / $loantenor->month * $installments;
                        $totalpaid = $totalpaid + $loanpaid;
        }
        view()->share('users',$users);
        $pdf = PDF::loadView('admin.loansbalance.export.pdf', compact('totalpaid'));
        return $pdf->stream('loansbalance.pdf');

    } 
}

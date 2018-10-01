<?php

namespace App\Admin\Controllers;

use App\User;
use App\Investment;
use App\LoanTenor;
use App\Company;
use App\Admin\Extensions\ExcelExporterInvesmentBalances;
use App\Installment;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request; 
use PDF;
use App\Libraries\CurrencyFormat;

class InvestmentBalanceController extends Controller
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

            $content->header('Investments Balance');
                if (isset($_GET['report_'])) {

                    $loanpaid = 0;
                    $totalpaid = 0;
                    $loantenor = 0;
                    $users = User::whereHas('investments', function ($query) {
                                $query->where('status_id', '=', '3');
                            });
                    $investments = Investment::where('investments.status_id', '=', '3');
                    if (isset($_GET['company_id'])) {
                        $users = $users->where('users.company_id', '=', $_GET['company_id']);
                        $investments = $investments->join('loans', 'loans.id', '=', 'loan_id')->join('users', 'users.id', '=', 'loans.user_id')->where('users.company_id', '=', $_GET['company_id']);
                    }
                    $investments = $investments->get();
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
                                      <span class="info-box-text">Total Investment</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';    

                    $formatAmountPaid = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Loan Paid</span>
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
                    foreach ($investments as $investment) {
                            $loans = \App\Loan::where('id', '=', $investment->loan_id)
                                    ->get();
                        foreach ($loans as $loan) {
                            $loantenor = LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                            $installments = \App\Installment::where('installmentable_id', '=', $investment->id)->where('installmentable_type', '=', 'App\Investment')->where('paid', '=', 1)->count();
                            $loanpaid = $investment->amount_invested / $loantenor->month * $installments;
                            $totalpaid = $totalpaid + $loanpaid;
                        }
                    }
                    $contentUser   = sprintf($formatUser, count($users->get()));
                    $contentAmount = sprintf($formatAmount, CurrencyFormat::rupiah($investments->sum('amount_invested'), true));
                    $contentAmountPaid = sprintf($formatAmountPaid, CurrencyFormat::rupiah($totalpaid, true));
                    $contentAmountBalance = sprintf($formatAmountBalance, CurrencyFormat::rupiah($investments->sum('amount_invested') - $totalpaid, true));
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

            $content->header('Investments Balance');

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

            $content->header('Investments Balance');

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
            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="investmentsbalances/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });

            if (isset($_GET['report_'])) {
                $grid->disableActions();
            } 

            $grid->id('User ID')->sortable();
            $grid->name();
            $grid->model()->whereHas('investments', function ($query) {
                $query->where('status_id', '=', '3');
            });
            $grid->column('Total Investment')->display(function () {
                        $investments = \App\Investment::where('user_id', '=', "{$this->id}")
                                    ->get();
                        $totalinvested = 0;
                        foreach ($investments as $investment) {
                            $totalinvested = $totalinvested + $investment->amount_invested;
                        }
                        return CurrencyFormat::rupiah($totalinvested, true);
            });
            $grid->column('Total Loan Paid')->display(function () {
                        $investments = \App\Investment::where('user_id', '=', "{$this->id}")
                                    ->get();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                        foreach ($investments as $investment) {
                            $loans = \App\Loan::where('id', '=', $investment->loan_id)
                                    ->get();
                                foreach ($loans as $loan) {
                                    $loantenor = \App\LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                                    $installments = \App\Installment::where('installmentable_id', '=', $investment->id)->where('installmentable_type', '=', 'App\Investment')->where('paid', '=', 1)->count();
                                    $loanpaid = $investment->amount_invested / $loantenor->month * $installments;
                                    $totalpaid = $totalpaid + $loanpaid;
                                }
                        }
                        return CurrencyFormat::rupiah($totalpaid, true);
            });
            $grid->column('Balance')->display(function () {
                        $investments = \App\Investment::where('user_id', '=', "{$this->id}")
                                    ->get();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                        $totalborrowed = 0;
                        foreach ($investments as $investment) {
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
                        return CurrencyFormat::rupiah($totalborrowed - $totalpaid, true);
            });
            $grid->filter(function($filter){
                $filter->equal('company_id', 'Company')->select(Company::get()->pluck('name','id'));
            });
            $grid->exporter(new ExcelExporterInvesmentBalances());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }    
    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        $users = User::whereHas('investments', function ($query) {
                $query->where('status_id', '=', '3');
            });
        if ($request->input('company_id') != null) {
            $filter = $request->input('company_id');
            $users = $users->where('users.company_id', '=', $filter);
        }
        $users = $users->join('investments', 'investments.user_id', '=', 'users.id')->where('investments.status_id', '=', 3);
        $users = $users->join('loans', 'loans.id', '=', 'investments.loan_id');
        $users = $users->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id');
        $users = $users->select('loans.amount_borrowed','users.name as name', 'loan_tenors.month', 'investments.id as loansid', 'users.id as userid','investments.amount_invested');
        $users = $users->get();
        // dd($loans);
        foreach ($users as $user) {
            $user->installmentcount = Installment::where('installmentable_id', '=', $user->loansid)
                            ->where('installmentable_type', '=', 'App\Investment')
                            ->where('paid', '=', 1)
                            ->count();
            $installments = Installment::where('installmentable_id', '=', $user->loansid)
                            ->where('installmentable_type', '=', 'App\Investment')
                            ->where('paid', '=', 1)
                            ->count();
            $user->balance = $user->amount_invested - $user->amount_invested / $user->month * $user->installmentcount;
            $user->totalpaid = $user->amount_invested / $user->month * $user->installmentcount;
        }
        view()->share('users',$users);
        $pdf = PDF::loadView('admin.investmentsbalance.export.pdf');
        return $pdf->stream('investmentsbalance.pdf');

    } 
}

<?php

namespace App\Admin\Controllers;

use App\Loan;
use App\LoanTenor;
use App\Installment;
use App\Admin\Extensions\ExcelExporterDetailsBalances;

use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request; 
use PDF;

class DetailsBalanceController extends Controller
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

            $content->header('Loan Details');
            $content->breadcrumb(
             ['text' => 'Balances', 'url' => '/balances'],
             ['text' => 'Details Balance', 'url' => '/balances/detailsbalances']
            );
            if (isset($_GET['report_'])) {

                    $loanpaid = 0;
                    $totalpaid = 0;
                    $loantenor = 0;
                    $loans = Loan::where('status_id', '=', '3');
                    if (isset($_GET['user_id'])) {
                        $loans = $loans->where('user_id', '=', $_GET['user_id']);
                        $users = User::find($_GET['user_id'])->name;
                        $formatAmountUser = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">User</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';
                        $contentUsers   = sprintf($formatAmountUser, $users);
                    }else{
                        $contentUsers   = '';
                    }
                    $formatLoans = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Loan</span>
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
                    $loans = $loans->get();
                    foreach ($loans as $loan) {
                        $loantenor = LoanTenor::where('id', '=', $loan->loan_tenor_id)->first();
                        $installments = Installment::where('installmentable_id', '=', $loan->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                        $loanpaid = $loan->amount_requested / $loantenor->month * $installments;
                        $totalpaid = $totalpaid + $loanpaid;
                    }
                    $contentLoans   = sprintf($formatLoans, count($loans));
                    $contentAmount = sprintf($formatAmount, 'Rp. ' . number_format($loans->sum('amount_requested'), 0, '.', '.'));
                    $contentAmountPaid = sprintf($formatAmountPaid, 'Rp. ' . number_format($totalpaid, 0, '.', '.'));
                    $contentAmountBalance = sprintf($formatAmountBalance, 'Rp. ' . number_format($loans->sum('amount_requested') - $totalpaid, 0, '.', '.'));
                    $content->row(function($row) use ($contentLoans, $contentAmount, $contentAmountPaid, $contentAmountBalance,$contentUsers) {
                        $row->column(4, $contentLoans);
                        $row->column(4, $contentAmount);
                        $row->column(4, $contentAmountPaid);
                        $row->column(4, $contentAmountBalance);
                        $row->column(4, $contentUsers);
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

            $content->header('Loan Details');

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

            $content->header('Loan Details');

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
        return Admin::grid(Loan::class, function (Grid $grid) {
            $isVerified = 1;
            if (isset($_GET['is_verified'])) {
                $isVerified = $_GET['is_verified'];
            }
            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="detailsbalances/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });

            $grid->model()->where('status_id', 3);

            // $grid->id('ID')->sortable();
            $grid->id('Loan ID')->display(function($id){
                return !empty($id) ? 'Loan '.$id : '' ;
            });
            $grid->amount_requested()->display(function($amount_requested){
                return !empty($amount_requested) ?  number_format($amount_requested,2) : '' ;
            });
            $grid->column('Total Paid')->display(function () {
                        $loans = \App\Loan::where('id', '=', "{$this->id}")
                                    ->first();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                            $loantenor = \App\LoanTenor::where('id', '=', $loans->loan_tenor_id)->first();
                            $installments = \App\Installment::where('installmentable_id', '=', $loans->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                            $loanpaid = $loans->amount_requested / $loantenor->month * $installments;
                            $totalpaid = $totalpaid + $loanpaid;
                        return 'Rp.'.number_format($totalpaid,2);
            });
            $grid->column('Balance')->display(function () {
                        $loans = \App\Loan::where('id', '=', "{$this->id}")
                                    ->first();
                        $totalpaid = 0;
                        $loantenor = 0;
                        $loanpaid = 0;
                            $loantenor = \App\LoanTenor::where('id', '=', $loans->loan_tenor_id)->first();
                            $installments = \App\Installment::where('installmentable_id', '=', $loans->id)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
                            $loanpaid = $loans->amount_requested / $loantenor->month * $installments;
                            $totalpaid = $totalpaid + $loanpaid;
                        $loans->balance = number_format(($loans->amount_requested - $totalpaid),2);
                        return !empty($loans) ? $loans->balance : '-';
            });
            $grid->status()->name('Status')->sortable()->display(function ($id) use ($isVerified) {
                switch ($id) {
                    case 'Pending':
                        if ($isVerified == 1) {
                            return 'Verified';
                        }
                        return 'Unverified';
                        break;/*

                    case 'Declined':
                        return 'Rejected';
                        break;

                    case 'Rejected':
                        return 'Declined';
                        break;*/
                    
                    default:
                        return $id;
                        break;
                }
                //return $user ? '<a href="'.url('admin/users/'.$user->id).'">'.$user->name.'</a>' : '';
            });
            $grid->column('Details Installments')->display(function () {
                        return '<a target="_blank" href="/admin/detailsbalanceinstallments?report_=1&installmentable_type=App%5CLoan&installmentable_id='.$this->id.'"><span class="label label-success">Details</span></a>';
            });
            $grid->filter(function($filter){
               $filter->equal('user_id', 'User ID'); 
            });
            $grid->exporter(new ExcelExporterDetailsBalances());
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
        $loans = Loan::whereHas('user', function ($query) {
                $query->where('status_id', '=', '3');
            });
        if (isset($_GET['user_id'])) {
            $loans = $loans->where('user_id', '=', $_GET['user_id']);
        }
        if ($request->input('company_id') != null) {
            $filter = $request->input('company_id');
            $loans = $loans->where('users.company_id', '=', $filter);
        }
        $loans = $loans->join('users', 'users.id', '=', 'loans.user_id')->where('loans.status_id', '=', 3)->whereNull('loans.deleted_at');
        $loans = $loans->join('loan_tenors', 'loan_tenors.id', '=', 'loans.loan_tenor_id');
        $loans = $loans->select('loans.amount_requested','users.name as name', 'loan_tenors.month', 'loans.id as loansid', 'users.id as userid');
        $loans = $loans->get();
        // dd($loans);
        foreach ($loans as $loan) {
            $loan->installmentcount = Installment::where('installmentable_id', '=', $loan->loansid)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
            $installments = Installment::where('installmentable_id', '=', $loan->loansid)->where('installmentable_type', '=', 'App\Loan')->where('paid', '=', 1)->count();
            $loan->loanpaid = $loan->amount_requested / $loan->month * $installments;
            $loan->totalpaid = $loan->totalpaid + $loan->loanpaid;
        }
        view()->share('loans',$loans);
        $pdf = PDF::loadView('admin.detailsbalance.export.pdf');
        return $pdf->stream('detailsbalance.pdf');

    } 
}

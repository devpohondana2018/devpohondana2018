<?php

namespace App\Admin\Controllers;

use App\Loan;
use App\LoanGrade;
use App\User;
use App\Company;
use DB;
use PDF;
use App\Admin\Extensions\ExcelExpoterLoanProfit;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use App\Libraries\CurrencyFormat;

class LoanProfitController extends Controller
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

            $content->header('Loan Profits');
            $content->description('description');
            if (isset($_GET['report_'])) {

                    $loans = Loan::where('status_id', '3');

                    if (isset($_GET['Company'])) {
                        $filter = $_GET['Company'];
                        if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('company_id', '=', $filter);
                            });
                    }

                    if (isset($_GET['id'])) {
                        $filter = $_GET['id'];
                        if (!empty($filter))
                            $loans->where('id', '=', $filter);
                            
                    }

                    if (isset($_GET['amount_requested'])) {
                        $filter = $_GET['amount_requested'];
                        $loans = $this->loanBeetwen($loans, 'amount_requested', $filter);
                    }

                    if (isset($_GET['loan_tenor_id'])) {
                        $filter = $_GET['loan_tenor_id'];
                        if (!empty($filter))
                            $loans->where('loan_tenor_id', $filter);
                    }

                    if (isset($_GET['date_expired'])) {
                        $filter = $_GET['date_expired'];
                        $loans = $this->loanBeetwen($loans, 'date_expired', $filter);
                    }

                    if (isset($_GET['provision_rate'])) {
                        $filter = $_GET['provision_rate'];
                        $loans = $this->loanBeetwen($loans, 'provision_rate', $filter);
                    }

                    if (isset($_GET['interest_rate'])) {
                        $filter = $_GET['interest_rate'];
                        $loans = $this->loanBeetwen($loans, 'interest_rate', $filter);
                    }

                    if (isset($_GET['invest_rate'])) {
                        $filter = $_GET['invest_rate'];
                        $loans = $this->loanBeetwen($loans, 'invest_rate', $filter);
                    }

                    if (isset($_GET['amount_total'])) {
                        $filter = $_GET['amount_total'];
                        $loans = $this->loanBeetwen($loans, 'amount_total', $filter);
                    }

                    if (isset($_GET['Grade'])) {
                        $filter = $_GET['Grade'];
                        if (!empty($filter))
                            $loans->whereHas('grade', function ($query) use ($filter) {
                                $query->where('rank', 'like', $filter);
                            });
                    }

                    $formatUser = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Loans</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmount = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Basic Loans</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';
                    $formatAmountInterest = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Interest Amount</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';
                    $formatAmountProvision = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Platform Amount</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';
                    $formatAmountInvest = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Invest Amount</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';
                    $formatAmountProfit = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Profit Amount</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                $contentUser   = sprintf($formatUser, count($loans->get()));
                $contentAmount = sprintf($formatAmount, CurrencyFormat::rupiah($loans->sum('amount_requested'), true));                    
                $contentAmountInterest = sprintf($formatAmountInterest, CurrencyFormat::rupiah($loans->sum('interest_fee'), true));
                $contentAmountProvision = sprintf($formatAmountProvision, CurrencyFormat::rupiah($loans->sum('provision_fee'), true));
                $contentAmountInvest = sprintf($formatAmountInvest, CurrencyFormat::rupiah($loans->sum('invest_fee'), true));
                $contentAmountProfit = sprintf($formatAmountProfit, CurrencyFormat::rupiah(($loans->sum('interest_fee') + $loans->sum('provision_fee') - $loans->sum('invest_fee')), true));

                    $content->row(function($row) use ($contentUser, $contentAmount, $contentAmountInterest, $contentAmountProvision, $contentAmountInvest, $contentAmountProfit) {
                        $row->column(4, $contentUser);
                        $row->column(4, $contentAmount);
                        $row->column(4, $contentAmountInterest);
                        $row->column(4, $contentAmountProvision);
                        $row->column(4, $contentAmountInvest);
                        $row->column(4, $contentAmountProfit);
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

            $content->header('Loan Profits');
            $content->description('description');

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

            $content->header('Loan Profits');
            $content->description('description');

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
            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="loanprofits/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });

            if (isset($_GET['report_'])) {
                $grid->disableActions();
            } 

            $grid->model()->where('status_id', '=', '3');
            $grid->id('Loan ID')->sortable();
            $grid->user_id('User')->display(function($userId) {
                return User::find($userId)->name;
            });            
            $grid->loan_grade_id('Grade')->display(function($gradeId) {
                $loan_grade_id = LoanGrade::find($gradeId);
                return !empty($loan_grade_id) ? $loan_grade_id->rank : '' ;
            });
            $grid->amount_requested('Basic Loan')->display(function($amount_requested){
                return !empty($amount_requested) ? CurrencyFormat::rupiah($amount_requested) : '' ;
            });
            $grid->interest_rate('Interest Rate');
            $grid->interest_fee('Interest Amount')->display(function($interest_amount){
                return !empty($interest_amount) ? CurrencyFormat::rupiah($interest_amount) : '' ;
            });
            $grid->provision_rate('Platform Rate');
            $grid->provision_fee('Platform Amount')->display(function($provision_amount){
                return !empty($provision_amount) ? CurrencyFormat::rupiah($provision_amount) : 0 ;
            });
            $grid->invest_rate('Invest Rate');
            $grid->invest_fee('Invest Amount')->display(function($invest_amount){
                return !empty($invest_amount) ? CurrencyFormat::rupiah($invest_amount) : '' ;
            });
            // $grid->column('Profit Rate')->display(function () {
            //             $loans = DB::table('loans');
            //             $loans = $loans->where('loans.id', '=', "{$this->id}")
            //                         ->select('invest_fee', 'interest_fee', 'provision_fee', 'amount_requested')
            //                         ->first(); 
            //             // $profit_rate = (!empty($loans) ? $loans->interest_rate : 0) + (!empty($loans) ? $loans->provision_rate : 0) - (!empty($loans) ? $loans->invest_rate : 0);
            //             // return !empty($profit_rate) ? $profit_rate : '-';                        
            //                         $profit_rate = ((!empty($loans) ? $loans->interest_fee : 0) + (!empty($loans) ? $loans->provision_fee : 0) - (!empty($loans) ? $loans->invest_fee : 0)) / $loans->amount_requested;
            //             return !empty($profit_rate) ? number_format($profit_rate,2) : '-';
            // });
            $grid->column('Profit Amount')->display(function () {
                        $loans = DB::table('loans');
                        $loans = $loans->where('loans.id', '=', "{$this->id}")
                                    ->select('invest_fee', 'interest_fee', 'provision_fee')
                                    ->first(); 
                        $profit_rate = (!empty($loans) ? $loans->interest_fee : 0) + (!empty($loans) ? $loans->provision_fee : 0) - (!empty($loans) ? $loans->invest_fee : 0);
                        return !empty($profit_rate) ? CurrencyFormat::rupiah($profit_rate) : '-';
            });
            $grid->filter(function($filter){
                $filter->where(function ($query) {
                        $query->whereHas('user', function ($query) {
                            $query->where('company_id', '=', "{$this->input}");
                        });
                }, 'Company')->select(Company::get()->pluck('name','id'));
            });
            $grid->exporter(new ExcelExpoterLoanProfit());
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
        $loans = Loan::where('status_id', '=', 3);
        if ($request->input('Company') != null) {
            $filter = $request->input('Company');
            $loans = $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('company_id', '=', $filter);
                            });
        }
        if ($request->input('id') != null) {
            $filter = $request->input('id');
            $loans = $loans->where('id', '=', $filter);
        }
        $loans = $loans->get();
        foreach ($loans as $loan) {
            if (!empty($loan->user_id)) {
                $loan->username = User::where('id', '=', $loan->user_id)->first()->name;
            }else{
                $loan->username = '';
            }
            if (!empty($loan->loan_grade_id)) {
                $loan->grade = LoanGrade::where('id', '=', $loan->loan_grade_id)->first()->rank;
            }else{
                $loan->grade = '';
            }
        }

        view()->share('loans',$loans);
        $pdf = PDF::loadView('admin.loanprofits.export.pdf');
        return $pdf->stream('loanprofits.pdf');

    } 
}

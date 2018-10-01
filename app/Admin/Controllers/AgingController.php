<?php

namespace App\Admin\Controllers;

use App\User;
use App\Installment;
use App\Company;
use DB;
use Encore\Admin\Form;
use App\Admin\Extensions\ExcelExpoterAging;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use PDF;
use App\Libraries\CurrencyFormat;

class AgingController extends Controller
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

            $content->header('Aging');
            if (isset($_GET['report_'])) {

                    $now = Carbon::now()->format('Y-m-d');
                    $thirty = Carbon::now()->addDays(-30)->format('Y-m-d');
                    $thirtyone = Carbon::now()->addDays(-31)->format('Y-m-d');
                    $sixty = Carbon::now()->addDays(-60)->format('Y-m-d');;
                    $sixtyone = Carbon::now()->addDays(-61)->format('Y-m-d');
                    $ninety = Carbon::now()->addDays(-90)->format('Y-m-d');
                    $ninetyone = Carbon::now()->addDays(-91)->format('Y-m-d');
                    $installments = Installment::where('due_date', '<', Carbon::now()->format('Y-m-d'));

                    /*dd($sixty);
                    dd($thirtyone);*/
                    $users = User::whereHas('loans', function ($query) {
                                $query->join('installments', 'installmentable_id', '=', 'loans.id')
                                        // ->where('status_id', '=', 3)
                                        ->where('loans.status_id', '=', 3)
                                        ->where('users.verified','=', 1)
                                        ->where('users.active','=', 1)
                                        ->whereNull('installments.deleted_at')
                        				->where('installments.paid', 0)
                                        ->where('installments.installmentable_type', '=', 'App\\Loan')
                                        ->where('installments.due_date', '<', Carbon::now()->format('Y-m-d'));
                            });
                    if (isset($_GET['company_id'])) {
                        $filter = $_GET['company_id'];
                        if (!empty($filter))
                        $users = $users->where('company_id', '=', $filter);
                    }
                    if (isset($_GET['id'])) {
                        $filter = $_GET['id'];
                        if (!empty($filter))
                        $users = $users->where('id', '=', $filter);
                    }
                    $users = $users->get();
                    foreach ($users as $user) {
                        $user->zerotothirty = Installment::whereBetween('due_date', [$thirty,$now])->join('loans', 'loans.id', '=', 'installmentable_id')
                        ->where('installments.paid', 0)->whereNull('installments.deleted_at')->where('loans.user_id', '=', $user->id)->sum('amount');

                        $user->thirtyonetosixty = Installment::whereBetween('due_date', [$sixty,$thirtyone])->join('loans', 'loans.id', '=', 'installmentable_id')
                        ->where('installments.paid', 0)->whereNull('installments.deleted_at')->where('loans.user_id', '=', $user->id)->sum('amount');
                        $user->sixtyonetoninety = Installment::whereBetween('due_date', [$ninety,$sixtyone])->join('loans', 'loans.id', '=', 'installmentable_id')
                        ->where('installments.paid', 0)->whereNull('installments.deleted_at')->where('loans.user_id', '=', $user->id)->sum('amount');
                        $user->morethanninetyone = Installment::where('due_date', '<', $ninetyone)->join('loans', 'loans.id', '=', 'installmentable_id')
                        ->where('installments.paid', 0)->whereNull('installments.deleted_at')->where('loans.user_id', '=', $user->id)->sum('amount');
                        $user->total = Installment::where('due_date', '<', $now)->join('loans', 'loans.id', '=', 'installmentable_id')
                        ->where('installments.paid', 0)->whereNull('installments.deleted_at')->where('loans.user_id', '=', $user->id)->sum('amount');
                    }
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
                                      <span class="info-box-text">Total 0 - 30</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';    

                    $formatAmountPaid = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total 31 - 60</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmountBalance = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total 61-90</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';
                    $formatAmountmorethanninety = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total > 91</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';
                    $contentUser   = sprintf($formatUser, count($users));
                    $contentAmount = sprintf($formatAmount, CurrencyFormat::rupiah($users->sum('zerotothirty'), true));
                    $contentAmountPaid = sprintf($formatAmountPaid, CurrencyFormat::rupiah($users->sum('thirtyonetosixty'), true));
                    $contentAmountBalance = sprintf($formatAmountBalance, CurrencyFormat::rupiah($users->sum('sixtyonetoninety'), true));
                    $contentAmountmorethanninety = sprintf($formatAmountmorethanninety, CurrencyFormat::rupiah($users->sum('morethanninetyone'), true));
                    $content->row(function($row) use ($contentUser, $contentAmount, $contentAmountPaid, $contentAmountBalance, $contentAmountmorethanninety) {
                        $row->column(4, $contentUser);
                        $row->column(4, $contentAmount);
                        $row->column(4, $contentAmountPaid);
                        $row->column(4, $contentAmountBalance);
                        $row->column(4, $contentAmountmorethanninety);
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

            $content->header('Aging');
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

            $content->header('Aging');
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
            $grid->name()->sortable();
            $grid->column('company_id','Company')->sortable()->display(function ($company_id) {
                $company = Company::find($company_id);
                return $company ? $company->name : '-';
            });
            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="agings/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });

            if (isset($_GET['report_'])) {
                $grid->disableActions();
            } 
            $grid->model()->whereHas('loans', function ($query) {
                $query->join('installments', 'installmentable_id', '=', 'loans.id')
                        ->where('status_id', '=', '3')
                        ->where('installments.paid', 0)
                        ->where('installments.installmentable_type', '=', 'App\\Loan')
                        ->where('installments.due_date', '<', Carbon::now()->format('Y-m-d'));
            });
            $grid->Column('0 - 30')->display(function(){
                $installments = Installment::join('loans', 'loans.id', '=', 'installments.installmentable_id')
                                    ->join('users', 'users.id', '=', 'loans.user_id')
                                    ->where('loans.user_id', '=', "{$this->id}")
                        			->where('installments.paid', 0)
                                    ->where('installments.installmentable_type', '=', 'App\\Loan')
                                    ->whereNull('installments.deleted_at');
                $now = Carbon::now()->format('Y-m-d');
                $later = Carbon::now()->addDays(-30)->format('Y-m-d');
                // dd($later);
                $installments = $installments->whereBetween('due_date', [$later,$now]);
                return !empty($installments) ?  CurrencyFormat::rupiah($installments->sum('amount')) : '' ;
            });
            $grid->Column('31 - 60')->display(function(){
                $installments = Installment::join('loans', 'loans.id', '=', 'installments.installmentable_id')
                                    ->join('users', 'users.id', '=', 'loans.user_id')
                                    ->where('loans.user_id', '=', "{$this->id}")
                        			->where('installments.paid', 0)
                                    ->where('installments.installmentable_type', '=', 'App\\Loan')
                                    ->whereNull('installments.deleted_at');
                $now = Carbon::now()->addDays(-31)->format('Y-m-d');
                $later = Carbon::now()->addDays(-60)->format('Y-m-d');
                // dd($later);
                $installments = $installments->whereBetween('due_date', [$later,$now]);
                return !empty($installments) ?  CurrencyFormat::rupiah($installments->sum('amount')) : '' ;
            });
            $grid->Column('61 - 90')->display(function(){
                $installments = Installment::join('loans', 'loans.id', '=', 'installments.installmentable_id')
                                    ->join('users', 'users.id', '=', 'loans.user_id')
                                    ->where('loans.user_id', '=', "{$this->id}")
                        			->where('installments.paid', 0)
                                    ->where('installments.installmentable_type', '=', 'App\\Loan')
                                    ->whereNull('installments.deleted_at');
                $now = Carbon::now()->addDays(-61)->format('Y-m-d');
                $later = Carbon::now()->addDays(-90)->format('Y-m-d');
                // dd($later);
                $installments = $installments->whereBetween('due_date', [$later,$now]);
                return !empty($installments) ?  CurrencyFormat::rupiah($installments->sum('amount')) : '' ;
            });            
            $grid->Column('> 91')->display(function(){
                $installments = Installment::join('loans', 'loans.id', '=', 'installments.installmentable_id')
                                    ->join('users', 'users.id', '=', 'loans.user_id')
                                    ->where('loans.user_id', '=', "{$this->id}")
                        			->where('installments.paid', 0)
                                    ->where('installments.installmentable_type', '=', 'App\\Loan')
                                    ->whereNull('installments.deleted_at');
                $now = Carbon::now()->addDays(-61)->format('Y-m-d');
                $later = Carbon::now()->addDays(-90)->format('Y-m-d');
                // dd($later);
                $installments = $installments->where('due_date', '<', $later);
                return !empty($installments) ?  CurrencyFormat::rupiah($installments->sum('amount')) : '' ;
            });
            $grid->Column('Total')->display(function(){
                $now = Carbon::now()->format('Y-m-d');
                $installments1 = Installment::join('loans', 'loans.id', '=', 'installments.installmentable_id')
                                    ->join('users', 'users.id', '=', 'loans.user_id')
                                    ->where('loans.user_id', '=', "{$this->id}")
                        			->where('installments.paid', 0)
                                    ->where('installments.installmentable_type', '=', 'App\\Loan')
                                    ->whereNull('installments.deleted_at')
                                    ->where('due_date', '<' ,$now)->sum('amount');
                return !empty($installments1) ?  CurrencyFormat::rupiah($installments1) : '' ;
            });
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->equal('company_id', 'Company')->select(Company::get()->pluck('name','id'));
            });
            $grid->exporter(new ExcelExpoterAging());
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
        $now = Carbon::now()->format('Y-m-d');
        $thirty = Carbon::now()->addDays(-30)->format('Y-m-d');
        $thirtyone = Carbon::now()->addDays(-31)->format('Y-m-d');
        $sixty = Carbon::now()->addDays(-60)->format('Y-m-d');;
        $sixtyone = Carbon::now()->addDays(-61)->format('Y-m-d');
        $ninety = Carbon::now()->addDays(-90)->format('Y-m-d');
        $ninetyone = Carbon::now()->addDays(-91)->format('Y-m-d');
        $users = User::whereHas('loans', function ($query) {
                $query->join('installments', 'installmentable_id', '=', 'loans.id')
                        // ->where('status_id', '=', '3')
                        ->where('loans.status_id', '=', 3)
                        ->where('users.verified','=', 1)
                        ->where('users.active','=', 1)
                        ->where('installments.paid', 0)
                        ->whereNull('loans.deleted_at')
                        ->whereNull('installments.deleted_at')
                        ->where('installments.installmentable_type', '=', 'App\\Loan')
                        ->where('installments.due_date', '<', Carbon::now()->format('Y-m-d'));
            });
        if ($request->input('company_id') != null) {
            $filter = $request->input('company_id');
            $users = $users->where('company_id', '=', $filter);
        }
        if ($request->input('id') != null) {
            $filter = $request->input('id');
            $users = $users->where('id', '=', $filter);
        }
        $users = $users->get();
        foreach ($users as $user) {
            $user->zerotothirty = Installment::whereBetween('due_date', [$thirty,$now])
                                ->join('loans', 'loans.id', '=', 'installmentable_id')
                                ->where('installments.paid', 0)
                                ->where('loans.user_id', '=', $user->id)
                                ->sum('amount');
            $user->thirtyonetosixty = Installment::whereBetween('due_date', [$sixty,$thirtyone])
                                ->join('loans', 'loans.id', '=', 'installmentable_id')
                                ->where('installments.paid', 0)
                                ->where('loans.user_id', '=', $user->id)
                                ->sum('amount');
            $user->sixtyonetoninety = Installment::whereBetween('due_date', [$ninety,$sixtyone])
                                ->join('loans', 'loans.id', '=', 'installmentable_id')
                                ->where('installments.paid', 0)
                                ->where('loans.user_id', '=', $user->id)
                                ->sum('amount');
            $user->morethanninetyone = Installment::where('due_date', '<', $ninetyone)
                                ->join('loans', 'loans.id', '=', 'installmentable_id')
                                ->where('installments.paid', 0)
                                ->where('loans.user_id', '=', $user->id)
                                ->sum('amount');
            $user->total = Installment::where('due_date', '<', $now)
                                ->join('loans', 'loans.id', '=', 'installmentable_id')
                                ->where('installments.paid', 0)
                                ->where('loans.user_id', '=', $user->id)
                                ->sum('amount');
        }

        view()->share('users',$users);
        $pdf = PDF::loadView('admin.agings.export.pdf');
        return $pdf->stream('agings.pdf');

    } 
}

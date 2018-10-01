<?php

namespace App\Admin\Controllers;

use App\Installment;
use DB;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request; 
use PDF;
use App\Admin\Extensions\ExcelExporterDetailsInstallment;

class DetailsBalanceInstallmentController extends Controller
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

            $content->header('Installment Details');
            $content->breadcrumb(
             ['text' => 'Balances', 'url' => '/balances'],
             ['text' => 'Details Balance', 'url' => '/detailsbalances'],
             ['text' => 'Details Installment', 'url' => '/detailsbalanceinstallments']
            );            
            if (isset($_GET['report_'])) {

                    $installments = DB::table('installments');
                    $installments2 = DB::table('installments');
                    if (isset($_GET['installmentable_type'])) {
                        $type = $_GET['installmentable_type'];
                        if ($type == 'App\Loan') {
                            if (isset($_GET['installmentable_id'])) {
                                $installments = $installments->join('loans', 'loans.id', '=', 'installments.installmentable_id')
                                                ->where('installmentable_type', '=', 'App\Loan')
                                                ->where('installments.installmentable_id', '=', $_GET['installmentable_id']);
                                $installments2 = $installments->join('loans as loans2', 'loans2.id', '=', 'installments.installmentable_id')
                                                ->where('installmentable_type', '=', 'App\Loan')
                                                ->where('installments.installmentable_id', '=', $_GET['installmentable_id']);
                            }
                        }else {
                            if (isset($_GET['installmentable_id'])) {
                                $installments = $installments->join('investments', 'investments.id', '=', 'installments.installmentable_id')
                                                ->join('loans', 'loans.id', '=', 'investments.loan_id')
                                                ->where('installmentable_type', '=', 'App\Investment')
                                                ->where('installments.installmentable_id', '=', $_GET['installmentable_id']);
                                $installments2 = $installments->join('investments', 'investments.id', '=', 'installments.installmentable_id')
                                                ->join('loans as loans2', 'loans2.id', '=', 'investments.loan_id')
                                                ->where('installmentable_type', '=', 'App\Investment')
                                                ->where('installments.installmentable_id', '=', $_GET['installmentable_id']);
                                }
                        }
                    }
                    $formatInstallment = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Installments</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmount = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Amount</span>
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

                    $formatAmountUnPaid = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Unpaid</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $installmentPaidContext = $installments;
                    // $installmentUnpaidContext = $installments2;
                    $contentInstallment   = sprintf($formatInstallment, count($installments->get()));

                    $contentAmount = sprintf($formatAmount, 'Rp. ' . number_format($installments->sum('amount'), 0, '.', '.'));

                    $installmentsAmounts = $installments->select(DB::raw('sum(amount) as amount_value'))->orderBy('paid', 'ASC')->groupBy('paid');
                    $currentRow = 1;
                    foreach ($installmentsAmounts->get() as $installmentsAmount) {
                        if ($currentRow == 1) {
                            $contentAmountUnPaid = sprintf($formatAmountUnPaid, 'Rp. ' . number_format($installmentsAmount->amount_value, 0, '.', '.'));
                        }else{
                            $contentAmountPaid = sprintf($formatAmountPaid, 'Rp. ' . number_format($installmentsAmount->amount_value, 0, '.', '.'));
                        }
                        $currentRow++;
                    }
                    if (empty($contentAmountPaid)) {
                        $contentAmountPaid = sprintf($formatAmountPaid, 'Rp. 0');
                    }
                    //$paidinstallment = $installmentPaidContext->where('paid', '=', 1);
                    // $unpaidinstallment = $installmentUnpaidContext->where('paid', '=', 1);
                    if (!empty($contentAmountUnPaid)) {
                       $content->row(function($row) use ($contentInstallment, $contentAmount, $contentAmountPaid, $contentAmountUnPaid) {
                        $row->column(4, $contentInstallment);
                        $row->column(4, $contentAmount);
                        $row->column(4, $contentAmountPaid);
                        $row->column(4, $contentAmountUnPaid);
                      });
                    }else{
                        $content->row(function($row) use ($contentInstallment, $contentAmount, $contentAmountPaid) {
                        $row->column(4, $contentInstallment);
                        $row->column(4, $contentAmount);
                        $row->column(4, $contentAmountPaid);
                        });
                    }
                    
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

            $content->header('Installment Details');

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

            $content->header('Installment Details');

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
            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="detailsbalancesinstallments/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });
            $grid->id('Installment ID')->display(function($id){
                return !empty($id) ? 'Installment '.$id : '' ;
            });
            $grid->amount('Amount')->display(function($amount){
                return !empty($amount) ? number_format($amount,2) : '' ;
            });
            $grid->balance('Balance')->display(function($balance){
                return !empty($balance) ? number_format($balance,2) : '' ;
            });
            if (isset($_GET['installmentable_type'])) {
                $companysource = $_GET['installmentable_type'];
            }else{   
                $companysource = '';
            }
            if ($companysource == 'App\Loan') {# code...
                $grid->column('Tenor')->display(function () {
                    $installments = DB::table('installments');
                    $installmentstenor = $installments->join('loans', 'installments.installmentable_id', '=', 'loans.id')
                                        ->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')
                                        ->where('installments.id', '=', "{$this->id}")
                                        ->first();
                    return !empty($installmentstenor) ? $installmentstenor->tenor . '/' . $installmentstenor->month : '-';
                });
            }else{
                $grid->tenor('Tenor')->display(function($tenor){
                    return !empty($tenor) ? $tenor : '' ;
                });                
            }
            $grid->paid('Status')->sortable()->display(function ($paid) {
                return $paid ? 'Paid' : 'Unpaid';
            });
            $grid->due_date();

            $grid->filter(function($filter){
               $filter->equal('installmentable_id', 'Loan ID'); 
               $filter->equal('installmentable_type', 'Installment Type')->select(['App\Loan' => 'Loan','App\Investment' => 'Investment']); 
            });
            $grid->exporter(new ExcelExporterDetailsInstallment());
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

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        $installments = (new Installment());
        if ($request->input('installmentable_id') != null) {
            $filter = $request->input('installmentable_id');
            $installments = $installments->where('installmentable_id', '=', $filter);
        }
        if ($request->input('installmentable_type') != null) {
            $filter = $request->input('installmentable_type');
            $installments = $installments->where('installmentable_type', '=', $filter);
        }
        if ($request->input('installmentable_type') == 'App\Loan') {
            $installments = $installments->join('loans', 'loans.id', '=', 'installments.installmentable_id')->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')->where('installmentable_type','=', 'App\Loan');
        }else{
            $installments = $installments->join('investments', 'investments.id', '=', 'installments.installmentable_id')->join('loans', 'loans.id', '=', 'installments.installmentable_id')->join('loan_tenors', 'loans.loan_tenor_id', '=', 'loan_tenors.id')->where('installmentable_type','=', 'App\Investment');
        }
        $installments = $installments->get();
        view()->share('installments',$installments);
        $pdf = PDF::loadView('admin.detailsbalanceinstallment.export.pdf');
        return $pdf->stream('detailsbalanceinstallment.pdf');

    } 
}

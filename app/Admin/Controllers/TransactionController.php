<?php

namespace App\Admin\Controllers;

use App\Transaction;
use App\User;
use App\Status;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use PDF;
use App\Admin\Extensions\ExcelExporterTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;
use App\Investment;
use App\Installment;
use App\Loan;
use DB;

class TransactionController extends Controller
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

            $content->header('Transactions');
            $content->description('List');

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

            $content->header('Transactions');
            $content->description('Edit');

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

            $content->header('Transactions');
            $content->description('Create');

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
        return Admin::grid(Transaction::class, function (Grid $grid) {

            if(Admin::user()->isRole('auditor')) {
                $grid->disableCreateButton();
                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableExport();
            }

            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="transactions/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });
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
            
            $grid->id('ID')->sortable();
            $grid->user()->id('User')->sortable()->display(function ($id) {
                $user = User::find($id);
                return $user ? '<a href="'.url('admin/users/'.$user->id).'">'.$user->name.'</a>' : '';
            });
            $grid->amount('Amount')->sortable()->display(function ($amount) {
                return number_format($amount,2,',','.');
            });
            $grid->exporter(new ExcelExporterTransaction());
            $grid->type('Type')->sortable();
            $grid->transactionable_type('Transaction')->sortable()->display(function ($transactionable_type) {
                return str_replace('App\\', '', $transactionable_type);
            });
            $grid->status_id('Status')->sortable()->display(function ($status_id) {
                return Status::find($status_id)->name;
            });

            $grid->created_at()->sortable();

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->equal('user_id','User');
                $filter->between('amount');
                $filter->equal('type', 'Type')
                       ->select([
                            'Cash In' => 'Cash In',
                            'Cash Out' => 'Cash Out',
                        ]);
                $filter->equal('transactionable_type', 'Transaction')
                       ->select([
                            'App\Installment' => 'Installment',
                            'App\Investment' => 'Investment',
                            'App\Loan' => 'Loan'
                        ]);
                $filter->equal('status_id', 'Status')->select([
                    '1' => 'Pending',
                    '7' => 'Completed',
                ]);
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Transaction::class, function (Form $form) {

            // Kalau edit
            if( $transaction = Transaction::find(request()->route('transaction')) ) {
               
                $form->display('id', 'ID');
                $form->text('amount', 'Amount')->attribute(['value' => number_format($transaction->amount,2,',','.'), 'class' => 'form-control mask_money']);
                $form->display('user.name','User');
                $form->display('notes', 'Notes');

                //$form->image('payment_image', 'Payment Image')->attribute(['readonly' => 'true']);
                $form->date('payment_date', 'Payment Date')->attribute(['readonly' => 'true']);
                $states = [
                    'on'  => ['value' => 7, 'text' => 'Completed', 'color' => 'success'],
                    'off' => ['value' => 1, 'text' => 'Pending', 'color' => 'danger'],
                ];

                $form->switch('status_id', 'Status')->states($states);

                /*$form->select('status_id', 'Status')->options([
                    '1' => 'Pending',
                    '7' => 'Completed',
                ]);*/

                $form->display('created_at', 'Created At');
                $form->display('updated_at', 'Updated At');

                $form->disableReset();

                $form->saving(function (Form $form) {
                    $form->amount = str_replace(',','.',str_replace('.','',$form->amount));
                });

                $transactionId = request()->route('transaction');
                $transaction = Transaction::find($transactionId);
                //dd($transaction->transactionable_type);

                $form->saved(function (Form $form) use ($transaction) {
                    try {

                        if ($form->model()->status_id == 7) {
                            $transactionableId = $transaction->transactionable_id;

                            $investment = Investment::find($transactionableId);
                            if (!empty($investment)) {
                                $investment->paid = 1;
                                $investment->save();

                            }else {
                                $installment = Installment::find($transactionableId);
                                if (!empty($installment)) {
                                    $installment->paid = 1;
                                    $installment->save();
                                }
                            }
                            
                        }

                    } catch (\Exception $e){
                        admin_toastr('Something went wrong. Please try again!'. $e->getMessage());
                        return redirect('/admin/transactions/'.request()->route('transaction').'/edit');
                    }

                    admin_toastr('Update succeeded!');
                    return redirect('/admin/transactions/'.request()->route('transaction').'/edit');
                });

            } else {

                $form->text('amount', 'Amount')->attribute(['class' => 'form-control mask_money']);
                $form->select('user_id','User')->options(User::get()->pluck('name','id'));
                $form->textarea('notes', 'Notes')->rows(10);

                $form->disableReset();

                $form->saving(function (Form $form) {
                    $form->amount = str_replace(',','.',str_replace('.','',$form->amount));
                });

                $form->saved(function (Form $form) {
                    admin_toastr('Update succeeded!');
                    return back();
                });
            }

            
        });
    }
    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        // dd($urlparams);
        $transactions = (New Transaction());
        // dd($transactions);
        if ($request->input('user_id') != null) {
            $transactions = $transactions->where('user_id',$request->input('user_id'));
        }
        if ($request->input('type') != null) {
            $transactions = $transactions->where('type',$request->input('type'));
        }
        if ($request->input('transactionable_type') != null) {
            $transactions = $transactions->where('transactionable_type',$request->input('transactionable_type'));
        }
        if ($request->input('status_id') != null) {
            $transactions = $transactions->where('status_id',$request->input('status_id'));
        }
        if ($request->input('amount.start') != null || ($request->input('amount.end') != null))    {
            if ($request->input('amount.start') != null && $request->input('amount.end') != null) {
                // return '1';
                $transactions = $transactions->whereBetween('amount', [$request->input('amount.start'), $request->input('amount.end')]);
            }elseif($request->input('amount.start') != null){
                // return '2';
                $transactions = $transactions->where('amount', '>=', $request->input('amount.start'));
            }else{
                // return '3';
                $transactions = $transactions->where('amount', '<=', $request->input('amount.end'));
            }
        }
        $transactions = $transactions->get();
        // dd($transactions);
        foreach ($transactions as $transaction) {
            $transaction->username = User::find($transaction->user_id)->name;
            $transaction->status = Status::find($transaction->status_id)->name;
        }
        // return response()->json($installments);
        view()->share('transactions',$transactions);
        $pdf = PDF::loadView('admin.transactions.export.pdf');
        return $pdf->stream('transactions.pdf');
        // return $users;
        // return view('admin.users.export.pdf', compact('users'));

    }
}

<?php

namespace App\Admin\Controllers;

use PDF;
use DB;
use Exception;
use Carbon\Carbon;
use App\Loan;
use App\User;
use App\Status;
use App\Company;
use App\Investment;
use App\Transaction;
use App\Events\InvestmentApproved;
use App\Events\InvestmentDeclined;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Admin\Extensions\ExcelExporterInvestment;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use App\Events\CashInInvestment;
use App\Libraries\CurrencyFormat;

class InvestmentController extends Controller
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
            $content->header('Fundings');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Fundings', 'url' => '/investments']
            );            
            if (isset($_GET['report_'])) {

                    $investments = Investment::whereHas('user', function ($query) {
                                $query->where('verified', '=', 1);
                            });
                    // $investments = $investments->where('status_id','!=','8');
                    if (isset($_GET['Company'])) {
                        $filter = $_GET['Company'];
                        if (!empty($filter))
                               $investments->join('users', 'users.id', '=', 'investments.user_id')
                                    ->join('companies', 'users.company_id', '=', 'companies.id')
                                    ->where('companies.id', '=', $filter);
                    }
                    if (isset($_GET['loan_id'])) {
                        $filter = $_GET['loan_id'];
                        if (!empty($filter))
                            $investments->where('loan_id', '=', $filter);
                    }

                    if (isset($_GET['status_id'])) {
                        $filter = $_GET['status_id'];
                        if (!empty($filter))
                            $investments->where('status_id', '=', $filter);
                    }

                    if (isset($_GET['paid'])) {
                        $filter = $_GET['paid'];
                            $investments->where('paid', '=', $filter);
                            if ($filter==1) {
                                $investments = $investments->where('status_id','!=','8');
                            }
                            if ($filter==0) {
                            	$investments = $investments->where('status_id','!=','8');
                            }
                    }

                    if (isset($_GET['user_id'])) {
                        $filter = $_GET['user_id'];
                        if (!empty($filter))
                            $investments->where('user_id', '=', $filter);
                    }

                    if (isset($_GET['amount_invested'])) {
                        $filter = $_GET['amount_invested'];
                        $investments = $this->InvestmentsBeetwen($investments, 'amount_invested', $filter);
                    }

                    if (isset($_GET['invest_rate'])) {
                        $filter = $_GET['invest_rate'];
                        $investments = $this->InvestmentsBeetwen($investments, 'invest_rate', $filter);
                    }

                    if (isset($_GET['amount_total'])) {
                        $filter = $_GET['amount_total'];
                        $investments = $this->InvestmentsBeetwen($investments, 'amount_total', $filter);
                    }


                    $formatUser = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total User Fundinds</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmount = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Fundings</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';    

                    $contentUser   = sprintf($formatUser, count($investments->get()));
                    $contentAmount = sprintf($formatAmount, CurrencyFormat::rupiah($investments->sum('amount_invested'), true));

                    $content->row(function($row) use ($contentUser, $contentAmount) {
                        $row->column(4, $contentUser);
                        $row->column(4, $contentAmount);
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

            $content->header('Fundings');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Fundings', 'url' => '/investments'],
             ['text' => 'Edit', 'url' => '/investments/edit']
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

            $content->header('Fundings');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Fundings', 'url' => '/investments'],
             ['text' => 'Create', 'url' => '/investments/create']
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
        return Admin::grid(Investment::class, function (Grid $grid) {

            if(Admin::user()->isRole('auditor')) {
                $grid->disableCreateButton();
                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableExport();
            }
            if (isset($_GET['report_'])) {
                $grid->disableActions();
            } 

            $grid->model()->whereHas('user', function ($query) {
                $query->where('verified', '=', '1');
            });

            if (isset($_GET['paid'])) {
                $filter = $_GET['paid'];
                    if ($filter==1) {
                        $grid->model()->where('status_id','!=','8');
                    }
                    if ($filter==0) {
                    	$grid->model()->where('status_id','!=','8');
                    }
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

            $grid->tools(function ($tools) {
                $params = '';
                if (isset($_SERVER['QUERY_STRING'])) {
                    $params = $_SERVER['QUERY_STRING'];
                }
                $tools->append('<a target="_blank" href="investments/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            });
            $grid->code('Funding ID')->sortable();
            $grid->column('user_id','Company')->display(function ($id) {
                $user = User::find($id);
                return $user->company ? $user->company->name : '-';
            });
            $grid->user()->id('User')->sortable()->display(function ($id) {
                $user = User::find($id);
                return $user ? '<a href="'.url('admin/users/'.$user->id).'">'.$user->name.'</a>' : '';
            });
            $grid->loan_id('Reference')->sortable()->display(function ($id) {
                $loan = Loan::find($id);
                return $loan ? '<a href="'.url('admin/loans/'.$loan->id).'">Loan #'.$id.'</a>' : '';
            });
            $grid->amount_invested('Jumlah')->setAttributes(['align' => 'left'])->sortable()->display(function ($amount) {
                return CurrencyFormat::rupiah($amount);
            });
            $grid->status()->name('Status')->sortable();
            $grid->created_at('Funding Date')->sortable();
            $grid->exporter(new ExcelExporterInvestment());

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('company_id', '=', "{$this->input}");
                    });
                }, 'Company')->select(Company::get()->pluck('name','id'));
                $filter->equal('loan_id','ID Pinjaman');
                $filter->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('id', '=', "{$this->input}");
                    });
                }, 'Pendana')->select(User::get()->pluck('name','id'));
                $filter->between('amount_invested');
                $filter->between('invest_rate');
                $filter->between('amount_total', 'Total Pinjaman');
                $filter->equal('status_id', 'Status')->select([
                    '3' => 'Accepted',
                    '7' => 'Completed',
                    '8' => 'Canceled',
                ]);
                $filter->equal('paid', 'Paid')->select([
                    '1' => 'Paid',
                    '0' => 'Unpaid' 
                ]);
                $filter->equal('user_id','ID Pendana');
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
        return Admin::form(Investment::class, function (Form $form) {

            $form->disableReset();

            // Kalau edit data
            if(request()->route('investment')) {
            
                // If user has role
                if( Admin::user()->can('investment.verifies') ) {

                    $investment = Investment::find(request()->route('investment'));

                    $form->html('<div class="alert alert-info"><h4><i class="icon fa fa-info-circle"></i> Status Pendanaan: '.strtoupper($investment->status->name).'</h4></div>');

                    $form->tools(function (Form\Tools $tools) use ($investment) {
                        // If pending status
                        if($investment->status_id == 1) {
                            if(Admin::user()->can('investment.approves')) {
                                $tools->add('<a class="btn btn-sm btn-success" style="margin-right:10px;" href="'.url('admin/investments/updateStatus/'.request()->route('investment')).'/2"><i class="fa fa-check"></i>&nbsp;&nbsp;Approve Funding</a>');
                            }
                        }
                            
                        if(Admin::user()->can('investment.declines')) {
                            $tools->add('<a class="btn btn-sm btn-danger" style="margin-right:10px;" href="'.url('admin/investments/updateStatus/'.request()->route('investment')).'/4"><i class="fa fa-times"></i>&nbsp;&nbsp;Decline Funding</a>');
                        }
                            
                        if ($investment->paid == 0) {
                            if(Admin::user()->can('investment.declines')) {
                                $tools->add('<a class="btn btn-sm btn-warning" style="margin-right:10px;" href="'.url('admin/investments/updatePayment/'.request()->route('investment')).'"><i class="fa fa-check"></i>&nbsp;&nbsp;Finish Payment</a>');
                            }
                        }
                        
                        // $tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/investments/calculateRates/'.request()->route('investment')).'"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Calculate Rates</a>');
                            
                        $investment = Investment::find(request()->route('investment'));
                        if(!$investment->installments()->count()) {
                            $tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/investments/generateInstallments/'.request()->route('investment')).'"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Generate Installments</a>');
                        } else {
                            $tools->add('<a class="btn btn-sm btn-danger" style="margin-right:10px;" href="'.url('admin/investments/deleteInstallments/'.request()->route('investment')).'"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete Installments</a>');
                        }
                    });

                    $form->tools(function (Form\Tools $tools) {
                        
                        // Disable back btn.
                        $tools->disableListButton();

                        $investment = Investment::find(request()->route('investment'));
                        if($investment->installments()->count() > 0) {
                            $data = array(
                                'installmentable_type' => 'App\Investment',
                                'installmentable_id' => request()->route('investment')
                            );
                            $query_url = http_build_query($data) . "\n";
                            $tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/installments?&'.$query_url).'" target="_blank"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Installments</a>');
                        }
                    });
                }

                $form->tab('Account Information', function ($form) use ($investment) {

                    $form->display('user.name','User');

                    if(($investment->status_id == 1) || ($investment->status_id == 2)) {
                        $form->text('amount_invested', 'Amount Funded')->attribute(['value' => number_format($investment->amount_invested,2,',','.'), 'class' => 'form-control mask_money']);
                        $form->select('loan_id', 'Loan ID')->options(Loan::get()->pluck('id','id'));
                        $form->text('invest_rate', 'Lender Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');

                    } else {
                        $form->text('amount_invested', 'Amount Funded')->attribute(['value' => number_format($investment->amount_invested,2,',','.'), 'readonly' => 'true']);
                        $form->display('loan_id', 'Loan ID');
                        $form->display('invest_rate', 'Lender Rate (%)');
                    }

                    $form->display('loan.interest_rate', 'Borrower Rate (%)');
                    $form->text('invest_fee', 'Lender Fee (calculated)')->attribute(['value' => number_format($investment->invest_fee,2,',','.'), 'readonly' => 'true']);
                    $form->text('amount_total', 'Amount Total (calculated)')->attribute(['value' => number_format($investment->amount_total,2,',','.'), 'readonly' => 'true']);
                    $form->textarea('notes','Admin Notes');

                    $form->saving(function (Form $form) {
                        $form->amount_invested = str_replace(',','.',str_replace('.','',$form->amount_invested));
                        $form->invest_fee = str_replace(',','.',str_replace('.','',$form->invest_fee));
                        $form->amount_total = str_replace(',','.',str_replace('.','',$form->amount_total));
                    });

                })->tab('Payment', function ($form) use ($investment) {
                    $form->image('payment_image', 'Bukti Transfer');
                });


                $form->saved(function (Form $form) {
                    admin_toastr('Update succeeded!');
                    return redirect('/admin/investments/'.request()->route('investment').'/edit');
                });
                

            } else {

                // If create data
                $form->select('user_id','User')->options(User::get()->pluck('name','id'));
                $form->text('amount_invested', 'Amount Invested');
                $form->select('loan_id', 'Loan ID')->options(Loan::get()->pluck('id','id'));
                $form->text('invest_rate', 'Lender Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                $form->textarea('notes','Admin Notes');
            }
        });
    }

    public function updateStatus($investment_id, $status_id)
    {   
        DB::beginTransaction();

        try {
            $investment = Investment::findOrFail($investment_id);
            // Check if loan assigned
            if($investment->loan) {
                $investment->status_id = $status_id;
                $investment->save();

                if($investment->status_id == 2) {
                    //Approved
                    $loan = Loan::findOrFail($investment->loan_id);
                    $pendingLoanAmount = $loan->amount_total - $loan->amount_funded;
                    if ($investment->amount_invested <= $pendingLoanAmount) {
                        $investment->generateInstallments();
                        //event(new InvestmentApproved($investment));

                        admin_toastr('Status updated');
                    }else{
                        admin_toastr('Jumlah pendanaan melebihi jumlah pinjaman', 'error');
                    }
                } elseif($investment->status_id == 4) {
                    // declined
                    event(new InvestmentDeclined($investment));
                    admin_toastr('Status updated');
                }

            } else { 
                admin_toastr('Please select loan first','error'); 
            }
        } catch (Exception $e) {
            DB::rollback();
            admin_toastr(
                    'Terjadi kesalahan saat mengirim data. Silahkan ulagi kembali. ' . config('app.debug') . 
                    (!config('app.debug') ?: $e->getMessage()), 
                    'error');
            return redirect('admin/investments/'.$investment_id.'/edit');
        }

        DB::commit();
        return redirect('admin/investments/'.$investment_id.'/edit');
    }

    public function updatePayment($investment_id)
    {   
        DB::beginTransaction();

        try {
            $investment = Investment::findOrFail($investment_id);

            if($investment->loan) {
                $loan = Loan::findOrFail($investment->loan_id);

                $pendingLoanAmount = $loan->amount_funded != null ? ($loan->amount_total - $loan->amount_funded) : $loan->amount_total;
                if ($investment->amount_invested > $pendingLoanAmount) {
                    admin_toastr('Jumlah pendanaan melebihi jumlah/sisa peminjaman', 'error');
                } else {
                    $investment->paid = 1;
                    $investment->save();

                    $hasTransaction = Transaction::where([
                        'user_id' => $investment->user_id, 
                        'transactionable_id' => $investment->id,
                        'transactionable_type' => 'App\Investment',
                    ])->first();

                    if (empty($hasTransaction)) {
                        $transactionid = Transaction::insertGetId([
                            'amount' => $investment->amount_invested,
                            'user_id' => $investment->user_id,
                            'transactionable_id' => $investment->id,
                            'transactionable_type' => 'App\Investment',
                            'type' => 'Cash In',
                            'status_id' => 1,
                            'created_at' => Carbon::now()
                        ]);

                        $transaction3 = Transaction::find($transactionid);
                        event(new CashInInvestment($transaction3));
                    }
                    
                    $loan->amount_funded += $investment->amount_invested;
                    $loan->save();
                    admin_toastr('Status updated');
                }
            } else { 
                admin_toastr('Please select loan first','error'); 
            }
        } catch (Exception $e) {
            DB::rollback();
            admin_toastr(
                    'Terjadi kesalahan saat mengirim data. Silahkan ulagi kembali. ' . config('app.debug') . 
                    (!config('app.debug') ?: $e->getMessage()), 
                    'error');
            return redirect('admin/investments/'.$investment_id.'/edit');
        }

        DB::commit();
        return redirect('admin/investments/'.$investment_id.'/edit');
    }

    public function calculateRates($investment_id)
    {
        $investment = Investment::findOrFail($investment_id);
        if( $investment->calculateRates() ) { admin_toastr('Rates calculated'); }
        else { admin_toastr('Silahkan memilih pinjaman terlebih dahulu','error'); }
        return redirect('admin/investments/'.$investment_id.'/edit');
    }

    public function generateInstallments($investment_id)
    {
        $investment = Investment::findOrFail($investment_id);
        if($investment->generateInstallments()) { admin_toastr('Investment installments created'); }
        else { admin_toastr('Investment installments already created','error'); }
        return redirect('admin/investments/'.$investment_id.'/edit');
    }

    public function deleteInstallments($investment_id)
    {
        $investment = Investment::findOrFail($investment_id);
        if($investment->deleteInstallments()) { admin_toastr('Investment installments deleted'); }
        else { admin_toastr('No installments available to delete','error'); }
        return redirect('admin/investments/'.$investment_id.'/edit');
    }
    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        // dd($urlparams);
        $investments = DB::table('investments')
                        ->join('users', 'users.id', '=', 'investments.user_id')
                        ->join('statuses', 'statuses.id', '=', 'investments.status_id')
                        ->where('users.verified', '=', 1);
        if ($request->input('paid') != null) {
            $investments = $investments->where('paid', $request->input('paid'));
        }  
        if ($request->input('Company') != null) {
            $investments = $investments->join('companies', 'companies.id', '=', 'users.company_id');
        }        
        if ($request->input('status_id') != null) {
            $investments = $investments->where('status_id', '=', $request->input('status_id'));
        }else{
            $investments = $investments->where('status_id', '=', 3);
        }     
        if ($request->input('loan_id') != null) {
            $investments = $investments->where('loan_id', '=', $request->input('loan_id'));
        }          
        if ($request->input('user_id') != null) {
            $investments = $investments->where('user_id', '=', $request->input('user_id'));
        }         
        if ($request->input('amount_invested.start') != null || ($request->input('amount_invested.end') != null))    {
            if ($request->input('amount_invested.start') != null && $request->input('amount_invested.end') != null) {
                // return '1';
                $investments = $investments->whereBetween('amount_invested', [$request->input('amount_invested.start'), $request->input('amount_invested.end')]);
            }elseif($request->input('amount_invested.start') != null){
                // return '2';
                $investments = $investments->where('amount_invested', '>=', $request->input('amount_invested.start'));
            }else{
                // return '3';
                $investments = $investments->where('amount_invested', '<=', $request->input('amount_invested.end'));
            }
        }  
        if ($request->input('invest_rate.start') != null || ($request->input('invest_rate.end') != null))    {
            if ($request->input('invest_rate.start') != null && $request->input('invest_rate.end') != null) {
                // return '1';
                $investments = $investments->whereBetween('invest_rate', [$request->input('invest_rate.start'), $request->input('invest_rate.end')]);
            }elseif($request->input('invest_rate.start') != null){
                // return '2';
                $investments = $investments->where('invest_rate', '>=', $request->input('invest_rate.start'));
            }else{
                // return '3';
                $investments = $investments->where('invest_rate', '<=', $request->input('invest_rate.end'));
            }
        }    
        if ($request->input('amount_total.start') != null || ($request->input('amount_total.end') != null))    {
            if ($request->input('amount_total.start') != null && $request->input('amount_total.end') != null) {
                // return '1';
                $investments = $investments->whereBetween('amount_total', [$request->input('amount_total.start'), $request->input('amount_total.end')]);
            }elseif($request->input('amount_total.start') != null){
                // return '2';
                $investments = $investments->where('amount_total', '>=', $request->input('amount_total.start'));
            }else{
                // return '3';
                $investments = $investments->where('amount_total', '<=', $request->input('amount_total.end'));
            }
        }    
        $investments = $investments
                            ->select('users.name as name', 'investments.loan_id', 'investments.amount_invested', 'statuses.name as status_name', 'investments.created_at', 'investments.id', 'investments.code')
                            ->get();
        view()->share('investments',$investments);
        $pdf = PDF::loadView('admin.investments.export.pdf');
        return $pdf->stream('investments.pdf');
        // return $users;
        // return view('admin.installments.export.pdf', compact('users'));

    } 
    private function InvestmentsBeetwen($investments, $column, $filter)
    {
        if (!empty($filter)) {
            $start = $filter['start']; 
            $end   = $filter['end']; 
            if ($start != null) {
                $investments->where($column, '>=', $start);
            }

            if ($end != null) {
                $investments->where($column, '<=', $end);
            }

            return $investments;
        }

        return $investments;
    }
}

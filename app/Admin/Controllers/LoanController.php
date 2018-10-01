<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ExcelExpoter;
use App\Loan;
use App\LoanTenor;
use App\LoanGrade;
use App\Status;
use App\User;
use App\Company;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Row;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Events\LoanApproved;
use App\Events\LoanRejected;
use Illuminate\Http\Request;
use App\Admin\Extensions\Tools\BatchApproveLoan;
use App\Admin\Extensions\Tools\BatchValidateGrade;
use DB;
use PDF;
use App\Libraries\CurrencyFormat;
use Carbon\Carbon;

class LoanController extends Controller
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
            $content->header('Loans');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Loans', 'url' => '/loans']
            );
            if (isset($_GET['status_id'])) {
                $status = $_GET['status_id'][0];
                $isVerified = 1;
                if (isset($_GET['Verified'])) {
                    $isVerified = $_GET['Verified'];
                }

                if (isset($_GET['report_'])) {

                    $loans = Loan::whereHas('user', function ($query) use ($isVerified) {
                                        $query->where('verified', '=', $isVerified );
                                    })
                                    ->where('status_id', $status);

                    if (isset($_GET['Company'])) {
                        $filter = $_GET['Company'];
                        if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('company_id', '=', $filter);
                            });
                    }

                    if (isset($_GET['user_id'])) {
                        $filter = $_GET['user_id'];
                        if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('user_id', '=', $filter);
                            });
                    }

                    if (isset($_GET['Peminjam'])) {
                        $filter = $_GET['Peminjam'];
                        if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('id', '=', $filter);
                            });
                    }

                    if (isset($_GET['Region'])) {
                        $filter = $_GET['Region'];
                        if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->whereIn('home_state', function($query){
                                    $query->select('name')->from('provinces')->where('region_id', $filter);
                                });
                            });
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

                    if (isset($_GET['accepted_at'])) {
                        $filter = $_GET['accepted_at'];
                        $loans = $this->loanBeetwen($loans, 'accepted_at', $filter);
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
                                      <span class="info-box-text">Total Peminjam</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $formatAmount = '<div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text">Total Pinjaman</span>
                                      <span class="info-box-number">%s</span>
                                    </div>
                                  </div>';

                    $contentUser   = sprintf($formatUser, count($loans->get()));
                    $contentAmount = sprintf($formatAmount, CurrencyFormat::rupiah(round($loans->sum('amount_requested')), true));

                    $content->row(function(Row $row) use ($contentUser, $contentAmount) {
                        $row->column(6, $contentUser);
                        $row->column(6, $contentAmount);
                    });
                    
                }
            }
            

            $content->body($this->grid());
        });
    }

    private function loanBeetwen($loans, $column, $filter)
    {
        if (!empty($filter)) {
            $start = $filter['start']; 
            $end   = $filter['end']; 
            if ($start != null) {
                $loans->where($column, '>=', $start);
            }

            if ($end != null) {
                $loans->where($column, '<=', $end);
            }

            return $loans;
        }

        return $loans;
    }

    /**
     * Show interface.
     *
     * @param $id
     * @return Content
     */
    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Loans');
            $content->description('Show');
            $content->breadcrumb(
             ['text' => 'Loans', 'url' => '/loans'],
             ['text' => 'Show', 'url' => '/loans/show']
            );
            $content->body($this->form()->edit($id));
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

            $content->header('Loans');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Loans', 'url' => '/loans'],
             ['text' => 'Edit', 'url' => '/loans/edit']
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

            $content->header('Loans');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Loans', 'url' => '/loans'],
             ['text' => 'Create', 'url' => '/loans/create']
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
        return Admin::grid(Loan::class, function (Grid $grid) {

            if (isset($_GET['is_verified'])) {
                $isVerified = $_GET['Verified'];
                $grid->model()->whereHas('user', function ($query) use ($isVerified) {
                    $query->where('verified', '=',$isVerified);
                }); 
            }

            if(Admin::user()->isRole('auditor')) {
                $grid->disableCreateButton();
                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableExport();
            }
            if (isset($_GET['report_'])) {
                $grid->disableActions();
            }

            $grid->model()->orderBy('id', 'asc');

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
            // $grid->tools(function ($tools) {
            //     $params = '';
            //     if (isset($_SERVER['QUERY_STRING'])) {
            //         $params = $_SERVER['QUERY_STRING'];
            //     }
            //     $tools->append('<a target="_blank" href="loans/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            // });
            $grid->code('Loan ID')->sortable();
            $grid->column('user_id','Company')->sortable()->display(function ($id) {
                $user = User::find($id);
                return $user->company ? $user->company->name : '-';
            });
            $grid->user()->id('Peminjam')->sortable()->display(function ($id) {
                $user = User::find($id);
                return $user ? '<a href="'.url('admin/users/'.$user->id).'">'.$user->name.'</a>' : '';
            });
            $grid->amount_requested('Pokok Pinjaman')->setAttributes(['align' => 'right', 'style' => 'padding-right: 4em;    width: 10%;'])->sortable()->display(function ($amount) {
                return CurrencyFormat::rupiah(round($amount));
            });
            $grid->tenor()->month('Tenor');
            $grid->grade()->rank('Grade');
            $grid->status()->name('Status')->display(function ($status) {
                switch ($status) {
                    case 'Pending':
                        $user_id = $this->user_id;
                        if($user = User::find($user_id)) {
                            if($user->verified == 1) {
                                return 'Verified';
                            } else {
                                return 'Unverified';
                            }
                        } else {
                            return 'Pending';
                        }
                        break;
                    default:
                        return $status;
                        break;
                }
            });

            $grid->accepted_at()->sortable();
            $grid->created_at()->sortable();
            $grid->exporter(new ExcelExpoter());
            
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('company_id', '=', "{$this->input}");
                    });
                }, 'Perusahaan')->select(Company::get()->pluck('name','id'));
                $filter->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('id', '=', "{$this->input}");
                    });
                }, 'Peminjam')->select(User::get()->pluck('name','id'));
                $filter->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->whereIn('home_state', function($query){
                            $query->select('name')->from('provinces')->where('region_id', "{$this->input}");
                        });
                    });
                }, 'Region')->select(['1' => 'Barat', '2' => 'Tengah', '3' => 'Timur']);
                $filter->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('type', '=', "{$this->input}");
                    });
                }, 'Type')->select(['orang' => 'Orang', 'badan' => 'Badan']);
                $filter->between('amount_requested','Pokok Pinjaman');
                $filter->equal('loan_tenor_id','Tenor')->select(LoanTenor::get()->pluck('month','id'));
                $filter->between('created_at','Tanggal Pengajuan')->date();
                $filter->between('accepted_at','Tanggal Accepted')->date();
                $filter->between('provision_rate','Platform Rate');
                $filter->between('interest_rate');
                $filter->between('invest_rate');
                $filter->between('amount_total', 'Total Pinjaman');
                $filter->where(function ($query) {
                    $query->whereHas('grade', function ($query) {
                        $query->where('rank', 'like', "%{$this->input}%");
                    });
                }, 'Grade');
                $filter->in('status_id', 'Status')->multipleSelect(Status::get()->pluck('name','id'));
                $filter->where(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('verified', '=', "{$this->input}");
                    });
                }, 'Verified')->select(['1' => 'Verified', '0' => 'Unverified']);
                $filter->equal('user_id', 'ID Peminjam');
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
        return Admin::form(Loan::class, function (Form $form) {

            // Kalau edit data
            if($loan = Loan::find(request()->route('loan'))) {

                $status = $loan->status->name;
                switch ($status) {
                    case 'Pending':
                        if($loan->user->verified == 1) {
                            $display_status = 'Verified';
                        } else {
                            $display_status = 'Unverified';
                        }
                        break;
                    default:
                        $display_status = $status;
                        break;
                }
                // dd($display_status);
                $form->html('<div class="alert alert-info"><h4><i class="icon fa fa-info-circle"></i> Status Pinjaman: '.strtoupper($display_status).'</h4></div>');

                // If user has role
                if( Admin::user()->can('loan.verifies') ) {

                    // If status not yet accepted
                    if(($loan->status_id == 1) || ($loan->status_id == 2)) {
                        $form->tools(function (Form\Tools $tools) {

                            $loan = Loan::find(request()->route('loan'));

                            if($loan->status_id == 1) {

                                if(Admin::user()->can('loan.approves')) {
                                    $tools->add('<a class="btn btn-sm btn-success" style="margin-right:10px;" href="'.url('admin/loans/updateStatus/'.request()->route('loan')).'/2"><i class="fa fa-check"></i>&nbsp;&nbsp;Approve Loan</a>');
                                }
                                if(Admin::user()->can('loan.declines')) {
                                    $tools->add('<a class="btn btn-sm btn-danger" style="margin-right:10px;" href="'.url('admin/loans/updateStatus/'.request()->route('loan')).'/9"><i class="fa fa-times"></i>&nbsp;&nbsp;Reject Loan</a>');
                                }
                            }
                            
                            /*$tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/loans/validateGrade/'.request()->route('loan')).'"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Validate Grade</a>');
                            $tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/loans/calculateRates/'.request()->route('loan')).'"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Calculate Rates</a>');*/

                            
                            if(!$loan->installments()->count()) {
                                /*$tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/loans/generateInstallments/'.request()->route('loan')).'"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Generate Installments</a>');*/
                            } else {
                                $tools->add('<a class="btn btn-sm btn-danger" style="margin-right:10px;" href="'.url('admin/loans/deleteInstallments/'.request()->route('loan')).'"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete Installments</a>');
                            }

                        });
                    }

                    $form->tools(function (Form\Tools $tools) {
                        
                        // Disable back btn.
                        $tools->disableListButton();

                        $loan = Loan::find(request()->route('loan'));
                        if($loan->installments()->count() > 0) {
                            $data = array(
                                'installmentable_type' => 'App\Loan',
                                'installmentable_id' => request()->route('loan')
                            );
                            $query_url = http_build_query($data) . "\n";
                            $tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/installments?&'.$query_url).'&direct=1" target="_blank"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Installments</a>');
                        }else{
                            
                            $tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/loans/generateInstallments/'.request()->route('loan')).'"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Generate Installments</a>');
                        }


                            $tools->add('<a class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/loans/validateGrade/'.request()->route('loan')).'/true" target="_blank"><i class="fa fa-sliders"></i>&nbsp;&nbsp;Validate Grade Checklist</a>');

                        if($loan->status_id !== 1) {
                            if(Admin::user()->isRole('super_administrator')) {
                                $tools->add('<a class="btn btn-sm btn-default" style="margin-right:10px;" href="'.url('admin/loans/updateStatus/'.request()->route('loan')).'/1"><i class="fa fa-pause"></i>&nbsp;&nbsp;Set Status Pending</a>');
                            }
                        }
                        if(Admin::user()->isRole('super_administrator')) {
                            $tools->add('<a class="btn btn-sm btn-danger" style="margin-right:10px;" href="'.url('admin/loans/updateStatus/'.request()->route('loan')).'/8"><i class="fa fa-remove"></i>&nbsp;&nbsp;Cancel</a>');
                        }
                    });

                }

                $form->display('user.name','Peminjam');
                $form->display('grade.rank', 'Grade');

                // If status not yet accepted
                if(($loan->status_id == 1) || ($loan->status_id == 2)) {

                    if( Admin::user()->can('loan.verifies') ) {

                        $form->text('amount_requested', 'Pokok Pinjaman')->attribute(['value' => number_format($loan->amount_requested,2,',','.'), 'class' => 'form-control mask_money']);
                        $form->textarea('description', 'Tujuan Pinjaman');
                        $form->select('loan_tenor_id', 'Tenor')->options(LoanTenor::get()->pluck('month','id'));
                        $form->date('date_expired', 'Tanggal Batas Pendanaan');
                        $form->text('provision_rate','Platform Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                        $form->text('interest_rate','Borrower Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                        $form->text('invest_rate','Lender Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                        $form->text('provision_fee','Platform Fee (Calculated)')->help('Pokok Pinjaman * Platform Rate')->attribute(['value' => number_format($loan->provision_fee,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('interest_fee', 'Interest Fee (Calculated)')->help('Pokok Pinjaman * Interest Rate')->attribute(['value' => number_format($loan->interest_fee,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_borrowed','Amount to be Transferred (Calculated)')->help('Pokok Pinjaman - Platform Fee')->attribute(['value' => number_format($loan->amount_borrowed,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_total','Amount Total (Calculated)')->help('Pokok Pinjaman + Interest Fee')->attribute(['value' => number_format($loan->amount_total,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_funded','Amount Funded (Calculated)')->help('Jumlah total pendanaan terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_funded,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_paid','Amount Paid (Calculated)')->help('Jumlah total pembayaran terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_paid,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->textarea('notes','Admin Notes');
                    } else {

                        $form->text('amount_requested', 'Pokok Pinjaman')->attribute(['value' => number_format($loan->amount_requested,2,',','.'), 'readonly' => 'true']);
                        $form->display('description', 'Tujuan Pinjaman');
                        $form->display('tenor.month', 'Tenor');
                        $form->display('date_expired', 'Tanggal Batas Pendanaan');
                        $form->display('provision_rate', 'Platform Rate (%)');
                        $form->display('interest_rate', 'Borrower Rate (%)');
                        $form->display('invest_rate', 'Lender Rate (%)');
                        $form->text('provision_fee','Platform Fee (Calculated)')->help('Pokok Pinjaman * Platform Rate')->attribute(['value' => number_format($loan->provision_fee,2,',','.'), 'readonly' => 'true']);
                        $form->text('interest_fee', 'Borrower Interest Fee (Calculated)')->help('Pokok Pinjaman * Interest Rate')->attribute(['value' => number_format($loan->interest_fee,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_borrowed','Amount to be Transferred (Calculated)')->help('Pokok Pinjaman - Platform Fee')->attribute(['value' => number_format($loan->amount_borrowed,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_total','Amount Total (Calculated)')->help('Pokok Pinjaman + Interest Fee')->attribute(['value' => number_format($loan->amount_total,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_funded','Amount Funded (Calculated)')->help('Jumlah total pendanaan terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_funded,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_paid','Amount Paid (Calculated)')->help('Jumlah total pembayaran terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_paid,2,',','.'), 'readonly' => 'true']);
                        $form->textarea('notes','Admin Notes')->attribute(['readonly' => 'true']);
                    }
                    
                } else {

                    if( Admin::user()->can('loan.verifies') ) {

                        $form->text('amount_requested', 'Pokok Pinjaman')->attribute(['value' => number_format($loan->amount_requested,2,',','.'), 'class' => 'form-control mask_money']);
                        $form->textarea('description', 'Tujuan Pinjaman');
                        $form->select('loan_tenor_id', 'Tenor')->options(LoanTenor::get()->pluck('month','id'));
                        $form->date('date_expired', 'Tanggal Batas Pendanaan');
                        $form->text('provision_rate','Platform Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                        $form->text('interest_rate','Borrower Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                        $form->text('invest_rate','Lender Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                        $form->text('provision_fee','Platform Fee (Calculated)')->help('Pokok Pinjaman * Platform Rate')->attribute(['value' => number_format($loan->provision_fee,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('interest_fee', 'Interest Fee (Calculated)')->help('Pokok Pinjaman * Interest Rate')->attribute(['value' => number_format($loan->interest_fee,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_borrowed','Amount to be Transferred (Calculated)')->help('Pokok Pinjaman - Platform Fee')->attribute(['value' => number_format($loan->amount_borrowed,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_total','Amount Total (Calculated)')->help('Pokok Pinjaman + Interest Fee')->attribute(['value' => number_format($loan->amount_total,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_funded','Amount Funded (Calculated)')->help('Jumlah total pendanaan terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_funded,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->text('amount_paid','Amount Paid (Calculated)')->help('Jumlah total pembayaran terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_paid,2,',','.'), 'class' => 'form-control mask_money', 'readonly' => 'true']);
                        $form->textarea('notes','Admin Notes');
                    } else {

                        $form->text('amount_requested', 'Pokok Pinjaman')->attribute(['value' => number_format($loan->amount_requested,2,',','.'), 'readonly' => 'true']);
                        $form->display('description', 'Tujuan Pinjaman');
                        $form->display('tenor.month', 'Tenor');
                        $form->display('date_expired', 'Tanggal Batas Pendanaan');
                        $form->display('provision_rate', 'Platform Rate (%)');
                        $form->display('interest_rate', 'Borrower Rate (%)');
                        $form->display('invest_rate', 'Lender Rate (%)');
                        $form->text('provision_fee','Platform Fee (Calculated)')->help('Pokok Pinjaman * Platform Rate')->attribute(['value' => number_format($loan->provision_fee,2,',','.'), 'readonly' => 'true']);
                        $form->text('interest_fee', 'Borrower Interest Fee (Calculated)')->help('Pokok Pinjaman * Interest Rate')->attribute(['value' => number_format($loan->interest_fee,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_borrowed','Amount to be Transferred (Calculated)')->help('Pokok Pinjaman - Platform Fee')->attribute(['value' => number_format($loan->amount_borrowed,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_total','Amount Total (Calculated)')->help('Pokok Pinjaman + Interest Fee')->attribute(['value' => number_format($loan->amount_total,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_funded','Amount Funded (Calculated)')->help('Jumlah total pendanaan terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_funded,2,',','.'), 'readonly' => 'true']);
                        $form->text('amount_paid','Amount Paid (Calculated)')->help('Jumlah total pembayaran terhadap pinjaman ini')->attribute(['value' => number_format($loan->amount_paid,2,',','.'), 'readonly' => 'true']);
                        $form->textarea('notes','Admin Notes')->attribute(['readonly' => 'true']);
                    }
                    
                }                

            } else {

                // Create new resource
                $form->select('user_id','User')->options(User::get()->pluck('name','id'));
                $form->text('amount_requested', 'Amount Requested')->attribute(['class' => 'form-control mask_money']);
                $form->date('date_expired', 'Date Expired');
                $form->textarea('description', 'Description');
                $form->select('loan_tenor_id', 'Tenor')->options(LoanTenor::get()->pluck('month','id'));
                $form->text('provision_rate','Provision Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                $form->text('interest_rate','Borrower Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                $form->text('invest_rate','Lender Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
                $form->textarea('notes','Admin Notes');
            }

            $form->disableReset();

            $form->saving(function (Form $form) {
                $form->amount_requested = str_replace(',','.',str_replace('.','',$form->amount_requested));
                $form->provision_fee = str_replace(',','.',str_replace('.','',$form->provision_fee));
                $form->interest_fee = str_replace(',','.',str_replace('.','',$form->interest_fee));
                $form->amount_borrowed = str_replace(',','.',str_replace('.','',$form->amount_borrowed));
                $form->amount_total = str_replace(',','.',str_replace('.','',$form->amount_total));
                $form->amount_funded = str_replace(',','.',str_replace('.','',$form->amount_funded));
                $form->amount_paid = str_replace(',','.',str_replace('.','',$form->amount_paid));
            });

            $form->saved(function (Form $form) {
                Loan::findOrFail(request()->route('loan'))->calculateRates();
                admin_toastr('Update succeeded! Rates calculated!');
                return redirect('/admin/loans/'.request()->route('loan').'/edit');
            });
            
        });
    }

    public function validateGrade($loan_id, $display = false)
    {
        if($display == true) {
            return Admin::content(function (Content $content) use ($loan_id) {
                $content->header('Loan');
                $loan = Loan::findOrFail($loan_id);
                if($loan->user->type == 'orang') {
                    $content->row(view('admin.loans.validate_grade_checklist',compact('loan')));
                } elseif($loan->user->type == 'badan') {
                    $content->row(view('admin.loans.validate_grade_checklist_company',compact('loan')));
                }
            });
        } else {
            $loan = Loan::findOrFail($loan_id);
            if($grade = $loan->validateGrade()) {
                if(!$loan->provision_rate) $loan->provision_rate = $grade->platform_rate;
                if(!$loan->interest_rate) $loan->interest_rate = $grade->borrower_rate;
                if(!$loan->invest_rate) $loan->invest_rate = $grade->lender_rate;
                $loan->loan_grade_id = $grade->id;
                $loan->save();
                admin_toastr('Grade validated');
            } else {
                admin_toastr('No available grade is valid for this loan, please refer to checklist for more details','error');
            }
            return redirect('admin/loans/'.$loan->id.'/edit');
        }
    }

    public function updateStatus($loan_id, $status)
    {
        $next = true;
        DB::beginTransaction();
        $loan = Loan::findOrFail($loan_id);
        // Check if current status is pending
        if($loan->status_id == 1) {
            if($status == 2) {

                if (!$loan->calculateRates()) {
                    DB::rollback();
                    admin_toastr('Rate cant\'t create','error');
                    activity()
                            ->performedOn($loan)
                            ->causedBy(User::find($loan->user_id))
                            ->log('Rate cant\'t create');
                    return redirect('/admin/loans/'.request()->route('loan').'/edit');
                }
                if($grade = $loan->validateGrade()) {
                    if(!$loan->provision_rate) $loan->provision_rate = 0;
                    if(!$loan->interest_rate) $loan->interest_rate = 0;
                    if(!$loan->invest_rate) $loan->invest_rate = 0;
                    $loan->loan_grade_id = $grade->id;
                    $loan->save();
                    activity()
                            ->performedOn($loan)
                            ->causedBy(User::find($loan->user_id))
                            ->log('Grade Validated');
                } else {
                    DB::rollback();
                    admin_toastr('Loan cannot be approved if rates are still empty, please check again','error');
                    activity()
                            ->performedOn($loan)
                            ->causedBy(User::find($loan->user_id))
                            ->log('Loan cannot be approved if rates are still empty, please check again');
                    return redirect('/admin/loans/'.request()->route('loan').'/edit');
                }

                if (!$loan->generateInstallments()) {
                    DB::rollback();
                    admin_toastr('Installments cant\'t create','error');
                    activity()
                            ->performedOn($loan)
                            ->causedBy(User::find($loan->user_id))
                            ->log('Installments cant\'t create');
                    return redirect('/admin/loans/'.request()->route('loan').'/edit');
                }

                // Approves
                // check if rates are set
                if( ($loan->provision_rate === NULL) || ($loan->interest_rate === NULL) || ($loan->invest_rate === NULL) ) {
                    DB::rollback();
                    admin_toastr('Loan cannot be approved if rates are still empty, please check again','error');
                    activity()
                            ->performedOn($loan)
                            ->causedBy(User::find($loan->user_id))
                            ->log('Loan cannot be approved if rates are still empty, please check again');
                    return redirect('/admin/loans/'.request()->route('loan').'/edit');
                }
                // check if grade are set
                if( !$loan->grade ) {
                    DB::rollback();
                    admin_toastr('Loan cannot be approved if grade is not validated, please check again','error');
                    activity()
                            ->performedOn($loan)
                            ->causedBy(User::find($loan->user_id))
                            ->log('Loan cannot be approved if grade is not validated, please check again');
                    return redirect('/admin/loans/'.request()->route('loan').'/edit');
                }
                //$loan->generateInstallments();
                $loan->date_expired = Carbon::now()->addDays(7);
                $loan->calculateRates();
                event(new LoanApproved($loan));
            } elseif($status == 9) {
                // Declines
                event(new LoanRejected($loan));
            }
            $loan->status_id = $status;
            $loan->save();
            activity()
                    ->withProperties(['amount_borrowed' => $loan['amount_borrowed']])
                    ->performedOn($loan)
                    ->causedBy(User::find($loan->user_id))
                    ->log('Loan Approved');
            admin_toastr('Status updated');
        } else {
            $loan->status_id = $status;
            $loan->save();
            activity()
                    ->withProperties(['amount_borrowed' => $loan['amount_borrowed']])
                    ->performedOn($loan)
                    ->causedBy(User::find($loan->user_id))
                    ->log('Loan Canceled');
            admin_toastr('Status already updated','info');
        }

        DB::commit();
        return redirect('admin/loans/'.$loan->id.'/edit');
    }

    public function calculateRates($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        if( $loan->calculateRates() ) { admin_toastr('Rates calculated'); }
        return redirect('admin/loans/'.$loan->id.'/edit');
    }

    public function generateInstallments($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        if($loan->generateInstallments()) { admin_toastr('Loan installments created'); }
        else { admin_toastr('Loan installments already created','error'); }
        return redirect('admin/loans/'.$loan->id.'/edit');
    }

    public function deleteInstallments($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        if($loan->deleteInstallments()) { admin_toastr('Loan installments deleted'); }
        else { admin_toastr('No installments available to delete','error'); }
        return redirect('admin/loans/'.$loan->id.'/edit');
    }

    public function mass_approves(Request $request)
    {
        foreach (Loan::find($request->get('ids')) as $loan) {
            $this->updateStatus($loan->id, $request->get('action'));
        }
    }

    public function mass_validate_grades(Request $request)
    {
        foreach (Loan::find($request->get('ids')) as $loan) {
            $this->validateGrade($loan->id);
        }
    }
    public function exportpdf(Request $request)
    {
        /*$urlparams = $request->all();
        // dd($urlparams);
        $loans = DB::table('loans')->join('users', 'users.id', '=', 'loans.user_id')->join('loan_tenors', 'loan_tenors.id', '=', 'loan_tenor_id')->join('statuses', 'statuses.id', '=', 'loans.status_id')->where('users.verified', '=', 1);

        if ($request->input('status_id') != null) {
            $loans = $loans->whereIn('status_id', $request->input('status_id'));
        }
        if ($request->input('Company') != null) {
            $loans = $loans->where('users.company_id', $request->input('Company'));
        }
        if ($request->input('user_id') != null) {
            $loans = $loans->where('user_id', $request->input('user_id'));
        } 
        if ($request->input('loan_tenor_id') != null) {
            $loans = $loans->where('loans.loan_tenor_id', $request->input('loan_tenor_id'));
        } 
        if ($request->input('Grade') != null) {
            $loans = $loans->join('loan_grades', 'loan_grade_id', '=', 'loan_grades.id')
                            ->where('rank', 'LIKE', $request->input('Grade'));
                            // ->select('loan_grades.rank');
        }         
        if ($request->input('amount_requested.start') != null || ($request->input('amount_requested.end') != null))    {
            if ($request->input('amount_requested.start') != null && $request->input('amount_requested.end') != null) {
                // return '1';
                $loans = $loans->whereBetween('amount_requested', [$request->input('amount_requested.start'), $request->input('amount_requested.end')]);
            }elseif($request->input('amount_requested.start') != null){
                // return '2';
                $loans = $loans->where('amount_requested', '>=', $request->input('amount_requested.start'));
            }else{
                // return '3';
                $loans = $loans->where('amount_requested', '<=', $request->input('amount_requested.end'));
            }
        }         
        if ($request->input('date_expired.start') != null || ($request->input('date_expired.end') != null))    {
            if ($request->input('date_expired.start') != null && $request->input('date_expired.end') != null) {
                // return '1';
                $loans = $loans->whereBetween('date_expired', [$request->input('date_expired.start'), $request->input('date_expired.end')]);
            }elseif($request->input('date_expired.start') != null){
                // return '2';
                $loans = $loans->where('date_expired', '>=', $request->input('date_expired.start'));
            }else{
                // return '3';
                $loans = $loans->where('date_expired', '<=', $request->input('date_expired.end'));
            }
        }        
        if ($request->input('provision_rate.start') != null || ($request->input('provision_rate.end') != null))    {
            if ($request->input('provision_rate.start') != null && $request->input('provision_rate.end') != null) {
                // return '1';
                $loans = $loans->whereBetween('provision_rate', [$request->input('provision_rate.start'), $request->input('provision_rate.end')]);
            }elseif($request->input('provision_rate.start') != null){
                // return '2';
                $loans = $loans->where('provision_rate', '>=', $request->input('provision_rate.start'));
            }else{
                // return '3';
                $loans = $loans->where('provision_rate', '<=', $request->input('provision_rate.end'));
            }
        }      
        if ($request->input('interest_rate.start') != null || ($request->input('interest_rate.end') != null))    {
            if ($request->input('interest_rate.start') != null && $request->input('interest_rate.end') != null) {
                // return '1';
                $loans = $loans->whereBetween('interest_rate', [$request->input('interest_rate.start'), $request->input('interest_rate.end')]);
            }elseif($request->input('interest_rate.start') != null){
                // return '2';
                $loans = $loans->where('interest_rate', '>=', $request->input('interest_rate.start'));
            }else{
                // return '3';
                $loans = $loans->where('interest_rate', '<=', $request->input('interest_rate.end'));
            }
        }
        if ($request->input('invest_rate.start') != null || ($request->input('invest_rate.end') != null))    {
            if ($request->input('invest_rate.start') != null && $request->input('invest_rate.end') != null) {
                // return '1';
                $loans = $loans->whereBetween('invest_rate', [$request->input('invest_rate.start'), $request->input('invest_rate.end')]);
            }elseif($request->input('invest_rate.start') != null){
                // return '2';
                $loans = $loans->where('invest_rate', '>=', $request->input('invest_rate.start'));
            }else{
                // return '3';
                $loans = $loans->where('invest_rate', '<=', $request->input('invest_rate.end'));
            }
        }
        if ($request->input('amount_total.start') != null || ($request->input('amount_total.end') != null))    {
            if ($request->input('amount_total.start') != null && $request->input('amount_total.end') != null) {
                // return '1';
                $loans = $loans->whereBetween('amount_total', [$request->input('amount_total.start'), $request->input('amount_total.end')]);
            }elseif($request->input('amount_total.start') != null){
                // return '2';
                $loans = $loans->where('amount_total', '>=', $request->input('amount_total.start'));
            }else{
                // return '3';
                $loans = $loans->where('amount_total', '<=', $request->input('amount_total.end'));
            }
        }
        $loans = $loans
                    ->select('loans.loan_grade_id', 'users.name as name', 'amount_requested', 'loan_tenors.month', 'statuses.name as status', 'loans.created_at');
        // if ($request->input('Grade') != null) {
        //     $loans = $loans->select('loan_grades.rank', 'users.name as name', 'amount_requested', 'loan_tenors.month', 'statuses.name as status', 'loans.created_at');
        //                     // ->select('loan_grades.rank');
        // }
        $loans = $loans->get();
        foreach ($loans as $loan) {
            if (!empty($loan->loan_grade_id)) {
                $grades = LoanGrade::where('id', $loan->loan_grade_id)->first();
                $loan->rank = !empty($grades) ? $grades->rank : '';
            }else {
                $loan->rank = '';
            }
        }*/

        $isVerified = 1;
        if (isset($_GET['status_id'])) {
            $status = $_GET['status_id'][0];
            if (isset($_GET['Verified'])) {
                $isVerified = $_GET['Verified'];
            }
        }
        $loans = Loan::whereHas('user', function ($query) use ($isVerified) {
            $query->where('verified', '=', $isVerified );
        })
        ->where('status_id', $status);

                    if (isset($_GET['Company'])) {
                        $filter = $_GET['Company'];
                        if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('company_id', '=', $filter);
                            });
                    }

                    if (isset($_GET['user_id'])) {
                        $filter = $_GET['user_id'];
                        if (!empty($filter))
                            $loans->whereHas('user', function ($query) use ($filter) {
                                $query->where('user_id', '=', $filter);
                            });
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

                    if (isset($_GET['accepted_at'])) {
                        $filter = $_GET['accepted_at'];
                        $loans = $this->loanBeetwen($loans, 'accepted_at', $filter);
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
        //return response()->json($loans);
        // dd($loans);
        view()->share('loans',$loans->get());
        $pdf = PDF::loadView('admin.loans.export.pdf');
        return $pdf->stream('loans.pdf');
        // return $users;
        // return view('admin.installments.export.pdf', compact('users'));

    }
}

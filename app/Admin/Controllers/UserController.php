<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Form as WidgetsForm;
use Encore\Admin\Widgets\Box;
use App\Http\Controllers\Controller;
use App\Admin\Extensions\ExcelExporterUser;
use App\User;
use App\Http\Requests;
use App\Company;
use App\Bank;
use App\BankAccount;
use App\Events\UserVerified;
use App\Loan;
use App\Provinces;
use App\District;
use PDF;
use DB;

class UserController extends Controller
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
            $content->header('Users');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Users', 'url' => '/users']
            );
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

            $content->header('Users');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Users', 'url' => '/users'],
             ['text' => 'Edit', 'url' => '/users/edit']
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

            $content->header('Users');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Users', 'url' => '/users'],
             ['text' => 'Create', 'url' => '/users/create']
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

            if(Admin::user()->isRole('auditor')) {
                $grid->disableCreateButton();
                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableExport();
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
            // $grid->tools(function ($tools) {
            //     $params = '';
            //     if (isset($_SERVER['QUERY_STRING'])) {
            //         $params = $_SERVER['QUERY_STRING'];
            //     }
            //     $tools->append('<a target="_blank" href="users/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            // });        

            $grid->code('User ID')->sortable();
            $grid->column('company_id','Company')->sortable()->display(function ($company_id) {
                $company = \App\Company::find($company_id);
                return $company ? $company->name : '-';
            });
            $grid->name()->sortable();
            $grid->email()->sortable();
            $grid->column('type','Type')->sortable();
            $grid->column('roles')->display(function () {
                $user = \App\User::find($this->id);
                return $user->roles ? $user->roles->pluck('name')->first() : '-';
            });
            $grid->active()->sortable()->display(function ($value) {
                return $value == true ? 'Active' : 'Inactive';
            });
            $grid->verified()->sortable()->display(function ($value) {
                return $value == true ? 'Verified' : 'Unverified';
            });
            $grid->created_at()->sortable();
            $grid->exporter(new ExcelExporterUser());  

            if (isset($_GET['report_'])) {
                $grid->disableActions();
            }          

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
                
                $actions->append('<a target="_blank" href="/admin/activitylogs?User='.$actions->getKey().'"><i class="fa fa-history"></i></a></i></a>');
                $users = User::find($actions->getKey());
                if ($users) {
                    $users_role = $users->getRoleNames();
                }
                // dd($users_role[0]);
                if (!empty($users_role[0])) {
                    if ($users_role[0] == 'borrower') {
                        $actions->append('<a style="margin-left: 7%;" target="_blank" href="/admin/installments?User='.$actions->getKey().'&installmentable_type=App%5CLoan"><i class="fa fa-info"></i></a></i></a>');
                    }elseif($users_role[0] == 'lender'){
                       $actions->append('<a style="margin-left: 7%;" target="_blank" href="/admin/installments?User='.$actions->getKey().'&installmentable_type=App%5CInvestment"><i class="fa fa-info"></i></a></i></a>');                
                    }else{

                    }
                }
            });

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->where(function ($query) {
                    $query->whereIn('home_state', function($query){
                        $query->select('name')
                            ->from('provinces')
                            ->where('region_id', "{$this->input}");
                    });
                }, 'Region')->select(['1' => 'Barat', '2' => 'Tengah', '3' => 'Timur']);
                $filter->equal('type')->select(['orang' => 'Orang', 'badan' => 'Badan']);
                $filter->like('name');
                $filter->like('email');
                $filter->where(function ($query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', '=', "{$this->input}");
                    });
                }, 'Roles')->select(['lender' => 'lender', 'borrower' => 'borrower']);
                $filter->equal('bi_checking')->select([ '1' => 'Collect 1', '2' => 'Collect 2', '3' => 'Collect 3', '4' => 'Collect 4', '5' => 'Collect 5']);
                $filter->equal('verified')->radio([0 => 'Not Verified', 1 => 'Verified']);
                $filter->equal('active')->radio([0 => 'Inactive', 1 => 'Active']);
                $filter->like('home_address', 'Address');
                $filter->like('home_state', 'State');
                $filter->like('home_postal_code', 'Postal Code');
                $filter->equal('home_ownership', 'Status Tempat Tinggal')->select([ 'sendiri' => 'Sendiri', 'sendiri' => 'Sendiri', 'keluarga' => 'Keluarga', 'sewa' => 'Sewa', 'lainnya' => 'Lain-lain']);
                $filter->like('home_phone');
                $filter->like('mobile_phone');
                $filter->like('id_no', 'No KTP');
                $filter->like('npwp_no', 'No NPWP');
                $filter->like('pob', 'Place of birth');
                $filter->between('dob', 'Date or birth')->date();
                $filter->equal('company_id', 'Company')->select(Company::get()->pluck('name','id'));
                $filter->between('employment_salary', 'Monthy Salary');
                $filter->like('employment_position', 'Position');
                $filter->between('employment_duration', 'Duration (months)');
                $filter->equal('employment_status', 'Status')->select([ 'kontrak' => 'Kontrak', 'permanen' => 'Permanen']);
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
        return Admin::form(User::class, function (Form $form) {

            // If status not yet verified
            if(Admin::user()->can('user.verifies')) {
                if($user = User::find(request()->route('user'))) {
                    $form->tools(function (Form\Tools $tools) use ($user) {
                        // $usersid = User::find($this->id);
                        $tools->add('<a target="_blank" class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/loans?user_id='.request()->route('user')).'"><i class="fa fa-eye"></i>&nbsp;&nbsp;View User Loans</a>');
                        $tools->add('<a target="_blank" class="btn btn-sm btn-primary" style="margin-right:10px;" href="'.url('admin/investments?user_id='.request()->route('user')).'"><i class="fa fa-eye"></i>&nbsp;&nbsp;View User Fundings</a>');
                        if($user->verified !== 1) {
                            $tools->add('<a class="btn btn-sm btn-success" style="margin-right:10px;" href="'.url('admin/users/verify/'.request()->route('user')).'"><i class="fa fa-check"></i>&nbsp;&nbsp;Verify User</a>');
                        }
                    });
                }
            }

            // If user has role
            if(!Admin::user()->can('Update users')) {

                $form->tab('Account Information', function ($form) {

                    $form->display('type', 'User Type');
                    $form->image('avatar', 'Picture');
                    $form->display('email','Email');
                    $form->display('created_at');

                })->tab('Personal Details', function ($form) {

                    $form->display('name', 'Full Name');
                    $form->display('pob', 'Place of Birth');
                    $form->display('dob', 'Date of Birth');
                    $form->display('gender', 'Gender');
                    $form->display('education', 'Education');
                    $form->display('religion', 'Religion');
                    $form->display('home_address', 'Address');
                    $form->display('home_state', 'State');
                    $form->display('home_city', 'City');
                    $form->display('home_postal_code', 'Postal Code');
                    $form->display('home_phone', 'Home Phone');
                    $form->display('mobile_phone', 'Mobile Phone');
                    $form->display('home_ownership', 'Status Tempat Tinggal');
                    $form->image('home_doc','PBB');
                    $form->display('id_no', 'No. KTP');
                    $form->image('id_doc','KTP');
                    $form->display('npwp_no', 'No. NPWP');
                    $form->image('npwp_doc', 'No. NPWP');

                })->tab('Employment Details', function ($form) {

                    $form->display('employment_type', 'Employment Type');
                    $form->display('employment_salary', 'Monthly Salary');
                    $form->image('employment_salary_slip','Slip Gaji');
                    $form->display('employment_position', 'Position');
                    $form->display('employment_duration', 'Duration (months)');
                    $form->display('employment_status', 'Status');

                })->tab('Company Details', function ($form) {

                    $form->display('company_type', 'Company Type');
                    $form->display('company.name', 'Company Name');
                    $form->display('company_industry', 'Company Industry');
                    $form->image('financial_doc','Dokumen Keuangan');
                    $form->display('current_equity', 'Modal Disetor');
                    $form->display('current_asset', 'Total Aset Lancar');
                    $form->display('current_inventory', 'Total Inventori');
                    $form->display('current_receivable', 'Total Piutang');
                    $form->display('current_debt', 'Total Utang Lancar');
                    $form->image('collateral_doc','Dokumen Jaminan');
                    $form->display('collateral_amount','Nilai Jaminan');

                })->tab('BI Checking', function ($form) {

                    $form->select('bi_checking', 'BI Checking')->options([ '1' => 'Collect 1', '2' => 'Collect 2', '3' => 'Collect 3', '4' => 'Collect 4', '5' => 'Collect 5']);

                })->tab('Notes', function ($form) {

                    $form->textarea('notes','Admin Notes');

                });

            } else {

                $form->tab('Account Information', function ($form) {

                    $form->select('type', 'Account Type')->options([ 'orang' => 'Orang', 'badan' => 'Badan']);
                    $form->image('avatar', 'Picture');
                    $form->text('email','Email')->rules(function ($form) {
                        // if create
                        if (!$id = $form->model()->id) {
                            return 'required|unique:users';
                        } else {
                            return 'required|unique:users,email,'.$id;
                        }
                    });
                    $form->password('password', trans('admin.password'))->rules('required|confirmed|min:6')
                        ->default(function ($form) {
                            return $form->model()->password;
                        });
                    $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
                        ->default(function ($form) {
                            return $form->model()->password;
                        });

                    $states = [
                        'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
                        'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
                    ];
                    $form->switch('active', 'Active')->states($states);
                    $form->switch('verified', 'Verified')->states($states);
                    $form->display('created_at');

                });

                $user = User::find(request()->route('user'));
                if($user->type == 'orang') {

                    $form->tab('Personal Details', function ($form) {

                        $form->text('name', 'Full Name');
                        $form->text('pob', 'Place of Birth');
                        $form->date('dob', 'Date of Birth');
                        $form->select('gender', 'Gender')->options(['laki' => 'Laki-laki', 'perempuan' => 'Perempuan']);
                        $form->select('education', 'Education')->options(['sd'=>'SD','smp'=>'SMP','sma'=>'SMA','diploma'=>'Diploma','sarjana'=>'Sarjana']);
                        $form->select('religion', 'Religion')->options([ 'islam'=>'Islam','kristen_protestan' => 'Kristen Protestan','kristen_katolik'=>'Kristen Katolik','hindu'=>'Hindu','buddha'=>'Buddha','konghucu'=>'Konghucu','lainnya'=>'Lain-lain']);
                        $form->text('home_address', 'Address');
                        // $form->select('home_state', 'State')->options(Provinces::get()->pluck('name','id'));
                        // $form->select('home_city', 'City')->options(District::get()->pluck('name','id'));
                        $form->text('home_city', 'City');
                        $form->text('home_state', 'State');
                        $form->text('home_postal_code', 'Postal Code')->rules('nullable|alpha_dash|min:5');
                        $form->text('home_phone', 'Home Phone');
                        $form->text('mobile_phone', 'Mobile Phone');
                        $form->select('home_ownership', 'Status Tempat Tinggal')->options([ 'sendiri' => 'Sendiri', 'keluarga' => 'Keluarga', 'sewa' => 'Sewa', 'lainnya' => 'Lain-lain']);
                        $form->image('home_doc','PBB');
                        $form->text('id_no', 'No. KTP');
                        $form->image('id_doc','KTP');
                        $form->text('npwp_no', 'No. NPWP');
                        $form->image('npwp_doc', 'No. NPWP');

                    });

                    $form->tab('Employment Details', function ($form) {

                        $form->select('company_id', 'Company Name')->options(Company::get()->pluck('name','id'));
                        $form->select('employment_type', 'Employment Type')->options([ 'pns' => 'PNS', 'bumn' => 'BUMN','swasta'=>'Swasta','wiraswasta'=>'Wiraswasta','lainnya'=>'lain-lain']);
                        $form->text('employment_salary', 'Monthly Salary')->rules('nullable|integer');
                        $form->image('employment_salary_slip','Slip Gaji');
                        $form->text('employment_position', 'Position')->rules('nullable');
                        $form->text('employment_duration', 'Duration (months)')->rules('nullable|integer');
                        $form->select('employment_status', 'Status')->options([ 'kontrak' => 'Kontrak', 'permanen' => 'Permanen']);

                    });

                } elseif ($user->type == 'badan') {

                    $form->tab('Company Details', function ($form) {

                        $form->select('company_type', 'Company Type')->options([ 'PT' => 'PT', 'Koperasi' => 'Koperasi','Pemerintah Pusat'=>'Pemerintah Pusat','Pemerintah Daerah'=>'Pemerintah Daerah','lainnya'=>'lain-lain']);
                        $form->select('company_id', 'Company Name')->options(Company::get()->pluck('name','id'));
                        $form->text('home_address', 'Company Address');
                        // $form->select('home_state', 'State')->options(Provinces::get()->pluck('name','id'));
                        // $form->select('home_city', 'City')->options(District::get()->pluck('name','id'));
                        $form->text('home_city', 'Company City');
                        $form->text('home_state', 'Company State');
                        $form->text('home_postal_code', 'Company Postal Code')->rules('nullable|alpha_dash|min:5');
                        $form->text('home_phone', 'Company Phone');
                        $form->text('website_url', 'Company Website');
                        $industries = ['Industri Pengolahan Pangan', 'Industri Tekstil', 'Industri Barang Kulit', 'Industri Pengolahan Kayu', 'Industri Pengolahan Kertas', 'Industri Kimia Farmasi', 'Industri Pengolahan Karet', 'Industri Barang Galian bukan Logam', 'Industri Baja / Pengolahan Logam', 'Industri Peralatan', 'Industri Pertambangan', 'Industri Pariwisata', 'lainnya'];
                        $form->select('company_industry', 'Company Industry')->options($industries);
                        $form->select('home_ownership', 'Status Domisili')->options([ 'sendiri' => 'Sendiri', 'keluarga' => 'Keluarga', 'sewa' => 'Sewa', 'lainnya' => 'Lain-lain']);
                        $form->text('npwp_no', 'Company NPWP Number');
                        $form->image('npwp_doc', 'Company NPWP Image');
                        $form->image('akta_doc','Company Akta Image');
                        $form->image('home_doc','Company SIUP Image');
                        $form->image('tdp_doc','Company TDP Image');

                    });

                    $form->tab('Financial Details', function ($form) {

                        $form->image('financial_doc','Dokumen Keuangan');
                        $form->text('current_equity', 'Modal Disetor');
                        $form->text('current_asset', 'Total Aset Lancar');
                        $form->text('current_inventory', 'Total Inventori');
                        $form->text('current_receivable', 'Total Piutang');
                        $form->text('current_debt', 'Total Utang Lancar');
                        $form->text('collateral_amount','Jumlah Jaminan');
                        $form->image('collateral_doc','Dokumen Jaminan');

                    });

                    $form->tab('PIC Details', function ($form) {

                        $form->text('name', 'PIC Name');
                        $form->text('mobile_phone', 'PIC Mobile Phone');
                        $form->text('id_no', 'PIC No. KTP');
                        $form->image('id_doc','PIC KTP Image');

                    });
                    
                }

                $form->tab('BI Checking', function ($form) {

                    $form->select('bi_checking', 'BI Checking')->options([ '1' => 'Collect 1', '2' => 'Collect 2', '3' => 'Collect 3', '4' => 'Collect 4', '5' => 'Collect 5']);

                })->tab('Notes', function ($form) {

                    $form->textarea('notes','Admin Notes');

                });

                $form->ignore(['password_confirmation']);

                $form->saving(function (Form $form) {
                    if ($form->password && $form->model()->password != $form->password) {
                        $form->password = bcrypt($form->password);
                    }
                });
                
            }

            $form->saved(function (Form $form) {
                admin_toastr('Update succeeded!');
                return redirect('/admin/users/'.request()->route('user').'/edit');
            });

            $form->disableReset();
        });
    }

    public function verifyUser($user_id)
    {
        $user = User::findOrFail($user_id);
        
        if($user->type == 'orang') {
            if(!$user->bi_checking) {
                admin_toastr('Please set BI Checking value before verify user', 'error');
                return redirect('admin/users/'.$user->id.'/edit');
            }
        }

        $user->verified = 1;
        if($user->save()) {
            // Update all ungraded loans
            foreach ($user->loans()->whereNull('loan_grade_id')->get() as $loan) {
                if($grade = $loan->validateGrade()) {
                    if(!$loan->provision_rate) $loan->provision_rate = $grade->platform_rate;
                    if(!$loan->interest_rate) $loan->interest_rate = $grade->borrower_rate;
                    if(!$loan->invest_rate) $loan->invest_rate = $grade->lender_rate;
                    $loan->loan_grade_id = $grade->id;
                    $loan->save();
                    $loan->calculateRates();
                    activity()
                       ->performedOn($loan)
                       ->causedBy(Admin::user())
                       ->log('User loans grade validated');
                    admin_toastr('User loans grade validated!');
                }
            }
            event(new UserVerified($user));
            activity()
               ->performedOn($user)
               ->causedBy(Admin::user())
               ->log('Succesfull Verified');
            admin_toastr('User verified!');
        }
        
        return redirect('admin/users/'.$user->id.'/edit');
    }

    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        $users = User::with('roles');
        if ($request->input('verified') != null) {
            $users = $users->where('verified', $request->input('verified'));
        }
        if ($request->input('active') != null) {
            $users = $users->where('active', $request->input('active'));
        }
        if ($request->input('email') != null) {
            $users = $users->where('email', 'LIKE', $request->input('email'));
        }
        if ($request->input('name') != null) {
            $users = $users->where('name', 'LIKE', $request->input('name'));
        }
        if ($request->input('Roles') != null) {
            $users = $users->role($request->input('Roles'));
        }
        if ($request->input('bi_checking') != null) {
            $users = $users->where('bi_checking', 'LIKE', $request->input('bi_checking'));
        }
        if ($request->input('home_address') != null) {
            $users = $users->where('home_address', 'LIKE', $request->input('home_address'));
        }
        if ($request->input('home_state') != null) {
            $users = $users->where('home_state', 'LIKE', $request->input('home_state'));
        }
        if ($request->input('home_postal_code') != null) {
            $users = $users->where('home_postal_code', 'LIKE', $request->input('home_postal_code'));
        }
        if ($request->input('home_ownership') != null) {
            $users = $users->where('home_ownership', 'LIKE', $request->input('home_ownership'));
        }
        if ($request->input('home_phone') != null) {
            $users = $users->where('home_phone', 'LIKE', $request->input('home_phone'));
        }
        if ($request->input('mobile_phone') != null) {
            $users = $users->where('mobile_phone', 'LIKE', $request->input('mobile_phone'));
        }
        if ($request->input('id_no') != null) {
            $users = $users->where('id_no', 'LIKE', $request->input('id_no'));
        }
        if ($request->input('npwp_no') != null) {
            $users = $users->where('npwp_no', 'LIKE', $request->input('npwp_no'));
        }
        if ($request->input('pob') != null) {
            $users = $users->where('pob', 'LIKE', $request->input('pob'));
        }
        if ($request->input('dob.start') != null || ($request->input('dob.end') != null))    {
            if ($request->input('dob.start') != null && $request->input('dob.end') != null) {
                // return '1';
                $users = $users->whereBetween('dob', [$request->input('dob.start'), $request->input('dob.end')]);
            }elseif($request->input('dob.start') != null){
                // return '2';
                $users = $users->where('dob', '>=', $request->input('dob.start'));
            }else{
                // return '3';
                $users = $users->where('dob', '<=', $request->input('dob.end'));
            }
        }
        if ($request->input('company_id') != null) {
            $users = $users->where('company_id', $request->input('company_id'));
        }
        if ($request->input('employment_salary.start') != null || ($request->input('employment_salary.end') != null))    {
            if ($request->input('employment_salary.start') != null && $request->input('employment_salary.end') != null) {
                // return '1';
                $users = $users->whereBetween('employment_salary', [$request->input('employment_salary.start'), $request->input('employment_salary.end')]);
            }elseif($request->input('employment_salary.start') != null){
                // return '2';
                $users = $users->where('employment_salary', '>=', $request->input('employment_salary.start'));
            }else{
                // return '3';
                $users = $users->where('employment_salary', '<=', $request->input('employment_salary.end'));
            }
        }
        if ($request->input('employment_position') != null) {
            $users = $users->where('employment_position', $request->input('employment_position'));
        }
        if ($request->input('employment_duration.start') != null || ($request->input('employment_duration.end') != null))    {
            if ($request->input('employment_duration.start') != null && $request->input('employment_duration.end') != null) {
                // return '1';
                $users = $users->whereBetween('employment_duration', [$request->input('employment_duration.start'), $request->input('employment_duration.end')]);
            }elseif($request->input('employment_duration.start') != null){
                // return '2';
                $users = $users->where('employment_duration', '>=', $request->input('employment_duration.start'));
            }else{
                // return '3';
                $users = $users->where('employment_duration', '<=', $request->input('employment_duration.end'));
            }
        }
        if ($request->input('employment_status') != null) {
            $users = $users->where('employment_status', $request->input('employment_status'));
        }
        $users = $users->get();
        // dd($users);
        view()->share('users',$users);
        $pdf = PDF::loadView('admin.users.export.pdf');
        return $pdf->stream('users.pdf');
        // return $users;
        // return view('admin.users.export.pdf', compact('users'));

    }

    public function report()
    {
        return Admin::content(function (Content $content) {
            $content->header('Users');
            $content->description('Report');
            $this->showFormParameters($content);
            $content->body(new Box('Form-1', $this->form_report()));
            // $content->body($this->grid_report());
        });
    }

    public function grid_report()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            if(Admin::user()->isRole('auditor')) {
                $grid->disableCreateButton();
                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableExport();
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
            // $grid->tools(function ($tools) {
            //     $params = '';
            //     if (isset($_SERVER['QUERY_STRING'])) {
            //         $params = $_SERVER['QUERY_STRING'];
            //     }
            //     $tools->append('<a target="_blank" href="users/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            // });        

            $grid->code('User ID')->sortable();
            $grid->column('company_id','Company')->sortable()->display(function ($company_id) {
                $company = \App\Company::find($company_id);
                return $company ? $company->name : '-';
            });
            $grid->name()->sortable();
            $grid->email()->sortable();
            $grid->column('type')->sortable();
            $grid->column('roles')->display(function () {
                $user = \App\User::find($this->id);
                return $user->roles ? $user->roles->pluck('name')->first() : '-';
            });
            $grid->active()->sortable()->display(function ($value) {
                return $value == true ? 'Active' : 'Inactive';
            });
            $grid->verified()->sortable()->display(function ($value) {
                return $value == true ? 'Verified' : 'Unverified';
            });
            $grid->bi_checking()->sortable()->display(function ($value) {
                return $value? 'Collect '.$value : '-';
            });

            $grid->created_at()->sortable();
            $grid->exporter(new ExcelExporterUser());  

            if (isset($_GET['report_'])) {
                $grid->disableActions();
            }          

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
                
                $actions->append('<a target="_blank" href="/admin/activitylogs?User='.$actions->getKey().'"><i class="fa fa-history"></i></a></i></a>');
                $users = User::find($actions->getKey());
                if ($users) {
                    $users_role = $users->getRoleNames();
                }
                // dd($users_role[0]);
                if (!empty($users_role[0])) {
                    if ($users_role[0] == 'borrower') {
                        $actions->append('<a style="margin-left: 7%;" target="_blank" href="/admin/installments?User='.$actions->getKey().'&installmentable_type=App%5CLoan"><i class="fa fa-info"></i></a></i></a>');
                    }elseif($users_role[0] == 'lender'){
                       $actions->append('<a style="margin-left: 7%;" target="_blank" href="/admin/installments?User='.$actions->getKey().'&installmentable_type=App%5CInvestment"><i class="fa fa-info"></i></a></i></a>');                
                    }else{

                    }
                }
            });

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->where(function ($query) {
                    $query->whereIn('home_state', function($query){
                        $query->select('name')
                            ->from('provinces')
                            ->where('region_id', "{$this->input}");
                    });
                }, 'Region')->select(['1' => 'Barat', '2' => 'Tengah', '3' => 'Timur']);
                $filter->equal('type')->select(['orang' => 'Orang', 'badan' => 'Badan']);
                $filter->like('name');
                $filter->like('email');
                $filter->where(function ($query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', '=', "{$this->input}");
                    });
                }, 'Roles')->select(['lender' => 'lender', 'borrower' => 'borrower']);
                $filter->equal('bi_checking')->select([ '1' => 'Collect 1', '2' => 'Collect 2', '3' => 'Collect 3', '4' => 'Collect 4', '5' => 'Collect 5']);
                $filter->equal('verified')->radio([0 => 'Not Verified', 1 => 'Verified']);
                $filter->equal('active')->radio([0 => 'Inactive', 1 => 'Active']);
                $filter->like('home_address', 'Address');
                $filter->like('home_state', 'State');
                $filter->like('home_postal_code', 'Postal Code');
                $filter->equal('home_ownership', 'Status Tempat Tinggal')->select([ 'sendiri' => 'Sendiri', 'sendiri' => 'Sendiri', 'keluarga' => 'Keluarga', 'sewa' => 'Sewa', 'lainnya' => 'Lain-lain']);
                $filter->like('home_phone');
                $filter->like('mobile_phone');
                $filter->like('id_no', 'No KTP');
                $filter->like('npwp_no', 'No NPWP');
                $filter->like('pob', 'Place of birth');
                $filter->between('dob', 'Date or birth')->date();
                $filter->equal('company_id', 'Company')->select(Company::get()->pluck('name','id'));
                $filter->between('employment_salary', 'Monthy Salary');
                $filter->like('employment_position', 'Position');
                $filter->between('employment_duration', 'Duration (months)');
                $filter->equal('employment_status', 'Status')->select([ 'kontrak' => 'Kontrak', 'permanen' => 'Permanen']);
            });
        });
    }

    protected function form_report()
    {
        $form = new WidgetsForm();
        $form->date('Created At');
        return $form;
    }

    protected function showFormParameters($content)
    {
        $parameters = request()->except(['_pjax', '_token']);
        if (!empty($parameters)) {
            ob_start();
            dump($parameters);
            $contents = ob_get_contents();
            ob_end_clean();
            $content->row(new Box('Form parameters', $contents));
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;
use DateTime;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Auth;
use DB;
use App\Loan;
use App\LoanTenor;
use App\Company;
use App\Installment;
use App\Investment;
use App\Status;
use App\Provinces;
use App\District;
use App\Bank;
use App\BankAccount;
use App\LoanGrade;
use App\Transaction;
use App\Events\CashInInvestment;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    private $needPayment;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->needPayment = 0;
        $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError', 'verify_account', 'get_verify_resend', 'post_verify_resend']]);
    }

    public function dataTable(Request $request)
    {
        $amountFunded = $request->input('amount_funded');
        $grade = $request->input('grade');
        $investRate = $request->input('investRate');

        //return 'asdasd';

        $search = $request->input('search');
        $draw = $request->input('draw');

        $columns = [
            'loans.id',
            'loans.amount_requested',
            DB::raw('(loans.amount_requested - loans.amount_funded) as amount_funded'),
            'lt.month',
            'lg.rank',
            'loans.invest_rate as interest_rate',
            'loans.created_at',
            'loans.amount_funded',
            'loans.amount_borrowed',
            'loans.date_expired',
        ];
                /*->whereDate('date_expired','<',$expire_date)*/
                /*->whereRaw('loans.amount_funded <> loans.amount_borrowed')*/

        $datas = Loan::join('loan_tenors as lt', 'lt.id', '=', 'loans.loan_tenor_id')
        ->join('loan_grades as lg', 'lg.id', '=', 'loans.loan_grade_id')
        ->join('users as u', 'u.id', '=', 'loans.user_id')
        ->join('installments as im', 'im.installmentable_id', '=', 'loans.id')
        ->whereNotNull('loans.loan_grade_id')
        ->where('lt.active','1')
        ->where('loans.status_id','3')
        ->where('loans.created_at', '>=', Carbon::now()->addDays(-7)->toDateString())
        ->where('loans.date_expired', '>=', Carbon::now()->toDateString())
        ->whereRaw('loans.amount_funded < loans.amount_requested')
        ->where([
            'im.installmentable_type' => 'App\Loan',
            'im.tenor' => '1',
            'im.paid' => '0',
        ]);

        if (!empty($amountFunded)) {
            $datas = $datas->whereRaw('IF (loans.amount_funded is not null , (loans.amount_requested - loans.amount_funded), loans.amount_requested) <= ' . intval($amountFunded));
            $datas= $datas->where('loans.amount_requested', '<=', $amountFunded);
        }


        if (!empty($grade)) {
            $tenorMonth = LoanTenor::where('id', $grade)->first(['month']);
            $datas = $datas->where('lt.month', '<=', $tenorMonth->month);
        }
        /*if (!empty($investRate)) {
            $datas = $datas->where('loans.invest_rate', '>=', $investRate);
        }*/
                    
        $response['recordsTotal']    = $datas->count();
        $response['recordsFiltered'] = $datas->count();

        $searchValue = $search['value'];

        if ($request->has ( 'order' )) {
            if ($request->input ( 'order.0.column' ) != '') {
                $orderColumn = $request->input ( 'order.0.column' );
                $orderDirection = $request->input ( 'order.0.dir' );
                $datas->orderBy ( $columns [intval ( $orderColumn )], $orderDirection );
            }
        }

        $start  = $request->input ( 'start' );   
        $length = $request->input ( 'length' ); 


        $response['data'] = array();
        $response['data'] = $this->getDataTable($datas->select($columns)->get());
        return response()->json($response);
        
        //return response()->json($datas->get());
        //return response()->json($response);
    }

    private function getDataTable($datas)
    {
        $response = array();
        $count = count($datas);
        $actionFormat = '<div onclick="onLoanFundingClick(this)" data-id="{id}" data-amount="{amount}" class="btn btn-default btn-success-pohondana btn-loan-funding">Pilih</div>';

        //return (1 != null ? "'" . round((1/120)*100,2) . "'" : '0');
        //return $datas;

        foreach ($datas as $data) {
            $amountPending = $data->amount_funded != null ? $data->amount_requested - $data->amount_funded : $data->amount_requested; 
                $action = str_replace('{id}', $data->id, $actionFormat); 
                $action = str_replace('{amount}', $amountPending, $action); 
            $data = [
                '<center>' . $data->id . '</center>',
                '<center>Rp. ' . number_format($data->amount_requested, 0, '.', '.') . '</center>',
                    '<center>Rp. ' . number_format($data->amount_requested - $data->amount_funded, 0, '.', '.') . '</center>',
                '<center>' . $data->month. ' Bulan</center>',
                '<center>' . $data->rank . '</center>',
                '<center>' . $data->interest_rate * 12 . '%</center>',
                '<center>' . $data->created_at->addDays(7)->format('d/m/Y') . '</center>',
                // '<center>' . ( $data->amount_funded != null ? "'" . round(($data->amount_funded/$data->amount_borrowed)*100,2) . "'" : '0' ) . '%</center>',
                '<center>' . ( $data->amount_funded != null ? round(($data->amount_funded/$data->amount_requested)*100,2) : 0 ) . '%</center>',
                '<div class="table-action">' . $action . '</div>',
            ];
            array_push($response, $data);
        }
        return $response;
    }

    public function getLenderRate($id)
    {
        $lenderGrade = LoanGrade::where('rank', $id)->select('id', 'lender_rate')->first();
        return response()->json($lenderGrade);
    }

    public function showRegisterLenderType()
    {
        return view('auth.register_lender_type', compact('tenors', 'provinces'));
    }

    public function showRegistrationTypeForm($type = 'borrower', Request $request)
    {
        $provinces = Provinces::select('name')->orderBy('name')->get();
        $tenors = LoanTenor::active()->orderBy('month', 'ASC')->pluck('month','id');
        $banks = Bank::select('id', 'name')->orderBy('name')->get();
        if($type == 'lender') {
            $isPayment = $request->input('payment');
            $loanGrades = LoanGrade::select('rank', 'lender_rate')->where('rank', '<>', 'D')->groupBy('rank', 'lender_rate')->get();
            //return compact('provinces', 'banks', 'loanGrades', 'isPayment');
            return view('auth.register_lender', compact('provinces', 'banks', 'loanGrades', 'isPayment', 'tenors'));
        } elseif($type == 'lender_company') {
            $isPayment = $request->input('payment');
            $loanGrades = LoanGrade::select('rank', 'lender_rate')->where('rank', '<>', 'D')->groupBy('rank', 'lender_rate')->get();
            //return compact('provinces', 'banks', 'loanGrades', 'isPayment');
            return view('auth.register_lender_company', compact('provinces', 'banks', 'loanGrades', 'isPayment', 'tenors'));
        } elseif($type == 'borrower') {
            $companies = Company::affiliate()->orderBy('name', 'asc')->pluck('name','id');
            return view('auth.register_borrower', compact('companies','tenors', 'provinces'));
        } elseif($type == 'borrower_company') {
            return view('auth.register_borrower_company', compact('tenors', 'provinces'));
        }

        return redirect('/register/borrower');
    }

    public function checkMobile(Request $request)
    {
        $value = $request->input('mobile_phone');
        $isExists = \App\User::where('mobile_phone',$value)->select('id')->count();
        if(empty($isExists)){
            $mobile['exist'] = false;
        }else{
            $mobile['exist'] = true;
        }

        return response()->json($mobile);
    }

    public function checkMobilePhone(Request $request)
    {   
        $value = $request->input('mobile_phone');
        $user = User::where('mobile_phone', $value)->select('id')->count();

        return !empty($user) ? '"No. Handphone sudah digunakan"' : 'true';
    }

    public function checkEmailV2(Request $request)
    {
        $value = $request->input('email');
        $user = User::where('email',$value)->select('id')->count();

        return !empty($user) ? '"E-mail sudah digunakan"' : 'true';
    }

    public function checkemail(Request $request){
        $email = $request->input('email');
        $isExists = \App\User::where('email',$email)->select('id')->count();
        if(empty($isExists)){
            $mail['exist'] = false;
        }else{
            $mail['exist'] = true;
        }

        return response()->json($mail);
    }

    public function checkKTPV2(Request $request){
        $id_no = $request->input('id_no');
        $user = User::where('id_no',$id_no)->select('id')->count();
        return !empty($user) ? '"No. KTP sudah digunakan"' : 'true';
    }

    public function checkKTP(Request $request){
        $id_no = $request->input('id_no');
        $isExists = \App\User::where('id_no',$id_no)->select('id')->count();
        if(empty($isExists)){
            $id_ktp['exist'] = false;
        }else{
            $id_ktp['exist'] = true;
        }

        return response()->json($id_ktp);
    }

    public function checkNPWPV2(Request $request){
        $npwp_no = $request->input('npwp_no');
        $user = User::where('npwp_no',$npwp_no)->select('id')->count();
        return !empty($user) ? '"No. NPWP sudah digunakan"' : 'true';
    }

    public function checkNPWP(Request $request){
        $npwp_no = $request->input('npwp_no');
        $isExists = \App\User::where('npwp_no',$npwp_no)->select('id')->count();
        if(empty($isExists)){
            $id_npwp['exist'] = false;
        }else{
            $id_npwp['exist'] = true;
        }

        return response()->json($id_npwp);
    }

    public function autocomplete(Request $request)
    {
        $data = Company::select("name")->where("name","LIKE","%{$request->input('query')}%")
        ->where("affiliate", "=", "1")
        ->orderBy('name')
        ->get();
        // dd($data);
        return response()->json($data);
    }

    public function district(){
        $name = Input::get('name');
        $district = DB::table('district')
        ->select('district.name')
        ->join('provinces as pro', 'pro.id', '=', 'district.province_id')
        ->where('pro.name', '=', $name)
        ->orderBy('name')
        ->get();
        // dd($district);
        return response()->json($district);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if($data['user_type'] == 'lender') {
            return Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'mobile_phone' => 'required|alpha_dash',
                'agreed' => 'accepted',
                'mobile_phone' => 'required|alpha_dash',
                'home_address' => 'required|string|max:255',
                'home_city' => 'required|string|max:255',
                'home_state' => 'required|string|max:255',
                'home_postal_code' => 'required|alpha_dash',
                'id_no' => 'required|alpha_dash|unique:users',
                /*'id_doc' => 'required|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',*/
                'npwp_no' => 'required|alpha_dash|min:15|unique:users',
                /*'npwp_doc' => 'required|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',*/
                'pob' => 'required',
                'dob' => 'required|date_format:"d/m/Y"',
                'bank_name' => 'required|exists:banks,id',
                'account_name' => 'required|string|max:191',
                'account_number' => 'required|numeric',
                'amount_invesment_value' => 'required|numeric|min:1000000',
                /*'grade_investment' => 'required|exists:loan_grades,rank',
                'rate_investment' => 'required|exists:loan_grades,lender_rate',*/
                'loan_id' => 'required|exists:loans,id',
            ]);
        } elseif($data['user_type'] == 'borrower') {
            return Validator::make($data, [
                'amount_requested' => 'required|numeric|min:1000000',
                'pic_name' => 'nullable|string',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'mobile_phone' => 'required|alpha_dash',
                'home_phone' => 'nullable|alpha_dash',
                'home_address' => 'required|string|max:255',
                'home_city' => 'required|string|max:255',
                'home_state' => 'required|string|max:255',
                'home_postal_code' => 'required|alpha_dash',
                'home_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'home_ownership' => 'required',
                'id_no' => 'required|alpha_dash|min:16|unique:users',
                'id_doc' => 'required|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'npwp_no' => 'required|alpha_dash|min:15|unique:users',
                'npwp_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'pob' => 'required|string',
                'dob' => 'required|date_format:"d/m/Y"',
                'company_id' => 'required',
                'employment_salary' => 'required|integer',
                'employment_salary_slip' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'employment_position' => 'required|string|max:255',
                'employment_duration_year' => 'nullable|integer',
                'employment_duration_month' => 'nullable|integer',
                'employment_status' => 'required',
                'agreed' => 'accepted'
            ]);
        } elseif($data['user_type'] == 'borrower_company') {
            return Validator::make($data, [
                'company_id' => 'required',
                'amount_requested' => 'required|numeric|min:1000000',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'mobile_phone' => 'required|alpha_dash',
                'home_phone' => 'nullable|alpha_dash',
                'home_address' => 'required|string|max:255',
                'home_city' => 'required|string|max:255',
                'home_state' => 'required|string|max:255',
                'home_postal_code' => 'required|alpha_dash',
                'home_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'home_ownership' => 'required',
                'id_no' => 'required|alpha_dash|min:16|unique:users',
                'id_doc' => 'required|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'npwp_no' => 'required|alpha_dash|min:15|unique:users',
                'npwp_doc' => 'required|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'agreed' => 'accepted',
                'current_equity' => 'required|numeric',
                'current_asset' => 'required|numeric',
                'current_inventory' => 'required|numeric',
                'current_receivable' => 'required|numeric',
                'current_debt' => 'required|numeric',
                'financial_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'collateral_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'company_industry' => 'required',
                'company_type' => 'required',
                'website_url' => 'required',
                'tdp_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
                'akta_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048'
            ]);
        } elseif($data['user_type'] == 'lender_company') {
            return Validator::make($data, [
                'company_id' => 'required',
                'employment_type' => 'required',
                'name' => 'required|string|max:255',
                'mobile_phone' => 'required|alpha_dash',
                'id_no' => 'required|alpha_dash|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'agreed' => 'accepted',
                'home_address' => 'required|string|max:255',
                'home_city' => 'required|string|max:255',
                'home_state' => 'required|string|max:255',
                'home_postal_code' => 'required|alpha_dash',
                'home_phone' => 'required|alpha_dash',
                'website_url' => 'required',
                'company_type' => 'required',
                'company_industry' => 'required',
                'home_ownership' => 'required',
                'npwp_no' => 'required|alpha_dash|min:15|unique:users',
                'bank_name' => 'required|exists:banks,id',
                'account_name' => 'required|string|max:191',
                'account_number' => 'required|numeric',
                'amount_invesment_value' => 'required|numeric|min:1000000',
                'loan_id' => 'required|exists:loans,id',
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // dd($data);
        if ($data['user_type'] == 'borrower') {

            try{
                $company = Company::firstOrCreate(['name' => $data['company_id']]);
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'mobile_phone' => $data['mobile_phone'],
                    'home_phone' => $data['home_phone'],
                    'home_address' => $data['home_address'],
                    'home_city' => $data['home_city'],
                    'home_state' => $data['home_state'],
                    'home_postal_code' => $data['home_postal_code'],
                    'home_ownership' => $data['home_ownership'],
                    'id_no' => $data['id_no'],
                    'npwp_no' => $data['npwp_no'],
                    'pob' => $data['pob'],
                    'dob' => DateTime::createFromFormat('d/m/Y', $data['dob'])->format('Y-m-d'),
                    'company_id' => $company->id,
                    'employment_type' => $data['employment_type'],
                    'employment_salary' => $data['employment_salary'],
                    'employment_position' => $data['employment_position'],
                    'employment_duration' => ($data['employment_duration_year'] * 12) + $data['employment_duration_month'],
                    'employment_status' => $data['employment_status'],
                    'gender' => $data['gender'],
                    'education' => $data['education'],
                    'religion' => $data['religion'],
                    'verified' => false,
                    'active' => false,
                    'type' => 'orang',
                ]);

                // upload docs
                if(array_key_exists('id_doc', $data)) {
                    $filename = (time()+1).'.'.$data['id_doc']->extension();
                    $user->id_doc = $data['id_doc']->storeAs('documents', $filename, 'public');                
                }

                if(array_key_exists('home_doc', $data)) {
                    $filename = (time()+2).'.'.$data['home_doc']->extension();
                    $user->home_doc = $data['home_doc']->storeAs('documents', $filename, 'public');
                }

                if(array_key_exists('npwp_doc', $data)) {
                    $filename = (time()+3).'.'.$data['npwp_doc']->extension();
                    $user->npwp_doc = $data['npwp_doc']->storeAs('documents', $filename, 'public');
                }

                if(array_key_exists('employment_salary_slip', $data)) {
                    $filename = (time()+4).'.'.$data['employment_salary_slip']->extension();
                    $user->employment_salary_slip = $data['employment_salary_slip']->storeAs('documents', $filename, 'public');
                }

                $user->save();
                $user->assignRole('borrower');

                $tenor = LoanTenor::find($data['tenor_id']);

                // Create object
                $loan = new Loan;
                $loan->amount_requested = $data['amount_requested'];
                $loan->loan_tenor_id = $tenor->id;
                $loan->date_expired = Carbon::now()->addDays(7);
                $loan->description = $data['description'];
                $loan->status_id = Status::where('name','pending')->first()->id;
                $loan->provision_rate = 1;
                $loan->user_id = $user->id;
                $loan->save();
                activity()
                ->causedBy($user)
                ->withProperties($data)
                ->performedOn($loan)
                ->log('Create Loan');


            }catch(\Exception $e){
                DB::rollback();
                return back()
                ->withInput(Input::all())
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengirim data. Silahkan ulangi kembali' .
                    $e->getMessage() 
                );
            }

        } elseif($data['user_type'] == 'borrower_company') {
            $company = Company::firstOrCreate(['name' => $data['company_id']]);
            $user = User::create([
                'company_id' => $company->id,
                'mobile_phone' => $data['mobile_phone'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'name' => $data['name'],
                'home_address' => $data['home_address'],
                'home_state' => $data['home_state'],
                'home_city' => $data['home_city'],
                'home_postal_code' => $data['home_postal_code'],
                'home_phone' => $data['home_phone'],
                'id_no' => $data['id_no'],
                'npwp_no' => $data['npwp_no'],
                'home_ownership' => $data['home_ownership'],
                'verified' => false,
                'active' => false,
                'company_industry' => $data['company_industry'],
                'company_type' => $data['company_type'],
                'current_equity' => $data['current_equity'],
                'current_asset' => $data['current_asset'],
                'current_inventory' => $data['current_inventory'],
                'current_receivable' => $data['current_receivable'],
                'current_debt' => $data['current_debt'],
                'type' => 'badan',
                'website_url' => $data['website_url'],
            ]);

            // upload docs
            if(array_key_exists('id_doc', $data)) {
                $filename = (time()+1).'.'.$data['id_doc']->extension();
                $user->id_doc = $data['id_doc']->storeAs('documents', $filename, 'public');                
            }

            if(array_key_exists('home_doc', $data)) {
                $filename = (time()+2).'.'.$data['home_doc']->extension();
                $user->home_doc = $data['home_doc']->storeAs('documents', $filename, 'public');
            }

            if(array_key_exists('npwp_doc', $data)) {
                $filename = (time()+3).'.'.$data['npwp_doc']->extension();
                $user->npwp_doc = $data['npwp_doc']->storeAs('documents', $filename, 'public');
            }

            if(array_key_exists('financial_doc', $data)) {
                $filename = (time()+4).'.'.$data['financial_doc']->extension();
                $user->financial_doc = $data['financial_doc']->storeAs('documents', $filename, 'public');
            }

            if(array_key_exists('collateral_doc', $data)) {
                $filename = (time()+5).'.'.$data['collateral_doc']->extension();
                $user->collateral_doc = $data['collateral_doc']->storeAs('documents', $filename, 'public');
            }

            if(array_key_exists('tdp_doc', $data)) {
                $filename = (time()+6).'.'.$data['tdp_doc']->extension();
                $user->tdp_doc = $data['tdp_doc']->storeAs('documents', $filename, 'public');
            }

            if(array_key_exists('akta_doc', $data)) {
                $filename = (time()+7).'.'.$data['akta_doc']->extension();
                $user->akta_doc = $data['akta_doc']->storeAs('documents', $filename, 'public');
            }

            $user->save();
            $user->assignRole('borrower');
            $user->addCode();

            $tenor = LoanTenor::find($data['tenor_id']);

            // Create object
            $loan = new Loan;
            $loan->amount_requested = $data['amount_requested'];
            $loan->loan_tenor_id = $tenor->id;
            $loan->date_expired = Carbon::now()->addDays(7);
            $loan->description = $data['description'];
            $loan->status_id = Status::where('name','pending')->first()->id;
            $loan->provision_rate = 1;
            $loan->user_id = $user->id;
            $loan->save();

        } elseif($data['user_type'] == 'lender') {

            try {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'mobile_phone' => $data['mobile_phone'],
                    'home_address' => $data['home_address'],
                    'home_city' => $data['home_city'],
                    'home_state' => $data['home_state'],
                    'home_postal_code' => $data['home_postal_code'],
                    'pob' => $data['pob'],
                    'dob' => DateTime::createFromFormat('d/m/Y', $data['dob'])->format('Y-m-d'),
                    'id_no' => $data['id_no'],
                    'npwp_no' => $data['npwp_no'],
                    'verified' => 1,
                    'active' => false,
                    'type' => 'orang',
                ]);

                /*if(array_key_exists('id_doc', $data)) {
                    $filename = (time()+1).'.'.$data['id_doc']->extension();
                    $user->id_doc = $data['id_doc']->storeAs('documents', $filename, 'public');                
                }*/

                if(array_key_exists('id_doc_base_64', $data)) {

                    $img = $data['id_doc_base_64'];


                    $img = substr($img, strpos($img, ",")+1);
                    $filename = (time()+1) . '.jpg';
                    $path = public_path() . "/storage/documents/" . $filename;
                    $dataImage = base64_decode($img);
                    file_put_contents($path, $dataImage);
                    $user->id_doc = 'documents/' . $filename;/**/

                    // $user->id_doc = $data['id_doc']->storeAs('documents', $filename, 'public');                
                }

                /*if(array_key_exists('npwp_doc', $data)) {
                    $filename = (time()+3).'.'.$data['npwp_doc']->extension();
                    $user->npwp_doc = $data['npwp_doc']->storeAs('documents', $filename, 'public');
                }*/

                if(array_key_exists('npwp_doc_base_64', $data)) {

                    $img = $data['npwp_doc_base_64'];

                    $img = substr($img, strpos($img, ",")+1);
                    $filename = (time()+2) . '.jpg';
                    $path = public_path() . "/storage/documents/" . $filename;
                    $dataImage = base64_decode($img);
                    file_put_contents($path, $dataImage);
                    $user->npwp_doc = 'documents/' . $filename;            
                }

                $user->save();
                $user->assignRole('lender');
                $user->addCode();

                BankAccount::create([
                    'user_id' => $user->id,
                    'bank_id' => $data['bank_name'],
                    'account_name' => $data['account_name'],
                    'account_number' => $data['account_number'],
                ]);
                
                $loan = Loan::find($data['loan_id']);

                // Kalau ga ada existing loans
                // if( ($data['loan_id'] == 1) || ($data['loan_id'] == 2) || ($data['loan_id'] == 3) ) { // Check function GetDataTable, refer to static var

                //     // dd($data);
                //     // Create fake loan
                //     $tenor = LoanTenor::find($data['grade_investment']);
                    
                //     if ($data['loan_id'] == '1') { $static_rank = 'A'; $static_investRate = 1.25; } 
                //     elseif($data['loan_id'] == '2'){ $static_rank = 'B'; $static_investRate = 1.3; } 
                //     elseif ($data['loan_id'] == '3') { $static_rank = 'C'; $static_investRate = 1.5; }

                //     $static_loan_grade = LoanGrade::where('loan_tenor_id',$tenor->id)->where('rank',$static_rank)->get();

                //     $loan = new Loan;
                //     $loan->amount_requested = $data['amount_invesment_value'];
                //     $loan->loan_tenor_id = $tenor->id;
                //     $loan->date_expired = Carbon::now()->addDays(7);
                //     $loan->description = "Auto investment fake loan";
                //     $loan->status_id = 3; // Auto accepted because fake
                //     $loan->provision_rate = NULL;
                //     $loan->invest_rate = $static_investRate;
                //     $loan->loan_grade_id = $static_loan_grade->id;
                //     $loan->user_id = NULL;
                //     $loan->save();
                // }

                // dd($loan);

                $investment = Investment::create([
                    'amount_invested' => $data['amount_invesment_value'],
                    'amount_total' => $data['amount_invesment_value'],
                    'loan_id' => $loan->id,
                    'user_id' => $user->id,
                    'status_id' => 3,
                    'invest_rate' => $loan->invest_rate,
                ]);

                $loan->amount_funded += $data['amount_invesment_value'];
                $loan->save();

                $investment->calculateRates();
                $investment->generateInstallments();

                $transactionid = Transaction::insertGetId([
                    'amount' => $investment->amount_invested,
                    'user_id' => $investment->user_id,
                    'transactionable_id' => $investment->id,
                    'transactionable_type' => 'App\Investment',
                    'payment_date' => Carbon::now(),
                    'type' => 'Cash In',
                    'status_id' => 1,
                    'created_at' => Carbon::now()
                ]);

                $transaction3 = Transaction::find($transactionid);
                event(new CashInInvestment($transaction3));
                $this->needPayment = 1;
                
                activity()
                ->causedBy($user)
                ->withProperties($data)
                ->performedOn($investment)
                ->log('Create Investment');

            }catch(\Exception $e){
                DB::rollback();
                return back()
                ->withInput(Input::all())
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengirim data. Silahkan ulangi kembali' .
                    $e->getMessage() 
                );
            }
        } 
        elseif($data['user_type'] == 'lender_company') {
            $company = Company::firstOrCreate(['name' => $data['company_id']]);
            try {
                $user = User::create([
                    'company_id' => $company->id,
                    'employment_type' => $data['employment_type'],
                    'name' => $data['name'],
                    'mobile_phone' => $data['mobile_phone'],
                    'id_no' => $data['id_no'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'home_address' => $data['home_address'],
                    'home_state' => $data['home_state'],
                    'home_city' => $data['home_city'],
                    'home_postal_code' => $data['home_postal_code'],
                    'home_phone' => $data['home_phone'],
                    'website_url' => $data['website_url'],
                    'company_type' => $data['company_type'],
                    'company_industry' => $data['company_industry'],
                    'home_ownership' => $data['home_ownership'],
                    'npwp_no' => $data['npwp_no'],
                    'verified' => 1,
                    'active' => false,
                    'type' => 'badan',
                ]);

                if(array_key_exists('id_doc_base_64', $data)) {

                    $img = $data['id_doc_base_64'];
                    $img = substr($img, strpos($img, ",")+1);
                    $filename = (time()+1) . '.jpg';
                    $path = public_path() . "/storage/documents/" . $filename;
                    $dataImage = base64_decode($img);
                    file_put_contents($path, $dataImage);
                    $user->id_doc = 'documents/' . $filename;/**/     
                }

                if(array_key_exists('npwp_doc_base_64', $data)) {

                    $img = $data['npwp_doc_base_64'];

                    $img = substr($img, strpos($img, ",")+1);
                    $filename = (time()+2) . '.jpg';
                    $path = public_path() . "/storage/documents/" . $filename;
                    $dataImage = base64_decode($img);
                    file_put_contents($path, $dataImage);
                    $user->npwp_doc = 'documents/' . $filename;            
                }

                if(array_key_exists('akta_doc_base_64', $data)) {

                    $img = $data['akta_doc_base_64'];

                    $img = substr($img, strpos($img, ",")+1);
                    $filename = (time()+3) . '.jpg';
                    $path = public_path() . "/storage/documents/" . $filename;
                    $dataImage = base64_decode($img);
                    file_put_contents($path, $dataImage);
                    $user->akta_doc = 'documents/' . $filename;            
                }

                if(array_key_exists('home_doc_base_64', $data)) {

                    $img = $data['home_doc_base_64'];

                    $img = substr($img, strpos($img, ",")+1);
                    $filename = (time()+4) . '.jpg';
                    $path = public_path() . "/storage/documents/" . $filename;
                    $dataImage = base64_decode($img);
                    file_put_contents($path, $dataImage);
                    $user->home_doc = 'documents/' . $filename;            
                }

                if(array_key_exists('tdp_doc_base_64', $data)) {

                    $img = $data['tdp_doc_base_64'];

                    $img = substr($img, strpos($img, ",")+1);
                    $filename = (time()+5) . '.jpg';
                    $path = public_path() . "/storage/documents/" . $filename;
                    $dataImage = base64_decode($img);
                    file_put_contents($path, $dataImage);
                    $user->tdp_doc = 'documents/' . $filename;            
                }

                $user->save();
                $user->assignRole('lender');
                $user->addCode();

                BankAccount::create([
                    'user_id' => $user->id,
                    'bank_id' => $data['bank_name'],
                    'account_name' => $data['account_name'],
                    'account_number' => $data['account_number'],
                ]);
                
                $loan = Loan::find($data['loan_id']);

                // Kalau ga ada existing loans
                // if( $data['loan_id'] == 1 || $data['loan_id'] == 2 || $data['loan_id'] == 3 ) { // Check function GetDataTable, refer to static var

                //     // Create fake loan
                //     $tenor = LoanTenor::find($data['grade_investment']);
                    
                //     if ($data['loan_id'] == '1') { $static_rank = 'A'; $static_investRate = 1.25; } 
                //     elseif($data['loan_id']== '2'){ $static_rank = 'B'; $static_investRate = 1.3; } 
                //     elseif ($data['loan_id'] == '3') { $static_rank = 'C'; $static_investRate = 1.5; }

                //     $static_loan_grade = LoanGrade::where('loan_tenor_id',$tenor->id)->where('rank',$static_rank)->get();

                //     $loan = new Loan;
                //     $loan->amount_requested = $data['amount_invesment_value'];
                //     $loan->loan_tenor_id = $tenor->id;
                //     $loan->date_expired = Carbon::now()->addDays(7);
                //     $loan->description = "Auto investment fake loan";
                //     $loan->status_id = 3; // Auto accepted because fake
                //     $loan->provision_rate = NULL;
                //     $loan->invest_rate = $static_investRate;
                //     $loan->loan_grade_id = $static_loan_grade->id;
                //     $loan->user_id = NULL;
                //     $loan->save();
                // }

                $investment = Investment::create([
                    'amount_invested' => $data['amount_invesment_value'],
                    'amount_total' => $data['amount_invesment_value'],
                    'loan_id' => $loan->id,
                    'user_id' => $user->id,
                    'status_id' => 3,
                    'invest_rate' => $loan->invest_rate,
                    'notes' => null,
                ]);

                $loan->amount_funded += $data['amount_invesment_value'];
                $loan->save();

                $investment->calculateRates();
                $investment->generateInstallments();

                $transactionid = Transaction::insertGetId([
                    'amount' => $investment->amount_invested,
                    'user_id' => $investment->user_id,
                    'transactionable_id' => $investment->id,
                    'transactionable_type' => 'App\Investment',
                    'payment_date' => Carbon::now(),
                    'type' => 'Cash In',
                    'status_id' => 1,
                    'created_at' => Carbon::now()
                ]);

                $transaction3 = Transaction::find($transactionid);
                event(new CashInInvestment($transaction3));
                $this->needPayment = 1;
                
                activity()
                ->causedBy($user)
                ->withProperties($data)
                ->performedOn($investment)
                ->log('Create Investment');

            }catch(\Exception $e){
                DB::rollback();
                return back()
                ->withInput(Input::all())
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengirim data. Silahkan ulangi kembali' .
                    $e->getMessage() 
                );
            }
        }
        return $user;
    }

    protected function registered(Request $request, $user)
    {
        //$this->guard()->logout();
        if ($this->needPayment == 1) {
            return redirect('/register/lender?payment=' . $this->needPayment)->with('success','Pendaftaran berhasil, harap periksa kotak masuk Anda untuk email verifikasi akun');
        }
        return redirect('/login?payment=' . $this->needPayment)->with('success','Pendaftaran berhasil, harap periksa kotak masuk Anda untuk email verifikasi akun');
    }
}

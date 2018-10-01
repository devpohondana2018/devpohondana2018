<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Company;
use App\Loan;
use App\Status;
use App\Bank;
use App\BankAccount;
use App\Investment;
use App\Installment;
use Session;
use Carbon\Carbon;
use DB;
use Encore\Admin\Config\Config;
use App\Mail\InstallmentReminderEmail;
use Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(Auth::user()->hasRole('borrower')) {

        $userId = Auth::user()->id;
        $loans = Auth::user()->loans();
        $pending_loans = Auth::user()
                          ->loans()
                          ->whereHas('status', function ($query) {
                              $query->where('name', 'pending');
                          })
                          ->select('id', 'created_at')
                          ->get();
        $approved_loans = Auth::user()
                          ->loans()
                          ->whereHas('status', function ($query) {
                              $query->where('name', 'approved');
                          })
                          ->select('id', 'created_at')
                          ->get();
        $accepted_loans = Auth::user()
                          ->loans()
                          ->whereHas('status', function ($query) {
                              $query->where('name', 'accepted');
                          })
                          ->get();
        $rejected_loan = Auth::user()
                          ->loans()
                          ->whereHas('status', function ($query) {
                              $query->where('name', 'rejected');
                          })
                          ->select('id')
                          ->get();
        $declined_loan = Auth::user()
                          ->loans()
                          ->whereHas('status', function ($query) {
                              $query->where('name', 'declined');
                          })
                          ->select('id')
                          ->get();
        $completed_loan = Auth::user()
                          ->loans()
                          ->whereHas('status', function ($query) {
                              $query->where('name', 'completed');
                          })
                          ->select('id')
                          ->get();

        foreach ($accepted_loans as $accepted_loan) {
          $installmentPaidCount = Installment::where([
                                      'paid' => 1,
                                      'installmentable_id' => $accepted_loan->id,
                                      'installmentable_type' => 'App\Loan'
                                  ])->count();
          $primaryInstallment = $accepted_loan->amount_requested / $accepted_loan->tenor->month;
          $accepted_loan->balance = $accepted_loan->amount_requested - ($primaryInstallment * $installmentPaidCount);
        }

        //return compact('loans', 'pending_loans','approved_loans','accepted_loans', 'completed_loan');
        return view('dashboard',compact('loans', 'pending_loans','approved_loans','accepted_loans', 'completed_loan', 'rejected_loan', 'declined_loan'));
      


        //$loans = Auth::user()->loans();
        $userId = Auth::user()->id;
        $pendingLoan = Loan::where(['user_id' => $userId, 'status_id' => 1])->count();
        $approvedLoan = Loan::where(['user_id' => $userId, 'status_id' => 2])->count();
        $acceptedLoan = Loan::where(['user_id' => $userId, 'status_id' => 3])->count();
        $completedLoan = Loan::where(['user_id' => $userId, 'status_id' => 7])->count();

        $loanTotal = Loan::whereIn('status_id', [3,7])
                 ->where([
                    'user_id' => $userId
                  ])
                 ->sum('amount_borrowed');

        $loanPendingPayment = Loan::whereIn('loans.status_id', [3,7])
                 ->where([
                    'loans.user_id' => $userId,
                    'i.paid' => 1,
                  ])
                 ->join('installments as i', 'i.installmentable_id', '=', 'loans.id')
                 ->where('i.installmentable_type', 'App\Loan')
                 ->sum('i.amount');

        $loanCompletedPayment = $loanTotal - $loanPendingPayment;
        
        $loanTotalDisplay = 'Rp. ' . number_format($loanTotal, 2, ',', '.');
        $loanPendingPaymentDisplay = 'Rp. ' . number_format($loanPendingPayment, 2, ',', '.');
        $loanCompletedPaymentDisplay = 'Rp. ' . number_format($loanCompletedPayment, 2, ',', '.');
        return view('dashboard',compact( 
          'pendingLoan',
          'approvedLoan',
          'acceptedLoan', 
          'completedLoan',
          'loanTotalDisplay',
          'loanPendingPaymentDisplay',
          'loanCompletedPaymentDisplay'
        ));
      }

      if(Auth::user()->hasRole('lender')) {
        $userId = Auth::user()->id;
        $paidInvestment = Investment::where(['user_id' => $userId, 'paid' => 1])->count();
        $unpaidInvestment = Investment::where(['user_id' => $userId, 'paid' => 0])->count();
        $canceledInvestment = Investment::where(['user_id' => $userId, 'status_id' => 8])->count();
        $completedInvestment = Investment::where(['user_id' => $userId, 'status_id' => 7])->count();

        $investmentTotal = Investment::whereIn('status_id', [3,7])
                 ->where([
                    'user_id' => $userId,
                    'paid' => 1,
                  ])
                 ->sum('amount_invested');

        $investmentTotalRefund = Investment::whereIn('investments.status_id', [3,7])
                 ->where([
                    'investments.user_id' => $userId,
                    'i.paid' => 1,
                  ])
                 ->join('installments as i', 'i.installmentable_id', '=', 'investments.id')
                 ->join('transactions as t', 't.transactionable_id', '=', 'i.id')
                 ->where('i.installmentable_type', 'App\Investment')
                 ->where('t.transactionable_type', 'App\Installment')
                 ->sum('t.amount');

        $pendingRefunded = $investmentTotal - $investmentTotalRefund;
        
        $investmentTotal = 'Rp. ' . number_format($investmentTotal, 2, ',', '.');
        $investmentTotalRefund = 'Rp. ' . number_format($investmentTotalRefund, 2, ',', '.');
        $pendingRefunded = 'Rp. ' . number_format($pendingRefunded, 2, ',', '.');
        return view('dashboard',compact(
          'investments', 
          'paidInvestment',
          'unpaidInvestment',
          'canceledInvestment', 
          'completedInvestment',
          'investmentTotalRefund',
          'investmentTotal',
          'pendingRefunded'
        ));
      }
      
    }

    public function profile()
    {
        $user = Auth::user();
        return $user->isVerified ? view('profile_block',compact('user')) : view('profile',compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if($user->hasRole('borrower')) {

          $request->validate([
            'name' => 'required|string|max:255',
            'mobile_phone' => 'required|alpha_dash',
            'home_phone' => 'required|alpha_dash',
            'home_address' => 'required|string|max:255',
            'home_city' => 'required|string|max:255',
            'home_state' => 'required|string|max:255',
            'home_postal_code' => 'required|alpha_dash',
            'home_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg|max:2048',
            'home_ownership' => 'required',
            'id_no' => ['required',Rule::unique('users')->ignore($user->id),'alpha_dash','min:16'],
            'id_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg|max:2048',
            'npwp_no' => ['required',Rule::unique('users')->ignore($user->id),'alpha_dash','min:15'],
            'npwp_doc' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg|max:2048',
            'pob' => 'required|string',
            'dob' => 'required|string',
            'company' => 'required|string',
            'employment_salary' => 'required|integer',
            'employment_salary_slip' => 'nullable|image|mimes:jpeg,gif,jpg,gif,svg|max:2048',
            'employment_position' => 'required|string|max:255',
            'employment_duration' => 'required|integer',
            'employment_status' => 'required'
          ]);

          // check user input compares with db
          $company = Company::firstOrCreate(['name' => $request->company]);
          $user->company_id = $company->id;
        
        } else {
          $request->validate([
            'name' => 'required|string|max:255',
            'mobile_phone' => 'required|alpha_dash'
          ]);
        }

        $user->fill($request->all());

        if($request->hasFile('id_doc')) {
          $filename = (time()+1).'.'.$request->id_doc->extension();
          $user->id_doc = $request->id_doc->storeAs('documents', $filename, 'public');
        }

        if($request->hasFile('home_doc')) {
          $filename = (time()+2).'.'.$request['home_doc']->extension();
          $user->home_doc = $request->home_doc->storeAs('documents', $filename, 'public');
        }

        if($request->hasFile('npwp_doc')) {
          $filename = (time()+2).'.'.$request['npwp_doc']->extension();
          $user->npwp_doc = $request->npwp_doc->storeAs('documents', $filename, 'public');
        }

        if($request->hasFile('employment_salary_slip')) {
          $filename = (time()+3).'.'.$request['employment_salary_slip']->extension();
          $user->employment_salary_slip = $request->employment_salary_slip->storeAs('documents', $filename, 'public');
        }

        $user->save();

        return back()->with('success','Data berhasil disimpan');
    }

    public function account()
    {
        $user = Auth::user();
        return view('account',compact('user'));
    }

    public function accountUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
          'avatar' => 'image|mimes:jpeg,gif,jpg,gif,svg|max:2048',
          'email' => ['required',Rule::unique('users')->ignore($user->id),'email'],
          'password' => 'nullable|confirmed|min:6'
        ]);

        $user->fill($request->except('password'));

        if($request->hasFile('avatar')) {
          $filename = time().'.'.$request->avatar->extension();
          $avatar = $request->avatar->storeAs('avatars', $filename, 'public');
          $user->avatar = 'avatars/'.$filename;
        }

        if($request->filled('password')) {
          $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return back()->with('success','Data berhasil disimpan');
    }

    public function profile_bank()
    {
        $user = Auth::user();
        $banks = Bank::pluck('name','id');
        return view('profile_bank',compact('user','banks'));
    }

    public function update_bank(Request $request)
    {
        $user = Auth::user();

        $request->validate([
          'bank_id' => 'required',
          'account_name' => 'string',
          'account_number' => 'numeric'
        ]);

        $bank_account = $user->bankAccount ? $user->bankAccount : new BankAccount;
        $bank_account->bank_id = $request->bank_id;
        $bank_account->account_name = $request->account_name;
        $bank_account->account_number = $request->account_number;
        $bank_account->user_id = $user->id;
        $bank_account->save();

        if($url = Session::get('backUrl')) {
          $request->session()->forget('backUrl');
          return redirect($url)->with('success','Data berhasil disimpan');
        } else {
          return back()->with('success','Data berhasil disimpan');
        }
    }

    public function test() {
      $due_date_days = Carbon::now()->addDays(config('installments_email_reminder_due_days'))->format('Y-m-d');
      $unpaid_due_installments = DB::table('installments')
            ->where('installmentable_type','App\\Loan')
            ->where('paid',0)
            ->whereDate('due_date','<',$due_date_days);
      if($unpaid_due_installments->count() > 0) {
          foreach ($unpaid_due_installments->get() as $installment) {
            $loan = Loan::find($installment->installmentable_id);
            Mail::to($loan->user)->send(new InstallmentReminderEmail($loan));
          }
      }
    }
}

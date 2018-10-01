<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\UserVa;
use App\Loan;
use App\LoanTenor;
use App\Company;
use App\Transaction;
use App\Installment;
use App\Investment;
use App\Status;
use Carbon\Carbon;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\CompleteBankAccount;
use App\Events\LoanAccepted;
use App\Events\LoanDeclined;
use App\Libraries\Midtrans;
use App\Libraries\JsonResponse;
use Encore\Admin\Config\Config;
use PDF;
use App\Events\CashOutLoan;
use App\Events\CashInInstallment;
use App\Events\CashOutInstallment;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:borrower');
        $this->middleware(CompleteBankAccount::class)->only('acceptLoan');
    }

    public function index()
    {
        $loans = Auth::user()->loans()->orderBy('id', 'desc')->paginate(10);
        return view('loans.index',compact('loans'));
    }

    public function create()
    {
        $tenors = LoanTenor::active()->orderBy('month', 'ASC')->pluck('month','id');
        return view('loans.create',compact('tenors'));
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
          'amount_requested' => 'required|numeric'
        ]);

        // Static variables
        $user = Auth::user();
        $amount_requested = $request->input('amount_requested');

        // Check for pending / active loans
        $pending_loans = $user->loans()->where('status_id', ['1','2'])->count();
        $active_loans = $user->loans()->where('status_id', '3')->count();
        if($pending_loans > 0) {
            activity()
               ->withProperties($request->all())
               ->log('Try to Create New Loan but Another Loan still in Approving Process');
            return redirect('member/loans')->with('message','Anda masih mempunyai pinjaman yang masih dalam proses persetujuan. Harap menunggu sebelum mengajukan pinjaman yang baru.');
        } elseif($active_loans > 0) {
            activity()
               ->withProperties($request->all())
               ->log('Try to Create New Loan but User Must Finish Existing Loan');
            return redirect('member/loans')->with('message','Harap menyelesaikan pinjaman Anda sebelum mengajukan pinjaman yang baru');
        }

        $max_loan = config('max_loan_limit');
        $tenor = LoanTenor::find($request->input('tenor_id'));

        // Maximum loan validation
        if ($amount_requested > $max_loan) {
            activity()
               ->withProperties($request->all())
               ->log('Try to Create New Loan but Limit Loan');
          return back()->with('error','Jumlah pinjaman lebih besar dari limit yang diperbolehkan');
        }

        // Create object
        $loan = new Loan;
        $loan->amount_requested = $amount_requested;
        $loan->loan_tenor_id = $request->input('tenor_id');
        $loan->date_expired = Carbon::now()->addDays(7);
        $loan->description = $request->input('description');
        $loan->user_id = $user->id;
        $loan->status_id = Status::where('name','pending')->first()->id;
        $loan->save();
        activity()
           ->withProperties($request->all())
           ->performedOn($loan)
           ->log('Create Loan');

        // Assign grade and rates
        $loan->loan_grade_id = $loan->validateGrade()->id;
        $loan->save();
        $loan->calculateRates();

        return redirect('member/loans/'.$loan->id)->with('success','Pengajuan pinjaman berhasil disimpan');
    }

    public function paymentConfirmation($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_payment' => 'required|image|mimes:jpeg,gif,jpg,gif,svg,png|max:10048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(
                        'error',
                        $validator->errors()->first()
                    );
        }

        $filename = (time()+1).'.'.$request['image_payment']->extension();
        $installment = Installment::findOrFail($id);

        DB::beginTransaction();

        if ($installment->installmentable_type == 'App\Loan') {
            $loan = Loan::find($installment->installmentable_id);
            $investments = Investment::where([
                'loan_id' => $installment->installmentable_id,
                'paid' => 1,
                'status_id' => 3,
            ])
            ->select('id', 'user_id')
            ->get();

            try {
                $hasTransaction = Transaction::where([
                    'user_id' => $loan->user_id, 
                    'transactionable_id' => $installment->id,
                    'transactionable_type' => 'App\Installment',
                ])->first();

                if (empty($hasTransaction)) {
                    $transactionid = Transaction::insertGetId([
                        'amount' => $installment->amount,
                        'user_id' => $loan->user_id,
                        'transactionable_id' => $installment->id,
                        'transactionable_type' => 'App\Installment',
                        'payment_image' => $request['image_payment']->storeAs('documents', $filename, 'public'),
                        'payment_date' => Carbon::now(),
                        'type' => 'Cash In',
                        'status_id' => 1,
                        'created_at' => Carbon::now()
                    ]);

                    $transaction1 = Transaction::find($transactionid);
                    event(new CashInInstallment($transaction1));
                }

                foreach ($investments as $investment) {
                    $investmentInstallment = Installment::where([
                        'installmentable_type' => 'App\Investment',
                        'installmentable_id' => $investment->id,
                        'paid' => 0,
                        'tenor' => $installment->tenor,
                    ])
                    ->first();

                    if (!empty($investmentInstallment)) {

                        $transactionid = Transaction::insertGetId([
                            'amount' => $investmentInstallment->amount,
                            'user_id' => $investment->user_id,
                            'transactionable_id' => $investmentInstallment->id,
                            'transactionable_type' => 'App\Installment',
                            'payment_image' => $request['image_payment']->storeAs('documents', $filename, 'public'),
                            'payment_date' => Carbon::now(),
                            'type' => 'Cash Out',
                            'status_id' => 1,
                            'created_at' => Carbon::now()
                        ]);


                        $transaction2 = Transaction::find($transactionid);
                        event(new CashOutInstallment($transaction2));
                    }
                }

                admin_toastr('Update succeeded!');
            } catch (Exception $e) {
                DB::rollback();
                admin_toastr('Something went wrong please try again ' . (!config('app.debug') ?: $e->getMessage()) );
                return redirect('/admin/installments/'.request()->route('installment').'/edit');
            }
        }else{
            try {
                $investment = Investment::join('loans as l', 'l.id', '=', 'investments.loan_id')
                            ->join('loan_tenors as lt', 'lt.id', '=', 'l.loan_tenor_id')
                            ->select('investments.user_id', 'lt.month', 'investments.amount_invested')
                            ->find($installment->installmentable_id);
                $hasTransaction = Transaction::where([
                    'user_id' => $investment->user_id, 
                    'transactionable_id' => $installment->id,
                    'transactionable_type' => 'App\Installment',
                ])->first();

                $installmentAmount = $investment->amount_invested / $investment->month;

                if (empty($hasTransaction)) {
                    $transactionid = Transaction::insertGetId([
                        'amount' => $installmentAmount,
                        'user_id' => $investment->user_id,
                        'transactionable_id' => $installment->id,
                        'transactionable_type' => 'App\Installment',
                        'payment_image' => $request['image_payment']->storeAs('documents', $filename, 'public'),
                        'payment_date' => Carbon::now(),
                        'type' => 'Cash Out',
                        'status_id' => 1,
                        'created_at' => Carbon::now()
                    ]);
                    $transaction3 = Transaction::find($transactionid);
                    event(new CashOutInstallment($transaction3));
                }
            } catch (Exception $e) {
                DB::rollback();
                admin_toastr('Something went wrong please try again ' . (!config('app.debug') ?: $e->getMessage()) );
                return redirect('/admin/installments/'.request()->route('installment').'/edit');
            }
        }

        DB::commit();

        /*try {
            //if(array_key_exists('image_payment', $request)) {
                $filename = (time()+1).'.'.$request['image_payment']->extension();
                $installment->payment_image = $request['image_payment']->storeAs('documents', $filename, 'public');
                $installment->payment_date = Carbon::now();
                $installment->save();                
            //  }
        } catch (Exception $e){ 
            return redirect()->back()->with(
                        'error',
                       'Something went wrong. Please try again. ' .
                       !config(app.debug) ?: $e->getMessage()
                    );
        }*/
        return redirect()->back()->with('success', 'Data berhasil dikirim');
    }

    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        $userId = Auth::user()->id;
        if($loan->user_id !== $userId) { return redirect()->back(); }

        $installmentPaidCount = Installment::where([
                                    'paid' => 1,
                                    'installmentable_id' => $loan->id,
                                    'installmentable_type' => 'App\Loan'
                                ])->count();
        $primaryInstallment = $loan->amount_requested / $loan->tenor->month;
        $balance = $loan->amount_requested - ($primaryInstallment * $installmentPaidCount);
        if ($loan->status_id == 2) {
            $loan->generateInstallments();
        }
        
        $midtrans        = new Midtrans;
        $installments    = $loan->installments;
        $investments     = $loan->investments;

        $currentPendingInstallment = array();
        foreach ($installments as $installment) {
            if ($installment->paid == 0){
                $amount = intval($installment->amount);
                if (!empty($currentPendingInstallment['due_date'])) {
                    if ($currentPendingInstallment['due_date'] > $installment->due_date->timestamp) {
                        $currentPendingInstallment['id']       = $installment->id;
                        $currentPendingInstallment['amount']   = $amount;
                        $currentPendingInstallment['tenor']    = $installment->tenor;
                        $currentPendingInstallment['due_date'] = $installment->due_date->timestamp;
                    }
                }else{
                    $currentPendingInstallment['id']       = $installment->id;
                    $currentPendingInstallment['amount']   = $amount;
                    $currentPendingInstallment['tenor']    = $installment->tenor;
                    $currentPendingInstallment['due_date'] = $installment->due_date->timestamp;
                }
            }
        }
        $userVa = '';

        if (!empty($currentPendingInstallment['id'])) {
            /*$checkHasPayment = $this->checkHasPayment($currentPendingInstallment);

            //return (array) $currentPendingInstallment;

            $userVa = UserVa::getByUserId($userId)->first();

            if (empty($userVa)) {
                $vaId = UserVa::insertGetId([
                    'user_id'    => $userId,
                    'va_number'  => $checkHasPayment->va_numbers{0}->va_number,
                    'created_at' => Carbon::now()->toDateTimeString()
                ]);
                $userVa = UserVa::getByUserId($userId)->first();
            }*/
        }

        return view('loans.view',compact('loan','installments','investments', 'userVa', 'currentPendingInstallment', 'midtrans', 'balance'));
    }

    public function viewDoc($id)
    {
        $loan = Loan::findOrFail($id);
        view()->share('loan',$loan);
        $html = \View::make('pdf.loan')->with('loan', $loan);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
        // return $pdf->stream('pohondana_pengajuan_pinjaman_'.$loan->id.'.pdf');
    }

    public function acceptLoan($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->accepted_at = Carbon::now();
        $loan->date_expired = Carbon::now()->addDays(7);
        $loan->save();
        event(new LoanAccepted($loan));

        if($this->generateInstallments($loan)) {
            if($loan->user_id !== Auth::user()->id) { return redirect()->back(); }
            $loan->status_id = Status::where('name','accepted')->first()->id;
            $loan->save();

            $hasTransaction = Transaction::where([
                'user_id' => $loan->user_id, 
                'transactionable_id' => $loan->id,
                'transactionable_type' => 'App\Loan',
            ])->first();

            if (empty($hasTransaction)) {
                $transactionid = Transaction::insertGetId([
                    'amount' => $loan->amount_borrowed,
                    'user_id' => $loan->user_id,
                    'transactionable_id' => $loan->id,
                    'transactionable_type' => 'App\Loan',
                    'type' => 'Cash Out',
                    'status_id' => 1,
                    'created_at' => Carbon::now()
                ]);

                $transaction3 = Transaction::find($transactionid);
                event(new CashOutLoan($transaction3));
            }

            activity()
               ->performedOn($loan)
               ->log('Accept Loan'); 
            return redirect('member/loans/'.$id)->with('message','Terima kasih, silahkan tunggu dana dikirimkan langsung ke rekening Anda');
        }
    }

    public function generateInstallments($loan)
    {
        if($loan->generateInstallments()) { 
            return true;
        }else { 
            return true; 
        }
    }

    public function declineLoan($id)
    {
        $loan = Loan::findOrFail($id);
        if($loan->user_id !== Auth::user()->id) { return redirect()->back(); }
        $loan->status_id = Status::where('name','declined')->first()->id;
        $loan->save();
        event(new LoanDeclined($loan));
        activity()
               ->performedOn($loan)
               ->log('Decline Loan');   
        return redirect('member/loans/'.$id)->with('message','Terima kasih');
    }

    private function checkHasPayment($paymentData)
    {
        $userName  = explode(" ", Auth::user()->name);
        $userEmail = Auth::user()->email;
        $userPhone = Auth::user()->mobile_phone;
        $firstName = $userName[0];
        $lastName  = '';
        $amount    = intval($paymentData['amount']);

        try {
            $lastName = $userName[1];
        } catch (\Exception $e) { }

        $data['payment_type']                        = 'bank_transfer';
        $data['bank_transfer']['bank']               = "bca";
        $data['bank_transfer']['va_number']          = substr_replace($userPhone, '', 10, strlen($userPhone));
        $data['transaction_details']['order_id']     = $paymentData['id'];
        $data['transaction_details']['gross_amount'] = $amount;
        $data['item_details'][0]['id']               = $paymentData['id'];
        $data['item_details'][0]['price']            = $amount;
        $data['item_details'][0]['quantity']         = "1";
        $data['item_details'][0]['name']             = 'Tenor ke-' . $paymentData['tenor'];
        $data['customer_details']['email']           = $userEmail;
        $data['customer_details']['first_name']      = $firstName;
        $data['customer_details']['last_name']       = $lastName;
        $data['customer_details']['phone']           = $userPhone;

        $midtrans = new Midtrans;
        $response = json_decode($midtrans->remote($midtrans->getBaseUrl('charge'), $data, 1));

        return $response;
    }

}
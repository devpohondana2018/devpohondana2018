<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Exception;
use PDF;
use Carbon\Carbon;
use App\Loan;
use App\LoanTenor;
use App\Installment;
use App\Investment;
use App\Status;
use App\UserVa;
use App\Transaction;
use App\Libraries\Midtrans;
use App\Libraries\JsonResponse;
use App\Http\Middleware\CompleteBankAccount;
use App\Events\InvestmentAccepted;
use App\Events\CashInInvestment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;

class InvestmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:lender');
        $this->middleware(CompleteBankAccount::class)->only('acceptInvestment');
    }

    public function index()
    {
        $investmentsQuery = Auth::user()->investments()->orderBy('created_at', 'DESC');
        $investCount = $investmentsQuery->count();
        $investments = $investmentsQuery->get();
        return view('investments.index',compact('investments', 'investCount'));
    }

    public function show($id)
    {
        $investment = Investment::findOrFail($id);
        if($investment->user_id !== Auth::user()->id) { return redirect()->back(); }

        //return UserVa::user($investment->user_id);

        $currentPendingInstallment['id'] = $investment->id;
        $currentPendingInstallment['amount'] = $investment->amount_invested;


        $midtrans = new Midtrans;
        /*if ($investment->paid == 0) {
            $checkHasPayment = $this->checkHasPayment($currentPendingInstallment);

            //return (array) $checkHasPayment;

            //return (array) $checkHasPayment;
            $userVa = UserVa::user($investment->user_id)->first();

            if (empty($userVa)) {
                try {
                    $userVa = new UserVa;
                    $userVa->user_id = $investment->user_id;
                    $userVa->va_number = $checkHasPayment->va_numbers{0}->va_number;
                    $userVa->save();
                } catch (Exception $e){ 
                    return (array) $checkHasPayment;
                }
            }
        }*/

        return view('investments.view',compact('investment', 'userVa', 'midtrans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tenors = LoanTenor::pluck('month','id');
        return view('investments.create',compact('tenors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
          'amount_invested' => 'required|numeric'
        ]);

        // Create object
        $investment = new Investment;
        $investment->amount_invested = $request->amount_invested;
        $investment->user_id = Auth::user()->id;
        $investment->status_id = Status::where('name','pending')->first()->id;
        $investment->save();
        activity()
               ->log('Make Investment');
        return redirect('member/investments/'.$investment->id)->with('success','Pengajuan pendanaan berhasil disimpan');
    }

    public function download()
    {
        $investments = Auth::user()->investments;
        view()->share('investments',$investments);
        $html = \View::make('pdf.investment')->with('investments', $investments);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
    }

    public function viewDoc($id)
    {
        $investment = Investment::findOrFail($id);
        view()->share('investment',$investment);
        //$pdf = PDF::loadView('pdf.loan');
        $html = \View::make('pdf.perjanjian_pendanaan')->with('investment', $investment);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
       /* return $loan;
        return $pdf->stream('pohondana_pengajuan_pinjaman_'.$loan->id.'.pdf');*/
    }

    public function acceptInvestment($id)
    {
         DB::beginTransaction();

        try {
          $investment = Investment::findOrFail($id);
          if($investment->user_id !== Auth::user()->id) { return redirect()->back(); }
              $investment->status_id = Status::where('name','accepted')->first()->id;
              $investment->save();

              /*$loan = Loan::findOrFail($investment->loan_id);
              $loan->amount_funded += $investment->amount_invested;
              $loan->save();*/
            activity()
                   ->log('Accept Investment');

              event(new InvestmentAccepted($investment));
          } catch (Exception $e) {
              activity()
                   ->log('Failed Accept Investment');
              DB::rollback();
              return back()->with('error', 'Terjadi kesalahan saat mengirim data. Silahkan ulangi kembali.');
          }

          DB::commit();

          return redirect('member/investments/'.$id)->with('message','Terima kasih, silahkan melakukan pembayaran ke Pohon Dana');
    }

    public function declineInvestment($id)
    {
        $investment = Investment::findOrFail($id);
        if($investment->user_id !== Auth::user()->id) { return redirect()->back(); }
        $investment->status_id = Status::where('name','declined')->first()->id;
        $investment->save();
        activity()
               ->log('Decline Investment');
        return redirect('member/investments/'.$id)->with('message','Terima kasih');
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

        $investment = Investment::findOrFail($id);
        $filename = (time()+1).'.'.$request['image_payment']->extension();

        DB::beginTransaction();

        try {

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
                    'payment_image' => $request['image_payment']->storeAs('documents', $filename, 'public'),
                    'payment_date' => Carbon::now(),
                    'type' => 'Cash In',
                    'status_id' => 1,
                    'created_at' => Carbon::now()
                ]);

                $transaction3 = Transaction::find($transactionid);
                event(new CashInInvestment($transaction3));
            }
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(
                        'error',
                       'Something went wrong. Please try again. ' .
                       !config('app.debug') ?: $e->getMessage()
                    );
        }

        DB::commit();
        return redirect()->back()->with('success', 'Data berhasil dikirim');
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
        $data['transaction_details']['order_id']     = $paymentData['id'] . '-inves';
        $data['transaction_details']['gross_amount'] = $amount;
        $data['item_details'][0]['id']               = $paymentData['id'];
        $data['item_details'][0]['price']            = $amount;
        $data['item_details'][0]['quantity']         = "1";
        $data['item_details'][0]['name']             = 'Pembayaran pendanaan';
        $data['customer_details']['email']           = $userEmail;
        $data['customer_details']['first_name']      = $firstName;
        $data['customer_details']['last_name']       = $lastName;
        $data['customer_details']['phone']           = $userPhone;

        $midtrans = new Midtrans;
        $response = json_decode($midtrans->remote($midtrans->getBaseUrl('charge'), $data, 1));

        return $response;
    }
}

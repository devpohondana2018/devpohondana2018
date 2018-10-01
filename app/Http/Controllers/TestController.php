<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Validator;
use App\Loan;
use App\User;
use App\Status;
/*use App\UserVa;*/
use App\Transaction;
use App\Installment;
use App\HasUserReminder;
use App\Investment;
use App\Events\LoanAccepted;
use Mail;
use App\Mail\InstallmentReminderNonRekananEmail;
use App\Mail\InstallmentReminderRekananEmail;
use App\Libraries\Midtrans;
use App\Libraries\JsonResponse;
use App\Events\LoanRejected;
/*use App\Events\InvestmentRejected;*/
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Config\Config;
use App\Events\CashInInstallment;
use App\Events\CashOutInstallment;
use App\Events\CashOutLoan;
use App\Events\CashInInvestment;

class TestController extends Controller
{
    public function viewMailReminder()
    {
        $dateFormat = 'Y-m-d';
        $due_date_days = Carbon::now()->format('Y-m-d');

        //return $due_date_days;

        $unpaid_due_installments = DB::table('installments')
            ->where('installmentable_type','App\\Loan')
            ->where('paid', 0);


        if($unpaid_due_installments->count() > 0) {
            $dueDateTemp = array();
            $userTemp = array();
            foreach ($unpaid_due_installments->get() as $installment) {
                $dueDateArray = array();
                $dueDate = Carbon::createFromFormat('Y-m-d', $installment->due_date);
                array_push($dueDateArray, $dueDate->format($dateFormat));
                array_push($dueDateArray, $dueDate->addDays(-3)->format($dateFormat));
                array_push($dueDateArray, $dueDate->addDays(-4)->format($dateFormat));

                array_push($dueDateTemp, $dueDateArray);

                $hasDueDate = in_array($due_date_days, $dueDateArray);

                if ($hasDueDate) {
                    $loan = Loan::find($installment->installmentable_id);
                    $user = User::join('companies as c', 'c.id', '=', 'users.company_id')
                          ->select('users.id', 'c.name', 'c.affiliate')
                          ->find($loan->user_id);

                    array_push($userTemp, [$loan->user_id, $due_date_days, $loan->id]);

                    if (!empty($user)) {

                        $hasReminder = HasUserReminder::where([
                            'user_id' => $user->id,
                            'loan_id' => $loan->id,
                            'created_at' => $due_date_days
                        ])
                        ->count();

                        if ($hasReminder < 1) {
                            HasUserReminder::insert([
                                'user_id' => $user->id,
                                'loan_id' => $loan->id,
                                'created_at' => $due_date_days
                            ]);

                            if ($user->affiliate == 1) {
                                Mail::to($loan->user)->send(new InstallmentReminderRekananEmail($loan, $installment));
                                return view('email.installment_reminder_rekanan', compact('loan', 'installment'));
                            }else{
                                //return $user->id;
                                $midtrans = new Midtrans;
                                $currentPendingInstallment['userName']  = $user->name;
                                $currentPendingInstallment['userEmail'] = $user->email;
                                $currentPendingInstallment['userPhone'] = $user->mobile_phone;
                                $currentPendingInstallment['id']       = $installment->id;
                                $currentPendingInstallment['amount']   = intval($installment->amount);
                                $currentPendingInstallment['tenor']    = $installment->tenor;
                                $currentPendingInstallment['due_date'] = $installment->due_date;
                                //$checkHasPayment = $this->checkHasPayment($currentPendingInstallment);

                                $userVa = "";
                                /*$userId = $user->id;
                                $userVa = UserVa::getByUserId($userId)->first();

                                if (empty($userVa)) {
                                    $vaId = UserVa::insertGetId([
                                        'user_id'    => $userId,
                                        'va_number'  => $checkHasPayment->va_numbers{0}->va_number,
                                        'created_at' => Carbon::now()->toDateTimeString()
                                    ]);
                                    $userVa = UserVa::getByUserId($userId)->first();
                                }*/

                                Mail::to($loan->user)->send(new InstallmentReminderNonRekananEmail($loan, $installment, $userVa, $midtrans));
                                return view('email.installment_reminder_nonrekanan', compact('loan', 'installment', 'userVa', 'midtrans'));
                            }
                        }

                    }
                }
            }
        }

        /*$loan = Loan::first();
        //return compact('loan');
        return view('email.installment_reminder_rekanan', compact('loan'));*/
    }

    public function sampleSendLoanAccepted($id)
    {
        $loan = Loan::findOrFail($id);
        event(new LoanAccepted($loan));
    }

    public function sampleSendLoanReject($id)
    {
        $loan = Loan::findOrFail($id);
        //event(new LoanRejected($loan));
        return view('email.loan_rejected', compact('loan'));

        /*Mail::send('email.test', [], function($message){
            $message->to('irsal.firdaus@gmail.com')->subject('Test SMTP');
            $message->from('notification@pohondana.id', 'Pohondana'); 
        });*/
        //return redirect('member/loans/'.$id)->with('message','Terima kasih, silahkan tunggu dana dikirimkan langsung ke rekening Anda');
    }

    public function sampleSendInvesmentReject($id)
    {
        /*$investment = Investment::findOrFail($id);
        event(new InvestmentRejected($investment));
        return view('email.investment_rejected', compact('investment')); */
    }

    public function sampleMultipleEmail()
    {

       /* $configEmail = explode(";", config('loan_rejected_email_notification'));
        $adminEmail = explode(";", config('email_admin'));
        $ccEmail = array_merge($configEmail, $adminEmail);
        return $ccEmail;*/
    }

    private function checkHasPayment($paymentData)
    {
        $userName  = $paymentData['userName'];
        $userEmail = $paymentData['userEmail'];
        $userPhone = $paymentData['userPhone'];
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

    public function sampleInvestmentExpired()
    {
        $investments = Investment::pendingInstallment()->get();

        foreach ($investments as $investment) {
            DB::beginTransaction();

            try {
                $loan = Loan::find($investment->loan_id);
                if (!empty($loan)) {
                    $loan->amount_funded -= $investment->amount_invested;
                    $loan->save();
                }
                $investment->status_id = Status::select('id')->where('name', 'cancelled')->first()->id;
                //$investment->deleted_at = Carbon::now();
                $investment->save();
            } catch (Exception $e) { 
                return $e->getMessage();
                DB::rollback();
            }

            DB::commit();
        }

        return $investments;
    }

    public function testCashInInstallment()
    {
        $transaction = Transaction::find(35);
        event(new CashInInstallment($transaction));
    }

    public function testCashOutInstallment()
    {
        $transaction = Transaction::find(35);
        event(new CashOutInstallment($transaction));
    }

    public function testCashOutLoan()
    {
        $transaction = Transaction::find(35);
        event(new CashOutLoan($transaction));
    }

    public function testCashInInvestment()
    {
        $transaction = Transaction::find(35);
        event(new CashInInvestment($transaction));
    }


    public function encrypt()
    {
       $hashs = DB::table('bank_accounts')
                ->select('account_number','id')
                ->get();
        // dd($hashs);
        foreach ($hashs as $hash) {
            $bank = \App\BankAccount::find($hash->id);
            // dd($hash->account_number);
            $bank->account_number = encrypt($hash->account_number);
            $bank->save();
        }

    }
}

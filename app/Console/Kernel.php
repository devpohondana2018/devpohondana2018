<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Encore\Admin\Config\Config;
use Carbon\Carbon;
use App\Loan;
use App\User;
/*use App\UserVa;*/
use App\Status;
use App\Installment;
use App\Investment;
use App\HasUserReminder;
use App\Libraries\Midtrans;
use App\Libraries\JsonResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;
use App\Mail\InstallmentReminderEmail;
use App\Mail\InstallmentReminderNonRekananEmail;
use App\Mail\InstallmentReminderRekananEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Pending rengga: aktifin backup ketika live
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('02:00');
        /*$schedule->command('foo')
         ->cron('* * * * * *')
         ->appendOutputTo('reminder_log.txt');*/

         
        //CHECK PENDING INVESMENT PAYMENT
        /*$schedule->call(function () {
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
        })
        ->everyMinute();*/

        // Check due installments
         $schedule->call(function () {
            $dateFormat = 'Y-m-d';
            $due_date_days = Carbon::now()->format('Y-m-d');

            //return $due_date_days;

            $unpaid_due_installments = DB::table('installments')
                ->join('loans as l', 'l.id', '=', 'installments.installmentable_id')
                ->where('installmentable_type','App\\Loan')
                ->where('paid', 0)
                ->where('l.status_id', '3');


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
                        $loan = Loan::where('status_id', 3)->find($installment->installmentable_id);

                        if (!empty($loan)) {
                            $user = User::join('companies as c', 'c.id', '=', 'users.company_id')
                                  ->select('users.id', 'c.name', 'c.affiliate')
                                  ->find($loan->user_id);

                            array_push($userTemp, [$loan->user_id, $due_date_days, $loan->id]);

                            if (!empty($user)) {

                                $hasReminder = HasUserReminder::where([
                                                    'user_id' => $user->id,
                                                    'loan_id' => $loan->id,
                                                    'created_at' => $due_date_days
                                                ])->count();

                                if ($hasReminder < 1) {
                                    HasUserReminder::insert([
                                        'user_id' => $user->id,
                                        'loan_id' => $loan->id,
                                        'created_at' => $due_date_days
                                    ]);

                                    if ($user->affiliate == 1) {
                                        $configEmail = explode(";", config('email_reminder'));
                                        Mail::to($loan->user)
                                            ->cc($configEmail)
                                            ->queue(new InstallmentReminderRekananEmail($loan, $installment));
                                        //return view('email.installment_reminder_rekanan', compact('loan', 'installment'));
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

                                        $configEmail = explode(";", config('email_reminder'));
                                        Mail::to($loan->user)
                                            ->cc($configEmail)
                                            ->queue(new InstallmentReminderNonRekananEmail($loan, $installment, $userVa, $midtrans));
                                        //return view('email.installment_reminder_nonrekanan', compact('loan', 'installment', 'userVa', 'midtrans'));
                                    }
                                }

                            }   
                        }
                    }
                }
            }
        })
        ->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
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
}

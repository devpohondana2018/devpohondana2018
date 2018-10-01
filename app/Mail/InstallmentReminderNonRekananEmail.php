<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Loan;
use App\User;
/*use App\UserVa;*/
use App\Installment;
use App\Libraries\Midtrans;

class InstallmentReminderNonRekananEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $installment;
    public $userVa;
    public $midtrans;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Loan $loan, $installment, /*UserVa*/ $userVa, Midtrans $midtrans)
    {
        $this->loan = $loan;
        $this->installment =  $installment;
        $this->userVa = $userVa;
        $this->midtrans = $midtrans;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment Reminder ' . $this->loan->user->name)
                    ->view('email.installment_reminder_nonrekanan');
    }
}

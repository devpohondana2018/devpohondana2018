<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Loan;
use App\User;
use App\UserVa;
use App\Installment;
use App\Libraries\Midtrans;

class InstallmentReminderRekananEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $installment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Loan $loan, $installment)
    {
        $this->loan = $loan;
        $this->installment =  $installment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment Reminder ' . $this->loan->user->name)
                    ->view('email.installment_reminder_rekanan');
    }
}

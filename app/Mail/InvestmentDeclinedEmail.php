<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Investment;

class InvestmentDeclinedEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $investment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Investment Declined')->view('email.investment_declined');
    }
}

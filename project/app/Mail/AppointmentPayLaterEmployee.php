<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentPayLaterEmployee extends Mailable
{
    use Queueable, SerializesModels;


    public $subject;
    public $event;
    public $user;
    public $employee;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$event,$user,$employee)
    {
        $this->subject=$subject;
        $this->event=$event;
        $this->user=$user;
        $this->employee=$employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->markdown('emails.payLaterEmployee');
    }
}

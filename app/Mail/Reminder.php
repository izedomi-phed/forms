<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reminder extends Mailable
{
    use Queueable, SerializesModels;

    public $form_title;
    public $approval_link;
    public $send_to;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($form_title, $approval_link, $send_to = null)
    {
        //return $request_type;
        //
        $this->form_title = $form_title;
        $this->approval_link = $approval_link;
        $this->send_to = $send_to;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.reminder');
    }
}

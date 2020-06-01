<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Declined extends Mailable
{
    use Queueable, SerializesModels;

    public $form_title;
    public $approver_action;
    public $comment;
    public $staff_id;
    public $staff_name;
    public $approver_name_role;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($form_title, $approver_action, $comment, $staff_id, $staff_name, $approver_name_role)
    {
        //
        $this->form_title = $form_title;
        $this->approver_action = $approver_action;
        $this->comment = $comment;
        $this->staff_id = $staff_id;
        $this->staff_name = $staff_name;
        $this->approver_name_role = $approver_name_role;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.declined');
    }
}

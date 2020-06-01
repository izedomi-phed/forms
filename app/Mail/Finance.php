<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Finance extends Mailable
{
    use Queueable, SerializesModels;

    public $form_title;
    public $approver_action;
    public $comment;
    public $staff_id;
    public $staff_name;
    public $approver_name_role;
    public $approver_link;
    public $isFinalApprover;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($form_title, $approver_action, $comment, $staff_id, $staff_name, $approver_name_role, $approver_link, $isFinalApprover)
    {
        //
        $this->form_title = $form_title;
        $this->approver_action = $approver_action;
        $this->comment = $comment;
        $this->staff_id = $staff_id;
        $this->staff_name = $staff_name;
        $this->approver_name_role = $approver_name_role;
        $this->approver_link = $approver_link;
        $this->isFinalApprover = $isFinalApprover;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.finance');
    }
}

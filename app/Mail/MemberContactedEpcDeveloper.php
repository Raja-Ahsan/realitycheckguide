<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ClientContact;
use App\Models\User;

class MemberContactedEpcDeveloper extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $user;

    public function __construct(ClientContact $contact, User $user)
    {
        $this->contact = $contact;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Member Contacted EPC Developer')
            ->view('emails.member_contacted_epc_developer')
            ->with([
                'contact' => $this->contact,
                'user' => $this->user,
            ]);
    }
}

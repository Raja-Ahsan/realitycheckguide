<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\MemberDirectory;
use App\Models\User;

class MemberDirectoryUpdatedToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $memberDirectory;
    public $updater;

    public function __construct(MemberDirectory $memberDirectory, User $updater)
    {
        $this->memberDirectory = $memberDirectory;
        $this->updater = $updater;
    }

    public function build()
    {
        return $this->subject('Member Directory Updated: ' . $this->memberDirectory->title)
            ->view('emails.member_directory_updated_to_admin')
            ->with([
                'memberDirectory' => $this->memberDirectory,
                'updater' => $this->updater,
            ]);
    }
}

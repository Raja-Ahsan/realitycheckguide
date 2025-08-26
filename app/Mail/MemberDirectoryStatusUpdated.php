<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\MemberDirectory;

class MemberDirectoryStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $memberDirectory;
    public $rejectionReason;

    public function __construct(MemberDirectory $memberDirectory, $rejectionReason = null)
    {
        $this->memberDirectory = $memberDirectory;
        $this->rejectionReason = $rejectionReason;
    }

    public function build()
    {
        return $this->subject('Member Directory Status Updated')
            ->view('emails.member_directory_status_updated')
            ->with([
                'memberDirectory' => $this->memberDirectory,
                'rejectionReason' => $this->rejectionReason,
            ]);
    }
}

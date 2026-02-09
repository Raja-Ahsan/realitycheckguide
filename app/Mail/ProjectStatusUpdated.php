<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project, $reason = null)
    {
        $this->project = $project;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Project Status Updated')
            ->view('emails.project_status_updated')
            ->with([
                'project' => $this->project,
                'reason' => $this->reason,
            ]);
    }
}

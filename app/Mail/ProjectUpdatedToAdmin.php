<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use App\Models\User;

class ProjectUpdatedToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $updater;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Project $project, User $updater)
    {
        $this->project = $project;
        $this->updater = $updater;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Project Updated: ' . $this->project->name)
                    ->view('emails.project_updated_to_admin')
                    ->with([
                        'project' => $this->project,
                        'updater' => $this->updater,
                    ]);
    }
}

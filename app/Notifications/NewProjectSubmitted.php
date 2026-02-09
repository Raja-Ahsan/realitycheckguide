<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProjectSubmitted extends Notification
{
    use Queueable;

    public $project;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($project, $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('New Project Submitted for Approval')
            ->view(
                'emails.project_submitted_to_admin',
                [
                    'user' => $this->user,
                    'project' => $this->project,
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'project_update',
            'message' => 'New project "' . $this->project->name . '" submitted by ' . $this->user->name . ' (' . $this->user->getRoleNames()->first() . ')',
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
        ];
    }
}

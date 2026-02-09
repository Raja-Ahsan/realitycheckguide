<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $project;
    protected $reason;

    public function __construct($project, $reason = null)
    {
        $this->project = $project;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = 'Your project "' . $this->project->name . '" status was updated to <b>' . ucfirst($this->project->status) . '</b>.';
        if ($this->project->status === 'rejected' && $this->reason) {
            $message .= '<br><b>Reason:</b> ' . e($this->reason);
        }
        return [
            'type' => 'project_update',
            'message' => $message,
            'project_id' => $this->project->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            //
        ];
    }
}


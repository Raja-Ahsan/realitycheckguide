<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ProjectUpdatedNotification extends Notification
{
    use Queueable;

    protected $project;
    protected $updater;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($project, User $updater)
    {
        $this->project = $project;
        $this->updater = $updater;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return null;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'project_update',
            'message' => $this->updater->name . ' ' . $this->updater->last_name . ' (' . $this->updater->role . ') has updated project: "' . $this->project->name . '.',
            'project_id' => $this->project->id,
            'updater_id' => $this->updater->id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            //
        ];
    }
}

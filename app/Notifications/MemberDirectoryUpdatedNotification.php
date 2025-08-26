<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class MemberDirectoryUpdatedNotification extends Notification
{
    use Queueable;

    protected $memberDirectory;
    protected $updater;

    /**
     * Create a new notification instance.
     */
    public function __construct($memberDirectory, User $updater)
    {
        $this->memberDirectory = $memberDirectory;
        $this->updater = $updater;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'member_update',
            'message' => $this->updater->name . ' ' . $this->updater->last_name . ' (' . $this->updater->role . ') has updated member directory entry: "' . $this->memberDirectory->title . '.',
            'member_id' => $this->memberDirectory->id,
            'updater_id' => $this->updater->id,
        ];
    }

    /**
     * Optionally return mail message (not used here).
     */
    public function toMail($notifiable)
    {
        return null;
    }

    public function toArray($notifiable)
    {
        return [];
    }
}

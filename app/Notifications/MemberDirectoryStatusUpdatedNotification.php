<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MemberDirectory;

class MemberDirectoryStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $memberDirectory;
    protected $rejectionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(MemberDirectory $memberDirectory, $rejectionReason = null)
    {
        $this->memberDirectory = $memberDirectory;
        $this->rejectionReason = $rejectionReason;
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
        $message = 'Your member directory entry "' . $this->memberDirectory->title . '" status was updated to <b>' . ucfirst($this->memberDirectory->status) . '</b>.';

        if ($this->memberDirectory->status === 'rejected' && $this->rejectionReason) {
            $message .= '<br><b>Reason:</b> ' . e($this->rejectionReason);
        }

        return [
            'type' => 'member_update',
            'message' => $message,
            'member_id' => $this->memberDirectory->id,
        ];
    }

    /**
     * Optional email version.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}

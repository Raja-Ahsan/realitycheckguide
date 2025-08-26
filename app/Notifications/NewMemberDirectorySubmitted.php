<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewMemberDirectorySubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $memberDirectory;
    public $user;

    public function __construct($memberDirectory, $user)
    {
        $this->memberDirectory = $memberDirectory;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Member Directory Submitted for Approval')
            ->view('emails.member_directory_submitted_to_admin', [
                'user' => $this->user,
                'member' => $this->memberDirectory
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'member_update',
            'message' => 'New member "' . $this->memberDirectory->title . '" submitted by ' . $this->user->name . ' (' . $this->user->getRoleNames()->first() . ')',
            'member_id' => $this->memberDirectory->id,
            'user_id' => $this->user->id,
        ];
    }
}

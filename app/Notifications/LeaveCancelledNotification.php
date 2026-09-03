<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveCancelledNotification extends Notification
{
    use Queueable;

    public $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'leave_id' => $this->leave->id,
            'message' => "{$this->leave->user->name} has cancelled his pending leave request for {$this->leave->from_date->format('d F')} to {$this->leave->to_date->format('d F Y')}.",
            'type' => 'cancelled',
            'icon' => 'trash'
        ];
    }
}

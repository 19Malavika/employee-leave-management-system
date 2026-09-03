<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLeaveRequestNotification extends Notification
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
            'message' => "{$this->leave->user->name} has submitted a {$this->leave->leaveType->name} request for {$this->leave->from_date->format('d F')} to {$this->leave->to_date->format('d F Y')}.",
            'type' => 'new_request',
            'icon' => 'calendar'
        ];
    }
}

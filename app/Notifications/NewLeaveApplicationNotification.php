<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLeaveApplicationNotification extends Notification
{
    use Queueable;

    public function __construct(public Leave $leave) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'leave_id' => $this->leave->id,
            'title' => 'New Leave Application Submitted',
            'message' => $this->leave->user->name . ' submitted a leave request for ' . $this->leave->total_days . ' day(s) (' . $this->leave->from_date->format('d M') . ' - ' . $this->leave->to_date->format('d M') . ').',
            'type' => 'leave_applied',
            'url' => route('manager.leaves.index'),
        ];
    }
}

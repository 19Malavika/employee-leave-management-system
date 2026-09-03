<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public Leave $leave, public string $action) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = ucfirst($this->action);
        $reasonText = $this->leave->rejection_reason ? ' Reason: ' . $this->leave->rejection_reason : '';

        return [
            'leave_id' => $this->leave->id,
            'title' => 'Leave Request ' . $statusText,
            'message' => 'Your leave request for ' . $this->leave->total_days . ' day(s) starting ' . $this->leave->from_date->format('d M') . ' has been ' . strtolower($statusText) . '.' . $reasonText,
            'type' => 'leave_' . strtolower($statusText),
            'url' => route('employee.leaves.index'),
        ];
    }
}

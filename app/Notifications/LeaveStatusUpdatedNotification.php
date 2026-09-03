<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveStatusUpdatedNotification extends Notification
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
        $statusStr = $this->leave->status === 'approved' ? 'approved by your manager' : 'rejected';
        $message = "Your {$this->leave->leaveType->name} request for {$this->leave->from_date->format('d F')} to {$this->leave->to_date->format('d F Y')} has been {$statusStr}.";
        
        if ($this->leave->status === 'rejected' && $this->leave->rejection_reason) {
            $message .= " Reason: {$this->leave->rejection_reason}";
        }

        return [
            'leave_id' => $this->leave->id,
            'message' => $message,
            'type' => $this->leave->status, // 'approved' or 'rejected'
            'icon' => $this->leave->status === 'approved' ? 'check' : 'x'
        ];
    }
}

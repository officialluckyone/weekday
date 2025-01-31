<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeadlineNotification extends Notification
{
    use Queueable;

    protected $job;
    protected $title;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($job, $title, $message)
    {
        $this->job = $job;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title'     => $this->title,
            'message'   => $this->message,
            'priority'  => 'Prioritas Tugas Adalah = '.$this->job->priority,
            'status'    => 'Status Tugas Adalah = '.$this->job->status,
            'start'     => $this->job->start,
            'deadline'  => $this->job->deadline,
            'url'       => url('/task/' . $this->job->id),
        ];
    }
}

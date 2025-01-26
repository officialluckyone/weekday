<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Project;

class ProjectDeadlineNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Deadline Proyek Mendekat')
                    ->greeting('Halo!')
                    ->line('Proyek "' . $this->project->name . '" mendekati tenggat waktu.')
                    ->line('Tanggal Selesai: ' . $this->project->end_date)
                    ->action('Lihat Proyek', route('projects.show', $this->project))
                    ->line('Pastikan semua tugas selesai tepat waktu!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
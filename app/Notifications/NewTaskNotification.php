<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;

class NewTaskNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Tugas Baru Ditambahkan')
                    ->greeting('Halo!')
                    ->line('Tugas baru telah ditambahkan ke proyek: ' . $this->task->project->name)
                    ->line('Judul Tugas: ' . $this->task->title)
                    ->action('Lihat Tugas', route('projects.show', $this->task->project))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
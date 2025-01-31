<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use App\Models\Task;
use Carbon\Carbon;
use App\Notifications\TaskDeadlineNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckUserTasks
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event)
    {
        $user = $event->user;
        if (Session::has('notified_' . $user->id)) {
            Log::info("User ID {$user->id} sudah menerima notifikasi pada sesi ini. Tidak mengirim ulang.");
            return;
        }

        $tasks = Task::whereHas('task_user',function($q)use($user){
            $q->where('users.id',$user->id);
        })
        ->where('status','!=','Done')
        ->whereNull('deleted_at')
        ->get();

        foreach($tasks as $task){
            $deadline = Carbon::parse($task->deadline);
            $remainingDays = Carbon::now()->diffInDays($deadline, false);

            if($remainingDays <= 2 && $remainingDays >= 0){
                $title = "Tugas Mendekati Tenggat Waktu";
                $message = 'Tugas "' . $task->title . '" mendekati tenggat waktu pada ' . \Carbon\Carbon::parse($task->deadline)->isoFormat('DD-MMMM-YYYY');
                $user->notify(new TaskDeadlineNotification($task, $title, $message));
                Log::info("Notifikasi dikirim: Task ID {$task->id} mendekati deadline untuk User ID {$user->id}");
            }elseif($remainingDays < 0 ){
                $title = "Tugas Melewati Tenggat Waktu";
                $message = 'Tugas "' . $task->title . '" melewati tenggat waktu pada ' . \Carbon\Carbon::parse($task->deadline)->isoFormat('DD-MMMM-YYYY');
                $user->notify(new TaskDeadlineNotification($task, $title, $message));
                Log::info("Notifikasi dikirim: Task ID {$task->id} sudah melewati deadline untuk User ID {$user->id}");
            }
        }

        Session::put('notified_' . $user->id, true);
    }
}

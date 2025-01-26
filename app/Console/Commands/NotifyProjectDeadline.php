<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Notifications\ProjectDeadlineNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class NotifyProjectDeadline extends Command
{
    protected $signature = 'notify:project-deadline';
    protected $description = 'Mengirim notifikasi untuk proyek yang mendekati tenggat waktu';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();
        $deadlineSoon = $today->copy()->addDays(3); // Misalnya 3 hari sebelum deadline

        $projects = Project::whereDate('end_date', '=', $deadlineSoon->toDateString())->get();

        if($projects->count()) {
            $users = \App\Models\User::all();
            foreach($projects as $project) {
                Notification::send($users, new ProjectDeadlineNotification($project));
            }
            $this->info('Notifikasi deadline proyek telah dikirim.');
        } else {
            $this->info('Tidak ada proyek yang mendekati deadline.');
        }
    }
}
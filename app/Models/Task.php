<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\User;

class Task extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'tasks';

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function task_user()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }
}

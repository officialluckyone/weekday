<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;

class Project extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'projects';

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function task()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}

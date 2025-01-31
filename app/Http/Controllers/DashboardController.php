<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->data['link_active'] = 'dashboard';
    }

    public function index()
    {
        return view('dashboard.index',$this->data);
    }

    public function progress(Request $request)
    {
        if(auth()->user()->hasRole('Project Manager')){
            $query = Project::whereNull('deleted_at')->where([['pic_id',auth()->user()->id]])->orderBy('begin','DESC');
        }elseif(auth()->user()->hasRole('Member')){
            $user = auth()->user();
            $query = Project::whereHas('task',function($q)use($user){
                $q->whereHas('task_user',function($qt)use($user){
                    $qt->where('user_id',$user->id);
                });
            })->whereNull('deleted_at')->orderBy('begin','DESC');
        }else{
            $query = Project::whereNull('deleted_at')->orderBy('begin','DESC');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('date')) {
            $date = \Carbon\Carbon::createFromFormat('d-m-Y',$request['date'])->format('Y-m-d');
            $query->whereDate('begin','<=',$date)->whereDate('end','>=',$date);
        }

        $projects = $query->withCount([
            'task as todo' => function ($query) { $query->where('status', 'To-Do'); },
            'task as in_progress' => function ($query) { $query->where('status', 'In Progress'); },
            'task as done' => function ($query) { $query->where('status', 'Done'); }
        ])->get();

        return response()->json($projects);
    }
}

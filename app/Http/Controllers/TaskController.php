<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->data['link_active'] = 'task';
    }

    public function index()
    {
        abort_if(Gate::denies('Task Access'), 403);
        if(auth()->user()->hasRole('Super Admin')){
            $this->data['tasks'] = Task::with('project')->whereNull('deleted_at')->orderBy('deadline','DESC')->get();
        }else{
            $user = auth()->user();
            $this->data['tasks'] = Task::with('project')->whereHas('task_user',function($q)use($user){
                $q->where('user_id',$user->id);
            })->orderBy('deadline','DESC')->get();
        }
        return view('task.index',$this->data);
    }

    public function show(Task $task)
    {
        abort_if(Gate::denies('Task Access'), 403);
        $this->data['project']      = Project::where('id',$task->project_id)->firstOrFail();
        $this->data['task']         = Task::with('task_user')->where('id',$task->id)->firstOrFail();
        $this->data['link_back']    = route('task.index');
        $this->data['action']       = route('task.status',$task->id);
        return view('task.detail',$this->data);
    }

    public function status(Request $request, Task $task)
    {
        abort_if(Gate::denies('Task Status'), 403);
        try {
            DB::beginTransaction();
            Task::where('id',$task->id)->update([
                'status'    => $request['status'],
            ]);
            DB::commit();
            return redirect()->route('task.show',$task->id)->with('success','Successfully changed status task project');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

}

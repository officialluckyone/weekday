<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TaskDeadlineNotification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->data['link_active'] = 'project';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('Project Access'), 403);
        $user = auth()->user();
        if(auth()->user()->hasRole('Project Manager')){
            $this->data['projects'] = Project::with('pic')
            ->whereNull('deleted_at')
            ->where('pic_id',auth()->user()->id)
            ->orWhereHas('task',function($q)use($user){
                $q->whereHas('task_user',function($qt)use($user){
                    $qt->where('user_id',$user->id);
                });
            })
            ->orderBy('begin','DESC')
            ->get();
        }elseif(auth()->user()->hasRole('Member')){
            $this->data['projects'] = Project::with('pic')->whereHas('task',function($q)use($user){
                $q->whereHas('task_user',function($qt)use($user){
                    $qt->where('user_id',$user->id);
                });
            })->whereNull('deleted_at')->orderBy('begin','DESC')->get();
        }else{
            $this->data['projects'] = Project::with('pic')->whereNull('deleted_at')->orderBy('begin','DESC')->get();
        }
        return view('project.index',$this->data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('Project Create'), 403);
        $this->data['action']   = route('project.store');
        return view('project.form',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('Project Create'), 403);
        $request->validate([
            'name'          => 'required',
            'start_at'      => 'required',
            'end_at'        => 'required',
        ],[
            'name.required'     => 'Name must be required',
            'start_at.required' => 'Start Project must be required',
            'end_at.required'   => 'End Project must be required',
        ]);

        try {
            DB::beginTransaction();
            $request['begin']   = \Carbon\Carbon::createFromFormat('d-m-Y',$request['start_at'])->format('Y-m-d');
            $request['end']     = \Carbon\Carbon::createFromFormat('d-m-Y',$request['end_at'])->format('Y-m-d');
            $request['pic_id']  = auth()->user()->id;
            Project::create($request->except(['_token','start_at','end_at']));
            DB::commit();
            return redirect()->route('project.index')->with('success','Successfully added new project');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->data['tasks']    = Task::whereNull('deleted_at')->where('project_id',$project->id)->get();
        $this->data['project']  = $project;
        return view('project.task',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        abort_if(Gate::denies('Project Update'), 403);
        $this->data['action']   = route('project.update',$project->id);
        $this->data['project']  = $project;
        return view('project.form',$this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        abort_if(Gate::denies('Project Update'), 403);
        $request->validate([
            'name'          => 'required',
            'start_at'      => 'required',
            'end_at'        => 'required',
        ],[
            'name.required'     => 'Name must be required',
            'start_at.required' => 'Start Project must be required',
            'end_at.required'   => 'End Project must be required',
        ]);
        try {
            DB::beginTransaction();
            $request['begin']   = \Carbon\Carbon::createFromFormat('d-m-Y',$request['start_at'])->format('Y-m-d');
            $request['end']     = \Carbon\Carbon::createFromFormat('d-m-Y',$request['end_at'])->format('Y-m-d');
            Project::where('id',$project->id)->update($request->except(['_token','start_at','end_at','_method']));
            DB::commit();
            return redirect()->route('project.index')->with('success','Successfully changed project');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        abort_if(Gate::denies('Project Delete'), 403);
        try {
            DB::beginTransaction();
            Project::where('id',$project->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('project.index')->with('success','Successfully deleted project');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function add_task(Project $project)
    {
        abort_if(Gate::denies('Task Create'), 403);
        $this->data['project']  = $project;
        $this->data['action']   = route('project.task.store',$project->id);
        $this->data['users']    = User::whereHas('roles',function($q){
            $q->where('name','Member')->orWhere('name','Project Manager');
        })->get();
        return view('project.task_form',$this->data);
    }

    public function store_task(Request $request, Project $project)
    {
        abort_if(Gate::denies('Task Create'), 403);
        $request->validate([
            'title'         => 'required',
            'deadline_at'   => 'required',
            'start_at'      => 'required',
            'priority'      => 'required',
            'users_id'      => 'required|array|min:1'
        ],[
            'title.required'        => 'Title tas must be required',
            'deadline_at.required'  => 'Deadline task must be required',
            'start_at.required'     => 'Start task must be required',
            'priority.required'     => 'Priority task must be required',
            'users_id.required'     => 'Tasks For Users must be required',
            'users_id.min'          => 'Tasks For Users min 1 users',
        ]);
        try {
            DB::beginTransaction();
            $request['project_id']  = $project->id;
            $request['deadline']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['deadline_at'])->format('Y-m-d');
            $request['start']       = \Carbon\Carbon::createFromFormat('d-m-Y',$request['start_at'])->format('Y-m-d');
            $task = Task::create($request->except('_token','deadline_at','users_id','start_at'));
            $task->task_user()->sync($request->users_id);
            if($task->status != 'Done'){
                $users      = User::whereIn('id',$request->users_id)->get();
                $title      = "Tugas Baru Sudah Dibuat";
                $message    = "Anda memiliki tugas untuk dikerjakan dari tanggal ".\Carbon\Carbon::parse($task->start)->isoFormat('DD-MMMM-YYYY')." s/d tanggal ".\Carbon\Carbon::parse($task->deadline)->isoFormat('DD-MMMM-YYYY')." ";
                foreach($users as $user){
                    $user->notify(new TaskDeadlineNotification($task, $title, $message));
                }
            }
            DB::commit();
            return redirect()->route('project.show',$project->id)->with('success','Successfully added new task project');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }

    }

    public function show_task(Project $project, Task $task)
    {
        $this->data['project']      = $project;
        $this->data['task']         = Task::with('task_user')->where('id',$task->id)->first();
        $this->data['link_back']    = route('project.show',$project->id);
        return view('task.detail',$this->data);
    }

    public function edit_task(Project $project, Task $task)
    {
        abort_if(Gate::denies('Task Update'), 403);
        $this->data['project']  = $project;
        $this->data['task']     = Task::with('task_user')->where('id',$task->id)->first();
        $this->data['action']   = route('project.task.update',[$project->id,$task->id]);
        $this->data['users']    = User::whereHas('roles',function($q){
            $q->where('name','Member')->orWhere('name','Project Manager');
        })->get();
        return view('project.task_form',$this->data);
    }

    public function update_task(Request $request, Project $project, Task $task)
    {
        abort_if(Gate::denies('Task Update'), 403);
        $request->validate([
            'title'         => 'required',
            'deadline_at'   => 'required',
            'start_at'      => 'required',
            'priority'      => 'required',
            'users_id'      => 'required|array|min:1'
        ],[
            'title.required'        => 'Title tas must be required',
            'deadline_at.required'  => 'Deadline task must be required',
            'start_at.required'     => 'Start task must be required',
            'priority.required'     => 'Priority task must be required',
            'users_id.required'     => 'Tasks For Users must be required',
            'users_id.min'          => 'Tasks For Users min 1 users',
        ]);

        try {
            DB::beginTransaction();
            $request['deadline']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['deadline_at'])->format('Y-m-d');
            $request['start']       = \Carbon\Carbon::createFromFormat('d-m-Y',$request['start_at'])->format('Y-m-d');
            Task::where('id',$task->id)->update($request->except('_token','deadline_at','users_id','_method','start_at'));
            $task->task_user()->sync($request->users_id);
            if($request['deadline_at'] != $task->deadline_at && $task->status != 'Done'){
                $users = User::whereIn('id',$request->users_id)->get();
                $title      = "Tugas Sudah Dirubah";
                $message    = "Anda memiliki tugas yang dirubah silahkan cek di halaman task, atau klik disini";
                foreach($users as $user){
                    $user->notify(new TaskDeadlineNotification($task, $title, $message));
                }
            }
            DB::commit();
            return redirect()->route('project.show',$project->id)->with('success','Successfully changed task project');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy_task(Project $project, Task $task)
    {
        abort_if(Gate::denies('Task Delete'), 403);
        try {
            DB::beginTransaction();
            Task::where('id',$task->id)->update([
                'deleted_at'    => \Carbon\Carbon::now()
            ]);
            DB::commit();
            return redirect()->route('project.show',$project->id)->with('success','Successfully deleted task project');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}

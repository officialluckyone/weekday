<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->data['link_active'] = 'user';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('User Access'), 403);
        if(!auth()->user()->hasRole('Super Admin')){
            $this->data['users']    = User::whereHas('roles',function($q){
                $q->where('name','!=','Super Admin');
            })->get();
        }else{
            $this->data['users']    = User::all();
        }
        return view('user.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('User Create'), 403);
        $this->data['action'] = route('users.store');
        $this->data['roles']  = Role::where('name','!=','Super Admin')->get();
        return view('user.form',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('User Create'), 403);
        $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string',
            'role'      => 'required',
        ],[
            'name.required'     => 'Name must be required',
            'name.string'       => 'Name not valid',
            'email.required'    => 'Email must be required',
            'email.email'       => 'Email not valid',
            'email.unique'      => 'Email already use',
            'password.required' => 'Password must be required',
            'password.string'   => 'Password not valid',
            'role.required'     => 'Role must be required',
        ]);

        try {
            DB::beginTransaction();
            $data['users']  = $request->only(['name','username','email']);
            $data['users']['password'] = Hash::make($request->password);
            $data['users']['email_verified_at'] = \Carbon\Carbon::now();
            $users = User::create($data['users']);
            $users->assignRole($request->role);
            DB::commit();
            return redirect()->route('users.index')->with('success','Successfully added new user');
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        abort_if(Gate::denies('User Update'), 403);
        $this->data['user']     = $user;
        $this->data['action']   = route('users.update',$user->id);
        $this->data['roles']    = Role::where('name','!=','Super Admin')->get();
        return view('user.form',$this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        abort_if(Gate::denies('User Update'), 403);
        $rules = [
            'name'      => 'required|string',
            'email'     => 'required|email',
            'role'      => 'required',
        ];

        if($request->email != $user->email){
            $rules = [
                'email'     => 'required|email|unique:users',
            ];
        }

        $request->validate($rules,[
            'name.required'     => 'Name must be required',
            'name.string'       => 'Name not valid',
            'email.required'    => 'Email must be required',
            'email.email'       => 'Email not valid',
            'email.unique'      => 'Email Already use',
            'role.required'     => 'Role must be required',
        ]);

        $nama = $user->name;
        try {
            DB::beginTransaction();
            $data['users']  = $request->only(['name','username','email']);
            if(!is_null($request->password)){
                $data['users']['password'] = Hash::make($request->password);
            }
            $data['users']['email_verified_at'] = \Carbon\Carbon::now();
            User::where('id',$user->id)->update($data['users']);
            $user->syncRoles($request->role);

            DB::commit();
            return redirect()->route('users.index')->with('success','Successfully changed user account '.$nama);
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
    public function destroy(User $user)
    {
        //
    }

     /**
     * Banned the specified resource from storage.
     */
    public function banned(User $users)
    {
        abort_if(Gate::denies('User Banned'), 403);
        $nama = $users->name;
        try {
            DB::beginTransaction();
            User::where('id',$users->id)->update([
                'banned'    => true,
            ]);
            DB::commit();
            return redirect()->route('users.index')->with('success','Successfully deactivated user account '.$nama);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Unbanned the specified resource from storage.
     */
    public function unbanned(User $users)
    {
        abort_if(Gate::denies('User Banned'), 403);
        $nama = $users->name;
        try {
            DB::beginTransaction();
            User::where('id',$users->id)->update([
                'banned'    => false,
            ]);
            DB::commit();
            return redirect()->route('users.index')->with('success','Successfully reactivated user account '.$nama);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->data['link_active'] = 'profile';
    }

    public function index()
    {
        $this->data['user'] = auth()->user();
        $this->data['action'] = route('profile.store');
        return view('profile.index',$this->data);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'  => 'required|string',
            'email' => 'required|email',
        ];

        $message = [
            'name.required'                     => 'Name must be required',
            'name.string'                       => 'Name not valid',
            'email.required'                    => 'Email must be required',
            'email.email'                       => 'Email not valid',
        ];

        if($request->email != $user->email){
            $rules['email']           = 'required|string|unique:users';
            $message['email.unique']  = 'Email is already in use';
        }

        $request->validate($rules,$message);

        try {
            DB::beginTransaction();
            User::where('id',$user->id)->update($request->except(['_token']));
            DB::commit();
            return redirect()->route('profile.index')->with('success','Successfully changed profile');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function setting()
    {
        $this->data['action'] = route('user-password.update');
        return view('profile.setting',$this->data);
    }
}

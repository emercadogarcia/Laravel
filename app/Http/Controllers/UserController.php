<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $messages = [
    		'name.required' => 'El nombre es un campo obligatorio.',
    		'email.required' => 'El email/correo eletronico es un dato obligatorio.',
    		'password.required' => 'El password es un campo obligatrio.'
    	];
    	$rules = [
    		'name' => ['required'],
    		'email' => ['required', 'email', 'unique:users'],
    		'password' => ['required', 'min:8']
    	];
        $this->validate($request, $rules, $messages);

        // $request -> validate([
        //     'name' => ['required'],
        //     'email' => ['required','email','unique:users'],
        //     'password' => ['required','min:8']
        // ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return back();
    }

    public function destroy(User $user)
    {
        $user -> delete();

        return back();
    }

}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    // public function create(){
    //     return view('admin.user.create');
    // }

    // public function store(Request $request)
    // {
    //     $user = User::create(['name' => $request->name]);

    //     $redirect_arr = [
    //         'edit' => route('admin.user.edit', ['user' => $user->id]),
    //         'create' => route('admin.user.create')
    //     ];

    //     return redirect($redirect_arr[$request->redirect]);
    // }

    // public function edit(user $user)
    // {
    //     return view('admin.user.edit', compact('user'));
    // }

    // public function destroy(Request $request, user $user)
    // {
    //     $user->delete();

    //     if ($request->has("redirect")) {
    //         return redirect($request->redirect);
    //     }

    //     return redirect()->back();
    // }

    // public function update(Request $request, user $user)
    // {
    //     $user->update($request->all());
    //     return redirect()->route('admin.user.index');
    // }
}

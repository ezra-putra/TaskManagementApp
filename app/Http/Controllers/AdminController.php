<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function userList()
    {
        $user = User::all();

        return view('admin.user-list', compact('user'));
    }

    public function roleModal(Request $request)
    {
        $id = ($request->get('id'));
        $user = User::where('id', $id)->get();
        $role = Role::all();

        return response()->json(array(
            'msg'=> view('admin.edit-role',compact('user', 'role'))->render()
        ),200);
    }

    public function editRoleUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return redirect()->back()->with('success', 'Role change succesfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('authentication.login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    public function login(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->attempt($credential)){

            $user = auth()->user();

            return redirect('/');
        }

        return redirect()->back()->withInput()->withErrors([
            'email' => 'Incorrect Password or Email.',
        ]);
    }
}

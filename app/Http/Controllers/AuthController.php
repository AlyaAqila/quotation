<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller {
    //display sign in page
    public function showSignin() {
        return view('auth.signin');
    }

    //display sign up page
    public function showSignup() {
        return view('auth.signup');
    }

    //process sign in
    public function signin(Request $request) {
        //validate login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //check email + password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }
        return back()->withErrors([
            'email' => 'The email or password is incorrect.',
        ])->onlyInput('email');
    }
    

    //process sign up
    public function signup(Request $request) {
        //validate form
        $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'              
                    ],
            ]);
        //create user
        User::create([
            'name' => $request->name,
            'email' =>$request->email,
            'password' => Hash::make($request->password),
        ]);
        //go to sign in page    
        return redirect()
            ->route('signin')
            ->with('success','Account created successfully. Please sign in.');
    }

    //process sign out
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin');
    }
    
}
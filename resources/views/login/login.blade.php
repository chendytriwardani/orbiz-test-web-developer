<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $hardcodedEmail = 'test@example.com';
        $hardcodedPassword = 'password123';

        $email = $request->input('email');
        $password = $request->input('password');

        if ($email === $hardcodedEmail && $password === $hardcodedPassword) {
            return redirect('/dashboard')->with('success', 'Login successful!');
        }

        return back()->with('error', 'Invalid credentials.');
    }
}
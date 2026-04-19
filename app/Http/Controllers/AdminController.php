<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{

    public function handleLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->login($request);
        }
        return $this->showLoginForm();
    }
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin');
        }
        return view('admin/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        // Attempt login
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin');
        }

        return back()->withErrors([
            'username' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function deleteSessionData(Request $request)
    {

        $request->session()->forget('your_session_key');

        return response()->json(['success' => true, 'message' => 'Session data deleted successfully.']);
    }
}

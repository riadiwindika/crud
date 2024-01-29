<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginIndex() {

        return view('auth.login');

    }

    public function login(Request $request) {

        $request->validate([
            'email' => 'required|string|max:50|email',
            'password' => 'required|string|min:8'
        ]);

        $input = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($input))
        {
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Login successfully.',
                    'redirect' => route('admin.index'),
                ], 200);
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Email or password is incorrect',
            ], 200);
        }
    }

    public function logout() {

        Auth::logout();
        session()->forget('link');
        session()->flush();

        return redirect()->route('login.index');

    }
}

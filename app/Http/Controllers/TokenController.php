<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

class TokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([

            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            //return new AuthenticationException();
            return response()->json([
                'user' => NULL,
                'message' => 'Password salah!'
            ],401);
        }

        return [
            'token' => $request->user()->createToken($request->deviceId)->plainTextToken,
            'user' => $request->user(),
        ];
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->where('name', $request->deviceId)->delete();
        return response('', 204);
    }
}

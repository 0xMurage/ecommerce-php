<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ], ['email.*' => 'Invalid email/password combination.',
            'password.required' => 'Invalid email/password combination.']);

        #auth with token
        if (!$token = Auth::attempt($validated)) {
            return response()->json(['message' => 'Invalid email/password combination.'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);

    }
}

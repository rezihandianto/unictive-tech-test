<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60
        ]);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'User successfully logged out']);
    }
    public function me()
    {
        return response()->json(Auth::user());
    }
}

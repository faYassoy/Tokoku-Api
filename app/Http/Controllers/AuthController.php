<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
            $operator = Auth::guard('web')->user();

            // Ensure your Operator model uses HasApiTokens trait
            $token = $operator->createToken('Operator Access Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'operator' => $operator,
                'token' => $token,
            ], 200);
        }

        return response()->json(['message' => 'The provided credentials do not match our records.'], 401);
    }
};

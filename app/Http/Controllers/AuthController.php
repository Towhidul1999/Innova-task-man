<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('TaskManager')->accessToken;

            try {
                return response()->json([
                    'success' => true,
                    'message' => 'User login Success',
                    'token' => $token
                ], 200);
            } catch (\Error $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
        }else{
            return response()->json(['message' => 'Credentials Not Match']);
        }
    }
}

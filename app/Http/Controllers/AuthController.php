<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->userAgent())->plainTextToken;

        return ResponseService::SuccessResponse([
            'token' => $token,
            'user' => $user,
        ],
            "User logged in successfully"
        );

    }


    public function user()
    {
       return ResponseService::SuccessResponse([
           "user"=>\request()->user()
       ],"Current user retrieved successfully");
    }
}

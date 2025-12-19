<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Socialite;

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
            "user" => \request()->user()
        ], "Current user retrieved successfully");
    }


    public function googleLogin(Request $request)
    {

        $request->validate([
            "code" => "required"
        ]);


        $code = $request->code;
        $client_id = config("services.google.client_id");

        $client_secret = config("services.google.client_secret");

        $url = "https://oauth2.googleapis.com/token?code=$code&client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code&redirect_uri=";

        $test = Http::withHeader("Content-Type", "application/x-www-form-urlencoded")->post($url);

        Log::info("access code", ["code" => $test->jsoon()]);


        $user = Socialite::driver("google")->userFromToken($request->code);

        Log::info("user found", ["user" => $user]);

        return ResponseService::SuccessResponse($user, "User logged in successfully");


    }
}

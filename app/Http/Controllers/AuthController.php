<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            "user" => \request()->user()
        ], "Current user retrieved successfully");
    }


    /**
     * @throws ConnectionException
     */
    public function googleLogin(Request $request)
    {

        $request->validate([
            "code" => "required"
        ]);


        $code = $request->code;
        $client_id = config("services.google.client_id");

        $client_secret = config("services.google.client_secret");

        $url = "https://www.googleapis.com/oauth2/v3/tokeninfo?access_token=$code";

        $test = Http::get($url);

        $user = $test->object();

        Log::info("found user", ["user" => $user]);

        return ResponseService::SuccessResponse($user, "User logged in successfully");


    }


    public function singUp(Request $request)
    {
        $request->validate([
            "email" => "required|string|email",
            "imageUrl" => "required|url",
            "id" => "required|string",
            "name" => "required|string",
        ]);


        $existingUser = User::where('email', $request->email)
            ->where("google_user_id",$request->id)->first();

        /**
         * if the user already exist
         */

        if ($existingUser) {

            $token = $existingUser->createToken($request->userAgent())->plainTextToken;

            return ResponseService::SuccessResponse([
                'token' => $token,
                'user' => $existingUser,
            ],
                "User logged in successfully"
            );
        }

        /**
         * if user does not exist
         */

        $newUser = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "google_user_id" => $request->id,
            "image_url" => $request->imageUrl,
            "password" => Hash::make($request->password),
        ]);

        $token = $newUser->createToken($request->userAgent())->plainTextToken;

        return ResponseService::SuccessResponse([
            'token' => $token,
            'user' => $newUser,
        ],
            "User logged in successfully"
        );
    }



}

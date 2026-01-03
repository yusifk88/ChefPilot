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

        $url = "https://oauth2.googleapis.com/token?code=$code&client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code";

        $accessCodeRequest = Http::withHeader("Content-Type","application/x-www-form-urlencoded")->post($url);

        if ($accessCodeRequest->successful()) {

            $response = $accessCodeRequest->object();

            $userInfo = Http::withHeaders(["Authorization" => "Bearer $response->access_token"])
                ->get("https://openidconnect.googleapis.com/v1/userinfo");

            $user = $userInfo->object();

            $existingUser = User::where('email', $user->email)->where("google_user_id",$user->sub)->first();


            $foundUser = null;

            if ($existingUser) {

                $existingUser->update([
                    "name"=>$user->name,
                ]);


                $foundUser = $existingUser;


            }else{


            $foundUser = new User([
                "name"=>$user->name,
                "email"=>$user->email,
                "google_user_id"=>$user->sub,
                "image_url"=>$user->picture,
                "password" => bcrypt($user->sub),
            ]);
            $foundUser->save();


            }


            $token = $foundUser->createToken($request->userAgent())->plainTextToken;

            return ResponseService::SuccessResponse([
                'token' => $token,
                'user' => $foundUser,
            ],
                "User logged in successfully"
            );


        }


        return ResponseService::FailedResponse("Login failed , try again");




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

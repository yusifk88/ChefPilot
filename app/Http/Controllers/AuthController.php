<?php

namespace App\Http\Controllers;

use App\Mail\AccessCode;
use App\Models\Attempt;
use App\Models\Interaction;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Recipe;
use App\Models\User;
use App\Models\UserItem;
use App\Services\ResponseService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        $user->update([
            "device_name" => $request->device_name,
            "device_model" => $request->device_model,
            "ip_address" => $request->ip(),
            "timezone" => $request->timezone,
            "country" => $request->country,
            "device_os" => $request->device_os,
        ]);

        $token = $user->createToken($request->userAgent())->plainTextToken;

        return ResponseService::SuccessResponse([
            'token' => $token,
            'user' => $user,
        ],
            "User logged in successfully"
        );

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

        $accessCodeRequest = Http::withHeader("Content-Type", "application/x-www-form-urlencoded")->post($url);

        if ($accessCodeRequest->successful()) {

            $response = $accessCodeRequest->object();

            $userInfo = Http::withHeaders(["Authorization" => "Bearer $response->access_token"])
                ->get("https://openidconnect.googleapis.com/v1/userinfo");

            $user = $userInfo->object();

            $existingUser = User::where('email', $user->email)->where("google_user_id", $user->sub)->first();


            $foundUser = null;

            if ($existingUser) {

                $existingUser->update([
                    "name" => $user->name,
                    "device_name" => $request->device_name,
                    "device_model" => $request->device_model,
                    "ip_address" => $request->ip(),
                    "timezone" => $request->timezone,
                    "country" => $request->country,
                    "device_os" => $request->device_os,
                ]);


                $foundUser = $existingUser;


            } else {


                $foundUser = new User([
                    "name" => $user->name,
                    "email" => $user->email,
                    "google_user_id" => $user->sub,
                    "image_url" => $user->picture,
                    "password" => Hash::make($user->sub),
                    "device_name" => $request->device_name,
                    "device_model" => $request->device_model,
                    "ip_address" => $request->ip(),
                    "timezone" => $request->timezone,
                    "country" => $request->country,
                    "device_os" => $request->device_os,
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


        $existingUser = User::where('email', $request->email)->first();

        /**
         * if the user already exist
         */

        if ($existingUser) {

            $token = $existingUser->createToken($request->userAgent())->plainTextToken;

            $existingUser->update([
                "device_name" => $request->device_name,
                "device_model" => $request->device_model,
                "ip_address" => $request->ip(),
                "timezone" => $request->timezone,
                "country" => $request->country,
                "device_os" => $request->device_os,
            ]);

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
            "device_name" => $request->device_name,
            "device_model" => $request->device_model,
            "ip_address" => $request->ip(),
            "timezone" => $request->timezone,
            "country" => $request->country,
            "device_os" => $request->device_os,

        ]);

        $token = $newUser->createToken($request->userAgent())->plainTextToken;

        return ResponseService::SuccessResponse([
            'token' => $token,
            'user' => $newUser,
        ],
            "User logged in successfully"
        );
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            "name" => "required|string"
        ]);
        $user = $request->user();

        $user->update([
            "name" => $request->name,
            "bio" => $request->bio
        ]);

        return ResponseService::SuccessResponse($user, "User updated successfully");

    }

    public function user()
    {
        return ResponseService::SuccessResponse([
            "user" => \request()->user()
        ], "Current user retrieved successfully");
    }

    public function notifications()
    {

        return ResponseService::SuccessResponse([
            "unread" => request()->user()->unreadNotifications,
            "all" => request()->user()->notifications()->paginate(50),
        ], "Current user retrieved successfully");

    }


    public function markNotificationsAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
    }

    public function notificationCount()
    {
        return ResponseService::SuccessResponse([
            "all" => request()->user()->notifications->count(),
            "unread" => request()->user()->unreadNotifications->count(),
        ], "Current user notification count");

    }

    public function setUserTheme(request $request)
    {
        $request->validate([
            "theme" => "required|string|in:light,dark,system",
        ]);

        $user = request()->user();

        $user->update(["theme" => $request->theme]);

        return ResponseService::SuccessResponse($user, "Theme updated successfully");
    }

    public function changeAvatar(Request $request)
    {
        $request->validate([
            "avatar" => "required|string",
        ]);


//        if (str_contains($request->avatar, "/")) {
//            [$meta, $base64] = explode(',', $request->avatar, 2);
//        }

        $imageData = base64_decode($request->avatar);

        if ($imageData === false) {
            return ResponseService::FailedResponse(message: "Invalid image data");
        }
        $user = $request->user();


        $filename = "chefpilot/$user->id/avatar/" . Str::uuid() . ".jpg";

        Storage::disk('spaces')->put(
            $filename,
            $imageData,
            'public'
        );

        $url = Storage::disk('spaces')->url($filename);

        $user = $request->user();

        $user->update(["image_url" => $url]);

        return ResponseService::SuccessResponse($user, "User avatar updated successfully");

    }

    public function requestCode()
    {

        $code = substr(Str::ulid(), -6);
        $user = auth()->user();

        Cache::put("authCode" . $user->id, $code, now()->addMinutes(15));

        Mail::to($user->email)->send(new AccessCode($code));

    }

    public function deleteAccount(Request $request)
    {

        $request->validate([
            "code" => "required|string|min:6"
        ]);

        $user = $request->user();

        if (Cache::get("authCode" . $user->id) != $request->code) {

            return ResponseService::FailedResponse(message: "Invalid code or code expired");
        }

        Recipe::query()->where("user_id", $user->id)->delete();
        Post::query()->where("user_id", $user->id)->delete();
        UserItem::query()->where("user_id", $user->id)->delete();
        PostComment::query()->where("user_id", $user->id)->delete();
        Interaction::query()->where("user_id", $user->id)->delete();
        Attempt::query()->where("user_id", $user->id)->delete();

        $user->tokens()->delete();
        Schema::disableForeignKeyConstraints();
        $user->delete();

        return ResponseService::SuccessResponse(message: "Your account has been deleted, thank you for trying Chefpilot");

    }


    public function subscriptionIntent()
    {

        $secret = \request()->user()->createSetupIntent()->client_secret;

        return ResponseService::SuccessResponse(data: ["secret" => $secret], message: "Subscription intent secret created successfully");

    }


}

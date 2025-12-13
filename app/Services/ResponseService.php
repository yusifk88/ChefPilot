<?php

namespace App\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseService
{

    /**
     * @param $data
     * @param string $message
     * @return JsonResponse
     * structure for a successful API response
     */

    public static function SuccessResponse($data = [], string $message = "Request processed successfully"): JsonResponse
    {
        return response()->json([
            "message" => $message,
            "data" => $data
        ]);

    }

    /**
     * @param string $message
     * @param $data
     * @param int $code
     * @return JsonResponse
     * return a failed response
     */
    public static function FailedResponse(string $message = "Request processed successfully", $data = [], int $code = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json([
            "message" => $message,
            "errors" => $data
        ], $code);

    }

    /**
     * @param string $message
     * @param string $type
     * @param string $title
     * @param string $url
     * @param string $url_text
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     * Render the information page
     */

    public static function renderInfo(string $message = "", string $type = "error", string $title = "", string $url = "", string $url_text = "View"): View|\Illuminate\Foundation\Application|Factory|Application
    {

        return view("info", [
            "title" => $title ?? "error",
            "type" => $type,
            "message" => $message ?? "Something went, please try again",
            "link" => $url,
            "link_text" => $url_text
        ]);

    }


}

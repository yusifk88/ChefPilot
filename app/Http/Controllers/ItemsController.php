<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function index()
    {
        $items = FoodItem::where("id",">",0)->orderBy("name")->get();
        return ResponseService::SuccessResponse(["items" => $items],"Items List retrieved successfully");
    }
}

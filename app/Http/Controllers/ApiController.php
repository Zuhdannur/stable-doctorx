<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Service;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getFlagService(Request $request) {
        $query = Service::find($request->id)->flag;
        return response()->json(["data" => $query]);
    }
}

<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Wechat\Fan;

class FanController extends Controller
{
    
    public function index() 
    {
        $fans = Fan::get();
        return response()->json(['status' => 'success', 'data' => $fans]);
    }

    public function show()
    {
        $fan = Fan::find(request()->fan);
        return response()->json(['status' => 'success', 'data' => $fan]);
    }

}

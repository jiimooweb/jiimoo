<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Commons\Fan;

class FanController extends Controller
{
    
    public function index() 
    {
        $status = request()->status;
        $nickname = request()->nickname;
        $fans = Fan::when($status > -1, function($query) use ($status) {
            return $query->where('status', $status);
        })->when($nickname, function($query) use ($nickname) {
            return $query->where('nickname', 'like','%'.$nickname.'%');
        })->paginate(config('common.pagesize'));
        return response()->json(['status' => 'success', 'data' => $fans]);
    }

    public function show()
    {
        $fan = Fan::find(request()->fan);
        return response()->json(['status' => 'success', 'data' => $fan]);
    }

}

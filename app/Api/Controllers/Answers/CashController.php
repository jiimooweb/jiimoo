<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Cash;
use App\Api\Controllers\Controller;

class CashController extends Controller
{
        public function show($xcxid){
            $cash=Cash::where('xcx_id',$xcxid);
            return $xcxid;
        }
        public function create(){

        }
        public function store($xcx_id){
            $this->validate(request(),[
                'ratio' => 'required',
            ]);
           $save=Cash::create(['xcx_id'=>$xcx_id,'ratio'=>request('ratio')]);
           return $save;
        }
        public function edite(){}
        public function update($xcx_id){
            $this->validate(request(),[
                'ratio' => 'required',
            ]);
            $update=Cash::where('xcx_id',$xcx_id)->update(['ratio'=>request('ratio')]);
            return $update;
        }
}

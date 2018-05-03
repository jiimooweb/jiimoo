<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Depot;
use App\Api\Controllers\Controller;

class DepotController extends Controller
{
        public function create(){
            $this->validate(request(),[
                'name' => 'required|unique:answers_depot,name',
                'activity' => 'required',
            ]);
            $save=Depot::create(request(['name','activity']));
        }
        public function store(){

        }
        public function edite(){}
        public function update(Depot $depot)
        {
            $this->validate(request(), [
                'name' => 'required',
                'activity' => 'required',
            ]);
            $update = $depot->update(request(['name', 'activity']));
            return $update;
        }
        public function delete(Depot $depot){
            if($depot->question){
                return false;
            }else{
                $depot->delete();
                return true;
            }
        }
}

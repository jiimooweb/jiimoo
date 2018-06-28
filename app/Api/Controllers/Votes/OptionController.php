<?php

namespace App\Api\Controllers\Votes;
use App\Http\Requests\Votes\OptionStoreRequest;
use App\Http\Requests\Votes\OptionUpdateRequest;
use App\Models\Votes\Option;
use App\Api\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    public function index()
    {
        $voteID = request()->voteID;
        $data = Option::where('vote_id', $voteID)->withCount('fans')->get();
        foreach ($data as $item) {
            $fansCount = $item->fans_count;
            if($item->total < $fansCount){
                $item->total = $fansCount;
            }
            unset($item->fans_count);
        }
        return response()->json(['status' => 'success', 'data' => $data ]);
    }

    public function store()
    {
        /*
        $list = request(['vote_id', 'options']);
        $voteID = $list['vote_id'];
        $options = $list['options'];
        $now = Carbon::now();
        $data = [];
       foreach ($options as $option){
            array_push($data,['vote_id'=>$voteID,'content'=>$option->content,'total'=>$option->total,'created_at'=>$now,'updated_at'=>$now]);
        }
        $bool = DB::table('votes_options')->insert($data);
        if($bool){
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);
        }
        return response()->json(['status' => 'error', 'msg' => '新增失败！']);
        */
        $list = request([ 'vote_id','options']);
        $voteID = $list['vote_id'];
        $options = $list['options'];
        $now = Carbon::now();
        $data = [];
        DB::beginTransaction()
        try{
            foreach ($options as $option){
                Option::where('id', $voteID)->delete();
                array_push($data,['vote_id'=>$voteID,'content'=>$option['content'],'total'=>$option['total'],'created_at'=>$now,'updated_at'=>$now]);
            }
            DB::table('votes_options')->insert($data);
            DB::commit();
            return response()->json(['status' => 'success', 'msg' => '成功！']);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '失败！']);
        }
    }

//    public function update(OptionUpdateRequest $request)
//    {
//        $options = request('options');
//        foreach ($options as $option){
//            $data = [];
//            $data['content'] = $option->content;
//            $data['total'] = $option->total;
//            Option::where('id', $option->id)->update($data);
//        }
//    }
//
//    public function destroy()
//    {
//        $options = request('options');
//        foreach ($options as $option){
//            Option::where('id', $option)->delete();
//        }
//    }
}
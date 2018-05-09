<?php

namespace App\Api\Controllers\Queues;

use App\Jobs\TestJob;
use App\Services\Token;
use App\Models\Queues\Queue;
use Illuminate\Http\Request;
use App\Jobs\SendQueueNotice;
use App\Models\Queues\QueueFan;
use App\Api\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Queues\QueueRequest;
use App\Http\Requests\Queues\QueueUserRequest;

class QueueController extends Controller
{
    
    public function index() 
    {
        $Queue = new Queue;
        $queues = $Queue->getQueueFans();
        return response()->json(['status' => 'success', 'data' => $queues]);   
    }

    public function store(QueueRequest $request) 
    {   
        $data = request()->all();  
        if(Queue::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
        
    }

    public function show()
    {
        $Queue = new Queue;
        $queue = $Queue->getFansByQueueID(request()->queue);
        $status = $queue ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $queue]);   
    }

    public function update(QueueRequest $request)
    {
        $data = request()->all();                      
        if(Queue::where('id', request()->queue)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(Queue::where('id', request()->queue)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

    public function join(QueueUserRequest $request) 
    {
        $Queue = new Queue;
        $queue = $Queue->getFansByQueueID($request->queue);
        $data['queue_id'] = $request->queue;
        $data['user_id'] = $request->user_id;
        $data['no'] = $queue->fans_count++;
        if(QueueFan::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '排队成功！']);                         
        }

        return response()->json(['status' => 'error', 'msg' => '排队失败！']);     
        
    }

    public function call()
    {
        $data = $request()->all();

        //TODO: 呼叫的方法

        // if(QueueFan::create($data)) {
        //     return response()->json(['status' => 'success', 'msg' => '排队成功！']);                         
        // }

    }

    public function confirm()
    {
        if(QueueFan::where('id', request()->fan)->update(['status' => 1])) {
            dispatch(new SendQueueNotice(QueueFan::where('id', request()->fan)->first()->queue_id));
            return response()->json(['status' => 'success', 'msg' => '确认成功！']);                         
        }  
    }

    public function cancel()
    {
        if(QueueFan::where('id', request()->fan)->update(['status' => -1])) {
            return response()->json(['status' => 'success', 'msg' => '取消成功！']);                         
        }
    }

    public function test() 
    {
        dispatch(new TestJob());
        return 'success';
    }

    

    

}

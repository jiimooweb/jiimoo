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

    public function join(Request $request) 
    {
        $queue_id = $request->queue;
        $Queue = new Queue;
        $queue = $Queue->getFansByQueueID($queue_id);
        $data['xcx_id'] = session('xcx_id');
        $data['fan_id'] =  Token::getUid();
        if(QueueFan::where($data)->where('status',0)->get()->count()) {
            return response()->json(['status' => 'error', 'msg' => '请勿重复排队！']);     
        }else {
            $data['form_id'] = $request->form_id;
            $data['queue_id'] = $queue_id;            
            $data['no'] = $Queue->getNo($queue_id);
            $data['is_notice'] = 1;
            $data['notice_pos'] = $request->position;
            if(QueueFan::create($data)) {
                $queues = $Queue->getQueueFans();
                return response()->json(['status' => 'success', 'data' => $queues,'msg' => '排队成功！','no' => $data['no']]);
            }
            return response()->json(['status' => 'error', 'msg' => '排队失败！']);
        }
    }

    public function add(Request $request) 
    {
        $queue_id = $request->queue;
        $Queue = new Queue;
        $queue = $Queue->getFansByQueueID($queue_id);
        $data['xcx_id'] = session('xcx_id');
        $data['queue_id'] = $queue_id;            
        $data['no'] = $Queue->getNo($queue_id);
        $data['is_notice'] = 0;
        if(QueueFan::create($data)) {
            $queues = $Queue->getQueueFans();
            return response()->json(['status' => 'success', 'data' => $queues,'msg' => '排队成功！','no' => $data['no']]);
        }

        return response()->json(['status' => 'error', 'msg' => '排队失败！']);
        
    }


    public function call()
    {
        $data = $request()->all();

        return '呼叫成功';
        //TODO: 呼叫的方法

        // if(QueueFan::create($data)) {
        //     return response()->json(['status' => 'success', 'msg' => '排队成功！']);                         
        // }

    }

    public function confirm()
    {
        if(QueueFan::where('id', request()->fan)->update(['status' => 1])) {
            $queue_id = QueueFan::where('id', request()->fan)->first()->queue_id;
            // dispatch(new SendQueueNotice(session('xcx_id'), $queue_id));
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

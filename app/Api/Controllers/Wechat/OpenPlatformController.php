<?php 

namespace App\Api\Controllers\Wechat;

use App\Models\Commons\Xcx;
use App\Models\Wechat\Audit;
use App\Services\OpenPlatform;
use App\Models\Wechat\Experiencer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use EasyWeChat\OpenPlatform\Server\Guard;


class OpenPlatformController extends Controller
{
    public function event_authorize(){
        $server  = OpenPlatform::getApp()->server;

        // 处理授权成功事件
        $server->push(function ($message) {
            // OpenPlatform::initOpenPlatform($message['AuthorizationCode'], 'add');
        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) {
            OpenPlatform::updateMiniProgram($message['AuthorizerAppid']);
        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) {
            OpenPlatform::unAuthorized();
        }, Guard::EVENT_UNAUTHORIZED);

        return $server->serve();
    }

    public function user_author() 
    {
        $openPlatform = OpenPlatform::getApp();        

        $url = $openPlatform->getPreAuthorizationUrl('https://www.rdoorweb.com/wechat/user');

        return view('wechat',['url' => $url]);
    }

    public function miniprogram() 
    {
        $xcx_id = request()->xcx_id;

        $miniProgram = Xcx::find($xcx_id);
    
        if($miniProgram->authorization_status == 1) {
            return response()->json(['status' => 'authorized', 'data' => $miniProgram]);
        }

        $openPlatform = OpenPlatform::getApp();        

        $url = $openPlatform->getPreAuthorizationUrl('https://www.rdoorweb.com/wechat/save_miniprogram'.$xcx_id);

        return response()->json(['status' => 'unauthorize', 'data' => $url]);
    }

    public function save_miniprogram() 
    {  
        $code = request()->get('auth_code');
        $xcx_id = request()->xcx_id;
        OpenPlatform::initMiniProgram($xcx_id,$auth_code);
    }

    public function bind_tester()
    {
        $wechatid = request()->wechatid;
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        $msg = $miniProgram->tester->bind($wechatid);
        if($msg['errcode'] == 0) {
            Experiencer::create(['wechatid' => $wechatid, 'userstr' => $msg['userstr']]);
            return response()->json(['status' => 'success', 'msg' => '绑定成功']);
        }
        
        return $msg;
        // TODO:: 判断错误代码
    }

    public function unbind_tester()
    {
        $wechatid = request()->wechatid;
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        $msg = $miniProgram->tester->unbind($wechatid);
        if($msg['errcode'] == 0) {
            Experiencer::where('wechatid', $wechatid)->delete();
            return response()->json(['status' => 'success', 'msg' => '解绑成功']);
        }
        return response()->json(['status' => 'error', 'msg' => '系统繁忙']);
    }

    public function commit()
    {
        $xcx_id = request()->xcx_id;
        $miniProgram = OpenPlatform::getMiniProgram($xcx_id);
        $extJson = OpenPlatform::getExtJson($xcx_id);
        return $miniProgram->code->commit(request()->template_id, $extJson, '1.0', '任意门网络工作室小程序');
    }

    public function get_qrcode()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->getQrCode('pages/index/index');
    }

    public function get_category()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->getCategory();
    }

    public function get_page()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->getPage();        
    }

    public function submit_audit()
    {
        $xcx_id = request()->xcx_id;
        $miniProgram = OpenPlatform::getMiniProgram($xcx_id);
        $itemList = [
                        [
                            "address" => "pages/applicants/applicants",
                            "tag" => "人员 资源",
                            "first_class" => "文娱",
                            "second_class"=> "资讯",
                            "first_id" => 275,
                            "second_id" => 276,
                            "title" => "首页"
                        ]
                    ];

        $res = $miniProgram->code->submitAudit($itemList);    
        if($res['errcode'] == 0) {
            $xcx = Xcx::find($xcx_id);
            $data = [
                'xcx_id' => $xcx_id,
                'audit_id' => $res['auditid'],
                'app_id' => $xcx['app_id']
            ];
            Audit::create($data);
        }

        return $res;
    }

    public function get_auditstatus()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->getAuditStatus(request()->audit_id); 
    }

    public function get_latest_auditstatus()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->getLatestAuditStatus(); 
    }

    public function release() 
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->release(); 
    }

    public function undocodeaudit()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->withdrawAudit(); 
    }

    public function rollback_release()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->rollbackRelease(); 
    }

    public function change_visitstatus()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->changeVisitStatus(request()->action);
    }




    public function code_tpl_get_drafts()
    {
        $openPlatform = OpenPlatform::getApp();   
        return $openPlatform->code_template->getDrafts();                  
    }

    public function code_tpl_create_from_draft()
    {
        $openPlatform = OpenPlatform::getApp();   
        return $openPlatform->code_template->createFromDraft(request()->draft_id);                
    }

    public function code_tpl_list()
    {
        $openPlatform = OpenPlatform::getApp();   
        return $openPlatform->code_template->list(); 
    }

    public function code_tpl_delete()
    {
        $openPlatform = OpenPlatform::getApp();   
        return $openPlatform->code_template->delete(request()->template_id); 
    }

    
    public function callback($app_id)
    {
        $openPlatform = OpenPlatform::getApp();
        $server      = $openPlatform->server;

        $server->push(EventHandler::class, Message::EVENT); // 检测中，这个是没什么用的

        $msg = $server->getMessage();

        $refresh_token = Redis::get($app_id) ?? Xcx::where('app_id', $app_id)->fisrt()['refresh_token'];
        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token);

        if ($msg['MsgType'] == 'text') {
            // if ($msg['Content'] == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
            //     $refresh_token = Redis::get($app_id) ?? Xcx::where('app_id', $app_id)->fisrt()['refresh_token'];
            //     $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token);
            //     $miniProgram->customer_service->message($msg['Content'] . '_callback')
            //         ->from($msg['ToUserName'])->to($msg['FromUserName'])->send();
            //     die;
            // }
        } elseif ($msg['MsgType'] == 'event') {

            if($msg['Event'] == 'weapp_audit_success') {
                OpenPlatform::saveAudit($app_id, $msg, 0);
            }

            if($msg['Event'] == 'weapp_audit_fail') {
                OpenPlatform::saveAudit($app_id, $msg, 1);
            }
            
            die;
        }

        return $openPlatform->server->serve();
    }
}
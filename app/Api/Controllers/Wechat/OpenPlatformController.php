<?php 

namespace App\Api\Controllers\Wechat;

use App\Models\Commons\Xcx;
use App\Services\OpenPlatform;
use App\Models\Wechat\Experiencer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
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
        
        return 'error';
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
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        $extJson = OpenPlatform::getExtJson();
        return $miniProgram->code->commit(3, $extJson, '1.0', '任意门网络工作室小程序');
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
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->code->submitAudit();                
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
        // $official = $this->initOfficialAccount();
        $openPlatform = OpenPlatform::getApp();
        $server      = $openPlatform->server;

        $server->push(EventHandler::class, Message::EVENT); // 检测中，这个是没什么用的

        $msg = $server->getMessage();

        if ($msg['MsgType'] == 'text') {
            if ($msg['Content'] == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
                $miniProgram = $openPlatform->miniProgram($app_id, Cache::get($app_id));
                $miniProgram->customer_service->message($msg['Content'] . '_callback')
                    ->from($msg['ToUserName'])->to($msg['FromUserName'])->send();
                die;
            } elseif (strpos($msg['Content'], 'QUERY_AUTH_CODE') == 0) {
                echo '';
                $code           = substr($msg['Content'], 16);
                $authorizerInfo = $openPlatform->handleAuthorize($code)['authorization_info'];
                Cache::put(
                    $authorizerInfo['authorizer_appid'], 
                    $authorizerInfo['authorizer_refresh_token'],
                    7200
                );
                $miniProgram = $openPlatform->miniProgram(
                    $authorizerInfo['authorizer_appid'], 
                    $authorizerInfo['authorizer_refresh_token']
                );
                $miniProgram->customer_service->message($code . "_from_api")
                            ->from($msg['ToUserName'])->to($msg['FromUserName'])->send();
            }
        } elseif ($msg['MsgType'] == 'event') {
            $miniProgram = $openPlatform->miniProgram($app_id, Cache::get($app_id));
            $miniProgram->customer_service->message($msg['Event'] . 'from_callback')
                ->to($msg['FromUserName'])->from($msg['ToUserName'])->send();
            die;
        }

        return $openPlatform->server->serve();
    }
}
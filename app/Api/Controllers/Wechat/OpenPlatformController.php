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
            $data = OpenPlatform::initOpenPlatform($message['AuthorizationCode'], 'add');
        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) {
            $data = OpenPlatform::initOpenPlatform($message['AuthorizationCode'], 'set');
        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) {
            OpenPlatform::unAuthorized();
        }, Guard::EVENT_UNAUTHORIZED);

        return $server->serve();
    }

    

    public function user_authorize() 
    {
        $openPlatform = OpenPlatform::getApp();        
        $url = $openPlatform->getPreAuthorizationUrl('https://www.rdoorweb.com/wechat/authorized');
        return view('/wechat',['url' => $url]);
    }

    public function bind_tester()
    {
        $wechatid = request()->wechatid;
        $miniProgram = OpenPlatform::getMiniProgram();
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
        $miniProgram = OpenPlatform::getMiniProgram();
        $msg = $miniProgram->tester->unbind($wechatid);
        if($msg['errcode'] == 0) {
            Experiencer::where('wechatid', $wechatid)->delete();
            return response()->json(['status' => 'success', 'msg' => '解绑成功']);
        }
        return response()->json(['status' => 'error', 'msg' => '系统繁忙']);
    }

    public function authorized() 
    {
        return 'success';
    }

    public function commit()
    {
        $miniProgram = OpenPlatform::getMiniProgram();
        $extJson = OpenPlatform::createExtJson();
        return $miniProgram->code->commit(0, $extJson, '1.0', '任意门网络工作室小程序');
    }

    public function get_qrcode()
    {
        $miniProgram = OpenPlatform::getMiniProgram();
        return $miniProgram->code->getQrCode('pages/index/index');
    }

    public function get_category()
    {
        $miniProgram = OpenPlatform::getMiniProgram();
        return $miniProgram->code->getCategory();
    }

    public function get_page()
    {
        $miniProgram = OpenPlatform::getMiniProgram();
        return $miniProgram->code->getPage();        
    }

    public function token() 
    {

    }
}
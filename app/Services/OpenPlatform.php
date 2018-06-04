<?php
namespace App\Services;

use App\Models\Commons\Xcx;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class OpenPlatform 
{

    public static function getApp()
    {
        $openPlatform = \EasyWeChat\Factory::openPlatform(config('wechat.open_platform.default'));  
        return $openPlatform;
    }

    public static function getMiniProgram(int $xcx_id)
    {
        $xcx = Xcx::find($xcx_id);
        $app = self::getApp()->miniProgram($xcx['app_id'], $xcx['refresh_token']);
        return $app;
    }

    public static function openPlatformPost($url,$data = '') 
    {   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //开启该选项时，curl_exec()执行时不会直接输出都页面，而是返回字符串格式的数据，可接收 => $data = curl_exec($ch);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function initMiniProgram(int $xcx_id, string $auth_code)
    {
        $openPlatform = self::getApp();

        $info = $openPlatform->handleAuthorize($auth_code)['authorization_info'];

        //获取小程序实例
        $miniProgram = $openPlatform->miniProgram($info['authorizer_appid'], $info['authorizer_refresh_token']);
        //设置域名
        $miniProgram->domain->modify(self::miniProgramModifyDomain('add'));
        
        //获取小程序信息
        $miniProgramInfo = $openPlatform->getAuthorizer($info['authorizer_appid']);        
        //保存
        self::saveMiniProgram($xcx_id,$miniProgramInfo);
        
    }

    public static function updateMiniProgram(string $app_id)
    {
        $Xcx = Xcx::where(['app_id' => $app_id, 'authorization_status' => 1])->first();

        $openPlatform = self::getApp();
        //获取小程序信息
        $miniProgramInfo = $openPlatform->getAuthorizer($app_id);        
        //保存
        self::saveMiniProgram($Xcx->id, $miniProgramInfo);
        
    }

    public static function unAuthorized(string $app_id)
    {
        Xcx::where('app_id', $app_id)->update(['authorization_status' => -1]);   
    }

    public static function miniProgramModifyDomain(string $method)
    {
        if($method == 'get') {
            $data = ["action" =>  $method];
        }else {
            $data = [
                "action" =>  $method,
                "requestdomain" => ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
                "wsrequestdomain" => ["wss://www.rdoorweb.com","wss://www.rdoorweb.com"],
                "uploaddomain" => ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
                "downloaddomain"=> ["https://www.rdoorweb.com","https://www.rdoorweb.com"],
            ];
        }

        return $data;
    }

    public static function saveMiniProgram(int $xcx_id, array $miniProgram)
    {
        $data = [];
        $authorizer_info = $miniProgram['authorizer_info'];
        $data['head_img'] = $authorizer_info['head_img'];
        $data['nick_name'] = $authorizer_info['nick_name'];
        $data['user_name'] = $authorizer_info['user_name'];
        $data['qrcode_url'] = $authorizer_info['qrcode_url'];
        $data['principal_name'] = $authorizer_info['principal_name'];
        $data['signature'] = $authorizer_info['signature'];
        $data['qrcode_url'] = $authorizer_info['qrcode_url'];
        $data['service_type_info'] = $authorizer_info['service_type_info']['id'];
        $data['verify_type_info'] = $authorizer_info['verify_type_info']['id'];
        $data['business_info'] = json_encode($authorizer_info['business_info']);
        $data['network'] = json_encode($authorizer_info['MiniProgramInfo']['network']);
        $data['categories'] = json_encode($authorizer_info['MiniProgramInfo']['categories']);
        $data['visit_status'] = $authorizer_info['MiniProgramInfo']['visit_status'];
        $data['app_id'] = $miniProgram['authorization_info']['authorizer_appid'];         
        $data['refresh_token'] = $miniProgram['authorization_info']['authorizer_refresh_token'];
        $data['func_info'] = json_encode($miniProgram['authorization_info']['func_info']); 
        $data['authorization_status'] = 1; 
        Xcx::where('id', $xcx_id)->update($data);
    }

    public static function getExtJson(int $xcx_id) : string
    {
        $xcx = Xcx::find($xcx_id);
        $ext = [
            'extEnable' => true,
            'extAppid' => $xcx->app_id,
            'ext' => [
                'xcx_flag' => $xcx->xcx_flag
            ]
        ];

        return json_encode($ext);
    }

    public static function saveAudit(string $app_id, array $msg, int $status) : Audit
    {
        $xcx_id = Xcx::where('app_id', $app_id)->first()['id'];
        $miniProgram = self::getMiniProgram($xcx_id);
        $auditMsg = json_decode($miniProgram->code->getLatestAuditStatus(), true);
        $audit = Audit::where('audit_id', $auditMsg['auditid'])->first();        
        $audit->status = $status;
        $audit->org_id = $msg['ToUserName'];
        $audit->sys_id = $msg['FromUserName'];
        $audit->create_time = $msg['CreateTime'];
        $audit->succ_time = $msg['SuccTime'];
        $audit->fail_time = $msg['FailTime'];
        $audit->reason  = $msg['Reason'];
        return $audit->save();
    }

    public static function getVersion() : int
    {
        $version = Redis::get('version');
        if($version) {
            Redis::set('version', ++$version);
        }else {
            $version = 10000;
            Redis::set('version', $version);
        }
        
        return $version;
    }
}

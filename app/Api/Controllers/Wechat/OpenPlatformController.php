<?php 

namespace App\Api\Controllers\Wechat;

use App\Models\Commons\Xcx;
use App\Services\OpenPlatform;
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
        $url = $openPlatform->getPreAuthorizationUrl('http://www.rdoorweb.com/wechat/authorized');
        return view('/wechat',['url' => $url]);
    }

    public function authorized() 
    {
        
        return 'success';
    }

    public function token() 
    {
        $data = array (
            'head_img' => 'http://wx.qlogo.cn/mmopen/5uic18jZ3I7ibpc0CqlcBOGRPrPHEOT7QOZywicdc2kofFJWgrotx5piaqrhFf0D0QsdhwCiapbAUCsBoXcPibViaXk5rJkMibaujiaRic/0',
            'nick_name' => '任意门网络工作室',
            'user_name' => 'gh_b5d413d15359',
            'qrcode_url' => 'http://mmbiz.qpic.cn/mmbiz_jpg/E9OTCTFJTuzv9RVv7ibeLl3VeJWiaGsIftGnXuswCassLQzkmhgasjGn7pDlK1LeibD5fNSqsDbhnGxflwcws67jw/0',
            'principal_name' => '中山火炬开发区任意门网络工作室',
            'signature' => '中山市任意门网络工作室，专注于公众平台开发以及网站建设,SEO搜索优化等',
            'service_type_info' => 0,
            'verify_type_info' => 0,
            'business_info' => 'a:5:{s:8:"open_pay";i:0;s:10:"open_shake";i:0;s:9:"open_scan";i:0;s:9:"open_card";i:0;s:10:"open_store";i:0;}',
            'network' => 'a:5:{s:13:"RequestDomain";a:1:{i:0;s:24:"https://www.rdoorweb.com";}s:15:"WsRequestDomain";a:1:{i:0;s:22:"wss://www.rdoorweb.com";}s:12:"UploadDomain";a:1:{i:0;s:24:"https://www.rdoorweb.com";}s:14:"DownloadDomain";a:1:{i:0;s:24:"https://www.rdoorweb.com";}s:9:"BizDomain";a:0:{}}',
            'categories' => 'a:0:{}',
            'visit_status' => 0,
            'app_id' => 'wxc1fb7bd6c21cb0cc',
            'refresh_token' => 'refreshtoken@@@ML5cp_wMRY6L9nOlKUDzyOzsJ4uIHAJPnL_KT-07Jr8',
            'func_info' => 'a:9:{i:0;a:1:{s:18:"funcscope_category";a:1:{s:2:"id";i:17;}}i:1;a:2:{s:18:"funcscope_category";a:1:{s:2:"id";i:18;}s:12:"confirm_info";a:3:{s:12:"need_confirm";i:0;s:15:"already_confirm";i:0;s:11:"can_confirm";i:0;}}i:2;a:1:{s:18:"funcscope_category";a:1:{s:2:"id";i:19;}}i:3;a:2:{s:18:"funcscope_category";a:1:{s:2:"id";i:25;}s:12:"confirm_info";a:3:{s:12:"need_confirm";i:0;s:15:"already_confirm";i:0;s:11:"can_confirm";i:0;}}i:4;a:2:{s:18:"funcscope_category";a:1:{s:2:"id";i:30;}s:12:"confirm_info";a:3:{s:12:"need_confirm";i:0;s:15:"already_confirm";i:0;s:11:"can_confirm";i:0;}}i:5;a:2:{s:18:"funcscope_category";a:1:{s:2:"id";i:31;}s:12:"confirm_info";a:3:{s:12:"need_confirm";i:0;s:15:"already_confirm";i:0;s:11:"can_confirm";i:0;}}i:6;a:1:{s:18:"funcscope_category";a:1:{s:2:"id";i:36;}}i:7;a:1:{s:18:"funcscope_category";a:1:{s:2:"id";i:37;}}i:8;a:1:{s:18:"funcscope_category";a:1:{s:2:"id";i:40;}}}',
            'authorization_status' => 1,
            );
        $miniProgram = Xcx::where('id', 33)->update($data);
        
    }
}
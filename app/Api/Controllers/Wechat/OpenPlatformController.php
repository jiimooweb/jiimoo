<?php 

namespace App\Api\Controllers\Wechat;

use App\Utils\Wechat;
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
            OpenPlatform::unAuthorized($message['AuthorizerAppid']);
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

        $url = $openPlatform->getPreAuthorizationUrl('https://www.rdoorweb.com/wechat/'. $xcx_id. '/save_miniprogram');

        // return response()->json(['status' => 'unauthorize', 'data' => $url]);
        return view('wechat',['url' => $url]);
    }

    public function save_miniprogram() 
    {  
        $auth_code = request()->get('auth_code');
        $xcx_id = request()->xcx_id;
        OpenPlatform::initMiniProgram($xcx_id,$auth_code);
        return 'success';
    }

    public function bind_tester()
    {
        $wechatid = request()->wechatid;
        $xcx_id = request()->xcx_id;
        $miniProgram = OpenPlatform::getMiniProgram($xcx_id);
        $msg = $miniProgram->tester->bind($wechatid);
        if($msg['errcode'] == 0) {
            Experiencer::create(['xcx_id' => $xcx_id,'wechatid' => $wechatid, 'userstr' => $msg['userstr']]);
            return response()->json(['status' => 'success', 'msg' => '绑定成功']);
        }
        
        return Wechat::retMsg($msg);
        
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

    public function get_testers()
    {
        $experiencers = Experiencer::where('xcx_id', request()->xcx_id)->get();
        return response()->json(['status' => 'success', 'data' => $experiencers]);
    }

    public function commit()
    {
        $xcx_id = request()->xcx_id;
        $template_id = request()->template_id;
        $audit = Audit::where(['xcx_id' => $xcx_id, 'template_id' => $template_id])->first();
        $version = $audit['version'] ?? OpenPlatform::getVersion();
        $extJson = OpenPlatform::getExtJson($xcx_id); 

        $miniProgram = OpenPlatform::getMiniProgram($xcx_id);
        $msg = $miniProgram->code->commit($template_id, $extJson, $version, '任意门网络工作室小程序');

        if($msg['errcode'] == 0) {
            $xcx = Xcx::find($xcx_id);
            $data = [
                'xcx_id' => $xcx_id,
                'app_id' => $xcx['app_id'],                
                'template_id' => $template_id,
                'version' => $version,
                'status' => 3,
            ];
            if($audit) {
                $data['status'] = $audit['status'];
                Audit::where('id', $audit['id'])->update($data);
            }else {
                Audit::create($data);
            }   
        }

        return Wechat::retMsg($msg);
    }

    public function get_qrcode()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        $file = $miniProgram->code->getQrCode('pages/index/index');
        file_put_contents('php://temp/Qrcode.png', '/Qrcode.png');
        die;
        return $miniProgram->code->getQrCode('pages/index/index');
    }

    public function get_category()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->getCategory());
    }

    public function get_page()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->getPage());        
    }

    public function get_audits()
    {
        $xcx_id = request()->xcx_id;
        $audits = Audit::where('xcx_id', $xcx_id)->get();
        return response()->json(['status' => 'success', 'data' => $audits]);
    }

    public function submit_audit()
    {
        $xcx_id = request()->xcx_id;
        $audit_id = request('audit_id');
        $itemList = request('item_list');
        
        $miniProgram = OpenPlatform::getMiniProgram($xcx_id);
        $itemList = [
                        [
                            "address" => "pages/applicants/applicants",
                            "tag" => "人员 资源",
                            "first_class" => "餐饮",
                            "second_class"=> "菜谱",
                            "first_id" => 220,
                            "second_id" => 225,
                            "title" => "首页"
                        ]
                    ];

        $msg = $miniProgram->code->submitAudit($itemList);    
        if($msg['errcode'] == 0) {
            $data = [
                'audit_id' => $msg['auditid'],
                'status' => 2,
                'item_list' => json_encode($itemList)
            ];
            Audit::where('id', $audit_id)->update($data);
        }

        return Wechat::retMsg($msg);
    }

    public function get_auditstatus()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->getAuditStatus(request()->audit_id)); 
    }

    public function get_latest_auditstatus()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->getLatestAuditStatus()); 
    }

    public function release() 
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->release()); 
    }

    public function undocodeaudit()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->withdrawAudit()); 
    }

    public function rollback_release()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->rollbackRelease()); 
    }

    public function change_visitstatus()
    {
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->code->changeVisitStatus(request()->action));
    }

    public function get_notice_templates()
    {
        $xcx_id = request()->xcx_id;
        $templates = \App\Models\Wechat\NoticeTemplate::getTemplate($xcx_id);
        return $templates;
    }

    //模板消息
    public function template_list()
    {
        $page = request()->page ?? 1;
        $count = 20;
        $offset = $count * ($page - 1);
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->template_message->list($offset, $count));
    }

    public function get_template()
    {
        $template_id = request()->template_id;
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->template_message->get($template_id));
    }

    public function add_template()
    {
        $id = request()->id;
        $xcx_id = request()->xcx_id;
        $xcxNotice = \App\Models\Wechat\NoticeTemplate::where(['notice_template_id' => $id, 'xcx_id' => $xcx_id])->first();
        if($xcxNotice) {
            $xcxNotice->status = $xcxNotice->status ? 0 : 1;
            $xcxNotice->save();
            return response()->json(['status' => 'success', 'msg' => '操作成功']);
        }
        $template = \App\Models\Commons\NoticeTemplate::find($id);

        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        $msg = $miniProgram->template_message->add($template['template_id'], explode(',', $template['keyword_id_list']));

        if($msg['errcode'] == 0) {
            $data = [
                'xcx_id' => $xcx_id,
                'notice_template_id' => $id,
                'template_id' => $msg['template_id'],
                'status' => 1
            ];
            \App\Models\Wechat\NoticeTemplate::create($data);
            return response()->json(['status' => 'success', 'msg' => '开启成功']);
        }

        return Wechat::retMsg($msg);
    }

    public function get_templates()
    {
        $page = request()->page ?? 1;
        $count = 20;
        $offset = $count * ($page - 1);
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->template_message->getTemplates($offset, $count));
    }

    public function del_template()
    {
        $template_id = request()->$template_id;
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return Wechat::retMsg($miniProgram->template_message->delete($template_id));
    }


    //小程序代码模板
    public function code_tpl_get_drafts()
    {
        $openPlatform = OpenPlatform::getApp();   
        return Wechat::retMsg($openPlatform->code_template->getDrafts());                  
    }

    public function code_tpl_create_from_draft()
    {
        $openPlatform = OpenPlatform::getApp();   
        return Wechat::retMsg($openPlatform->code_template->createFromDraft(request()->draft_id));                
    }

    public function code_tpl_list()
    {
        $openPlatform = OpenPlatform::getApp();   
        return Wechat::retMsg($openPlatform->code_template->list()); 
    }

    public function code_tpl_delete()
    {
        $openPlatform = OpenPlatform::getApp();   
        return Wechat::retMsg($openPlatform->code_template->delete(request()->template_id)); 
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

    public function summary_trend(){
        $miniProgram = OpenPlatform::getMiniProgram(request()->xcx_id);
        return $miniProgram->data_cube->summaryTrend();
    }

}
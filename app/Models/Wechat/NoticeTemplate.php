<?php

namespace App\Models\Wechat;

use Illuminate\Database\Eloquent\Model;

class NoticeTemplate extends Model
{
    protected $guarded = [];

    protected $table = 'xcx_notice_templates';

    public function notice_template()
    {
        return $this->belongsTo(\App\Models\Commons\NoticeTemplate::class);
    }

    public static function getTemplate(int $xcx_id) : array
    {
        $templates = \App\Models\Commons\NoticeTemplate::get()->load('xcx_notice_templates')->toArray();
        foreach($templates as &$template) {
            $template['status'] = 0;            
            foreach($template['xcx_notice_templates']  as $xcx_notice_templates) {
                if($xcx_notice_templates['xcx_id'] == $xcx_id) {
                    $template['status'] = $xcx_notice_templates['status'];
                    break;
                }
            }
            unset($template['xcx_notice_templates']);
        }

        return $templates;
    }

    public static function getTemplateIdByMark($mark) {
        $xcx_id = session('xcx_id');
        $template = \App\Models\Commons\NoticeTemplate::where('mark', $mark)->with(['xcx_notice_templates'])->first();
        foreach($template->xcx_notice_templates as $xcx_template) {
            if($xcx_template->xcx_id == $xcx_id) {
                $template_id = $xcx_template->template_id;
                break;
            }
        }
        return $template_id;
    }
}

<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;

class NoticeTemplate extends Model
{
    protected $table = 'notice_templates';

    protected $guarded= [];

    protected $hidden = ['created_at', 'updated_at'];

    public function xcx_notice_templates()
    {
        return $this->hasMany(\App\Models\Wechat\NoticeTemplate::class, 'notice_template_id', 'id');
    }


}

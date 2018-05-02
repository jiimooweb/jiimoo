<?php

namespace App\Models\Commons;

use EasyWeChat\Factory;
use App\Models\Commons\AdminUser;
use Illuminate\Database\Eloquent\Model;

class Xcx extends Model
{
    //
    protected $guarded=[];
    protected $table = 'xcxs';

    public function user()
    {
        return $this->belongsToMany(AdminUser::class,'users_xcxs',
            'xcx_id','user_id')->
            withPivot(['xcx_id','user_id']);
    }

    public function hasUser($id) 
    {
        return $this->user()->where('user_id', $id)->count();
    }

    public function assignUser($user)
    {
        return $this->xcxs()->save($user);
    }

    public function detachUser($user)
    {
        return $this->detachXcx()->detach($user);
    }

    //获取小程序实例
    public static function getApp($xcx_id, $type='array') 
    {
        $miniProgram = self::find($xcx_id);
        
        $config = [
            'app_id' => $miniProgram->app_id,
            'secret' => $miniProgram->app_secret,
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => $type,
        
            'log' => [
                'level' => 'debug',
                'file' => 'storage/logs/wechat.log',
            ],
        ];
        
        $app = Factory::miniProgram($config);

        return $app;
    }


}

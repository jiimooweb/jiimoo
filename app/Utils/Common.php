<?php

namespace App\Utils;

class Common 
{
    public static function getTree($arr,$pid = 0,$step = 0){
        global $tree;
        foreach($arr as $key=>$val) {
            if($val['pid'] == $pid) {
                $flg = str_repeat('-',$step);
                $val['name'] = $flg.$val['name'];
                $tree[] = $val;
                self::GetTree($arr , $val['id'] ,$step+1);
            }
        }
        return $tree;
    }
}
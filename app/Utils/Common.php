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

    /**
     * @param $arr
     * @param $key_name
     * @return array
     * 将数据库中查出的列表以指定的 id 作为数组的键名 
     */
    public static function convert_arr_key($arr, $key_name)
    {
        $result = array();
        foreach($arr as $key => $val){
            $result[$val[$key_name]] = $val;
        }
        return $result;
    }
}
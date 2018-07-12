<?php

namespace App\Services;

class Map
{
    private static $EARTH_RADIUS = 6378137;//赤道半径(单位m)

    /**
	 * 转化为弧度(rad)
	 * */
	private static function rad($d)
	{
	   return $d * pi() / 180.0;
	}
	/**
	 * 基于googleMap中的算法得到两经纬度之间的距离,计算精度与谷歌地图的距离精度差不多，相差范围在0.2米以下
	 * @param lon1 第一点的精度
	 * @param lat1 第一点的纬度
	 * @param lon2 第二点的精度
	 * @param lat3 第二点的纬度
	 * @return 返回的距离，单位m
	 * */
	public static function GetDistance($lon1,$lat1,$lon2,$lat2)
	{
        $radLat1 = self::rad($lat1);
        $radLat2 = self::rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = self::rad($lon1) - self::rad($lon2);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2)* pow(sin($b/2),2)));
        $s = $s * self::$EARTH_RADIUS;
	   //s = Math.round(s * 10000) / 10000;
	   return $s;
	}

    

}

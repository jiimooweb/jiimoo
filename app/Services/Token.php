<?php

namespace App\Services;

use Exception;
use App\Utils\RoleScope;
use Illuminate\Support\Facades\Cache;

class Token
{

    // 生成令牌
    public static function generateToken()
    {
        $randChar = self::getRandChar(32);
        $timestamp = time();
        $tokenSalt = config('token.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }

    public static function getRandChar(int $length) : string
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }

    //验证token是否合法或者是否过期
    //验证器验证只是token验证的一种方式
    //另外一种方式是使用行为拦截token，根本不让非法token
    //进入控制器
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= RoleScope::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            return response()->json(['msg' => 'Token信息错误']);

        }
    }

    // 用户专有权限
    public static function needUserScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == RoleScope::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            return response()->json(['msg' => 'Token信息错误']);

        }
    }

    //管理员
    public static function needAdminScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == RoleScope::Admin) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            return response()->json(['msg' => 'Token信息错误']);

        }
    }

    /**
     * 获取当前token,key对应的value
     * @param string $keys
     * @return void result
     * @throws \app\lib\exception\TokenException
     */
    public static function getCurrentTokenVar($key)
    {
        $token = request()->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            return response()->json(['msg' => 'Token信息错误']);
        } else {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                return response()->json(['msg' => '尝试获取的Token变量并不存在']);
            }
        }
    }


    /**
     * 从缓存中获取当前用户指定身份标识
     * @param array $keys
     * @return array result
     * @throws \app\lib\exception\TokenException
     */
    public static function getCurrentIdentity(array $keys)
    {
        $token = request()->header('token');
        $identities = Cache::get($token);
        if (!$identities) {
            return response()->json(['msg' => 'Token信息错误']);
        } else {
            $identities = json_decode($identities, true);
            $result = [];
            foreach ($keys as $key) {
                if (array_key_exists($key, $identities)) {
                    $result[$key] = $identities[$key];
                }
            }
            return $result;
        }
    }


    /**
     * 当需要获取全局UID时，应当调用此方法
     *而不应当自己解析UID
     *
     */
    public static function getUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        $scope = self::getCurrentTokenVar('scope');
        if ($scope == RoleScope::Admin) {
            // 只有Super权限才可以自己传入uid
            // 且必须在get参数中，post不接受任何uid字段
            $userID = request()->input('uid');
            if (!$userID) {
                return response()->json(['msg' => '用户UID无效']);
            }
            return $userID;
        } else {
            return $uid;
        }
    }

    /**
     * 检查操作UID是否合法
     * @param $checkedUID
     * @return bool
     * @throws Exception
     */
    public static function isValidOperate($checkedUID)
    {
        if (!$checkedUID) {
            return response()->json(['msg' => '检查UID时必须传入一个被检查的UID']);
        }
        $currentOperateUID = self::getCurrentUid();
        if ($currentOperateUID == $checkedUID) {
            return true;
        }
        return false;
    }


    /**
     * 验证token是否正确
     * @param $token 
     * @return bool
     */
    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }





}
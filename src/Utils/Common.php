<?php

namespace Dymyw\Yaf\Utils;

/**
 * Class Common
 * @package Dymyw\Yaf\Utils
 */
class Common
{
    /**
     * 生成唯一令牌
     *
     * @param $userId
     * @return string
     */
    public static function getAccessToken($userId) : string
    {
        return md5(uniqid(mt_rand(), true) . $userId);
    }

    /**
     * 获取客户端 IP
     *
     * @return string
     */
    public static function getClientIp()
    {
        // Nginx 代理模式下，获取客户端真实 IP
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        // 客户端的 IP
        elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // 浏览当前页面的用户计算机的网关
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ips = array_filter($arr, function ($item) {
                return $item != 'unknown';
            });
            $ip = trim(array_shift($ips));
        }
        // 浏览当前页面的用户计算机的 IP
        elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        else {
            $ip = '0.0.0.0';
        }

        return $ip;
    }
}

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
    public static function getClientIp() : string
    {
        $ip = '0.0.0.0';

        // Nginx 代理模式下，获取客户端真实 IP
        if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        // 客户端的 IP
        elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // 浏览当前页面的用户计算机的网关
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ips as $ip) {
                if (!preg_match("/^(10│172.16│192.168)./", $ip)) {
                    break;
                }
            }
        }
        // 浏览当前页面的用户计算机的 IP
        elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * 获取随机小数
     *
     * @param int $num 小数点后多少位
     * @return float
     */
    public static function randDecimal($num = 3) : float
    {
        $max = pow(10, $num);

        return mt_rand(1, $max) / $max;
    }

    /**
     * 获取时间
     *
     * @return float
     */
    public static function getMicroTime()
    {
        list($usec, $sec) = explode(" ", microtime());

        return ((float) $usec + (float) $sec);
    }
}

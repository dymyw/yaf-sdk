<?php

namespace Dymyw\Yaf\Utils\Cache;

use Dymyw\Yaf\Utils\ConstMap;

/**
 * Class Key
 * @package Dymyw\Yaf\Utils\Cache
 */
class Key
{
    /**
     * 获取 Redis key
     *
     * @param string $keyPattern
     * @param array $params
     *          vType  缓存类型
     *          middle 缓存Key中间部分
     *          last   缓存Key结尾部分
     * @return string
     */
    public static function getRedisKey(string $keyPattern, $params = []) : string
    {
        switch ($params['vType']) {
            case 'hash':
                $keyPattern = ConstMap::HASH_TYPE . $keyPattern;
                break;
            case 'kv':
                $keyPattern = ConstMap::KV_TYPE . $keyPattern;
                break;
            case 'list':
                $keyPattern = ConstMap::LIST_TYPE . $keyPattern;
                break;
            case 'set':
                $keyPattern = ConstMap::SET_TYPE . $keyPattern;
                break;
            case 'zset':
                $keyPattern = ConstMap::SORT_SET_TYPE . $keyPattern;
                break;
            default:
                break;
        }

        return isset($params['middle']) ? sprintf($keyPattern, $params['middle'], $params['last']) : sprintf($keyPattern, $params['last']);
    }
}

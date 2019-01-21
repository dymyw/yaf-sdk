<?php

namespace Dymyw\Yaf\Model;

use Medoo\Medoo;

/**
 * Class AbstractModel
 * @package Dymyw\Yaf\Model
 */
abstract class AbstractModel
{
    /**
     * 数据库资源
     *
     * @var Medoo|null
     */
    protected static $database = null;

    /**
     * 数据库名称
     *
     * @var string
     */
    protected $tableName = 'test';

    /**
     * AbstractModel constructor.
     * @param string $db
     * @param null $options
     */
    public function __construct($db = 'default_db', $options = null)
    {
        if (defined('static::TABLE_NAME')) {
            $this->tableName = static::TABLE_NAME;
        }

        self::$database = Connection::getInstance($db, $options);
    }

    /**
     * 获取当前表名
     *
     * @return string
     */
    public function getTableName() : string
    {
        return $this->tableName;
    }

    /**
     * 获取执行的 SQL 日志
     *
     * @return array
     */
    public function getSqlLog()
    {
        return self::$database->log();
    }

    /**
     * 获取最后一条执行的 SQL
     *
     * @return mixed|null|string|string[]
     */
    public function getLastSql()
    {
        return self::$database->last();
    }

    /**
     * 查询列表
     *
     * @param $columns
     * @param null $where
     * @param null $join
     * @return array
     */
    public function select($columns, $where = null, $join = null)
    {
        if ($join) {
            $list = self::$database->select($this->tableName, $join, $columns, $where);
        } else {
            $list = self::$database->select($this->tableName, $columns, $where);
        }

        return $list ?: [];
    }

    /**
     * 查询一条
     *
     * @param $columns
     * @param null $where
     * @param null $join
     * @return array|mixed
     */
    public function get($columns, $where = null, $join = null)
    {
        if ($join) {
            return self::$database->get($this->tableName, $join, $columns, $where);
        } else {
            return self::$database->get($this->tableName, $columns, $where);
        }
    }

    /**
     * 获取总数
     *
     * @param $columns
     * @param null $where
     * @param null $join
     * @return int
     */
    public function count($columns, $where = null, $join = null)
    {
        if ($join) {
            $count = self::$database->count($this->tableName, $join, $columns, $where);
        } else {
            $count = self::$database->count($this->tableName, $columns, $where);
        }

        return $count ?: 0;
    }

    /**
     * 分页
     *
     * @param $columns
     * @param null $where
     * @param null $join
     * @param array $pageOptions
     * @return array
     */
    public function getPaginatedList($columns, $where = null, $join = null, $pageOptions = [])
    {
        $result = [
            'list' => [],
        ];

        if ($pageOptions) {
            $where['LIMIT'] = [
                ($pageOptions['page_num'] - 1) * $pageOptions['page_limit'],
                $pageOptions['page_limit'],
            ];
        }

        $result['list'] = $this->select($columns, $where, $join);

        if ($pageOptions && $pageOptions['need_pagination']) {
            unset($where['LIMIT']);
            $columns    = '*';
            $count      = $this->count($columns, $where, $join);

            $result['total_page']   = ceil($count / $pageOptions['page_limit']);
            $result['total_cnt']    = $count;
            $result['page_limit']   = $pageOptions['page_limit'];
        }

        return $result;
    }

    /**
     * 判断数据是否存在
     *
     * @param $where
     * @param null $join
     * @return bool
     */
    public function has($where, $join = null)
    {
        if ($join) {
            return self::$database->has($this->tableName, $join, $where);
        } else {
            return self::$database->has($this->tableName, $where);
        }
    }

    /**
     * 插入数据
     *
     * @param $data
     * @return bool|\PDOStatement
     */
    public function insert($data)
    {
        return self::$database->insert($this->tableName, $data);
    }

    /**
     * 获取最后插入 ID
     *
     * @return int|mixed|string
     */
    public function getLastInsertId()
    {
        $id = self::$database->id();

        return is_numeric($id) ? intval($id) : $id;
    }

    /**
     * 更新数据
     *
     * @param $data
     * @param null $where
     * @return bool|\PDOStatement
     */
    public function update($data, $where = null)
    {
        return self::$database->update($this->tableName, $data, $where);
    }
}

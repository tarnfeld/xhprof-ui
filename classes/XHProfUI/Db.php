<?php

namespace XHProfUI;

/**
 * Abstract class to represent a database back-end
 *
 * @package xhprof (https://github.com/preinheimer/xhprof)
 */
abstract class Db
{
    protected $config;
    public $linkID;

    public function __construct($config)
    {
        $this->config = $config;
    }

    abstract public function connect();
    abstract public function query($sql);
    abstract public function escape($str);
    abstract public function affectedRows();

    public static function unixTimestamp($field)
    {
        throw new RuntimeException("Method '" . get_called_class() . "::" . __FUNCTION__ . "' not implemented");
    }

    public static function dateSub($days)
    {
        throw new RuntimeException("Method '" . get_called_class() . "::" . __FUNCTION__ . "' not implemented");
    }

    public static function getNextAssoc($resultSet)
    {
        throw new RuntimeException("Method '" . get_called_class() . "::" . __FUNCTION__ . "' not implemented");
    }
}

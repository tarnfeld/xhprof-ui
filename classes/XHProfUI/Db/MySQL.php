<?php

namespace XHProfUI\Db;

/**
 * Database back-end class for MySQL
 *
 * @package xhprof (https://github.com/preinheimer/xhprof)
 */
class MySQL extends \XHProfUI\Db
{
  protected $link_id = null;

  public function connect()
  {
    $this->link_id = mysql_connect($this->config["hostname"], $this->config["username"], $this->config["password"]);
    if ($this->link_id === FALSE)
    {
      xhprof_error("Could not connect to db");
      throw new Exception("Unable to connect to database");
      return false;
    }

    $this->query("SET NAMES utf8");
    mysql_select_db($this->config["database"], $this->link_id);
  }

  public function query($sql)
  {
    return mysql_query($sql, $this->link_id);
  }

  public static function getNextAssoc($resultSet)
  {
    return mysql_fetch_assoc($resultSet);
  }

  public function escape($str)
  {
    return mysql_real_escape_string($str);
  }

  public function affectedRows()
  {
    return mysql_affected_rows($this->link_id);
  }

  public static function unixTimestamp($field)
  {
    return 'UNIX_TIMESTAMP('.$field.')';
  }

  public static function dateSub($days)
  {
    return 'DATE_SUB(CURDATE(), INTERVAL '.$days.' DAY)';
  }
}

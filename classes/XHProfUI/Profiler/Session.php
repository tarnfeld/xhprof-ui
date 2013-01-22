<?php

namespace XHProfUI\Profiler;

/**
* Class for managing each profiled session
*/
class Session
{

  /**
   * Table name
   */
  public static $table = "sessions";

  /**
   * Session properties
   */
  public $id;
  public $url;
  public $timestamp;
  public $serverName;
  public $serverId;
  public $remoteAddress;
  public $profileData;
  public $cookieData;
  public $getData;
  public $postData;
  public $isAjax;
  public $peakMemory;
  public $wallTime;
  public $cpu;
  public $user;

  /**
   * Cached connection to the db
   */
  protected $_db;

  /**
   * Save the current session
   *
   * @author Tom Arnfeld <tom@duedil.com>
   * @param \XBProfUI\Db $db
   * @param array $config
   * @return Session
   */
  public static function save(\XBProfUI\Db $db)
  {
    $config = \XHProfUI\Config::cache();

    $session = new static($db);
    $data = xhprof_disable();

    // Set the various custom properties
    $session->id = $session->_getSessionId();
    $session->url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
    $session->timestamp = $_SERVER['REQUEST_TIME'];
    $session->serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
    $session->remoteAddress = $_SERVER['REMOTE_ADDR'];
    $session->isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    $session->peakMemory = isset($data['main()']['pmu']) ? $data['main()']['pmu'] : '';
    $session->wallTime = isset($data['main()']['wt']) ? $data['main()']['wt'] : '';
    $session->cpu = isset($data['main()']['cpu']) ? $data['main()']['cpu'] : '';

    $user = posix_getpwuid(posix_getuid());
    $session->user = $user["name"];

    $session->serverId = $config["app"]["server_id"];

    // Set the serialized objects
    $session->profileData = $data;
    $session->cookieData = $_COOKIE;
    $session->getData = $_GET;
    $session->postData = $_POST;

    if (!$session->_save()) {
      return null;
    }

    return $session;
  }

  /**
   * Return an array of sessions based on the given query
   *
   * @author Tom Arnfeld <tom@duedil.com>
   * @param array $search_query
   * @param integer $limit
   * @return array[Session]
   */
  public static function getSessions(\XHProfUI\Db $db, array $search_query, $limit = 25)
  {
    // Work out the where clause
    $where = null;
    foreach ($search_query as $field => $value) {
      if (!$where) $where = "WHERE";
      else $where .= " AND";

      $where .= " `" . $field . "` = '" . $db->escape($value).  "'";
    }

    // Build the query
    $query = "SELECT *
              FROM `" . static::$table . "`
              {$where}
              LIMIT {$limit}";

    $r = $db->query($query);
    $results = array();

    while ($row = $db->getNextAssoc($r)) {
      $session = new static($db);
      $session->_setFromArray($row);

      $results[$session->id] = $session;
    }

    return $results;
  }

  /**
   * Create a new query
   *
   * @author Tom Arnfeld <tom@duedil.com>
   * @param array $config
   */
  protected function __construct(\XHProfUI\Db $db)
  {
    $this->_db = $db;
  }

  /**
   * Get a uniq ID for this session
   *
   * @author Tom Arnfeld <tom@duedil.com>
   * @return string
   */
  protected function _getSessionId()
  {
    return uniqid();
  }

  // Actuall save
  protected function _save()
  {
    $config = \XHProfUI\Config::cache();
    $serializer = $config["profiler"]["serializer"];

    $query = "INSERT INTO `" . static::$table . "`
              (`id`, `url`, `timestamp`, `server_name`, `server_id`, `remote_address`, `is_ajax`, `peak_memory`, `wall_time`, `cpu`, `profile_data`, `cookie_data`, `get_data`, `post_data`)
              VALUES(
                '" . $this->_db->escape($this->id) . "',
                '" . $this->_db->escape($this->url) . "',
                '" . $this->_db->escape($this->timestamp) . "',
                '" . $this->_db->escape($this->serverName) . "',
                '" . $this->_db->escape($this->serverId) . "',
                '" . $this->_db->escape($this->remoteAddress) . "',
                '" . $this->_db->escape($this->isAjax) . "',
                '" . $this->_db->escape($this->peakMemory) . "',
                '" . $this->_db->escape($this->wallTime) . "',
                '" . $this->_db->escape($this->cpu) . "',
                '" . $this->_db->escape(\XHProfUI\Serializer::serialize($this->profileData, $serializer)) . "',
                '" . $this->_db->escape(\XHProfUI\Serializer::serialize($this->cookieData, $serializer)) . "',
                '" . $this->_db->escape(\XHProfUI\Serializer::serialize($this->getData, $serializer)) . "',
                '" . $this->_db->escape(\XHProfUI\Serializer::serialize($this->postData, $serializer)) . "'
              );";

    $this->_db->query($query);
    if ($this->_db->affectedRows()) {
      return true;
    }

    return false;
  }

  protected function _setFromArray($array)
  {
    $config = \XHProfUI\Config::cache();

    $fields = array(
      "id" => array("id", "string"),
      "url" => array("url", "string"),
      "timestamp" => array("timestamp", "int"),
      "server_name" => array("serverName", "string"),
      "server_id" => array("serverId", "string"),
      "remote_address" => array("remoteAddress", "string"),
      "profile_data" => array("profileData", "serialized"),
      "cookie_data" => array("cookieData", "serialized"),
      "get_data" => array("getData", "serialized"),
      "post_data" => array("postData", "serialized"),
      "is_ajax" => array("isAjax", "bool"),
      "peak_memory" => array("peakMemory", "int"),
      "wall_time" => array("wallTime", "int"),
      "cpu" => array("cpu", "int"),
      "user" => array("user", "string")
    );

    foreach ($array as $k => $v)
    {
      if (isset($fields[$k])) {

        // Type mapping
        if (isset($fields[$k][1])) {
          switch ($fields[$k][1]) {

            case "int":
              $v = (int) $v;
              break;

            case "bool":
              $v = (bool)(int) $v;
              break;

            case "serialized":
              $v = \XHProfUI\Serializer::unserialize($v, $config["profiler"]["serializer"]);
              break;
          }
        }

        $this->{$fields[$k][0]} = $v;
      }
    }
  }
}

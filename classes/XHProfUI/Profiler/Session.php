<?php

namespace XHProfUI\Profiler;

/**
* Class for managing each profiled session
*/
class Session
{

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
   * Config
   */
  protected $_config;

  /**
   * Save the current session
   *
   * @author Tom Arnfeld <tarnfeld@me.com>
   * @param array $config
   * @return Session
   */
  public static function save($config)
  {
    $session = new static($config);
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
    $serializer = $config["profiler"]["serializer"];
    $session->profileData = gzcompress(\XHProfUI\Serializer::serialize($data, $serializer));
    $session->cookieData = gzcompress(\XHProfUI\Serializer::serialize($_COOKIE, $serializer));
    $session->getData = gzcompress(\XHProfUI\Serializer::serialize($_GET, $serializer));
    $session->postData = gzcompress(\XHProfUI\Serializer::serialize($_POST, $serializer));

    if (!$session->_save()) {
      return null;
    }

    return $session;
  }

  /**
   * Return an array of sessions based on the given query
   *
   * @author Tom Arnfeld <tom@duedil.com>
   * @param [type] $query [description]
   * @return [type] [description]
   */
  public static function getSessions($query)
  {

  }

  /**
   * Create a new query
   *
   * @author Tom Arnfeld <tom@duedil.com>
   * @param array $config
   */
  protected function __construct($config)
  {
    $this->_config = $config;

    $this->_db = new \XHProfUI\Db\MySQL($config);
    $this->_db->connect();
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
    $query = "INSERT INTO `sessions`
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
                '" . $this->_db->escape($this->profileData) . "',
                '" . $this->_db->escape($this->cookieData) . "',
                '" . $this->_db->escape($this->getData) . "',
                '" . $this->_db->escape($this->postData) . "'
              );";

    $this->_db->query($query);
    if ($this->_db->affectedRows()) {
      return true;
    }

    return false;
  }
}

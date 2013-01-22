<?php

namespace XHProfUI;

/**
 * Helper class for loading config files
 *
 * @author Tom Arnfeld <tarnfeld@me.com>
 */
class Config
{
  /**
   * Cached config array
   *
   * @var array
   */
  protected static $_cache = array();

  public static function cache()
  {
    return static::$_cache;
  }

  public static function load($file)
  {
    if (!file_exists($file)) {
      throw new \Exception("Config file '" . $file . "' does not exist");
    }

    return static::$_cache = (include_once $file);
  }
}

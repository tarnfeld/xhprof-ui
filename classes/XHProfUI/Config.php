<?php

namespace XHProfUI;

/**
 * Helper class for loading config files
 *
 * @author Tom Arnfeld <tarnfeld@me.com>
 */
class Config
{
  public static function load($file)
  {
    if (!file_exists($file)) {
      throw new \Exception("Config file '" . $file . "' does not exist");
    }

    return (include_once $file);
  }
}

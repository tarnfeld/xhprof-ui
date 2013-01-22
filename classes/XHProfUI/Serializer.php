<?php

namespace XHProfUI;

/**
* Class for handling the serialization of profiling data
*
* @author Tom Arnfeld <tarnfeld@me.com>
*/
class Serializer
{
  /**
   * Serialization types
   */
  const JSON  = 'json';
  const PHP   = 'php';

  /**
   * Serialize the an array of data with the given type
   *
   * @author Tom Arnfeld <tarnfeld@me.com>
   * @param array $data
   * @param string $type
   * @return string
   */
  public static function serialize($data, $type)
  {
    if ($type == static::JSON) {
      return json_encode($data);
    }
    else if ($type == static::PHP) {
      return serialize($data);
    }
  }

  /**
   * Unserialize a string with the given type
   *
   * @author Tom Arnfeld <tarnfeld@me.com>
   * @param string $data
   * @param string $type
   * @return array
   */
  public static function unserialize($data, $type)
  {
    if ($type == static::JSON) {
      return json_decode($data);
    }
    else if ($type == static::PHP) {
      return unserialize($data);
    }
  }
}

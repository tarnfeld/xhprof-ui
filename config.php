<?php

/**
 * XHProfUI Config
 */

return array(
  "db" => array(
    "hostname" => "127.0.0.1", // MySQL DB Host
    "username" => "root", // MySQL DB User
    "password" => "root", // MySQL DB Pass
    "database" => "xhprof_example" // MySQL DB Name
  ),
  "app" => array(
    "namespace" => "Sample", // Application name
    "url" => "http://xhprof.dev", // XHProfUI URL
    "server_id" => "air-development" // ID for this server
  ),
  "profiler" => array(
    "enabled" => true, // Enable the profiler?
    "serializer" => XHProfUI\Serializer::JSON, // Use the JSON serializer
    "allowed_ips" => array(
      "127.0.0.1"
    ),
    "key" => "_profile"
  )
);

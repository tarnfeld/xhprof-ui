<?php

/**
 * Header file to send data to XHProfUI
 *
 * @author Tom Arnfeld <tarnfeld@me.com>
 */

// Handle the CLI interface
if (PHP_SAPI == "cli") {
  $_SERVER["REMOTE_ADDR"] = null;
  $_SERVER["REQUEST_URI"] = $_SERVER["SCRIPT_NAME"];
}

// Bootstrap the autoloader
require __DIR__ . "/../vendor/autoload.php";

// Load a few variables
global $_xhprof_ui_config;
$_xhprof_ui_config = \XHProfUI\Config::load(__DIR__ . "/../config.php");
$profile_key = $_xhprof_ui_config["profiler"]["key"];
$do_profile = null;

if (!extension_loaded('xhprof') || !$_xhprof_ui_config["profiler"]["enabled"]) {
  return;
}

// Validate this request
if (PHP_SAPI == "cli" || (isset($_xhprof_ui_config["profiler"]["allowed_ips"])) && in_array($_SERVER["REMOTE_ADDR"], $_xhprof_ui_config["profiler"]["allowed_ips"])) {

  // Handle the cookie ?_profile GET request
  if (isset($_GET[$profile_key])) {

    // Set the cookie
    if ($_GET[$profile_key] == "1") {
      setcookie("xhprof_ui_profile_enabled", "1");
      $do_profile = true;
    }
    else {
      setcookie("xhprof_ui_profile_enabled", "0");
      $do_profile = false;
    }
  }

  // Work out whether we should profile this request or not...
  $do_profile = ($do_profile !== false && ($do_profile || (isset($_COOKIE["xhprof_ui_profile_enabled"]) && $_COOKIE["xhprof_ui_profile_enabled"] == "1") || PHP_SAPI == "cli" || (isset($_SERVER["XHPROF_PROFILE"]) && $_SERVER["XHPROF_PROFILE"])));
}

// Profile the request
if (!$do_profile) {
  return;
}

// Enable XHProf
xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

// Shutdown handler
function _xhprof_ui_shutdown() {
  global $_xhprof_ui_config;

  \XHProfUI\Profiler\Session::save($_xhprof_ui_config);
}

register_shutdown_function('_xhprof_ui_shutdown');

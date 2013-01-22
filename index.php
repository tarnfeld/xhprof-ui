<?php

/**
 * XHProfUI Application
 *
 * @author Tom Arnfeld <tarnfeld@me.com>
 */

require __DIR__ . "/vendor/autoload.php";

// Bootstrap the application
$app = new \Bullet\App(array(

  // Template Config
  "template.cfg" => array(
    "path" => __DIR__ . "/templates/",
    "path_layouts" => __DIR__ . "/templates/layout/",
    "auto_layout" => "default"
  )
));

// Setup a few global things
$config = XHProfUI\Config::load(__DIR__ . "/config.php");
$db = new XHProfUI\Db\MySQL(); $db->connect();

// Session list
$app->path("/", function($request) use ($app, $db) {

  // Check for any search params
  $query = array();

  if (isset($_GET["search"]["id"]) && ($q = trim($_GET["search"]["id"]))) {
    $query["id"] = $q;
  }

  if (isset($_GET["search"]["server_name"]) && ($q = trim($_GET["search"]["server_name"]))) {
    $query["server_name"] = $q;
  }

  if (isset($_GET["search"]["server_id"]) && ($q = trim($_GET["search"]["server_id"]))) {
    $query["server_id"] = $q;
  }

  if (isset($_GET["search"]["is_ajax"]) && ($q = trim($_GET["search"]["is_ajax"]))) {
    $query["is_ajax"] = (bool)(int) $q;
  }

  // Work out the limit
  $limit = 25;
  if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
    $limit = (int) $_GET["limit"];
  }

  // Get the sessions
  $sessions = \XHProfUI\Profiler\Session::getSessions($db, $query, $limit);

  // Render the template
  return $app->template("sessions", array(
    "sessions" => $sessions
  ));
});

// Fire away!
echo $app->run(new Bullet\Request());

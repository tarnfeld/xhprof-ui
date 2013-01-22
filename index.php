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
$db = new XHProfUI\Db\MySQL($config["db"]);

// Session list
$app->path("/", function($request) use ($app) {
  var_dump(class_exists("\XHProfUI\DB\MySQL"));die();
  return $app->template("sessions", array());
});

// Fire away!
echo $app->run(new Bullet\Request());

<? $config = \XHProfUI\Config::cache(); global $app; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>XHProf UI</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/assets/css/bootstrap-responsive.css" />
    <script type="text/javascript" src="/assets/bootstrap/assets/js/jquery.js"></script>
    <script type="text/javascript" src="/assets/bootstrap/assets/js/bootstrap.js"></script>

    <link rel="stylesheet" type="text/css" href="/assets/css/base.css" />
    <script type="text/javascript" src="/assets/js/global.js"></script>
  </head>
  <body>
    <form action="" method="GET">
      <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <a class="brand" href="/"><?= $config["app"]["namespace"] ?></a>

            <ul class="nav">
              <li<? if ($active_nav == "sessions") { ?> class="active"<? } ?>><a href="/">Sessions</a></li>
              <li<? if ($active_nav == "urls") { ?> class="active"<? } ?>><a href="/urls">URLs</a></li>
            </ul>

            <?= $app->run("GET", "_nav_filters")->content(); ?>
          </div>
        </div>
      </div>

      <div class="container content">
        <?= $yield ?>
      </div>
    </form>
  </body>
</html>

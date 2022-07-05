<?php
  //namespace app;
  // index.php
  // On charge les modeles et les controleurs
  include_once '../sys/coreFunctions.php';
  include_once '../sys/DatenController.php';
  include_once '../sys/DatenTemplateEngine.php';
  include_once '../sys/DatenRoute.php';
  include_once '../sys/DatenDB.php';


  include_once '../fonctions.php';
  include_once '../sys/routeManager.php';
  include_once '../app/globales.php';
  include_once '../app/controlleurs.php';
  include_once '../sys/logIn.php';
  include_once '../app/routes.php';
  include_once '../app/views.php';
  //die;

  //datenshi\Database::createConnection();

  // gestion des routes
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  switch ($uri) {
    case '/nop':
        load_mainpage();
      break;

    case '/account':
      load_dashboard();
      break;
    default:
      // code...
      $noFound = true;
      if (datenshi\Page::check(substr($uri, 1))) {
        datenshi\Page::execute(substr($uri, 1));
        $noFound = false;
      }
      if ($noFound == true) load_err(404);
      break;
  }
?>

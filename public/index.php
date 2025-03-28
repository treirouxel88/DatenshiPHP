<?php
  // index.php
  // On charge les modeles et les controleurs
  require_once '../app/globales.php';
  require_once '../sys/init.php';
  require_once '../sys/coreFunctions.php';
  require_once '../sys/DatenLogin.php';
  require_once '../sys/DatenController.php';
  require_once '../sys/DatenTemplateEngine.php';
  require_once '../sys/DatenRoute.php';
  require_once '../sys/DatenDB.php';
  require_once '../sys/routeManager.php';

  // if you wish to include libs, uncomment this line
  //require_once '../libs/index.php';

  require_once '../fonctions.php';
  require_once '../app/controlleurs.php';
  require_once '../app/routes.php';
  require_once '../app/views.php';

  require_once '../sys/entrypoint.php';
?>

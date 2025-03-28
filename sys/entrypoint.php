<?php
  namespace datenshi;

  Database::createConnection();

  // gestion des routes

  //useful for regionlocking purposes
  /*if (!in_array(get_cc(), array("FR"))) {
    load_err(403);
    die;
  }*/

  $uri = getFirstRoute();
  Page::setUri($uri);

  if (isset($login_enable) && $login_enable && $uri == $login_auth_path) {
    Auth::login();
  } else {
  if (Page::check($uri)) {
    Page::execute($uri);
  } else {
	load_err(404);
  }
  }
?>

<?php
namespace datenshi;

/*
  Declarer des routes/pages comme ceci:
      datenshi\Page::add(routeName, display, flags, controller);

      routeName : le nom de la route, ce que tu mets dans l'url
      display : le nom affiché dans les menu, sur la page
      flags : gestion des permission/comportement
          ROUTE_ONLY: route
          LOGGED_IN: page affiché seulement en étant connecté
          LOGGED_OUT: page affiché seulement en étant déconnecté
          HIDDEN: page masquée du menu
      controller : le controller qui est appelé par la page (si l'utilisateur à les droits)
*/
 ?>

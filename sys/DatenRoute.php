<?php

namespace datenshi;

/**
 * Controler component to be extended
 */
class Page
{
  private static int $routesCounter;
  private static array $routes = [];

  static function add(string $routeUri, $routeDisplay, $routeFlags, $routeProgram)
  {
    // contructeur
    self::$routes = array($routeUri => [$routeDisplay, $routeFlags, $routeProgram]) + self::$routes;
    //var_dump(self::$routes);
  }

  static function display(string $routeUri)
  {
    // contructeur
    echo self::$routes[$routeUri][0];
  }

  static function get(string $routeUri)
  {
    // contructeur
    return self::$routes[$routeUri];
  }

  static function getAll()
  {
    return self::$routes;
  }

  static function check(string $routeUri)
  {
    // contructeur
    if (isset(self::$routes[$routeUri])) {
      return true;
    } else {
      return false;
    }
  }

  static function execute($routeUri) {
    //if (checkPerms())
    call_user_func(self::$routes[$routeUri][2]);
  }
}
 ?>

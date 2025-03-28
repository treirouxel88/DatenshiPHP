<?php

namespace datenshi;

/**
 * Controler component to be extended
 */
class Page
{
  private static array $uri;
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
    $froute = explode("/",$routeUri)[0];
    if (isset(self::$routes[$froute])) {
      if (!empty(str_contains(self::$routes[$froute][1], "API"))) {
        return true;
      } else if (isset(self::$routes[$routeUri])) {
        return true;
      } else return false;
    } else {
      return false;
    }
  }

  static function setUri(string $routeUri)
  {
    // setter
    self::$uri = explode("/",$routeUri);
  }

  static function getUri()
  {
    // getter
    return self::$uri;
  }

  static function execute($routeUri) {
    $froute = explode("/",$routeUri)[0];
    if (str_contains(self::$routes[$froute][1], "LOGIN")) {
      Auth::login();
    }if (str_contains(self::$routes[$froute][1], "REDIR")) {
      header('Location: '.self::$routes[$froute][2]);
    } else if (str_contains(self::$routes[$froute][1], "REDIR_PERMA")) {
      header("Status: 301 Moved Permanently", false, 301);
      header('Location: '.self::$routes[$froute][2]);
    } else {
      call_user_func(self::$routes[$froute][2]);
    }
    //call_user_func(self::$routes[$froute][2]);
  }
}
 ?>

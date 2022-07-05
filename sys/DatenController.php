<?php

namespace datenshi;

/**
 * Controler component to be extended
 */
class Controller
{
  private static int $controllerCounter;
  private string $controllerName;

  function __construct(string $ctrName, $ctrFunction)
  {
    // contructeur
    $this->controllerName = $ctrName;
  }

  function execute() {
    call_user_func($this->controllerFunction);
  }
}
 ?>

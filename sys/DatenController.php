<?php

namespace datenshi;

/**
 * Controler component to be extended
 */
abstract class Controller
{
  abstract protected function execute();

  public function call() {
    $this->execute();
  }
}
 ?>

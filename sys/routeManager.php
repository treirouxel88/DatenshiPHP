<?php

  //return bool en fonction des permissions, par défaut : true
  function checkPermsa($perms) {
    $permissionlist = array(
      "LOGGED_OUT" => $_SESSION['logged'] ? 0 : 1,
      "LOGGED_IN" => $_SESSION['logged'] ? 1 : 0,
      "ONLY_DEV" => checkUser("discordid"),
      "HIDDEN" => 0
    );
    $perms = explode(" ", $perms);
    foreach ($perms as $v) {
      if (array_key_exists($v, $permissionlist) && $permissionlist[$v] == 0) {
        return false;
      }
    }
    return true;
  }

  function menuRendera($page, $list) {
    foreach ($list as $k => $v) {
      if (checkPerms($v["flags"])) {
        $active = (isset($page) && $page == $v["internal"]) ? ' class="active"' : '';
        $return .= '<li'. $active .'><a href="'.$v["link"].'">'.$v["display"].'</a></li>';
      }
    }
    return $return;
  }

  function loadController($contrName) {
    $contrName();
  }
?>

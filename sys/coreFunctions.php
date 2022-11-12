<?php

  function load_err($errcode){
    $page = "err";
    require '../app/globales.php';
    $errmsg = $sys_errList[$errcode];
    require '../templates/page_err.php';
    die;
  }

  function menuRenderFromPages($page=NULL) {
    $list = datenshi\Page::getAll();
    $list = array_reverse($list);
    foreach ($list as $k => $v) {
      if (checkPerms($v[1])) {
        $active = (isset($page) && $page == $v[0]) ? ' active' : '';
        $return .= '<li class="nav-item"><a class="nav-link'. $active .'" href="/'.$k.'">'.$v[0].'</a></li>';
      }
    }
    return $return;
  }

  function generateTable($tableTemplate, $tableContent) {
    $thead = "";
    $tbody = "";
    foreach ($tableTemplate as $v) {
      $thead = $thead."<td><b>$v</b></td>";
    }
    foreach ($tableContent as $v) {
      $tbody = $tbody."<tr>";
      foreach ($v as $val) {
        $tbody = $tbody."<td>$val</td>";
      }
      $tbody = $tbody."</tr>";
    }
    $table = "
    <table>
    <thead>
      <tr>
          $thead
      </tr>
    </thead>

    <tbody>
      $tbody
    </tbody>
    </table>
    ";
    return $table;
  }

  //return bool en fonction des permissions, par défaut : true
  function checkPerms($perms) {
    //calculs mathématique
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

 ?>

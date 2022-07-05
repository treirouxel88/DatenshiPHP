<?php
  function checkUsernameCorrectInDb($uid, $uname) {
    require "bdd.php";
    mysqli_select_db($bddc,"$dbname") or die("database エラー );");
    $userbase = "CREATE TABLE IF NOT EXISTS `$dbname`.`users` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `usernamae` VARCHAR(64) NOT NULL , `mc_uuid` TEXT NOT NULL , `userbio` TEXT NOT NULL , `usersettei` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    mysqli_query($bddc,$userbase);
    if ($last_coms = $bddc->query("SELECT * FROM `users` WHERE id = '$uid' LIMIT 1")) {
      //printf("Select a retourné %d lignes.\n", $last_coms->num_rows);

      if ($last_coms->num_rows == 0) {
        $def = "default";
        if ($stmt = $bddc->prepare("INSERT INTO users(id, usernamae, userbio, usersettei) VALUES(?, ?, ?, ?)")) {

          /* Lecture des marqueurs */
          $stmt->bind_param("isss", $uid, $uname, $def, $def);

          /* Exécution de la requête */
          $stmt->execute();

          /* Fermeture du traitement */
          $stmt->close();
        }
      } else {
        while ($row = $last_coms->fetch_assoc()) {
          if ($user->username != $row["usernamae"]) {
            if ($stmt = $bddc->prepare("UPDATE users SET usernamae = ? WHERE ID = $uid")) {

              /* Lecture des marqueurs */
              $stmt->bind_param("s", $uname);

              /* Exécution de la requête */
              $stmt->execute();

              /* Fermeture du traitement */
              $stmt->close();
            }
          }
        }
      }
  }
}

function checkUserExistsInDb($uid, $uname) {
  require "bdd.php";
  mysqli_select_db($bddc,"$dbname") or die("database エラー );");
  $userbase = "CREATE TABLE IF NOT EXISTS `$dbname`.`users` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `usernamae` VARCHAR(64) NOT NULL , `mc_uuid` TEXT NOT NULL , `userbio` TEXT NOT NULL , `usersettei` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
  mysqli_query($bddc,$userbase);
  if ($last_coms = $bddc->query("SELECT * FROM `users` WHERE id = '$uid' LIMIT 1")) {
    //printf("Select a retourné %d lignes.\n", $last_coms->num_rows);

    if ($last_coms->num_rows == 0) {
      return false;
    } else {
      while ($row = $last_coms->fetch_assoc()) {
        if ($user->username != $row["usernamae"]) {
          if ($stmt = $bddc->prepare("UPDATE users SET usernamae = ? WHERE ID = $uid")) {

            /* Lecture des marqueurs */
            $stmt->bind_param("s", $uname);

            /* Exécution de la requête */
            $stmt->execute();

            /* Fermeture du traitement */
            $stmt->close();
          }
        }
      }
    }
}
}

  function createUserFromDiscord($uid, $uname) {
    // Assuming the user will always login with Discord until
    // He sets his password manually, we will reproduce the normal
    // steps as the user register


    $password = getRamdonPass();
    // Double hashage
    $password = password_hash($password, PASSWORD_ARGON2I);
    if ($password == false) return;
    // Second hash
    $password = password_hash($password, PASSWORD_ARGON2ID);
    if ($password == false) return;

  }

  function createUser($uid, $password) {
    // The password given have been pre-hashed before the request
    // we will secure the accountby hashing the password one more time
    // schéma de communication :
    // formulaire -> hash -> requête de création d'utilisateur -> second hash -> BDD

    // Password hash
    $password = password_hash($password, PASSWORD_ARGON2ID);
    if ($password == false) return;

  }

  function get_all_enq() {

    return "all";
  }

  //return bool en fonction des permissions, par défaut : true
  function checkUser($id) {
    return false;
  }

  function menuRender($page, $list) {
    foreach ($list as $k => $v) {
      if (checkPerms($v["flags"])) {
        $active = (isset($page) && $page == $v["internal"]) ? ' class="active"' : '';
        $return .= '<li'. $active .'><a href="'.$v["link"].'">'.$v["display"].'</a></li>';
      }
    }
    return $return;
  }

  function get_all_series() {
    $series = array();




    return $series;
  }
?>

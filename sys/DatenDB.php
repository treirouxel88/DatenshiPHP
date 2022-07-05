<?php

namespace datenshi;
 class Database
 {
   static $dbObj;

   static function createConnection()
   {
     // code...
     require "../app/bdd.php";
     $dsn = "mysql:host=".$host.";dbname=".$base;
     try {
       $connexion = new \PDO($dsn,$user,$pass);
       $connexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
       self::$dbObj = $connexion;
       $reponse = true;
     } catch(\PDOException $e) {
       #printf("Echec connexion : %s\n",
       #$e->getMessage());
       //exit();
       $reponse = false;
       load_err(400);
     }
     return $reponse;
   }

   static function request($sql) {
    try {
      $sth = self::$dbObj->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
      //var_dump($result);
      return $result;
    } catch(\PDOException $e) {
      #printf("Echec connexion : %s\n",
      #$e->getMessage());
      //exit();
      $reponse = false;
      load_err(400);
    }
   }

   static function getDbObj() {
     return self::$dbObj;
   }
 }
  ?>

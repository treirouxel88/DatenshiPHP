<?php

namespace datenshi;
 class Database
 {
   static $dbObj;

   static function createConnection()
   {
     // code...
     require "../app/bdd.php";
     //TODO if ($host == "disabled") return true;
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
       load_err(500);
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
      load_err(500);
    }
   }
   static function getLines($sql, $params=array()) {
    try {
      $stmt = self::$dbObj->prepare($sql);
      if ($stmt === false) {
          throw new Exception('Failed to prepare statement: ' . self::$dbObj->error);
      }
      /*if (is_array($params) && !empty($params)) {
        foreach ($params as $k => $v) {
          $stmt->bind_param(":".$k, $v);
        }
      }*/
      $stmt->execute($params);
      $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch(\PDOException $e) {
      #printf("Echec connexion : %s\n",
      #$e->getMessage());
      //exit();
      $reponse = false;
      load_err(500);
    }
   }
   static function getLine($sql, $params=array()) {
    try {
      $stmt = self::$dbObj->prepare($sql);
      if ($stmt === false) {
          throw new Exception('Failed to prepare statement: ' . self::$dbObj->error);
      }
      /*if (is_array($params) && !empty($params)) {
        foreach ($params as $k => $v) {
          $stmt->bind_param(":".$k, $v);
        }
      }*/
      $stmt->execute($params);
      $result = $stmt->fetch(\PDO::FETCH_ASSOC);
      return $result;
    } catch(\PDOException $e) {
      #printf("Echec connexion : %s\n",
      #$e->getMessage());
      //exit();
      $reponse = false;
      load_err(500);
    }
   }

   
   static function execute($sql, $params=array()) {
    try {
      $stmt = self::$dbObj->prepare($sql);
      if ($stmt === false) {
          throw new Exception('Failed to prepare statement: ' . self::$dbObj->error);
      }
      /*if (is_array($params) && !empty($params)) {
        foreach ($params as $k => $v) {
          $stmt->bind_param(":".$k, $v);
        }
      }*/
      $didExecute = $stmt->execute($params);
      return $didExecute;
    } catch(\PDOException $e) {
      #printf("Echec connexion : %s\n",
      #$e->getMessage());
      //exit();
      $reponse = false;
      load_err(500);
    }
   }

   static function getDbObj() {
     return self::$dbObj;
   }
 }
  ?>
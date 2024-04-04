<?php

namespace core;

use PDO;
use PDOException;

class Connection {
  public $conn;

  public function __construct($db) {
    $this->connect($db);
  }

  private function connect($db) {
    $db_name = $db['db_name'];
    $host = $db['host'];
    $username = $db['username'];
    $password = $db['password'];

    try {
      $this->conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
      echo "Connection failed." . $e->getMessage();
    }
  }

  public function insert($sql, $params) {
    try {

      return query($this, $sql, $params);

    } catch(PDOException $e) {
      if($e->getCode() == '23000') {
        return ["errors" => ['Username or email are already used']];
      }
      return ["errors" => ['Something vent wrong. Please, try again.']];
      // return $e->getMessage();
    }
  }

  public static function getData($sql, $params = []) {
    try {
      $conn = new Connection(CONN);
      $query = $conn->conn->prepare($sql);

      foreach($params as $key => $value) {
        $query->bindParam(":{$key}", $params[$key]);
      }
  
      $query->execute();

      return $query->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
      return ["errors" => ['Something vent wrong. Please, try again.']];
      // return $e->getMessage();
    }
  }

  public static function updateStatus($sql, $params) {
    // UPDATE users SET activated = 1 WHERE {params};
    try {
      $conn = new Connection(CONN);

      return query($conn, $sql, $params);


    } catch(PDOException $e) {
      return ["errors" => ['Something vent wrong. Please, try again.']];
      // return $e->getMessage();
    }
  }
}
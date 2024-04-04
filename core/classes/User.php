<?php

use core\Connection;

class User {
  private $conn;
  public $data = [];


  public function  __construct() {
    $this->conn = new Connection(CONN);
  }

  public function register($data) {
    $this->data = $data;

    $this->data['verified'] = generateRandomString();

    if(isAdult($this->data['date_of_birth'])) {
      $this->data['adult'] = 1;
    } else $this->data['adult'] = 0;

    $this->data['password'] = $this->data['register_password'];
    unset($this->data['register_password']);
    unset($this->data['confirm_password']);

    $string = "";
    foreach($this->data as $key => $value) {
      if($key === 'password') {
        $string .= "{$key} = :{$key}";
      }else {
        $string .= "{$key} = :{$key}, ";
      }
    }
    $sql = "INSERT INTO users SET {$string};";
  
    $res = $this->conn->insert($sql, $this->data);

    if($res == 1) {
      // $message = "Dobrodošli<br>Vaše korisničko ime je: {$this->data['username']}<br>A vaša lozinka je: {$this->data['password']}<br>";
      // $message .= "Kliknite <a href=\"verify_user_account?email={$this->data['email']}&verification_number={urlencode($this->data['verified'])}\" target=\"_blank\">ovde</a> da verifikujete vas mail. ";
      // In case any of our lines are larger than 70 characters, we should use wordwrap()
      // $message = wordwrap($message, 70, "\r\n");
  
      // Send
      // mail("{$this->data['email']}", 'Potvrda registracije', $message);
  

      return ["email" => $this->data['email'], "number" => urlencode($this->data['verified'])];
    } else return $res;
  }

  public function login($data) {
    $data['password'] = $data['login_password'];
    unset($data['login_password']);

    $remember = false;
    if(array_key_exists('remember', $data)) {
      $remember = true;
      unset($data['remember']);
    }

    if(strpos($data['username_email'], '@') !== false && strpos($data['username_email'], '.') !== false) {
      $data['email'] = $data['username_email'];
      unset($data['username_email']);

      $sql = "SELECT * FROM users WHERE email = :email AND password = :password";

    } else {
      $data['username'] = $data['username_email'];
      unset($data['username_email']);

      $sql = "SELECT * FROM users WHERE username = :username AND password = :password";
    } 

    $user = Connection::getData($sql, $data)[0];

    if($user) {
      if($user['activated'] != 1) {
        return ["errors" => ['Account is not verified.']];
        die();
      } 

      // if login is successfull
      $_SESSION['id'] = $user['verified'];
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['email'] = $user['email'];

      if($remember) {
        setcookie("id", $user['verified'], time() + 3600 * 24, '/');
        setcookie("user_id", $user['id'], time() + 3600 * 24, '/');
        setcookie("username", $user['username'], time() + 3600 * 24, '/');
        setcookie("email", $user['email'], time() + 3600 * 24, '/');
      }

      return ["success" => true];

    } else return ["errors" => ['Invalid username/email or password.']];
    
  }
}
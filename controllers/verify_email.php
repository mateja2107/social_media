<?php

if(isset($_GET['email']) && isset($_GET['verification_number'])) {
  $data = [
    "email" => $_GET['email'],
    "verified" => $_GET['verification_number']
  ];

  $sql = "SELECT * FROM users WHERE email = :email AND verified = :verified";

  if(count(core\Connection::getData($sql, $data)) == 1) {
    $sql = "UPDATE users SET activated = 1 WHERE email = :email AND verified = :verified";
    
    $message = "Doslo je do greske";

    if(core\Connection::updateStatus($sql, $data)) {
      $message = "Uspesno ste verifikovali mail adresu. Ulogujte se <a href=\"/login\">ovde</a>.";
    }

    header("Location: /register?verified={$message}");
  };
} else {
  header("Location: /");
}
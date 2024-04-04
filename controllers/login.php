<?php

$heading = "Login";

if($_SERVER['REQUEST_METHOD'] == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);

  $errors = [];

  foreach ($data as $key => $value) {
    $value = trim($value);

    if ($value === "" && !in_array("Field cannot be empty.", $errors)) {
      $errors[] = "Field cannot be empty.";
    }

    $specialCharacters = '/[^\w\s]/';
    $emailRegex = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
    $HTMLTagRegex = '/<\/?[\w\s]*>|<.+[\W]>/';

    // Filter HTML tags
    if (preg_match($HTMLTagRegex, $value)) {
      $errors[] = "Cannot submit html tags.";
    }

    // Validate username or email
    if ($key === "username_email") {
      if (strlen($value) < 3) {
        $errors[] = "username or email must be 3 or more characters long.";
      }
      if (strpos($value, " ") !== false) {
        $errors[] = "username or email cannot contain spaces.";
      }

      if (strpos($value, "@") !== false) {
        if (!preg_match($emailRegex, $value)) {
          $errors[] = "Enter a valid email.";
        }
      } else {
        if (preg_match($specialCharacters, $value)) {
          $errors[] = "username cannot contain any special characters except _";
        }
      }
    }

    if ($key === "login_password") {
      // Validate password
      if (strpos($value, " ") !== false) {
        $errors[] = "password cannot contain spaces.";
      }
      if (strlen($value) < 5) {
        $errors[] = "password must be 5 or more characters long.";
      }
    }
  }


  // Output errors
  if(count($errors) > 0) {
    $errors = ["errors" => $errors];
    echo json_encode($errors);
    die();
  }

  $user = new User();

  echo json_encode($user->login($data));

  die();
}

view('login.view.php', [
  "heading" => $heading
]);
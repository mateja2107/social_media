<?php

$heading = "Register";

if($_SERVER['REQUEST_METHOD'] == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);

  $errors = [];

  foreach ($data as $key => $value) {
    // trim the value " asd " = "asd";
    $value = trim($value);

    // check if all inputs are filled except bio
    if ($key !== "bio") {
      if ($value === "" && !in_array("Field cannot be empty.", $errors)) {
        $errors[] = "Field cannot be empty.";
      }
    }

    $numbers = '/\d/';
    $letters = '/[a-zA-Z]/';
    $specialCharacters = '/[^\w\s]/';
    $emailRegex = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
    $HTMLTagRegex = '/<\/?[\w\s]*>|<.+[\W]>/';

    // filter HTML tags
    if (preg_match($HTMLTagRegex, $value)) {
      $errors[] = "Cannot submit html tags.";
    }

    // validate first name and last name
    if ($key === "first_name" || $key === "last_name") {
      $inputName = str_replace("_", " ", $key);
      if (strpos($value, " ") !== false) {
        $errors[] = "{$inputName} cannot contain spaces.";
      }
      if (strlen($value) < 3) {
        $errors[] = "{$inputName} must be 3 or more characters long.";
      }
      if (preg_match($numbers, $value)) {
        $errors[] = "{$inputName} cannot contain numbers.";
      }
      if (preg_match($specialCharacters, $value) || strpos($value, "_") !== false) {
        $errors[] = "{$inputName} cannot contain special characters.";
      }
    }

    // validate date
    if ($key === "date_of_birth") {
      if (strlen($value) != 10 || preg_match($letters, $value)) {
        $errors[] = "Enter a valid date.";
      }
    }

    // validate status
    if ($key === "status") {
      if ($value !== "public" && $value !== "private") {
        $errors[] = "Status must be public or private.";
      }
    }

    // validate username
    if ($key === "username") {
      if (strpos($value, " ") !== false) {
        $errors[] = "{$key} cannot contain spaces.";
      }
      if (strlen($value) < 3) {
        $errors[] = "{$key} must be 3 or more characters long.";
      }

      // all special characters except _
      $validateUsernameRegex = '/[^\w\s_]/';
      if (preg_match($validateUsernameRegex, $value)) {
        $errors[] = "username cannot contain any special character except _";
      }
    }

    // validate email
    if ($key === "email") {
      if (!preg_match($emailRegex, $value)) {
        $errors[] = "Enter a valid email.";
      }
    }

    // validate password
    if ($key === "register_password") {
      if (strpos($value, " ") !== false) {
        $errors[] = "password cannot contain spaces.";
      }
      if (strlen($value) < 5) {
        $errors[] = "password must be 5 or more characters long.";
      }
    }
  }

  // confirm password
  if ($data["confirm_password"] !== $data["register_password"]) {
    $errors[] = "passwords must match!";
  }

  // Output errors
  if(count($errors) > 0) {
    $errors = ["errors" => $errors];
    echo json_encode($errors);
    die();
  }

  $user = new User();
  echo json_encode($user->register($data));

  die();
}

view('register.view.php', [
  "heading" => $heading
]);
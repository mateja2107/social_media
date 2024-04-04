<?php

function dd($val)
{
  echo '<pre>';
  var_dump($val);
  echo '</pre>';
}

function base_path($path)
{
    return BASE_PATH . $path;
}

function view($path, $attributes = [])
{
    extract($attributes);

    require base_path('views/' . $path);
}

function abort($code = 404)
{
    http_response_code($code);

    require base_path("views/{$code}.php");
}

function isAdult($dateOfBirth) 
{
    // Create a DateTime object for the date of birth
    $dob = new DateTime($dateOfBirth);
    
    // Get the current date
    $currentDate = new DateTime();
    
    // Calculate the difference between the current date and the date of birth
    $age = $dob->diff($currentDate)->y;
    
    // Check if the age is greater than or equal to 18
    return $age >= 18;
}

function generateRandomString($length = 100) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~';
    $randomString = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $max)];
    }

    $data = ["verified" => $randomString];

    $sql = "SELECT * FROM users WHERE verified = :verified";

    $user = core\Connection::getData($sql, $data);

    if($user) {
        generateRandomString();
    } else return $randomString;
}

function query($conn, $sql, $params) 
{
    $query = $conn->conn->prepare($sql);

    // Bind parameters
    foreach($params as $key => $value) {
        // $query->bindParam(":{$key}", $value);
        $query->bindParam(":{$key}", $params[$key]);
    }

    // Execute query
    return $query->execute();
}

function login()
{
  if (isset($_SESSION['id'])) {
    return true;

  } else if (isset($_COOKIE['id'])) {
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['email'] = $_COOKIE['email'];

    return true;
    
  } else return false;
}
<?php

$url = parse_url($_SERVER['REQUEST_URI']);

$uri = $url['path'];

if(array_key_exists('query', $url)) $query_string = $url['query'];

$routes = [
    "/" => "../controllers/index.php",
    "/login" => "../controllers/login.php",
    "/register" => "../controllers/register.php",
    "/verify_user_account" => "../controllers/verify_email.php"
];

if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else abort(); 


<?php

$heading = "Welcome";

if(isset($_SESSION['id'])) $heading = "Home";

view('home.view.php', [
    "heading" => $heading
]);
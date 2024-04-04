<?php
session_start();

const CONN = [
  "db_name" => "social_media_db",
  "host" => "localhost",
  "username" => "root",
  "password" => ""
];

require __DIR__ . '/functions.php';
login();

require __DIR__ . '/../core/classes/Connection.php';
require __DIR__ . '/../core/classes/User.php';

require __DIR__ . '/../core/router.php';


// $db = new core\Connection($conn);
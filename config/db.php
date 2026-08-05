<?php

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "college_db";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_errno) {
    die("Error {$conn->connect_errno}: {$conn->connect_error}");
}

// Connection established
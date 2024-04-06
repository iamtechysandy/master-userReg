<?php

// Database configuration
$dbHost = 'localhost'; // Change this if your database is hosted elsewhere
$dbUsername = 'root';
$dbPassword = ''; // If your password is blank
$dbName = 'lms';

// Establish database connection
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

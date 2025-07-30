<?php
// db_connect.php

// Database connection parameters - replace with your actual values
$host = 'localhost';
$port = '5432';
$dbname = 'bar_db';
$user = 'postgres';
$password = '2002';

// Create connection string
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Connect to PostgreSQL
$conn = pg_connect($conn_string);

if (!$conn) {
    // Connection failed
    die("Error: Unable to connect to PostgreSQL database.");
}

// Now $conn can be used for queries

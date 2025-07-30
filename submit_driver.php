<?php
session_start();
include 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get form values
$pickup = pg_escape_string($conn, $_POST['pickup'] ?? '');
$dropoff = pg_escape_string($conn, $_POST['dropoff'] ?? '');
$time = pg_escape_string($conn, $_POST['time'] ?? '');
$date = pg_escape_string($conn, $_POST['date'] ?? '');
$user_id = $_SESSION['user_id'] ?? null; // Ensure user_id is stored in session

// Validate required fields
if (!$pickup || !$dropoff || !$time || !$date || !$user_id) {
    echo "All fields are required.";
    exit;
}

// Insert into driver_bookings table (you must create this table in your DB)
$query = "INSERT INTO driver_bookings (user_id, pickup_location, dropoff_location, pickup_time, pickup_date)
          VALUES ($1, $2, $3, $4, $5)";

$result = pg_query_params($conn, $query, [$user_id, $pickup, $dropoff, $time, $date]);

if ($result) {
    header("Location: driver_success.php");
    exit;
} else {
    echo "Error saving booking.";
    exit;
}

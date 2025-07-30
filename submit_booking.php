<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    require_once 'db_connect.php';

    // Get form data
    $name = trim($_POST['name']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = (int) $_POST['guests'];

    // Get user ID from session (make sure it's set during login)
    $user_id = $_SESSION['user_id'];

    // Validate input (basic check)
    if (empty($name) || empty($date) || empty($time) || $guests < 1) {
        echo "<p style='color: red; text-align: center; margin-top: 30px;'>Please fill out all fields correctly.</p>";
        exit;
    }

    // Prepare SQL query
    $query = "INSERT INTO bookings (user_id, name, date, time, guests)
              VALUES ($1, $2, $3, $4, $5)";
    $params = [$user_id, $name, $date, $time, $guests];

    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        // Success
        header("Location: booking_success.php");
        exit;
    } else {
        // Error
        echo "<p style='color: red; text-align: center; margin-top: 30px;'>Booking failed. Please try again.</p>";
    }
} else {
    // Redirect if not POST
    header("Location: book_table.php");
    exit;
}

<?php
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

// Connect to PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

// Get total from user_cart table
$result = pg_query_params($conn, "SELECT SUM(total) AS grand_total FROM user_cart WHERE user_id = $1", [$user_id]);
$row = pg_fetch_assoc($result);
$total = $row['grand_total'] ?? 0;

// Get payment form inputs
$card = $_POST['card_number'] ?? '';
$expiry = $_POST['expiry'] ?? '';
$cvv = $_POST['cvv'] ?? '';

if (!$card || !$expiry || !$cvv || $total <= 0) {
    die("Invalid payment details or cart is empty.");
}

// Simulate payment success (90% chance)
$success = rand(1, 10) > 1;

if (!$success) {
    header("Location: payment_cancel.php");
    exit;
}

// Save payment info to session
$_SESSION['last_payment'] = [
    'amount' => $total,
    'card_last4' => substr($card, -4),
    'date' => date('Y-m-d H:i:s')
];

// Store payment in the database
// Store payment in the database
pg_query_params($conn, "
    INSERT INTO payments (user_id, amount, card_last4, payment_date)
    VALUES ($1, $2, $3, NOW())
", [$user_id, $total, substr($card, -4)]);

// ✅ Clear the cart now after successful payment
pg_query_params($conn, "DELETE FROM user_cart WHERE user_id = $1", [$user_id]);

// Redirect to payment success
header("Location: payment_success.php");
exit;

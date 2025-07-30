<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_name'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

// Fetch cart items for current user
$cart_res = pg_query_params($conn, "SELECT * FROM user_cart WHERE user_id = $1", [$user_id]);
$cart_items = pg_fetch_all($cart_res);

if (!$cart_items || count($cart_items) == 0) {
    die("Invalid payment details or cart is empty.");
}

// Validate POST data (example: card_number, expiry, cvv)
$card_number = $_POST['card_number'] ?? '';
$expiry = $_POST['expiry'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$order_type = $_POST['order_type'] ?? '';
$table_id = $_POST['table_id'] ?? null;
$address = $_POST['address'] ?? null;
$phone = $_POST['phone'] ?? null;

// Basic payment validation (mock)
if (
    strlen($card_number) !== 16 || !ctype_digit($card_number) ||
    !preg_match('/^\d{2}\/\d{2}$/', $expiry) || // simple MM/YY format
    (strlen($cvv) < 3 || strlen($cvv) > 4) || !ctype_digit($cvv)
) {
    die("Invalid payment details or cart is empty.");
}

// Calculate total price
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Validate table availability if Dine-in
if ($order_type === 'Dine-in' && $table_id) {
    $table_check = pg_query_params($conn, "SELECT is_available FROM tables WHERE id = $1", [$table_id]);
    $table_data = pg_fetch_assoc($table_check);
    if (!$table_data || $table_data['is_available'] !== 't') {
        die("Sorry, this table is already booked. Please choose a different table.");
    }
}

// Insert order_types row
$insert_order_sql = "
    INSERT INTO order_types (user_id, order_type, table_id, address, phone, created_at)
    VALUES ($1, $2, $3, $4, $5, NOW())
    RETURNING id
";
$insert_order_result = pg_query_params($conn, $insert_order_sql, [$user_id, $order_type, $table_id, $address, $phone]);

if (!$insert_order_result) {
    die("Failed to save order details. Please try again.");
}

$order_row = pg_fetch_assoc($insert_order_result);
$order_type_id = $order_row['id'];

// If Dine-in, mark table unavailable
if ($order_type === 'Dine-in' && $table_id) {
    pg_query_params($conn, "UPDATE tables SET is_available = FALSE WHERE id = $1", [$table_id]);
}

// If Delivery, insert delivery_details
if ($order_type === 'Delivery' && $address && $phone) {
    pg_query_params($conn, "
        INSERT INTO delivery_details (user_id, address, phone, order_type_id, created_at)
        VALUES ($1, $2, $3, $4, NOW())
    ", [$user_id, $address, $phone, $order_type_id]);
}

// Insert order_bites items
foreach ($cart_items as $item) {
    pg_query_params($conn, "
        INSERT INTO order_bites (user_id, product_name, price, quantity, total, added_at)
        VALUES ($1, $2, $3, $4, $5, NOW())
    ", [
        $user_id,
        $item['product_name'],
        $item['price'],
        $item['quantity'],
        $item['price'] * $item['quantity']
    ]);
}

// Clear cart after order
pg_query_params($conn, "DELETE FROM user_cart WHERE user_id = $1", [$user_id]);

// Optionally store order type in session (for showing status later)
$_SESSION['last_order_type'] = $order_type;

// Redirect to a mock payment gateway page (pass total amount)
header("Location: mock_gateway.php?amount=$total");
exit;

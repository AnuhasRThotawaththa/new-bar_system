<?php
session_start();
header('Content-Type: application/json');

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// 2. Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// 3. Sanitize input
$bite_id = $_POST['bite_id'] ?? null;
$qty = $_POST['qty'] ?? null;

if (!is_numeric($bite_id) || !is_numeric($qty) || (int)$qty < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$bite_id = (int)$bite_id;
$qty = (int)$qty;
$user_id = (int)$_SESSION['user_id'];

// 4. Connect to PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// 5. Get bite info from bites table
$bite_query = "SELECT name, price FROM bites WHERE id = $1";
$bite_result = pg_query_params($conn, $bite_query, [$bite_id]);

if (!$bite_result || pg_num_rows($bite_result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Bite not found']);
    pg_close($conn);
    exit;
}

$bite = pg_fetch_assoc($bite_result);
$product_name = $bite['name'];
$price = (int)$bite['price'];
$total = $qty * $price;

// 6. Check if item already in cart
$check_sql = "SELECT quantity FROM user_cart WHERE user_id = $1 AND product_name = $2";
$check_result = pg_query_params($conn, $check_sql, [$user_id, $product_name]);

if ($check_result && pg_num_rows($check_result) > 0) {
    // 7. Update existing cart item
    $existing = pg_fetch_assoc($check_result);
    $new_qty = $existing['quantity'] + $qty;
    $new_total = $new_qty * $price;

    $update_sql = "UPDATE user_cart SET quantity = $1, total = $2, added_at = NOW() WHERE user_id = $3 AND product_name = $4";
    $update_result = pg_query_params($conn, $update_sql, [$new_qty, $new_total, $user_id, $product_name]);

    if ($update_result) {
        echo json_encode(['success' => true, 'message' => 'Cart updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
    }
} else {
    // 8. Insert new item into cart
    $insert_sql = "INSERT INTO user_cart (user_id, product_name, price, quantity, total, added_at)
                   VALUES ($1, $2, $3, $4, $5, NOW())";
    $insert_result = pg_query_params($conn, $insert_sql, [$user_id, $product_name, $price, $qty, $total]);

    if ($insert_result) {
        echo json_encode(['success' => true, 'message' => 'Bite added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
    }
}

// 9. Close connection
pg_close($conn);
?>

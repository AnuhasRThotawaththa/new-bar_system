<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$bite_id = intval($_POST['bite_id'] ?? 0);
$qty = intval($_POST['qty'] ?? 0);

if ($bite_id <= 0 || $qty <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid bite ID or quantity']);
    exit;
}

// Connect to PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Check if item already in cart for this user
$query = "SELECT id, quantity FROM user_cart WHERE user_id = $1 AND bite_id = $2";
$result = pg_query_params($conn, $query, [$user_id, $bite_id]);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    exit;
}

if (pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $existing_qty = intval($row['quantity']);
    $new_qty = $existing_qty + $qty;

    $update = pg_query_params($conn, "UPDATE user_cart SET quantity = $1 WHERE id = $2", [$new_qty, $row['id']]);
    if ($update) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
        exit;
    }
} else {
    $insert = pg_query_params($conn, "INSERT INTO user_cart (user_id, bite_id, quantity, added_at) VALUES ($1, $2, $3, NOW())", [$user_id, $bite_id, $qty]);
    if ($insert) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
        exit;
    }
}
?>

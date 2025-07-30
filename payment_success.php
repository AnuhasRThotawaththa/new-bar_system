<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
$first_name = $_SESSION['first_name'] ?? 'User';
$order_type = $_SESSION['last_order_type'] ?? null;

$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

$payment = $_SESSION['last_payment'] ?? ['amount' => 0, 'card_last4' => 'XXXX', 'date' => date('Y-m-d H:i:s')];

$order_id = 'SIP' . rand(1000, 9999);

if ($user_id && $payment['amount'] > 0) {
    pg_query_params($conn, "
        INSERT INTO payments (user_id, order_id, amount, card_last4, payment_date)
        VALUES ($1, $2, $3, $4, $5)
    ", [
        $user_id,
        $order_id,
        $payment['amount'],
        $payment['card_last4'],
        $payment['date']
    ]);

    $cart_result = pg_query_params($conn, "SELECT * FROM user_cart WHERE user_id = $1", [$user_id]);
    $cart_items = pg_fetch_all($cart_result);

    if ($cart_items) {
        foreach ($cart_items as $item) {
            $total_item = $item['price'] * $item['quantity'];
            pg_query_params($conn, "
                INSERT INTO orders (user_id, order_id, product_name, price, quantity, total)
                VALUES ($1, $2, $3, $4, $5, $6)
            ", [
                $user_id,
                $order_id,
                $item['product_name'],
                $item['price'],
                $item['quantity'],
                $total_item
            ]);
        }
    }

    pg_query_params($conn, "DELETE FROM user_cart WHERE user_id = $1", [$user_id]);
} else {
    echo "<h2 style='color:red;text-align:center;'>Invalid payment details or cart is empty.</h2>";
    echo "<p style='text-align:center;'><a href='dashboard.php' style='color:yellow;'>Return to Dashboard</a></p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment Receipt - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col">

  <!-- Navbar (same as my_delivery_orders.php) -->
  <nav class="bg-gray-900 p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="logo.jpg" alt="Logo" class="h-10 w-10 rounded-full border-2 border-yellow-400" />
        <span class="text-2xl font-bold text-yellow-400">Sip & Sit</span>
      </div>
      <div class="flex gap-4 items-center">
        <a href="dashboard.php" class="text-yellow-300 border border-yellow-400 px-3 py-1 rounded-md text-sm hover:bg-yellow-400 hover:text-black">Dashboard</a>
        <span class="text-yellow-300 text-sm border border-yellow-400 px-3 py-1 rounded-md">Hi, <?= htmlspecialchars($first_name) ?>!</span>
        <a href="logout.php" class="text-red-400 border border-red-400 px-3 py-1 rounded-md text-sm hover:bg-red-400 hover:text-black">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="flex-grow flex items-center justify-center p-6">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg text-center max-w-md w-full">
      <h1 class="text-3xl font-bold text-green-400 mb-4">✅ Payment Successful!</h1>
      <p class="text-yellow-300 mb-2">Thank you for your payment.</p>
      <hr class="my-4 border-yellow-400">

      <div class="text-left text-sm space-y-2">
        <p><strong>Date:</strong> <?= htmlspecialchars($payment['date']) ?></p>
        <p><strong>Paid Amount:</strong> Rs. <?= number_format($payment['amount']) ?></p>
        <p><strong>Card:</strong> **** **** **** <?= htmlspecialchars($payment['card_last4']) ?></p>
        <p><strong>Order ID:</strong> #<?= htmlspecialchars($order_id) ?></p>
      </div>

      <div class="mt-6 flex flex-col gap-3 items-center">
        <a href="dashboard.php" class="bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-300 transition font-semibold">
          Back to Dashboard
        </a>

        <?php if ($order_type === 'Delivery'): ?>
          <a href="my_delivery_orders.php" class="bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-300 transition font-semibold">
            My Delivery Status
          </a>
        <?php elseif ($order_type === 'Dine-in'): ?>
          <p class="text-green-300 font-medium text-sm mt-2">🍽️ We will arrange your table in 10 minutes.</p>
        <?php elseif ($order_type === 'Takeaway'): ?>
          <p class="text-blue-300 font-medium text-sm mt-2">🥤 Come again to dope with us!</p>
        <?php endif; ?>
      </div>
    </div>
  </main>

</body>
</html>

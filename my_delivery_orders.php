<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_name'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];

// DB connection
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

// Fetch delivery orders for the user
$sql = "
    SELECT d.id AS delivery_id, d.address, d.phone, d.created_at,
           o.id AS order_id, o.order_type
    FROM delivery_details d
    JOIN order_types o ON d.order_type_id = o.id
    WHERE d.user_id = $1
    ORDER BY d.created_at DESC
";
$result = pg_query_params($conn, $sql, [$user_id]);

$orders = pg_fetch_all($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Delivery Orders - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white">

  <nav class="bg-gray-900 p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="logo.jpg" alt="Logo" class="h-10 w-10 rounded-b-full border-2 border-yellow-400" />
        <span class="text-2xl font-bold text-yellow-400">Sip & Sit</span>
      </div>
      <div class="flex gap-4 items-center">
        <a href="dashboard.php" class="text-yellow-300 border border-yellow-400 px-3 py-1 rounded-md text-sm hover:bg-yellow-400 hover:text-black">Dashboard</a>
        <span class="text-yellow-300 text-sm border border-yellow-400 px-3 py-1 rounded-md">Hi, <?= htmlspecialchars($first_name) ?>!</span>
        <a href="logout.php" class="text-red-400 border border-red-400 px-3 py-1 rounded-md text-sm hover:bg-red-400 hover:text-black">Logout</a>
      </div>
    </div>
  </nav>

  <main class="max-w-4xl mx-auto mt-10 p-6 bg-gray-800 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold text-yellow-400 text-center mb-6">📦 My Delivery Orders</h1>

    <?php if (!$orders): ?>
      <p class="text-center text-gray-400">No delivery orders found.</p>
    <?php else: ?>
      <div class="space-y-5">
        <?php foreach ($orders as $order): ?>
          <div class="bg-gray-900 p-4 rounded-md shadow">
            <p class="text-lg text-yellow-300 font-semibold mb-1">Delivery #<?= htmlspecialchars($order['delivery_id']) ?></p>
            <p class="text-sm text-gray-300">Address: <?= htmlspecialchars($order['address']) ?></p>
            <p class="text-sm text-gray-300">Phone: <?= htmlspecialchars($order['phone']) ?></p>
            <p class="text-sm text-gray-400 mt-1">Ordered on: <?= date('Y-m-d h:i A', strtotime($order['created_at'])) ?></p>

            <div class="flex justify-between items-center mt-4">
              <span class="bg-green-600 text-white px-4 py-2 rounded font-semibold text-sm shadow-md">✅ Payment Successfully</span>
              <a href="dashboard.php" class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 px-4 py-2 rounded text-sm font-bold transition">Go to Dashboard</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <footer class="bg-gray-950 text-center py-6 text-gray-50 text-sm mt-10">
    <p>&copy; 2025 Sip & Sit. All rights reserved.</p>
    <p>Crafted with passion. Drink responsibly.</p>
  </footer>

</body>
</html>

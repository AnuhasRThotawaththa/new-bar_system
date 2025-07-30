<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_first_name = $_SESSION["first_name"] ?? 'Chef';

$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Failed to connect to the database.");
}

// Fetch pending bites orders safely
$orders = pg_query_params($conn, "SELECT * FROM order_bites WHERE status = $1 ORDER BY added_at DESC", ['Pending']);

// Count summary statistics safely
$count_pending_res = pg_query_params($conn, "SELECT COUNT(*) FROM order_bites WHERE status = $1", ['Pending']);
$count_progress_res = pg_query_params($conn, "SELECT COUNT(*) FROM order_bites WHERE status = $1", ['In Progress']);
$count_completed_res = pg_query_params($conn, "SELECT COUNT(*) FROM order_bites WHERE status = $1 AND DATE(added_at) = CURRENT_DATE", ['Completed']);

$count_pending = $count_pending_res ? pg_fetch_result($count_pending_res, 0, 0) : 0;
$count_progress = $count_progress_res ? pg_fetch_result($count_progress_res, 0, 0) : 0;
$count_completed = $count_completed_res ? pg_fetch_result($count_completed_res, 0, 0) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Chef Dashboard - Sip & Sit</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
  <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-950 text-white min-h-screen p-6">

  <!-- Header -->
  <header class="flex justify-between items-center max-w-6xl mx-auto mb-6">
    <h1 class="text-4xl font-extrabold text-yellow-400">
      👨‍🍳 Welcome, Chef <?= htmlspecialchars($user_first_name) ?>
    </h1>
    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">
      Logout
    </a>
  </header>

  <!-- Summary Cards -->
  <main class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-gray-800 p-5 rounded-2xl shadow-lg border-l-4 border-yellow-400">
      <h2 class="text-xl font-bold mb-2">Pending Orders</h2>
      <p class="text-3xl font-semibold text-yellow-300"><?= $count_pending ?></p>
    </div>

    <div class="bg-gray-800 p-5 rounded-2xl shadow-lg border-l-4 border-green-500">
      <h2 class="text-xl font-bold mb-2">In Progress</h2>
      <p class="text-3xl font-semibold text-green-300"><?= $count_progress ?></p>
    </div>

    <div class="bg-gray-800 p-5 rounded-2xl shadow-lg border-l-4 border-blue-500">
      <h2 class="text-xl font-bold mb-2">Completed Today</h2>
      <p class="text-3xl font-semibold text-blue-300"><?= $count_completed ?></p>
    </div>
  </main>

  <!-- Orders Table -->
  <section class="max-w-6xl mx-auto bg-gray-800 p-6 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-4 text-yellow-400">🧾 Pending Kitchen Orders</h2>

    <div class="overflow-x-auto">
      <?php if ($orders && pg_num_rows($orders) > 0): ?>
      <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-700 text-yellow-300">
          <tr>
            <th class="py-3 px-4 text-left">#Order ID</th>
            <th class="py-3 px-4 text-left">Product</th>
            <th class="py-3 px-4 text-left">Quantity</th>
            <th class="py-3 px-4 text-left">Total</th>
            <th class="py-3 px-4 text-left">Added At</th>
            <th class="py-3 px-4 text-left">Status</th>
            <th class="py-3 px-4 text-left">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-300 divide-y divide-gray-600">
          <?php while ($row = pg_fetch_assoc($orders)): ?>
            <tr>
              <td class="py-3 px-4">#<?= $row['id'] ?></td>
              <td class="py-3 px-4"><?= htmlspecialchars($row['product_name']) ?></td>
              <td class="py-3 px-4"><?= (int)$row['quantity'] ?></td>
              <td class="py-3 px-4">Rs. <?= number_format((int)$row['total']) ?></td>
              <td class="py-3 px-4"><?= date('M d, H:i', strtotime($row['added_at'])) ?></td>
              <td class="py-3 px-4 text-yellow-400"><?= htmlspecialchars($row['status']) ?></td>
              <td class="py-3 px-4">
                <form method="POST" action="mark_bite_progress.php" onsubmit="return confirm('Mark this order as In Progress?');">
                  <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                  <button type="submit" class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-sm">
                    Mark In Progress
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p class="text-center text-gray-400">No pending orders found.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center text-gray-500 mt-10 text-sm">
    &copy; <?= date("Y") ?> Sip & Sit. All rights reserved.
  </footer>

</body>
</html>

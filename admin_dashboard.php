<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white">

  <div class="max-w-6xl mx-auto p-4">
    <h1 class="text-3xl font-bold text-yellow-400 mb-4">Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      <a href="admin_products.php" class="bg-yellow-400 text-gray-900 py-3 rounded text-center font-semibold hover:bg-yellow-300">Manage Products</a>
      <a href="admin_table_bookings.php" class="bg-yellow-400 text-gray-900 py-3 rounded text-center font-semibold hover:bg-yellow-300">View Table Bookings</a>
      <a href="admin_driver_bookings.php" class="bg-yellow-400 text-gray-900 py-3 rounded text-center font-semibold hover:bg-yellow-300">View Driver Bookings</a>
    </div>

    <a href="admin_logout.php" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-400 transition">Logout</a>
  </div>

</body>
</html>

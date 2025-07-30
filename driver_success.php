<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header('Location: login.php');
  exit;
}

$user_first_name = htmlspecialchars($_SESSION['user_first_name']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Driver Booking Success - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-white">

  <!-- Navbar -->
  <nav class="bg-gray-950 p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-b-full border-2 border-yellow-400" />
        <span class="text-2xl font-bold text-yellow-400">Sip & Sit</span>
      </div>
      <div class="flex gap-4 items-center">
        <a href="dashboard.php" class="border border-yellow-300 text-yellow-300 px-3 py-1 rounded-md text-sm hover:bg-yellow-300 hover:text-gray-900 transition">Dashboard</a>
        <span class="border border-yellow-300 text-yellow-300 px-3 py-1 rounded-md text-sm">Hi, <?= $user_first_name ?>!</span>
        <a href="logout.php" class="border border-red-400 text-red-400 px-3 py-1 rounded-md text-sm hover:bg-red-400 hover:text-gray-900 transition">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Success Message Section -->
  <section class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg max-w-md w-full">
      <h1 class="text-3xl font-bold text-green-400 mb-4">🚗 Driver Booked Successfully!</h1>
      <p class="text-gray-300 mb-6">Your driver booking has been received. One of our trusted drivers will contact you shortly. Thank you for choosing Sip & Sit!</p>
      <a href="dashboard.php" class="bg-yellow-400 text-gray-900 font-semibold px-6 py-2 rounded hover:bg-yellow-300 transition">Back to Dashboard</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-950 text-center py-6 text-sm text-gray-50">
    <p>&copy; 2025 Sip & Sit. All rights reserved.</p>
    <p>Crafted with passion. Drink responsibly.</p>
  </footer>

</body>
</html>

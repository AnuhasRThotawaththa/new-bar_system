<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_first_name = htmlspecialchars($_SESSION['user_first_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Success - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-gray-950 p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-b-full border-2 border-yellow-400" />
        <span class="text-2xl font-bold text-yellow-400">Sip & Sit</span>
      </div>
      <div class="flex gap-4 items-center">
        <a href="dashboard.php" class="text-yellow-300 hover:underline">Dashboard</a>
        <span class="text-yellow-300 border border-yellow-300 px-3 py-1 rounded-md text-sm">Hi, <?= $user_first_name ?>!</span>
        <a href="logout.php" class="text-red-400 border border-red-400 px-3 py-1 rounded-md text-sm hover:bg-red-400 hover:text-gray-900 transition">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Confirmation Message -->
  <main class="flex-grow flex items-center justify-center px-4">
    <div class="bg-gray-800 rounded-lg shadow-xl p-8 text-center max-w-md w-full">
      <svg class="mx-auto mb-4 w-16 h-16 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
      <h2 class="text-2xl font-bold text-yellow-400 mb-2">Booking Confirmed!</h2>
      <p class="text-gray-300 mb-4">Your table has been successfully booked. We look forward to serving you at Sip & Sit!</p>
      <a href="dashboard.php" class="inline-block bg-yellow-400 text-black font-semibold px-6 py-2 rounded hover:bg-yellow-300 transition">Back to Dashboard</a>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-950 text-center py-6 text-sm text-gray-50">
    <p>&copy; 2025 Sip & Sit. All rights reserved.</p>
    <p>Crafted with passion. Drink responsibly.</p>
  </footer>

</body>
</html>

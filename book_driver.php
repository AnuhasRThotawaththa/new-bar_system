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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Book a Driver - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .bg-slideshow {
      position: fixed;
      top: 0;
      left: 0;
      z-index: -10;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }

    .bg-slideshow::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 2;
    }

    .bg-slideshow img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0;
      transition: opacity 2s ease-in-out;
      z-index: 1;
    }

    .bg-slideshow img.active {
      opacity: 1;
    }
  </style>
</head>

<body class="bg-black/80 text-white relative">

  <!-- Background Slideshow -->
  <div class="bg-slideshow">
    <img src="1.jpg" class="active" />
    <img src="2.jpg" />
    <img src="3.jpg" />
    <img src="4.jpg" />
    <img src="5.jpg" />
  </div>

  <!-- Navbar -->
  <nav class="bg-gray-900/60 backdrop-blur-sm p-4 shadow-lg relative z-10">
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

  <!-- Book Driver Section -->
  <section class="max-w-xl mx-auto py-16 px-4 text-center relative z-10">
    <h1 class="text-4xl font-bold text-yellow-400 mb-6">Book a Driver 🚗</h1>
    <p class="text-gray-300 mb-10">Too tipsy to drive? Let us help you get home safely with our trusted drivers.</p>

    <form action="submit_driver.php" method="POST" class="bg-gray-900/60 backdrop-blur-md p-6 rounded-lg shadow-lg space-y-5 text-left">
      <div>
        <label class="block mb-1 text-sm text-gray-300" for="pickup">Pickup Location</label>
        <input type="text" name="pickup" id="pickup" required class="w-full px-3 py-2 rounded bg-gray-800 border border-gray-600 text-white" />
      </div>
      <div>
        <label class="block mb-1 text-sm text-gray-300" for="dropoff">Drop-off Location</label>
        <input type="text" name="dropoff" id="dropoff" required class="w-full px-3 py-2 rounded bg-gray-800 border border-gray-600 text-white" />
      </div>
      <div>
        <label class="block mb-1 text-sm text-gray-300" for="time">Pickup Time</label>
        <input type="time" name="time" id="time" required class="w-full px-3 py-2 rounded bg-gray-800 border border-gray-600 text-white" />
      </div>
      <div>
        <label class="block mb-1 text-sm text-gray-300" for="date">Date</label>
        <input type="date" name="date" id="date" required class="w-full px-3 py-2 rounded bg-gray-800 border border-gray-600 text-white" />
      </div>

      <button type="submit" class="w-full bg-yellow-400 text-black font-semibold py-2 rounded hover:bg-yellow-300 transition">Confirm Booking</button>
    </form>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-950/80 backdrop-blur-sm text-center py-6 text-sm text-gray-400 relative z-10">
    <p>&copy; 2025 Sip & Sit. All rights reserved.</p>
    <p>Crafted with passion. Drink responsibly.</p>
  </footer>

  <!-- Slideshow Script -->
  <script>
    const slides = document.querySelectorAll('.bg-slideshow img');
    let index = 0;

    setInterval(() => {
      slides.forEach(img => img.classList.remove('active'));
      index = (index + 1) % slides.length;
      slides[index].classList.add('active');
    }, 3500);
  </script>
</body>
</html>

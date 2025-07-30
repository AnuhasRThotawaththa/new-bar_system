<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          animation: {
            fadeIn: 'fadeIn 1s ease-in-out forwards',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: 0, transform: 'translateY(20px)' },
              '100%': { opacity: 1, transform: 'translateY(0)' },
            },
          },
        }
      }
    };
  </script>
</head>

<body class="bg-gray-950 text-white">

  <!-- Navbar -->
  <nav class="fixed w-full top-0 z-30 glass py-3 transition-all duration-300 bg-gray-950 shadow-lg" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex-shrink-0 flex items-center">
          <a href="index.php" class="flex items-center space-x-3">
            <div class="gradient-border rounded-full p-1">
              <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-full object-cover border-2 border-yellow-400" />
            </div>
            <span class="text-2xl font-bold text-yellow-400 font-serif">Sip & Sit</span>
          </a>
        </div>

        <div class="hidden md:flex items-center space-x-8">
          <a href="about.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
            About Us
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
          <a href="contact.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
            Contact
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
          <a href="login.php"
            class="px-4 py-2 rounded-full bg-yellow-400 text-gray-900 font-medium hover:bg-yellow-300 transition-colors duration-300 flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            <span>Sign In</span>
          </a>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button id="mobileMenuButton" class="text-gray-300 hover:text-yellow-400 focus:outline-none">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Login Section -->
  <div class="pt-24 min-h-screen flex flex-col items-center justify-center px-4">
    <div class="bg-gray-900 p-8 rounded-lg shadow-2xl w-full max-w-md animate-fadeIn">
      <h2 class="text-3xl font-bold text-yellow-400 mb-6 text-center">Sign In to Sip & Sit</h2>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-500 text-white p-2 rounded mb-4 animate-fadeIn">
          <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <form action="process_login.php" method="POST" class="space-y-5 animate-fadeIn">
        <div>
          <label class="block text-gray-300 text-sm mb-1" for="email">Email</label>
          <input type="email" name="email" id="email" required
            class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" />
        </div>
        <div>
          <label class="block text-gray-300 text-sm mb-1" for="password">Password</label>
          <input type="password" name="password" id="password" required
            class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" />
        </div>
        <div>
          <label class="block text-gray-300 text-sm mb-1" for="role">Login As</label>
          <select id="role" name="role" required
            class="w-full p-3 rounded bg-gray-800 text-white border border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            <option value="customer">Customer</option>
            <option value="chef">Chef</option>
            <option value="rider">Rider</option>
          </select>
        </div>
        <button type="submit"
          class="w-full bg-yellow-400 text-gray-900 font-semibold py-2 rounded hover:bg-yellow-300 transition transform hover:scale-105">
          Sign In
        </button>
      </form>

      <p class="text-gray-400 text-sm mt-4 text-center animate-fadeIn">
        Don't have an account?
        <a href="register.php" class="text-yellow-400 hover:underline">Register here</a>
      </p>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-950 text-center py-6 mt-10 text-sm text-gray-400 w-full animate-fadeIn">
      <p>&copy; 2025 Sip & Sit. All rights reserved.</p>
      <p>Crafted with passion. Drink responsibly.</p>
    </footer>
  </div>

  <!-- Optional: Mobile menu toggle JS -->
  <script>
    const mobileMenuBtn = document.getElementById('mobileMenuButton');
    mobileMenuBtn.addEventListener('click', () => {
      alert("Mobile menu can be implemented here if needed.");
    });
  </script>
</body>

</html>

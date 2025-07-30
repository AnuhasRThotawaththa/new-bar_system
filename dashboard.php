<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_first_name = $_SESSION["first_name"] ?? 'customer';
$user_avatar = $_SESSION["avatar"] ?? 'default-avatar.jpg';
$last_login = $_SESSION["last_login"] ?? 'First time login!';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Sip & Sit</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    :root {
      --primary: #fbbf24;
      --primary-dark: #d97706;
      --secondary: #1e40af;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #0f172a;
      color: #f8fafc;
    }
    
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
      background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.7) 100%);
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
      transform: scale(1.1);
      transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 1;
    }
    
    .bg-slideshow img.active {
      opacity: 1;
      transform: scale(1);
    }
    
    .card-hover {
      transition: all 0.3s ease;
      transform: translateY(0);
    }
    
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .menu-item {
      transition: all 0.3s ease;
      transform: scale(1);
    }
    
    .menu-item:hover {
      transform: scale(1.05);
    }
    
    .floating {
      animation: floating 6s ease-in-out infinite;
    }
    
    @keyframes floating {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
      100% { transform: translateY(0px); }
    }
    
    .pulse {
      animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      width: 20px;
      height: 20px;
      background-color: #ef4444;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      font-weight: bold;
    }
    
    .smooth-scroll {
      scroll-behavior: smooth;
    }
    
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>

<body class="smooth-scroll">

  <!-- Slideshow Background -->
  <div class="bg-slideshow">
    <img src="1.jpg" class="active" />
    <img src="2.jpg" />
    <img src="3.jpg" />
    <img src="4.jpg" />
    <img src="5.jpg" />
  </div>

  <!-- Navbar -->
  <nav class="bg-gray-900/80 backdrop-blur-sm p-4 shadow-lg sticky top-0 z-50 border-b border-gray-700">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-full border-2 border-yellow-400 object-cover hover:rotate-12 transition-transform" />
        <span class="text-2xl font-bold text-yellow-400 hover:text-yellow-300 transition">Sip & Sit</span>
      </div>
      <div class="flex gap-4 items-center">
        <div class="relative group">
          <button class="p-2 rounded-full bg-gray-800/50 hover:bg-gray-700 transition relative">
            <i class="fas fa-bell text-yellow-300"></i>
            <span class="notification-badge">3</span>
          </button>
          <div class="absolute right-0 mt-2 w-72 bg-gray-800 rounded-lg shadow-xl p-4 hidden group-hover:block z-50">
            <h4 class="font-bold text-yellow-300 mb-2">Notifications</h4>
            <div class="space-y-2">
              <div class="p-2 bg-gray-700/50 rounded hover:bg-gray-700 cursor-pointer">
                <p class="text-sm">Your table reservation is confirmed!</p>
                <p class="text-xs text-gray-400">2 mins ago</p>
              </div>
              <div class="p-2 bg-gray-700/50 rounded hover:bg-gray-700 cursor-pointer">
                <p class="text-sm">New seasonal drinks available!</p>
                <p class="text-xs text-gray-400">1 hour ago</p>
              </div>
              <div class="p-2 bg-gray-700/50 rounded hover:bg-gray-700 cursor-pointer">
                <p class="text-sm">Special offer: 20% off on whiskeys</p>
                <p class="text-xs text-gray-400">5 hours ago</p>
              </div>
            </div>
            <a href="#" class="block text-center text-yellow-300 text-sm mt-2 hover:underline">View all</a>
          </div>
        </div>
        
        <div class="relative group">
          <button class="flex items-center gap-2 border border-yellow-300 text-yellow-300 px-3 py-1 rounded-md text-sm hover:bg-yellow-300 hover:text-gray-900 transition">
            <img src="<?= $user_avatar ?>" class="h-6 w-6 rounded-full object-cover" />
            Hi, <?= $user_first_name ?>!
            <i class="fas fa-chevron-down text-xs"></i>
          </button>
          <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl p-2 hidden group-hover:block z-50">
            <a href="profile.php" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded flex items-center gap-2">
              <i class="fas fa-user text-yellow-300"></i> My Profile
            </a>
            <a href="reservations.php" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded flex items-center gap-2">
              <i class="fas fa-calendar-check text-yellow-300"></i> My Reservations
            </a>
            <a href="orders.php" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded flex items-center gap-2">
              <i class="fas fa-receipt text-yellow-300"></i> Order History
            </a>
            <div class="border-t border-gray-700 my-1"></div>
            <a href="logout.php" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded flex items-center gap-2 text-red-400">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="relative flex flex-col justify-center items-center text-center py-24 px-4 z-10">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl md:text-6xl font-extrabold text-yellow-400 mb-6 leading-tight">
        Welcome back, <span class="text-white"><?= $user_first_name ?></span>!
      </h1>
      <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-2xl mx-auto">
        Discover our new seasonal menu, reserve your perfect spot, and enjoy a night to remember.
      </p>
      
      <div class="flex flex-wrap gap-4 justify-center mb-8">
        <a href="#order" class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-semibold px-6 py-3 rounded-lg text-lg transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center gap-2">
          <i class="fas fa-glass-cheers"></i> Order Now
        </a>
        <a href="#reserve" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-6 py-3 rounded-lg text-lg transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center gap-2">
          <i class="fas fa-calendar-alt"></i> Reserve Table
        </a>
        <a href="#rides" class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-6 py-3 rounded-lg text-lg transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center gap-2">
          <i class="fas fa-car"></i> Book Ride
        </a>
      </div>
      
      <div class="bg-gray-800/60 backdrop-blur-sm rounded-lg p-4 inline-block">
        <p class="text-sm text-gray-300 flex items-center gap-2">
          <i class="fas fa-clock text-yellow-300"></i> Last login: <?= $last_login ?>
        </p>
      </div>
    </div>
    
    <div class="absolute bottom-10 animate-bounce">
      <a href="#order" class="text-gray-300 hover:text-yellow-300">
        <i class="fas fa-chevron-down text-2xl"></i>
      </a>
    </div>
  </section>

  <!-- Quick Stats Section -->
  <section class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
    <div class="bg-gray-800/80 backdrop-blur-sm rounded-lg p-6 shadow-lg card-hover border-t-4 border-yellow-400">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-400">Loyalty Points</p>
          <h3 class="text-2xl font-bold text-yellow-300">1,250</h3>
        </div>
        <div class="bg-yellow-400/20 p-3 rounded-full">
          <i class="fas fa-medal text-yellow-400 text-xl"></i>
        </div>
      </div>
      <div class="mt-4">
        <div class="w-full bg-gray-700 rounded-full h-2">
          <div class="bg-yellow-400 h-2 rounded-full" style="width: 65%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-1">350 more for Gold status</p>
      </div>
    </div>
    
    <div class="bg-gray-800/80 backdrop-blur-sm rounded-lg p-6 shadow-lg card-hover border-t-4 border-blue-400">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-400">Upcoming Reservation</p>
          <h3 class="text-2xl font-bold text-blue-300">Tomorrow</h3>
        </div>
        <div class="bg-blue-400/20 p-3 rounded-full">
          <i class="fas fa-calendar-day text-blue-400 text-xl"></i>
        </div>
      </div>
      <div class="mt-4">
        <p class="text-sm text-gray-300">8:30 PM for 4 people</p>
      </div>
    </div>
    
    <div class="bg-gray-800/80 backdrop-blur-sm rounded-lg p-6 shadow-lg card-hover border-t-4 border-purple-400">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-400">Monthly Visits</p>
          <h3 class="text-2xl font-bold text-purple-300">7</h3>
        </div>
        <div class="bg-purple-400/20 p-3 rounded-full">
          <i class="fas fa-chart-line text-purple-400 text-xl"></i>
        </div>
      </div>
      <div class="mt-4">
        <p class="text-sm text-gray-300">+3 from last month</p>
      </div>
    </div>
    
    <div class="bg-gray-800/80 backdrop-blur-sm rounded-lg p-6 shadow-lg card-hover border-t-4 border-green-400">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-400">Saved Rides</p>
          <h3 class="text-2xl font-bold text-green-300">2</h3>
        </div>
        <div class="bg-green-400/20 p-3 rounded-full">
          <i class="fas fa-car text-green-400 text-xl"></i>
        </div>
      </div>
      <div class="mt-4">
        <p class="text-sm text-gray-300">Ready when you are</p>
      </div>
    </div>
  </section>

  <!-- Order Section -->
  <section id="order" class="max-w-7xl mx-auto px-4 py-16 relative z-10">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4">What would you like to order?</h2>
      <p class="text-gray-400 max-w-2xl mx-auto">Explore our carefully curated selection of premium drinks and delicious bites.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <a href="order_beers.php" class="bg-gray-800/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover menu-item border border-gray-700 hover:border-yellow-400 transition-all">
        <div class="h-48 bg-gradient-to-br from-amber-700 to-amber-900 flex items-center justify-center">
          <i class="fas fa-beer text-6xl text-white opacity-80"></i>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-yellow-300 mb-2">Craft Beers</h3>
          <p class="text-gray-400 text-sm">From local IPAs to imported stouts</p>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-yellow-300 font-medium">Explore</span>
            <span class="text-xs bg-yellow-400/20 text-yellow-300 px-2 py-1 rounded">15+ options</span>
          </div>
        </div>
      </a>
      
      <a href="order_wines.php" class="bg-gray-800/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover menu-item border border-gray-700 hover:border-yellow-400 transition-all">
        <div class="h-48 bg-gradient-to-br from-purple-700 to-purple-900 flex items-center justify-center">
          <i class="fas fa-wine-glass-alt text-6xl text-white opacity-80"></i>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-yellow-300 mb-2">Fine Wines</h3>
          <p class="text-gray-400 text-sm">Red, white, rosé and sparkling</p>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-yellow-300 font-medium">Explore</span>
            <span class="text-xs bg-yellow-400/20 text-yellow-300 px-2 py-1 rounded">30+ options</span>
          </div>
        </div>
      </a>
      
      <a href="order_whiskey.php" class="bg-gray-800/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover menu-item border border-gray-700 hover:border-yellow-400 transition-all">
        <div class="h-48 bg-gradient-to-br from-amber-800 to-amber-950 flex items-center justify-center">
          <i class="fas fa-whiskey-glass text-6xl text-white opacity-80"></i>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-yellow-300 mb-2">Premium Whiskeys</h3>
          <p class="text-gray-400 text-sm">Scotch, bourbon, rye and more</p>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-yellow-300 font-medium">Explore</span>
            <span class="text-xs bg-yellow-400/20 text-yellow-300 px-2 py-1 rounded">50+ options</span>
          </div>
        </div>
      </a>
      
      <a href="order_bites.php" class="bg-gray-800/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover menu-item border border-gray-700 hover:border-yellow-400 transition-all">
        <div class="h-48 bg-gradient-to-br from-red-700 to-red-900 flex items-center justify-center">
          <i class="fas fa-utensils text-6xl text-white opacity-80"></i>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-yellow-300 mb-2">Delicious Bites</h3>
          <p class="text-gray-400 text-sm">Perfect pairings for your drinks</p>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-yellow-300 font-medium">Explore</span>
            <span class="text-xs bg-yellow-400/20 text-yellow-300 px-2 py-1 rounded">25+ options</span>
          </div>
        </div>
      </a>
    </div>
    
    <div class="mt-12 text-center">
      <a href="full_menu.php" class="inline-flex items-center gap-2 text-yellow-300 hover:text-yellow-200 font-medium">
        View full menu <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </section>

  <!-- Reservation Section -->
  <section id="reserve" class="max-w-7xl mx-auto px-4 py-16 bg-gray-800/30 backdrop-blur-sm rounded-2xl my-12 relative z-10">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4">Reserve Your Table</h2>
      <p class="text-gray-400 max-w-2xl mx-auto">Book in advance to secure the perfect spot for your night out.</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
      <div>
        <div class="bg-gray-800/80 rounded-xl p-6 shadow-lg">
          <h3 class="text-xl font-bold text-white mb-4">Make a Reservation</h3>
          
          <form id="reservationForm" class="space-y-4">
            <div>
              <label for="reservation-date" class="block text-sm font-medium text-gray-300 mb-1">Date</label>
              <input type="date" id="reservation-date" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none">
            </div>
            
            <div>
              <label for="reservation-time" class="block text-sm font-medium text-gray-300 mb-1">Time</label>
              <select id="reservation-time" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none">
                <option value="">Select time</option>
                <option value="17:00">5:00 PM</option>
                <option value="17:30">5:30 PM</option>
                <option value="18:00">6:00 PM</option>
                <option value="18:30">6:30 PM</option>
                <option value="19:00">7:00 PM</option>
                <option value="19:30">7:30 PM</option>
                <option value="20:00">8:00 PM</option>
                <option value="20:30">8:30 PM</option>
                <option value="21:00">9:00 PM</option>
                <option value="21:30">9:30 PM</option>
              </select>
            </div>
            
            <div>
              <label for="reservation-party" class="block text-sm font-medium text-gray-300 mb-1">Party Size</label>
              <select id="reservation-party" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none">
                <option value="">Select party size</option>
                <option value="1">1 person</option>
                <option value="2">2 people</option>
                <option value="3">3 people</option>
                <option value="4">4 people</option>
                <option value="5">5 people</option>
                <option value="6">6 people</option>
                <option value="7">7 people</option>
                <option value="8">8+ people</option>
              </select>
            </div>
            
            <div>
              <label for="reservation-area" class="block text-sm font-medium text-gray-300 mb-1">Preferred Area</label>
              <select id="reservation-area" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none">
                <option value="">No preference</option>
                <option value="bar">Bar seating</option>
                <option value="window">Window table</option>
                <option value="patio">Outdoor patio</option>
                <option value="private">Private booth</option>
              </select>
            </div>
            
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-semibold py-3 px-4 rounded-lg transition-all transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
              Check Availability
            </button>
          </form>
        </div>
      </div>
      
      <div>
        <div class="relative h-full min-h-[400px] rounded-xl overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-900 to-blue-700 opacity-80"></div>
          <div class="absolute inset-0 flex items-center justify-center">
            <i class="fas fa-calendar-alt text-8xl text-white opacity-20"></i>
          </div>
          <div class="relative z-10 p-8 h-full flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-white mb-4">Why Reserve?</h3>
            <ul class="space-y-4">
              <li class="flex items-start gap-3">
                <div class="bg-blue-400/20 p-2 rounded-full">
                  <i class="fas fa-check text-blue-400"></i>
                </div>
                <p class="text-gray-200">Guaranteed seating during peak hours</p>
              </li>
              <li class="flex items-start gap-3">
                <div class="bg-blue-400/20 p-2 rounded-full">
                  <i class="fas fa-check text-blue-400"></i>
                </div>
                <p class="text-gray-200">Priority service for reservations</p>
              </li>
              <li class="flex items-start gap-3">
                <div class="bg-blue-400/20 p-2 rounded-full">
                  <i class="fas fa-check text-blue-400"></i>
                </div>
                <p class="text-gray-200">Special requests accommodated</p>
              </li>
              <li class="flex items-start gap-3">
                <div class="bg-blue-400/20 p-2 rounded-full">
                  <i class="fas fa-check text-blue-400"></i>
                </div>
                <p class="text-gray-200">Earn double loyalty points</p>
              </li>
            </ul>
            
            <div class="mt-8">
              <a href="reservations.php" class="inline-flex items-center gap-2 text-blue-300 hover:text-blue-200 font-medium">
                View my reservations <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Ride Service Section -->
  <section id="rides" class="max-w-7xl mx-auto px-4 py-16 relative z-10">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4">Safe Ride Home</h2>
      <p class="text-gray-400 max-w-2xl mx-auto">Enjoy your night without worrying about how to get home.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gray-800/80 backdrop-blur-sm rounded-xl p-6 shadow-lg card-hover border border-gray-700 hover:border-emerald-500 transition-all">
        <div class="flex items-center gap-4 mb-4">
          <div class="bg-emerald-400/20 p-3 rounded-full">
            <i class="fas fa-car text-emerald-400 text-xl"></i>
          </div>
          <h3 class="text-xl font-bold text-emerald-300">Standard Ride</h3>
        </div>
        <p class="text-gray-400 mb-4">Regular vehicle with professional driver</p>
        <div class="flex items-center justify-between">
          <span class="text-emerald-300 font-bold">$25</span>
          <button class="text-sm bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg transition">
            Book Now
          </button>
        </div>
      </div>
      
      <div class="bg-gray-800/80 backdrop-blur-sm rounded-xl p-6 shadow-lg card-hover border border-gray-700 hover:border-emerald-500 transition-all">
        <div class="flex items-center gap-4 mb-4">
          <div class="bg-emerald-400/20 p-3 rounded-full">
            <i class="fas fa-car-side text-emerald-400 text-xl"></i>
          </div>
          <h3 class="text-xl font-bold text-emerald-300">Premium Ride</h3>
        </div>
        <p class="text-gray-400 mb-4">Luxury vehicle with premium service</p>
        <div class="flex items-center justify-between">
          <span class="text-emerald-300 font-bold">$45</span>
          <button class="text-sm bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg transition">
            Book Now
          </button>
        </div>
      </div>
      
      <div class="bg-gray-800/80 backdrop-blur-sm rounded-xl p-6 shadow-lg card-hover border border-gray-700 hover:border-emerald-500 transition-all">
        <div class="flex items-center gap-4 mb-4">
          <div class="bg-emerald-400/20 p-3 rounded-full">
            <i class="fas fa-van-shuttle text-emerald-400 text-xl"></i>
          </div>
          <h3 class="text-xl font-bold text-emerald-300">Group Ride</h3>
        </div>
        <p class="text-gray-400 mb-4">For groups up to 8 people</p>
        <div class="flex items-center justify-between">
          <span class="text-emerald-300 font-bold">$65</span>
          <button class="text-sm bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg transition">
            Book Now
          </button>
        </div>
      </div>
    </div>
    
    <div class="mt-12 bg-gray-800/80 backdrop-blur-sm rounded-xl p-6 shadow-lg">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-xl font-bold text-white mb-4">Schedule a Ride for Later</h3>
          <p class="text-gray-400 mb-4">Plan ahead and schedule your ride home when you make your reservation.</p>
          <ul class="space-y-2 mb-6">
            <li class="flex items-center gap-2 text-gray-300">
              <i class="fas fa-check-circle text-emerald-400"></i>
              <span>No surge pricing - fixed rates</span>
            </li>
            <li class="flex items-center gap-2 text-gray-300">
              <i class="fas fa-check-circle text-emerald-400"></i>
              <span>Cancel anytime up to 1 hour before</span>
            </li>
            <li class="flex items-center gap-2 text-gray-300">
              <i class="fas fa-check-circle text-emerald-400"></i>
              <span>Driver will wait up to 15 minutes</span>
            </li>
          </ul>
          <a href="rides.php" class="inline-flex items-center gap-2 text-emerald-300 hover:text-emerald-200 font-medium">
            View ride history <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        
        <div class="relative h-full min-h-[200px]">
          <canvas id="rideChart"></canvas>
        </div>
      </div>
    </div>
  </section>

  <!-- Special Offers Section -->
  <section class="max-w-7xl mx-auto px-4 py-16 relative z-10">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4">Special Offers</h2>
      <p class="text-gray-400 max-w-2xl mx-auto">Exclusive deals just for you</p>
    </div>
    
    <div class="relative">
      <div class="flex overflow-x-auto scrollbar-hide gap-6 pb-6">
        <div class="flex-none w-80 bg-gradient-to-br from-amber-600 to-amber-800 rounded-xl p-6 shadow-lg">
          <div class="flex justify-between items-start mb-4">
            <div>
              <span class="text-xs bg-black/20 text-white px-2 py-1 rounded">Limited Time</span>
              <h3 class="text-xl font-bold text-white mt-2">Happy Hour</h3>
            </div>
            <div class="bg-white/10 p-2 rounded-lg">
              <i class="fas fa-clock text-white text-xl"></i>
            </div>
          </div>
          <p class="text-white/80 text-sm mb-6">4-6PM Weekdays: 25% off all draft beers and house wines</p>
          <button class="w-full bg-white hover:bg-gray-100 text-amber-800 font-semibold py-2 px-4 rounded-lg transition">
            Learn More
          </button>
        </div>
        
        <div class="flex-none w-80 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl p-6 shadow-lg">
          <div class="flex justify-between items-start mb-4">
            <div>
              <span class="text-xs bg-black/20 text-white px-2 py-1 rounded">Members Only</span>
              <h3 class="text-xl font-bold text-white mt-2">Whiskey Flight</h3>
            </div>
            <div class="bg-white/10 p-2 rounded-lg">
              <i class="fas fa-whiskey-glass text-white text-xl"></i>
            </div>
          </div>
          <p class="text-white/80 text-sm mb-6">Sample 4 premium whiskeys for the price of 3</p>
          <button class="w-full bg-white hover:bg-gray-100 text-purple-800 font-semibold py-2 px-4 rounded-lg transition">
            Learn More
          </button>
        </div>
        
        <div class="flex-none w-80 bg-gradient-to-br from-red-600 to-red-800 rounded-xl p-6 shadow-lg">
          <div class="flex justify-between items-start mb-4">
            <div>
              <span class="text-xs bg-black/20 text-white px-2 py-1 rounded">New</span>
              <h3 class="text-xl font-bold text-white mt-2">Date Night</h3>
            </div>
            <div class="bg-white/10 p-2 rounded-lg">
              <i class="fas fa-heart text-white text-xl"></i>
            </div>
          </div>
          <p class="text-white/80 text-sm mb-6">2 cocktails + shared appetizer for $35</p>
          <button class="w-full bg-white hover:bg-gray-100 text-red-800 font-semibold py-2 px-4 rounded-lg transition">
            Learn More
          </button>
        </div>
        
        <div class="flex-none w-80 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 shadow-lg">
          <div class="flex justify-between items-start mb-4">
            <div>
              <span class="text-xs bg-black/20 text-white px-2 py-1 rounded">Weekend</span>
              <h3 class="text-xl font-bold text-white mt-2">Brunch Special</h3>
            </div>
            <div class="bg-white/10 p-2 rounded-lg">
              <i class="fas fa-martini-glass-citrus text-white text-xl"></i>
            </div>
          </div>
          <p class="text-white/80 text-sm mb-6">Unlimited mimosas with any entrée purchase</p>
          <button class="w-full bg-white hover:bg-gray-100 text-blue-800 font-semibold py-2 px-4 rounded-lg transition">
            Learn More
          </button>
        </div>
      </div>
      
      <button class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-gray-800/80 hover:bg-gray-700 rounded-full w-10 h-10 flex items-center justify-center shadow-lg z-20">
        <i class="fas fa-chevron-left text-white"></i>
      </button>
      <button class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-gray-800/80 hover:bg-gray-700 rounded-full w-10 h-10 flex items-center justify-center shadow-lg z-20">
        <i class="fas fa-chevron-right text-white"></i>
      </button>
    </div>
  </section>

  <!-- Events Section -->
  <section class="max-w-7xl mx-auto px-4 py-16 bg-gray-800/30 backdrop-blur-sm rounded-2xl my-12 relative z-10">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4">Upcoming Events</h2>
      <p class="text-gray-400 max-w-2xl mx-auto">Live music, tastings, and special gatherings</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-gray-800/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover">
        <div class="h-48 bg-gradient-to-br from-purple-700 to-purple-900 relative overflow-hidden">
          <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80')] bg-cover bg-center opacity-30"></div>
          <div class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
            Sold Out
          </div>
          <div class="absolute bottom-4 left-4">
            <span class="text-xs bg-black/40 text-white px-2 py-1 rounded">June 15, 2025</span>
          </div>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-white mb-2">Wine Tasting Night</h3>
          <p class="text-gray-400 text-sm mb-4">Sample rare vintages from our cellar with expert sommeliers</p>
          <div class="flex justify-between items-center">
            <span class="text-xs text-yellow-300">7:00 PM - 10:00 PM</span>
            <button class="text-xs bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded transition disabled:opacity-50" disabled>
              Join Waitlist
            </button>
          </div>
        </div>
      </div>
      
      <div class="bg-gray-800/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover">
        <div class="h-48 bg-gradient-to-br from-amber-700 to-amber-900 relative overflow-hidden">
          <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1514933651103-005eec06c04b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80')] bg-cover bg-center opacity-30"></div>
          <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
            5 Spots Left
          </div>
          <div class="absolute bottom-4 left-4">
            <span class="text-xs bg-black/40 text-white px-2 py-1 rounded">June 22, 2025</span>
          </div>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-white mb-2">Whiskey Masterclass</h3>
          <p class="text-gray-400 text-sm mb-4">Learn the art of whiskey appreciation from industry experts</p>
          <div class="flex justify-between items-center">
            <span class="text-xs text-yellow-300">6:30 PM - 9:30 PM</span>
            <button class="text-xs bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-3 py-1 rounded transition">
              Reserve Spot
            </button>
          </div>
        </div>
      </div>
      
      <div class="bg-gray-800/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover">
        <div class="h-48 bg-gradient-to-br from-blue-700 to-blue-900 relative overflow-hidden">
          <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80')] bg-cover bg-center opacity-30"></div>
          <div class="absolute top-4 right-4 bg-yellow-500 text-gray-900 text-xs font-bold px-2 py-1 rounded">
            Free Entry
          </div>
          <div class="absolute bottom-4 left-4">
            <span class="text-xs bg-black/40 text-white px-2 py-1 rounded">Every Friday</span>
          </div>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-white mb-2">Live Jazz Nights</h3>
          <p class="text-gray-400 text-sm mb-4">Relax to the smooth sounds of local jazz ensembles</p>
          <div class="flex justify-between items-center">
            <span class="text-xs text-yellow-300">8:00 PM - 11:00 PM</span>
            <button class="text-xs bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded transition">
              More Info
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-12 text-center">
      <a href="events.php" class="inline-flex items-center gap-2 text-yellow-300 hover:text-yellow-200 font-medium">
        View all events <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </section>

  <!-- Loyalty Program Section -->
  <section class="max-w-7xl mx-auto px-4 py-16 relative z-10">
    <div class="bg-gradient-to-br from-amber-700 to-amber-900 rounded-2xl p-8 md:p-12 shadow-2xl overflow-hidden">
      <div class="relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
          <div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Sip & Sit Loyalty Program</h2>
            <p class="text-amber-100 mb-6">Join our loyalty program and earn points with every purchase that can be redeemed for exclusive perks and discounts.</p>
            
            <div class="flex items-center gap-4 mb-8">
              <div class="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                <p class="text-amber-300 text-sm">Your Status</p>
                <h3 class="text-xl font-bold text-white">Silver Member</h3>
              </div>
              <div class="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                <p class="text-amber-300 text-sm">Points Balance</p>
                <h3 class="text-xl font-bold text-white">1,250 pts</h3>
              </div>
            </div>
            
            <div class="mb-8">
              <div class="flex justify-between text-sm text-amber-100 mb-1">
                <span>Silver (1,000 pts)</span>
                <span>Gold (1,600 pts)</span>
              </div>
              <div class="w-full bg-amber-900 rounded-full h-3">
                <div class="bg-gradient-to-r from-amber-300 to-amber-500 h-3 rounded-full" style="width: 65%"></div>
              </div>
            </div>
            
            <button class="bg-white hover:bg-gray-100 text-amber-900 font-semibold px-6 py-3 rounded-lg transition-all transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
              Redeem Points
            </button>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-amber-300/20">
              <div class="bg-amber-400/10 p-3 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <i class="fas fa-gift text-amber-300"></i>
              </div>
              <h4 class="font-bold text-white mb-1">Birthday Reward</h4>
              <p class="text-amber-100 text-sm">Free dessert or cocktail on your birthday</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-amber-300/20">
              <div class="bg-amber-400/10 p-3 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <i class="fas fa-glass-cheers text-amber-300"></i>
              </div>
              <h4 class="font-bold text-white mb-1">Welcome Drink</h4>
              <p class="text-amber-100 text-sm">Complimentary drink every 5 visits</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-amber-300/20">
              <div class="bg-amber-400/10 p-3 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <i class="fas fa-percentage text-amber-300"></i>
              </div>
              <h4 class="font-bold text-white mb-1">Member Discount</h4>
              <p class="text-amber-100 text-sm">10% off all food orders</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-amber-300/20">
              <div class="bg-amber-400/10 p-3 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                <i class="fas fa-star text-amber-300"></i>
              </div>
              <h4 class="font-bold text-white mb-1">Exclusive Access</h4>
              <p class="text-amber-100 text-sm">Early booking for special events</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-amber-400 rounded-full opacity-20 blur-xl"></div>
      <div class="absolute -left-20 -top-20 w-64 h-64 bg-amber-600 rounded-full opacity-20 blur-xl"></div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-950/90 backdrop-blur-sm py-12 px-4 relative z-10 border-t border-gray-800">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <div>
          <div class="flex items-center gap-3 mb-4">
            <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-full border-2 border-yellow-400" />
            <span class="text-xl font-bold text-yellow-400">Sip & Sit</span>
          </div>
          <p class="text-gray-400 text-sm">Crafting memorable experiences one sip at a time since 2015.</p>
          <div class="flex gap-4 mt-4">
            <a href="#" class="text-gray-400 hover:text-yellow-300 transition">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-yellow-300 transition">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-yellow-300 transition">
              <i class="fab fa-twitter"></i>
            </a>
          </div>
        </div>
        
        <div>
          <h4 class="text-lg font-bold text-white mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-yellow-300 transition text-sm">Home</a></li>
            <li><a href="#" class="text-gray-400 hover:text-yellow-300 transition text-sm">Menu</a></li>
            <li><a href="#" class="text-gray-400 hover:text-yellow-300 transition text-sm">Reservations</a></li>
            <li><a href="#" class="text-gray-400 hover:text-yellow-300 transition text-sm">Events</a></li>
            <li><a href="#" class="text-gray-400 hover:text-yellow-300 transition text-sm">Contact Us</a></li>
          </ul>
        </div>
        
        <div>
          <h4 class="text-lg font-bold text-white mb-4">Contact</h4>
          <ul class="space-y-2">
            <li class="flex items-center gap-2 text-gray-400 text-sm">
              <i class="fas fa-map-marker-alt text-yellow-300"></i>
              123 Lounge Street, Nightlife City
            </li>
            <li class="flex items-center gap-2 text-gray-400 text-sm">
              <i class="fas fa-phone text-yellow-300"></i>
              (555) 123-4567
            </li>
            <li class="flex items-center gap-2 text-gray-400 text-sm">
              <i class="fas fa-envelope text-yellow-300"></i>
              info@sipandsit.com
            </li>
            <li class="flex items-center gap-2 text-gray-400 text-sm">
              <i class="fas fa-clock text-yellow-300"></i>
              Mon-Sun: 4PM - 2AM
            </li>
          </ul>
        </div>
        
        <div>
          <h4 class="text-lg font-bold text-white mb-4">Newsletter</h4>
          <p class="text-gray-400 text-sm mb-4">Subscribe to get updates on events and special offers.</p>
          <form class="flex gap-2">
            <input type="email" placeholder="Your email" class="flex-grow bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none">
            <button type="submit" class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm transition">
              <i class="fas fa-paper-plane"></i>
            </button>
          </form>
        </div>
      </div>
      
      <div class="border-t border-gray-800 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <p class="text-gray-500 text-sm">&copy; 2025 Sip & Sit. All rights reserved.</p>
          <div class="flex gap-4 mt-4 md:mt-0">
            <a href="#" class="text-gray-500 hover:text-gray-300 text-sm transition">Privacy Policy</a>
            <a href="#" class="text-gray-500 hover:text-gray-300 text-sm transition">Terms of Service</a>
            <a href="#" class="text-gray-500 hover:text-gray-300 text-sm transition">Accessibility</a>
          </div>
        </div>
        <p class="text-center text-gray-600 text-xs mt-4">Crafted with passion. Drink responsibly.</p>
      </div>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <button id="backToTop" class="fixed bottom-8 right-8 bg-yellow-400 hover:bg-yellow-300 text-gray-900 p-3 rounded-full shadow-lg transition-all opacity-0 invisible">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- Slideshow Script -->
  <script>
    // Background slideshow
    const slides = document.querySelectorAll('.bg-slideshow img');
    let index = 0;
    
    setInterval(() => {
      slides.forEach((img) => img.classList.remove('active'));
      index = (index + 1) % slides.length;
      slides[index].classList.add('active');
    }, 5000);
    
    // Ride chart
    const rideCtx = document.getElementById('rideChart').getContext('2d');
    const rideChart = new Chart(rideCtx, {
      type: 'doughnut',
      data: {
        labels: ['Standard', 'Premium', 'Group'],
        datasets: [{
          data: [45, 30, 25],
          backgroundColor: [
            'rgba(16, 185, 129, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(124, 58, 237, 0.8)'
          ],
          borderColor: [
            'rgba(16, 185, 129, 1)',
            'rgba(59, 130, 246, 1)',
            'rgba(124, 58, 237, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              color: '#e2e8f0',
              font: {
                family: "'Poppins', sans-serif"
              }
            }
          },
          title: {
            display: true,
            text: 'Your Ride Preferences',
            color: '#f8fafc',
            font: {
              family: "'Poppins', sans-serif",
              size: 16,
              weight: 'bold'
            }
          }
        },
        cutout: '70%'
      }
    });
    
    // Back to top button
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        backToTopBtn.classList.remove('opacity-0', 'invisible');
        backToTopBtn.classList.add('opacity-100', 'visible');
      } else {
        backToTopBtn.classList.remove('opacity-100', 'visible');
        backToTopBtn.classList.add('opacity-0', 'invisible');
      }
    });
    
    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
    
    // Animate elements on scroll
    document.addEventListener('DOMContentLoaded', () => {
      anime({
        targets: '.card-hover',
        translateY: [20, 0],
        opacity: [0, 1],
        delay: anime.stagger(100),
        easing: 'easeOutExpo'
      });
      
      anime({
        targets: 'nav',
        translateY: [-50, 0],
        opacity: [0, 1],
        duration: 800,
        easing: 'easeOutExpo'
      });
      
      anime({
        targets: 'section',
        translateY: [30, 0],
        opacity: [0, 1],
        delay: anime.stagger(100),
        duration: 800,
        easing: 'easeOutExpo'
      });
    });
    
    // Form validation
    const reservationForm = document.getElementById('reservationForm');
    
    reservationForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const date = document.getElementById('reservation-date').value;
      const time = document.getElementById('reservation-time').value;
      const party = document.getElementById('reservation-party').value;
      
      if (!date || !time || !party) {
        alert('Please fill in all required fields');
        return;
      }
      
      // Simulate form submission
      const submitBtn = reservationForm.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
      
      setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Check Availability';
        
        // Show success message
        const successMsg = document.createElement('div');
        successMsg.className = 'bg-green-500 text-white p-4 rounded-lg mt-4';
        successMsg.innerHTML = `
          <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>Table available! Redirecting to booking...</span>
          </div>
        `;
        reservationForm.appendChild(successMsg);
        
        // Redirect after delay
        setTimeout(() => {
          window.location.href = 'reservation-confirm.php';
        }, 1500);
      }, 2000);
    });
  </script>
</body>
</html>
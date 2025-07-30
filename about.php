<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us - Sip & Sit</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <script src="https://unpkg.com/scrollreveal"></script>
  <style>
    :root {
      --primary: #f59e0b;
      --primary-dark: #d97706;
      --secondary: #1f2937;
      --accent: #10b981;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #0a0a0a;
      color: #f3f4f6;
      overflow-x: hidden;
    }
    
    h1, h2, h3, h4 {
      font-family: 'Playfair Display', serif;
    }
    
    /* Glass morphism effect */
    .glass {
      background: rgba(15, 23, 42, 0.7);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #0f172a;
    }
    ::-webkit-scrollbar-thumb {
      background: var(--primary);
      border-radius: 4px;
    }
    
    /* Floating animation */
    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-20px);
      }
    }
    
    .floating {
      animation: float 6s ease-in-out infinite;
    }
    
    /* Gradient border */
    .gradient-border {
      position: relative;
    }
    
    .gradient-border::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      padding: 2px;
      background: linear-gradient(45deg, var(--primary), var(--accent));
      -webkit-mask: 
        linear-gradient(#fff 0 0) content-box, 
        linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      pointer-events: none;
    }
    
    /* Section heading underline */
    .section-heading {
      position: relative;
      display: inline-block;
    }
    
    .section-heading::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 50%;
      height: 3px;
      background: var(--primary);
      border-radius: 3px;
    }
    
    /* Feature card */
    .feature-card {
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      border-color: rgba(245, 158, 11, 0.3);
    }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-950">

  <!-- Loading animation -->
  <div id="loading" class="fixed inset-0 bg-gray-950 z-50 flex items-center justify-center transition-opacity duration-500">
    <div class="text-center">
      <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_3rwasyjy.json" background="transparent" speed="1" style="width: 120px; height: 120px;" loop autoplay></lottie-player>
      <p class="text-yellow-400 mt-4 text-lg font-medium">Loading...</p>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="fixed w-full top-0 z-30 glass py-3 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex-shrink-0 flex items-center">
          <a href="index.php" class="flex items-center space-x-3">
            <div class="gradient-border rounded-full p-1">
              <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-full object-cover" />
            </div>
            <span class="text-2xl font-bold text-yellow-400 font-serif">Sip & Sit</span>
          </a>
        </div>
        
        <div class="hidden md:flex items-center space-x-8">
          <a href="index.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
            Home
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
          <a href="about.php" class="text-yellow-400 transition-colors duration-300 relative group">
            About
            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-yellow-400"></span>
          </a>
          <a href="contact.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
            Contact
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
          </a>
        </div>  
        
        <div class="md:hidden flex items-center">
          <button id="mobileMenuButton" class="text-gray-300 hover:text-yellow-400 focus:outline-none">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobileMenu" class="md:hidden glass absolute top-full left-0 right-0 shadow-xl transition-all duration-300 transform origin-top scale-y-0 h-0 overflow-hidden">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-gray-800/50 transition">Home</a>
        <a href="about.php" class="block px-3 py-2 rounded-md text-base font-medium text-yellow-400 hover:bg-gray-800/50 transition">About</a>
        <a href="contact.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-gray-800/50 transition">Contact</a>
      </div>
    </div>
  </nav>

  <!-- About Us Section -->
  <main class="min-h-screen pt-20 pb-10">
    <section class="max-w-6xl mx-auto px-4 py-12">
      <div class="text-center mb-16">
        <div class="w-32 h-32 mx-auto mb-6 floating">
          <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_1pxqjqps.json" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h1 class="text-4xl font-bold text-yellow-400 mb-4 section-heading">About Sip & Sit</h1>
        <p class="text-gray-400 max-w-3xl mx-auto leading-relaxed text-lg">
          Sip & Sit is more than just an online liquor store — it's your personal gateway to a world of premium drinks and comfortable vibes. Whether you're a connoisseur or just starting to explore, we've got the perfect sip for you.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <div class="glass p-8 rounded-2xl feature-card">
          <div class="text-yellow-400 text-4xl mb-4">
            <i class="fas fa-bullseye"></i>
          </div>
          <h2 class="text-2xl font-semibold text-yellow-300 mb-3">Our Mission</h2>
          <p class="text-gray-400 leading-relaxed">To bring you quality drinks with exceptional service, helping you make every gathering special. We source only the finest spirits from around the world to elevate your drinking experience.</p>
        </div>
        <div class="glass p-8 rounded-2xl feature-card">
          <div class="text-yellow-400 text-4xl mb-4">
            <i class="fas fa-star"></i>
          </div>
          <h2 class="text-2xl font-semibold text-yellow-300 mb-3">Why Choose Us?</h2>
          <p class="text-gray-400 leading-relaxed">Wide variety of liquor, curated selections, easy online ordering, and fast delivery across the region. Our experts handpick each product to ensure premium quality.</p>
        </div>
      </div>

      <div class="glass p-8 rounded-2xl mb-16">
        <h2 class="text-3xl font-bold text-yellow-400 mb-8 text-center section-heading">Our Story</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
          <div>
            <p class="text-gray-400 mb-6 leading-relaxed text-lg">
              Founded in 2020, Sip & Sit began as a small boutique liquor store with a passion for quality spirits. What started as a local business quickly grew into Sri Lanka's premier online liquor destination.
            </p>
            <p class="text-gray-400 leading-relaxed text-lg">
              Our team of sommeliers and spirits experts travel the globe to discover exceptional products that we're proud to share with our customers. We believe every drink tells a story, and we're here to help you find yours.
            </p>
          </div>
          <div class="rounded-lg overflow-hidden gradient-border">
            <img src="https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Our store" class="w-full h-auto rounded-lg object-cover">
          </div>
        </div>
      </div>

      <h2 class="text-3xl font-bold text-yellow-400 mb-12 text-center section-heading">Our Values</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div class="glass p-8 rounded-xl feature-card">
          <div class="text-yellow-400 text-3xl mb-4">
            <i class="fas fa-medal"></i>
          </div>
          <h3 class="text-xl font-semibold text-white mb-3">Quality</h3>
          <p class="text-gray-400">We source only the finest spirits from reputable distilleries and wineries worldwide.</p>
        </div>
        <div class="glass p-8 rounded-xl feature-card">
          <div class="text-yellow-400 text-3xl mb-4">
            <i class="fas fa-hand-holding-heart"></i>
          </div>
          <h3 class="text-xl font-semibold text-white mb-3">Integrity</h3>
          <p class="text-gray-400">Honest recommendations and transparent pricing with no hidden costs.</p>
        </div>
        <div class="glass p-8 rounded-xl feature-card">
          <div class="text-yellow-400 text-3xl mb-4">
            <i class="fas fa-shipping-fast"></i>
          </div>
          <h3 class="text-xl font-semibold text-white mb-3">Service</h3>
          <p class="text-gray-400">Fast, reliable delivery with careful packaging to ensure your order arrives perfectly.</p>
        </div>
      </div>

      <div class="glass p-8 rounded-2xl">
        <h2 class="text-3xl font-bold text-yellow-400 mb-8 text-center section-heading">Our Team</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden gradient-border p-1">
              <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="Team member" class="w-full h-full object-cover rounded-full">
            </div>
            <h3 class="text-xl font-semibold text-white mb-1">Sarah Johnson</h3>
            <p class="text-yellow-400 mb-2">Head Sommelier</p>
            <p class="text-gray-400 text-sm">With 15 years in the industry, Sarah curates our wine selection.</p>
          </div>
          <div class="text-center">
            <div class="w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden gradient-border p-1">
              <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Team member" class="w-full h-full object-cover rounded-full">
            </div>
            <h3 class="text-xl font-semibold text-white mb-1">David Chen</h3>
            <p class="text-yellow-400 mb-2">Spirits Expert</p>
            <p class="text-gray-400 text-sm">David travels worldwide to discover exceptional spirits for our collection.</p>
          </div>
          <div class="text-center">
            <div class="w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden gradient-border p-1">
              <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Team member" class="w-full h-full object-cover rounded-full">
            </div>
            <h3 class="text-xl font-semibold text-white mb-1">Maria Rodriguez</h3>
            <p class="text-yellow-400 mb-2">Customer Experience</p>
            <p class="text-gray-400 text-sm">Maria ensures every customer receives exceptional service.</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 py-12 px-4">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
      <div>
        <h3 class="text-yellow-400 text-xl font-bold mb-4">Sip & Sit</h3>
        <p class="mb-4">Your premium online liquor store offering the finest selection of spirits from around the world.</p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-yellow-400"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-gray-400 hover:text-yellow-400"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-gray-400 hover:text-yellow-400"><i class="fab fa-twitter"></i></a>
        </div>
      </div>
      <div>
        <h4 class="text-white text-lg font-semibold mb-4">Quick Links</h4>
        <ul class="space-y-2">
          <li><a href="index.php" class="hover:text-yellow-400 transition">Home</a></li>
          <li><a href="about.php" class="hover:text-yellow-400 transition">About Us</a></li>
          <li><a href="contact.php" class="hover:text-yellow-400 transition">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-white text-lg font-semibold mb-4">Customer Service</h4>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-yellow-400 transition">FAQs</a></li>
          <li><a href="#" class="hover:text-yellow-400 transition">Shipping Policy</a></li>
          <li><a href="#" class="hover:text-yellow-400 transition">Privacy Policy</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-white text-lg font-semibold mb-4">Contact Us</h4>
        <address class="not-italic">
          <p class="mb-2"><i class="fas fa-map-marker-alt mr-2 text-yellow-400"></i> 123 Spirits Lane, Colombo, Sri Lanka</p>
          <p class="mb-2"><i class="fas fa-phone-alt mr-2 text-yellow-400"></i> +94 76 123 4567</p>
          <p class="mb-2"><i class="fas fa-envelope mr-2 text-yellow-400"></i> info@sipandsit.lk</p>
        </address>
      </div>
    </div>
    <div class="max-w-6xl mx-auto pt-8 mt-8 border-t border-gray-800 text-center">
      <p>&copy; <?php echo date('Y'); ?> Sip & Sit. All rights reserved.</p>
    </div>
  </footer>

  <script>
    // Loading animation
    window.addEventListener('load', function() {
      setTimeout(function() {
        document.getElementById('loading').style.opacity = '0';
        setTimeout(function() {
          document.getElementById('loading').style.display = 'none';
        }, 500);
      }, 1000);
    });

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
      mobileMenu.classList.toggle('scale-y-0');
      mobileMenu.classList.toggle('h-0');
      mobileMenu.classList.toggle('scale-y-100');
      mobileMenu.classList.toggle('h-auto');
      mobileMenu.classList.toggle('py-4');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!mobileMenu.contains(event.target) && event.target !== mobileMenuButton) {
        mobileMenu.classList.add('scale-y-0', 'h-0');
        mobileMenu.classList.remove('scale-y-100', 'h-auto', 'py-4');
      }
    });

    // Scroll reveal animations
    ScrollReveal().reveal('.feature-card', {
      delay: 200,
      distance: '20px',
      origin: 'bottom',
      interval: 100,
      easing: 'cubic-bezier(0.5, 0, 0, 1)',
      reset: true
    });

    ScrollReveal().reveal('section > div', {
      delay: 200,
      distance: '30px',
      origin: 'bottom',
      easing: 'cubic-bezier(0.5, 0, 0, 1)',
      reset: true
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('shadow-lg', 'py-2');
        navbar.classList.remove('py-3');
      } else {
        navbar.classList.remove('shadow-lg', 'py-2');
        navbar.classList.add('py-3');
      }
    });
  </script>
</body>
</html>
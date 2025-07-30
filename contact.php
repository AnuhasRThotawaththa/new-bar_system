<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Contact Us | Sip & Sit – Premium Liquor Experience</title>
  <meta name="description" content="Get in touch with Sip & Sit. Reach out for reservations, inquiries, or feedback about our premium liquor services." />
  <link rel="icon" href="favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
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
    
    /* Hero text animation */
    .hero-text {
      background: linear-gradient(90deg, #f59e0b, #fcd34d, #f59e0b);
      background-size: 200% auto;
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: shine 3s linear infinite;
    }
    
    @keyframes shine {
      to {
        background-position: 200% center;
      }
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
    
    /* Pulse animation */
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }
    
    .pulse:hover {
      animation: pulse 2s infinite;
    }
    
    /* Custom checkbox */
    .custom-checkbox {
      appearance: none;
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid var(--primary);
      border-radius: 4px;
      outline: none;
      cursor: pointer;
      position: relative;
    }
    
    .custom-checkbox:checked {
      background-color: var(--primary);
    }
    
    .custom-checkbox:checked::after {
      content: '✓';
      position: absolute;
      color: white;
      font-size: 14px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    
    /* Marquee effect */
    .marquee {
      animation: marquee 20s linear infinite;
    }
    
    @keyframes marquee {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-50%);
      }
    }
    
    /* Contact form specific styles */
    .contact-input {
      background-color: rgba(31, 41, 55, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }
    
    .contact-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
    }
    
    .contact-textarea {
      min-height: 150px;
    }
    
    .map-container {
      height: 100%;
      min-height: 300px;
      border-radius: 12px;
      overflow: hidden;
    }
  </style>
</head>
<body class="min-h-screen">

<!-- Cursor follower -->
<div id="cursorFollower" class="fixed w-6 h-6 rounded-full bg-yellow-400/30 pointer-events-none z-50 transform -translate-x-1/2 -translate-y-1/2 mix-blend-difference"></div>

<!-- Loading screen -->
<div id="loadingScreen" class="fixed inset-0 bg-gray-950 z-50 flex flex-col items-center justify-center transition-opacity duration-500">
  <div class="w-32 h-32 mb-8">
    <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_3rwasyjy.json" background="transparent" speed="1" loop autoplay></lottie-player>
  </div>
  <p class="text-yellow-400 text-xl font-medium">Sip & Sit</p>
  <div class="w-48 h-1 bg-gray-800 mt-4 rounded-full overflow-hidden">
    <div id="loadingBar" class="h-full bg-yellow-400 w-0"></div>
  </div>
</div>

<!-- Floating action button -->
<div class="fixed right-6 bottom-6 z-40 flex flex-col gap-3">
  <button id="backToTop" class="w-12 h-12 rounded-full bg-yellow-400 text-gray-900 flex items-center justify-center shadow-lg hover:bg-yellow-300 transition-all transform hover:scale-110 hidden">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
  </button>
  
  <button id="chatButton" class="w-12 h-12 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg hover:bg-green-400 transition-all transform hover:scale-110 pulse">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
  </button>
</div>

<!-- Chat widget -->
<div id="chatWidget" class="fixed right-6 bottom-24 w-80 bg-gray-800 rounded-t-xl shadow-xl z-40 hidden transform origin-bottom-right transition-all duration-300 scale-0 opacity-0">
  <div class="bg-gray-900 px-4 py-3 rounded-t-xl flex justify-between items-center">
    <h3 class="font-medium text-yellow-400">Live Chat Support</h3>
    <button id="closeChat" class="text-gray-400 hover:text-white">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </button>
  </div>
  <div class="p-4 h-64 overflow-y-auto">
    <div class="chat-message mb-4">
      <div class="flex items-start">
        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-yellow-400 flex items-center justify-center text-gray-900 font-bold">S</div>
        <div class="ml-3">
          <div class="bg-gray-700 rounded-lg py-2 px-3">
            <p class="text-sm">Hello! Welcome to Sip & Sit. How can I help you today?</p>
          </div>
          <p class="text-xs text-gray-400 mt-1">Just now</p>
        </div>
      </div>
    </div>
  </div>
  <div class="p-3 border-t border-gray-700">
    <div class="flex">
      <input type="text" placeholder="Type your message..." class="flex-1 bg-gray-700 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-400">
      <button class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-r-lg font-medium hover:bg-yellow-300 transition">
        Send
      </button>
    </div>
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
        <a href="about.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
          About Us
          <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="contact.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
          Contact
          <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="login.php" class="px-4 py-2 rounded-full bg-yellow-400 text-gray-900 font-medium hover:bg-yellow-300 transition-colors duration-300 flex items-center space-x-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
          </svg>
          <span>Sign In</span>
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
      <a href="about.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-gray-800/50 transition">About</a>
      <a href="contact.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-gray-800/50 transition">Contact</a>
      <a href="login.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 bg-yellow-400 hover:bg-yellow-300 transition text-center mt-2">Sign In</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 md:pt-40 md:pb-28 bg-gray-950 overflow-hidden">
  <!-- Background pattern -->
  <div class="absolute inset-0 z-0 opacity-10">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1514933651103-005eec06c04b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/90 via-black/70 to-black/90"></div>
  </div>
  
  <div class="relative z-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <h1 class="text-4xl md:text-6xl font-bold mb-6 hero-text">Contact Us</h1>
    <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto">Have questions or feedback? We'd love to hear from you. Reach out to our team anytime.</p>
  </div>
</section>

<!-- Contact Form Section -->
<section class="py-20 bg-gray-950">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <!-- Contact Form -->
      <div class="glass p-8 rounded-2xl">
        <h2 class="text-3xl font-bold mb-8">Send Us a Message</h2>
        
        <form id="contactForm" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="firstName" class="block text-gray-300 mb-2">First Name</label>
              <input type="text" id="firstName" name="firstName" class="w-full contact-input rounded-lg px-4 py-3 text-white focus:outline-none" required>
            </div>
            <div>
              <label for="lastName" class="block text-gray-300 mb-2">Last Name</label>
              <input type="text" id="lastName" name="lastName" class="w-full contact-input rounded-lg px-4 py-3 text-white focus:outline-none" required>
            </div>
          </div>
          
          <div>
            <label for="email" class="block text-gray-300 mb-2">Email Address</label>
            <input type="email" id="email" name="email" class="w-full contact-input rounded-lg px-4 py-3 text-white focus:outline-none" required>
          </div>
          
          <div>
            <label for="phone" class="block text-gray-300 mb-2">Phone Number (Optional)</label>
            <input type="tel" id="phone" name="phone" class="w-full contact-input rounded-lg px-4 py-3 text-white focus:outline-none">
          </div>
          
          <div>
            <label for="subject" class="block text-gray-300 mb-2">Subject</label>
            <select id="subject" name="subject" class="w-full contact-input rounded-lg px-4 py-3 text-white focus:outline-none" required>
              <option value="" disabled selected>Select a subject</option>
              <option value="reservation">Reservation Inquiry</option>
              <option value="delivery">Delivery Question</option>
              <option value="product">Product Question</option>
              <option value="feedback">Feedback</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div>
            <label for="message" class="block text-gray-300 mb-2">Your Message</label>
            <textarea id="message" name="message" rows="5" class="w-full contact-input contact-textarea rounded-lg px-4 py-3 text-white focus:outline-none" required></textarea>
          </div>
          
          <div class="flex items-center">
            <input type="checkbox" id="consent" name="consent" class="custom-checkbox mr-3" required>
            <label for="consent" class="text-gray-300 text-sm">I consent to Sip & Sit collecting my details through this form.</label>
          </div>
          
          <button type="submit" class="w-full bg-yellow-400 text-gray-900 py-4 rounded-lg font-bold hover:bg-yellow-300 transition duration-300 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Send Message
          </button>
        </form>
      </div>
      
      <!-- Contact Info -->
      <div class="space-y-8">
        <div class="glass p-8 rounded-2xl">
          <h3 class="text-2xl font-bold mb-6">Contact Information</h3>
          
          <div class="space-y-6">
            <div class="flex items-start">
              <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-400/10 flex items-center justify-center text-yellow-400 mr-4 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div>
                <h4 class="font-medium text-gray-300 mb-1">Our Location</h4>
                <p class="text-gray-400">123 Spirits Lane, Beverage City, BC 12345</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-400/10 flex items-center justify-center text-yellow-400 mr-4 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div>
                <h4 class="font-medium text-gray-300 mb-1">Phone Number</h4>
                <p class="text-gray-400">(123) 456-7890</p>
                <p class="text-gray-400 text-sm mt-1">Monday - Friday, 9am - 9pm EST</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-400/10 flex items-center justify-center text-yellow-400 mr-4 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <h4 class="font-medium text-gray-300 mb-1">Email Address</h4>
                <p class="text-gray-400">hello@sipandsit.com</p>
                <p class="text-gray-400 text-sm mt-1">We typically respond within 24 hours</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Map -->
        <div class="glass p-0 rounded-2xl overflow-hidden">
          <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215573291234!2d-73.987844924164!3d40.74844097138992!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ0JzU0LjQiTiA3M8KwNTknMTQuMiJX!5e0!3m2!1sen!2sus!4v1623861234567!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gradient-to-b from-gray-900 to-gray-950">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <span class="text-yellow-400 font-medium uppercase tracking-wider">Help Center</span>
      <h2 class="text-4xl md:text-5xl font-bold mt-4">Frequently Asked Questions</h2>
    </div>
    
    <div class="space-y-4">
      <!-- FAQ Item 1 -->
      <div class="glass rounded-xl overflow-hidden">
        <button class="faq-toggle w-full flex justify-between items-center p-6 text-left">
          <h3 class="text-lg font-medium text-white">How do I make a reservation at your venue?</h3>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div class="faq-content px-6 pb-6 hidden">
          <p class="text-gray-300">You can make a reservation through our website by visiting the Reservations page, or by calling us directly at (123) 456-7890. For large parties or special events, we recommend making reservations at least 48 hours in advance.</p>
        </div>
      </div>
      
      <!-- FAQ Item 2 -->
      <div class="glass rounded-xl overflow-hidden">
        <button class="faq-toggle w-full flex justify-between items-center p-6 text-left">
          <h3 class="text-lg font-medium text-white">What are your delivery hours?</h3>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div class="faq-content px-6 pb-6 hidden">
          <p class="text-gray-300">Our delivery service operates from 11am to 11pm daily. Last orders for delivery must be placed by 10:30pm to ensure timely delivery. Delivery times may vary based on location and demand.</p>
        </div>
      </div>
      
      <!-- FAQ Item 3 -->
      <div class="glass rounded-xl overflow-hidden">
        <button class="faq-toggle w-full flex justify-between items-center p-6 text-left">
          <h3 class="text-lg font-medium text-white">Can I return or exchange a product?</h3>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div class="faq-content px-6 pb-6 hidden">
          <p class="text-gray-300">Due to the nature of our products, we cannot accept returns or exchanges of alcoholic beverages once they have left our premises. However, if you receive a damaged or incorrect item, please contact us within 24 hours of delivery for assistance.</p>
        </div>
      </div>
      
      <!-- FAQ Item 4 -->
      <div class="glass rounded-xl overflow-hidden">
        <button class="faq-toggle w-full flex justify-between items-center p-6 text-left">
          <h3 class="text-lg font-medium text-white">Do you offer private events or tastings?</h3>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div class="faq-content px-6 pb-6 hidden">
          <p class="text-gray-300">Yes! We host private tastings and events in our exclusive lounge areas. Our sommeliers and spirit experts can curate a personalized experience for your group. Please contact our events team at events@sipandsit.com for more information and availability.</p>
        </div>
      </div>
    </div>
    
    <div class="text-center mt-12">
      <p class="text-gray-400 mb-6">Still have questions? We're here to help.</p>
      <a href="#contactForm" class="inline-flex items-center px-6 py-3 border border-yellow-400 text-yellow-400 rounded-full font-medium hover:bg-yellow-400/10 transition duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        Contact Us
      </a>
    </div>
  </div>
</section>

<!-- Newsletter -->
<section class="py-20 bg-gradient-to-b from-gray-900 to-gray-950">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div class="glass p-8 md:p-12 rounded-2xl">
      <h2 class="text-3xl md:text-4xl font-bold mb-6">Stay Connected</h2>
      <p class="text-gray-300 mb-8 max-w-2xl mx-auto">Subscribe to our newsletter for exclusive offers, new arrivals, and members-only events.</p>
      
      <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
        <input type="email" placeholder="Your email address" class="flex-1 bg-gray-800 border border-gray-700 rounded-full px-6 py-3 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <button type="submit" class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-full font-bold hover:bg-yellow-300 transition duration-300 whitespace-nowrap">
          Subscribe
        </button>
      </form>
      
      <div class="mt-6 flex items-center justify-center space-x-4">
        <div class="flex items-center">
          <input type="checkbox" id="newsletterConsent" class="custom-checkbox mr-2">
          <label for="newsletterConsent" class="text-sm text-gray-400">I agree to receive marketing emails</label>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 pt-16 pb-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
      <div>
        <div class="flex items-center space-x-3 mb-6">
          <div class="gradient-border rounded-full p-1">
            <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-full object-cover" />
          </div>
          <span class="text-2xl font-bold text-yellow-400 font-serif">Sip & Sit</span>
        </div>
        <p class="text-gray-400 mb-6">Premium liquors, craft beers, and fine wines delivered to your doorstep or enjoyed at our exclusive venues.</p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-yellow-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
            </svg>
          </a>
          <a href="#" class="text-gray-400 hover:text-yellow-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z" />
            </svg>
          </a>
          <a href="#" class="text-gray-400 hover:text-yellow-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </a>
        </div>
      </div>
      
      <div>
        <h3 class="text-lg font-semibold text-white mb-6">Quick Links</h3>
        <ul class="space-y-3">
          <li><a href="about.php" class="text-gray-400 hover:text-yellow-400 transition">About Us</a></li>
          <li><a href="menu.php" class="text-gray-400 hover:text-yellow-400 transition">Our Menu</a></li>
          <li><a href="reservations.php" class="text-gray-400 hover:text-yellow-400 transition">Reservations</a></li>
          <li><a href="delivery.php" class="text-gray-400 hover:text-yellow-400 transition">Delivery</a></li>
          <li><a href="events.php" class="text-gray-400 hover:text-yellow-400 transition">Events</a></li>
        </ul>
      </div>
      
      <div>
        <h3 class="text-lg font-semibold text-white mb-6">Legal</h3>
        <ul class="space-y-3">
          <li><a href="privacy.php" class="text-gray-400 hover:text-yellow-400 transition">Privacy Policy</a></li>
          <li><a href="terms.php" class="text-gray-400 hover:text-yellow-400 transition">Terms of Service</a></li>
          <li><a href="shipping.php" class="text-gray-400 hover:text-yellow-400 transition">Shipping Policy</a></li>
          <li><a href="refund.php" class="text-gray-400 hover:text-yellow-400 transition">Refund Policy</a></li>
        </ul>
      </div>
      
      <div>
        <h3 class="text-lg font-semibold text-white mb-6">Contact Us</h3>
        <ul class="space-y-3">
          <li class="flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-3 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-gray-400">123 Spirits Lane, Beverage City, BC 12345</span>
          </li>
          <li class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span class="text-gray-400">(123) 456-7890</span>
          </li>
          <li class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="text-gray-400">hello@sipandsit.com</span>
          </li>
        </ul>
      </div>
    </div>
    
    <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
      <p class="text-gray-500 text-sm mb-4 md:mb-0">© 2023 Sip & Sit. All rights reserved.</p>
      <div class="flex space-x-6">
        <a href="#" class="text-gray-500 hover:text-yellow-400 text-sm transition">Privacy Policy</a>
        <a href="#" class="text-gray-500 hover:text-yellow-400 text-sm transition">Terms of Service</a>
        <a href="#" class="text-gray-500 hover:text-yellow-400 text-sm transition">Sitemap</a>
      </div>
    </div>
  </div>
</footer>

<!-- Age verification modal -->
<div id="ageVerification" class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center px-4 <?php if (!isset($_SESSION['age_verified'])) echo ''; else echo 'hidden'; ?>">
  <div class="bg-gray-900 rounded-xl max-w-md w-full p-8 text-center">
    <div class="w-20 h-20 mx-auto mb-6">
      <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_2cwDXD.json" background="transparent" speed="1" loop autoplay></lottie-player>
    </div>
    <h3 class="text-2xl font-bold text-white mb-4">Age Verification</h3>
    <p class="text-gray-300 mb-6">By entering this site you are confirming that you are of legal drinking age in your country of residence.</p>
    <div class="flex flex-col sm:flex-row gap-4">
      <button id="confirmAge" class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-lg font-bold hover:bg-yellow-300 transition flex-1">
        I am 21 or older
      </button>
      <button id="exitSite" class="border border-gray-700 text-gray-300 px-6 py-3 rounded-lg font-bold hover:bg-gray-800 transition flex-1">
        Exit
      </button>
    </div>
  </div>
</div>

<script>
  // Loading screen animation
  window.addEventListener('load', function() {
    let progress = 0;
    const loadingBar = document.getElementById('loadingBar');
    const loadingScreen = document.getElementById('loadingScreen');
    const interval = setInterval(() => {
      progress += Math.random() * 10;
      if (progress >= 100) {
        progress = 100;
        clearInterval(interval);
        loadingScreen.style.opacity = '0';
        setTimeout(() => {
          loadingScreen.style.display = 'none';
          // Show age verification after loading if not already verified
          <?php if (!isset($_SESSION['age_verified'])): ?>
            document.getElementById('ageVerification').classList.remove('hidden');
          <?php endif; ?>
        }, 500);
      }
      loadingBar.style.width = `${progress}%`;
    }, 100);
  });

  // Age verification
  document.getElementById('confirmAge').addEventListener('click', function() {
    // Set session variable to remember age verification
    fetch('verify_age.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'age_verified=true'
    })
    .then(response => {
      if (response.ok) {
        document.getElementById('ageVerification').classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    })
    .catch(error => console.error('Error:', error));
  });

  document.getElementById('exitSite').addEventListener('click', function() {
    window.location.href = 'https://www.google.com';
  });

  // Mobile menu toggle
  document.getElementById('mobileMenuButton').addEventListener('click', function() {
    const menu = document.getElementById('mobileMenu');
    if (menu.classList.contains('h-0')) {
      menu.classList.remove('h-0', 'scale-y-0');
      menu.classList.add('h-auto', 'scale-y-100', 'py-4'); 
    } else {
      menu.classList.add('h-0', 'scale-y-0');
      menu.classList.remove('h-auto', 'scale-y-100', 'py-4');
    }
  });

  // Back to top button
  window.addEventListener('scroll', function() {
    const backToTop = document.getElementById('backToTop');
    if (window.scrollY > 300) {
      backToTop.classList.remove('hidden');
    } else {
      backToTop.classList.add('hidden');
    }
  });

  document.getElementById('backToTop').addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  // Chat widget toggle
  document.getElementById('chatButton').addEventListener('click', function() {
    const chatWidget = document.getElementById('chatWidget');
    if (chatWidget.classList.contains('hidden')) {
      chatWidget.classList.remove('hidden', 'scale-0', 'opacity-0');
      chatWidget.classList.add('scale-100', 'opacity-100');
    } else {
      chatWidget.classList.add('scale-0', 'opacity-0');
      setTimeout(() => {
        chatWidget.classList.add('hidden');
      }, 300);
    }
  });

  document.getElementById('closeChat').addEventListener('click', function() {
    const chatWidget = document.getElementById('chatWidget');
    chatWidget.classList.add('scale-0', 'opacity-0');
    setTimeout(() => {
      chatWidget.classList.add('hidden');
    }, 300);
  });

  // Cursor follower
  document.addEventListener('mousemove', function(e) {
    const cursor = document.getElementById('cursorFollower');
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';
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

  // FAQ toggle functionality
  document.querySelectorAll('.faq-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const faqItem = button.parentElement;
      const content = button.nextElementSibling;
      const icon = button.querySelector('svg');
      
      // Toggle this item
      content.classList.toggle('hidden');
      icon.classList.toggle('rotate-180');
      
      // Close other open items
      document.querySelectorAll('.faq-content').forEach(item => {
        if (item !== content && !item.classList.contains('hidden')) {
          item.classList.add('hidden');
          item.previousElementSibling.querySelector('svg').classList.remove('rotate-180');
        }
      });
    });
  });

  // Contact form submission
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    // Simple validation
    if (!formData.get('firstName') || !formData.get('email') || !formData.get('message')) {
      alert('Please fill in all required fields.');
      return;
    }
    
    // Here you would typically send the form data to your server
    console.log('Form submitted:', {
      firstName: formData.get('firstName'),
      lastName: formData.get('lastName'),
      email: formData.get('email'),
      phone: formData.get('phone'),
      subject: formData.get('subject'),
      message: formData.get('message')
    });
    
    // Show success message
    alert('Thank you for your message! We will get back to you soon.');
    this.reset();
  });

  // Scroll reveal animations
  ScrollReveal().reveal('[data-animate]', {
    delay: 200,
    distance: '30px',
    origin: 'bottom',
    interval: 100,
    easing: 'cubic-bezier(0.5, 0, 0, 1)',
    reset: true
  });
</script>
</body>
</html>
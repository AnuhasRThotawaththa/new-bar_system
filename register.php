<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - Sip & Sit</title>
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
    
    /* Input focus effect */
    .input-focus:focus {
      box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
    }
    
    /* Button hover effect */
    .btn-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Transition for smooth effects */
    .transition-all {
      transition: all 0.3s ease;
    }
    
    /* Password strength meter */
    .password-strength {
      height: 4px;
      background: #e2e8f0;
      border-radius: 2px;
      margin-top: 8px;
      overflow: hidden;
    }
    
    .password-strength-bar {
      height: 100%;
      width: 0;
      transition: width 0.3s ease, background 0.3s ease;
    }
    
    /* Floating labels */
    .floating-label {
      position: absolute;
      left: 12px;
      top: 12px;
      color: #94a3b8;
      transition: all 0.2s ease;
      pointer-events: none;
    }
    
    .form-input:focus + .floating-label,
    .form-input:not(:placeholder-shown) + .floating-label {
      top: -8px;
      left: 8px;
      font-size: 12px;
      background: #0f172a;
      padding: 0 4px;
      color: var(--primary);
    }
    
    /* Error messages */
    .error-message {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
    }
    
    .error-message.active {
      max-height: 50px;
    }
    
    /* Eye button for password */
    .eye-btn {
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary);
      cursor: pointer;
      padding: 0.25rem;
      border-radius: 0.375rem;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      user-select: none;
    }
    
    .eye-btn:hover {
      background-color: rgba(250, 204, 21, 0.15);
    }
    
    /* Tooltip */
    .tooltip {
      position: absolute;
      right: 0;
      top: 0;
      transform: translateY(-100%);
      background: #1e293b;
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .eye-btn:hover .tooltip {
      opacity: 1;
    }
    
    /* Form container animation */
    .form-container {
      transform: translateY(20px);
      opacity: 0;
      animation: fadeInUp 0.5s ease forwards;
    }
    
    @keyframes fadeInUp {
      to {
        transform: translateY(0);
        opacity: 1;
      }
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
  <nav class="glass p-4 fixed w-full top-0 z-40 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <a href="index.php" class="flex items-center space-x-3">
        <div class="gradient-border rounded-full p-1">
          <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-full object-cover" />
        </div>
        <span class="text-2xl font-bold text-yellow-400 font-serif">Sip & Sit</span>
      </a>
      <div class="hidden md:flex space-x-8">
        <a href="index.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
          Home
          <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="about.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
          About
          <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="contact.php" class="text-gray-300 hover:text-yellow-400 transition-colors duration-300 relative group">
          Contact
          <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
        </a>
      </div>
      <button id="mobileMenuButton" class="md:hidden text-yellow-400 text-3xl">☰</button>
    </div>
  </nav>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="hidden fixed top-16 right-0 w-56 glass text-gray-200 shadow-lg p-4 rounded-l-xl z-50">
    <a href="index.php" class="block py-2 hover:text-yellow-400">Home</a>
    <a href="about.php" class="block py-2 hover:text-yellow-400">About</a>
    <a href="contact.php" class="block py-2 hover:text-yellow-400">Contact</a>
  </div>

  <!-- Main Content -->
  <main class="min-h-screen flex items-center justify-center px-4 pt-20 pb-10">
    <div class="w-full max-w-md glass p-8 rounded-2xl shadow-xl transform transition-all duration-500 hover:shadow-2xl form-container">
      <div class="text-center mb-8">
        <div class="w-24 h-24 mx-auto mb-4 floating">
          <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_2cwDXD.json" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h2 class="text-3xl font-bold text-yellow-400 mb-2">Create Account</h2>
        <p class="text-gray-400">Join our premium experience</p>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-500/90 text-white p-3 rounded-lg mb-6 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <form id="registerForm" action="process_register.php" method="POST" class="space-y-6" novalidate>
        <div class="grid grid-cols-2 gap-4">
          <div class="relative">
            <input type="text" name="first_name" id="first_name" required 
                   class="w-full p-3 rounded-lg bg-gray-800/50 text-white form-input border border-gray-700 focus:border-yellow-400 input-focus" 
                   placeholder=" " />
            <label for="first_name" class="floating-label">First Name</label>
            <div class="error-message text-red-400 text-xs mt-1" id="first_name_error"></div>
          </div>
          
          <div class="relative">
            <input type="text" name="last_name" id="last_name" required 
                   class="w-full p-3 rounded-lg bg-gray-800/50 text-white form-input border border-gray-700 focus:border-yellow-400 input-focus" 
                   placeholder=" " />
            <label for="last_name" class="floating-label">Last Name</label>
            <div class="error-message text-red-400 text-xs mt-1" id="last_name_error"></div>
          </div>
        </div>

        <div class="relative">
          <input type="email" name="email" id="email" required 
                 class="w-full p-3 rounded-lg bg-gray-800/50 text-white form-input border border-gray-700 focus:border-yellow-400 input-focus" 
                 placeholder=" " />
          <label for="email" class="floating-label">Email</label>
          <div class="error-message text-red-400 text-xs mt-1" id="email_error"></div>
        </div>

        <!-- Hidden role (default to customer) -->
        <input type="hidden" name="role" value="customer">

        <div class="relative input-wrapper">
          <input type="password" name="password" id="password" required 
                 class="w-full p-3 rounded-lg bg-gray-800/50 text-white form-input border border-gray-700 focus:border-yellow-400 input-focus" 
                 placeholder=" " 
                 oninput="checkPasswordStrength(this.value)" />
          <label for="password" class="floating-label">Password</label>
          <div class="eye-btn group" onclick="togglePasswordVisibility('password', this)" 
               title="Show/Hide Password" role="button" tabindex="0" 
               onkeypress="if(event.key === 'Enter') togglePasswordVisibility('password', this);">
            <i class="far fa-eye"></i>
            <span class="tooltip">Show Password</span>
          </div>
          <div class="password-strength mt-2">
            <div class="password-strength-bar" id="password-strength-bar"></div>
          </div>
          <div class="error-message text-red-400 text-xs mt-1" id="password_error"></div>
          <div class="text-xs text-gray-400 mt-1">
            <p>Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.</p>
          </div>
        </div>

        <div class="relative input-wrapper">
          <input type="password" name="confirm_password" id="confirm_password" required 
                 class="w-full p-3 rounded-lg bg-gray-800/50 text-white form-input border border-gray-700 focus:border-yellow-400 input-focus" 
                 placeholder=" " />
          <label for="confirm_password" class="floating-label">Confirm Password</label>
          <div class="eye-btn group" onclick="togglePasswordVisibility('confirm_password', this)" 
               title="Show/Hide Password" role="button" tabindex="0" 
               onkeypress="if(event.key === 'Enter') togglePasswordVisibility('confirm_password', this);">
            <i class="far fa-eye"></i>
            <span class="tooltip">Show Password</span>
          </div>
          <div class="error-message text-red-400 text-xs mt-1" id="confirm_password_error"></div>
        </div>

        <div class="flex items-center">
          <input id="terms" name="terms" type="checkbox" required 
                 class="h-4 w-4 text-yellow-400 focus:ring-yellow-400 border-gray-600 rounded bg-gray-700">
          <label for="terms" class="ml-2 block text-sm text-gray-300">
            I agree to the <a href="#" class="font-medium text-yellow-400 hover:text-yellow-300">Terms of Service</a> and <a href="#" class="font-medium text-yellow-400 hover:text-yellow-300">Privacy Policy</a>
          </label>
        </div>
        <div class="error-message text-red-400 text-xs mt-1" id="terms_error"></div>

        <button type="submit"
          class="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-bold py-3 px-4 rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 btn-hover shadow-lg">
          Register
        </button>
      </form>

      <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-700"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-gray-900 text-gray-400">Or continue with</span>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
          <a href="#"
            class="w-full inline-flex justify-center py-2 px-4 border border-gray-700 rounded-lg shadow-sm bg-gray-800 text-sm font-medium text-gray-300 hover:bg-gray-700 transition-all">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
            </svg>
          </a>
          <a href="#"
            class="w-full inline-flex justify-center py-2 px-4 border border-gray-700 rounded-lg shadow-sm bg-gray-800 text-sm font-medium text-gray-300 hover:bg-gray-700 transition-all">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm4.39 14.622a5.575 5.575 0 01-4.39 2.378 5.575 5.575 0 01-4.39-2.378 5.575 5.575 0 01-1.01-3.19v-.044a5.575 5.575 0 011.01-3.19A5.575 5.575 0 0110 5.622a5.575 5.575 0 014.39 2.378 5.575 5.575 0 011.01 3.19v.044a5.575 5.575 0 01-1.01 3.19z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>
      </div>

      <p class="mt-8 text-center text-sm text-gray-400">
        Already have an account?{' '}
        <a href="login.php" class="font-medium text-yellow-400 hover:text-yellow-300 transition-colors">Sign in here</a>
      </p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="glass py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center space-x-3 mb-4 md:mb-0">
          <div class="gradient-border rounded-full p-1">
            <img src="logo.jpg" alt="Sip & Sit Logo" class="h-8 w-8 rounded-full object-cover" />
          </div>
          <span class="text-xl font-bold text-yellow-400 font-serif">Sip & Sit</span>
        </div>
        <div class="flex space-x-6">
          <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
            </svg>
          </a>
          <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123.06s3.057-.012 4.123-.06c1.064-.049 1.791-.218 2.427-.465a4.902 4.902 0 011.772-1.153 4.902 4.902 0 011.153-1.772c.247-.636.416-1.363.465-2.427.047-1.024.06-1.379.06-4.123 0-2.744-.013-3.099-.06-4.123-.049-1.064-.218-1.791-.465-2.427a4.902 4.902 0 00-1.153-1.772 4.902 4.902 0 00-1.772-1.153c-.636-.247-1.363-.416-2.427-.465C15.378 2.013 15.023 2 12.315 2zm0 2a10.13 10.13 0 00-1.5.074 2.787 2.787 0 00-1.326 1.326A10.13 10.13 0 008 12.315c0 1.46.074 2.527.074 3.189a2.787 2.787 0 001.326 1.326A10.13 10.13 0 0012.315 22c1.46 0 2.527-.074 3.189-.074a2.787 2.787 0 001.326-1.326A10.13 10.13 0 0022 12.315c0-1.46-.074-2.527-.074-3.189a2.787 2.787 0 00-1.326-1.326A10.13 10.13 0 0012.315 4z" clip-rule="evenodd" />
            </svg>
          </a>
          <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
            </svg>
          </a>
        </div>
        <p class="mt-4 md:mt-0 text-sm text-gray-400 text-center md:text-right">
          &copy; 2025 Sip & Sit. All rights reserved.<br>
          Crafted with passion. Drink responsibly.
        </p>
      </div>
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
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // Scroll reveal animations
    ScrollReveal().reveal('main > div', {
      delay: 200,
      distance: '30px',
      origin: 'bottom',
      easing: 'cubic-bezier(0.5, 0, 0, 1)',
      reset: true
    });

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
      initFormValidation();
      
      // Add floating label functionality
      const inputs = document.querySelectorAll('.form-input');
      inputs.forEach(input => {
        if (input.value) {
          input.nextElementSibling.classList.add('floating-label-active');
        }
        
        input.addEventListener('focus', function() {
          this.nextElementSibling.classList.add('floating-label-active');
        });
        
        input.addEventListener('blur', function() {
          if (!this.value) {
            this.nextElementSibling.classList.remove('floating-label-active');
          }
        });
      });
    });

    function initFormValidation() {
      const form = document.getElementById('registerForm');
      
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        
        let isValid = true;
        
        // Validate first name
        const firstName = document.getElementById('first_name').value.trim();
        if (!firstName) {
          showError('first_name', 'First name is required');
          isValid = false;
        } else if (firstName.length < 2) {
          showError('first_name', 'First name must be at least 2 characters');
          isValid = false;
        }
        
        // Validate last name
        const lastName = document.getElementById('last_name').value.trim();
        if (!lastName) {
          showError('last_name', 'Last name is required');
          isValid = false;
        } else if (lastName.length < 2) {
          showError('last_name', 'Last name must be at least 2 characters');
          isValid = false;
        }
        
        // Validate email
        const email = document.getElementById('email').value.trim();
        if (!email) {
          showError('email', 'Email is required');
          isValid = false;
        } else if (!isValidEmail(email)) {
          showError('email', 'Please enter a valid email address');
          isValid = false;
        }
        
        // Validate password
        const password = document.getElementById('password').value;
        if (!password) {
          showError('password', 'Password is required');
          isValid = false;
        } else if (password.length < 8) {
          showError('password', 'Password must be at least 8 characters');
          isValid = false;
        } else if (!isStrongPassword(password)) {
          showError('password', 'Password must include uppercase, lowercase, number, and special character');
          isValid = false;
        }
        
        // Validate confirm password
        const confirmPassword = document.getElementById('confirm_password').value;
        if (!confirmPassword) {
          showError('confirm_password', 'Please confirm your password');
          isValid = false;
        } else if (password !== confirmPassword) {
          showError('confirm_password', 'Passwords do not match');
          isValid = false;
        }
        
        // Validate terms checkbox
        const terms = document.getElementById('terms');
        if (!terms.checked) {
          showError('terms', 'You must accept the terms and conditions');
          isValid = false;
        }
        
        if (isValid) {
          this.submit();
        }
      });
    }
    
    function showError(fieldId, message) {
      const errorElement = document.getElementById(`${fieldId}_error`);
      errorElement.textContent = message;
      errorElement.classList.add('active');
      
      const input = document.getElementById(fieldId);
      input.classList.add('border', 'border-red-400');
      input.addEventListener('input', function() {
        this.classList.remove('border', 'border-red-400');
        errorElement.classList.remove('active');
      }, { once: true });
    }
    
    function clearErrors() {
      document.querySelectorAll('.error-message').forEach(el => {
        el.textContent = '';
        el.classList.remove('active');
      });
      
      document.querySelectorAll('.form-input').forEach(input => {
        input.classList.remove('border', 'border-red-400');
      });
    }
    
    function isValidEmail(email) {
      const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      return re.test(String(email).toLowerCase());
    }
    
    function isStrongPassword(password) {
      const hasUpperCase = /[A-Z]/.test(password);
      const hasLowerCase = /[a-z]/.test(password);
      const hasNumbers = /\d/.test(password);
      const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
      return hasUpperCase && hasLowerCase && hasNumbers && hasSpecialChars;
    }
    
    function checkPasswordStrength(password) {
      const strengthBar = document.getElementById('password-strength-bar');
      let strength = 0;
      
      if (password.length >= 8) strength += 1;
      if (/[A-Z]/.test(password)) strength += 1;
      if (/[a-z]/.test(password)) strength += 1;
      if (/\d/.test(password)) strength += 1;
      if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;
      
      const percentage = (strength / 5) * 100;
      strengthBar.style.width = `${percentage}%`;
      
      if (percentage < 40) {
        strengthBar.style.backgroundColor = '#ef4444';
      } else if (percentage < 70) {
        strengthBar.style.backgroundColor = '#f59e0b';
      } else {
        strengthBar.style.backgroundColor = '#10b981';
      }
    }

    let timeoutId = null;
    let activePasswordField = null;

    function togglePasswordVisibility(inputId, btn) {
      const input = document.getElementById(inputId);
      const icon = btn.querySelector('i');
      
      if (activePasswordField && activePasswordField !== input) {
        activePasswordField.type = 'password';
        const prevBtn = activePasswordField.parentElement.querySelector('.eye-btn');
        prevBtn.querySelector('i').classList.replace('fa-eye-slash', 'fa-eye');
        prevBtn.querySelector('.tooltip').textContent = 'Show Password';
        if (timeoutId) clearTimeout(timeoutId);
      }
      
      if (input.type === "text") {
        input.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        btn.querySelector('.tooltip').textContent = 'Show Password';
        if (timeoutId) clearTimeout(timeoutId);
        timeoutId = null;
        activePasswordField = null;
      } else {
        input.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        btn.querySelector('.tooltip').textContent = 'Hide Password';
        if (timeoutId) clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
          input.type = "password";
          icon.classList.replace('fa-eye-slash', 'fa-eye');
          btn.querySelector('.tooltip').textContent = 'Show Password';
          timeoutId = null;
          activePasswordField = null;
        }, 10000);
        activePasswordField = input;
      }
    }
  </script>
</body>
</html>
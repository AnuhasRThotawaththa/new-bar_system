<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_first_name = $_SESSION["first_name"] ?? 'Customer';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Wines - Sip & Sit</title>
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
      background-color: rgba(0, 0, 0, 0.6);
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

    .scale-bounce {
      transform: scale(1.05);
      transition: transform 0.2s ease-in-out;
    }
  </style>
</head>

<body class="bg-black/80 text-white relative">

  <!-- Background Slideshow -->
  <div class="bg-slideshow">
    <img src="1.jpg" class="active" />
    <img src="wine_bg2.jpg" />
    <img src="wine_bg3.jpg" />
    <img src="wine_bg4.jpg" />
    <img src="wine_bg5.jpg" />
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
        <span class="border border-yellow-300 text-yellow-300 px-3 py-1 rounded-md text-sm">Hi, <?= htmlspecialchars($user_first_name) ?>!</span>
        <a href="logout.php" class="border border-red-400 text-red-400 px-3 py-1 rounded-md text-sm hover:bg-red-400 hover:text-gray-900 transition">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Wines Section -->
  <section class="py-12 text-center max-w-6xl mx-auto px-4 relative z-10">
    <h1 class="text-3xl font-bold text-yellow-400 mb-6">Our Wine Collection 🍷</h1>
    <p class="text-gray-300 max-w-3xl mx-auto mb-10 text-sm">Explore our curated collection of fine wines — reds, whites, and sparkling favourites.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-10">
      <?php
      $wines = [
        ['name' => 'Cabernet Sauvignon', 'desc' => 'Bold, full-bodied with dark fruit flavors.', 'price' => 4500, 'img' => 'wine1.jpg'],
        ['name' => 'Classic Chardonnay', 'desc' => 'Smooth, buttery with hints of vanilla.', 'price' => 4000, 'img' => 'wine2.webp'],
        ['name' => 'Merlot Red Wine Italian', 'desc' => 'Soft, ripe and juicy with plum notes.', 'price' => 4300, 'img' => 'wine3.png'],
        ['name' => 'Pinot Noir', 'desc' => 'Light-bodied with cherry and spice.', 'price' => 4800, 'img' => 'wine4.webp'],
        ['name' => 'Sauvignon Blanc Nitida', 'desc' => 'Crisp and refreshing with citrus notes.', 'price' => 3900, 'img' => 'wine5.jpg'],
        ['name' => 'Dracula Cabernet Sauvignon Red Wine', 'desc' => 'Sweet, floral with a touch of acidity.', 'price' => 4100, 'img' => 'wine6.jpg'],
        ['name' => 'El Don Jammy Red', 'desc' => 'Rich, fruity, with peppery finish.', 'price' => 4600, 'img' => 'wine7.webp'],
        ['name' => 'Clarendelle Blanc', 'desc' => 'Deep color with plum and blackberry.', 'price' => 4400, 'img' => 'wine8.webp'],
        ['name' => 'Ramon Bilbao Crianza 2019 Red Wine', 'desc' => 'Sparkling, light and fruity.', 'price' => 5000, 'img' => 'wine9.webp'],
        ['name' => 'Bolla Veronese Rosso 2013 Red Wine', 'desc' => 'Classic sparkling wine with fine bubbles.', 'price' => 7500, 'img' => 'wine10.webp'],
      ];

      function safeId($str) {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($str));
      }

      foreach ($wines as $wine):
        $safeId = safeId($wine['name']);
      ?>
      <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-lg p-5 flex flex-col hover:scale-105 hover:shadow-yellow-400 transition-all duration-300">
        <div class="w-full h-52 mb-4 rounded-lg bg-white flex items-center justify-center shadow-inner">
          <img src="<?= htmlspecialchars($wine['img']) ?>" alt="<?= htmlspecialchars($wine['name']) ?>" class="max-h-48 object-contain" />
        </div>
        <h3 class="text-lg font-semibold text-yellow-300 mb-1"><?= htmlspecialchars($wine['name']) ?></h3>
        <p class="text-gray-300 text-sm mb-2"><?= htmlspecialchars($wine['desc']) ?></p>
        <span class="text-yellow-400 font-bold text-sm mb-3">Rs. <?= number_format($wine['price']) ?></span>
        <div class="flex items-center justify-center gap-2 mb-3">
          <button onclick="decrementQuantity('<?= $safeId ?>')" class="bg-gray-700 px-2 py-1 rounded text-white hover:bg-gray-600">&minus;</button>
          <input type="number" id="qty_<?= $safeId ?>" value="1" min="1" class="w-14 text-center p-1 rounded bg-gray-900 border border-gray-700 text-white" />
          <button onclick="incrementQuantity('<?= $safeId ?>')" class="bg-gray-700 px-2 py-1 rounded text-white hover:bg-gray-600">+</button>
        </div>
        <button id="btn_<?= $safeId ?>" onclick="addToCart('<?= $safeId ?>', '<?= addslashes($wine['name']) ?>', <?= $wine['price'] ?>)" class="bg-yellow-400 text-black font-semibold py-2 rounded hover:bg-yellow-300 transition-all duration-300">Add to Cart</button>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-6">
      <a href="cart.php" class="inline-block bg-yellow-400 text-black font-semibold px-6 py-2 rounded-lg hover:bg-yellow-300 transition text-base shadow-lg">🛒 View Cart</a>
    </div>

    <div class="mt-12 text-center">
      <h2 class="text-xl font-semibold text-yellow-300 mb-3">Want more?</h2>
      <p class="text-gray-400 text-sm mb-4">Don’t miss out on our premium whiskeys, traditional arracks, savoury bites, and more!</p>
      <div class="flex flex-wrap justify-center gap-3">
        <a href="order_whiskey.php" class="bg-amber-700 hover:bg-amber-600 text-white px-4 py-2 rounded shadow transition text-sm">🥃 Order Whiskeys</a>
        <a href="order_beers.php" class="bg-teal-600 hover:bg-teal-500 text-white px-4 py-2 rounded shadow transition text-sm">🍺 Order Beers</a>
        <a href="order_bites.php" class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded shadow transition text-sm">🍢 Order Bites</a>
        <a href="dashboard.php" class="bg-yellow-400 hover:bg-yellow-300 text-black font-semibold px-4 py-2 rounded shadow transition text-sm">🛜 Explore More</a>
      </div>
    </div>
  </section>

  <footer class="bg-gray-950/80 backdrop-blur-sm text-center py-4 mt-10 text-xs text-gray-300 z-10 relative">
    <p>&copy; 2025 Sip & Sit. All rights reserved.</p>
    <p>Crafted with passion. Drink responsibly.</p>
  </footer>

  <script>
    const bgImages = document.querySelectorAll('.bg-slideshow img');
    let index = 0;
    setInterval(() => {
      bgImages.forEach(img => img.classList.remove('active'));
      index = (index + 1) % bgImages.length;
      bgImages[index].classList.add('active');
    }, 3500);

    function incrementQuantity(id) {
      const input = document.getElementById('qty_' + id);
      input.value = parseInt(input.value || 1) + 1;
    }

    function decrementQuantity(id) {
      const input = document.getElementById('qty_' + id);
      if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
      }
    }

    function addToCart(id, productName, price) {
      const qtyInput = document.getElementById('qty_' + id);
      const qty = parseInt(qtyInput.value);
      if (qty < 1 || isNaN(qty)) {
        alert('Please select a valid quantity.');
        return;
      }

      const btn = document.getElementById('btn_' + id);
      btn.classList.add('scale-bounce');
      setTimeout(() => btn.classList.remove('scale-bounce'), 200);

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "add_to_cart.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            try {
              const res = JSON.parse(xhr.responseText);
              if (res.success) {
                btn.textContent = 'Added!';
                btn.classList.remove('bg-yellow-400', 'text-black');
                btn.classList.add('bg-green-500', 'text-white');
                setTimeout(() => {
                  btn.textContent = 'Add to Cart';
                  btn.classList.remove('bg-green-500', 'text-white');
                  btn.classList.add('bg-yellow-400', 'text-black');
                }, 2000);
              } else {
                alert('Failed: ' + (res.message || 'Unknown error.'));
              }
            } catch {
              alert('Invalid response from server.');
            }
          } else {
            alert('Failed to add to cart.');
          }
        }
      };

      const params = `product=${encodeURIComponent(productName)}&price=${price}&qty=${qty}`;
      xhr.send(params);
    }
  </script>
</body>
</html>

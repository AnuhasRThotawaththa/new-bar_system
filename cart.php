<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_name'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

// Handle remove item from cart
if (isset($_GET['remove'])) {
    $removeId = intval($_GET['remove']);
    pg_query_params($conn, "DELETE FROM user_cart WHERE id = $1 AND user_id = $2", [$removeId, $user_id]);
    header("Location: cart.php");
    exit;
}

// Handle clear cart
if (isset($_GET['clear'])) {
    pg_query_params($conn, "DELETE FROM user_cart WHERE user_id = $1", [$user_id]);
    header("Location: cart.php");
    exit;
}

// Fetch cart items for current user
$result = pg_query_params($conn, "SELECT * FROM user_cart WHERE user_id = $1 ORDER BY added_at DESC", [$user_id]);
$cart_items = pg_fetch_all($result);
$total = 0;

// Fetch available tables for dine-in
$tables_result = pg_query($conn, "SELECT * FROM tables WHERE is_available = TRUE ORDER BY table_number ASC");
$available_tables = pg_fetch_all($tables_result);

// Handle order form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_type = $_POST['order_type'] ?? '';
    $table_id = $_POST['table_id'] ?? null;
    $address = $_POST['address'] ?? null;
    $phone = $_POST['phone'] ?? null;

    if ($order_type === 'Dine-in' && $table_id) {
        $table_check = pg_query_params($conn, "SELECT is_available FROM tables WHERE id = $1", [$table_id]);
        $table_data = pg_fetch_assoc($table_check);
        if (!$table_data || $table_data['is_available'] !== 't') {
            echo "<script>alert('Sorry, this table is already booked. Please choose a different table.'); window.location='cart.php';</script>";
            exit;
        }
    }

    if ($cart_items) {
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }

    $insert_order_sql = "
        INSERT INTO order_types (user_id, order_type, table_id, address, phone, created_at)
        VALUES ($1, $2, $3, $4, $5, NOW())
        RETURNING id
    ";
    $insert_order_result = pg_query_params($conn, $insert_order_sql, [$user_id, $order_type, $table_id, $address, $phone]);

    if ($insert_order_result) {
        $order_row = pg_fetch_assoc($insert_order_result);
        $order_type_id = $order_row['id'];

        if ($order_type === 'Dine-in' && $table_id) {
            pg_query_params($conn, "UPDATE tables SET is_available = FALSE WHERE id = $1", [$table_id]);
        }

        if ($order_type === 'Delivery' && $address && $phone) {
            pg_query_params($conn, "
                INSERT INTO delivery_details (user_id, address, phone, order_type_id, created_at)
                VALUES ($1, $2, $3, $4, NOW())
            ", [$user_id, $address, $phone, $order_type_id]);
        }

        // Store order type for post-payment use
        $_SESSION['last_order_type'] = $order_type;

        // Redirect to mock payment
        header("Location: mock_gateway.php?amount=$total");
        exit;
    } else {
        echo "<script>alert('Failed to save order details. Please try again.');</script>";
    }
}
?>

<!-- HTML STARTS HERE -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Cart - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function handleOrderTypeChange(type) {
      document.getElementById('dinein-options').style.display = (type === 'Dine-in') ? 'block' : 'none';
      document.getElementById('delivery-options').style.display = (type === 'Delivery') ? 'block' : 'none';
    }
  </script>
</head>
<body class="bg-gray-950 text-white">
  <nav class="bg-gray-950 p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-b-full border-2 border-yellow-400" />
        <span class="text-2xl font-bold text-yellow-400">Sip & Sit</span>
      </div>
      <div class="flex gap-4 items-center">
        <a href="dashboard.php" class="border border-yellow-300 text-yellow-300 px-3 py-1 rounded-md text-sm hover:bg-yellow-300 hover:text-gray-900 transition">Dashboard</a>
        <span class="border border-yellow-300 text-yellow-300 px-3 py-1 rounded-md text-sm">Hi, <?= htmlspecialchars($_SESSION['first_name']) ?>!</span>
        <a href="logout.php" class="border border-red-400 text-red-400 px-3 py-1 rounded-md text-sm hover:bg-red-400 hover:text-gray-900 transition">Logout</a>
      </div>
    </div>
  </nav>

  <section class="max-w-4xl mx-auto p-6 mt-8 bg-gray-800 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold text-yellow-400 mb-6 text-center">🛒 Your Cart</h1>

    <?php if (!$cart_items): ?>
      <p class="text-center text-gray-400">Your cart is empty.</p>
    <?php else: ?>
      <div class="space-y-4 mb-6">
        <?php foreach ($cart_items as $item): 
          $total += $item['price'] * $item['quantity'];
        ?>
          <div class="flex justify-between items-center bg-gray-900 p-3 rounded-md">
            <div>
              <h3 class="text-lg font-semibold text-yellow-300"><?= htmlspecialchars($item['product_name']) ?></h3>
              <p class="text-gray-400 text-sm">
                Rs. <?= number_format($item['price']) ?> x <?= $item['quantity'] ?> = Rs. <?= number_format($item['price'] * $item['quantity']) ?>
              </p>
            </div>
            <a href="cart.php?remove=<?= $item['id'] ?>" class="text-red-400 text-sm hover:text-red-300">Remove ❌</a>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="text-right mb-4 text-yellow-300 font-semibold text-lg">
        Total: Rs. <?= number_format($total) ?>
      </div>

      <form action="cart.php" method="POST" class="space-y-6">
        <input type="hidden" name="amount" value="<?= $total ?>">

        <div>
          <label class="block mb-1">Select Order Type</label>
          <select name="order_type" onchange="handleOrderTypeChange(this.value)" required class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white">
            <option value="">-- Choose --</option>
            <option value="Dine-in">Dine-in</option>
            <option value="Delivery">Delivery</option>
            <option value="Takeaway">Takeaway</option>
          </select>
        </div>

        <div id="dinein-options" style="display:none;">
          <label class="block mb-1">Choose a Table</label>
          <select name="table_id" class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white">
            <?php if ($available_tables): ?>
              <?php foreach ($available_tables as $table): ?>
                <option value="<?= $table['id'] ?>">Table <?= htmlspecialchars($table['table_number']) ?></option>
              <?php endforeach; ?>
            <?php else: ?>
              <option disabled>No tables available</option>
            <?php endif; ?>
          </select>
        </div>

        <div id="delivery-options" style="display:none;">
          <label class="block mt-4 mb-1">Delivery Address</label>
          <input type="text" name="address" placeholder="123 Main Street" class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white">

          <label class="block mt-4 mb-1">Contact Number</label>
          <input type="text" name="phone" placeholder="07X-XXXXXXX" class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white">
        </div>

        <div class="flex justify-between items-center pt-4">
          <a href="cart.php?clear=1" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-400 transition">Clear Cart</a>
          <button type="submit" class="bg-yellow-400 text-gray-900 px-4 py-2 rounded font-bold hover:bg-yellow-300 transition">
            Proceed to Checkout
          </button>
        </div>
      </form>
    <?php endif; ?>
  </section>

  <footer class="bg-gray-950 text-center py-6 text-gray-50 text-sm mt-10">
    <p>&copy; 2025 Sip & Sit. All rights reserved.</p>
    <p>Crafted with passion. Drink responsibly.</p>
  </footer>
</body>
</html>

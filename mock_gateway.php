<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_name'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];

// Connect to PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

// Fetch total from cart (price * quantity)
$result = pg_query_params($conn, "SELECT COALESCE(SUM(price * quantity), 0) AS total FROM cart WHERE user_id = $1", [$user_id]);
$row = pg_fetch_assoc($result);
$total = $row['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Visa Payment - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white flex items-center justify-center min-h-screen p-4">

  <form action="process_payment.php" method="POST" class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md space-y-6">
    <h2 class="text-2xl font-bold text-yellow-400 text-center">Visa Card Payment</h2>

    <p class="text-center text-gray-300">Hello, <span class="text-yellow-300 font-semibold"><?= htmlspecialchars($first_name) ?></span> 👋</p>
    
    <!-- Hidden total amount -->
    <input type="hidden" name="amount" value="<?= htmlspecialchars($total) ?>">

    <!-- Card Number -->
    <div>
      <label class="block mb-1 text-sm">Card Number</label>
      <input name="card_number" type="text" maxlength="16" pattern="\d{16}" required placeholder="4111111111111111"
        class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white">
    </div>

    <!-- Expiry and CVV -->
    <div class="flex gap-4">
      <div class="flex-1">
        <label class="block mb-1 text-sm">Expiry</label>
        <input name="expiry" type="text" maxlength="5" pattern="\d{2}/\d{2}" required placeholder="MM/YY"
          class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white">
      </div>
      <div class="flex-1">
        <label class="block mb-1 text-sm">CVV</label>
        <input name="cvv" type="password" maxlength="3" pattern="\d{3}" required placeholder="123"
          class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white">
      </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-2 rounded hover:bg-yellow-300 transition">
      Pay 💳
    </button>
  </form>

</body>
</html>

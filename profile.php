<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_name'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

$update_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$first_name || !$last_name || !$email) {
        $update_message = "First name, last name, and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $update_message = "Invalid email format.";
    } else {
        $email_check = pg_query_params($conn, "SELECT id FROM users WHERE email = $1 AND id != $2", [$email, $user_id]);
        if (pg_num_rows($email_check) > 0) {
            $update_message = "This email is already taken by another user.";
        } else {
            if ($password) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET first_name = $1, last_name = $2, email = $3, password_hash = $4 WHERE id = $5";
                $params = [$first_name, $last_name, $email, $hashed_password, $user_id];
            } else {
                $update_query = "UPDATE users SET first_name = $1, last_name = $2, email = $3 WHERE id = $4";
                $params = [$first_name, $last_name, $email, $user_id];
            }

            $result = pg_query_params($conn, $update_query, $params);
            if ($result) {
                $update_message = "Profile updated successfully.";
                $_SESSION['first_name'] = $first_name;
            } else {
                $update_message = "Failed to update profile: " . pg_last_error($conn);
            }
        }
    }
}

$user_result = pg_query_params($conn, "SELECT * FROM users WHERE id = $1", [$user_id]);
$user = pg_fetch_assoc($user_result);

$payment_result = pg_query_params($conn, "SELECT * FROM payments WHERE user_id = $1 ORDER BY payment_date DESC", [$user_id]);
$payments = pg_fetch_all($payment_result);

$order_type_result = pg_query_params($conn, "
    SELECT ot.*, t.table_number 
    FROM order_types ot
    LEFT JOIN tables t ON ot.table_id = t.id
    WHERE ot.user_id = $1
    ORDER BY ot.created_at DESC
", [$user_id]);
$order_types = pg_fetch_all($order_type_result);

$delivery_result = pg_query_params($conn, "
    SELECT dd.*, ot.order_type 
    FROM delivery_details dd
    JOIN order_types ot ON dd.order_type_id = ot.id
    WHERE ot.user_id = $1
    ORDER BY dd.created_at DESC
", [$user_id]);
$deliveries = pg_fetch_all($delivery_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Profile - Sip & Sit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen p-6">

<div class="max-w-4xl mx-auto">
  <h1 class="text-3xl font-bold text-yellow-400 mb-6">👤 My Profile</h1>

  <?php if ($update_message): ?>
    <div class="mb-6 p-4 rounded <?= strpos($update_message, 'successfully') !== false ? 'bg-green-600' : 'bg-red-600' ?>">
      <?= htmlspecialchars($update_message) ?>
    </div>
  <?php endif; ?>

  <div class="bg-gray-800 rounded-lg p-6 mb-8 shadow-lg">
    <h2 class="text-xl font-semibold text-yellow-300 mb-4">✏️ Edit Account Details</h2>
    <form method="POST" action="" class="space-y-4">
      <div>
        <label class="block mb-1" for="first_name">First Name</label>
        <input id="first_name" name="first_name" type="text" required
               class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white"
               value="<?= htmlspecialchars($user['first_name']) ?>">
      </div>
      <div>
        <label class="block mb-1" for="last_name">Last Name</label>
        <input id="last_name" name="last_name" type="text" required
               class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white"
               value="<?= htmlspecialchars($user['last_name']) ?>">
      </div>
      <div>
        <label class="block mb-1" for="email">Email</label>
        <input id="email" name="email" type="email" required
               class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white"
               value="<?= htmlspecialchars($user['email']) ?>">
      </div>
      <div>
        <label class="block mb-1" for="password">Password <span class="text-sm text-gray-400">(Leave blank to keep current password)</span></label>
        <input id="password" name="password" type="password"
               class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white" placeholder="New password">
      </div>
      <button type="submit" class="bg-yellow-400 text-gray-900 px-4 py-2 rounded font-bold hover:bg-yellow-300 transition">
        Update Profile
      </button>
    </form>
  </div>

  <div class="bg-gray-800 rounded-lg p-6 mb-8 shadow-lg">
    <h2 class="text-xl font-semibold text-yellow-300 mb-4">👥 Account Details</h2>
    <p><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']) ?></p>
    <p><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Account Created:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
  </div>

  <div class="bg-gray-800 rounded-lg p-6 mb-8 shadow-lg">
    <h2 class="text-xl font-semibold text-yellow-300 mb-4">💳 Payment History</h2>
    <?php if (!$payments): ?>
      <p class="text-gray-400">No payment records found.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full text-left border border-yellow-400">
          <thead class="bg-gray-900 text-yellow-400">
          <tr>
            <th class="p-2 border border-yellow-400">Order ID</th>
            <th class="p-2 border border-yellow-400">Amount (Rs.)</th>
            <th class="p-2 border border-yellow-400">Card</th>
            <th class="p-2 border border-yellow-400">Date</th>
          </tr>
          </thead>
          <tbody class="text-sm">
          <?php foreach ($payments as $payment): ?>
            <tr class="border-t border-yellow-400 hover:bg-gray-700/50">
              <td class="p-2"><?= htmlspecialchars($payment['order_id']) ?></td>
              <td class="p-2"><?= number_format($payment['amount'], 2) ?></td>
              <td class="p-2">**** <?= htmlspecialchars($payment['card_last4']) ?></td>
              <td class="p-2"><?= date('F j, Y, g:i a', strtotime($payment['payment_date'])) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <div class="bg-gray-800 rounded-lg p-6 mb-8 shadow-lg">
    <h2 class="text-xl font-semibold text-yellow-300 mb-4">📦 Order Details</h2>
    <?php if (!$order_types): ?>
      <p class="text-gray-400">No order details found.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full text-left border border-yellow-400">
          <thead class="bg-gray-900 text-yellow-400">
          <tr>
            <th class="p-2 border border-yellow-400">Order Type</th>
            <th class="p-2 border border-yellow-400">Table No</th>
            <th class="p-2 border border-yellow-400">Ordered At</th>
          </tr>
          </thead>
          <tbody class="text-sm">
          <?php foreach ($order_types as $order): ?>
            <tr class="border-t border-yellow-400 hover:bg-gray-700/50">
              <td class="p-2"><?= htmlspecialchars($order['order_type']) ?></td>
              <td class="p-2"><?= $order['order_type'] === 'Dine-in' && $order['table_number'] ? 'Table ' . htmlspecialchars($order['table_number']) : '-' ?></td>
              <td class="p-2"><?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
    <h2 class="text-xl font-semibold text-yellow-300 mb-4">🚚 Delivery Details</h2>
    <?php if (!$deliveries): ?>
      <p class="text-gray-400">No delivery records found.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full text-left border border-yellow-400">
          <thead class="bg-gray-900 text-yellow-400">
          <tr>
            <th class="p-2 border border-yellow-400">Address</th>
            <th class="p-2 border border-yellow-400">Phone</th>
            <th class="p-2 border border-yellow-400">Status</th>
            <th class="p-2 border border-yellow-400">Created At</th>
          </tr>
          </thead>
          <tbody class="text-sm">
          <?php foreach ($deliveries as $delivery): ?>
            <tr class="border-t border-yellow-400 hover:bg-gray-700/50">
              <td class="p-2"><?= htmlspecialchars($delivery['address']) ?></td>
              <td class="p-2"><?= htmlspecialchars($delivery['phone']) ?></td>
              <td class="p-2">
                <?php 
                  $status = strtolower($delivery['status']);
                  if ($status === 'success' || $status === 'completed') {
                      echo '<span class="text-green-400 font-semibold">Completed</span>';
                  } elseif ($status === 'pending') {
                      echo '<span class="text-yellow-400 font-semibold">Pending</span>';
                  } elseif ($status === 'cancelled') {
                      echo '<span class="text-red-500 font-semibold">Cancelled</span>';
                  } else {
                      echo htmlspecialchars(ucfirst($status));
                  }
                ?>
              </td>
              <td class="p-2"><?= date('F j, Y, g:i a', strtotime($delivery['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>

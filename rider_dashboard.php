<?php
session_start();

if (!isset($_SESSION['rider_id'])) {
    header('Location: login.php');
    exit;
}

$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

$rider_id = $_SESSION['rider_id'];
$rider_name = $_SESSION['rider_name'] ?? 'Rider';

// Messages for feedback
$message = '';
$error = '';

// Handle accept job action
if (isset($_GET['accept_id'])) {
    $accept_id = (int)$_GET['accept_id'];
    $res = pg_query_params($conn, "UPDATE delivery_details SET driver_id = $1, status = 'Ongoing' WHERE id = $2 AND driver_id IS NULL AND status = 'Pending'", [$rider_id, $accept_id]);
    if ($res && pg_affected_rows($res) > 0) {
        $message = "You accepted the delivery job successfully.";
    } else {
        $error = "Failed to accept the job. It might be already taken.";
    }
}

// Handle complete job action
if (isset($_GET['complete_id'])) {
    $complete_id = (int)$_GET['complete_id'];
    $res = pg_query_params($conn, "UPDATE delivery_details SET status = 'Success' WHERE id = $1 AND driver_id = $2 AND status = 'Ongoing'", [$complete_id, $rider_id]);
    if ($res && pg_affected_rows($res) > 0) {
        $message = "You marked the delivery as completed. Great job!";
    } else {
        $error = "Failed to complete the delivery job.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_first_name = trim($_POST['rider_first_name'] ?? '');
    $new_last_name = trim($_POST['rider_last_name'] ?? '');
    $new_email = trim($_POST['rider_email'] ?? '');
    $new_password = $_POST['rider_password'] ?? '';

    if (!$new_first_name || !$new_last_name || !$new_email) {
        $error = "First name, last name, and email are required.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email already taken by others
        $email_check = pg_query_params($conn, "SELECT id FROM riders WHERE email = $1 AND id != $2", [$new_email, $rider_id]);
        if ($email_check && pg_num_rows($email_check) > 0) {
            $error = "This email is already taken by another rider.";
        } else {
            if ($new_password) {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $update = pg_query_params($conn, "UPDATE riders SET first_name = $1, last_name = $2, email = $3, password_hash = $4 WHERE id = $5",
                    [$new_first_name, $new_last_name, $new_email, $hashed, $rider_id]);
            } else {
                $update = pg_query_params($conn, "UPDATE riders SET first_name = $1, last_name = $2, email = $3 WHERE id = $4",
                    [$new_first_name, $new_last_name, $new_email, $rider_id]);
            }
            if ($update) {
                $_SESSION['rider_name'] = $new_first_name;
                $message = "Profile updated successfully.";
                $rider_name = $new_first_name;
            } else {
                $error = "Failed to update profile.";
            }
        }
    }
}

// Fetch updated rider info from riders table
$rider_result = pg_query_params($conn, "SELECT * FROM riders WHERE id = $1", [$rider_id]);
$rider = $rider_result ? pg_fetch_assoc($rider_result) : null;

// Fetch deliveries by status for better UI
$available_result = pg_query($conn, "SELECT dd.*, ot.order_type FROM delivery_details dd LEFT JOIN order_types ot ON dd.order_type_id = ot.id WHERE driver_id IS NULL AND status = 'Pending' ORDER BY created_at ASC");
$available = $available_result ? pg_fetch_all($available_result) : [];

$ongoing_result = pg_query_params($conn, "SELECT dd.*, ot.order_type FROM delivery_details dd LEFT JOIN order_types ot ON dd.order_type_id = ot.id WHERE driver_id = $1 AND status = 'Ongoing' ORDER BY created_at DESC", [$rider_id]);
$ongoing = $ongoing_result ? pg_fetch_all($ongoing_result) : [];

$completed_result = pg_query_params($conn, "SELECT dd.*, ot.order_type FROM delivery_details dd LEFT JOIN order_types ot ON dd.order_type_id = ot.id WHERE driver_id = $1 AND status = 'Success' ORDER BY created_at DESC", [$rider_id]);
$completed = $completed_result ? pg_fetch_all($completed_result) : [];

// Dummy last login - replace with actual logic if needed
$last_login = $_SESSION['last_login'] ?? date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <title>Rider Dashboard - Sip & Sit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-yellow-400 text-gray-900 p-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center space-x-3">
            <img src="logo.jpg" alt="Sip & Sit Logo" class="h-10 w-10 rounded-full border-2 border-gray-900" />
            <span class="font-extrabold text-lg">Sip & Sit - Rider Dashboard</span>
        </div>
        <div class="flex items-center space-x-6">
            <span class="font-semibold">Hello, <?= htmlspecialchars($rider_name) ?></span>
            <a href="?logout=1" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm font-semibold transition">Logout</a>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Profile Section -->
        <section class="bg-gray-800 rounded-lg p-6 shadow-lg col-span-1 md:col-span-1">
            <h2 class="text-xl font-semibold text-yellow-400 mb-4">👤 Your Profile</h2>

            <?php if ($rider): ?>
                <p class="mb-2"><strong>First Name:</strong> <?= htmlspecialchars($rider['first_name']) ?></p>
                <p class="mb-2"><strong>Last Name:</strong> <?= htmlspecialchars($rider['last_name']) ?></p>
                <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($rider['email']) ?></p>
            <?php else: ?>
                <p class="text-red-500">Failed to load profile info.</p>
            <?php endif; ?>

            <p class="mb-4 text-sm text-gray-400">Last login: <?= date('F j, Y, g:i a', strtotime($last_login)) ?></p>

            <form method="POST" action="" class="space-y-4" novalidate>
                <h3 class="font-semibold text-yellow-300">Update Profile</h3>
                <input type="hidden" name="update_profile" value="1" />
                <div>
                    <label for="rider_first_name" class="block mb-1 text-sm">First Name</label>
                    <input id="rider_first_name" name="rider_first_name" type="text" required
                        class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white"
                        value="<?= htmlspecialchars($rider['first_name'] ?? '') ?>">
                </div>
                <div>
                    <label for="rider_last_name" class="block mb-1 text-sm">Last Name</label>
                    <input id="rider_last_name" name="rider_last_name" type="text" required
                        class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white"
                        value="<?= htmlspecialchars($rider['last_name'] ?? '') ?>">
                </div>
                <div>
                    <label for="rider_email" class="block mb-1 text-sm">Email</label>
                    <input id="rider_email" name="rider_email" type="email" required
                        class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white"
                        value="<?= htmlspecialchars($rider['email'] ?? '') ?>">
                </div>
                <div>
                    <label for="rider_password" class="block mb-1 text-sm">New Password <small
                            class="text-gray-400">(Leave blank to keep current)</small></label>
                    <input id="rider_password" name="rider_password" type="password"
                        class="w-full p-2 rounded bg-gray-900 border border-yellow-400 text-white" placeholder="New password">
                </div>
                <button type="submit"
                    class="w-full bg-yellow-400 text-gray-900 font-semibold py-2 rounded hover:bg-yellow-300 transition">Update
                    Profile</button>
            </form>
            <?php if ($error): ?>
                <p class="mt-3 text-red-500 font-semibold"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if ($message): ?>
                <p class="mt-3 text-green-500 font-semibold"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </section>

        <!-- Deliveries Section -->
        <section class="bg-gray-800 rounded-lg p-6 shadow-lg col-span-1 md:col-span-2">
            <?php if ($message && !$error): ?>
                <div
                    class="mb-4 p-3 rounded bg-green-600 text-white font-semibold shadow-md select-none transition duration-500">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div
                    class="mb-4 p-3 rounded bg-red-600 text-white font-semibold shadow-md select-none transition duration-500">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <h2 class="text-xl font-semibold text-yellow-400 mb-4">🚚 Available Deliveries</h2>
            <?php if (!$available): ?>
                <p class="text-gray-400">No deliveries available at the moment.</p>
            <?php else: ?>
                <div class="overflow-x-auto max-h-[280px] md:max-h-[360px] scrollbar-thin scrollbar-thumb-yellow-400 scrollbar-track-gray-700">
                    <table class="w-full border border-yellow-400 text-left">
                        <thead class="bg-gray-900 text-yellow-400 sticky top-0">
                            <tr>
                                <th class="p-2 border border-yellow-400">Address</th>
                                <th class="p-2 border border-yellow-400">Phone</th>
                                <th class="p-2 border border-yellow-400">Order Type</th>
                                <th class="p-2 border border-yellow-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($available as $delivery): ?>
                                <tr class="border-t border-yellow-400 hover:bg-gray-700/70">
                                    <td class="p-2"><?= htmlspecialchars($delivery['address']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($delivery['phone']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($delivery['order_type'] ?? '-') ?></td>
                                    <td class="p-2">
                                        <a href="?accept_id=<?= $delivery['id'] ?>"
                                            onclick="return confirm('Are you sure you want to accept this delivery?');"
                                            class="text-green-400 hover:underline font-semibold">Accept Job</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <hr class="my-6 border-yellow-400/50" />

            <h2 class="text-xl font-semibold text-yellow-400 mb-4">🚛 Ongoing Deliveries</h2>
            <?php if (!$ongoing): ?>
                <p class="text-gray-400">You have no ongoing deliveries.</p>
            <?php else: ?>
                <div class="overflow-x-auto max-h-[280px] md:max-h-[360px] scrollbar-thin scrollbar-thumb-yellow-400 scrollbar-track-gray-700">
                    <table class="w-full border border-yellow-400 text-left">
                        <thead class="bg-gray-900 text-yellow-400 sticky top-0">
                            <tr>
                                <th class="p-2 border border-yellow-400">Address</th>
                                <th class="p-2 border border-yellow-400">Phone</th>
                                <th class="p-2 border border-yellow-400">Order Type</th>
                                <th class="p-2 border border-yellow-400">Status</th>
                                <th class="p-2 border border-yellow-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($ongoing as $delivery): ?>
                                <tr class="border-t border-yellow-400 hover:bg-gray-700/70">
                                    <td class="p-2"><?= htmlspecialchars($delivery['address']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($delivery['phone']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($delivery['order_type'] ?? '-') ?></td>
                                    <td class="p-2">
                                        <span
                                            class="inline-block px-2 py-1 rounded font-semibold text-yellow-900 bg-yellow-400">
                                            <?= htmlspecialchars($delivery['status']) ?>
                                        </span>
                                    </td>
                                    <td class="p-2">
                                        <a href="?complete_id=<?= $delivery['id'] ?>"
                                            onclick="return confirm('Mark this delivery as completed?');"
                                            class="text-blue-400 hover:underline font-semibold">Mark as Completed</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <hr class="my-6 border-yellow-400/50" />

            <h2 class="text-xl font-semibold text-yellow-400 mb-4">✅ Completed Deliveries</h2>
            <?php if (!$completed): ?>
                <p class="text-gray-400">You have no completed deliveries yet.</p>
            <?php else: ?>
                <div class="overflow-x-auto max-h-[280px] md:max-h-[360px] scrollbar-thin scrollbar-thumb-yellow-400 scrollbar-track-gray-700">
                    <table class="w-full border border-yellow-400 text-left">
                        <thead class="bg-gray-900 text-yellow-400 sticky top-0">
                            <tr>
                                <th class="p-2 border border-yellow-400">Address</th>
                                <th class="p-2 border border-yellow-400">Phone</th>
                                <th class="p-2 border border-yellow-400">Order Type</th>
                                <th class="p-2 border border-yellow-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($completed as $delivery): ?>
                                <tr class="border-t border-yellow-400 hover:bg-gray-700/70">
                                    <td class="p-2"><?= htmlspecialchars($delivery['address']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($delivery['phone']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($delivery['order_type'] ?? '-') ?></td>
                                    <td class="p-2">
                                        <span
                                            class="inline-block px-2 py-1 rounded font-semibold text-green-900 bg-green-400">
                                            <?= htmlspecialchars($delivery['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 text-center py-4 mt-auto">
        &copy; <?= date('Y') ?> Sip & Sit. All rights reserved. Drink responsibly.
    </footer>
</body>

</html>

<?php
session_start();

// Connect to PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=bar_db user=postgres password=2002");
if (!$conn) {
    die("Database connection failed.");
}

// Get and sanitize inputs
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = $_POST['role'] ?? 'customer'; // Default to customer for backwards compatibility

// Validate inputs
if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Email and password are required.";
    header("Location: login.php");
    exit;
}

// Query by role
if ($role === 'customer' || $role === 'chef') {
    $result = pg_query_params($conn, "SELECT * FROM users WHERE email = $1", [$email]);
} elseif ($role === 'rider') {
    $result = pg_query_params($conn, "SELECT * FROM riders WHERE email = $1", [$email]);
} else {
    $_SESSION['error'] = "Invalid login type selected.";
    header("Location: login.php");
    exit;
}

if (!$result || pg_num_rows($result) === 0) {
    $_SESSION['error'] = "Invalid email or password.";
    header("Location: login.php");
    exit;
}

$user = pg_fetch_assoc($result);

// Verify password
if ($user && password_verify($password, $user['password_hash'])) {

    // Set session values based on role
    if ($role === 'customer' || $role === 'chef') {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['role'] = strtolower($user['role']);

        // Redirect based on role from DB
        switch (strtolower($user['role'])) {
            case 'chef':
                header("Location: chef_dashboard.php");
                break;
            case 'customer':
                header("Location: dashboard.php");
                break;
            default:
                $_SESSION['error'] = "Unauthorized role found.";
                header("Location: login.php");
                break;
        }
    } elseif ($role === 'rider') {
        $_SESSION['rider_id'] = $user['id'];
        $_SESSION['rider_name'] = $user['first_name'];

        header("Location: rider_dashboard.php");
    }

    exit;
} else {
    $_SESSION['error'] = "Invalid email or password.";
    header("Location: login.php");
    exit;
}
?>

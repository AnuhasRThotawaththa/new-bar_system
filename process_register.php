<?php
session_start();

// PostgreSQL connection parameters
$host = 'localhost';
$port = '5432';
$dbname = 'bar_db';
$user = 'postgres';
$password = '2002';

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    $_SESSION['error'] = "Database connection failed.";
    header('Location: register.php');
    exit;
}

// Validate POST inputs
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? 'customer'; // 👈 Default to 'customer' if not provided

// Basic validation
if (!$first_name || !$last_name || !$email || !$password || !$confirm_password || !$role) {
    $_SESSION['error'] = "Please fill in all required fields.";
    header('Location: register.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email address.";
    header('Location: register.php');
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match.";
    header('Location: register.php');
    exit;
}

// Check if email already exists
$result = pg_query_params($conn, 'SELECT id FROM users WHERE email = $1', [$email]);
if (!$result) {
    $_SESSION['error'] = "Database query error.";
    header('Location: register.php');
    exit;
}
if (pg_num_rows($result) > 0) {
    $_SESSION['error'] = "Email is already registered.";
    header('Location: register.php');
    exit;
}

// Hash the password securely
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user into the database with role
$insert = pg_query_params($conn, 
  'INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES ($1, $2, $3, $4, $5) RETURNING id', 
  [$first_name, $last_name, $email, $password_hash, $role]);


if (!$insert) {
    $_SESSION['error'] = "Failed to register user.";
    header('Location: register.php');
    exit;
}

$new_user = pg_fetch_assoc($insert);
$user_id = $new_user['id'];

// Registration successful, log user in by storing info in session
$_SESSION['user_id'] = $user_id;
$_SESSION['user_email'] = $email;
$_SESSION['user_first_name'] = $first_name;
$_SESSION['user_role'] = $role; // 👈 Store the role
$_SESSION['logged_in'] = true;

// Redirect based on role
if ($role === 'chef') {
    header("Location: chef_dashboard.php");
} else {
    header("Location: login.php");
}
exit;
?>

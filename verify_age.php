<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['age_verified'])) {
    $_SESSION['age_verified'] = true;
    echo 'Age verification stored in session';
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>
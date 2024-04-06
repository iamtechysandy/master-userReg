<?php
session_start(); // Start session
require_once('../include/db_config.php');

// Check if user is logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $newRole = $_POST['role'];

    // Update the user's role in the database
    $sql = "UPDATE users SET role = ? WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $newRole, $email);
    mysqli_stmt_execute($stmt);

    // Check if the role was successfully updated
    if (mysqli_stmt_affected_rows($stmt) == 1) {
        $_SESSION['role_update_success'] = true;
    } else {
        $_SESSION['role_update_error'] = true;
    }

    mysqli_stmt_close($stmt);
}

// Close database connection
mysqli_close($conn);

// Redirect back to home.php
header("Location: users.php");
exit();
?>

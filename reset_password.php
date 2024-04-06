<?php
session_start(); // Start session

include_once 'include/db_config.php';

// Check if token is provided in the URL
if (!isset($_GET['token'])) {
    header("Location: reset_password_request.php");
    exit();
}

$token = $_GET['token'];

// Verify if the token exists in the database and is still valid
$sql = "SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$rowCount = mysqli_stmt_num_rows($stmt);

if ($rowCount != 1) {
    // Token is invalid or expired, display appropriate message
    $invalidTokenMessage = "Reset link has expired or is invalid. Please request a new one.";
} else {
    $showEmailInput = false;
}

$passwordResetSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the password in the database and clear the reset token
    $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $token);
    mysqli_stmt_execute($stmt);

    // Check if the password was successfully updated
    if (mysqli_stmt_affected_rows($stmt) == 1) {
        // Password reset successful
        $passwordResetSuccess = true;
    }

    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <?php require_once('include/bs5.php') ?>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Reset Password</h2>
                <?php if (isset($invalidTokenMessage)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $invalidTokenMessage; ?>
                    </div>
                    <form action="send_reset_link.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i>Submit</button>
                    </form>
                <?php } elseif ($passwordResetSuccess) { ?>
                    <div class="alert alert-success" role="alert">
                        Password reset successfully! <a href="login.php">Click Here to Login</a>.
                    </div>
                <?php } else { ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fgg2BlUdC0j7C+gZy5+YbTBqcPyGvX9x1lDb+ZVuDsv1+P2Ix3SjIy6Jy9v19tgb" crossorigin="anonymous"></script>
</body>
</html>

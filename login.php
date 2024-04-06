<?php
session_start(); // Start session

include_once 'include/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $sql = "SELECT id, username, password, role FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userId, $username, $hashedPassword, $role);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        // Password is correct
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role
        switch ($role) {
            case 'user':
                header("Location: user/home.php");
                break;
            case 'admin':
                header("Location: admin/home.php");
                break;
            case 'instructor':
                header("Location: instructor/home.php");
                break;
            default:
                // Handle other roles or unexpected scenarios
                header("Location: index.php");
                break;
        }
        exit();
    } else {
        // Invalid credentials
        $error = "Invalid email or password";
    }
}

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <?php require_once('include/bs5.php'); ?>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Login</h2>
                <?php if(isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="reset_password_request.php" class="text-decoration-none">Forgot Password?</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fgg2BlUdC0j7C+gZy5+YbTBqcPyGvX9x1lDb+ZVuDsv1+P2Ix3SjIy6Jy9v19tgb" crossorigin="anonymous"></script>
</body>
</html>

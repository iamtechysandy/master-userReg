<?php
session_start(); // Start session
require_once('../include/db_config.php');
// Check if user is logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// Welcome message based on role
$welcomeMsg = "";
switch ($_SESSION['role']) {
    case 'user':
        $welcomeMsg = "Welcome, User!";
        break;
    case 'admin':
        $welcomeMsg = "Welcome, Admin!";
        break;
    case 'instructor':
        $welcomeMsg = "Welcome, Instructor!";
        break;
    default:
        $welcomeMsg = "Welcome!";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <?php
    require_once('../include/bs5.php')
    ?>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $welcomeMsg; ?></h5>
                        <p class="card-text">This is your dashboard. You can start managing your tasks here.</p>
                        <a href="../logout.php" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

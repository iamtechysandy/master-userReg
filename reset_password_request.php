<?php
session_start(); // Start session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <!-- Bootstrap CSS -->
    <?php require_once('include/bs5.php') ?>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Reset Password</h2>
                <?php if(isset($_SESSION['reset_email_sent']) && $_SESSION['reset_email_sent']) { ?>
                    <div class="alert alert-success" role="alert">
                        Password reset link has been sent successfully.Please check your email also check Spam/Junk folder. 
                    </div>
                <?php } elseif(isset($_SESSION['reset_email_not_found']) && $_SESSION['reset_email_not_found']) { ?>
                    <div class="alert alert-danger" role="alert">
                        Email not found. Please enter a valid email address.
                    </div>
                <?php } ?>
                <form action="send_reset_link.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Registered Email" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i>Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fgg2BlUdC0j7C+gZy5+YbTBqcPyGvX9x1lDb+ZVuDsv1+P2Ix3SjIy6Jy9v19tgb" crossorigin="anonymous"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

<?php
// Include database configuration
require_once('include/db_config.php');
// Include PHPMailer library
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Remember to hash the password for security

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate verification code
    $verificationCode = bin2hex(random_bytes(16));

    // Default role ID for 'user'
    $defaultRoleID = 'user'; // Assuming 'user' role has ID 3 in your roles table

    // Prepare and execute SQL statement to insert data into the database
    $sql = "INSERT INTO users (username, email, password, verification_code, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $hashedPassword, $verificationCode, $defaultRoleID);

    if (mysqli_stmt_execute($stmt)) {
        // Send verification email
        $mail = new PHPMailer();
        $mail->isSMTP(); // Send using SMTP
        $mail->Host       = 'Your Smtp Server'; // SMTP server
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->Username   = 'Your Email'; // SMTP username
        $mail->Password   = 'Your Password'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption
        $mail->Port       = 465; // TCP port to connect to

        $mail->setFrom('Your Email', 'Name');
        $mail->addAddress($email);  // Add recipient
        $mail->Subject = 'Email Verification';
        $mail->Body = "Please click the following link to verify your email: http://localhost/lms/verify.php?code=$verificationCode";

        if ($mail->send()) {
            // Redirect with success message
            header("Location: signup.php?success=true");
            exit();
        } else {
            echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>

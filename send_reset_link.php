<?php
session_start(); // Start session

// Include database configuration
require_once('include/db_config.php');
// Include PHPMailer library
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Initialize session variables
$_SESSION['reset_email_sent'] = false;
$_SESSION['reset_email_error'] = false;
$_SESSION['reset_email_not_found'] = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT id, reset_token, reset_token_expiry FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $rowCount = mysqli_stmt_num_rows($stmt);
    
    if ($rowCount == 1) {
        mysqli_stmt_bind_result($stmt, $userId, $resetToken, $resetTokenExpiry);
        mysqli_stmt_fetch($stmt);
        
        // Check if token is still valid
        if ($resetToken && $resetTokenExpiry && strtotime($resetTokenExpiry) > time()) {
            // Token is valid, send reset link
            $token = $resetToken;

            // Send reset link to the user's email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP(); // Send using SMTP
                $mail->Host       = 'Your Email Server'; // SMTP server
                $mail->SMTPAuth   = true; // Enable SMTP authentication
                $mail->Username   = 'Your Email'; // SMTP username
                $mail->Password   = 'Yoyr Passowrd'; // SMTP password
                $mail->SMTPSecure = 'ssl'; // Enable TLS encryption
                $mail->Port       = 465; // TCP port to connect to
        
                $mail->setFrom('Your EMail', 'Techysandy.com-Admin');
                $mail->addAddress($email);  // Add recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Password Reset Link';
                $mail->Body    = "Click the following link to reset your password: <a href='http://localhost/lms/reset_password.php?token=$token'>Reset Password</a>";

                $mail->send();
                
                // Email sent successfully
                $_SESSION['reset_email_sent'] = true;
            } catch (Exception $e) {
                // Error sending email
                $_SESSION['reset_email_error'] = true;
            }
        } else {
            // Token has expired or not available, generate a new one
            $token = bin2hex(random_bytes(32));

            // Update token and expiry in the database
            $sql = "UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $token, $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Send reset link to the user's email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP(); // Send using SMTP
                $mail->Host       = 'Server'; // SMTP server
                $mail->SMTPAuth   = true; // Enable SMTP authentication
                $mail->Username   = 'Your Email'; // SMTP username
                $mail->Password   = 'Your Password'; // SMTP password
                $mail->SMTPSecure = 'ssl'; // Enable TLS encryption
                $mail->Port       = 465; // TCP port to connect to
        
                $mail->setFrom('Your Name', 'Techysandy.com-Admin');
                $mail->addAddress($email);  // Add recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Password Reset Link';
                $mail->Body    = "Click the following link to reset your password: <a href='http://localhost/lms/reset_password.php?token=$token'>Reset Password</a>";

                $mail->send();
                
                // Email sent successfully
                $_SESSION['reset_email_sent'] = true;
            } catch (Exception $e) {
                // Error sending email
                $_SESSION['reset_email_error'] = true;
            }
        }
    } else {
        // Email not found in database
        $_SESSION['reset_email_not_found'] = true;
    }
    
    // Redirect to reset_password_request.php
    header("Location: reset_password_request.php");
    exit();
}
?>

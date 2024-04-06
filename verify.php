<?php
include_once 'include/db_config.php';

if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];

    // Check if the verification code exists in the database
    $sql = "SELECT email FROM users WHERE verification_code = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $verificationCode);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $email);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt); // Close the statement before preparing a new one

    if ($email) {
        // Mark the email as verified
        $updateSql = "UPDATE users SET email_verified = 1 WHERE email = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "s", $email);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt); // Close the statement after execution
        
        // Delete the verification code from the database
        $deleteSql = "UPDATE users SET verification_code = NULL WHERE email = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "s", $email);
        mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt); // Close the statement after execution

        echo "Email verified successfully!";
    } else {
        echo "Invalid verification code!";
    }
} else {
    echo "Verification code not provided!";
}

// Close connection
mysqli_close($conn);
?>

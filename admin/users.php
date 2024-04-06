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

// Fetch all users from the database
$sql = "SELECT email, username, email_verified, IFNULL(reset_token, 'No') AS reset_password_requested, role FROM users";
$result = mysqli_query($conn, $sql);

// Initialize user data array
$usersData = [];

// Fetch user data and store in array
while ($row = mysqli_fetch_assoc($result)) {
    $usersData[] = $row;
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <?php require_once('../include/bs5.php') ?>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Admin Dashboard</h5>
                        <?php if (!empty($usersData)) { ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Email Verified</th>
                                        <th>Reset Password Requested</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usersData as $user) { ?>
                                        <tr>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $user['email_verified'] ? 'Yes' : 'No'; ?></td>
                                            <td><?php echo $user['reset_password_requested']; ?></td>
                                            <td><?php echo $user['role']; ?></td>
                                            <td>
                                                <form action="update_role.php" method="post">
                                                    <input type="hidden" name="email" value="<?php echo $user['email']; ?>">
                                                    <div class="input-group mb-3">
                                                        <select class="form-select" name="role" aria-label="Role">
                                                            <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                                            <option value="instructor" <?php echo ($user['role'] === 'instructor') ? 'selected' : ''; ?>>Instructor</option>
                                                            <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-primary">Change Role</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <p>No users found.</p>
                        <?php } ?>
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

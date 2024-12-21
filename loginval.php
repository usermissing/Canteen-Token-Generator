<?php
session_start();

// Hard-coded admin credentials
$admin_email = "admin@gmail.com";
$admin_password_hash = password_hash("Admin@123", PASSWORD_BCRYPT); // Securely hashed password

if (isset($_POST['login'])) {
    $email = $_POST['logemail'];
    $password = $_POST['logpass'];

    // Validate email and password
    if ($email === $admin_email && password_verify($password, $admin_password_hash)) {
        // Login successful
        $_SESSION['admin'] = true;
        $_SESSION['admin_email'] = $admin_email;
        header("Location: admindashboard.php"); // Redirect to the admin dashboard
        exit();
    } else {
        // Login failed
        echo "Invalid email or password.";
    }
}
?>
6
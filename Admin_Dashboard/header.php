<?php
require('../connection.php');
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Redirect to login page if the admin is not logged in
    header("Location: login.php"); // Change 'login.php' to your login page file
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/72f30a4d56.js" crossorigin="anonymous"></script>
    <link rel="icon" href="favIcon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Add Menu | Admin Panel</title>
    <style>
    .custom-shadow {
        filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.2));
    }
    </style>
</head>

<body class="bg-gray-100 font-sans ">
    <!-- Header Section -->
    <header class="bg-white shadow-md py- fixed w-full z-50">
        <div class="container mx-auto px-6 flex items-center ">
            <!-- Logo -->
            <a href="index.php" class="flex items-center">
                <img src="../images/logo1.png" alt="Logo" class="h-24 custom-shadow">
            </a>
            <div class="container mx-auto px-6 flex justify-end gap-5 items-end">
                <!-- Add Menu Button -->
                <a href="addMenu.php" class="block text-gray-700 font-semibold hover:text-cyan-600 transition">
                    <i class="fas fa-plus-circle py-4"></i>
                    <span class="hidden md:inline">Add Menu</span>
                </a>
                <a href="adminvieworders.php" class="block text-gray-700 font-semibold hover:text-cyan-600 transition">
                    <i class="fas fa-receipt py-4"></i>
                    <span class="hidden md:inline">View Order</span>
                </a>
                <a href="orderHistory.php" class="block text-gray-700 font-semibold hover:text-cyan-600 transition">
                    <i class="fas fa-history py-4"></i>
                    <span class="hidden md:inline">Order History</span>
                </a>
                <a href="logout.php" class="block text-red-600 font-semibold hover:text-red-400 transition">
                    <i class="fas fa-sign-out-alt py-4"></i>
                    <span class="hidden md:inline">Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20">
        <div class="container mx-auto px-6">
            <!-- <h1 class="text-2xl font-bold text-gray-800">Welcome to the Admin Panel</h1> -->
        </div>
    </main>
</body>

</html>
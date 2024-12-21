<?php
require('../connection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="../favIcon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/72f30a4d56.js" crossorigin="anonymous"></script>
    <style>
        .custom-shadow {
    filter: drop-shadow(10px 10px 20px white);
}
    </style>
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <section class="bg-blue-200 p-4 shadow-md fixed w-70 h-full flex flex-col">
        <div class="h-full flex flex-col justify-between">
            <div class="custom-shadow">
            <img src="../img/logo1.png" alt="Logo"  class="w-80">
            <a href="index.php">
            </a>
        </div>
        <nav class=" ">
            <ul>
                <li>
                    <a href="index.php" class="flex items-center text-white font-semibold py-2 px-4 rounded-lg hover:bg-cyan-700 transition">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="addMenu.php" class="flex items-center text-white font-semibold py-2 px-4 rounded-lg hover:bg-cyan-700 transition">
                        <i class="fas fa-users mr-3"></i> Manage Donors
                    </a>
                </li>
               
            </ul>
        </nav>
        
            </div>
    </section>

    <!-- Main Content -->
    <div class="  p-10 bg-gray-100 ">
    </div>
</body>

</html>

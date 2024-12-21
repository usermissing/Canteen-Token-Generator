<?php
require('../connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/72f30a4d56.js" crossorigin="anonymous"></script>
    <link rel="icon" href="favIcon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .custom-shadow {
            filter: drop-shadow(10px 10px 10px red);
        }
    </style>
    <title>Document</title>
</head>

<body>
    
<section class="bg-white p-4 shadow-md py-4 fixed w-full z-50">
    <div class="container mx-auto h-14 flex items-center justify-between mt-2">
        
        <a href="index.php">
            <img src="img/logo1.png" alt="Logoo" width="240" height="100" class="custom-shadow">
        </a>
        <a href="addMenu.php" class="flex items-center text-black font-semibold py-2 px-4 rounded-lg hover:bg-cyan-700 transition">
            <i class="fas fa-tachometer-alt mr-3"></i> Add Menu
        </a>
        <div class="flex items-center font-bold">

            
        <ul>
                <li>
                </li>
                </ul>
             
        
        </div>
              




           
            

        </div>
    </div>
</section>
</body>

</html>

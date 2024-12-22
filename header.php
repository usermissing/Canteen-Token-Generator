<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .navbar {
        display: flex;
        /* justify-content:items-center; */
        background-color: #588157;
        padding: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar a {
        color: #d4a373;
        text-decoration: none;
        margin: 0 15px;
        padding: 1px 16px;
        transition: background-color 0.3s;
    }

    .navbar a:hover {
        background-color: #575757;
        border-radius: 4px;
    }

    .navbar img {
        height: 110px;
        width: 110px;
        margin-right: auto;
    }
    </style>
</head>

<body>
    <div class="navbar">
        <!-- <div class="container mx-auto  items-center "> -->

        <a href="index.php" class="justify-center ">
            <img src="images/logo1.png" alt="Logoo"></a>
        <!-- <a href="index.php">About Us</a> -->

        <!-- <a href="#">Menu</a> -->
        <a href="orderhistory.php">History</a>

    </div>
    </div>
</body>

</html>
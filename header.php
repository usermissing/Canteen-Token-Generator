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
        background-color: #fff;
        padding: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar a {
        /* color: #d4a373; */

        color: black;
        text-decoration: none;
        margin-left: 15px;
        padding: 10px 16px;
        transition: background-color 0.3s;
    }

    .navbar a:hover {
        background-color: #575757;
        border-radius: 4px;
        color: white;
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
            <img src="images/logo1.png" alt="Logoo" style="width:70px;height:70px;"></a>
        <!-- <a href="index.php">About Us</a> -->
        <div>
            <!-- <a href="#">Menu</a> -->
            <a href="queue.php">View Queue</a>
            <a href="orderhistory.php">History</a>
        </div>

    </div>
    </div>
</body>

</html>
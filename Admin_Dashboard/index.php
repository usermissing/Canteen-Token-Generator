<?php
require('../connection.php');

// Fetch all available items
function fetchItems($con) {
    $sql = "SELECT * FROM food_items WHERE availability_status = 'Available'";
    return $con->query($sql);
}
function fetchTodayItems($con) {
    // Get today's day name (e.g., "Sunday", "Monday")
    $today_day = date('l');
    
    // Query to fetch items where available_days matches today's day
    $sql = "SELECT * FROM food_items WHERE availability_status = 'Available' AND available_days = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $today_day);  // Bind today's day as a string
    $stmt->execute();
    return $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Items for Today's Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #001F3F;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .navbar h1 {
            margin: 0;
            color: #FFC300;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 1.5rem;
            color: #001F3F;
        }
        form label {
            font-weight: bold;
        }
        form input[type="checkbox"] {
            margin-right: 0.5rem;
        }
        button {
            background-color: #FFC300;
            color: #001F3F;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #e0a800;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        .item {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fdfdfd;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Today's Menu Manager</h1>
    </div>

    <div class="container">
        <h1>Select Items for Today's Menu</h1>
        <form action="update_todays_menu.php" method="POST">
            <label for="items">Select Items:</label><br>
            <div class="grid-container">
                <?php
                $items = fetchItems($con);
                if ($items->num_rows > 0) {
                    while ($item = $items->fetch_assoc()) {
                        echo "<div class='item'><input type='checkbox' name='selected_items[]' value='{$item['item_id']}'> {$item['name']}</div>";
                    }
                } else {
                    echo "<p>No items available</p>";
                }
                ?>
            </div>
            <br>
            <button type="submit">Next</button>
        </form>
    </div>

    <div class="container">
        <h1>Today's Menu</h1>
        <div class="grid-container">
            <?php
            $items = fetchTodayItems($con);
            if ($items->num_rows > 0) {
                while ($item = $items->fetch_assoc()) {
                    echo "<div class='item'>";
                    echo "<strong>Name:</strong> {$item['name']}<br>";
                    echo "<strong>Quantity:</strong> {$item['quantity']}<br>";
                    echo "<strong>Price:</strong> {$item['price']}<br>";
                    echo "</div>";
                }
            } else {
                echo "<p>No items available for today</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

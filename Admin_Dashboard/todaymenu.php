<?php
require('../connection.php');

// Fetch all available items where available_days is today's day
function fetchItems($con) {
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
</head>
<body>
    <h1>Select Items for Today's Menu</h1>
    <form action="update_todays_menu.php" method="POST">
        <label for="items">Select Items:</label><br>
        <?php
        $items = fetchItems($con);
        if ($items->num_rows > 0) {
            while ($item = $items->fetch_assoc()) {
                // Create a checkbox for each item with additional details
                echo "<input type='checkbox' name='selected_items[]' value='{$item['item_id']}'> {$item['name']}<br>";
                echo "Quantity: {$item['quantity']}<br>";
                echo "Available Days: {$item['available_days']}<br>";
                echo "Price: {$item['price']}<br>"; // Assuming you have a price column in your table
                echo "<hr>";
            }
        } else {
            echo "<p>No items available for today</p>";
        }
        ?>
        <br>
        <button type="submit">Next</button>
    </form>
</body>
</html>

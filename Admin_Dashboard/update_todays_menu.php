<?php
require('../connection.php');

// Fetch all available items
function fetchItems($con, $selected_items) {
    $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
    $sql = "SELECT * FROM food_items WHERE item_id IN ($placeholders)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($selected_items)), ...$selected_items);
    $stmt->execute();
    return $stmt->get_result();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items']; // Selected item IDs

    // Fetch the selected items from the database
    $items = fetchItems($con, $selected_items);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Today's Menu Quantity</title>
</head>
<body>
    <h1>Update Quantity for Selected Items</h1>
    <form action="update_quantity.php" method="POST">
        <?php
        if ($items->num_rows > 0) {
            while ($item = $items->fetch_assoc()) {
                echo "<div>";
                echo "<label for='quantity_{$item['item_id']}'>{$item['name']}</label><br>";
                echo "<input type='hidden' name='item_ids[]' value='{$item['item_id']}'>";
                echo "<input type='number' id='quantity_{$item['item_id']}' name='quantities[]' value='{$item['quantity']}' min='0' required><br><br>";
                echo "</div>";
            }
        } else {
            echo "<p>No items found.</p>";
        }
        ?>
        <button type="submit">Update Quantities</button>
    </form>
</body>
</html>

<?php
require('../connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantities']) && isset($_POST['item_ids'])) {
    $quantities = $_POST['quantities'];  // Updated quantities
    $item_ids = $_POST['item_ids'];      // Corresponding item IDs

    // Loop through each item and update the quantity and available_days
    for ($i = 0; $i < count($quantities); $i++) {
        $item_id = $item_ids[$i];
        $new_quantity = $quantities[$i];

        // Update the quantity and available_days in the food_items table
        $sql = "UPDATE food_items SET quantity = ?, available_days = DAYNAME(CURDATE()) WHERE item_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii', $new_quantity, $item_id);
        
        if ($stmt->execute()) {
            // echo "Quantity of item ID $item_id updated to $new_quantity.<br>";
        } else {
            echo "Error updating item ID $item_id: " . $stmt->error . "<br>"; // Show the error if any
        }
    }

    // After updating, fetch the updated items and show them as today's menu
    $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
    $sql = "SELECT * FROM food_items WHERE item_id IN ($placeholders)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($item_ids)), ...$item_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    
    <h1>Today's Menu</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Updated Quantity</th>
                <th>Available Days</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($item = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$item['name']}</td>";
                echo "<td>{$item['quantity']}</td>";
                echo "<td>{$item['available_days']}</td>"; // Display the updated available day (e.g., Sunday, Monday)
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="index.php">Index</a>
    
    <?php
}
?>

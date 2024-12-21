<?php
require('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['quantities'])) {
    $quantities = $_POST['quantities'];
    $day = date('l'); // Get today's day name

    foreach ($quantities as $itemId => $quantity) {
        // Insert or update today's menu
        $sqlTodaysMenu = "INSERT INTO todays_menu (item_id, quantity, day, added_on)
                          VALUES (?, ?, ?, NOW())
                          ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)";
        $stmtTodaysMenu = $con->prepare($sqlTodaysMenu);
        $stmtTodaysMenu->bind_param("iis", $itemId, $quantity, $day);
        $stmtTodaysMenu->execute();
        $stmtTodaysMenu->close();

        // Update available days and quantity in the food_items table
        $sqlFoodItems = "UPDATE food_items 
                         SET available_days = ?, quantity = ? 
                         WHERE item_id = ?";
        $stmtFoodItems = $con->prepare($sqlFoodItems);
        $stmtFoodItems->bind_param("sii", $day, $quantity, $itemId);
        $stmtFoodItems->execute();
        $stmtFoodItems->close();
    }

    echo "Today's menu has been updated and food item quantities have been adjusted!";
    header("Location: index.php");
    exit;
} else {
    echo "No quantities provided.";
    exit;
}
?>

<?php
// Database connection
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $order = $input['order'] ?? [];
    $response = [];
    $canProceed = true;

    // Start a transaction to ensure atomicity of stock updates
    $con->begin_transaction();

    // Loop through the order and update stock
    foreach ($order as $item) {
        $itemId = $item['item_id'];
        $quantity = $item['quantity'];

        // Fetch current stock for the item
        $query = $con->prepare("SELECT quantity FROM food_items WHERE item_id = ?");
        $query->bind_param('i', $itemId);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        // If stock is insufficient, rollback the transaction
        if ($result['quantity'] < $quantity) {
            $canProceed = false;
            $response[] = [
                'item_id' => $itemId,
                'message' => "Insufficient stock for item ID: $itemId. Available: {$result['quantity']}, Requested: $quantity"
            ];
        }
    }

    if ($canProceed) {
        // Update the stock of each item if the order can proceed
        foreach ($order as $item) {
            $itemId = $item['item_id'];
            $quantity = $item['quantity'];

            // Update the stock in the database
            $updateQuery = $con->prepare("UPDATE food_items SET quantity = quantity - ? WHERE item_id = ?");
            $updateQuery->bind_param('ii', $quantity, $itemId);
            $updateQuery->execute();
        }

        // Commit the transaction to apply the changes
        $con->commit();

        // Return success response
        echo json_encode([
            'success' => true
        ]);
    } else {
        // Rollback the transaction if stock is insufficient
        $con->rollback();

        // Return failure response
        echo json_encode([
            'success' => false,
            'issues' => $response
        ]);
    }
}
?>
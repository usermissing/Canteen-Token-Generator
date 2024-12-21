<?php
include 'connection.php';

header('Content-Type: application/json');

// Read and decode JSON data
$orderData = json_decode(file_get_contents('php://input'), true);

$response = [];
$grandTotal = 0;
$orderedItems = [];

// Process each item in the order
foreach ($orderData as $orderItem) {
    $itemId = $orderItem['item_id'];
    $requestedQuantity = $orderItem['quantity'];

    // Fetch the available quantity from the database
    $stmt = $con->prepare("SELECT name, price, quantity FROM food_items WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $itemName = $item['name'];
        $itemPrice = $item['price'];
        $availableQuantity = $item['quantity'];

        // Check if the requested quantity is available
        if ($requestedQuantity > $availableQuantity) {
            $response['status'] = 'invalid';
            $response['message'] = "Requested quantity for $itemName exceeds available stock.";
            echo json_encode($response);
            exit;
        } else {
            // Calculate the total for this item
            $totalPrice = $itemPrice * $requestedQuantity;

            // Update the database to reduce the available quantity
            $newQuantity = $availableQuantity - $requestedQuantity;
            $updateStmt = $con->prepare("UPDATE food_items SET quantity = ? WHERE item_id = ?");
            $updateStmt->bind_param("ii", $newQuantity, $itemId);
            $updateStmt->execute();

            // Add to the ordered items list
            $orderedItems[] = [
                'name' => $itemName,
                'quantity' => $requestedQuantity,
                'price' => $itemPrice,
                'total' => $totalPrice
            ];

            // Update grand total
            $grandTotal += $totalPrice;
        }
    } else {
        $response['status'] = 'invalid';
        $response['message'] = "Item with ID $itemId not found.";
        echo json_encode($response);
        exit;
    }
}

$response['status'] = 'success';
$response['items'] = $orderedItems;
$response['grandTotal'] = $grandTotal;

echo json_encode($response);
?>
<?php
// Database connection

include "connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $order = $input['order'] ?? [];
    $response = [];
    $canProceed = true;

    foreach ($order as $item) {
        $itemId = $item['item_id'];
        $quantity = $item['quantity'];

        // Fetch stock for the current item
        $query = $con->prepare("SELECT * FROM food_items WHERE item_id = ?");
        $query->bind_param('i', $itemId);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        if ($result['quantity'] < $quantity) {
            $canProceed = false;
            $response[] = [
                'item_id' => $itemId,
                'message' => $result['name'] . " - ".$result['quantity']
            ];
        }
    }

    if ($canProceed) {
        echo json_encode([
            'success' => true,
            'message' => 'Order can proceed!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'issues' => $response
        ]);
    }
}
?>
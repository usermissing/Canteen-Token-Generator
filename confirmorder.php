<?php
    // Assuming you have already established a connection to the database
    include "connection.php";
    // Get the total cost and order items (product_id and quantity) from the frontend
    $total_cost = $_POST['total']; // The total order cost (sent from the frontend)
    $order_items = json_decode($_POST['orderData'], true); // The order items array (sent as JSON)

    // Insert the order into the orders table
    $sql = "INSERT INTO orders (total_cost) VALUES (?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("d", $total_cost);
    $stmt->execute();

    // Get the ID of the inserted order (oid)
    $oid = $stmt->insert_id;
    $stmt->close();


    // Insert each item into the order_items table
    $sql = "INSERT INTO order_items (oid, item_id, quantity) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);

    foreach ($order_items as $item) {
        $item_id = $item['item_id']; // Product ID from the frontend
        $quantity = $item['quantity']; // Quantity from the frontend
        $stmt->bind_param("iii", $oid, $item_id, $quantity);
        $stmt->execute();
        //decrease
        // Decrease the quantity of the item in the inventory
        $update_inventory_sql = "UPDATE food_items SET quantity = quantity - ? WHERE item_id = ?";
        $update_stmt = $con->prepare($update_inventory_sql);
        $update_stmt->bind_param("ii", $quantity, $item_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    $stmt->close();
    $con->close();

    // echo "Order placed successfully with ID: " . $oid;

    $order_summary = "";
foreach ($order_items as $item) {
    $order_summary .=  $item['name'] . " - " . $item['quantity'] . "<br>";
}

// Display the order summary along with the order ID (token) in large letters
echo "
    <div style='text-align: center; font-family: Arial, sans-serif;'>
        <h1>Order Confirmed</h1>
        <p style='font-size: 24px;'>Token Number</p>
        <strong style='font-size: 48px; color: green;'>$oid</strong>
        <h3>Ordered Items:</h3>
        <p style='font-size: 18px;'>$order_summary</p>
        <p style='font-size: 24px;'>Total Cost: Rs. $total_cost</p>
    </div>
";
?>
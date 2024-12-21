<?php
// Connect to your database
include('connection.php');

// Query to get all orders along with their items
$sql = "
    SELECT 
        o.oid, 
        o.status, 
        o.total_cost, 
        o.created_at, 
        i.name, 
        oi.quantity, 
        i.price, 
        (oi.quantity * i.price) AS total_price
    FROM 
        orders o
    LEFT JOIN 
        order_items oi ON o.oid = oi.oid
    LEFT JOIN 
        food_items i ON oi.item_id = i.item_id
    
    ORDER BY 
        o.created_at ASC
";
$result = $con->query($sql);

// Initialize an empty array to store orders and their items
$orders = [];

// Process each row from the query result
while ($row = $result->fetch_assoc()) {
    $oid = $row['oid'];

    // If the order ID is not in the orders array, initialize it
    if (!isset($orders[$oid])) {
        $orders[$oid] = [
            'oid' => $oid,
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'total_cost' => $row['total_cost'],
            'items' => []
        ];
    }

    // Add the item details to the corresponding order
    $orders[$oid]['items'][] = [
        'name' => $row['name'],
        'created_at' => $row['created_at'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'total_price' => $row['total_price']
    ];
}

// Display the orders and their items
foreach ($orders as $order) {
    echo "<h2>Order ID: " . $order['oid'] . "</h2>";
    echo "<h2>Date: " . $order['created_at'] . "</h2>";
    echo "<p>Status: " . $order['status'] . "</p>";
    echo "<p>Total Cost: Rs. " . $order['total_cost'] . "</p>";

    echo "<h3>Items in this Order:</h3>";
    echo "<ul>";
    foreach ($order['items'] as $item) {
        echo "<li>";
        echo "Item: " . $item['name'] . " - ";
        echo "Price: Rs. " . $item['price'] . " - ";
        echo "Quantity: " . $item['quantity'] . " - ";
        echo "Total Price: Rs. " . $item['total_price'];
        echo "</li>";
    }
    echo "</ul>";
}

$con->close();
?>
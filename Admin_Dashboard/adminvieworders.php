<?php
// Connect to your database
include('../connection.php');

// Fetch all orders with their items
$sql = "
    SELECT 
        o.oid, 
        o.total_cost, 
        i.name, 
        oi.quantity, 
        i.price, 
        (oi.quantity * i.price) AS total_price
    FROM orders o
    LEFT JOIN order_items oi ON o.oid = oi.oid
    LEFT JOIN food_items i ON oi.item_id = i.item_id
    WHERE o.status = 'Pending'
    ORDER BY o.created_at
";
$result = $con->query($sql);

echo "<h2>Orders Pending</h2>";
echo "<table border='1'>
        <tr>
            <th>Order ID</th>
            <th>Items</th>
            <th>Total Cost</th>
            <th>Action</th>
        </tr>";

$orders = [];

while ($row = $result->fetch_assoc()) {
    $oid = $row['oid'];

    // If the order ID is not in the orders array, initialize it
    if (!isset($orders[$oid])) {
        $orders[$oid] = [
            'oid' => $oid,
            'total_cost' => $row['total_cost'],
            'items' => []
        ];
    }

    // Add the item details to the corresponding order
    $orders[$oid]['items'][] = [
        'name' => $row['name'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'total_price' => $row['total_price']
    ];
}

// Display the orders and their items
foreach ($orders as $order) {
    echo "<tr>
            <td>" . $order['oid'] . "</td>
            <td>";

    // Display the items for this order
    echo "<ul>";
    foreach ($order['items'] as $item) {
        echo "<li>" . $item['name'] . " (Qty: " . $item['quantity'] . ") - Rs. " . $item['total_price'] . "</li>";
    }
    echo "</ul>";

    echo "</td>
            <td>Rs. " . $order['total_cost'] . "</td>
            <td><button onclick='markCompleted(" . $order['oid'] . ")'>Mark as Completed</button></td>
          </tr>";
}

echo "</table>";

$con->close();
?>

<script>
function markCompleted(orderId) {
    // Send a request to the server to mark the order as completed
    fetch('mark_order_completed.php', {
            method: 'POST',
            body: JSON.stringify({
                oid: orderId
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order marked as completed!');
                location.reload(); // Reload the page to show the updated list
            } else {
                alert('Error marking order as completed');
            }
        });
}
</script>
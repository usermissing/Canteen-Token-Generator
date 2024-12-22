<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Redirect to login page if the admin is not logged in
    header("Location: login.php"); 
    exit();
}
// Connect to your database
include('../connection.php');
@include'header.php';
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

echo "<h2 class='text-center text-xl font-semibold mb-4 mt-10'>Orders Pending</h2>";
echo "<div class='overflow-x-auto'>";
echo "<table class='table-auto w-3/5 mx-auto border-collapse border border-gray-300 overflow-x-auto'>
        <thead>
            <tr class='bg-gray-300'>
                <th class='px-4 py-2 border'>Order ID</th>
                <th class='px-4 py-2 border'>Items</th>
                <th class='px-4 py-2 border'>Total Cost</th>
                <th class='px-4 py-2 border'>Action</th>
            </tr>
        </thead>
        <tbody>";

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
    echo "<tr class='bg-white '>
            <td class='px-4 py-2 border'>" . $order['oid'] . "</td>
            <td class='px-4 py-2 border'>";

    // Display the items for this order
    echo "<ul>";
    foreach ($order['items'] as $item) {
        echo "<li>" . $item['name'] . " (Qty: " . $item['quantity'] . ") - Rs. " . $item['total_price'] . "</li>";
    }

    echo "</td>
            <td class='px-4 py-2 border'>Rs. " . $order['total_cost'] . "</td>
            <td class='px-4 py-2 border'><button onclick='markCompleted(" . $order['oid'] . ")' class='bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600'>Mark as Completed</button></td>
          </tr>";
}

echo "</tbody>
</table>";
echo "<div class='overflow-x-auto'>";


            echo "</td>
        <td>Rs. " . $order['total_cost'] . "</td>
        <td><button onclick='markCompleted(" . $order['oid'] . ")'>Mark as Completed</button></td>
          </tr>" ;  echo "</table>" ; $con->close();
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
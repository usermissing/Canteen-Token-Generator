<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Redirect to login page if the admin is not logged in
    header("Location: login.php"); 
    exit();
}

// Connect to your database
include('../connection.php');
@include 'header.php';

// Fetch all completed orders with their items
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
    WHERE o.status = 'Completed'  -- Filter only completed orders
    ORDER BY o.created_at DESC    -- Orders from most recent to oldest
";
$result = $con->query($sql);
$date=date("y-m-d");
echo "<h2 class='text-center text-xl font-semibold mb-4 mt-10'>Completed Order History</h2>";
echo "<h3 class='text-center text-xl font-semibold mb-4 mt-10'>$date</h3>";
echo "<div class='overflow-x-auto'>";
echo "<table class='table-auto w-3/5 mx-auto border-collapse border border-gray-300 overflow-x-auto'>
        <thead>
            <tr class='bg-gray-300'>
                <th class='px-4 py-2 border'>Order ID</th>
                <th class='px-4 py-2 border'>Items</th>
                <th class='px-4 py-2 border'>Total Cost</th>
            </tr>
        </thead>
        <tbody>";

$orders = [];
$grand_total=0;
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
    $grand_total += $row['total_price'];
    
    
}




// Display the orders and their items
foreach ($orders as $order) {
    echo "<tr class='bg-white'>
            <td class='px-4 py-2 border'>" . $order['oid'] . "</td>
            <td class='px-4 py-2 border'>";

    // Display the items for this order
    echo "<ul>";
    foreach ($order['items'] as $item) {
        echo "<li>" . $item['name'] . " (Qty: " . $item['quantity'] . ") - Rs. " . $item['total_price'] . "</li>";
    }
    echo "</ul>";

    echo "</td>
            <td class='px-4 py-2 border'>Rs. " . $order['total_cost'] . "</td>
          </tr>";

          
}

echo "</tbody>
</table>";

//displaying grand total
echo "<div class= 'text-center mt-4 font-semibold'>
    <h3 class='text-x1'>Grand total:Rs.$grand_total</h3>
</div>";

echo "<div class='overflow-x-auto'>";

$con->close();
?>
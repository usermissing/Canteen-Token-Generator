<?php
include('connection.php');
include('header.php');

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

// Display the orders and their items in a row
echo "<div class='flex flex-wrap gap-5 m-10 justify-center'>";
foreach ($orders as $order) {
    echo "<div class='bg-white shadow-md rounded-lg p-6 w-full md:w-1/3 lg:w-1/5'>"; 
    echo "<h2 class='text-lg font-bold text-blue-400'>Token No: " . $order['oid'] . "</h2>";
    echo "<h2 class='text-sm  text-gray-700'>Date: " . $order['created_at'] . "</h2>";
    echo "<p class='text-gray-700'>Status: <span class='font-medium'>" . $order['status'] . "</span></p>";
    echo "<p class='text-gray-700'>Total Cost: <span class='font-medium'>Rs. " . $order['total_cost'] . "</span></p>";

    echo "<h3 class='text-md font-semibold mt-4 text-green-600'>Items in this Order:</h3>";
    echo "<ul class='list-disc ml-6'>";
    foreach ($order['items'] as $item) {
        echo "<li class='text-gray-600'>";
        echo "<span class='font-medium '>Item:</span> " . $item['name'] . " ";
        echo "<span class='font-medium'>Price:</span> Rs. " . $item['price'] . "  ";
        echo "<span class='font-medium'>Quantity:</span> " . $item['quantity'] . "  ";
        echo "<span class='font-medium'>Total Price:</span> Rs. " . $item['total_price'];
        echo "</li>";
    }
    echo "</ul>";
    echo "</div>";
}
echo "</div>";

$con->close();
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

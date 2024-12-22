<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #f4f4f9;
    color: #333;
}

.token-display {
    margin-top: 20px;
    padding: 20px;
    background-color: #007BFF;
    color: white;
    border-radius: 10px;
    font-size: 60px;
    font-weight: bold;
    text-align: center;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.products {
    margin-top: 20px;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.products h2 {
    margin-top: 0;
    color: #007BFF;
}

.product-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
}

.product-item:last-child {
    border-bottom: none;
}

.total {
    margin-top: 20px;
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    color: #333;
}
.date {
    margin-top: 20px;
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    color:#FFFFFF;
}
</style>
<?php
// Connect to your database
include('connection.php');
// Fetch all orders with their items
$sql = "
SELECT
o.oid,
o.total_cost,
o.created_at,
i.name,
oi.quantity,
i.price,
(oi.quantity * i.price) AS total_price
FROM orders o
LEFT JOIN order_items oi ON o.oid = oi.oid
LEFT JOIN food_items i ON oi.item_id = i.item_id
WHERE o.status = 'Pending'
ORDER BY o.created_at
DESC
LIMIT 1
";
$result = $con->query($sql);


echo "<div class='overflow-x-auto'>";


    $orders = [];

    while ($row = $result->fetch_assoc()) {
    $oid = $row['oid'];

    // If the order ID is not in the orders array, initialize it
    if (!isset($orders[$oid])) {
    $orders[$oid] = [
    'oid' => $oid,
    'total_cost' => $row['total_cost'],
    'created_at' => $row['created_at'],

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
    echo '
    <div class="token-display">
        Token Number: '.$order["oid"].'
        
        <div class="date">
        date: '.$order["created_at"].'
        </div>
        </div>

    ';

        
        
}
echo'
<div class="products">
            <h2>Order has been placed.</h2>
            <p>Please wait for your turn</p>
            <a class="btn btn-primary" href="./">Home</a>
            </div>
            ';
 $con->close();
?>
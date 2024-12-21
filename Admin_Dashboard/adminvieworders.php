<?php
// Connect to your database
include('../connection.php');

// Fetch all orders
$sql = "SELECT * FROM orders WHERE status = 'Pending' ORDER BY created_at";
$result = $con->query($sql);

echo "<h2>Orders Pending</h2>";
echo "<table border='1'>
        <tr>
            <th>Order ID</th>
            <th>Total Cost</th>
            <th>Action</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . $row['oid'] . "</td>
            <td>" . $row['total_cost'] . "</td>
            <td><button onclick='markCompleted(" . $row['oid'] . ")'>Mark as Completed</button></td>
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
<?php
// Include your database connection
// Make sure $con is your database connection object
include('connection.php');

$tokensql = "SELECT * FROM orders WHERE status = 'Pending' ORDER BY oid ASC LIMIT 5";
$tokenres = $con->query($tokensql);

$tokens = [];

while ($row = $tokenres->fetch_assoc()) {
    $tokens[] = $row['oid'];
}

// Return the response as JSON
if (empty($tokens)) {
    echo json_encode(['status' => 'noqueue']);
} else {
    echo json_encode(['status' => 'success', 'tokens' => $tokens]);
}
?>
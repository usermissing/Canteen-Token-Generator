<?php 
require('../connection.php');

// Fetch all available items
function fetchItems($con, $selected_items) {
    $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
    $sql = "SELECT * FROM food_items WHERE item_id IN ($placeholders)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($selected_items)), ...$selected_items);
    $stmt->execute();
    return $stmt->get_result();
}

// Handle POST request for selected items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items']; // Selected item IDs
    $items = fetchItems($con, $selected_items); // Fetch the selected items
}

// Handle quantity update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantities']) && isset($_POST['item_ids'])) {
    $quantities = $_POST['quantities'];
    $item_ids = $_POST['item_ids'];

    for ($i = 0; $i < count($quantities); $i++) {
        $item_id = $item_ids[$i];
        $new_quantity = $quantities[$i];

        $sql = "UPDATE food_items SET quantity = ?, added_on = NOW() WHERE item_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii', $new_quantity, $item_id);
        $stmt->execute();
    }

    // Redirect with success message and retain selected items
    $selected_items_query = http_build_query(['selected_items' => implode(',', $item_ids)]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&" . $selected_items_query);
    
    exit;
}

// Fetch items based on the query string for retaining items after a redirect
if (isset($_GET['selected_items'])) {
    // Check if selected_items is a string or an array
    if (is_string($_GET['selected_items'])) {
        $selected_items = explode(',', $_GET['selected_items']);
    } elseif (is_array($_GET['selected_items'])) {
        $selected_items = $_GET['selected_items'];
    } else {
        $selected_items = []; // Fallback to an empty array
    }

    $items = fetchItems($con, $selected_items);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Today's Menu Quantity</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<?php @include 'header.php' ?>

    <div class="container mx-auto max-w-screen-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Update Quantity for Selected Items</h1>

        <!-- Success message -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p class="text-green-500 text-center mb-4 font-medium">Added to today's menu successfully.</p>
        <?php endif; ?>

        <form action="" method="POST" class="bg-white p-6 rounded-lg shadow-md mb-6">
            <?php
            if (isset($items) && $items->num_rows > 0) {
                while ($item = $items->fetch_assoc()) {
                    echo "<div class='mb-4'>";
                    echo "<label for='quantity_{$item['item_id']}' class='block text-gray-700 font-medium'>{$item['name']} </label>";
                    echo "<input type='hidden' name='item_ids[]' value='{$item['item_id']}'>";
                    echo "<input type='number' placeholder='Enter quantity for {$item['name']}' id='quantity_{$item['item_id']}' name='quantities[]' min='0' required class='w-full mt-1 p-2 border border-gray-300 rounded'>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center text-gray-500'>No items found.</p>";
            }
            ?>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 ">Add to today's menu</button>
        </form>
        <a class="bg-green-500 text-white  rounded-lg p-4 font-bold "href="index.php">Back to Index</a>
    </div>
</body>
</html>

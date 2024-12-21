<?php
require('../connection.php');
function fetchItems($con)
{
    $sql = "SELECT * FROM food_items WHERE availability_status = 'Available'";
    return $con->query($sql);
}

function fetchTodayItems($con)
{
    $today_date = date('Y-m-d');

    // Query to fetch items added today
    $sql = "SELECT * FROM food_items WHERE DATE(added_on) = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $today_date);
    $stmt->execute();
    return $stmt->get_result();
}

if (isset($_POST['remove'])) {
    $item_id = $_POST['item_id'];

    $sql = "UPDATE food_items SET added_on = NULL WHERE item_id = ?";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('i', $item_id);

        if ($stmt->execute()) {
            echo "<p>Item removed successfully!</p>";
            header('Location: index.php');
            exit();
        } else {
            echo "<p>Error removing item: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error preparing the SQL query: " . $con->error . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Items for Today's Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <?php @include 'header.php' ?>

    <div class="container mx-auto px-4 py-6">
        <h1
            class="text-center lg:text-3xl text-xl font-bold text-gray-700 bg-gray-50 p-6 rounded-xl shadow-lg relative overflow-hidden">
            <span class="relative z-10">Select Items for Today's Menu</span>
        </h1>


        <form action="update_todays_menu.php" method="POST" class="bg-white p-6 rounded-lg shadow-lg space-y-4">
            <label for="items" class="block text-lg font-semibold text-gray-700">Select Items:</label>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <?php
                $items = fetchItems($con);
                if ($items->num_rows > 0) {
                    while ($item = $items->fetch_assoc()) {
                        echo "<div class='flex items-center'>";
                        echo "<input type='checkbox' name='selected_items[]' value='{$item['item_id']}' class='mr-2 h-5 w-5'>";
                        echo "<span class='text-lg text-gray-800'>{$item['name']}</span>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-gray-600 text-center'>No items available</p>";
                }
                ?>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="w-full sm:w-auto mt-6 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none">Next</button>
                <a href="edit_menu.php"
                    class="w-full sm:w-auto mt-6 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none text-center">Edit</a>
            </div>
        </form>

    </div>

    <div class="container mx-auto px-4 py-6 ">
        <!-- Display today's menu items -->
        <h1
            class="text-center text-3xl mb-1 font-bold text-gray-700 bg-gray-50 p-6 rounded-xl shadow-lg relative overflow-hidden">
            Today's Menu</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <?php
            $items = fetchTodayItems($con);
            if ($items->num_rows > 0) {
                while ($item = $items->fetch_assoc()) {
                    echo "<div class='bg-white p-6 rounded-lg shadow-md flex flex-col items-center'>";
                    if (!empty($item['image_url'])) {
                        echo "<img src='" . $item['image_url'] . "' alt='" . htmlspecialchars($item['name']) . "' class='w-full h-48 object-cover rounded-md mb-4'>";
                    } else {
                        echo "<img src='uploads/placeholder.jpg' alt='No image available' class='w-full h-48 object-cover rounded-md mb-4'>";
                    }
                    echo "<h2 class='text-xl font-semibold text-gray-800'>{$item['name']}</h2>";
                    echo "<p class='text-gray-600'>Quantity: {$item['quantity']}</p>";
                    echo "<p class='text-gray-600'>Price: Rs {$item['price']}</p>";
                    echo "<hr class='my-4 border-t border-gray-300'>";
                    echo "<form action='' method='POST'>";
                    echo "<input type='hidden' name='item_id' value='{$item['item_id']}'>";
                    echo "<button type='submit' name='remove' class='text-red-500 hover:text-red-700'>Remove</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-gray-600 text-center'>No items available for today</p>";
            }
            ?>
        </div>
    </div>

    <div class="container">
        <h1>Today's Menu</h1>
        <div class="grid-container">
            <?php
            $items = fetchTodayItems($con);
            if ($items->num_rows > 0) {
                while ($item = $items->fetch_assoc()) {
                    echo "<div class='item'>";
                    echo "<strong>Name:</strong> {$item['name']}<br>";
                    echo "<strong>Quantity:</strong> {$item['quantity']}<br>";
                    echo "<strong>Price:</strong> {$item['price']}<br>";
                    echo "</div>";
                }
            } else {
                echo "<p>No items available for today</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>
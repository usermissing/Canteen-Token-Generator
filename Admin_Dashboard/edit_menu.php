<?php
require('../connection.php');

function fetchAllItems($con) {
    $sql = "SELECT * FROM food_items";
    return $con->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $item_id = $_POST['item_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_url = null;  

    $sql = "SELECT image_url FROM food_items WHERE item_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($current_image_url);
    $stmt->fetch();
    $stmt->close();

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Try to upload the file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file;  // New image path
            } else {
                $error_message = "Error uploading the image: " . htmlspecialchars($_FILES['image']['error']);
            }
        } else {
            $error_message = "Invalid file type. Please upload an image (jpg, jpeg, png, gif).";
        }
    } else {
        $image_url = $current_image_url;
    }

    $sql = "UPDATE food_items SET name = ?, price = ?, description = ?, image_url = ? WHERE item_id = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param('sdssi', $name, $price, $description, $image_url, $item_id);

    if ($stmt->execute()) {
        $success_message = "Item updated successfully!";
    } else {
        $error_message = "Error updating item: " . $stmt->error;
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'];

    $sql = "DELETE FROM food_items WHERE item_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $item_id);

    if ($stmt->execute()) {
        $success_message = "Item deleted successfully!";
    } else {
        $error_message = "Error deleting item: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<?php @include 'header.php' ?>

<div class="container mx-auto  p-4 sm:p-6">
    <h1 class="text-center text-2xl sm:text-2xl font-bold text-gray-800 mb-6">Edit Menu Items</h1>

    <?php if (isset($success_message)): ?>
        <p class="text-green-500 text-center mb-4"><?= htmlspecialchars($success_message) ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg">
        <?php
        $items = fetchAllItems($con);
        if ($items->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full  border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 border">Name    </th>
                            <th class="px-4 py-2 border">Price</th>
                            <th class="px-4 py-2 border">Description</th>
                            <th class="px-4 py-2 border">Image</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $items->fetch_assoc()): ?>
                            <tr>
                                <form action="edit_menu.php" method="POST" enctype="multipart/form-data">
                                    <td class="px-2 py-2 border">
                                        <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" class="w-full p-2 border border-gray-300 rounded">
                                    </td>
                                    <td class="px-2 py-2 border">
                                        <input type="number" name="price"  value="<?= htmlspecialchars($item['price']) ?>" class="w-full p-2 border border-gray-300 rounded">
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <textarea name="description" class="w-full p-2 border border-gray-300 rounded"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <?php if (!empty($item['image_url'])): ?>
                                            <!-- <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Item Image" class="w-16 h-16 rounded mb-2">
                                        <?php endif; ?> -->
                                        <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded">
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                                            <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                            <button type="submit" name="update_item" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">Update</button>
                                            <button type="submit" name="delete_item" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-600 text-center">No items available</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
require('../connection.php');
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Redirect to login page if the admin is not logged in
    header("Location: login.php");
    exit();
}
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        } else {
            $error_message = "Error uploading the image: " . htmlspecialchars($_FILES['image']['error']);
        }
    } else {
        $error_message = "No image uploaded or an error occurred.";
    }

    // Check if product name already exists
    $stmt = $con->prepare("SELECT name FROM food_items WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Item already added ";
    } else {
        // Add the menu item only if name doesn't exist
        addMenuItem($con, $name, $price, $image_url, $description);
    }
}

// Function to add a new menu item
function addMenuItem($con, $name, $price, $image_url, $description) {
    $sql = "INSERT INTO food_items (name, price, image_url, description)
            VALUES (?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sdss", $name, $price, $image_url, $description);

    if ($stmt->execute()) {
        global $success_message;
        $success_message = "Menu item added successfully!";
    } else {
        global $error_message;
        $error_message = "Error adding item: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100 font-sans">
    <?php @include "header.php" ?>
    <div class="container mx-auto p-6">
        <h1 class="text-center text-2xl font-bold text-gray-800 mb-6 ">Add New Menu Item</h1>

        <?php if (isset($success_message)): ?>
            <p class="text-green-500 text-center mb-4"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="name" class="block text-lg font-semibold text-gray-700">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter item name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            
            <div class="mb-4">
                <label for="price" class="block text-lg font-semibold text-gray-700">Price</label>
                <input type="number" max="500" id="price" name="price" placeholder="Enter item price" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            
            <div class="mb-4">
                <label for="image" class="block text-lg font-semibold text-gray-700">Image:</label>
                <input type="file" required id="image" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-lg font-semibold text-gray-700">Description:</label>
                <textarea id="description" name="description" placeholder="Enter item description" maxlength="15" rows="4" class="w-full  px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>
            
            <div class="text-center">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Add Item
                </button>
            </div>
        </form>
    </div>
</body>
</html>

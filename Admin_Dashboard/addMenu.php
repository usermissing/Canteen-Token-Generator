<?php
require('../connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    // $available_days = implode(", ", $_POST['available_days']); // Convert array to string
    // $quantity = $_POST['quantity'];
    // $availability_status = $_POST['availability_status'];
    $description = $_POST['description'];

    // Handle image upload
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        } else {
            echo "Error uploading the image.";
        }
    }

    // Function to add a menu item
    function addMenuItem($con, $name, $price,  $image_url, $description) {
        $sql = "INSERT INTO food_items (name, price, image_url, description, added_on)
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sdss", $name, $price,  $image_url, $description);

        if ($stmt->execute()) {
            echo "Menu item added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Add the menu item
    addMenuItem($con, $name, $price, $image_url,  $description);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
</head>
<body>
    <h1>Add New Menu Item</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="price">Price:</label><br>
        <input type="number" step="0.01" id="price" name="price" required><br><br>
        
        <!-- <label for="available_days">Available Days:</label><br>
        <select id="available_days" name="available_days[]" multiple required>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select><br><br> -->
        
        <!-- <label for="quantity">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" required><br><br>
         -->
        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image"><br><br>
        
        <!-- <label for="availability_status">Availability Status:</label><br>
        <select id="availability_status" name="availability_status" required>
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select><br><br> -->
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>
        
        <button type="submit">Add Item</button>
    </form>
</body>
</html>

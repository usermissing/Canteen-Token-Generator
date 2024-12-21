<?php
include 'connection.php';

$sql = "SELECT * FROM food_items"; 
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
           
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            display: flex;
            justify-content: flex-end;
            background-color: #333;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            padding: 8px 16px;
            transition: background-color 0.3s;
        }
        .navbar a:hover {
            background-color: #575757;
            border-radius: 4px;
        }
        .container {
            padding: 40px 20px;
            text-align: center;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .food-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .food-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .food-item img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .food-item-details {
            text-align: center;
        }
        .food-item-details span {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }
        .food-item-details label {
            font-size: 14px;
            color: #333;
        }
        .food-item-details input {
            margin-top: 5px;
            padding: 5px;
            width: 60px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .order-section {
            margin-top: 20px;
        }
        .order-section button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .order-section button:hover {
            background-color: #218838;
        }
    
    </style>
    <script>
        function calculateTotal() {
            const items = document.querySelectorAll('.food-item input');
            let total = 0;
            items.forEach(item => {
                const price = parseFloat(item.dataset.price);
                const quantity = parseInt(item.value) || 0;
                total += price * quantity;
            });
            document.getElementById('total-cost').textContent = `Total Cost: Rs${total.toFixed(2)}`;
        }
    </script>
</head>
<body>

<div class="navbar">
    <a href="#">About Us</a>
    <a href="#">Order Here</a>
    <a href="#">Menu</a>
</div>

<div class="container">
    <h1>Welcome to Our Restaurant</h1>
    <div class="food-selection">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='food-item'>
                        <img src='images/{$row['image_url']}' alt='{$row['name']}'>
                        <div class='food-item-details'>
                            <span><strong>Food Item:</strong> {$row['name']}</span>
                            <span><strong>Price:</strong> Rs{$row['price']}</span>
                            <label for='quantity-{$row['quantity']}'>Quantity:</label>
                            <input type='number' id='quantity-{$row['item_id']}' data-price='{$row['price']}' name='quantity-{$row['item_id']}' min='0' max='10' onchange='calculateTotal()'>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>No food items available.</p>";
        }
        ?>
    </div>
    <div class="order-section">
        <p id="total-cost">Total Cost: Rs0</p>
        <button type="button" onclick="alert('Order Placed!')">Order Now</button>
    </div>
</div>

</body>
</html>

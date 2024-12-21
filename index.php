<?php
include 'connection.php';
$today_day = date('l');

// $sql = "SELECT * FROM food_items "; 
$sql = "SELECT * FROM food_items WHERE availability_status = 'Available' AND FIND_IN_SET('$today_day', available_days)";

// $sql = "SELECT * FROM food_items WHERE availability_status = 'Available' AND available_days = $today_day";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        background-color: #588157;
        padding: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar a {
        color: #d4a373;
        text-decoration: none;
        margin: 0 15px;
        padding: 8px 16px;
        transition: background-color 0.3s;
    }

    .navbar a:hover {
        background-color: #575757;
        border-radius: 4px;
    }
    .navbar img {
            height: 90px;
            width: 90px;
            margin-right: auto;
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

    /* zero quantity modal */
    .modal,
    .invalid-quantity-modal,
    .order-items-modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1001;
        /* Ensure it appears above everything else */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5);
        /* Black background with opacity */
    }

    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: 15% auto;
        /* Center the modal */
        padding: 20px;
        border: 1px solid #888;
        width: 25%;

        /* Adjust as needed */
        border-radius: 10px;
        text-align: center;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    button {
        width: max-content;
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
        document.getElementById('total-cost').textContent = `${total}`;
    }
    </script>
</head>

<body>

    <div class="navbar">
    <img src="/images/logo.png" alt="Logo">
        <a href="index.php">About Us</a>
        
        <a href="#">Menu</a>
        <a href="history.php">History</a>

    </div>

    <div class="container">
        <!-- zero quantity -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <p>Please select at least one item.</p>
                <button class="btn btn-primary" onclick="removeZeroQuantityModal()">Close</button>
            </div>
        </div>
        <!-- invalid quantity -->
        <div id="invalid-quantity-modal" class="invalid-quantity-modal">
            <div class="modal-content">
                <h2>Invalid Quantity</h2>
                <p id="invalid-quantity-body"></p>
                <button class="btn btn-primary" onclick="closeInvalidQuantityModal()">Close</button>
            </div>
        </div>
        <!-- ordered items -->
        <div id="order-items-modal" class="order-items-modal">
            <div class="modal-content">
                <h2>Your Orders</h2>
                <p id="order-items-body"></p>
                <div class="buttons" style="display:flex;">

                    <button class="btn btn-primary" style="margin:0 1rem;" onclick="confirmOrder()">Confim
                        order</button>
                    <button class="btn btn-primary" onclick="closeOrdersItems()">Close</button>
                </div>
            </div>
        </div>


        <h1>Welcome to Our Restaurant</h1>
        <!-- show token queue -->



        <div class="food-selection">
            <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='food-item'>
                        <img src='images/{$row['image_url']}' alt='{$row['name']}'>
                        <div class='food-item-details'>
                            <span><strong>Food Item:</strong> <span class='orderd-items' id='".$row['item_id']."'>{$row['name']}</span></span>
                            <span><strong>Price:</strong> Rs{$row['price']}</span>
                            <div>Remaining Quantity: ".$row['quantity']."</div>
                            <label for='quantity-{$row['quantity']}'>Quantity:</label>
                            <input type='number' id='quantity-{$row['item_id']}' data-price='{$row['price']}' value='0' name='quantity-{$row['item_id']}' min='0' max='10' onchange='calculateTotal()'>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>No food items available.</p>";
        }
        ?>
        </div>
        <div class="order-section">
            Total Rs. <span id="total-cost">0</span>
            <br>
            <button type="button" id="order-now" onclick="checkOrder()">Order Now</button>
        </div>

        <script>
        let displayOrderItems = [];

        function checkOrder() {
            const items = document.querySelectorAll('.food-item input');
            const orderedItems = document.querySelectorAll('.orderd-items');


            const orderData = [];

            items.forEach(item => {
                const quantity = parseInt(item.value) || 0;
                if (quantity > 0) {
                    orderData.push({
                        item_id: item.id.split('-')[1], // Extract ID from input ID
                        quantity: quantity
                    });
                }
            });
            // diaplay before order array
            displayOrderItems = []
            orderData.map((item) => {
                for (let i = 0; i < orderedItems.length; i++) {
                    if (item.item_id == orderedItems[i].id) {
                        let temp = {
                            ...item,
                            name: orderedItems[i].innerText // Use comma, not semicolon
                        };
                        displayOrderItems.push(temp);
                    }
                }
            });


            // if all quantity are zero
            if (orderData.length === 0) {
                document.getElementById('modal').style.display = 'block';
                return;
            }
            // AJAX Request to Backend
            fetch('check_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        order: orderData
                    })
                })
                .then(response => response.json()) // Read response as plain text
                .then(data => {
                    // Check the raw server response

                    try {
                        console.log(data.success);
                        // Attempt to parse JSON
                        if (data.success) {
                            displayOrdersItems()
                            // decreaseStock();

                        } else {
                            let issueMessages = "Available\n";
                            data.issues.forEach(issue => {
                                issueMessages += `${issue.message}\n`;
                            });
                            // Open modal with issues
                            openQuantityModal(issueMessages);

                        }
                    } catch (error) {
                        console.error('Invalid JSON:', error);
                        alert('An error occurred while processing the server response.');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('An error occurred while checking the order.');
                });

            // decrese stock
            function decreaseStock() {
                fetch('decrease_stock.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            order: orderData
                        }) // Send the order data to decrease stock
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Order placed successfully, redirect to confirmation page
                            window.location.href = 'confirm_order.php'; // Redirect to confirmation page
                        } else {
                            alert("Error occurred while processing the stock update.");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while decreasing stock.');
                    });
            }
        }
        // confirm order
        function confirmOrder() {
            const total = document.getElementById("total-cost").innerText;
            const orderData = displayOrderItems.map(item => ({
                item_id: item.item_id,
                name: item.name,
                quantity: item.quantity
            }));

            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'confirmorder.php';

            // Add total as a hidden input
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total';
            totalInput.value = total;
            form.appendChild(totalInput);

            // Add order data as a hidden input (convert to JSON string)
            const orderDataInput = document.createElement('input');
            orderDataInput.type = 'hidden';
            orderDataInput.name = 'orderData';
            orderDataInput.value = JSON.stringify(orderData);
            form.appendChild(orderDataInput);

            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();
        }

        // close zero quanity modal
        function removeZeroQuantityModal() {
            document.getElementById('modal').style.display = 'none';
        }
        // close invalid quantity modal
        function closeInvalidQuantityModal() {
            document.getElementById("invalid-quantity-modal").style.display = "none";
        }
        // quantity not valid
        function openQuantityModal(issueMessages) {
            console.log("invalid quantity", issueMessages);

            document.getElementById("invalid-quantity-body").innerText = issueMessages;
            document.getElementById("invalid-quantity-modal").style.display = "block";
        }
        //displayOrderItems
        function displayOrdersItems() {
            const total = document.getElementById("total-cost").innerText;
            let orderlistmsg = ""
            console.log(displayOrderItems)
            displayOrderItems.map((item) => {
                orderlistmsg += item.name + " - " + item.quantity + "\n";
            })
            orderlistmsg += "Total Rs. " + total;
            document.getElementById("order-items-body").innerText = orderlistmsg;

            document.getElementById("order-items-modal").style.display = "block";

        }

        function closeOrdersItems() {

            document.getElementById("order-items-body").innerText = "";

            document.getElementById("order-items-modal").style.display = "none";

        }
        </script>

    </div>

</body>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
</script>

</html>
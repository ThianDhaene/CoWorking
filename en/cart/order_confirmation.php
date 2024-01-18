<?php
// Start or resume the session
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ietsgents";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve order details from the query parameter
$order_id = $_GET['order_id'] ?? 0;

// Fetch order details
$sql = "SELECT * FROM orders WHERE order_id = $order_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    echo "Order not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styleoc.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
</head>
<body>
    <header>
    </header>
    <main>
    <div class="order">
        <h1><span class="orderconfirmation">O</span><span class="orderconfirmation">r</span><span class="orderconfirmation">d</span><span class="orderconfirmation">e</span><span class="orderconfirmation">r</span><span class="orderconfirmation"> </span><span class="orderconfirmation"> </span><span class="orderconfirmation"> </span><span class="orderconfirmation"> </span> <span class="orderconfirmation">C</span><span class="orderconfirmation">o</span><span class="orderconfirmation">n</span><span class="orderconfirmation">f</span><span class="orderconfirmation">i</span><span class="orderconfirmation">r</span><span class="orderconfirmation">m</span><span class="orderconfirmation">a</span><span class="orderconfirmation">t</span><span class="orderconfirmation">i</span><span class="orderconfirmation">o</span><span class="orderconfirmation">n</span></h1>
        <p class="thanks">Thank you for your order!</p>
        

        <h2>Order Details</h2>
        <p>Order ID: <?php echo $order['order_id']; ?></p>
        <p>Order Date: <?php echo $order['order_date']; ?></p>
        <p>Total Amount: €<?php echo $order['total_amount']; ?></p>
        <p>Status: <?php echo $order['status']; ?></p>

        <h2>Ordered Items</h2>
    </div>
        <?php

        // Fetch order items
        $items_sql = "SELECT * FROM order_items WHERE order_id = $order_id";
        $items_result = $conn->query($items_sql);

        if ($items_result->num_rows > 0) {
            while ($item = $items_result->fetch_assoc()) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                // Fetch product name separately
                $product_name_sql = "SELECT name FROM products WHERE product_id = $product_id";
                $product_name_result = $conn->query($product_name_sql);

                if ($product_name_result && $product_name_result->num_rows > 0) {
                    $product_name = $product_name_result->fetch_assoc()['name'];
                } else {
                    $product_name = "Product Not Found";
                }

                // Display order item details
        ?>
                <div class="order-item">
                    <p>Product: <?php echo $product_name; ?></p>
                    <p>Quantity: <?php echo $quantity; ?></p>
                    <p>Price: €<?php echo $price; ?></p>
                </div>
                <?php
            }
        } else {
            echo "<p>No items in this order.</p>";
        }
        
        ?>
        <img src="../img/logo1_zonder_achtergrond.png" alt="">
    </main>

    <footer>
        <a href="../">Klik hier om terug te gaan</a>
    </footer>
</body>
</html>
<?php
$conn->close();
?>

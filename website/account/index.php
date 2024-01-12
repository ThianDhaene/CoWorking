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

// Retrieve user information from the session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Fetch orders for the logged-in user
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$result = $conn->query($sql);

// You can use this information to fetch more details from the database if needed

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ietsgents | about</title>
    <link rel="icon" href="../img/logo2.png" />
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="../login/style.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <header class=>
        <a href="../">
        <img src="../img/logo2_zonder_achtergrond.png" class="logo" alt="">
        </a>
        <nav>
        <ul>
            <li><a href="../" data-text="HOME">Home</a></li>
            <li><a href="../about" data-text="ABOUT">About</a></li>
            <li><a href="../shop" data-text="SHOP">Shop</a></li>
            <li><a href="../contact" data-text="CONTACT">Contact</a></li>
        </ul>
        </nav>
        <ul>
        <li><a href="../login"><img src="../img/account.webp" class="account" alt=""></a></li>
        <li><a href="../cart"><img src="../img/winkelmandje.webp" class="cart" alt=""></a></li>
        </ul>
    </header>

    <main>
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <div class="userinfo">
            <p>Your account information:</p>
            <ul>
                <li>Username: <?php echo $username; ?></li>
                <li>Email: <?php echo $email; ?></li>
                <!-- Add more details as needed -->
            </ul>
        </div>
        <?php
        // Check if there are orders
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $order_id = $row['order_id'];
                $order_date = $row['order_date'];
                $total_amount = $row['total_amount'];
                $status = $row['status'];

                // Fetch order items
                $items_sql = "SELECT * FROM order_items WHERE order_id = $order_id";
                $items_result = $conn->query($items_sql);
        ?>

                <div class="order">
                    <p>Order ID: <?php echo $order_id; ?></p>
                    <p>Order Date: <?php echo $order_date; ?></p>
                    <p>Total Amount: <?php echo $total_amount; ?></p>
                    <p>Status: <?php echo $status; ?></p>

                    <?php
                    // Check if there are order items
                    if ($items_result->num_rows > 0) {
                        while ($item = $items_result->fetch_assoc()) {
                            $product_id = $item['product_id'];
                            $quantity = $item['quantity'];
                            $price = $item['price'];

                            // Fetch product details
                            $product_sql = "SELECT * FROM products WHERE product_id = $product_id";
                            $product_result = $conn->query($product_sql);
                            $product = $product_result->fetch_assoc();
                    ?>

                            <div class="order-item">
                                <p>Product: <?php echo $product['product_name']; ?></p>
                                <p>Quantity: <?php echo $quantity; ?></p>
                                <p>Price: <?php echo $price; ?></p>
                            </div>

                    <?php
                        }
                    } else {
                        echo "<p>No items in this order.</p>";
                    }
                    ?>
                </div>

        <?php
            }
        } else {
            echo "<p>You have no orders yet.</p>";
        }
        ?>


    </main>

</body>
</html>

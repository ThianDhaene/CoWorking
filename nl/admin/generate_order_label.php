<?php

// Include your database connection and any necessary functions
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ietsgents";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Retrieve order details based on $_GET['order_id']
$order_id = $_GET['order_id'];


// retrieve order details from the database using $_GET['order_id']
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Fetch order details from the database
    // Replace the following lines with your actual database query
    $order_sql = "SELECT * FROM orders WHERE order_id = $orderId";
    $order_result = $conn->query($order_sql);

    if ($order_result && $order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        $street = $order['street'];
        $number = $order['number'];
        $city = $order['city'];
        $zipcode = $order['postal_code'];
        $country = $order['country'];
        $userId = $order['user_id'];

        // Fetch user details from the database
        // Assuming your users table has a column named 'username'
        $user_sql = "SELECT username FROM users WHERE user_id = $userId";
        $user_result = $conn->query($user_sql);

        if ($user_result && $user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            $userName = $user['username'];

            // Now you have the required information to generate the order label
            // ...

        } else {
            echo "Error: User with ID $userId not found.";
        }
    } else {
        echo "Error: Order with ID $orderId not found.";
    }
} else {
    echo "Error: Order ID not provided.";
}

$imagePath = '../img/logo1_zonder_achtergrond.png';?>

<link rel="stylesheet" href="style.css" />
<div class="orderlabel">
    <?php echo "<img src='$imagePath' alt='Order Image'>"; ?>
    <div class="orderlabelinfo">
        <?php
        echo "<h2>Order for $userName</h2>";
        echo "<p>Order  #$order_id</p>";
        echo "<p>Shipping Address: $street $number, $zipcode $city, $country</p>";
        ?>
    </div>
</div>

<?php
// Output the order label content

// Display other order details
// ...

// You may include CSS styles to format the printed content
echo '<style>
    /* Your CSS styles for printing go here */
    body {
        font-family: Arial, sans-serif;
    }
    /* Additional styles */
</style>';
?>

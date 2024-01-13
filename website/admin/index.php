<?php
// Check if the admin is logged in
session_start();

// Check if the user is not an admin, redirect to the login page
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: ../login");
    exit();
}

// Check if the logout button is clicked
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to the login page after logout
    header("Location: ../login");
    exit();
}

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
// Fetch orders from the database
$orders_sql = "SELECT * FROM orders";
$orders_result = $conn->query($orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ietsgents | Admin</title>
    <link rel="icon" href="../img/logo2.png" />
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="submit" name="logout" value="Logout">
        </form>
    </header>
    <main>
        <h1>Admin Dashboard</h1>

        <!-- Display Orders -->
        <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Username</th> <!-- Add this line for username -->
                <th>User Email</th> <!-- Add this line for user email -->
                <th>Total Amount</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Products</th>
                <th>Street</th>
                <th>Number</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Country</th>
                <th>Extra Info</th>
                <th>Change Status</th>
            </tr>
        </thead>
        <tbody>
            <hr>
            <?php
            while ($order = $orders_result->fetch_assoc()) {
                $order_id = $order['order_id'];
                $user_id = $order['user_id'];

                // Fetch user details for this order
                $user_sql = "SELECT * FROM users WHERE user_id = $user_id";
                $user_result = $conn->query($user_sql);
                $user = ($user_result->num_rows > 0) ? $user_result->fetch_assoc() : null;
                ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $user_id; ?></td>
                    <td><?php echo ($user) ? $user['username'] : 'N/A'; ?></td> <!-- Display username -->
                    <td><?php echo ($user) ? $user['email'] : 'N/A'; ?></td> <!-- Display user email -->
                    <td><?php echo $order['total_amount']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <!-- Fetch and display products for this order -->
                        <?php
                        $products_sql = "SELECT * FROM order_items JOIN products ON order_items.product_id = products.product_id WHERE order_id = $order_id";
                        $products_result = $conn->query($products_sql);

                        while ($product = $products_result->fetch_assoc()) {
                            echo $product['name'] . " x " . $product['quantity'] . "<br>";
                        }
                        ?>
                    </td>
                    <!-- Display Address Information -->
                    <td><?php echo $order['street']; ?></td>
                    <td><?php echo $order['number']; ?></td>
                    <td><?php echo $order['city']; ?></td>
                    <td><?php echo $order['postal_code']; ?></td>
                    <td><?php echo $order['country']; ?></td>
                    <td><?php echo $order['extra_info']; ?></td>
                    <td>
                        <!-- Form to change order status -->
                        <form method="post" action="update_order_status.php">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <select name="new_status">
                                <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Shipped" <?php echo ($order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                <option value="Delivered" <?php echo ($order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                <!-- Add more status options as needed -->
                            </select>
                            <input type="submit" name="update_status" value="Update">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </main>
    <footer>
        
    </footer>
    </body>
</html>

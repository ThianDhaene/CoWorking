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

// Handle adding products to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Check if the product is already in the cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Product already in the cart, update quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Product not in the cart, add it
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Handle the checkout process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
    // Insert order details into the database
    $user_id = $_SESSION['user_id'];
    $total_amount = calculateTotalAmount(); // Implement this function to calculate the total amount

    $order_sql = "INSERT INTO orders (user_id, total_amount, order_date, status) VALUES ('$user_id', '$total_amount', NOW(), 'Pending')";
    if ($conn->query($order_sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Insert order items into the database
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product_sql = "SELECT * FROM products WHERE product_id = $product_id";
            $product_result = $conn->query($product_sql);

            // Check if the product with the given ID exists
            if ($product_result && $product_result->num_rows > 0) {
                $product = $product_result->fetch_assoc();
                $price = $product['price'];

                $order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')";
                $conn->query($order_item_sql);
            } else {
                echo "Error: Product with ID $product_id not found.";
                // Handle this situation as needed (e.g., remove the item from the cart)
            }
        }

        // Clear the cart after placing the order
        unset($_SESSION['cart']);

        // Redirect to the order confirmation page
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
    } else {
        echo "Error creating order: " . $conn->error;
    }
}


// Function to calculate the total amount in the cart
function calculateTotalAmount() {
    // Implement your logic to calculate the total amount based on the products in the cart
    return 0; // Replace with your actual calculation
}

// Fetch products from the database
$product_sql = "SELECT * FROM products";
$product_result = $conn->query($product_sql);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place an Order</title>
    <link rel="stylesheet" href="path/to/your/css/style.css">
</head>
<body>
    <header>
        <!-- Your header content goes here -->
    </header>

    <main>
        <h1>Place an Order</h1>

        <!-- Product Listing -->
        <ul>
            <?php while ($product = $product_result->fetch_assoc()) { ?>
                <li>
                    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <label><?php echo $product['name']; ?> - $<?php echo $product['price']; ?></label>
                        <input type="number" name="quantity" value="1" min="1" required>
                        <input type="submit" name="add_to_cart" value="Add to Cart">
                    </form>
                </li>
            <?php } ?>
        </ul>

        <!-- View Cart -->
        <h2>Your Shopping Cart</h2>
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $product_id => $quantity) { ?>
                    <li>
                        <?php echo "Product ID: $product_id, Quantity: $quantity"; ?>
                    </li>
                <?php } ?>
            </ul>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <input type="submit" name="checkout" value="Checkout">
            </form>
        <?php } else { ?>
            <p>Your shopping cart is empty.</p>
        <?php } ?>
    </main>

    <footer>
        <!-- Your footer content goes here -->
    </footer>
</body>
</html>
<?php
$conn->close();
?>

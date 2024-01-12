<?php
// Start or resume the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ietsgents";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding a test product to the cart
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
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Page - Add to Cart</title>
    <link rel="stylesheet" href="path/to/your/css/style.css">
</head>
<body>
    <header>
        <!-- Your header content goes here -->
    </header>

    <main>
        <h1>Test Page - Add to Cart</h1>

        <!-- Form to add a test product to the cart -->
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="product_id">Product ID:</label>
            <input type="text" name="product_id" required>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" required>
            <br>
            <input type="submit" name="add_to_cart" value="Add to Cart">
        </form>

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

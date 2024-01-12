<?php
// Start or resume the session
session_start();

// Check if the user is not logged in, redirect to the login page with a message
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login?message=login_required");
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
    $street = $_POST['street']; // Get the street from the form
    $number = $_POST['number']; // Get the number from the form
    $city = $_POST['city']; // Get the city from the form
    $postal_code = $_POST['postal_code']; // Get the postal code from the form
    $country = $_POST['country']; // Get the country from the form
    $extra_info = $_POST['extra_info']; // Get the extra information from the form

    $order_sql = "INSERT INTO orders (user_id, total_amount, order_date, status, street, number, city, postal_code, country, extra_info) 
                  VALUES ('$user_id', '$total_amount', NOW(), 'Pending', '$street', '$number', '$city', '$postal_code', '$country', '$extra_info')";
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

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  // Fetch user address from the database
  $address_sql = "SELECT street, number, city, postal_code, country, extra_info FROM orders WHERE user_id = $user_id ORDER BY order_date DESC LIMIT 1";
  $address_result = $conn->query($address_sql);

  if ($address_result && $address_result->num_rows > 0) {
      $address = $address_result->fetch_assoc();
      $street = $address['street'];
      $number = $address['number'];
      $city = $address['city'];
      $postal_code = $address['postal_code'];
      $country = $address['country'];
      $extra_info = $address['extra_info'];
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
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ietsgents | about</title>
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
    <section class="section-inleiding">
      <h1><span class="about">C</span><span class="about">A</span><span class="about">R</span><span class="about">T</span></h1>
    </section>
    <div class="cart-container">
        <h2>Shopping Cart</h2>
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    // Fetch product details
                    $product_sql = "SELECT * FROM products WHERE product_id = $product_id";
                    $product_result = $conn->query($product_sql);
                    if ($product_result && $product_result->num_rows > 0) {
                        $product = $product_result->fetch_assoc();
                        $product_name = $product['name'];
                        ?>
                        <li>
                          <b><?php echo "Product: $product_name, Quantity: $quantity"; ?></b>
                        </li>
                    <?php
                    } else {
                        // Handle the situation where the product is not found
                        echo "Error: Product with ID $product_id not found.";
                    }
                } ?>
            </ul>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <label for="street">Street:</label>
                    <input type="text" name="street" value="<?php echo $street; ?>" required>
                    <br>
                    <label for="number">Number:</label>
                    <input type="text" name="number" value="<?php echo $number; ?>" required>
                    <br>
                    <label for="city">City:</label>
                    <input type="text" name="city" value="<?php echo $city; ?>" required>
                    <br>
                    <label for="postal_code">Postal Code:</label>
                    <input type="text" name="postal_code" value="<?php echo $postal_code; ?>" required>
                    <br>
                    <label for="country">Country:</label>
                    <input type="text" name="country" value="<?php echo $country; ?>" required>
                    <br>
                    <label for="extra_info">Extra Information:</label>
                    <textarea name="extra_info"><?php echo $extra_info; ?></textarea>
                    <br>
                    <input type="submit" name="checkout" value="Checkout">
                </form>

        <?php } else { ?>
            <p>Your shopping cart is empty.</p>
        <?php } ?>
    </div>
  </main>
  

  <footer>
    <div class="footer-p">
      <p>&copy; ietsgents 2023</p>
    </div>
    <nav class="socials">
      <ul>
        <li>
          <a href="https://www.instagram.com/ietsgents/" target="_blank"
            ><img src="../img/instagram_logo.png" alt=""
          /></a>
        </li>
      </ul>
    </nav>
  </footer>

  <script src="cart.js" defer></script>
</body>
</html>

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

// Handle adding a test product (product ID 1) to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
  $product_id = 1; // Set the product ID to 1
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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ietsgent | shop</title>
    <link rel="icon" href="../../img/logo2.png" />
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../about/style.css" />
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="../about/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <header>
      <a href="./">
        <img src="../../img/logo2_zonder_achtergrond.png" class="logo" alt="">
      </a>
      <nav>
        <ul>
          <li><a href="../../" data-text="HOME">Home</a></li>
          <li><a href="../../about" data-text="ABOUT">About</a></li>
          <li><a href="../../shop" data-text="SHOP">Shop</a></li>
          <li><a href="../../contact" data-text="CONTACT">Contact</a></li> 
        </ul>
      </nav>
      <ul>
        <li><a href="../../login"><img src="../../img/account.webp" class="account" alt="" ></a></li>
        <li><a href="../../cart"><img src="../../img/winkelmandje.webp" class="cart" alt=""></a></li>
       </ul>
    </header>
    <main>
      <div class="product-container">
        <h2>ietsgents Hoodie</h2>
        <img src="hoodie.png" alt="Product Image">
        <p>Introducing the ietsgents Hoodie where simplicity meets style. Crafted with precision, this hoodie embodies clean lines and a sleek design, making it the perfect wardrobe essential for those who appreciate understated elegance. Elevate your casual look with the ietsgents Minimalistic Hoodie and embrace the essence of modern minimalism.</p>
        <p>Price: $19.99</p>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" required>
            <br>
            <input type="submit" name="add_to_cart" value="Add to Cart">
        </form>

    </div>
    
  </main>
  <footer>
    
  </footer>
  <script src="main.js"></script>
  </body>
</html>
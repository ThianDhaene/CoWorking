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

  // Redirect to the cart page
  header("Location: ../../cart");
  exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_review"])) {
  // Check if the user is logged in
  if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or show an error message
    header("Location: ../../login/index.php?message=login_required");
    exit();
  }

  $user_id = $_SESSION['user_id'];
  $product_id = 1;
  $rating = $_POST["rating"];
  $comment = $_POST["comment"];

  // Insert the review into the database
  $review_sql = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES ('$user_id', '$product_id', '$rating', '$comment')";

  if ($conn->query($review_sql) === TRUE) {
      // Redirect or show a success message
      header("Location: index.php?product_id=$product_id");
      exit();
  } else {
      echo "Error submitting review: " . $conn->error;
  }
}

function getReviews($conn, $product_id) {
  $reviews = array();
  $review_sql = "SELECT * FROM reviews WHERE product_id = $product_id";
  $result = $conn->query($review_sql);

  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $reviews[] = $row;
      }
  }

  return $reviews;
}

?>

<!DOCTYPE html>
<html lang="eng">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ietsgent | shop</title>
    <link rel="icon" href="../../img/logo2.png" />
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../style.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet"/>
  </head>
  <body>
    <header class="container" >
      <a href="./../../">
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
    <main class="container">
      <div class="product-container">
        <div class="left-column">
          <img src="hoodie.png" alt="Product Image" class="product-image">
        </div>
        <div class="right-column">
          <h2 class="product-title"><span class="about">i</span><span class="about">e</span><span class="about">t</span><span class="about">s</span><span class="about">g</span><span class="about">e</span><span class="about">n</span><span class="about">t</span><span class="about">s</span><span class="about"> h</span><span class="about">o</span><span class="about">o</span><span class="about">d</span><span class="about">i</span><span class="about">e</span></h2>
          <p class="product-price">Price: $19.99</p>
          
          <p class="product-description">Introducing the ietsgents Hoodie where simplicity meets style. Crafted with precision, this hoodie embodies clean lines and a sleek design, making it the perfect wardrobe essential for those who appreciate understated elegance. Elevate your casual look with the ietsgents Minimalistic Hoodie and embrace the essence of modern minimalism.</p>
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="add-to-cart-form">
              <label for="quantity" class="quantity-label">Quantity:</label>
              <input type="number" name="quantity" value="1" min="1" required class="quantity-input">
              <br>
              <input type="submit" name="add_to_cart" value="Add to Cart" class="add-to-cart-button">
          </form>

          <?php
            // $reviews = getReviews($conn, $product_id);
            // // Display average rating
            // $averageRating = 0;
            // if (!empty($reviews)) {
            //     $totalRating = array_reduce($reviews, function ($carry, $review) {
            //         return $carry + $review['rating'];
            //     }, 0);
            //     $averageRating = $totalRating / count($reviews);
            // }

            // echo "Average Rating: " . number_format($averageRating, 1);

            // // Display individual reviews
            // foreach ($reviews as $review) {
            //     echo "<div>";
            //     echo "Rating: " . $review['rating'] . "<br>";
            //     echo "Comment: " . $review['comment'] . "<br>";
            //     // You can display other information like username, date, etc.
            //     echo "</div>";
            // }
          ?>


          <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
              <label for="rating">Rating:</label>
              <select name="rating">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="2">3</option>
                  <option value="2">4</option>
                  <option value="5">5</option>
              </select>
              <br>
              <label for="comment">Comment:</label>
              <textarea name="comment"></textarea>
              <br>
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
              <input type="submit" name="submit_review" value="Submit Review">
          </form>

        </div>
      </div>
    
  </main>
  <footer>
    
  </footer>
  <script src="main.js"></script>
  </body>
</html>
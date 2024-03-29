<?php
//Change product id here
$product_id = 3; 

// Start or resume the session
session_start();

// Database connection
$servername = "localhost";
$username = "lukas.ietsgents";
$password = "ietsgents.ww";
$dbname = "db_ietsgents";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handles adding a product to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
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



// Getting user reviews
$reviews_sql = "SELECT * FROM reviews WHERE product_id = $product_id";
$reviews_result = $conn->query($reviews_sql);

// Check if there are reviews for the product
if ($reviews_result && $reviews_result->num_rows > 0) {
    // Fetch and store reviews in an array
    $reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);
} else {
    // No reviews found
    $reviews = array();
}
function getReviewsWithUsernames($conn, $product_id) {
  $reviews = array();
  $review_sql = "SELECT reviews.*, users.username FROM reviews 
                 JOIN users ON reviews.user_id = users.user_id 
                 WHERE reviews.product_id = $product_id";
  $result = $conn->query($review_sql);

  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $reviews[] = $row;
      }
  }

  return $reviews;
}

// Function to generate star icons based on the rating
function generateStars($rating) {
  $fullStar = '★';
  $emptyStar = '☆';

  $stars = '';
  for ($i = 1; $i <= 5; $i++) {
      if ($i <= $rating) {
          $stars .= $fullStar;
      } else {
          $stars .= $emptyStar;
      }
  }

  return $stars;
}
// Function to calculate average rating
function calculateAverageRating($reviews) {
  $totalRating = 0;
  foreach ($reviews as $review) {
      $totalRating += $review['rating'];
  }

  // Avoid division by zero
  $averageRating = count($reviews) > 0 ? $totalRating / count($reviews) : 0;

  return round($averageRating, 1); // Round to 1 decimal place
}

$reviews = getReviewsWithUsernames($conn, $product_id);
$averageRating = calculateAverageRating($reviews);
//End of getting reviews

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_review"])) {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to the login page or show an error message
        header("Location: ../../login/index.php?message=login_required");
        exit();
    }

    $user_id = $_SESSION['user_id'];
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


?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ietsgent | shop</title>
    <link rel="icon" href="../../img/logo2.png" />
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../style.css" />
    <link rel="stylesheet" href="../productstyle.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet" />
</head>

<body>
    <header class="container">
        <a href="./../../">
            <img src="../../img/logo2_zonder_achtergrond.png" class="logo" alt="ietsgents">
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
        <li><a href="../../login"><img src="../../img/account.webp" class="account" alt="login"></a></li>
            <li><a href="../../cart"><img src="../../img/winkelmandje.webp" class="cart" alt="winkelkar"></a></li>
        </ul>
    </header>
    <main class="container">
        <div class="product-container">
            <div class="left-column">
                <img src="img.png" alt="Product Image" class="product-image">
            </div>
            <div class="right-column">
                <h2 class="product-title"><span class="about">i</span><span class="about">e</span><span class="about">t</span><span class="about">s</span><span class="about">g</span><span class="about">e</span><span class="about">n</span><span class="about">t</span><span class="about">s</span> <span class="about">t</span><span class="about">o</span><span class="about">t</span><span class="about">e</span><span class="about">b</span><span class="about">a</span><span class="about">g</span>
</h2>
                <div class="average-rating">
                          <p><?php echo generateStars($averageRating); ?></p>
                </div>
                <span class="product-price" data-original-price="25.00">9.99 EUR</span>

                <p class="product-description">Maak kennis met de ietsgents Minimalistic Tote Bag - een samensmelting van vorm en functie. Ongecompliceerd en moeiteloos stijlvol, combineert deze tas eenvoud met veelzijdigheid. Draag je benodigdheden in tijdloze stijl met de ietsgents Minimalistic Tote Bag, een perfecte accessoire voor de moderne minimalist.</p>
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="add-to-cart-form">
                  <label for="quantity" class="quantity-label">Aantal:</label>
                  <input type="number" name="quantity" id="quantity" value="1" min="1" required="" class="quantity-input">
                  <br>
                  <input type="submit" name="add_to_cart" value="Voeg toe aan winkelmandje" class="add-to-cart-button">
                </form>
              </div>
            </div>
            <div class="reviews">
              <div class="add-review">
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <label for="rating">Beoordeling</label>
                    <div class="rating">
                        <?php
                        // Loop to generate 5 stars
                        for ($i = 1; $i <= 5; $i++) {
                            echo "<span class='star' data-rating='$i'>&#9733;</span>";
                        }
                        ?>
                    </div>
                    <input type="hidden" name="rating" id="selected-rating" value="0" required>
                    <br>
                    <label for="comment">Recensie:</label><br>
                    <textarea name="comment" id="comment" required=""></textarea>
                    <br>
                    <input type="submit" name="submit_review" value="Verstuur Recensie">
                </form>
              </div>
              <div class="reviews-container">
                <p><strong>Wat anderen van dit product vinden:</strong></p>
                  <?php
                  if (empty($reviews)) {
                      echo "<p>Geen beoordelingen beschikbaar voor dit product.</p>";
                  } else {
                      // Loop through reviews and display them
                      foreach ($reviews as $review):
                  ?>
                          <div class="review">
                              <p><strong><?php echo $review['username'];?></strong></p>
                              <p><strong>Beoordeling:</strong> <?php echo generateStars($review['rating']); ?></p>
                              <p><strong>Recensie:</strong> <?php echo $review['comment']; ?></p>
                          </div>
                          <!-- Display the average rating -->
                      
                  <?php endforeach;
                  
                  }
                  
                  ?>
              </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <script src="../stars.js"></script>
</body>

</html>

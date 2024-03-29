<?php
// Start or resume the session
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login");
    exit();
}

// Database connection
$servername = "localhost";
$username = "lukas.ietsgents";
$password = "ietsgents.ww";
$dbname = "db_ietsgents";

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

// Fetch user address from the database
$address_sql = "SELECT street, number, city, postal_code, country, extra_info FROM orders WHERE user_id = $user_id ORDER BY order_date DESC LIMIT 1";
$address_result = $conn->query($address_sql);

// Check if address information exists
if ($address_result && $address_result->num_rows > 0) {
    $address = $address_result->fetch_assoc();
    $street = $address['street'];
    $number = $address['number'];
    $city = $address['city'];
    $zipcode = $address['postal_code'];
    $country = $address['country'];
    $extra_info = $address['extra_info'];
} else {
    // Set default values or handle as needed
    $street = $number = $city = $zipcode = $country = $extra_info = "";
}

// You can use this information to fetch more details from the database if needed


// Check if the logout button is clicked
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to the login page after logout
    header("Location: ../login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ietsgents | account</title>
    <link rel="icon" href="../img/logo2.png" />
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <header>
            <a href="../">
            <img src="../img/logo2_zonder_achtergrond.png" class="logo" alt="ietsgents">
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
            <li><a href="../login"><img src="../img/account.webp" class="account" alt="login"></a></li>
            <li><a href="../cart"><img src="../img/winkelmandje.webp" class="cart" alt="winkelkar"></a></li>
            </ul>
        </header>
    </div>
    <main>
    <script>
      window.embeddedChatbotConfig = {
      chatbotId: "VcTyy6DJxJweJpURmub1b",
      domain: "www.chatbase.co"
      }
      </script>
      <script
      src="https://www.chatbase.co/embed.min.js"
      chatbotId="VcTyy6DJxJweJpURmub1b"
      domain="www.chatbase.co"
      defer>
      </script>
        <div class="container">
        <?php 
        //Get adres
        if ($address_result && $address_result->num_rows > 0) {
            $address = $address_result->fetch_assoc();}
        ?>
        <h1>Welkom, <?php echo $username; ?>!</h1>
        <div class="userinfo">
            <p class="gebruiker">Jouw accountinformatie.</p>
            <ul>
                <li>Gebruikersnaam: <?php echo $username; ?></li>
                <li>Email: <?php echo $email; ?></li>
                <!-- Add more details as needed -->
            </ul>
            <p class="adres">Uw adres:</p>
                <ul>
                    <li>Straat: <?php echo $street; ?></li>
                    <li>Huisnummer: <?php echo $number; ?></li>
                    <li>Stad: <?php echo $city; ?></li>
                    <li>Postcode: <?php echo $zipcode; ?></li>
                    <li>Land: <?php echo $country; ?></li>
                    <?php if (!empty($address['extra_info'])) { ?>
                        <li>Extra Informatie: <?php echo $address['extra_info']; ?></li>
                    <?php } ?>
                </ul>
        </div>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="submit" name="logout" value="Uitloggen">
        </form>
        <h1>Uw bestellingen</h1>
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
                    <p>Bestel-ID: <?php echo $order_id; ?></p>
                    <p>Besteldatum: <?php echo $order_date; ?></p>
                    <p>Totaal Bedrag: €<?php echo $total_amount; ?></p>
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

                            // Fetch product name separately
                            $product_name_sql = "SELECT name FROM products WHERE product_id = $product_id";
                            $product_name_result = $conn->query($product_name_sql);

                            if ($product_name_result && $product_name_result->num_rows > 0) {
                                $product_name = $product_name_result->fetch_assoc()['name'];
                            } else {
                                $product_name = "Product Niet Gevonden";
                            }
                    ?>

                            <div class="order-item">
                                <hr>
                                <p>Product: <?php echo $product['name']; ?></p>
                                <p>Aantal: <?php echo $quantity; ?></p>
                                <p>Prijs: €<?php echo $price; ?></p>
                            </div>

                    <?php
                        }
                    } else {
                        echo "<p>Geen items in deze bestelling.</p>";
                    }
                    ?>
                </div>
                

        <?php
            }
        } else {
            echo "<p>U heeft nog geen bestellingen.</p>";
        }
        ?>
        </div>

    </main>

</body>
</html>
<?php
$conn->close();
?>
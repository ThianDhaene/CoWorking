<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ietsgents";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to securely hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to verify password against hashed password
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

function debug_to_console($data) {
  $output = $data;
  if (is_array($output))
      $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// Start or resume the session
session_start();

// Check for the login_required message
$message = isset($_GET['message']) ? $_GET['message'] : '';


// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  // Redirect to the account page
  header("Location: ../account");
  exit();
}

// User login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (verifyPassword($password, $row["password_hash"])) {
            // Start a session (if not started already)
            session_start();

            // Store user information in session (you can store more information as needed)
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Redirect to the account page
            header("Location: ../account");
            exit();

            debug_to_console("Login successful!");
        } else {
          debug_to_console("Incorrect password");
        }
    } else {
        debug_to_console("User not found!");
    }
}

$conn->close();
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
  <link rel="stylesheet" href="../about/style.css" />
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
    <div class="containertje">
        <h1><span class="about">L</span><span class="about">O</span><span class="about">G</span><span class="about">I</span><span class="about">N</span></h1>
    </div>
    <div class="container">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="username"><b>Username:</b></label>
        <input type="text" name="username" placeholder="Enter username" required>
          <?php
          // Display error message for user not found after form submission
          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
              if ($result->num_rows == 0) {
                  echo "<p style='color: #b01605;'>User not found!</p>";
              }
          }
          ?>
        <br>

        <label for="password"><b>Password:</b></label>
        <input type="password" name="password" placeholder="Enter password" required>
          <?php
          // Display error message for incorrect password after form submission
          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
              if ($result->num_rows > 0 && !verifyPassword($password, $row["password_hash"])) {
                  echo "<p style='color: #b01605;'>Incorrect password!</p>";
              }
          }
          ?>
        <br>

        <input type="submit" name="login" value="Login">
          <?php
              // Display a message if login is required
              if ($message === 'login_required') {
                echo "<p style='color: #b01605;'>Please login first.</p>";
              }
              // Display success message after successful login and no errors
              if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"]) && !($result->num_rows == 0) && !(($result->num_rows > 0 && !verifyPassword($password, $row["password_hash"])))) {
                  echo "<p style='color: green;'>Login successful!</p>";
              }
          ?>

        </form>
    </div>
    <div class="container" style="background-color: transparent">
          <span class="psw"><a href="../register">Create an account</a></span>
    </div>
  </main>
  </body>
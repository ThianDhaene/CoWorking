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

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  // Redirect to the account page
  header("Location: ../account");
  exit();
}

// User registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
  $username = $_POST["username"];
  $password = hashPassword($_POST["password"]);
  $email = $_POST["email"];

  // Check if the username is already taken
  $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username'";
  $checkUsernameResult = $conn->query($checkUsernameQuery);

  // Check if the email is already in use
  $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
  $checkEmailResult = $conn->query($checkEmailQuery);

  if ($checkUsernameResult->num_rows > 0) {
    debug_to_console("Username already in use!");
  } elseif ($checkEmailResult->num_rows > 0) {
    debug_to_console("Email already in use!");
  } else {
      // If username and email are unique, proceed with registration
      $sql = "INSERT INTO users (username, password_hash, email) VALUES ('$username', '$password', '$email')";

      if ($conn->query($sql) === TRUE) {
            // Start a session (if not started already)
            session_start();

            // Store user information in session (you can store more information as needed)
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Redirect to the account page
            header("Location: ../account");
            exit();
        debug_to_console("Registration successful!");
      } else {
        debug_to_console("Error");
      }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ietsgents | about</title>
  <link rel="icon" href="../img/logo2.png" />
  <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
  <link rel="stylesheet" href="../about/style.css" />
  <link rel="stylesheet" href="../register/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
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
      <li><a href="../cart"><img src="../img/winkelmandje.webp" class="cart" alt="cart"></a></li>
    </ul>
  </header>
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
    <div class="containertje">
    <h1><span class="about">R</span><span class="about">E</span><span class="about">G</span><span class="about">I</span><span class="about">S</span><span class="about">T</span><span class="about">E</span><span class="about">R</span></h1>
    </div>

    <div class="container">
      <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
          <label for="username"><b>Username:</b></label>
          <input type="text" name="username" placeholder="Enter username" required>
            <?php
            // Display error message for invalid username after form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
                if (isset($checkUsernameResult) && $checkUsernameResult->num_rows > 0) {
                    echo "<p style='color: red;'>Username has already been taken. Please choose another one.</p>";
                }
            }
            ?>
          <br>
  
          <label for="password"><b>Password:</b></label>
          <input type="password" name="password" placeholder="Enter password" required>
          <br>
  
          <label for="email"><b>Email:</b></label>
          <input type="email" name="email" placeholder="Enter email" required>
            <?php
            // Display error message for invalid email after form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
                if (isset($checkEmailResult) && $checkEmailResult->num_rows > 0) {
                    echo "<p style='color: red;'>Email is already in use. Please use a different email address.</p>";
                }
            }
            ?>
          <br>
  
          <input type="submit" name="register" value="Register">
            <?php
            // Display success message after successful registration and no errors
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"]) && !($checkEmailResult->num_rows > 0) && !($checkUsernameResult->num_rows > 0)) {
                echo "<p style='color: green;'>Registration successful!</p>";
            }
            ?>
      </form>
    </div>
    <div class="container" style="background-color: transparent">
      <span class="psw"><a href="../login">Do you already have an account?</a></span>
    </div>
  </main>
  </body>
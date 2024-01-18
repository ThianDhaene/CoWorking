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

    // Check if the entered credentials match the admin credentials
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'admin') {
      // Admin login successful
      // You might want to set a session variable or take any specific action here
      $_SESSION['is_admin'] = true;
      header("Location: ../admin");
      exit();
    } else {
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

    
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ietsgents | Login</title>
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
  </div>
  <main>
    <div class="containertje">
<<<<<<< Updated upstream
      <h1><span class="login">L</span><span class="login">O</span><span class="login">G</span><span class="login">I</span><span class="login">N</span></h1>
=======
        <h1><span class="about">A</span><span class="about">A</span><span class="about">N</span><span class="about">M</span><span class="about">E</span><span class="about">L</span><span class="about">D</span><span class="about">E</span><span class="about">N</span></h1>
>>>>>>> Stashed changes
    </div>
    <div class="container logincontainer">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="username"><b>Gebruikersnaam:</b></label>
        <input type="text" name="username" placeholder="Voer gebruikersnaam in" required>
          <?php
          // Display error message for user not found after form submission
          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
              if ($result->num_rows == 0) {
                  echo "<p style='color: #b01605;'>Gebruiker niet gevonden!</p>";
              }
          }
          ?>
        <br>

        <label for="password"><b>Wachtwoord:</b></label>
        <input type="password" name="password" placeholder="Voer wachtwoord in" required>
          <?php
          // Display error message for incorrect password after form submission
          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
              if ($result->num_rows > 0 && !verifyPassword($password, $row["password_hash"])) {
                  echo "<p style='color: #b01605;'>Onjuiste wachtwoord!</p>";
              }
          }
          ?>
        <br>

        <input type="submit" name="login" value="Login">
          <?php
              // Display a message if login is required
              if ($message === 'login_required') {
                echo "<p style='color: #b01605;'>Gelieve eerst in te loggen!</p>";
              }
              // Display success message after successful login and no errors
              if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"]) && !($result->num_rows == 0) && !(($result->num_rows > 0 && !verifyPassword($password, $row["password_hash"])))) {
                  echo "<p style='color: green;'>Aanmelden gelukt!</p>";
              }
          ?>

        </form>
    </div>
    <div class="container logincontainer" style="background-color: transparent">
          <span class="psw"><a href="../register">Registreer</a></span>
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
                  ><img src="./img/instagram_logo.png" alt=""
                /></a>
              </li>
            </ul>
          </nav>
        </footer>
  </body>
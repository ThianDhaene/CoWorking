<?php

// Show all errors (for educational purposes)
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Constanten (connectie-instellingen databank)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ietsgents');

date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection error: ' . $e->getMessage();
    exit;
}
$name = isset($_POST['name']) ? (string)$_POST['name'] : '';
$email = isset($_POST['email']) ? (string)$_POST['email'] : '';
$message = isset($_POST['message']) ? (string)$_POST['message'] : '';
$msgName ='';
$msgEmail = '';
$msgMessage = '';

// form is sent: perform formchecking!
if (isset($_POST['btnSubmit'])) {

    $allOk = true;

    if (trim($name) === '') {
      $msgName = 'Please enter a name';
      $allOk = false;
    }
    // name not empty
    if (trim($email) === '') {
        $msgEmail = 'Please enter an email';
        $allOk = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msgEmail = 'Invalid email address format';
        $allOk = false;
  }

    if (trim($message) === '') {
        $msgMessage = 'Please enter a message';
        $allOk = false;
    }

    // end of form check. If $allOk still is true, then the form was sent in correctly
    if ($allOk) {

        $stmt = $db->prepare('INSERT INTO messages (sender,email, message, added_on) VALUES (?, ?, ?, ?)');
        $stmt->execute(array($name,$email, $message, (new DateTime())->format('Y-m-d H:i:s')));
        // the query succeeded, redirect to this very same page
        if ($db->lastInsertId() !== 0) {
            header('Location: formchecking_thanks.php?name=' . urlencode($name));
            exit();
        } // the query failed
        else {
            echo 'Databankfout.';
            exit;
        }

    }

}

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ietsgents | contact</title>
    <link rel="icon" href="../img/logo2.png" />
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="../contact/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet" />
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
            <li><a href="./" data-text="CONTACT">Contact</a></li> 
          </ul>
        </nav>
        <ul>
          <li><a href="../login"><img src="../img/account.webp" class="account" alt="" ></a></li>
          <li><a href="../cart"><img src="../img/winkelmandje.webp" class="cart" alt=""></a></li>
        </ul>
      </header>
    </div>
    <main>
        <h1><span class="contact">C</span><span class="contact">O</span><span class="contact">N</span><span class="contact">T</span><span class="contact">A</span><span class="contact">C</span><span class="contact">T</span></h1>
        <div class="contactcontainer">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <p class="message">Heb je vragen of opmerkingen voor ons? Laat een bericht voor ons achter!</p>
              <div>
                <label for="name">Naam</label>
                <input type="text" id="name" name="name" value="<?php echo htmlentities($name); ?>" class="input-text"/>
                <span class="message error"><?php echo $msgName; ?></span>
              </div>
            
              <div>
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" value="<?php echo htmlentities($email); ?>" class="input-text"/>
                <span class="message error"><?php echo $msgEmail; ?></span>
              </div>

              <div>
                <label for="message">Bericht</label>
                <textarea name="message" id="message" rows="5" cols="40"><?php echo htmlentities($message); ?></textarea>
                <span class="message error"><?php echo $msgMessage; ?></span>
              </div>

              <input type="submit" id="btnSubmit" name="btnSubmit" value="Verstuur" class="button"/>
          </form>
        </div>
    </main>
    <footer>
        <div class="footer-p">
          <p>&copy ietsgents 2023</p>
        </div>
        <nav class="socials">
          <ul>
            <li>
              <a href="https://www.instagram.com/ietsgents/" target="_blank"
                ><img src="../img/instagram_logo.png" alt="Instagram"
              /></a>
            </li>
          </ul>
        </nav>
      </footer>
</body>

</html>
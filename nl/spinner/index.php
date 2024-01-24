<?php
session_start();
if ($_SESSION['pageopened']) {
    // Page has already been opened
    // Handle accordingly, e.g., redirect to another page
    header("Location: ../errorpages/404.html");
    exit;
} else {
    // Page has not been opened yet
    // Perform necessary actions

    // Mark the page as opened
    $_SESSION['pageopened'] = true;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<title>ietgents | spin and win</title>
	<link rel="stylesheet" type="text/css" href="../about/style.css">
  <link rel="stylesheet" type="text/css" href="./style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap"
      rel="stylesheet"
    />
</head>
<header>
</header>
<main>
  <div class="titel">
      <h2><span class="about">C</span><span class="about">O</span><span class="about">N</span><span class="about">G</span><span class="about">R</span><span class="about">A</span><span class="about">T</span><span class="about">S</span><span class="about">, </span> <span class="about">Y</span><span class="about">O</span><span class="about">U </span><span class="about">F</span><span class="about">O</span><span class="about">U</span><span class="about">N</span><span class="about">D</span><span class="about"> A</span><span class="about">N</span><span class="about"> E</span><span class="about">A</span><span class="about">S</span><span class="about">T</span><span class="about">E</span><span class="about">R </span><span class="about">E</span><span class="about">G</span><span class="about">G</span></h2>
	</div>
      <div class="game">
			<img class="pin" src="../img/pin.png" alt="pin">
			<img class="wheel" src="../img/wheel.png" alt="wheel">
			<img class="button" id="disable" src="../img/button.png" alt="button">
		</div>
    <div class="text">
      <h2>Follow these steps to claim your price:</h2>
    <p>- Record while you spin this wheel.<br>- Post it on your Instagram story and tag @ietsgents.<br> - Add your Instagram username in the 'Extra Information' to your next following order.<br>- Your price will be send to you with your first following order!</p>
	  </div>
    <script src="script.js"></script>
  </main>
</body>
</html>
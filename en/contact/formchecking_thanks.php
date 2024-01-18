<?php

	$name = isset($_GET['name']) ? $_GET['name'] : false;

?><!DOCTYPE html>
<html lang="en">
<head>
	<title>Testform</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="../style.css" />
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<main>
<div class="bedanking">
	<?php

	echo '<p>Thank you for your message ' . htmlentities($name). '! We will answer this as soon as possible.</p>';
	echo '<img src="../img/logo1_zonder_achtergrond.png" alt="ietsgents">';

	?>
</div>
</main>
<footer>
	
</footer>


</body>
</html>

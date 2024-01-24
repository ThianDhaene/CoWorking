<?php

	$name = isset($_GET['name']) ? $_GET['name'] : false;

?><!DOCTYPE html>
<html lang="nl">
<head>
	<title>Bedankt!</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="../style.css" />
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<main>
<div class="bedanking">
	<?php

	echo '<p>Thanks for your message ' . htmlentities($name). '! We will answer this as soon as possible.</p>';
	echo '<img src="../img/logo1_zonder_achtergrond.png" alt="ietsgents">';
	echo '<meta http-equiv="refresh" content="3; url=https://lukasvermoere.ikdoeict.be/coworking/nl/contact/">'

	?>
</div>
</main>
<footer>
	
</footer>


</body>
</html>

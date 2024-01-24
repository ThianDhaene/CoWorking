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

	echo '<p>Dank je wel voor je bericht ' . htmlentities($name). '! We zullen dit zo snel mogelijk beantwoorden.</p>';
	echo '<img src="../img/logo1_zonder_achtergrond.png" alt="ietsgents">';
	echo '<meta http-equiv="refresh" content="3; url=https://lukasvermoere.ikdoeict.be/coworking/nl/contact/">'

	?>
</div>
</main>
<footer>
	
</footer>


</body>
</html>

<?php

	$name = isset($_GET['name']) ? $_GET['name'] : false;

?><!DOCTYPE html>
<html lang="en">
<head>
	<title>Testform</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<main>
<div class="bedanking">
	<?php

	echo '<p>Bedankt voor uw bericht ' . htmlentities($name). '! Wij beantwoorden dit zo snel mogelijk.</p>';
	echo '<img src="../img/logo1_zonder_achtergrond.png" alt="">';

	?>
</div>
</main>



</body>
</html>

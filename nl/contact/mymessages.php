<?php

// Constanten (connectie-instellingen databank)
$servername = "localhost";
$username = "lukas.ietsgents";
$password = "ietsgents.ww";
$dbname = "db_ietsgents";


date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . $servername . ';dbname=' . $dbname . ';charset=utf8mb4', $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindingsfout: ' .  $e->getMessage();
    exit;
}

// Opvragen van alle taken uit de tabel tasks
$stmt = $db->prepare('SELECT * FROM messages ORDER BY added_on DESC');
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


?><!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Mijn berichten</title>
    <link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<body>
<main class="bericht-container">
<?php if (sizeof($items) > 0) { ?>
    <ul>
        <?php foreach ($items as $item) { ?>
        <li>
        <div class="message-container">
        <?php echo '<strong>'. htmlentities($item['sender']) . '</strong>'; ?> - <?php echo '<strong>'. htmlentities($item['email']) . '</strong>'; ?> - 
        (<?php echo '<strong>'. (new Datetime($item['added_on']))->format('d/m/Y H:i:s'). '</strong>'; ?>)
        <?php echo '<br>' . htmlentities($item['message']); ?>
        </div>
        </li>
        <?php } ?>
    </ul>
    <?php
    } else {
        echo '<p>Nog geen berichten ontvangen.</p>' . PHP_EOL;
    }
    ?>
</main>
</body>
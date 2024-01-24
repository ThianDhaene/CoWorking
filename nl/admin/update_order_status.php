<?php
// Include your database connection
// Database connection
$servername = "localhost";
$username = "lukas.ietsgents";
$password = "ietsgents.ww";
$dbname = "db_ietsgents";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    // Get data from the form
    $order_id = $_POST["order_id"];
    $new_status = $_POST["new_status"];

    // Update the order status in the database
    $update_status_sql = "UPDATE orders SET status = '$new_status' WHERE order_id = $order_id";

    if ($conn->query($update_status_sql) === TRUE) {
        // Redirect back to the admin dashboard after updating the status
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating order status: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

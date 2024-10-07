<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exportsoftware"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$consignee = $_POST['consignee'];
$buyer = $_POST['buyer'];
$product_name = $_POST['product_name'];
$mfg_date = $_POST['mfg_date'];
$exp_date = $_POST['exp_date'];
$batch_no = $_POST['batch_no'];
$manufacturer = $_POST['manufacturer'];
$hs = $_POST['hs'];
$description = $_POST['description'];
$quantity = $_POST['quantity'];
$fob_price = $_POST['fob_price'];
$total_price = $_POST['total_price'];

$sql = "INSERT INTO exportt (consignee, buyer, product_name, mfg_date, exp_date, batch_no, manufacturer, hs, description, quantity, fob_price, total_price)
VALUES ('$consignee', '$buyer', '$product_name', '$mfg_date', '$exp_date', '$batch_no', '$manufacturer', '$hs', '$description', '$quantity', '$fob_price', '$total_price')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Record added successfully');
        window.location.href='welcome.php';
    </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

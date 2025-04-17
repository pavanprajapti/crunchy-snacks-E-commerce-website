<?php
include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='regular' LIMIT 4 ");
$stmt->execute();

$Regular_products = $stmt->get_result();

?>
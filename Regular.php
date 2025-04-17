<?php
include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='Regular' LIMIT 20 ");
$stmt->execute();

$regular_products = $stmt->get_result();

?>
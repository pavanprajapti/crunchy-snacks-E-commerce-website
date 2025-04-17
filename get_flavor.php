<?php
include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='flavor' LIMIT 20 ");
$stmt->execute();

$flavor_products = $stmt->get_result();

?>
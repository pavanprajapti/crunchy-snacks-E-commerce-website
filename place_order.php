<?php
session_start();
include('connection.php');

// Agar user login nahi hai toh redirect karo
if (!isset($_SESSION['logged_in'])) {
    header('location:../checkout.php?message=Please login/register to place an order');
    exit;
}

if (isset($_POST['place_order'])) {
    // ✅ **Validation Start** ✅

    // Sabhi fields empty toh nahi hain?
    if (
        empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || 
        empty($_POST['city']) || empty($_POST['address'])
    ) {
        header('location:../checkout.php?message=Please fill in all fields');
        exit;
    }

    // Name sanitize karein
    $name = trim(htmlspecialchars($_POST['name']));

    // Email validation karein
    $email = trim(htmlspecialchars($_POST['email']));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location:../checkout.php?message=Invalid email format');
        exit;
    }

    // Phone number validation karein (sirf numbers aur 10-digit length)
    $phone = trim($_POST['phone']);
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        header('location:../checkout.php?message=Invalid phone number');
        exit;
    }

    // City aur Address sanitize karein
    $city = trim(htmlspecialchars($_POST['city']));
    $address = trim(htmlspecialchars($_POST['address']));

    // Order cost check karein
    $order_cost = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
    if ($order_cost <= 0) {
        header('location:../checkout.php?message=Invalid order amount');
        exit;
    }

    $order_status = "not paid";
    $user_id = $_SESSION['user_id'];
    $order_date = date("Y-m-d H:i:s");

    // ✅ **Validation End** ✅

    // 1️⃣ **Order details database me store karein**
    $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isiisss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

    if (!$stmt->execute()) {
        header('location:../checkout.php?message=Order processing failed, try again');
        exit;
    }

    $order_id = $stmt->insert_id;

    // 2️⃣ **Cart check karein (empty cart toh nahi?)**
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product) {
            $product_id = $product['product_id'];
            $product_name = $product['product_name'];
            $product_image = $product['product_image'];
            $product_price = $product['product_price'];
            $product_quantity = $product['product_quantity'];

            // 3️⃣ **Order items database me store karein**
            $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param('iissiiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);
            $stmt1->execute();
        }
    } else {
        header('location:../checkout.php?message=Cart is empty');
        exit;
    }

    // 4️⃣ **Redirect karein payment page par**
    header('location:../payment.php?order_status=Order placed successfully');
    exit;
}
?>

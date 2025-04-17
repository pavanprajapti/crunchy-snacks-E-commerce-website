<?php

include('../server/connection.php');

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id=?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $order = $stmt->get_result();
} else if (isset($_POST['edit_order'])) {

    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id'];

    $stmt =  $conn->prepare("UPDATE orders SET order_status=? WHERE order_id=?");
    $stmt->bind_param('si', $order_status, $order_id);


    if ($stmt->execute()) {
        header('location: index.php?order_updated=Order has been uplode successfully');
    } else {
        header('location: products.php?Order_failed=Error occured try again');
    }
} else {
    header('location: index.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Edit Order</h2>

            <div class="table-resposive">
                <div class="mx-auto container">
                    <form id="edit-order-form" method="POST" action="edit_order.php">

                        <?php foreach ($order as $r) { ?>

                            <p style="color:red;"><?php if (isset($_GET['error'])) {
                                                        echo $_GET['error'];
                                                    } ?></p>
                            <div class="form-group">
                                <label>OrderId</label>
                                <p class="my-4"><?php echo $r['order_id']; ?></p>
                            </div>

                            <div class="form-group">
                                <label>OrderPrice</label>
                                <p class="my-4"><?php echo $r['order_cost']; ?></p>
                            </div>

                            <input type="hidden" name="order_id" value="<?php echo $r['order_id']; ?>" />

                            <div class="form-group">
                                <label>Order Status</label>
                                <select class="form-select" required name="order_status">

                                    <option value="not paid" <?php if ($r['order_status'] == 'not paid') {
                                                                    echo "selected";
                                                                } ?>>Notpaid</option>
                                    <option value="paid" <?php if ($r['order_status'] == 'paid') {
                                                                echo "selected";
                                                            } ?>>Paid</option>
                                    <option value="shipped" <?php if ($r['order_status'] == 'shipped') {
                                                                echo "selected";
                                                            } ?>>shipped</option>
                                    <option value="delivered" <?php if ($r['order_status'] == 'delivered') {
                                                                    echo "selected";
                                                                } ?>>Delivered</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>orderDate</label>
                                <p class="my-4"><?php echo $r['order_date']; ?></p>
                            </div>

                            <div class="d-grid">
                                <input type="submit" class="btn btn-primary" name="edit_order" value="Edit" />
                            </div>

                        <?php } ?>

                    </form>
                </div>


            </div>






        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php session_start() ?>

<?php

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location: login.php');
    exit();
}
// 1.determine page nomber
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    // if user has already entered page then page number is the one that they selected
    $page_no = $_GET['page_no'];
} else {
    //if user just entered the page then default page is 1
    $page_no = 1;
}

//2. return number of product
$stmt1 = $conn->prepare("SELECT COUNT(*) As total_records FROM orders");

$stmt1->execute();

$stmt1->bind_result($total_records);

$stmt1->store_result();

$stmt1->fetch();

// 3.product perpage

$total_records_per_page = 5;

$offset = ($page_no - 1) * $total_records_per_page;

$previous_page = $page_no - 1;
$next_page = $page_no + 1;

$adjacents = "2";

$total_no_of_pages = ceil($total_records / $total_records_per_page);

// 4.get all products

$stmt2 = $conn->prepare("SELECT * FROM orders LIMIT $offset,$total_records_per_page");
$stmt2->execute();
$orders = $stmt2->get_result();



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
        }

        footer {
            background-color: #f8f9fa;
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" id="sidebarMenu">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="index.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="index.php">
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="products.php">
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="account.php">
                                Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="add_product.php">
                                Add New Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="logout.php?logout=1">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Admin Panel</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                    </div>
                </nav>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Orders</h1>

                    <?php if(isset($_GET['order_updated'])) { ?>
                    <p class="text-center" style="color:green;"><?php echo $_GET['order_updated'];?></p>
                    <?php } ?>

                    <?php if(isset($_GET['order_failed'])) { ?>
                    <p class="text-center" style="color:red;"><?php echo $_GET['order_failed'];?></p>
                    <?php } ?>


                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Order Id</th>
                                <th scope="col">Order Status</th>
                                <th scope="col">user Id</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">User Phone</th>
                                <th scope="col">User Address</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order){?>

                                <tr>
                                    <td><?php echo $order['order_id'];?></td>
                                    <td><?php echo $order['order_status'];?></td>
                                    <td><?php echo $order['user_id'];?></td>
                                    <td><?php echo $order['order_date'];?></td>
                                    <td><?php echo $order['user_phone'];?></td>
                                    <td><?php echo $order['user_address'];?></td>
                                    <td><a class="btn btn-primary" href="edit_order.php?order_id=<?php echo $order['order_id'];?>">Edit</a></td>
                                    <td><a class="btn btn-danger">Delete</a></td>
                                    
                                </tr>

                            <?php } ?>
                        </tbody>

                    </table>
                </div>

                <nav aria-label="page navigation example">
                    <ul class="pagination mt-5 mx-auto">

                        <li class="page-item <?php if ($page_no <= 1) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link" href="<?php if ($page_no <= 1) {
                                                            echo '#';
                                                        } else {
                                                            echo "?page_no=" . ($page_no - 1);
                                                        } ?>">previous</a>
                        </li>


                        <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                        <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>

                        <?php if ($page_no >= 3) { ?>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="<?php echo "?page_no=" . $page_no; ?>"><?php echo $page_no; ?></a></li>
                        <?php } ?>


                        <li class="page-item <?php if ($page_no >= $total_no_of_pages) {
                                                    echo 'disable';
                                                } ?>">
                            <a class="page-link" href="<?php if ($page_no >= $total_no_of_pages) {
                                                            echo '#';
                                                        } else {
                                                            echo "?page_no=" . ($page_no + 1);
                                                        } ?>">Next</a>
                        </li>
                    </ul>
                </nav>


            </main>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
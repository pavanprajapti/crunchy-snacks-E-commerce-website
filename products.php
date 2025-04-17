<?php

include('../server/connection.php');


// 1.determine page nomber
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    // if user has already entered page then page number is the one that they selected
    $page_no = $_GET['page_no'];
} else {
    //if user just entered the page then default page is 1
    $page_no = 1;
}

//2. return number of product
$stmt1 = $conn->prepare("SELECT COUNT(*) As total_records FROM products");

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

$stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset,$total_records_per_page");
$stmt2->execute();
$products = $stmt2->get_result();



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
                            <a class="nav-link text-white" href="account.ph">
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
                    
                    <h1 class="h2">Products</h1>
                    <?php if(isset($_GET['edit_success_message'])) { ?>
                    <p class="text-center" style="color:green;"><?php echo $_GET['edit_success_message'];?></p>
                    <?php } ?>

                    <?php if(isset($_GET['edit_failur_message'])) { ?>
                    <p class="text-center" style="color:red;"><?php echo $_GET['edit_failur_message'];?></p>
                    <?php } ?>


                    <?php if(isset($_GET['deleted_successfully'])) { ?>
                    <p class="text-center" style="color:green;"><?php echo $_GET['deleted_successfully'];?></p>
                    <?php } ?>



                    <?php if(isset($_GET['deleted_failure'])) { ?>
                    <p class="text-center" style="color:red;"><?php echo $_GET['deleted_failure'];?></p>
                    <?php } ?>

                    <?php if(isset($_GET['product_created'])) { ?>
                        <p class="text-center" style="color: green;"><?php echo $_GET['product_created'];?></p>
                        <?php } ?>

                    <?php if(isset($_GET['product_failed'])) {  ?>
                        <p class="text-center" style="color:red;"><?php echo $_GET['product_failed'];?></p>
                        <?php } ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Product Id</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Product Price</th>
                                <th scope="col">Product Offer</th>
                                <th scope="col">Product Category</th>
                                <th scope="col">Product Color</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product){?>

                                <tr>
                                    <td><?php echo $product['product_id'];?></td>
                                    <td><img src="<?php echo "../assets/imges/".$product['product_image'];?>"style="width: 70px; height: 60px"/></td>
                                    <td><?php echo $product['product_name'];?></td>
                                    <td><?php echo $product['product_price'];?></td>
                                    <td><?php echo $product['product_special_offer']."%";?></td>
                                    <td><?php echo $product['product_category'];?></td>
                                    <td><?php echo $product['product_color'];?></td>

                                    <td><a class="btn btn-primary" href="edit_product.php?product_id=<?php echo $product['product_id'];?>">Edit</a></td>
                                    <td><a class="btn btn-danger" href="delete_product.php?product_id=<?php echo $product['product_id'];?>">Delete</a></td>
                                    
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
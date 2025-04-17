<?php


include('server/connection.php');
// return all product


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

$total_records_per_page = 12;

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
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .product img {
            width: 100%;
            height: auto;
            box-sizing: border-box;
            background-size: cover;
        }

        .pagination a {
            color: coral;
        }

        .pagination li:hover a {
            color: #fff;
            background-color: coral;
        }
    </style>
</head>

<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Snacks</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Contact.php">Contact Us</a>
                    </li>

                    <li class="nav-item">
                        <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                        <a href="account.php"><i class="fa-solid fa-user"></i></a>
                    </li>



                </ul>

            </div>
        </div>
    </nav>




    <section id="features" class="my-5 py-5">
        <div class="container mt-5 py-5">
            <h3>our flavors products </h3>
            <hr>
            <p>here you can check out our profuct</p>
        </div>
        <div class="row mx-auto container">

           <?php include('server/get_flavor.php'); ?>

           <?php while ($row = $flavor_products->fetch_assoc()) { ?>
                <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                    <img class="img-fluid md-3" src="assets/imges/<?php echo $row['product_image'] ?>" alt="">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>

                    <h5 class="p-name"><?php echo $row['product_name'];?></h5>
                    <h4 class="p-price">₹ <?php echo $row['product_price'];?></h4>
                    <a href="<?php echo "single.products.php? product_id=". $row['product_id'];?>"><button class="buy-btn">Buy Now</button></a>
                </div>
            <?php } ?>


           

           

            

            <nav aria-label="page navigation example">
                <ul class="pagination mt-5 mx-auto">

                    <li class="page-item <?php if ($page_no <= 1) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link" href="<?php if ($page_no <= 1) {
                                                        echo '#';
                                                    } else {
                                                        echo "?page_no=" .($page_no - 1);
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
        </div>
    </section>







    <!-- footer -->
    <?php include('layout/footer.php');?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>
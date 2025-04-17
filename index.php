
<?php 
include('layout/header.php');
?>

    <!-- Home -->

    <section id="home">
        <div class="container">
            <h5>NEW ARRIVALS</h5>
            <h1>Welcome to My<span> Khakhra</span> Shope</h1>
            <p>the most afferteble price</p>
            <button>shope now</button>
        </div>
    </section>



    <section id="new" class="w-100">
        <div class="row p-0 m-0">

            <!-- one -->
            <div class="one col-lg-6 col-md-10 col-sm-12 p-0">
                <img class="img-fluid" src="assets/imges/kharkhra1.jpeg" width="100%"/>
                <div class="detials">
                    <h2>Regular Khakhra</h2>
                    <a href="regular_product.php"><button class="text-uppercase">Shop Now</button></a>
                </div>
            </div>

            

            <!-- three -->
            <div class="one col-lg-6 col-md-10 col-sm-12 p-0">
                <img class="img-fluid" src="assets/imges/futures4.jpeg" width="100%" />
                <div class="detials">
                    <h2>flavor Khakhra</h2>
                    
                    <a href="flavor.php"><button class="text-uppercase">Shop Now</button></a>
                </div>
            </div>
        </div>
    </section>

    <!-- futured -->

    <section id="features" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h3>our featured</h3>
            <hr class="mx-auto">
            <p>here you can check out our featured profuct</p>
        </div>

        <div class="row mx-auto container-fluid">

            <?php include('server/get_featured_products.php'); ?>

            <?php while ($row = $featured_products->fetch_assoc()) { ?>

                <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                    <img class="img-fluid md-3" src="assets/imges/<?php echo $row['product_image']; ?>" alt="" />
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>

                    <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                    <h4 class="p-price">₹<?php echo $row['product_price']; ?></h4>
                    <a href="<?php echo "single.products.php? product_id=". $row['product_id'];?>"><button class="buy-btn">Buy Now</button></a>
                </div>

            <?php } ?>

        </div>



    </section>


    


    <!-- Regular Khakhra -->

    <section id="features" class="my-5 ">
        <div class="container text-center mt-5 py-5">
            <h3>Regular Khakhra</h3>
            <hr class="mx-auto">
            <p>here you can check out our featured profuct</p>
        </div>
        <div class="row mx-auto container-fluid">

            <?php include('server/get_regular.php'); ?>

            <?php while ($row = $Regular_products->fetch_assoc()) { ?>
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

           
        </div>
    </section>


   


<?php include('layout/footer.php');?>
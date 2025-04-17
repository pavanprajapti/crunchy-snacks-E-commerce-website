<?php 

include('../server/connection.php');
   

    if(isset($_GET['product_id'])){
        $product_id = $_GET['product_id'];
  $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
  $stmt->bind_param('i', $product_id);
  $stmt->execute();
  $products = $stmt->get_result();

}else if(isset($_POST['edit_btn'])){

    $product_id = $_POST['product_id'];
    $title = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $offer = $_POST['offer'];
    $color = $_POST['color'];
    $category = $_POST['category'];  

    $stmt =  $conn->prepare("UPDATE products SET product_name=?, product_description=?, product_price=?,
                            product_special_offer=?, product_color=?, product_category=? WHERE product_id=?");
    $stmt->bind_param('ssssssi', $title,$description,$price,$offer,$color,$category, $product_id);

   
    if( $stmt->execute()){
        header('location: products.php?edit_success_message=Product has been uplode successfully');
    }else{
        header('location: products.php?edit_failur_message=Error occured try again');
    }

    

}else{
    header('products.php');
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
            <h2 class="text-center mb-4">Edit Product</h2>
            <form id="edit-form" method="POST" action="edit_product.php">

                <p style="color:red;"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>

                <div class="form-group">

                <?php foreach($products as $product) { ?>

                    <input type="hidden" name="product_id" value="<?php echo $product['product_id'];?>"/>
                    <label>Title</label>
                    <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name']?>" name="name" placeholder="ProductName" required />
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" id="product-desc" value="<?php echo $product['product_description']?>" name="description" placeholder="Description" required />
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" class="form-control" id="product-price" value="<?php echo $product['product_price']?>" name="price" placeholder="price" required />
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-select" required name="category">
                        <option value="khakhra">Khakhra</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>color</label>
                    <input type="text" class="form-control" id="product-color" value="<?php echo $product['product_color']?>" name="color" placeholder="color" required />
                </div>
                <div class="form-group">
                    <label>Special Offer/Sale</label>
                    <input type="number" class="form-control" id="product-offer" value="<?php echo $product['product_special_offer']?>" name="product-offer" placeholder="Sale %" required />
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="edit_btn" value="Edit"/>
                </div>

                <?php } ?>
            </form>

            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

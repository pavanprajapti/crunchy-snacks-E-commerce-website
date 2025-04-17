<?php include('../server/connection.php')?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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
            <h2 class="text-center mb-4">Add Product</h2>
            <form method="POST" enctype="multipart/form-data" action="create_product.php">
                <p style="color:red;"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" class="form-control" id="product-name" name="name" placeholder="Enter product name" required />
                </div>
                <div class="mb-3">
                    <label for="productDescription" class="form-label">Description</label>
                   <input type="text" class="form-control" id="product-desc" name="description" placeholder="Description" />
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" step="0.01" class="form-control" id="product-price" name="price" placeholder="Enter price" required />
                </div>
                <div class="mb-3">
                    <label>Special offer</label>
                    <input type="number" class="form-control" id="product-offer" name="offer" placeholder="Special offer" required />
                </div>
                <div class="mb-3">
                    <label>Category</label>
                    <select class="form-select" required name="category">
                        <option value="regular ">regular</option>
                        <option value="flavor">flavor</option>
                        <option value="Regular">Regular</option>
                       
                    </select>
                </div>
                <div class="mb-3">
                    <label>color</label>
                    <input type="text" class="form-control" id="product-color" name="color" placeholder="color" required />
                </div>
                <div class="mb-3">
                    <label> Image 1</label>
                    <input type="file" class="form-control" id="Image" name="Image" placeholder="Image1" required />
                </div>
                <div class="mb-3">
                    <label> Image 2</label>
                    <input type="file" class="form-control" id="Image2" name="Image2" placeholder="Image2" required />
                </div>
                <div class="mb-3">
                    <label> Image 3</label>
                    <input type="file" class="form-control" id="Image3" name="Image3" placeholder="Image3" required />
                </div>
                <div class="mb-3">
                    <label> Image 4</label>
                    <input type="file" class="form-control" id="Image4" name="Image4" placeholder="Image4" required />
                </div>

                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" name="create_product" value="Create" />
                </div>
                
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

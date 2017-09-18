<?php
  session_start();

  require "query/product.php";

  $productModel = new Product();

  if(!isset($_SESSION["username"])) {
      header( "Location: collections.php" );
      exit(0);
  }

  $product = $productModel->readProductByName($_GET['name']);
  $product = $product->fetch();

   if(isset($_POST["submit"])) {
    $file = $_FILES["file"];
    
    $name = $_POST["productName"];
    $price = floatval($_POST["price"]);
    $detail = $_POST["detail"];
    $amount = intval($_POST["amount"]);
    $imageurl = isNewImage($file);
    $oldname = $product["name"];

    $result = $productModel->editProduct($name, $price, $detail, $amount, $imageurl, $oldname);
    if($result->rowCount()) {
        header( "Location: collections.php" );
        exit(0);
    }
  }

  function isNewImage($file) {
    global $product;
    if($file["size"]) {
      unlink($product["imageurl"]);
      $path = uploadImage($file);
      return $path;
    }else {
      return $product["imageurl"];
    }
  }

  function uploadImage($file) {
    $filename = $file["name"];
    $tempFilename = $file["tmp_name"];

    $filepath = "pic/". $filename;
    move_uploaded_file($tempFilename, $filepath);

    return $filepath;
  }

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<style>
	  .margin-bottom {
	  	margin-bottom: 8px;
	  }
 
  </style>

</head>
<body>

	<nav class="navbar navbar-default">
  		<div class="container-fluid">
   			<div class="navbar-header">
      		<a class="navbar-brand" href="index.php">JustSaySweet</a>
    	</div>

    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      		<ul class="nav navbar-nav navbar-right">
          <li><a href="add.php">Add</a></li>
        	<li><a href="collections.php">Collections</a></li>
        	<li><a href=""><?php echo $_SESSION["username"] ?></a></li>
      		</ul>
   		 </div>
  		</div>
	</nav>

	<div class="container">
    <div class="row">

      <form method="POST" action="" enctype="multipart/form-data">
        <div class="col-sm-6 col-sm-offset-3">
           <h1>Edit</h1>
            <div class="form-group">
              <label class="control-label" for="focusedInput">Product Name</label>
              <input class="form-control" id="focusedInput" type="text" name="productName" value="<?php echo $product["name"] ?>">
            </div>
            
            <div class="form-group">
              <label class="control-label" for="focusedInput">Price</label>
              <input class="form-control" id="focusedInput" type="text" name="price" value="<?php echo $product["price"] ?>">
            </div>

            <div class="form-group">
              <label for="textArea" class="control-label">Detail</label>
              <textarea class="form-control" rows="3" id="textArea" name="detail" ><?php echo $product["detail"] ?></textarea>
            </div>

            <div class="form-group">
              <label class="control-label" for="focusedInput">Amount</label>
              <input class="form-control" id="focusedInput" type="text" name="amount" value="<?php echo $product["amount"] ?>">
            </div>

            <div class="form-group">
              <label class="control-label" for="imgInp">Image</label>
              <input type='file' name="file" id="imgInp" />
              <img id="blah" src="<?php echo $product["imageurl"] ?>" alt="your image" />
            </div>
            
            <div class="form-group" style="margin-top:16px; text-align: center; ">
              <a type="submit" href="collections.php" class="btn btn-default">Cancel</a>
              <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <script>
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
                $('#blah').attr('width', "350px");
                $('#blah').attr('height', "350px");
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    });
  </script>

</body>
</html>
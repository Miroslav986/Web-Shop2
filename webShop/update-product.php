<?php
require_once 'user.class.php';

if(!isset($_GET['id'])) {
	header('Location: ./products.php');
}

require_once './product.class.php';
$p = new Product($_GET['id']);

if (isset($_POST['update_product'])) {
	$p->title = $_POST['title'];
	$p->price = $_POST['price'];
	$p->id_c = $_POST['cat_id'];
	$p->description = $_POST['description'];
	$p->image_info = $_FILES['img'];
	$update = $p->save();

	if ($update) {
		$p = new Product($_GET['id']);
	}
}
?>



<?php include "./layout/header.php";  ?> 

<h2>Update <?= $p->title ?></h2>

<?php 
if (isset($_POST['update_product']) && $update) {
	require_once 'helper.class.php';
	Helper::success('Product details updated.');
}
if (isset($_POST['update_product']) && !$update) {
	require_once 'helper.class.php';
	Helper::error('Failed to update product.');
}

?>

<form action="./update-product.php?id=<?=$_GET['id'] ?>" method="post" enctype="multipart/form-data">

  <div class="row mt-5">  

    <!-- OLD IMAGE -->
    <div class="col-md-6">
      <div class="image-container">
        <img src="<?=($p->img) ? $p->img : './img/product.png' ?>" class="img-fluid" />
      </div>
    </div>
    <!-- NEW IMAGE & CATEGORY -->
    <div class="col-md-6">
      <!-- NEW IMAGE -->
      <div class="form-group">
        <div class="mb-2">New image</div>
        <label class="custom-file">
          <input type="file" name="img" id="inputImage" class="custom-file-input" />
          <span class="custom-file-control"></span>
        </label>
      </div>
      <!-- CATEGORY -->
      <div class="form-group">
        <label for="inputCategory">Category</label>
        <select name="cat_id" class="form-control" id="inputCategory">
          <?php foreach($categories as $category): ?>
            <option value="<?= $category['id'] ?>" <?=($p->id_c == $category['id']) ? 'selected' : '' ?>>
              <?= $category['title'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <!-- TITLE -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputTitle">Title</label>
        <input type="text" name="title" class="form-control" value="<?=$p->title ?>" id="inputTitle" placeholder="Product title" />
      </div>
    </div>
    <!-- PRICE -->
    <div class="col-md-6">
      <label for="inputPrice">Price</label>
      <div class="input-group">
        <span class="input-group-addon">$</span>
        <input type="number" name="price" value="<?=$p->price ?>" class="form-control" id="inputPrice" placeholder="Product price" />
      </div>
    </div>
    <!-- DESCRIPTION -->
    <div class="col-md-12">
      <div class="form-group">
        <label for="inputDescription">Description</label>
        <textarea name="description" class="form-control" id="inputDescription" placeholder="Detailed product description"><?=$p->description ?></textarea>
      </div>
    </div>

    <!-- BUTTON -->
    <div class="col-md-12 clearfix">
      <button class="btn btn-primary float-right" type="submit" name="update_product">Update product</button>
    </div>
  </div>

</form>

<?php include "./layout/footer.php";  ?>


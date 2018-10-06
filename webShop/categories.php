<?php 

if(!isset($_GET['id'])) {
header("location: ./index.php");
}
 
require_once "./category.class.php";
$cat= new Category($_GET['id']);
$products = $cat->product();

 ?>


<?php include "./layout/header.php";  ?>

<h1><?= $cat->title ?></h1>
<?php foreach($products as $product) : ?>
<div>
	<?= $product['title'] ?>
</div>		
<?php endforeach;  ?>
<?php include "./layout/footer.php";  ?>
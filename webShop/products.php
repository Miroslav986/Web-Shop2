<?php  
$per_page = 6;
$page = 1;

if (isset($_GET['page'])) {
	$page = $_GET['page'];
}

if(isset($_GET["id_c"])) {
	require_once "category.class.php";
	$c= new Category($_GET["id_c"]);
	$products = $c->product();
	$page_title = $c->title;
} else if (isset($_GET['search'])) {
	require_once 'product.class.php';
	$p = new Product();
	$products = $p->search($_GET['search']);
	$page_title = 'Search results for "' . $_GET['search'] . '"';
} else {
	require_once "product.class.php";
	$p = new Product();
	$res = $p->all($per_page,$page);
	$products = $res['products'];
	$total_pages = $res['total_pages']; 
	$page_title = "All products";
	$previous = $page - 1;
	$next = $page + 1;
	if ($previous <= 0) {
		$previous = 1;
	}
	if ( $next > $total_pages ) {
		$next = $total_pages;
	}

}

?>

<?php include "./layout/header.php";  ?>

<h1> <?= $page_title  ?></h1>
<div class="row mt-4">
	<?php foreach($products as $product) : ?>
		<div class="col-md-4">
			<div class="card">
				<span class="badge badge-secondary price"><?= $product["price"]  ?></span>
				<img class="card-img-top" src="<?= $product['img'] ? $product['img'] : './img/product.png'?>">
				<div class="card-body">
					<h4 class="card-title"><?= $product["title"] ?></h4>

					<a href="product-details.php?id=<?= $product['id'] ?>" class="card-link float-right">Details</a>
					<a href="#" class="card-link">Add to cart</a>

				</div>
			</div>

			
		</div>
	<?php endforeach; ?> 

</div>

<?php
//ovim ifom  sakrivamo paginaciju za prikazivanje proizvoda preko kategoria i pretrage

 if(!isset($_GET['search']) && !isset($_GET['id_c']) ):?>
<div class="row mt-5"> 
	<div class="col-md-12"> 
		<div class="pgnt">
	      <nav aria-label="Page navigation example">
		    <ul class="pagination">
			    <li class="page-item ">
			 	  <a class="page-link" href="./products.php?page=<?= $previous ?>" >Previous</a>
			    </li>

			  <?php for($i = 1; $i <= $total_pages; $i++):  ?>
 		        <li class="page-item <?= ($page == $i) ? 'active' : null ?>" >
 		           <a class="page-link" href="./products.php?page=<?= $i ?>">
 		           <?= $i ?>
 		            </a>
 		        </li>
			  <?php endfor; ?>

			 	  <a class="page-link" href="./products.php?page=<?= $next ?> ">Next</a>
			   </li>
	   	    </ul>
	      </nav>
	     </div>
 
	</div>

</div>
<?php endif; ?>
<?php include "./layout/footer.php";  ?>
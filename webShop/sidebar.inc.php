<?php 
require_once "category.class.php";
$c = new Category(); // c je samo moj izbor moze biti bilo sta
$categories = $c->all();
?>


<div class="sidebar">
	<h2>Kategorije</h2>
  <div class="list-group mt-4">
	<?php foreach($categories as $category): ?>
     <a href="./products.php?id_c=<?= $category["id"] ?>" class="list-group-item list-group-item-action ">
    <?= $category["title"] ?>
     </a>
    <?php endforeach; ?>
  </div>
		
</div>
 
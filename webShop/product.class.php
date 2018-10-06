<?php
 
class Product {  

	private $config;
	private $db;
	private $image_path = './img/products/';
	private $allowed_image_extensions;
	private $max_image_size;
	public $id;
	public $id_c;
	public $title;
	public $description;
	public $price;
	public $img;
	public $image_info;


	 
	function __construct( $id = null ) {
		$this->max_image_size = 2 * 1024 * 1024;
		$this->allowed_image_extensions = [
		'image/jpeg', 'image/png', 'image/gif'
		]; 
		$this->db = require "./db.inc.php";
		$this->config = require "./config.inc.php";

		if($id != null ) {
			$q_getProductInfo = $this->db->prepare("
				SELECT *
				FROM `products` 
				WHERE `id` = :id
				");
			$q_getProductInfo->bindParam(":id",$id);
			$q_getProductInfo->execute();
			$productInfo = $q_getProductInfo->fetch();

			$this->id = $productInfo['id'];
			$this->id_c = $productInfo['id_c'];
			$this->title = $productInfo['title'];
			$this->description = $productInfo['description'];
			$this->price = $productInfo['price'];
			$this->img = $productInfo['img'];

		}
	}
	public function save() {
		if($this->id == null) {
			return $this->insert();
		} else {
			return $this->update();
		}
	}
	//  odredimo dva argumenta $limit = broj proizvoda po stranici i $page = od koje strane da krene
	public function all($limit = 6, $page = 1) {
// prvo prebrojimo proizvode i stavimo ih u promenljivu $total  ( kada se upit stavi u query odmah se izvrsava bez execute())
		$total = $this->db->query("
			SELECT COUNT(*)
			FROM `products`
			")->fetchColumn();
// $pages ukupan broj strana dobijamo tako sto broj proizvoda podelimo sa limitom po strani  $limit.		
		$pages = ceil($total/$limit);
// na ovaj nacin se racuna koliko proizvoda treba preskociti, to jest da prikazuje nove proizvode na sledecoj strani 
		$offset = ($page -1) * $limit;
		$start = $offset + 1;
		$end = min( ($offset + $limit),$total);
// $ offset i $limit moraju biti unapred odredjeni, to jest moraju biti poznate vrednosti u upitu, ne moze placeholder pa da se veze preko bindParam.
		$q_productsAll = $this->db->prepare("
			SELECT * 
			FROM `products`		
			LIMIT $offset, $limit
			");
		$q_productsAll->execute();
// izvrsimo upit i vratimo kroz niz sve produkte, ukupan broj strana i ukupan broj proizvoda
		return [
		'products' => $q_productsAll->fetchAll(),
		'total_pages' => $pages,
		'total_products' => $total
		];
	}

	public function insert() {
		$this->handleDirectories();
		$q_insertrProduct = $this->db->prepare("
			INSERT INTO `products`
			(`id_c`,`title`,`description`,`price`,`img`)
			VALUES
			(:id_c, :title, :description, :price, :img)
			");
		$q_insertrProduct->bindParam(":id_c", $this->id_c);
		$q_insertrProduct->bindParam(":title", $this->title);
		$q_insertrProduct->bindParam(":description", $this->description);
		$q_insertrProduct->bindParam(":price", $this->price);
		$q_insertrProduct->bindParam(":img", $this->img);
		$rezult= $q_insertrProduct->execute();
		$this->id = $this->db->lastInsertId();

		if($rezult && isset($this->image_info)) {

			$fileNameArray = explode('.', $this->image_info['name']);
			$imageExt = strtolower(end($fileNameArray));
			$imagePath = $this->image_path . $this->id . '.' . $imageExt;

			if(!in_array($this->image_info['type'], $this->allowed_image_extensions)) {
				return false;
			}

			if($this->image_info['size'] > $this->max_image_size) {
				return false;
			}

			move_uploaded_file($this->image_info['tmp_name'], $imagePath);

			$this->img = $imagePath;
			$this->save();
			$this->image_info = null;

		}

		return $rezult;
	}
	public function update() {
		$q_updateProduct = $this->db->prepare("
			UPDATE `products`
			SET
			`id_c`= :id_c,
			`title`=:title,
			`description`=:description,
			`price`= :price,
			`img` =:img
			WHERE `id`=:id
			");
		$q_updateProduct->bindParam(":id",$this->id);
		$q_updateProduct->bindParam(":id_c",$this->id_c);
		$q_updateProduct->bindParam(":title",$this->title);
		$q_updateProduct->bindParam(":description",$this->description);
		$q_updateProduct->bindParam(":price",$this->price);
		$q_updateProduct->bindParam(":img",$this->img);
		$update = $q_updateProduct->execute();

		if($update && $this->image_info != null && $this->image_info['error'] == 0) {

			$fileNameArray = explode('.', $this->image_info['name']);
			$imageExt = strtolower(end($fileNameArray));
			$imagePath = $this->image_path . $this->id . '.' . $imageExt;

			if(!in_array($this->image_info['type'], $this->allowed_image_extensions)) {
				return false;
			}

			if($this->image_info['size'] > $this->max_image_size) {
				return false;
			}

			move_uploaded_file($this->image_info['tmp_name'], $imagePath);

			$img_update = $this->image_update($imagePath);
			$this->image_info = null;

		}

		return $update;

	} 

	public function image_update($imagePath) {
		$q_updateProductImg = $this->db->prepare('
			UPDATE `products`
			SET 
				`img` = :img
			WHERE `id` = :id
			'); 
			$q_updateProductImg->bindParam(':id', $this->id);
			$q_updateProductImg->bindParam(':img',$imagePath);
			return $q_updateProductImg->execute();
	}
 
	public function delete() {
		if($this->id != null) {
			$q_deleteProduct = $this->db->prepare("
				DELETE
				FROM `products`
				WHERE `id` = :id
				");
			$q_deleteProduct->bindParam(":id",$this->id);
			$rezult = $q_deleteProduct->execute();
			$this->id = null;
			return $rezult;
		}
	}
	public function search($query) {
		$query = '%' . $query . '%';
		$q_search = $this->db->prepare("
			SELECT *
			FROM `products`
			WHERE `title` LIKE :query_title
			OR `description` LIKE :query_description
			");
		$q_search->bindParam(':query_title', $query);
		$q_search->bindParam(':query_description', $query);
		$q_search->execute();
		return $q_search->fetchAll();

 
	}
	private function handleDirectories() {
		if(!file_exists($this->image_path)) {
			mkdir($this->image_path, 0777, true);
		}
	}
	public function comments() {
		$q_getComments = $this->db->prepare("

			SELECT 
			`users`.`id` as user_id,
			`users`.`email`,
			`comments`.`id` as comment_id,
			`comments`.`comment`,
			`comments`.`comment_date`
			FROM `users`,`comments`
			WHERE `comments`.`id_p` = :id_p
			AND `users`.`id`=`comments`.`id_u`
			ORDER BY `comments`.`comment_date` DESC
			");
		$q_getComments->bindParam(":id_p",$this->id);
		$q_getComments->execute();
		return $q_getComments->fetchAll();
	}

	public function addComment($comment) {
		require_once "./user.class.php";
		$id_u = User::userId();
		if(!$id_u) {
			return false;
		}
		$q_addComment = $this->db->prepare("
			INSERT INTO `comments` 
			(`id_u`,`id_p`,`comment`)
			VALUES
			(:id_u, :id_p, :comment)
			");
		$q_addComment->bindParam(":id_u",$id_u);
		$q_addComment->bindParam(":id_p",$this->id);
		$q_addComment->bindParam(":comment",$comment);
		return $q_addComment->execute();

	}
	public function deleteComment() {
		$q_deleteComment = $this->db->prepare("
			DELETE 
			FROM `comments`
			WHERE `id` = :id
			");
		$q_deleteComment->bindParam(":id",$this->id);
		$result = $q_deleteComment->execute();
		$this->id = null;
		return $result;

	} 


	public function addToCart($quantity = 1) {
		require_once 'user.class.php';
		$id_u = User::userId();
		if(!$id_u) {return false;}
// upit gde selektujemo sve proizvode koji je kupio jedan korisnik
		$q_getUserProduct = $this->db->prepare("
			SELECT * 
			FROM `shopping_cart`
			WHERE `id_p` = :id_p
			AND `id_u` = :id_u
			");
		$q_getUserProduct->bindParam(':id_p',$this->id);
		$q_getUserProduct->bindParam(':id_u',$id_u);
		$q_getUserProduct->execute();
		$user_product_details = $q_getUserProduct->fetch();

		if ($q_getUserProduct->rowCount() > 0 ) {
			$new_quantity = $user_product_details['quantity'] + $quantity;
			// var_dump($new_quantity);
			$q_updateQuantity = $this->db->prepare("
				UPDATE `shopping_cart`
				SET `quantity` = :quantity
				WHERE `id` = :id
				");
			$q_updateQuantity->bindParam(':quantity',$new_quantity);
			$q_updateQuantity->bindParam(':id',$user_product_details['id']);
			return $q_updateQuantity->execute();

		} else {
//  upit za dodavanje u korpu
		$q_addToCart = $this->db->prepare("
			INSERT INTO `shopping_cart`
			(`id_p`,`id_u`,`quantity`)
			VALUES
			(:id_p ,:id_u ,:quantity )
			");
		$q_addToCart->bindParam(':id_p', $this->id);
		$q_addToCart->bindParam(':id_u', $id_u);
		$q_addToCart->bindParam(':quantity',$quantity );
		return $q_addToCart->execute();
		}
	}
	


// public function addToCart($quantity = 1) {
//     require_once './user.class.php';
//     $id_u = User::userId();
//     if ( !$id_u ) { return false; }

//     $q_getUserProduct = $this->db->prepare("
//       SELECT *
//       FROM `shopping_cart`
//       WHERE `id_p` = :id_p
//       AND `id_u` = :id_u
//     ");
//     $q_getUserProduct->bindParam(':id_p', $this->id);
//     $q_getUserProduct->bindParam(':id_u', $id_u);
//     $q_getUserProduct->execute();
//     $user_product_details = $q_getUserProduct->fetch();

//     if ( $q_getUserProduct->rowCount() > 0 ) {
//       $new_quantity = $user_product_details['quantity'] + $quantity;
//       $q_updateQunatity = $this->db->prepare("
//         UPDATE `shopping_cart`
//         SET `quantity` = :quantity
//         WHERE `id` = :id
//       ");
//       $q_updateQunatity->bindParam(':quantity', $new_quantity);
//       $q_updateQunatity->bindParam(':id', $user_product_details['id']);
//       return $q_updateQunatity->execute();
//     } else {
//       $q_addToCart = $this->db->prepare("
//         INSERT INTO `shopping_cart`
//         (`id_u`, `id_p`, `quantity`)
//         VALUES
//         (:id_u, :id_p, :quantity)
//       ");
//       $q_addToCart->bindParam(':id_u', $id_u);
//       $q_addToCart->bindParam(':id_p', $this->id);
//       $q_addToCart->bindParam(':quantity', $quantity);
//       return $q_addToCart->execute();
//     }
//   }


}
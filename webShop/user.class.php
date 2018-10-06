<?php  
 
class User { 

	private $db;
	private $config;
	public $id;
	public $name;
	public $last_name;
	public $email;
	public $password;
	public $newsletter;
	public $address;
	public $city;
	public $country;
	public $phone_number;
	public $date_of_birth;
	public $account_type;
	public $password_reset_token;

	function __construct($id = NULL) {
		$this->config = require "./config.inc.php";
		$this->db = require "./db.inc.php";
		

		if($id != NULL) {
			$q_getUser = $this->db->prepare("
				SELECT * 
				FROM `users`
				WHERE `id` = :id
				");
			$q_getUser->bindParam(":id", $id);

			$q_getUser->execute();
			$userInfo = $q_getUser->fetch();
			$this->id = $userInfo['id'];
			$this->name = $userInfo['name'];
			$this->last_name = $userInfo['last_name'];
			$this->email = $userInfo['email'];
			$this->password = $userInfo['password'];
			$this->newsletter = $userInfo['newsletter'];
			$this->address = $userInfo['address'];
			$this->city = $userInfo['city'];
			$this->country = $userInfo['country'];
			$this->phone_number = $userInfo['phone_number'];
			$this->date_of_birth = $userInfo['date_of_birth'];
			$this->account_type = $userInfo['account_type'];
			$this->password_reset_token = $userInfo['password_reset_token'];
		}
	}
	public function save() {
		if($this->id == NULL){
			return $this->insert_user();
		} else {
			return $this->update_user();
		}
	}

	public function insert_user() {
		$q_insertUser = $this->db->prepare("
			INSERT INTO `users`
			(`name`,`last_name`,`email`,`password`,`newsletter`,`address`,`city`,`country`,`phone_number`,`date_of_birth`,`password_reset_token`)
			VALUES
			(:name,:last_name,:email,:password,:newsletter,:address,:city,:country,:phone_number,:date_of_birth,:password_reset_token)
			");

		$q_insertUser->bindParam(":name",$this->name);
		$q_insertUser->bindParam(":last_name",$this->last_name);
		$q_insertUser->bindParam(":email",$this->email);
		$q_insertUser->bindParam(":password",$this->password);
		$q_insertUser->bindParam(":newsletter",$this->newsletter);
		$q_insertUser->bindParam(":address",$this->address);
		$q_insertUser->bindParam(":city",$this->city);
		$q_insertUser->bindParam(":country",$this->country);
		$q_insertUser->bindParam(":phone_number",$this->phone_number);
		$q_insertUser->bindParam(":date_of_birth",$this->date_of_birth);
	    //$q_insertUser->bindParam(":account_type",$this->account_type);
		$q_insertUser->bindParam(":password_reset_token",$this->password_reset_token);

		$rezult= $q_insertUser->execute();
		$this->id = $this->db->lastInsertId();
		return $rezult;
	}

	public function update_user() {
		$q_updateUser = $this->db->prepare("
			UPDATE `users`
			SET 
			`name` = :name,
			`last_name` = :last_name,
			`email` = :email,
			`password` = :password,
			`newsletter` = :newsletter,
			`address` = :address,
			`city` = :city,
			`country` = :country,
			`phone_number` = :phone_number,
			`date_of_birth` = :date_of_birth,
			`account_type` = :account_type,
			`password_reset_token` = :password_reset_token
			WHERE `id` = :id
			");

		$q_updateUser->bindParam(":id",$this->id);
		$q_updateUser->bindParam(":name",$this->name);
		$q_updateUser->bindParam(":last_name",$this->last_name);
		$q_updateUser->bindParam(":email",$this->email);
		$q_updateUser->bindParam(":password",$this->password);
		$q_updateUser->bindParam(":newsletter",$this->newsletter);
		$q_updateUser->bindParam(":address",$this->address);
		$q_updateUser->bindParam(":city",$this->city);
		$q_updateUser->bindParam(":country",$this->country);
		$q_updateUser->bindParam(":phone_number",$this->phone_number);
		$q_updateUser->bindParam(":date_of_birth",$this->date_of_birth);
		$q_updateUser->bindParam(":account_type",$this->account_type);
		$q_updateUser->bindParam(":password_reset_token",$this->password_reset_token);

		 $result = $q_updateUser->execute();
		 // if successufully saved, update session
		 if($result) {
		 	require_once "./helper.class.php";
		 	Helper::session_start();
		 	$_SESSION['user'] = [
		 	'id'=> $this->id,
		 	'name'=> $this->name,
		 	'last_name'=> $this->last_name,
		 	'email'=> $this->email,
		 	'password'=> $this->password,
		 	'newsletter'=> $this->newsletter,
		 	'address'=> $this->address,
		 	'city'=> $this->city,
		 	'country'=> $this->country,
		 	'phone_number'=> $this->phone_number,
		 	'date_of_birth'=> $this->date_of_birth,
		 	'account_type'=> $this->account_type,
		 	'password_reset_token'=> $this->password_reset_token
		 	];

		 }
			return $result;
	}
	public function delete() {
		if ($this->id != null) {
			$q_deleteCategory = $this->db->prepare("
				DELETE
				FROM `users`
				WHERE `id` = :id
				");
			$q_deleteCategory->bindParam(':id', $this->id);
			$result = $q_deleteCategory->execute();
			$this->id = null;
			return $result;
		}
	}
	public static function userId() {
		require_once "./helper.class.php";
		Helper::session_start();

		if(!isset($_SESSION['user_id'])) {
			return false;
		}
		return $_SESSION['user_id']; 
	}
	public function login($email, $password) {
		$q_checkUser = $this->db->prepare("
			SELECT *
			FROM `users`
			WHERE `email` = :email
			AND `password` = :password
			");
		$password = md5($password);
		$q_checkUser->bindParam(":email",$email);
		$q_checkUser->bindParam(":password",$password);
		$q_checkUser->execute();
		$userInfo = $q_checkUser->fetch();

		if(!$userInfo) {
			return false;
		}
		require_once "./helper.class.php";
		Helper::session_start();
		$_SESSION['user_id'] = $userInfo['id'];
		$_SESSION['user'] = $userInfo;
		return true;
	}
	public static function logout() {
		require_once "./helper.class.php";
		Helper::session_destroy();
	}

	public static function isAdmin() {
		require_once './helper.class.php';
		Helper::session_start();
		if(isset($_SESSION['user'])
		   && isset($_SESSION['user']['account_type'])
		   && $_SESSION['user']['account_type'] == 'admin') {
			return true;
		}
			return false;
	}
	public function getCart() {
		$user_id = $this->userId();
		if (!$user_id) {return false;}

		$q_getCart = $this->db->prepare("
			SELECT
			`shopping_cart`.`id`, 
			`products`.`title`,
			`products`.`price`,
			`shopping_cart`.`quantity`
			FROM `products`,`shopping_cart`
			WHERE `shopping_cart`.`id_p` = `products`.`id`
			AND `shopping_cart`.`id_u` = :id_u
			");
		$q_getCart->bindParam(":id_u",$user_id);
		$q_getCart->execute();
		$cart = $q_getCart->fetchAll();

		for ($i=0; $i < count($cart); $i++) {
			$cart[$i]['total_price'] = $cart[$i]['quantity'] * $cart[$i]['price'];
		}

		return $cart;

	}
	public function number_of_items_in_cart() {
    $user_id = $this->userId();
    if (!$user_id) { return false; }

    $q_getNumberOfProducts = $this->db->prepare("
      SELECT count(*)
      FROM `shopping_cart`
      WHERE `id_u` = :id_u
    ");
    $q_getNumberOfProducts->bindParam(':id_u', $user_id);
    $q_getNumberOfProducts->execute();
    return $q_getNumberOfProducts->fetchColumn();
  }

}
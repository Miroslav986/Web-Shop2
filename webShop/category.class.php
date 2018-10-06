<?php

class Category { 

	private $db;
	private $config;
	public $id;
	public $title;

	function __construct($id = NULL) {
		$this->db = require "./db.inc.php";
		$this->config = require "./config.inc.php";

		if($id != NULL) {
			$q_getCategori= $this->db->prepare("
				SELECT * 
				FROM `category`
				WHERE `id` = :id
				");
			$q_getCategori->bindParam(":id",$id);
			$q_getCategori->execute();
			$category = $q_getCategori->fetch();
			$this->id = $category['id'];
			$this->title = $category['title'];
		}

	}
	public function all() {
		$q_getAllCategory=$this->db->prepare("
			SELECT * 
			FROM `category`
			");
		$q_getAllCategory->execute();
		return $q_getAllCategory->fetchAll();
	}

	public function product() {
		if($this->id == null) {
			return [];
		} $q_getProductFromCategory = $this->db->prepare("
			SELECT * 
			FROM `products`
			WHERE `id_c`= :id_c
			");
		$q_getProductFromCategory->bindParam(":id_c", $this->id);
		$q_getProductFromCategory->execute();
		return $q_getProductFromCategory->fetchAll();
	}

	public function save() {
		if($this->id == NULL) {
			return $this->insert_category();
		} else {
			return $this->update_category();
		}
	}
	public function insert_category() {
		$q_insertCategory = $this->db->prepare("
			INSERT INTO `category` 
			(`title`)
			VALUES
			(:title)
			");
		$q_insertCategory->bindParam(":title", $this->title);
		$rezult = $q_insertCategory->execute();
		$this->id = $this->db->lastInsertId();
		return $rezult;
	}

	public function update_category() {

		$q_updateCategory = $this->db->prepare("
			UPDATE `category`
			SET 
			`title` = :title
			WHERE `id` = :id
			");

		$q_updateCategory->bindParam(":id",$this->id);
		$q_updateCategory->bindParam(":title",$this->title);
		return $q_updateCategory->execute();
	}
	public function delete() {
		if($this->id != null) {
		$q_deleteCategory = $this->db->prepare("
			DELETE
			FROM `category`
			WHERE `id` = :id
			");
		$q_deleteCategory->bindParam(":id",$this->id);
		$rezult = $q_deleteCategory->execute();
		$this->id=null;
		return $rezult;
		}

	}

}
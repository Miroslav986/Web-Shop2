<?php

class Comment {
	private $db;
	private $config;
	public $id;
	public $id_p;
	public $id_u;
	public $comment;
	public $comment_date;

	function __construct($id = null) {
		$this->db = require "./db.inc.php";
		$this->config = require "./config.inc.php";

		if($id != null) {
			$q_getCommentInfo = $this->db->prepare("
				SELECT *
				FROM `comments`
				WHERE `id`= :id
			
			");
			$q_getCommentInfo->bindParam(":id",$id);
			$q_getCommentInfo->execute();
			$commentInfo = $q_getCommentInfo->fetch();

			$this->id = $commentInfo['id'];
			$this->id_p = $commentInfo['id_p'];
			$this->id_u = $commentInfo['id_u'];
			$this->comment = $commentInfo['comment'];
			$this->comment_date = $commentInfo['comment_date'];

		}
	}
	public function save() {
		if( $this->id == null) {
			return $this->insert();
		} else {
			return $this->update();
		}
	}
	public function insert() {
		$q_insertComment=$this->db->prepare("
			INSERT INTO `comments`
			(`id_p`,`id_u`,`comment`)
			VALUES
			(:id_p,:id_u,:comment)
			");
		$q_insertComment->bindParam(":id_p",$this->id_p);
		$q_insertComment->bindParam(":id_u",$this->id_u);
		$q_insertComment->bindParam(":comment",$this->comment);
		$result = $q_insertComment->execute();
		$this->id=$this->db->lastInsertId();
		return $result;
	}
	public function update() {
		$q_updateComment = $this->db->prepare("
			UPDATE `comments`
			SET 
			`id_p` = :id_p,
			`id_u` = :id_u,
			`comment`= :comment
			WHERE `id` = :id
			");
		$q_updateComment->bindParam(":id", $this->id);
		$q_updateComment->bindParam(":id_p", $this->id_p);
		$q_updateComment->bindParam(":id_u", $this->id_u);
		$q_updateComment->bindParam(":comment", $this->comment);
		$q_updateComment->execute();
	}
	public function delete() {
		if($this->id != null) {
		$q_deleteComment = $this->db->prepare("
			DELETE
			FROM `comments`
			WHERE `comments`.`id` = :id
			");
		$q_deleteComment->bindParam(":id", $this->id);
		$result = $q_deleteComment->execute();
		$this->id = null;
		return $result;
		}
	}
	public function allComments($id) {
		$q_allComments = $this->db->prepare("
			SELECT *
			FROM `comments`,`products`,`users`
			WHERE `comments`.`id_p` = `products`.`id`
			AND `products`.`id` = :id_all
		

			");
		$q_allComments->bindParam(':id_all',$id);
		$q_allComments->execute();
		return $q_allComments->fetchAll();
	}

}
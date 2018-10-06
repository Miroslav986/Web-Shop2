<?php

$db = require "./db.inc.php";
$config = require "./config.inc.php";


$q_createUserTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `users`
	(
	`id` int AUTO_INCREMENT,
	`name` varchar(50),
	`last_name` varchar(50),
	`email` varchar(100) UNIQUE,
	`password` varchar(50),
	`newsletter` boolean DEFAULT false,
	`address` varchar(100),
	`city` varchar(50),
	`country` varchar(100),
	`phone_number` varchar(30),
	`date_of_birth` date,
	`account_type` enum('user','admin') DEFAULT 'user',
	`password_reset_token` varchar(255),
	`registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	)	
	");

$q_createUserTable->execute();

$q_getUsers = $db->prepare("
	SELECT *
	FROM `users`
	");
$q_getUsers->execute();
$numOfusers = $q_getUsers->rowCount();

if($numOfusers == 0) {

	$q_inssertAdministrator = $db->prepare("
		INSERT INTO `users`
		(`email`,`password`,`account_type`)
		VALUES
		(:email ,:password ,:account_type )
		");
	$q_inssertAdministrator->bindParam(":email",$config["default_admin_email"]);
	$q_inssertAdministrator->bindParam(":password", $config["default_admin_password"]);
	$q_inssertAdministrator->bindParam(":account_type", $config["default_admin_account_type"]);

	$q_inssertAdministrator->execute();
} 

$q_createCategoryTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `category`
	(
	`id` INT AUTO_INCREMENT,
	`title` varchar(50),
	PRIMARY KEY (`id`)
	)
	");
$q_createCategoryTable->execute();



$q_createProductTable = $db->prepare("

	CREATE TABLE IF NOT EXISTS `products`
	(
	`id` INT AUTO_INCREMENT,
	`title` varchar(200),
	`description` text,
	`price` decimal(10,2),
	`img` varchar(255),
	`id_c` int,
	PRIMARY KEY (`id`)
	)
	");
$q_createProductTable->execute();

$q_createCommentsTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `comments`
	(
	`id` int AUTO_INCREMENT,
	`id_p` int,
	`id_u` int,
	`comment` text,
	`comment_date` datetime DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	)
	");
$q_createCommentsTable->execute();


$q_createPurchaseTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `purchase`
	(
	`id` int AUTO_INCREMENT,
	`id_p` int,
	`id_u` int,
	`date_of_purchase` datetime,
	`purchase_type` ENUM('card','pod') DEFAULT NULL,
	`quantity` int,
	PRIMARY KEY (`id`)
	)
	");
$q_createPurchaseTable->execute();

$q_createShoppingCartTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `shopping_cart`
	(
	`id` int AUTO_INCREMENT,
	`id_p` int,
	`id_u` int,
	`quantity` int,
	PRIMARY KEY (`id`)
	)
	");
$q_createShoppingCartTable->execute();
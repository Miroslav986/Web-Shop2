<?php

require_once "./user.class.php";

if (!User::isAdmin() ) {
	header('location: ./index.php');
	die('You have to be administrator to access this page.');
}
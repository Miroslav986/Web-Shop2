<?php

require_once "./helper.class.php";
Helper::session_destroy();
header('location: ./index.php');
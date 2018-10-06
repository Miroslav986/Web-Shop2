<?php


//       --- testiranje za session -----

// User::logout();


require_once "./comment.class.php";

//  //Unosenje komentara
//  $k = new Comment();
//  $k->id_p = 22;
//  $k->id_u = 1;
//  $k->comment = "Ovo je NOVI PROIZVOD!!  treba ga kupiti";

// $r = $k->save();
//  var_dump($r);
	
 // Update komentara
// $k2 = new Comment(27);
// $k2->comment = "Ovo je novi komentar i nema bolji !!!";
// $k2->save();

//  delete komentara
// $k= new Comment(4);
// $k->delete();

// require_once "./product.class.php";k
 //Unosenje proizvoda
 // $p = new Product();
 // $p->title = "mikser";
 // $p->description = "jearpojp";
 // $p->price = "111.11";
 // $p->img = "img";

 // $p->id_c = 1;

 // $r = $p->save();
 // var_dump($r);

 // Update proizvoda
// $p2 = new Product(2);
// $p2->title = "frizider";
// $p2->save();

// $p2 = new Product(3);

// $p2->delete();

// var_dump($p2);
//require_once './category.class.php';

 //delete kategorije
 //$c = new Category(10);
 //$c->delete();


 


//  Unosenje kategorije
// $c = new Category();
// $c->title = "naslov";

//$r = $c->save();
// var_dump($r);

// // Update kategorije
// $c1 = new Category(9);
 //$c1->title = "automobili";
 //$c1->save();

// var_dump($c1);


// require_once './user.class.php';


// Unosenje korisnika
// $u = new User();
// $u->name = "Mica";
// $u->last_name = "last_name";
// $u->email = "email";
// $u->password = "password";
// $u->newsletter = true;
// $u->address = "Timocka 7/3";
// $u->city = "city";
// $u->country = "country";
// $u->phone_number = "+381 60 12 34 567";
// $u->date_of_birth = "1991-01-22";
// $u->account_type = "admin";
// $u->password_reset_token = "123...";
// $r = $u->save();
// var_dump($r);

// Update korisnika
// $u1 = new User(2);
// $u1->email = "new_email@domain.com";
// $u1->save();

// var_dump($u1);
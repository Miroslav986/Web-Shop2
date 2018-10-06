<?php
require_once "./user.only.php";
require_once "./user.class.php";
require_once "./helper.class.php";

Helper::session_start();
$u = new User($_SESSION['user_id']);

if(isset($_POST['update_settings'])) {
	$errors=[];

//   ----   OLD PASSWORD ---------

	if(!isset($_POST['old_password']) || ($_POST['old_password']) == '' ) {
		$errors[] = "Old password is reqired."; 
	}
//   ------   Check old password  --------- 

	if(empty($errors) && md5($_POST['old_password']) != $u->password )	{
		$errors[] = "Wrong old password";
	}
//   ------  email entered  --------
	if(!isset($_POST['email']) || $_POST['email'] == '') {
		$errors[] = "E-mail address is required.";
	} 	
//   ---------  new password  ------
	if(isset($_POST['new_password']) && $_POST['new_password'] != '') {
		if($_POST['new_password'] != $_POST['new_password_again']) {
		$errors[] = 'Passwords don\'t match' ;
	}
	}

	if(empty($errors)) {
		$u->email = $_POST['email'];
		if(isset($_POST['new_password']) && $_POST['new_password'] != '') {
		$u->password = md5($_POST['new_password']);
    }
    $u->name = $_POST['name'];
    $u->last_name = $_POST['last_name'];
		$u->newsletter = (isset($_POST['newsletter']) && $_POST['newsletter'] == 'on' ) ? 1 : 0;		
		$u->address = $_POST['address'];
		$u->city = $_POST['city'];
		$u->country = $_POST['country'];
		$u->phone_number = $_POST['phone_number'];
		$u->date_of_birth = (isset($_POST['date_of_birth']) && $_POST['date_of_birth'] != '' ) ? $_POST['date_of_birth'] : null ;
		
		$update = $u->save();
	}
 // var_dump($update);
}
?>

<?php include "./layout/header.php";  ?> 

<h1>Settings</h1>
<?php
if (isset($update) && $update) {
	Helper::success('User information updated successfully.');
}

if(isset($_POST['update_settings']) && !empty($errors)) {
	Helper::error($errors);
}


?>
<form action="settings.php" method="post">

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputEmail1">Old password</label>
    <input type="password" name="old_password" class="form-control" id="inputPassword" aria-describedby="emailHelp" placeholder="Old password">
    <small id="emailHelp" class="form-text text-muted">Old password is required in order to change any of the settings.</small>
  </div>
  </div>
  </div>

 <div class="row">
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Email</label>
    <input type="email" name="email" class="form-control" id="inputEmail" value="<?= $u->email  ?>">
  </div>
 </div>
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Phone number</label>
    <input type="number" name="phone_number" value="<?= $u->phone_number ?>" class="form-control" id="inputPhone" placeholder="Phone number">
  </div>
 </div>
 </div>

 <div class="mt-4"><h3>Password change</h3> </div>

 <div class="row">
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputEmail1">New password</label>
    <input type="password" name="new_password" class="form-control" id="inputNewPassword" aria-describedby="emailHelp" placeholder="New password">
   
  </div>
  </div>
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">New password again</label>
    <input type="password" name="new_password_again" class="form-control" id="inputPasswordAgain" placeholder="New password again">
  </div>
 </div>
 </div>
<h3 class="mt-4">Profile information</h3>

 <div class="row">
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Name</label>
    <input type="text" name="name" value="<?= $u->name ?>" class="form-control" id="inputName" placeholder="Name">
  </div>
 </div>
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Last name</label>
    <input type="text" name="last_name" value="<?= $u->last_name ?>" class="form-control" id="inputLastName" placeholder="Last name">
  </div>
 </div>
 </div>

 <div class="row">
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Address</label>
    <input type="text" name="address" value="<?= $u->address ?>" class="form-control" id="inputAdress" placeholder="Address">
  </div>
 </div>
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">City</label>
    <input type="text" name="city" value="<?= $u->city ?>" class="form-control" id="inputCity" placeholder="City">
  </div>
 </div>
 </div>

 <div class="row">
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Country</label>
    <input type="text" name="country" value="<?= $u->country ?>" class="form-control" id="inputCountry" placeholder="Country">
  </div>
 </div>
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Date of birth</label>
    <input type="date" name="date_of_birth" value="<?= $u->date_of_birth ?>" class="form-control" id="inputDate" placeholder="Date of birth">
  </div>
 </div>
 </div>
 <div class="col-md-12">
    <div class="form-check">
      <label class="form-check-label">
        <input type="checkbox" name="newsletter" class="form-check-input" <?= ($u->newsletter) ? 'checked' : null; ?> />
        I would like to receive newsletter.
      </label>
    </div>
  </div>


<div class="row">
  <div class="col-md-12 clearfix">

  <button type="submit" name="update_settings" class="btn btn-primary mt-4 mb-4 float-right">Update setings</button>

  </div>
  </div>
</form>

<?php include "./layout/footer.php";  ?>
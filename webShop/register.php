<?php 

require_once './helper.class.php';
$errors = [];

if(isset($_POST['submit'])) {

	if(!isset($_POST['email']) || $_POST['email'] == '') {
		$errors[] = 'E-mail address is required.';
	}
	if(!isset($_POST['password']) || $_POST['password'] == '') {
		$errors[] = 'Password is required.';
	}
	if(!isset($_POST['password1']) || $_POST['password1'] == '') {
		$errors[] = "You have to enter password twice.";
	}
	if($_POST['password'] != $_POST['password1']) {
		$errors[] = "Passwords don\'t match.";
	}
	if ( !isset($_POST['tos']) || $_POST['tos'] != 'on' ) {
		$errors[] = 'You have to agree to the terms of service.';
	}


	if(empty($errors)) {
		require_once './user.class.php';
		$u = new User();
		$u->name = $_POST['name']; 
		$u->last_name = $_POST['last_name']; 
		$u->email = $_POST['email']; 
		$u->password = md5($_POST['password']); 
		$u->newsletter = (isset($_POST['newsletter']) && $_POST['newsletter'] == 'on') ? 1 : 0 ; 
		$u->address = $_POST['address']; 
		$u->city = $_POST['city']; 
		$u->country = $_POST['country']; 
		$u->phone_number = $_POST['phone_number']; 
		$u->date_of_birth = (isset($_POST['date_of_birth'])  && $_POST['date_of_birth'] != '') ? $_POST['date_of_birth'] : null ; 
		$registration = $u->save();
		if($registration) {
			header("Location: ./login.php?registration=success");
		}
	}
}
?>

<?php include "./layout/header.php";  ?>
<h2>Registration</h2>

<?php 
if(isset($registration) && $registration) {
	Helper::success('Registration successfull.');
}
if(isset($registration) && !$registration) {
	Helper::error('Failed to add user to database.');
}
if( !empty($errors)) {
	Helper::error($errors);
}


?>

<form action="register.php" method="post" >
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="inputEmail4" class="col-form-label">Name</label>
			<input type="text" name="name" class="form-control" id="inputName" placeholder="Name">
		</div>
		<div class="form-group col-md-6">
			<label for="inputPassword4" class="col-form-label">Last name</label>
			<input type="text" name="last_name" class="form-control" id="inputLastName" placeholder="Last name">
		</div>
	</div>
	<div class="form-group">
		<label for="inputAddress" class="col-form-label">E-mail</label>
		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="E-mail">
	</div>
	<div class="form-row">
	<div class="form-group col-md-6">
			<label for="inputEmail4" class="col-form-label">Password</label>
			<input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
		</div>
		<div class="form-group col-md-6">
			<label for="inputPassword4" class="col-form-label">Password confirm</label>
			<input type="password" name="password1" class="form-control" id="inputPasswordConfirm" placeholder="Password confirm">
		</div>
	</div>	
	<div class="form-group">
		<label for="inputAddress" class="col-form-label">Address</label>
		<input type="text" name="address" class="form-control" id="inputAddress" placeholder="Address">
	</div>
	<div class="form-row">
	<div class="form-group col-md-6">
			<label for="inputEmail4" class="col-form-label">City</label>
			<input type="text" name="city" class="form-control" id="inputCity" placeholder="City">
		</div>
		<div class="form-group col-md-6">
			<label for="inputPassword4" class="col-form-label">Country</label>
			<input type="text" name="country" class="form-control" id="inputCountry" placeholder="Country">
		</div>
	</div>	
	<div class="form-row">
	<div class="form-group col-md-6">
			<label for="inputEmail4" class="col-form-label">Phone number</label>
			<input type="number" name="phone_number" class="form-control" id="inputPhoneNumber" placeholder="Phone number">
		</div>
		<div class="form-group col-md-6">
			<label for="inputPassword4" class="col-form-label">Date of birth</label>
			<input type="date" name="date_of_birth" class="form-control" id="inputDateOfBirth" >
		</div>
	</div>	
	 <div class="col-md-12">
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" name="newsletter" class="form-check-input" checked />
          I would like to receive newsletter.
        </label>
      </div>
    </div>
      <div class="col-md-12">
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" name="tos" class="form-check-input" />
          I read and agree to Terms of Service.
        </label>
      </div>
    </div>
     <div class="col-md-12 mb-5">
      <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
    </div>


	<?php include "./layout/footer.php";  ?>
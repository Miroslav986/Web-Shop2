<?php


if(isset( $_POST['email']) && isset($_POST['password']) ){

	require_once "./user.class.php";
	$u= new User();
	$login = $u->login($_POST['email'], $_POST['password']);
	if($login) {
		header('Location: ./index.php');
	}
}
?>

<?php include "./layout/header.php";  ?>
<?php 
 if(isset($_GET['registration']) && $_GET['registration'] == 'success') {
 	// require_once "helper.class.php";
 	Helper::success("Account successfully created.");
 }
?>


 <?php if(isset($login) && !$login ): ?>
 	<div class="row"> 
 		<div class="col-md-12">
 			<?php  
 				require_once "./helper.class.php";
 				Helper::error('Wrong username and/or password.', 'Login error!');
 			?>
 		</div>
 	</div>

 <?php endif; ?>	

 <div class="row" >
	<div class="col-md-3" ></div>
	<div class="col-md-6">
		<h2>Login:</h2>
		<form action="./login.php" method="post" class=" mt-4">
			<div class="form-group">
				<label for="exampleInputEmail1">Email address</label>
				<input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
				<!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
			</div>
			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
			</div>

			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</div>



</div>




<?php include "./layout/footer.php";  ?>
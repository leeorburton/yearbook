<?php 
	include_once 'db.php';

// if user has submitted form, validate inputs
	if(isset($_GET['submit'])){

		$errors = array();
		$pass_errors = array();

	// variables to mark which fields have errors so we can later give user appropriate feedbackand highlight input(s)
		$usernameOk = 1;
		$emailOk = 1;
		$fnOk = 1;
		$lnOk = 1;
		$jobOk = 1;
		$inspireOk = 1;
		$wordsOk = 1;
		$dislikeOk = 1;
		$passOk = 1;
	
		$pass = $_POST['password'];
		$confirmpass = $_POST['confirmpass'];

		// If user leaves multiple fields empty, we only want to tell them they have to fill it out once. This variable only needs to be changed to true once for error message to show.
		$empty = 0;

		// check if any fields are empty. If yes, mark appropriate variable so that we can highlight the specific input
		if (empty($_POST['username'])){
			$empty = 1;
			$usernameOk = 0;
		}
		if (empty($_POST['email'])){
			$empty = 1;
			$emailOk = 0;
		}
		if (empty($_POST['fn'])){
			$empty = 1;
			$fnOk = 0;
		}
		if (empty($_POST['ln'])){
			$empty = 1;
			$lnOk = 0;
		}
		if (empty($_POST['job'])){
			$empty = 1;
			$jobOk = 0;
		}
		if (empty($_POST['inspire'])){
			$empty = 1;
			$inspireOk = 0;
		}
		if (empty($_POST['words'])){
			$empty = 1;
			$wordsOk = 0;
		}
		if (empty($_POST['dislike'])){
			$empty = 1;
			$dislikeOk = 0;
		}

		// if any field is empty, show error message
		if($empty === 1){
			$errors[] .= 'Please fill out required field(s)';
		}


		// limit response length
		if (strlen($_POST['username']) > 20 ){
			$usernameOk = 0;
			$errors[] .= "Username cannot be over 20 characters"; 
		}
		if (strlen($_POST['fn']) > 20 ){
			$fnOk = 0;
			$errors[] .= "First name cannot be over 20 characters"; 
		}
		if (strlen($_POST['ln']) > 20 ){
			$lnOk = 0;
			$errors[] .= "Last name cannot be over 20 characters"; 
		}
		if (strlen($_POST['job']) > 30 ){
			$jobOk = 0;
			$errors[] .= "Dream job cannot be over 30 characters"; 
		}
		if (strlen($_POST['inspire']) > 30 ){
			$inspireOk = 0;
			$errors[] .= "Inspiration cannot be over 30 characters"; 
		}
		if (strlen($_POST['words']) > 280 ){
			$wordsOk = 0;
			$errors[] .= "Your quote cannot be over 280 characters"; 
		}
		if (strlen($_POST['dislike']) > 30 ){
			$dislikeOk = 0;
			$errors[] .= "Inspiration cannot be over 30 characters"; 
		}

		// check for special characters -- prevent SQL injection
		$string_exp = "/^[A-Za-z0-9 .'-:()1#@&%!,]+$/";
		$special = 0;

        if (!preg_match($string_exp, $_POST['username'])){
			$usernameOk = 0;
			$special = 1;
		}
		if (!preg_match($string_exp, $_POST['fn'])){
			$fnOk = 0;
			$special = 1;
		}
		if (!preg_match($string_exp, $_POST['ln'])){
			$lnOk = 0;
			$special = 1;
		}
		if (!preg_match($string_exp, $_POST['job'])){
			$jobOk = 0;
			$special = 1;
		}
		if (!preg_match($string_exp, $_POST['inspire'])){
			$inspireOk = 0;
			$special = 1;
		}
		if (!preg_match($string_exp, $_POST['words'])){
			$wordsOk = 0;
			$special = 1;
		}
		if (!preg_match($string_exp, $_POST['dislike'])){
			$dislikeOk = 0;
			$special = 1;
		}

		if($special != 0){
			$errors[] .= 'Field cannot contain special characters.';
		}

		// check if username is available
		$username = $_POST['username'];
		$sql = mysqli_query($con, "SELECT * from accounts WHERE username = '$username'");
		if (mysqli_num_rows($sql) > 0) {
			$errors[] .= "Sorry, that username is taken!";
			$usernameOk = 0;
		}

		// check if email is formatted correctly
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] .= "Your email is not valid!";
			$emailOk = 0;
		}

	// validate password
	// must be between 8 and 20 characters, have at least 1 number, 1 uppercase & 1 lowercase letter, 1 special character, & cannot contain spaces
		if ($pass != $confirmpass){
			$errors[] .= "Your passwords do not match!";
		}

		if (strlen($pass) < 8 || strlen($pass) > 20) {
			$pass_errors[] .= "between 8 and 20 characters";
			$passOk = 0;
		}
		if (!preg_match("/\d/", $pass)) {
			$pass_errors[] .= "at least 1 number";
			$passOk = 0;
		}
		if (!preg_match("/[A-Z]/", $pass)) {
			$pass_errors[] .= "at least 1 capital letter";
			$passOk = 0;
		}
		if (!preg_match("/[a-z]/", $pass)) {
			$pass_errors[] .= "at least 1 lowercase letter";
			$passOk = 0;
		}
		if (!preg_match("/\W/", $pass)) {
			$pass_errors[] .= "at least 1 special character";
			$passOk = 0;
		}
		if (preg_match("/\s/", $pass)) {
			$pass_errors[] .= "cannot contain any spaces";
			$passOk = 0;
		}

		

	// file validation
	$target_file = $_FILES["image"]["name"];
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	if(empty($_FILES["image"]["tmp_name"])){
		$errors[] .= "Sorry, you must upload a photo to create an account.";
		$uploadOk = 0;
	} else {
	// Check if file is an image
		$check = getimagesize($_FILES["image"]["tmp_name"]);

		if($check == false) {
			$errors[] .= "File must be an image.";
			$uploadOk = 0;
		}

		// Check if file already exists
		if (file_exists($target_file)) {
			$errors[] .= "Sorry, this image already exists!";
			$uploadOk = 0;
		}

		// Check file size
		if ($_FILES["image"]["size"] > 500000) {
			$errors[] .= "Sorry, your image cannot be over 500kb";
			$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$errors[] .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
	}

	// if there are no errors, create account
	if(count($pass_errors) === 0 && count($errors) === 0 && $uploadOk === 1){
			include 'create_account.php';
		}
	}
	
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

  </head>
  <body>
		<nav>
			<ul class="nav justify-content-end pt-2 pe-2 hidden">
				<li class="nav-item">
				<a class="nav-link active" aria-current="page" href="#">Login / Register</a>
				</li>
			</ul>
		</nav>
		
		<div class="wrapper">
			<section>
				<div class="container col-md-12 pt-75 pb-25">
					<h1 class="d-flex justify-content-center letter-space">Create an Account</h1>
					<h2 class="d-flex justify-content-center letter-space blue">Show your classmates who you are!</h2>
				</div>
			</section>

			<section>
				<!-- only once form is submitted and if there are errors, display error container and loop through error messages -->
				<div class="container col-md-6">
					<?php if (isset($_GET['submit']) && (count($errors) != 0)){ ?>
					<div class="error-cont">
						<?php foreach ($errors as $error){
							echo '<p>' . $error . '</p>';
							} ?>
					</div>
					<?php } ?> 

					<!-- only once form is submitted and if there are password errors, display error container and loop through password error messages since these were designed into a list -->
					<?php if (isset($_GET['submit']) && (count($pass_errors) != 0)){ ?>
					<div class="error-cont pass-error-cont">
						<p>Password requires:</p>
						<ul>
						<?php foreach ($pass_errors as $error){
							echo '<li>' . $error . '</li>';
							} ?>
						</ul>
					</div>
					<?php } ?> 
						
					<!-- Every input has a conditional that if its dependant variable shows an error, it will be given an is-invalid class name so that the input box is highlighted in red -->
					<!-- There is a second conditional so that if the form is not submitted due to an error, input values are repopulated with user's initial response to prevent them from having to redo the whole form again. -->
					<form action='?submit' method="post" autocomplete="off" enctype="multipart/form-data" class='needs-validation'>
						
							<div class="mb-3 form-floating">
								<input type="text" name="username" placeholder="Username" id="username" class="form-control <?php if($usernameOk === 0) echo 'is-invalid' ?>" <?php if(!empty($_GET)) echo 'value="' . $_POST['username'] . '"' ?>>
								<label for="username">Username:</label>
							</div>

							<div class="mb-3 form-floating">
								<input type="text" name="email" placeholder="Email:" id="email" class="form-control <?php if($emailOk === 0) echo 'is-invalid' ?>"  <?php if(!empty($_GET)) echo 'value="' . $_POST['email'] . '"' ?>>
								<label for="email">Email:</label>

							</div>

							<div class="mb-3 form-floating">
								<input type="password" class="form-control <?php if($passOk === 0) echo 'is-invalid' ?>" name="password" placeholder="Password:" id="password" <?php if(!empty($_GET)) echo 'value="' . $_POST['password'] . '"' ?>>
								<label for="password">Password:</label>
								<div id="passwordHelpBlock" class="form-text">
									Must be 8-20 characters, contain upper and lower case letters, numbers, and special characters.
								</div>
							</div>

							<div class='mb-3 form-floating'>
								<input type="password" class="form-control <?php if($passOk === 0) echo 'is-invalid' ?>" id="confirmpass" name="confirmpass" placeholder="Confirm Password:"  <?php if(!empty($_GET)) echo 'value="' . $_POST['confirmpass'] . '"' ?>>
								<label for="confirmpass">Confirm Password:</label>
							</div>
							
							<div class="mb-3">
								<label for="image" class="form-label">Photo of You:</label>
								<input type="file" class="form-control <?php if($uploadOk === 0) echo 'is-invalid'?>" id="image" name="image">
								<div id="imageHelpBlock" class="form-text">
  									Must be less than 500kb and jpg, jpeg, png, or gif.
								</div>
							</div>
							<div class="mb-3 form-floating">
								<input type="text" class="form-control <?php if($fnOk === 0) echo 'is-invalid' ?>" id="fn" name="fn" placeholder="First Name:" <?php if(!empty($_GET)) echo 'value="' . $_POST['fn'] . '"' ?>>
								<label for="fn">First Name:</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="text" class="form-control <?php if($lnOk === 0) echo 'is-invalid' ?>" id="ln" name="ln" placeholder="Last Name:"<?php if(!empty($_GET)) echo 'value="' . $_POST['ln'] . '"' ?>>
								<label for="ln">Last Name:</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="text" class="form-control <?php if($jobOk === 0) echo 'is-invalid' ?>" id="job" name="job" placeholder="Future Occupation:" <?php if(!empty($_GET)) echo 'value="' . $_POST['job'] . '"' ?>>
								<label for="job">Future Occupation:</label>

							</div>
							<div class="mb-3 form-floating">
								<input type="text" class="form-control <?php if($wordsOk === 0) echo 'is-invalid' ?>" id="words" name="words" placeholder="Known to Say:" <?php if(isset($_GET['submit'])) echo 'value="' . $_POST['words'] . '"' ?>>
								<label for="words">Known to Say:</label>
								<div id="quoteHelpBlock" class="form-text">
  									Up to 280 characters long.
								</div>
							</div>
							<div class="mb-3 form-floating">
								<input type="text" class="form-control <?php if($inspireOk === 0) echo 'is-invalid' ?>" id="inspire" name="inspire" placeholder="Inspired By:" <?php if(isset($_GET['submit'])) echo 'value="' . $_POST['inspire'] . '"' ?>>
								<label for="inspire">Most Inspired By:</label>

							</div>
							<div class="mb-3 form-floating">
								<input type="text" class="form-control <?php if($dislikeOk === 0) echo 'is-invalid' ?>" id="dislike" name="dislike" placeholder="Dislikes:" <?php if(isset($_GET['submit'])) echo 'value="' . $_POST['dislike'] . '"' ?>>
								<label for="dislike">Dislikes:</label>
							</div>
							<input type="submit" class="btn btn-primary mb-3 krub white text-shadow" value="Create my Profile">
							<a class="d-flex justify-content-center pt-3 text-center krub purple mb-5" href="login.php">Already have an account?<br/> Login</a>
					</form>
				</div>
			</section>
		</div>
	</body>
</html>
<?php
session_start();

// if user is not logged in, navigate to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit();
}

include_once 'db.php';


$stmt = $con->prepare('SELECT username, fn, ln, email, job, words, inspire, dislike FROM accounts WHERE id = ?');
// retrieve data via session id
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($username, $fn, $ln, $email, $job, $words, $inspire, $dislike);
$stmt->fetch();
$stmt->close();


// only execute once form is submitted
if(isset($_GET['update'])){

    $errors = array();
    $pass_errors = array();

    $emailOk = 1;
    $fnOk = 1;
    $lnOk = 1;
    $jobOk = 1;
    $inspireOk = 1;
    $wordsOk = 1;
    $dislikeOk = 1;

    // password and email validation
    $empty = 0;

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

    // if any field is empty, return error message
    if($empty === 1){
        $errors[] .= 'Please fill out required field(s)';
    }


    // check input lengths
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

    // if used special character, only return message once but will be reflected with style
		if($special != 0){
			$errors[] .= 'Field cannot contain special characters.';
		}


    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] .= "Your email is not valid!";
        $emailOk = 0;
    }

    // if there are no errors, update record
    if(count($errors) === 0){
        include 'update.php';
    }
    
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <nav class="container col-md-10 col-xl-8 pb-25">
        <ul class="nav justify-content-between pt-4">
            <li class="nav-item">   
                <a class="nav-link noNo" aria-current="page" href="index.php">
                    <p class="text-secondary noNo"><i class="bi bi-chevron-left"></i> Classlist</p>
                </a>  
            </li>
            <li class="nav-item">
                <a class='nav-link active noNo' aria-current='page' href='profile.php'>
                    <p class='d-flex nav-color noNo'><i class='bi bi-person-circle pe-2'></i>My Profile</p>
                </a>
                <a class='nav-link text-end pt-1 noNo' aria-current='page' href='logout.php'>
                    <p class='nav-color noNo font-light'>Sign Out</p>
                </a>          
            </li>
        </ul>
    </nav>
        
    <section>
        <div class="container col-md-10 col-lg-8 col-xl-6 pb-50">
            <h1 class="big-h1">My Profile</h1>
        </div>
    </section>
    
    <section>
        <div class="container col-md-10 col-lg-8 col-xl-6 pb-50">
            <?php if(isset($_GET['updated'])){ ?>
				<div class="updated-cont">
					<p>Your profile was successfully updated</p>
				</div>
			<?php } 
                if(isset($_GET['imgupdated'])){ ?>
				<div class="updated-cont">
					<p>Your photo was successfully updated</p>
				</div>
			<?php } ?> 
            <div class="card shadow-sm">
                <div class="row g-0">
                    <div class="col-md-6" style="overflow: hidden; height: 400px;  aspect-ratio: 1;">
                        <div class="bgImage" style="background-image: url('displayImage.php?id=<?php echo $_SESSION['id'] ?>');"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-body ps-4">
                            <div class="card-title mt-4">
                                <p class="name noNo">
                                    <?php echo $fn . " " . $ln ?>
                                </p>
                            </div>

                            <div class="quote pt-0 mat-0 mb-4 font-light">
                                <p class="card-text mb-4">"<?php echo $words;?>"</p>
                            </div>
                            

                            <div class="card-text">
                                <ul>
                                    <li>
                                        <p>Future Occupation:</p>
                                        <p class='value'><?php echo $job; ?></p>
                                    </li>
                                    <li>
                                        <p>Most inspired by:</p>
                                        <p class='value'><?php echo $inspire;?></p>
                                    </li>
                                    <li>
                                        <p>Dislikes:</p>
                                        <p class='value'><?php echo $dislike;?></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // display form only once user chooses to edit profile and hide once updated successfully
            if (!empty($_GET) && !isset($_GET['updated']) && !isset($_GET['imgupdated'])){ ?>
                <section>
                    <div class="container pt-25 mt-5">
                        <h2 class='letter-space mb-3'>Edit Profile</h2>
                        <?php if(isset($_GET['update']) && (count($errors) != 0)){ 
                        foreach ($errors as $error){
                                echo "<p class='error-cont'>" . $error . "</p>";
                            }
                        } ?>

                        <form action="?update" method="post" autocomplete="off" enctype="multipart/form-data">

                            <div class="form-floating mb-3">
                                <input type="text" name="username" value="<?php echo $username ?>" id="username" class="form-control" disabled>
                                <label for="username">Username</label>
                                <div id='usernameHelp' class='form-text'>You cannot change your username.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="email" value="<?php echo $email;?>" id="email" class="form-control <?php if($emailOk === 0) echo 'is-invalid' ?>" placeholder="Email:">
                                <label for="email">Email:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="fn" value="<?php echo $fn; ?>" id="fn" class="form-control <?php if($fnOk === 0) echo 'is-invalid' ?>" placeholder="First Name:">
                                <label for="fn">First Name:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="ln" value="<?php echo $ln; ?>" id="ln" class="form-control <?php if($lnOk === 0) echo 'is-invalid' ?>" placeholder="Last Name:">
                                <label for="ln">Last Name:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="job" value="<?php echo $job; ?>" id="job" class="form-control <?php if($jobOk === 0) echo 'is-invalid' ?>" placeholder="Future Occupation:">
                                <label for="text">Future Occupation:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="words" value="<?php echo $words;  ?>" id="words" class="form-control <?php if($wordsOk === 0) echo 'is-invalid' ?>" placeholder="Known to Say:">
                                <label for="words">Known to Say:</label>
                                <div id="quoteHelpBlock" class="form-text">
  									Up to 280 characters long.
								</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="inspire" value="<?php echo $inspire; ?>" id="inspire" class="form-control <?php if($inspireOk === 0) echo 'is-invalid' ?>" placeholder="Inspired By:">
                                <label for="inspire">Most Inspired By:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="dislike" value="<?php echo $dislike; ?>" id="dislike" required class="form-control <?php if($dislikelOk === 0) echo 'is-invalid' ?>" placeholder="Dislikes:">
                                <label for="dislike">Dislikes:</label>
                            </div>

                            <div class="container col-md-4 mt-5">
                                <input type="submit" name="update" value="Update" class="btn btn-primary krub white text-shadow" style="width: 100%;" />
                            </div> 

                            <div class="container col-md-4 mt-2 pb-5">
                                <a href="profile.php" class="btn btn-secondary krub white text-shadow" style="width: 100%;">
                                    Discard and Exit
                                </a>
                            </div>
                        </form>
                    </div>
                </section>
            
            <?php
                }
            ?>

            <?php
                if(empty($_GET) || isset($_GET['updated']) || isset($_GET['imgupdated'])){
            ?>
            <div class="container col-md-4 mt-5">
                <a href="?edit" class="btn btn-primary mb-2 krub white text-shadow" style="width: 100%;">Edit Profile
                </a>
				<a href="editImg.php?edit">
                    <button type="submit" class="btn btn-secondary krub white" style="width: 100%;">Change Image</button>
                </a>
            </div>  
            <?php
                }
            ?>
        </div>
    </section>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
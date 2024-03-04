<?php

    session_start();

    // if user is not logged in, navigate to login page
    if (!isset($_SESSION['loggedin'])) {
	    header('Location: login.php');
	    exit();
    }

    include 'db.php';


    if(isset($_GET['edit'])){
        $sql = "SELECT * FROM accounts WHERE id=".$_SESSION['id'];
        $result = mysqli_query($con, $sql);

        if(!$result = $con->query($sql)){
            die('There was an error running the query [' . $con->error . ']');
        }
    }

    if(isset($_GET['updateImg'])){
        // file validation
	    $uploadOk = 1;
        $errors = array();


        if(empty($_FILES["image"]["tmp_name"])){
            $errors[] .= "Sorry, you must upload a photo to submit";
            $uploadOk = 0;
        } else {
        // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
    
            if($check == false) {
                $errors[] .= "File must be an image.";
                $uploadOk = 0;
            } else {
                $target_file = $_FILES["image"]["name"];
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
    
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
        }

        if(count($errors) == 0 && $uploadOk == 1) {
            if ($stmt = $con->prepare('UPDATE accounts SET photo = ?, imgname = ? WHERE id = ?')){
        
                $imgname = $_FILES['image']['name'];
                $type = $_FILES['image']['type'];
                $photo = file_get_contents($_FILES['image']['tmp_name']);
        
                $stmt->bind_param('ssi', $photo, $imgname, $_SESSION['id']);
                $stmt->execute();
                    
                header("Location: profile.php?imgupdated");
                } else {
                    // Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all three fields.
                }
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
        <div class="container col-md-10 col-lg-8 col-xl-6">

            <h1>Edit Image</h1>

            <img src="displayImage.php?id=<?php echo $_SESSION['id'];?>"
            class="pt-4 pb-4 edit-img" style='width:50%;'/>
            
            <?php 
                if(isset($errors) && count($errors) != 0){
                    foreach ($errors as $error){
                        echo "<p class='error-cont'>" . $error . "</p>";
                    }
                }
            ?>
            <form action="?updateImg" method="post" autocomplete="off" enctype="multipart/form-data">

                <div class="pt-2 mb-3 pb-2">
                    <label for="image" class="form-label">
                        Select a new image:
                    </label>
                    <input class="form-control" type="file" name="image" id="image" required>
                    <div id="imageHelpBlock" class="form-text">
  						Must be less than 500kb and jpg, jpeg, png, or gif.
					</div>
                </div>

                <div class="container col-md-4 mt-3 krub">
                    <button type="submit" name="update" value="Update" class="btn btn-primary krub white text-shadow" style="width: 100%;">Save and Exit</button>
                </div> 

                <div class="container col-md-4 mt-2 pb-5">
                    <a href="profile.php" class="btn btn-secondary krub white text-shadow" style="width: 100%;">
                        Discard and Exit
                    </a>
                </div>
            </form>
        </div>
    </section>


    </body>
</html>

<?php
session_start();

include_once 'db.php';

$sql = "SELECT * FROM accounts";

$result = mysqli_query($con, $sql);

if(!$result = $con->query($sql)){
    die('There was an error running the query [' . $con->error . ']');
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Home</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">


</head>
<body>
    <nav class="container col-md-10 col-xl-8 pb-25">
        <ul class="nav justify-content-end pt-4">
            <li class="nav-item">
                <?php 
                    if (isset($_SESSION['loggedin'])) {
                        echo "
                        <a class='noNo nav-link ' aria-current='page' href='profile.php'>
                            <p class='d-flex nav-color noNo'><i class='bi bi-person-circle pe-2'></i>My Profile</p>
                        </a>";
                        echo "<a class='nav-link text-end pt-1 noNo' aria-current='page' href='logout.php'>
                        <p class='nav-color noNo font-light'>Sign Out</p>
                    </a> ";
                    } else {
                        echo "<a class='noNo nav-link text-end' aria-current='page' href='login.php'>
                            <p class='blue noNo font-light'>Login / Register</p>
                        </a>";
                    }
                ?>              
            </li>
        </ul>
    </nav> 
        
        <div class="container col-md-10 col-xl-8 pb-50">
            <div class="layout-title">
                <h1 class="big-h1">Class Yearbook '24</h1>
                <h2 class="text-center fw-light blue">Your year, right here!</h2>
            </div>
        </div>

        <div class="container col-md-10 col-xl-8 pb-25">
            <h3 class='font-light'>Classlist:</h3>
        </div>

        <?php
                while($row = $result->fetch_assoc()) {
        ?> 
 
        <div class="container col-md-10 col-xl-8 mb-3">

            <div class="card shadow-sm">
                <div class="row g-0">
                    <div class="col-md-6" style="overflow: hidden; height: 400px;  aspect-ratio: 1;">
                        <div class="bgImage" style="background-image: url('displayImage.php?id=<?php echo $row['id'] ?>');"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-body ps-4">
                            <div class="card-title mt-4">
                                <p class="name noNo"><?php echo $row['fn'] . " " . $row['ln']; ?></p>
                            </div>

                            <div class="quote pt-0 mat-0 mb-4 font-light"><p>"<?php echo $row['words'];?>"</p></div>

                            <div class="card-text">
                                <ul>
                                    <li>
                                        <p>Future Occupation:</p>
                                        <p class='value'><?php echo $row['job'];?></p>
                                    </li>
                                    <li>
                                        <p>Most inspired by:</p>
                                        <p class='value'><?php echo $row['inspire'];?></p>
                                    </li>
                                    <li>
                                        <p>Dislikes:</p>
                                        <p class='value'><?php echo $row['dislike'];?></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4"></div>


        </div>

    <?php }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <nav>
        <ul class="nav justify-content-end pt-2 pe-2 hidden">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Login/ Register</a>
            </li>
        </ul>
    </nav>

    <div class="wrapper">
      <section>
        <div class="container col-md-6 pt-75 pb-25">
          <h1 class="d-flex justify-content-center letter-space">User Login</h1>
          <h2 class="d-flex justify-content-center letter-space blue">Welcome to the yearbook</h2>
        </div>
      </section>

      <section class="login">
        <div class="container col-md-6">
          <?php 
            if (isset($_GET['empty'])){
              echo "<p class='error-cont'>Please fill out required field(s)</p>";
            } 
            if (isset($_GET['incorrect'])){
              echo "<p class='error-cont'>Username and/or password is incorrect</p>";
            }
          ?>
          <form action="authenticate.php" method="post">
            <div class="mb-3 form-floating shadow-sm">
              <input type="text" class="form-control krub h50" id="username" name="username" placeholder="Username:" required>
              <label for='username'>Username:</label>
            </div>
    
            <div class="mb-3 form-floating shadow-sm">
              <input type="password" class="form-control krub h50" id="password" name="password" placeholder="Password:">
              <label for='password'>Password:</label>
            </div>
    
            <input type="submit" class="btn btn-primary krub white text-shadow h25" style="width: 100%;" value="Login">
            <a class="d-flex justify-content-center pt-3 krub" href="register.php">Create an account</a>
          </form>
        </div>
      </section>
    </div>
  </body>
</html>
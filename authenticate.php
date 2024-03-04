<?php
session_start();

include_once 'db.php';


// check if the data from the login form was submitted/exists
if ( !isset($_POST['username'], $_POST['password']) ) {
    header('Location: login.php?empty');
	exit();
}

// Prepare our SQL to prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int)
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, verify the password.
        // Note: passwords are hashed before being stored
        if (password_verify($_POST['password'], $password)){            
            // Verification success. User has logged-in
            // Create sessions, so we know the user is logged in
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id'] = $id;
            // once logged in, navigate to homepage
            header('Location: index.php');
        } else {
            // Incorrect password
            header('Location: login.php?incorrect');
            exit();
        }
    } else {
        // Incorrect username
        header('Location: login.php?incorrect');
        exit();

    }


	$stmt->close();
}
?>
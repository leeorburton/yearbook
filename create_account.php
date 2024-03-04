<?php

include_once 'db.php';

// this is also checked in the register.php file - but just in case
if (!isset($_POST['username'], $_POST['confirmpass'], $_POST['password'], $_POST['email'],$_POST['fn'], $_POST['ln'], $_POST['job'], $_POST['words'], $_POST['inspire'], $_POST['dislike'])) {
	// Could not get the data that should have been sent.
	echo 'Sorry, something went wrong.';
	exit();
}

// check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int)
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// checked in the register.php file but just in case
		// Username already exists
		echo 'Sorry, that username is taken!';
		exit();
	} else {
		// Username doesn't exists, insert new account
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, fn, ln, photo, imgname, job, words, inspire, dislike) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')) {

			$imgname = $_FILES['image']['name'];
			$type = $_FILES['image']['type'];
			$photo = file_get_contents($_FILES['image']['tmp_name']);

			// hash passwords before storing for security
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			
            $stmt->bind_param('sssssssssss', $_POST['username'], $password, $_POST['email'], $_POST['fn'], $_POST['ln'], $photo, $imgname, $_POST['job'],$_POST['words'],$_POST['inspire'],$_POST['dislike']);
            $stmt->execute();

			//once account is created, start session and log user in
			session_start();
			$stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?');
			$stmt->bind_param('s', $_POST['username']);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id);
			$stmt->fetch();
			session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id'] = $id;
			
            // once logged in, navigate to homepage
            header('Location: index.php');

        } else {
            // Something is wrong with the SQL statement
			echo 'Sorry, something went wrong.';
			exit();
		}
	}
	$stmt->close();
} else {
	// Something is wrong with the SQL statement
	echo 'Sorry, something went wrong.';
	exit();
} 

$con->close();
?>
<?php
    include_once 'db.php';

    // update record with new inputs
    if ($stmt = $con->prepare('UPDATE accounts SET email = ?, fn = ?, ln = ?, job = ?, words = ?, inspire = ?, dislike = ? WHERE id = ?')){
        
           $stmt->bind_param('sssssssi', $_POST['email'], $_POST['fn'], $_POST['ln'], $_POST['job'], $_POST['words'], $_POST['inspire'], $_POST['dislike'], $_SESSION['id']);
        $stmt->execute();
        header("Location: profile.php?updated");
        } else {
            // Something is wrong with the SQL statement
            echo 'Could not prepare statement!';
        }
?>
<?php
   // get the ID of the image from the URL
   $id = $_GET['id'];
   // connect to the database
   $pdo = new PDO('mysql:host=localhost;dbname=db9bjfxeiincnd', 'uhzcgeogj2fqh', '7t*PLBQpGCM8Uwg');   
   // retrieve the image data from the database
   $stmt = $pdo->prepare("SELECT photo, imgname FROM accounts WHERE id=?");
   $stmt->bindParam(1, $id);
   $stmt->execute();
   // set the content type header
   header("Content-Type: image/jpeg");
   // output the image data
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   echo $row['photo'];
?>
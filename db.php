<?php

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'uhzcgeogj2fqh';
$DATABASE_PASS = '7t*PLBQpGCM8Uwg';
$DATABASE_NAME = 'db9bjfxeiincnd';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
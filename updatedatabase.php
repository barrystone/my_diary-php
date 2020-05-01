<?php
    session_start();

    if (array_key_exists("content", $_POST)) {
// include("connection.php");		
        $link = mysqli_connect("localhost", "root","890208","users");
		if (mysqli_connect_error()) {			
			die ("There was an error connecting to the database");			
		}       
		
        $query = "UPDATE `diaryproject` SET `diary` = '".mysqli_real_escape_string($link, $_POST['content'])."' WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";        
        mysqli_query($link, $query);        
    }
?>

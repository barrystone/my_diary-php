<?php
    session_start();
    $error = "";  
	if (!array_key_exists("id", $_SESSION)) {
		header("Location: index.php");
	}
    if (array_key_exists("submit", $_POST)) {
// include("connection.php");        
        $link = mysqli_connect("localhost", "root","890208","users");
		if (mysqli_connect_error()) {			
			die ("There was an error connecting to the database");			
		}      
		                
        if (!$_POST['password']) {            
            $error .= "Your original Passward is required<br>";            
        }
		if (!$_POST['newPassword']) {            
            $error .= "New Password is required<br>";            
        }
		if (!$_POST['checkPassword']) {            
            $error .= "Check Password is required<br>";            
        }         
        if ($error != "") {            
            $error = "<p>There were error(s) in your form:</p>".$error;            
        } else { 
			$query = "SELECT * FROM `diaryproject` WHERE id = '".$_SESSION['id']."'";                
			$result = mysqli_query($link, $query);                
			$row = mysqli_fetch_array($result);                
			if (isset($row)) {                        
				$hashedPassword = md5(md5($row['id']).$_POST['password']);
				if($_POST['newPassword']==$_POST['checkPassword']){
					if ($hashedPassword == $row['password']) {                            
						$_SESSION['id'] = $row['id']; 
						$query = "UPDATE `diaryproject` SET password = '".md5(md5($_SESSION['id']).$_POST['newPassword'])."' WHERE id = ".$_SESSION['id']." LIMIT 1"; 	
						mysqli_query($link, $query);
						//$row['password']=md5(md5(mysqli_insert_id($link)).$_POST['newPassword']);					
						setcookie("id", $row['id'], time() - 60*60*24*365);
						
						echo "success";
						
						header("Location: succcesschangepasswardpage.php");                                
					} else {                            
						$error = "Original password was not correct.";                            
					}  
				}else{
					$error = "Check password was not correct."; 
				}				
				                      
			} else {                        
				$error = "That email/password combination could not be found.";                        
			} 
		}
                    
    }	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags always come first -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
	<style type="text/css">          
		.container {      
			text-align: center;
			width: 400px;
		}          
		#homePageContainer {              
			//叫︽ЧΘ场だ祘Α絏         
		}          
		#containerLoggedInPage {              
			//叫︽ЧΘ场だ祘Α絏               
		}          
		html {           
			background: url(background.jpg) no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;          
		}          
		body {
			background:none;
			color:#FFF;
		}          
		#signUpForm {              
			display:none;              
		}          
		.toggleForms {              
			font-weight: bold;
			cursor:pointer;
		}          
		#diary {              
			width:100%;
			height:100%;              
		}   
		#myModefied{
			height:100px;
			weight:100%;
		}		
	</style>
</head>
<body> 

<?php //include("header.php"); ?>
   
	<div class="container" id="homePageContainer"> 
	
		<div id="myModefied"></div>
		
		<nav class="navbar navbar-light bg-faded navbar-fixed-top">
		  <a class="navbar-brand" href="#">Secret Diary</a>
			<div class="pull-xs-right">
				<a href ='index.php?logout=1'>
					<button class="btn btn-success-outline" type="submit">Logout</button>
				</a>
				<a href ='loggedinpage.php?'>
					<button class="btn btn-success-outline" type="submit">Back to diary</button>
				</a>
			</div>
		</nav>
		
		<h3>Change your passward.</h3>          
		<div id="error">
			<?php if ($error!="") {
			echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';    
			} ?>
		</div>

		<form method="post" id = "logInForm">    
			<p>Enter following password.</p>    
			<fieldset class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Your original Passward">        
			</fieldset>    
			<fieldset class="form-group">    
				<input class="form-control"type="password" name="newPassword" placeholder="New Password">        
			</fieldset>  
			<fieldset class="form-group">    
				<input class="form-control"type="password" name="checkPassword" placeholder="Check Password again">        
			</fieldset>			
				<input type="hidden" name="signUp" value="0">    
			<fieldset class="form-group">        
				<input class="btn btn-success" type="submit" name="submit" value="confirm">        
			</fieldset>    
			
		</form>			
		<?php 
		// <div style="background-color:white;height:100px;weight:100%;position:relative;top:100px;color:black;">
		// <?php	echo "<br>".$_SESSION['id']."<br>";//echo mysqli_insert_id($link); </div>?>	
	</div>
	
<?php //include("footer.php");?>
	
    <!-- jQuery first, then Bootstrap JS. -->	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>      
	<script type="text/javascript">      
		$(".toggleForms").click(function() {            
			$("#signUpForm").toggle();
			$("#logInForm").toggle();            
		});          
		  $('#diary').bind('input propertychange', function() {
				$.ajax({
				  method: "POST",
				  url: "updatedatabase.php",
				  data: { content: $("#diary").val() }
				});
		});      
	</script>      
</body>
</html>






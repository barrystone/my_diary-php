<?php
    session_start();
    $error = "";  
	
    if (array_key_exists("logout", $_GET)) {        
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";         
        session_destroy();        
    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        header("Location: loggedinpage.php");        
    }
    if (array_key_exists("submit", $_POST)) {  

// include("connection.php");	
        $link = mysqli_connect("localhost", "root","890208","users");
		if (mysqli_connect_error()) {			
			die ("There was an error connecting to the database");			
		}      
		
        if (!$_POST['email']) {            
            $error .= "An email address is required<br>";            
        }         
        if (!$_POST['password']) {            
            $error .= "A password is required<br>";            
        }         
        if ($error != "") {            
            $error = "<p>There were error(s) in your form:</p>".$error;            
        } else {            
            if ($_POST['signUp'] == '1') {            
                $query = "SELECT id FROM `diaryproject` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
                $result = mysqli_query($link, $query);
                if (mysqli_num_rows($result) > 0) {
                    $error = "That email address is taken.";
                } else {
                    $query = "INSERT INTO `diaryproject` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
                    if (!mysqli_query($link, $query)) {
                        $error = "<p>Could not sign you up - please try again later.</p>";
                    } else {
                        $query = "UPDATE `diaryproject` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";                        
                        $id = mysqli_insert_id($link);                        
                        mysqli_query($link, $query);
                        $_SESSION['id'] = $id;
                        if ($_POST['stayLoggedIn'] == '1') {
                            setcookie("id", $id, time() + 60*60*24*365);
                        }                             
                        header("Location: loggedinpage.php");
                    }
                }                 
            } else {                    
                    $query = "SELECT * FROM `diaryproject` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";                
                    $result = mysqli_query($link, $query);                
                    $row = mysqli_fetch_array($result);                
                    if (isset($row)) {                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);                        
                        if ($hashedPassword == $row['password']) {                            
                            $_SESSION['id'] = $row['id'];                            
                            if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {
                                setcookie("id", $row['id'], time() + 60*60*24*365);
                            } 
                            header("Location: loggedinpage.php");                                
                        } else {                            
                            $error = "That email/password combination could not be found.";                            
                        }                        
                    } else {                        
                        $error = "That email/password combination could not be found.";                        
                    }                    
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
		
		<h1>Secret Diary</h1>          
		<p><strong>Store your thoughts permanently and securely.</strong></p>
		
		<div id="error">
			<?php if ($error!="") {
			echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';    
			} ?>
		</div>
		
		<form method="post" id = "signUpForm">    
			<p>Interested? Sign up now.</p>    
			<fieldset class="form-group">
				<input class="form-control" type="email" name="email" placeholder="Your Email">        
			</fieldset>    
			<fieldset class="form-group">    
				<input class="form-control" type="password" name="password" placeholder="Password">        
			</fieldset>    
			<div class="checkbox">    
				<label>    
				<input type="checkbox" name="stayLoggedIn" value="1"> Stay logged in            
				</label>        
			</div>    
			<fieldset class="form-group">    
				<input type="hidden" name="signUp" value="1">        
				<input class="btn btn-success" type="submit" name="submit" value="Sign Up!">        
			</fieldset>    
			<p><a class="toggleForms">Log in</a></p>
		</form>

		<form method="post" id = "logInForm">    
			<p>Log in using your username and password.</p>    
			<fieldset class="form-group">
				<input class="form-control" type="email" name="email" placeholder="Your Email">        
			</fieldset>    
			<fieldset class="form-group">    
				<input class="form-control"type="password" name="password" placeholder="Password">        
			</fieldset>    
			<div class="checkbox">    
				<label>    
					<input type="checkbox" name="stayLoggedIn" value="1"> Stay logged in            
				</label>        
			</div>        
				<input type="hidden" name="signUp" value="0">    
			<fieldset class="form-group">        
				<input class="btn btn-success" type="submit" name="submit" value="Log In!">        
			</fieldset>    
			<p><a class="toggleForms">Sign up</a></p>
		</form>	
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

   






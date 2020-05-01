<?php
    session_start();
    //$diaryContent="";
// include("connection.php");
	$link = mysqli_connect("localhost", "root","890208","users");
	if (mysqli_connect_error()) {			
		die ("There was an error connecting to the database");			
	}
	
    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {        
        $_SESSION['id'] = $_COOKIE['id'];        
    }
    if (array_key_exists("id", $_SESSION)) {              
      include("connection.php");      
      $query = "SELECT diary FROM `diaryproject` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
      $row = mysqli_fetch_array(mysqli_query($link, $query)); 
      $diaryContent = $row['diary'];      
    } else {        
        header("Location: index.php");        
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

	<nav class="navbar navbar-light bg-faded navbar-fixed-top">
	  <a class="navbar-brand" href="#">Secret Diary</a>
		<div class="pull-xs-right">
			<a href ='index.php?logout=1'>
				<button class="btn btn-success-outline" type="submit">Logout</button>
			</a>
			<a href ='changepassward.php?'>
				<button class="btn btn-success-outline" type="submit">Change Passward</button>
			</a>
		</div>
	</nav>
	
	<div id="myModefied"></div>
    
	<div class="container-fluid" id="containerLoggedInPage">
        <textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
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

    
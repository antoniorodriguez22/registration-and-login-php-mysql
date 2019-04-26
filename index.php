<?php include('server.php') ?>
<?php
//if a session is not created yet, redirect to login
  if (!isset($_SESSION['userEmail'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  //if user clicks logout, redirect to login and destroy session. 
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['userEmail']);
  	header("location: login.php");
  }
?>

<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Index</title>

</head>

<body>

        <!--displays user full name-->
        <h2>Hello, <?php echo $_SESSION['fullName'];?></h2> 
        
        <p> You are in the members area, click below button to logout from your current session. 
        
        <!--Destroy session-->
          <a href="index.php?logout='1'"><button name="logout" >Logout</button></a> 

        </p>

</body>
</html>

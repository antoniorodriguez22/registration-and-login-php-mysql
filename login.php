<?php include('server.php') ?>
<!DOCTYPE html>

<html>
<head>
 
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Form</title>

</head>

<body>


        <!-- Default form register -->
<form  method="post" action="login.php">
  <?php include('dbactionfiles/error.php'); ?>

    <p>Login </p>

    <!-- E-mail or Username (depends on your database structure) -->
    <input type="email" placeholder="E-mail" name="userEmail">

    <!-- Password -->
    <input type="password" placeholder="Password" name="userPass">

    <!-- Login button -->
    <button type="submit" name="login_user" >Login</button>



        <p>Don't have an account?
            <a href="registration.php">Register here</a>

</form>


</div>
</body>
</html>

<?php include('server.php') ?>
<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Test</title>

</head>

<body>


        <!-- Default form register -->
<form action="registration.php" method="post">
<?php include('dbactionfiles/error.php'); ?>

    <p>Create Account</p>
    
    <!-- Full name -->
    <input type="text" placeholder="Nanme" name="fullName">

    <!-- E-mail -->
    <input type="email"  placeholder="E-mail" name="userEmail">

    <!-- Phone number -->
    <input type="text" placeholder="Phone Number" name="phoneNumber">

    <!-- Password -->
    <input type="password"  placeholder="Password" name="userPass1">

    <!-- Password -->
    <input placeholder="Repeat Password" name="userPass2">

    <!-- Sign up button -->
    <button type="submit" name="reg_user">Registrarse</button>
    
    <!-- Redirect to login -->
        <p class="text-white">Do you have an account already?
            <a href="/login.php">Login</a>

</form>
</body>
</html>

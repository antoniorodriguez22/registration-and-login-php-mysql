<?php
session_start(); //start a new session in browser

// initializing variables
$fullName = "";
$userEmail  = "";
$errors = array();

// connect to the database
$db = mysqli_connect('server', 'username', 'password', 'database');//connect to the database using these four arguments

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the registration form
  $fullName = mysqli_real_escape_string($db, $_POST['fullName']);
  $userEmail = mysqli_real_escape_string($db, $_POST['userEmail']);
  $phoneNumber = mysqli_real_escape_string($db, $_POST['phoneNumber']);
  $userPass1 = mysqli_real_escape_string($db, $_POST['userPass1']);
  $userPass2 = mysqli_real_escape_string($db, $_POST['userPass2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
	
  //Input validation: 
  // Empty input for those fields that are required:
  if (empty($fullName)) { array_push($errors, "Please, introduce full name."); }
  if (empty($userEmail)) { array_push($errors, "Please, introduce e-mail address."); }
  if (empty($userPass1)) { array_push($errors, "Please, introduce password."); }

  //validate e-mail (include @ and .)
if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $userEmail)) {
  	array_push($errors, "Please, introduce a valid e-mail address.");
}


  //passwords don't match
  if ($userPass1 != $userPass2) {
	array_push($errors, "Passwords don't match.");
  }
//password is too short
  if(strlen($userPass1) < 6){
    array_push($errors, 'Password should be at least 6 characters long');
  }
//password does not have at least one number
  if (!preg_match("#[0-9]+#", $userPass1)) {
    array_push($errors, 'Password should have at least 1 number.');
}

//check if user exists in database:
  
  $user_check_query = "SELECT * FROM usertable WHERE  userEmail='$userEmail' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) { // if user exists

    if ($user['userEmail'] === $userEmail) {
      array_push($errors, "E-mail is in use");
    }
  }

  // Finally, register user if there are no errors in the form
  
  
  if (count($errors) == 0) {
  	$userPass = password_hash($userPass1, PASSWORD_DEFAULT);//encrypt the password before saving in the database
	
    //prepare statement to avoid SQL injection
    
    $stmt = $db->prepare("INSERT INTO usertable (fullName, userEmail, phoneNumber, userPass)
  			  VALUES(?, ?, ?,?)");
    $stmt->bind_param("ssss", $fullName, $userEmail, $phoneNumber, $userPass);//bind parameters to statement. First argument declares input type s===string.
    $stmt->execute();//execute statement

  	$_SESSION['userEmail'] = $userEmail;
    	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');//redirect to index after login
  }
}




//LOGIN USER
if (isset($_POST['login_user'])) {

  //take input
  $email= mysqli_real_escape_string($db, $_POST['userEmail']);
  $password = mysqli_real_escape_string($db, $_POST['userPass']);

//input validation
//empty input
  if (empty($email)) {
    array_push($errors, "E-mail required.");
  }
  if (empty($password)) {
    array_push($errors, "Password required.");
  }

//check if user exists
  $user_check_query = "SELECT userPass FROM usertable WHERE  userEmail='$email'";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  if ($user) {   // if user exists, take encrypted password from the database to use it later in password_verify() function.
    $hashed_password = $user['userPass'];
  }else{
    array_push($errors, "Account not found.");
  }


//password verification pushing $errors array
if (count($errors) == 0) {
  if (password_verify($password, $hashed_password)) {//if input password matches database hashed password, then execute query, else push array error.
  //query selecting user email
  	$query = "SELECT * FROM usertable WHERE userEmail LIKE '$email'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['userEmail'] = $email; //create the session
      $value = mysqli_fetch_object($results);
      $_SESSION['fullName'] = $value->fullName;//setting full name for session (you could use username, email, etc. at your convenience.)
      $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong e-mail/password combination. ");

  	}
  }
  }
}

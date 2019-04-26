<?php
session_start();

// initializing variables
$fullName = "";
$userEmail  = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'humanosve');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $fullName = mysqli_real_escape_string($db, $_POST['fullName']);
  $userEmail = mysqli_real_escape_string($db, $_POST['userEmail']);
  $phoneNumber = mysqli_real_escape_string($db, $_POST['phoneNumber']);
  $userPass1 = mysqli_real_escape_string($db, $_POST['userPass1']);
  $userPass2 = mysqli_real_escape_string($db, $_POST['userPass2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  //empty values
  if (empty($fullName)) { array_push($errors, "Please, introduce full name."); }
  if (empty($userEmail)) { array_push($errors, "Please, introduce e-mail address."); }
  if (empty($userPass1)) { array_push($errors, "Please, introduce password."); }

if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $userEmail)) {
  	array_push($errors, "Please, introduce a valid e-mail address.");
}


  //passwords dont match
  if ($userPass1 != $userPass2) {
	array_push($errors, "Passwords don't match.");
  }
//password is too short
  if(strlen($userPass1) < 6){
    array_push($errors, 'Password should be at least 6 characters long');
  }
//password doe snot have at least one number
  if (!preg_match("#[0-9]+#", $userPass1)) {
    array_push($errors, 'Password should have at least 1 number.);
}

  // first check the database to make sure
  // a user does not already exist with the same username and/or email
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
    //prepare statement to avoid sql injection
    $stmt = $db->prepare("INSERT INTO usertable (fullName, userEmail, phoneNumber, userPass)
  			  VALUES(?, ?, ?,?)");
    $stmt->bind_param("ssss", $fullName, $userEmail, $phoneNumber, $userPass);
    $stmt->execute();

  	$_SESSION['userEmail'] = $userEmail;
    $_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}




//LOGIN USER
if (isset($_POST['login_user'])) {
  //take input
  $email= mysqli_real_escape_string($db, $_POST['userEmail']);
  $password = mysqli_real_escape_string($db, $_POST['userPass']);

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
  if ($user) { // if user exists
    $hashed_password = $user['userPass'];
  }else{
    array_push($errors, "Account not found.");
  }


//password verification pushing $errors array
if (count($errors) == 0) {
  if (password_verify($password, $hashed_password)) {//if encrypted password matches
//query
  	$query = "SELECT * FROM usertable WHERE userEmail LIKE '$email'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['userEmail'] = $email;
      $value = mysqli_fetch_object($results);
      $_SESSION['fullName'] = $value->fullName;//setting full name for session
      $_SESSION['success'] = "You are now logged in";
  	  header('location: Busqueda.php');
  	}else {
  		array_push($errors, "Wrond e-mail/password combination. ");

  	}
  }
  }
}

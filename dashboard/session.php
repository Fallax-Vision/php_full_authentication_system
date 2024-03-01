
<?php

	require "../system_settings.php";

	@session_start();

	// check whether the session is still active via the user_id
	if(!isset($_SESSION["auth_user_id"])){
		header("location: ../login");
		exit(); 
	}


  // check whether the user is still logged in
  // fetch the cookies
	$auth_user_id = $_COOKIE['auth_user_id'];
	$auth_session_key = $_COOKIE['auth_session_key'];

	// check whether the user still exists
	$sql_check_id = "SELECT * FROM users WHERE `user_id`='$auth_user_id'";
	$query_check_id = $con -> query($sql_check_id);
	$num_rows_check_id = mysqli_num_rows($query_check_id);

	// fetch account status
	$num_rows_check_id = $query_check_id -> fetch_array();
	
	// check if session still exists and is active
	$sql_session = "SELECT * FROM users_login_sessions WHERE 
									log_session_key='$auth_session_key' AND 
									log_status='active'";
	$query_session = $con -> query($sql_session);
	$num_rows_session = mysqli_num_rows($query_session);

  // check whether the cookies have been set and if the user exists
  if($num_rows_check_id == false){
      
    header("location: logout.php");

  } elseif (!isset($auth_session_key)) {

		header("location: logout.php");
	
	} elseif($num_rows_session == false){

		header("location: logout.php");

  }

?>
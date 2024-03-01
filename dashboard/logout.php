
<?php

	require "../system_settings.php";

	// unset the session via the to secure the account
	session_start();
	unset($_SESSION['auth_user_id']);
	session_destroy();
	header("location: ../login");


	// update the active value to from 1 to 0
	$auth_user_id = $_COOKIE['auth_user_id'];
	$auth_session_key = $_COOKIE['auth_session_key'];

	$current_date = date("Y-m-d");
	$t = strtotime("+3 Hours");
	$current_time = date("h:ia", $t);

	$sql_update_active = "UPDATE users SET user_login_status='0' WHERE user_id='$auth_user_id'";
	$sql_update_session = "UPDATE users_login_sessions SET 
													log_logout_date='$current_date', 
													log_logout_time='$current_time', 
													log_status='ended' 
												WHERE log_user_id='$auth_user_id' 
												AND log_session_key='$auth_session_key'";
	$query_update_active = $con -> query($sql_update_active);
	$query_update_session = $con -> query($sql_update_session);

	// Delete user cookie from browser
	if($query_update_active . $query_update_session){
		setcookie("auth_user_id", "", time() - (432000), "/");
		setcookie("auth_session_key", "", time() - (432000), "/");
	}

	$account_status_logout = $_GET['user_account_status'];
	
	if($auth_user_id==""){
		header("location: ../login");
	} elseif($account_status_logout=="suspended"){
		header("location: ../login?account_status=suspended");
	} elseif($account_status_logout=="deleted") {
		header("location: ../login?account_status=deleted");
	} else {
		header("location: ../login");
	}

?>
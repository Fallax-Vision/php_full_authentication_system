<?php
  require "system_settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/style.css">
  <title>Forgot Password</title>
</head>
<body>
  <div class="authentication_container">
    <?php
    
      // check whether the user is already logged in on this computer
      $auth_session_key = $_COOKIE['auth_session_key'];

      if(isset($auth_session_key)) {

        $sql_check_session_key = "SELECT * FROM users_login_sessions WHERE log_session_key='$auth_session_key' AND log_status='active'";
        $query_check_session_key = $con -> query($sql_check_session_key);
        $num_row_check_session_key = mysqli_num_rows($query_check_session_key);

        if($num_row_check_session_key==true) {
          // if the user is already logged in, redirect them to their dashboard
          header("location: dashboard/home");
        }
      }



      // when the user successfully created their account, fetch the value from the URL and display a success message below
      $accout_creation_status = $_GET['s'];

      if($accout_creation_status == "success") {
        echo "
          <div class='feedback_messages_container success'>
            <b>Success!</b> Your account has been created successfully. You can now loggin your new account below. 👇
          </div>
        ";
      }


      $recovery_key = $_GET['key'];

      if($recovery_key=="wrong") {
        echo "
          <div class='feedback_messages_container fail'>
            <b>Failed!</b> The recovery key you input was incorrect. Please, verify your details and try again below. 👇
          </div>
        ";
      }


      if(isset($_POST['submit_btn'])){

        $username_or_email = mysqli_real_escape_string($con, $_POST['username_or_email']);
        $account_pin = mysqli_real_escape_string($con, $_POST['account_pin']);
        // encrypt account_pin before sending it in the database
        $encrypted_account_pin = crypt($account_pin, 'auth');
        
        // check whether a user with the same email exists already
        $sql_check = "SELECT * FROM users WHERE (user_email='$username_or_email' OR user_username='$username_or_email') AND user_account_pin='$encrypted_account_pin'";
        $query_check = $con -> query($sql_check);
        $row_check = $query_check -> fetch_assoc();

        if(mysqli_num_rows($query_check)==false) {
          echo "
            <div class='feedback_messages_container fail'>
              <b>Failed!</b> Wrong username or email, or wrong PIN. Please, verify your details and try again below.
            </div>
          ";
        } else {
          
          // fetch the user's ID
          $user_id = $row_check['user_id'];

          // create a recovery key
          $recovery_key = uniqid();

          $sql_recovery = "INSERT INTO user_recover_password (
                            urp_user_id,
                            urp_recovery_key
                          ) VALUES(
                            '$user_id',
                            '$recovery_key'
                          )";
          $query_recovery = $con -> query($sql_recovery);

          if($query_recovery==false){

          } else {
            // redirect to dashboard
            header ("location: recover?r=".$recovery_key);
          }
        }
      }
    ?>
    
    <div class="form_container">

      <div class="title_section">
        <h1>Forgot Password</h1>
      </div>

      <div class="actual_form">

        <div class="guide_message">
          To recover your account, please, fill in the details below.
        </div>

        <form method="post">
          <div class="label_form"> Username or Email </div>
          <input type="text" name="username_or_email" class="form_input" placeholder="Username or Email" required>
          <div class="label_form"> Account PIN to recover your account</div>
          <input type="text" name="account_pin" class="form_input" placeholder="Your PIN" required>
          <input type="submit" name="submit_btn" class="form_input submit_btn" value="Verify">
        </form>
      </div>

      <div class="call_to_action_btns_container">
        Don't have an account? <a href="signup"> Create account now </a>
      </div>

    </div>
  </div>
</body>
</html>
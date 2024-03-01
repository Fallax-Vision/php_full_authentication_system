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
  <title>Recover Password</title>
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



      $recover_code = $_GET['r'];

      // fetch details of the recovery attempt
      $sql_fetch_recovery = "SELECT * FROM user_recover_password 
                            INNER JOIN users ON user_recover_password.urp_user_id = users.user_id
                            WHERE user_recover_password.urp_recovery_key='$recover_code'";
      $query_fetch_recovery = $con -> query($sql_fetch_recovery);
      $row_fetch_recovery = $query_fetch_recovery -> fetch_assoc();

      if(mysqli_num_rows($query_fetch_recovery)==false) {
        // redirect to dashboard
        header ("location: forgot?key=wrong");
      }



      if(isset($_POST['submit_btn'])){

        $user_id = $row_fetch_recovery['user_id'];

        $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
        $repeat_new_password = mysqli_real_escape_string($con, $_POST['repeat_new_password']);
        // encrypt account_pin before sending it in the database
        $encrypted_new_password = crypt($new_password, 'auth');
        
        if($new_password != $repeat_new_password) {
          echo "
            <div class='feedback_messages_container fail'>
              <b>Failed!</b> You did not repeat your password correctly. Please, repeat your password twice correctly and try again below.
            </div>
          ";
        } else {

          // update password
          $sql_update_password = "UPDATE users SET user_password='$encrypted_new_password' WHERE user_id='$user_id'";
          $query_update_password = $con -> query($sql_update_password);

          if($query_update_password==false){
            echo "
              <div class='feedback_messages_container fail'>
                Something weng wrong. Please, try again. ".mysqli_error($con)."
              </div>
            ";
          } else {

            // update the user_recover_password table
            $sql_update_rec_status = "UPDATE user_recover_password SET urp_recovery_key_used='yes', urp_status='success' WHERE urp_recovery_key='$recover_code'";
            $query_update_rec_status = $con -> query($sql_update_rec_status);

            if($query_update_rec_status==false){
              echo "
                <div class='feedback_messages_container fail'>
                  Something weng wrong. Please, try again. ".mysqli_error($con)."
                </div>
              ";
            } else {
              // redirect to login page
              header ("location: login?s=pass_rec_success");
            }
          }
        }
      }
    ?>
    
    <div class="form_container">

      <div class="title_section">
        <h1>Recover Password</h1>
      </div>

      <div class="actual_form">
        
        <?php if($row_fetch_recovery['urp_recovery_key_used']=="yes" && $row_fetch_recovery['urp_status']=="success"){ ?>

          <div class="guide_message">
            This recovery key has already been used. Please, click the button below to recover your password again. 👇
          </div>

        <?php } else { ?>

          <?php if(!isset($_POST['submit_btn'])){ ?>
            <div class="guide_message success">
              <?php echo "Hello, <b>".$row_fetch_recovery['user_firstname']."</b>. To recover your account, please, fill in the details below.";?>
            </div>
          <?php } ?>

          <form method="post">
            <div class="label_form"> New Password </div>
            <input type="password" name="new_password" class="form_input" placeholder="New Password" required>
            <div class="label_form"> Repeat New Password </div>
            <input type="password" name="repeat_new_password" class="form_input" placeholder="Repeat New Password " required>
            <input type="submit" name="submit_btn" class="form_input submit_btn" value="Recover Password">
          </form>

        <?php } ?>

      </div>

      <div class="call_to_action_btns_container">
        <?php if($row_fetch_recovery['urp_recovery_key_used']=="yes"){ ?>
          <a href="forgot"> Recover Password Again </a>
          <a href="login"> Or, Login </a>
        <?php } else { ?>
          Don't have an account? <a href="signup"> Create account now </a>
        <?php } ?>
      </div>

    </div>
  </div>
</body>
</html>
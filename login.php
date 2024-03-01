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
  <title>Login</title>
</head>
<body>
  <div class="authentication_container">
    
    <?php
      
      // check whether the user is already logged in on this browser
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


      // only show these message when the user has not clicked the "login" button yet
      if(!isset($_POST['submit_btn'])){
        
        // when the user successfully created their account, fetch the value from the URL and display a success message below
        $account_creation_status = $_GET['s'];

        if($account_creation_status == "success") {
          echo "
            <div class='feedback_messages_container success'>
              <b>Success!</b> Your account has been created successfully. You can now log in to your new account below 👇
            </div>
          ";
        } elseif($account_creation_status == "pass_rec_success") {
          echo "
            <div class='feedback_messages_container success'>
              <b>Success!</b> Your password was successfully. You can now log in with your new password below 👇
            </div>
          ";
        }
      }


      if(isset($_POST['submit_btn'])){
        
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        // encrypt password before sending it in the database
        $encrypted_password = crypt($password, 'auth');
        
        // check whether a user with the same email exists already
        $sql_check = "SELECT * FROM users WHERE user_username='$username' AND user_password='$encrypted_password'";
        $query_check = $con -> query($sql_check);
        $row_check = $query_check -> fetch_assoc();

        if(mysqli_num_rows($query_check)==false) {
          // if there is no user with the details entered
          echo "
            <div class='feedback_messages_container fail'>
              <b>Failed!</b> Wrong username or password. Try again below.
            </div>
          ";
        } else {

          $user_id = $row_check['user_id'];
          $user_session_key = uniqid();

          // update the users table to mention that the user is now logged in
          $sql_upd_users = "UPDATE users SET user_login_status='1' WHERE user_id='$user_id'";
          $query_upd_users = $con -> query($sql_upd_users);

          // record the login session
          $sql_login = "INSERT INTO users_login_sessions (
                          log_session_key,
                          log_user_id
                        ) VALUES (
                          '$user_session_key',
                          '$user_id'
                        )";
          $query_login = $con -> query($sql_login);

          if($query_upd_users==false || $query_login==false){
            // if there is no user with the details entered
            echo "
              <div class='feedback_messages_container fail'>
                <b>Failed!</b> Something went wrong. Try again.
                <br>Error: ".mysqli_error($con)."
              </div>
            ";
          } else {

            session_start();
            $_SESSION['auth_user_id'] = $user_id;

            // set login cookies
            setcookie("auth_user_id", $user_id, time() + (2592000), "/");
            setcookie("auth_session_key", $user_session_key, time() + (2592000), "/");

            // redirect to dashboard
            header ("location: dashboard/home");
          }
        }
      }
    ?>
    
    <div class="form_container">

      <div class="title_section">
        <h1>Login</h1>
      </div>

      <div class="actual_form">

        <form method="post">

          <div class="label_form"> Username <span style="color: red">*</span> </div>
          <input type="text" id="username" name="username" class="form_input" placeholder="Username" tabindex="0" required>
          <script>
            // Auto focus the username field for quick typing 
            document.querySelector('[tabindex="0"]').focus()
          </script>
          <!-- Disable space in username input ---->
          <script>
            const input = document.getElementById("username");
            input.addEventListener("keydown", function(event) {
              if (event.code === "Space") {
                event.preventDefault();
              }
            });
          </script>
          
          <div class="label_form"> Password <span style="color: red">*</span> </div>
          <input type="text" name="password" class="form_input" placeholder="Pasword" required>
          <div class="forgot_password">
            <a href="forgot"> I forgot my password! </a>
          </div>
          <input type="submit" name="submit_btn" class="form_input submit_btn" value="Login">
          
        </form>

      </div>

      <div class="call_to_action_btns_container">
        Don't have an account? <a href="signup"> Create account now </a>
      </div>
    </div>
  </div>
</body>
</html>
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
  <title>Signup</title>
</head>
<body>
  <div class="authentication_container">
    <?php
    
      // when the user clicks the "create_account" button
      if(isset($_POST['submit_btn'])){

        $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
        $email = mysqli_real_escape_string($con, $_POST['email']);

        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        // encrypt password before sending it in the database
        $encrypted_password = crypt($password, 'auth');

        $account_pin = mysqli_real_escape_string($con, $_POST['account_pin']);
        $encrypted_account_pin = crypt($account_pin, 'auth');
        
        // check whether a user with the same email exists already
        $sql_check_email = "SELECT * FROM users WHERE user_email='$email'";
        $query_check_email = $con -> query($sql_check_email);

        // check whether a user with the same username exists already
        $sql_check_username = "SELECT * FROM users WHERE user_username='$username'";
        $query_check_username = $con -> query($sql_check_username);

        if(mysqli_num_rows($query_check_email)==true) {
          // if another user with the same email is found, show this message and do not submit the data
          echo "
            <div class='feedback_messages_container fail'>
              <b>Failed!</b> A user with that email address already exists. Please, use another email address and try again below.
            </div>
          ";
        } elseif(mysqli_num_rows($query_check_username)==true) {
          // if another user with the same email is found, show this message and do not submit the data
          echo "
            <div class='feedback_messages_container fail'>
              <b>Failed!</b> A user with that username already registered. Please, use another username address and try again below.
            </div>
          ";
        } else {
          // if no user with the same email was found, then register the new user
          $sql_create = "INSERT INTO users (
                          user_firstname,
                          user_lastname,
                          user_email,
                          user_username,
                          user_password,
                          user_account_pin
                        ) VALUES (
                          '$firstname',
                          '$lastname',
                          '$email',
                          '$username',
                          '$encrypted_password',
                          '$encrypted_account_pin'
                        )";
          $query_create = $con -> query($sql_create);

          if($query_create == false) {
            // if there was an error, show the message below
            echo "
              <div class='feedback_messages_container fail'>
                Something weng wrong. Please, try again. ".mysqli_error($con)."
              </div>
            ";
          } else {
            // if there was no error, redirect to the login page, with a success message
            header ("location: login?s=success");
          }
        }
      }
    ?>

    <div class="form_container">

      <div class="title_section">
        <h1>Signup</h1>
      </div>

      <div class="actual_form">
        <form method="post">
          <div class="label_form"> Firstname <span style="color: red">*</span> </div>
          <input type="text" id="firstname" name="firstname" class="form_input" placeholder="Your firstname" maxlength="60" required>
          <div class="label_form"> Lastname </div>
          <input type="text" name="lastname" class="form_input" placeholder="Your lastname" maxlength="60">
          <div class="label_form"> Email <span style="color: red">*</span> </div>
          <input type="email" name="email" class="form_input" placeholder="Your email address" maxlength="60" required>
          <hr>
          <div class="label_form"> Username <span style="color: red">*</span> </div>
          <input type="text" name="username" class="form_input" placeholder="Username" maxlength="60" required>
          <div class="label_form"> Password <span style="color: red">*</span> </div>
          <input type="text" name="password" class="form_input" placeholder="Pasword" required>
          <div class="label_form"> Account PIN <span style="color: red">*</span> <span style="padding-left: 10px; color: #555; font-size:13px">(In case you forget your password)</span> </div>
          <input type="password" name="account_pin" class="form_input" placeholder="PIN" maxlength="5" required>
          <input type="submit" name="submit_btn" class="form_input submit_btn" value="Create Account">
        </form>
      </div>

      <div class="call_to_action_btns_container">
        Already have an account? <a href="login"> Login instead </a>
      </div>

    </div>
  </div>
</body>
</html>
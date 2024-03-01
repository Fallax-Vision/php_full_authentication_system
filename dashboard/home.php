<?php
  require "../system_settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/style.css">
  <title>Home page</title>
</head>
<body>
  <?php
    // include the file that checks whether the user is still logged in
    require "session.php";

    // include the header on the page
    require "header.php";
  ?>

  <div class="page_content">
    Home page content here
  </div>


  <div class="sticky_footer">
    <div class="left_quick_links">
      
    </div>
    <div class="logout_button_container">
      <a href="logout.php">⛔Logout</a>
    </div>
  </div>

</body>
</html>
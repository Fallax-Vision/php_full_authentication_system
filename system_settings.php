<?php

  // hide un-neccessary sql errors
  error_reporting(E_PARSE | E_ERROR);


  //connect to database and store connection in a variable called $con
  $con = new mysqli("localhost", "root", "", "authentication_full");



  
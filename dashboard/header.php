<?php
  $cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>


<div class="header">
  <div class="logo_section">
    <a href="home">
      Logo
    </a>
  </div>
  <div class="main_menu">
    <div class="wrapper">
      <ul>
        <a href="home">
          <li <?php if($cur_page=="home.php"){echo "class='active'";}?>>Home</li>
        </a>
        <a href="products">
          <li <?php if($cur_page=="products.php"){echo "class='active'";}?>>Products</li>
        </a>
      </ul>
    </div>
  </div>
  <div class="call_to_action_btn" onclick="document.getElementById('new_products_form_container').style.display='block'">
    <button>
      New Product
    </button>
  </div>
</div>



<!-- Form that adds a new product  -->
<div id="new_products_form_container" class="new_products_form_container <?php if(isset($_POST['add_product_btn'])){echo "visible";}?>">
  
  <div class="actual_new_product_form">

    <?php

      if(isset($_POST['add_product_btn'])){

        $prod_name = mysqli_real_escape_string($con, $_POST['prod_name']);
        $prod_normal_price = mysqli_real_escape_string($con, $_POST['prod_normal_price']);
        $prod_reduced_price = mysqli_real_escape_string($con, $_POST['prod_reduced_price']);
        $prod_description = mysqli_real_escape_string($con, $_POST['prod_description']);

        // check whether there is not product with the exact product_name
        $sql_check = "SELECT * FROM products WHERE prod_name='$prod_name'";
        $query_check = $con -> query($sql_check);

        if(mysqli_num_rows($query_check)==true){
          echo "
            <div class='feedback_messages_container fail floating'>
              <b>Failed!</b> A Product with the same name already exist. There cannot be 2 products witht the exact name. Type a different name and try again.
            </div>
          ";
        } else {

          // insert the new product in the products table
          $sql_new_product = "INSERT INTO products (
                                prod_longer_id,
                                prod_name,
                                prod_slug,
                                prod_normal_price,
                                prod_reduced_price,
                                prod_description,
                                prod_added_by
                              ) VALUES (

                              )";
          $query_new_product = $con -> query($sql_new_product);
          
          if($query_new_product) {
            echo "
              <div class='feedback_messages_container success floating'>
                <b>Failed!</b> A Product with the same name already exist. There cannot be 2 products witht the exact name. Type a different name and try again.
              </div>
            ";
          }
        }
      }
    ?>

    <div class="title_section">
      <h1>New product</h1>
    </div>

    <div class="form_inputs_container">

      <div class="label_form"> Product Name <span style="color: red">*</span> </div>
      <input type="text" name="prod_name" class="form_input" placeholder="Product Name" required>

      <div class="label_form"> Normal Price ($) <span style="color: red">*</span> </div>
      <input type="number" name="prod_normal_price" class="form_input" placeholder="1" required>

      <div class="label_form"> Reduced Price ($) - (optional) </div>
      <input type="number" name="prod_reduced_price" class="form_input" placeholder="optional" min="1">

      <div class="label_form"> Product Description (optional) </div>
      <textarea name="prod_description" class="form_input" placeholder="Product description here (max: 200 words)" maxlength="200"></textarea>
      
      <input type="submit" name="add_product_btn" class="form_input submit_btn" value="Add Product">
    </div>
  </div>
</div>



<script>
  // hide the popups when the user clicks on it
  var new_products_form_container = document.getElementById('new_products_form_container');

  window.onclick = function(event) {
    if (event.target == new_products_form_container) {
      new_products_form_container.style.display = "none";
    }
  }
</script>
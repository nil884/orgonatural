<?php
  include("includes/configuration.php");

        unset($_SESSION["reguser"]);

    
        unset($_SESSION['setuser']);
        unset($_SESSION['token']);
        unset($_SESSION['shopping_cart']);
        unset($_SESSION['wishitems']);
        unset($_SESSION['compare']);
        unset($_SESSION['subcat']);
      
      header("Location:".SITEURL);
?>
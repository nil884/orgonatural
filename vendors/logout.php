<?php include("../includes/configuration.php");
unset( $_SESSION['seller']);
unset( $_SESSION['last_login_timestamp']);
echo '<script> window.location="'.VENDORURL.'";</script>'; ?>
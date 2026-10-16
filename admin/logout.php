<?php include("../includes/configuration.php");
unset( $_SESSION['admin']);
unset( $_SESSION['last_login_timestamp']);
echo '<script> window.location="'.ADMINURL.'";</script>'; ?>
<?php
    include("../includes/configuration.php");
    /*session_unset();*/
    unset($_SESSION['client_id']);
    unset($_SESSION['clientname']);
    unset($_SESSION['uname']);
    /*unset($_SESSION["user"]);
    unset($_SESSION['adminuname']);
    unset($_SESSION['last_login_timestamp']);
    unset($_SESSION['adminsess']);*/
    /*unset($_SESSION["user"]);*/
    session_destroy();
    /* echo '<script> window.location="'.SUPPORTURL.'";</script>';*/
    echo '<script> window.location="'.SUPPORTURL.'";</script>';    
?>
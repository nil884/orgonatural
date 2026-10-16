<?php
     include("../../includes/configuration.php");
    $action=$_REQUEST['action'];
    $checktype=$_REQUEST['checktype'];
    $dept=$_REQUEST['dept'];
    $email= $_REQUEST['email'];
    $emppost=$_REQUEST['emppost'];
    $clientname=$_REQUEST['clientname'];

    if($action=="checkemail"){
     $where=" AND emp_email='".$email."'";
     $getemp=selectQuery(SUPPORTSTAFF,"emp_email","isDel='0' ".$where);

        if(count($getemp)){ echo "0"; }
        else{ echo "1"; }
    }

    if($action=="checkname"){
        $getname=selectQuery(SUPPORTSTAFF,"emp_name","isDel='0' AND emp_name='".$clientname."' ");
        if(count($getname)){ echo "0"; }
        else{ echo "1"; }
    }
?>
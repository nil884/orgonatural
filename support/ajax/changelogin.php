<?php include("../../includes/configuration.php");
    $action=$_REQUEST['action'];
    $currentpwd=$_REQUEST['currentpwd'];
    $empid=$_REQUEST['empid'];
    $confpassword1=$_REQUEST['confpassword'];
    $confpassword = md5($confpassword1);
    $currentpwd1 = md5($currentpwd);
    //Check Current Password
    if($action=="changelogin"){
        /*$getpassword = selectQuery(SUPPORTSTAFF,"*","emp_id='".$empid."' AND password='".$currentpwd1."' ");*/
        $getpassword = selectQuery(SUPPORTSTAFF,"*","emp_id='".$empid."' AND emp_pwd='".$currentpwd1."' ");
        if(count($getpassword)==1){
            echo "1";
        } else{
            echo "2";
        }
    }
    //Update Password
    if($action=="updatepassword"){
        $data = array( "emp_pwd"=>$confpassword );
        $updatepass = updateQuery(SUPPORTSTAFF,$data,"emp_id='".$empid."' ");
        if($updatepass){
            echo "1";
        } else{
            echo "2";
        }
    }
?>
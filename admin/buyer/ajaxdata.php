<? include("../../includes/configuration.php");
if($action == "statuschnage"){
    $uid = $_REQUEST['uid'];
    $setval = $_REQUEST['status'];
    if($setval == 1){
        $data = array('isActive'=>$setval,'email_verified'=>1);
        $que = updateQuery(BUYER,$data,"u_id=".$uid);
        if($que){ echo 1; } else{ echo 0; }
    }
    else {
        $data = array('isActive'=>$setval,);
        $que=updateQuery(BUYER,$data,"u_id=".$uid);
        if($que){ echo 1; } else{ echo 0; }
    }
} ?>
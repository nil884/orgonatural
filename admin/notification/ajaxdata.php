<? include("../../includes/configuration.php");
if($action == "add_notification"){
    $notification = $_REQUEST['notification'];
    $notificationlink = $_REQUEST['notificationlink'];
    $ico =  $_REQUEST['ico'];
    $data = array("notification" => addslashes(ucwords($notification)), "notificationlink" => $notificationlink, "notificationico" => $ico);
    $gettoal_count = selectQuery(NOTIFICATION,"count(n_id) as total_count","notification = '".$notification."'");
    if($gettoal_count[0]['total_count'] > 0){ echo 2; } else{
        $insert = insertQuery(NOTIFICATION,$data);
        if($insert){echo 1;}else{echo 0;}
    } 
}
if($action == "del_notification"){
    $que = deleteQuery(NOTIFICATION, "n_id=" . $n_id);
    if ($que){ echo 1; } else{ echo 0; }
}
if($action == "statuschnage"){
    $data = array('isActive'=>$status,);
    if(status == 0){
     $data['show_on_homepage'] = 0;
     $data['myaccount'] = 0;
     $data['show_on_all'] = 0;
    }
    $que = updateQuery(NOTIFICATION,$data,"n_id=".$uid);
    if($que){ echo 1; } else{ echo 0; }
}
if($action == "show_on_home"){
    $checkstat = selectQuery(NOTIFICATION,"isActive","n_id=".$uid);
    if($checkstat[0]['isActive'] == 1){
        if($status==1){
            $checkifalrady = selectQuery(NOTIFICATION,"count(n_id) as total_count","show_on_all ='1'  or show_on_homepage='1' ");
            if($checkifalrady[0]['total_count'] < 1){
                $data = array('show_on_homepage'=>$status);
                $que = updateQuery(NOTIFICATION,$data,"n_id=".$uid);
                if($que){echo 1;} else{echo 0;}
            }
            else{echo 3;}
        } else {
            $data = array('show_on_homepage'=>$status);
            $que = updateQuery(NOTIFICATION,$data,"n_id=".$uid);
            if($que){echo 1;} else{echo 0;}
        }
    } else { echo "2"; }
} 
if($action == "show_in_account"){
    $checkstat = selectQuery(NOTIFICATION,"isActive","n_id=".$uid);
    if($checkstat[0]['isActive'] == 1){
        if($status == 1){
            $checkifalrady = selectQuery(NOTIFICATION,"count(n_id) as total_count","show_on_all ='1'  or myaccount='1' ");
            if($checkifalrady[0]['total_count'] < 1){
                $data = array('myaccount'=>$status);
                $que = updateQuery(NOTIFICATION,$data,"n_id=".$uid);
                if($que){echo 1;} else{echo 0;}
            } else{echo 3;}
        }
        else {
            $data = array('myaccount'=>$status);
            $que = updateQuery(NOTIFICATION,$data,"n_id=".$uid);
            if($que){echo 1;} else{echo 0;}
        }
    } else {echo "2";}
} 

if($action == "ALL"){
    $nid = $_REQUEST['nid'];
    $setval = $_REQUEST['status'];
    $checkstat = selectQuery(NOTIFICATION,"*","n_id=".$nid);
    if($checkstat[0]['isActive']==1){
        if($setval==1){
            $checkifalrady = selectQuery(NOTIFICATION,"*","show_on_all ='1' or myaccount = '1'  or show_on_homepage='1' ");
            if(count($checkifalrady) < 1){
                $data = array( 'show_on_all'=>$setval );
                $que = updateQuery(NOTIFICATION,$data,"n_id=".$nid);
                if($que){ echo 1;} else{echo 0;}
            } else{echo 3;}
        }
        else{
            $data = array('show_on_all'=>$setval);
            $que = updateQuery(NOTIFICATION,$data,"n_id=".$nid);
            if($que){echo 1;} else{echo 0;}
        }
    } else{ echo 2; }
} ?>
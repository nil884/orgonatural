<?php include("../../includes/configuration.php");
if($action == "addsubadmin"){
    $uname = $_REQUEST['uname'];
    $pass = $_REQUEST['pass'];
    $md5pass = password_encrypt($pass);
    $menuid = $_REQUEST['menuid'];
    $uemail = $_REQUEST['uemail'];
    $umbl = $_REQUEST['umbl'];
    $result=selectQuery(ADMIN,"u_id","username='".$uname."'");
    if( count($result) > 0){ echo 0; }else{
        $data = array('utype'=>"subadmin", 'u_name' => "subadmin", 'u_mob' => $umbl, 'u_email'=> $uemail, 'username' =>$uname, 'password' =>$md5pass, 'allocatemenu' => $menuid,);
        $insert = insertQuery(ADMIN,$data);
        if($insert){ echo 1; } else{ echo 2; }
    }
}
if($action == "editsubadmin"){
    $uname = $_REQUEST['uname'];
    $menuid = $_REQUEST['menuid'];
    $uemail = $_REQUEST['uemail'];
    $umbl = $_REQUEST['umbl'];
    $subadmin_id = base64_decode($_REQUEST['subadmin_id']);
    $result = selectQuery(ADMIN,"u_id","username='".$uname."' and u_id <> '".$subadmin_id."'  ");
    if( count($result) > 0){ echo 0; } else{
        $data=array('utype'=>"subadmin", 'u_name' => "subadmin", 'u_mob' => $umbl, 'u_email'=> $uemail, 'username' =>$uname, 'allocatemenu' => $menuid,);
        $insert = updateQuery(ADMIN,$data,"u_id=".$subadmin_id);
        if($insert){ echo 1; } else{ echo 2; }
    }
}
if($action == "active_deactive"){
   $requestid = $_REQUEST['requestedid'];
   $data = array('isActive' => $status, );
   $upategetsubcat = updateQuery(ADMIN, $data, "u_id=" . $requestid);
   if($upategetsubcat) { echo 1; } else{ echo 0; }
}
if($action == "Delete_subadmin"){
  $del_subadmin = deleteQuery(ADMIN,'u_id="'.$subadmin_id.'"');
  if($del_subadmin == 1){ echo 1; } else{ echo 0; }
} ?>
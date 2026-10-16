<?php include ("../../includes/configuration.php");
$action = $_REQUEST['action'];
if($action == "youtubevidlink"){
    $youtubevideolink = $_POST['youtubevideolink'];
    $yuvideselect = selectQuery(YOUTUBEVIDEO,"yvideo_link");
    $data = array( "yvideo_link" => $youtubevideolink );
    $insert = insertQuery(YOUTUBEVIDEO, $data);
    if($insert){ echo "1"; } else{ echo "0"; }
}
if($action=="delytvideo"){
    $yvideId = $_POST['yvideId']; $del = deleteQuery(YOUTUBEVIDEO,"yvideo_id=".$yvideId); if($del){ echo 1;}else{ echo 0; }
}
if($action == "active_deactive"){
    $setval = $_REQUEST['status'];
    $actdata = array('isActive' => $setval);
    $que = updateQuery(YOUTUBEVIDEO, $actdata, "yvideo_id=" . $requestedid);
    if($que){echo 1; } else{echo 0;}
} ?>
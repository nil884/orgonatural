<?php include ("../../includes/configuration.php");
$action = $_REQUEST['action'];
if($action == "storyvidlink"){
    $storyvideolink = $_POST['storyvideolink'];
    //$yuvideselect = selectQuery(YOUTUBEVIDEO,"ystory_video");
    $data = array( "yvideo_link" => $storyvideolink,"yvideo_type"=>"story" );
    $insert = insertQuery(YOUTUBEVIDEO, $data);
    if($insert){ echo "1"; } else{ echo "0"; }
}
if($action=="delytvideo"){
    $yvideId = $_POST['yvideId']; $del = deleteQuery(YOUTUBEVIDEO,"yvideo_id=".$yvideId); if($del){ echo 1;}else{ echo 0; }
} ?>
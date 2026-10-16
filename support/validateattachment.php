<?php include("../includes/configuration.php");
    $allowedformat = explode(",",$_REQUEST['allowformat']);
    $allowedarr = array();
    for($i=0;$i<count($allowedformat);$i++){
        $selectQuery=selectQuery(MIME,"mime_info","extension='".$allowedformat[$i]."'");
        for($j=0;$j<count($selectQuery);$j++){
            array_push($allowedarr,$selectQuery[$j]['mime_info']);
        }
    }
    $maxsize = $_REQUEST['maxsize'];
    $type = mime_content_type ($_FILES['attachment']['tmp_name']);
    if(!in_array($type, $allowedarr)){
        echo "Attached File Type '".$type."' Not Allowed";
    } else if($_FILES['attachment']['size']>$maxsize) {
        echo "Attached File Size Exceed";
    }
    else{
        echo 1;
    }
?>
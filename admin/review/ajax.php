<?php
include("../../includes/configuration.php");
   $action=$_POST['action'];

   if($action=="setPriority"){
       $str = $_REQUEST['str'];
    $review_ids = explode(",", $str);
    if ($str != "") {
        for ($i = 0; $i < count($review_ids); $i++) {
        $data = array('priority' => $i + 1);
            $update = updateQuery(REVIEW, $data, "review_id=" .$review_ids[$i]);
            if($update){ echo 1; } else { echo 0; }
        }
    }
   }
   if($action=="deleteReview"){
         $reviewid=$_POST['reviewid'];

        $que=deleteQuery(REVIEW,"review_id=".$reviewid);
         if($que){  echo 1;  }
         else{ echo 0;  }
   }

     if($action=="statusReview"){
         $pid=$_REQUEST['pid'];
        $setval=$_REQUEST['status'];
        $data= array('isApproved'=>$setval, 'isActive' =>$setval );
        $que=updateQuery(REVIEW,$data,"review_id=".$pid);
         if($que){  echo 1;  }
         else{ echo 0;  }
   }

   if($action == "active_deactive"){
    $pid=$_REQUEST['requestedid'];
        $setval=$_REQUEST['status'];
        $data= array('isActive'=>$setval);

        $que=updateQuery(REVIEW,$data,"review_id=".$pid);
         if($que){  echo 1;  }
         else{ echo 0;  }
   }

   ?>
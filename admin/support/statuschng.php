<?php
     include("../../includes/configuration.php");
     $uid=$_REQUEST['uid'];

     $setval=$_REQUEST['status'];
        $data= array(
             'isActive'=>$setval
          );
     $que=updateQuery(SUPPORTDEPT,$data,"dept_id=".$uid);

     if($que)
     {
         echo 1;
     }
     else
     {
         echo 0;
     }

?>
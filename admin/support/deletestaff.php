<?php
   include("../../includes/configuration.php");
     $empid=$_POST['empid'];
     $setval='1';
    $data= array('isdel'=>'1', 'isActive'=>'0');
     $que=updateQuery(SUPPORTSTAFF,$data,"emp_id=".$empid);
         if($que){   echo '1'; }
         else{ echo "0";  }
?>
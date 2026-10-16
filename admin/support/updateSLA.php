<?php
     include("../../includes/configuration.php");

       $deptid=$_REQUEST['deptid'];
       $sla=$_REQUEST['sla'];
       $deptmgr=$_REQUEST['deptmgr'];
       $deptname=$_REQUEST['deptName'];
       $data=array(
         "dept_name"=>$deptname,
         "SLAtime"=>$sla,
         "dept_mgr"=>$deptmgr
       );


     $pdate=updateQuery(SUPPORTDEPT,$data,"dept_id=".$deptid);
     if($pdate)
     {
         echo 1;
     }
     else
     {
         echo 0;
     }
?>
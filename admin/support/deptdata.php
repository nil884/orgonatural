<?php
   include("../../includes/configuration.php");
  $dept=$_REQUEST['dept'];
  $sla=$_REQUEST['sla'];

  $result=selectQuery(SUPPORTDEPT,"*","dept_name='".$dept."' and isDel='0'");

   if( count($result) > 0)
   {
     echo 0;
    }
    else
    {
         $data=array(
               'dept_name'=>$dept,
                'SLAtime'=>$sla,
               'created_on'=> date("d/m/Y H:i:s"),
               'isActive'=>'1'
                );
         $insert=insertQuery(SUPPORTDEPT,$data);


 if($insert)
 {
     echo 1;
 }
 else
 {
     echo 2;
 }


 }
?>
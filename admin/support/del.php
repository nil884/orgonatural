<?php
     include("../../includes/configuration.php");
     $uid=$_POST['uid'];  $setval='1';
    $data= array('isdel'=>$setval,'isActive'=>'0');
     $que=updateQuery(SUPPORTDEPT,$data,"dept_id=".$uid);
         if($que){
             /* To update access of group */
             $getstaffgroup=selectQuery(SUPPORTSTAFFGROUP,"access_to_dept,group_id");
             for($i=0;$i<count($getstaffgroup);$i++){
                 $access=$getstaffgroup[$i]['access_to_dept'];
                 $accessarr=explode(",",$access);
                 $newarr=array();
                 for($j=0;$j<sizeof($accessarr);$j++){
                     if($accessarr[$j]==$uid){}
                     else{ array_push($newarr,$accessarr[$j]); }
                 }
                 $newaccessstr=implode(",",$newarr);
                  $dataaccess= array('access_to_dept'=>$newaccessstr);
                 $updategroup=updateQuery(SUPPORTSTAFFGROUP,$dataaccess,"group_id=".$getstaffgroup[$i]['group_id']);
             }

             /* to update department of group */
             $datadept=array('department'=>'0');
             $getstaff=updateQuery(SUPPORTSTAFF,$datadept,"department='".$uid."'");
           echo 1;
         }
         else{echo 0;}
?>
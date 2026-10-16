<?php
     include("../../includes/configuration.php");
     $action =$_REQUEST['action'];
     if($action == "add")
     {
       $grpname=$_REQUEST['grpname'];
       $status=$_REQUEST['status'];
       $chkId =$_REQUEST['chkId'];
       $Create = $_REQUEST['Create'];
       $Edit = $_REQUEST['Edit'];
       $Close = $_REQUEST['Close'];
       $Transfer = $_REQUEST['Transfer'];
       $Delete1 = $_REQUEST['Delete1'];

      $result=selectQuery(SUPPORTSTAFFGROUP,"*","group_name='".$grpname."'");
   if( count($result) > 0)
   {
     echo 3;
    }
    else
    {
       $data=array(
         "group_name"=>Ucfirst($grpname),
         "group_status"=> $status,
         "access_to_dept" =>$chkId,
         "Create" => $Create ,
         "Edit" => $Edit ,
         "Close" => $Close ,
         "Transfer" => $Transfer ,
         "Delete1" => $Delete1

       );
     $pdate=insertQuery(SUPPORTSTAFFGROUP,$data);
     if($pdate)
     {
         echo 1;
     }
     else
     {
         echo 0;
     }
     }
   }

   if($action == "del")
   {

    $uid=$_POST['uid'];
     $setval='1';
    $data= array(
         'isDel'=>$setval,
         'group_status'=>'0'
      );
     $que=updateQuery(SUPPORTSTAFFGROUP,$data,"group_id=".$uid);
         if($que)
         {
              /* to update group of group */
             $datadept=array(
              'emp_group'=>'0'
             );
             $getstaff=updateQuery(SUPPORTSTAFF,$datadept,"emp_group='".$uid."'");
           echo 1;
         }
         else
         {
           echo 0;
         }
   }

   if($action == "statusupdate"){
          $uid=$_REQUEST['uid'];

     $setval=$_REQUEST['status'];
        $data= array(
             'group_status'=>$setval
          );
     $que=updateQuery(SUPPORTSTAFFGROUP,$data,"group_id=".$uid);

     if($que)
     {
         echo 1;
     }
     else
     {
         echo 0;
     }

   }


    if($action == "update"){
       $grid = $_REQUEST['grid'];
       $grpname=$_REQUEST['grpname'];
       $status=$_REQUEST['status'];
       $chkId =$_REQUEST['chkId'];
       $Create = $_REQUEST['Create'];
       $Edit = $_REQUEST['Edit'];
       $Close = $_REQUEST['Close'];
       $Transfer = $_REQUEST['Transfer'];
       $Delete1 = $_REQUEST['Delete1'];


       $data=array(
         "group_name"=>Ucfirst($grpname),
         "group_status"=> $status,
         "access_to_dept" =>$chkId,
         "Create" => $Create ,
         "Edit" => $Edit ,
         "Close" => $Close ,
         "Transfer" => $Transfer ,
         "Delete1" => $Delete1 ,


       );
     $pdate=updateQuery(SUPPORTSTAFFGROUP,$data,"group_id=".$grid);
     if($pdate)
     {
         echo 1;
     }
     else
     {
         echo 0;
     }

    }

    if($action == "checkavailabilty"){
         $grpname=$_REQUEST['grpname'];
          $result=selectQuery(SUPPORTSTAFFGROUP,"*","group_name='".$grpname."'");
   if( count($result) > 0)
   {
     echo 0;
    }
    else
    {
        echo 1;
    }
    }
?>
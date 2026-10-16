<?php
     include("../../includes/configuration.php");

       $empid=$_REQUEST['empid'];
       $getempdata=selectQuery(SUPPORTSTAFF,"*","emp_id=".$empid);
        if($getempdata[0]['admin_panel_access']=="1")
        {
            $getadmin=selectQuery(USER,"*","username='".$getempdata[0]['username']."'");
        }
        $username=$_REQUEST['username'];
        $group=$_REQUEST['group'];
        $dept=$_REQUEST['dept'];
       $empname=$_REQUEST['empname'];
       $emppost=$_REQUEST['emppost'];
       $empemail=$_REQUEST['empemail'];
       $emppwd=$_REQUEST['emppwd'];
       $empstatus=$_REQUEST['status'];
       $acctype=$_REQUEST['acctype'];
       $adminacceess=$_REQUEST['adminacceess'];
        
       $check_staff_mail = selectQuery(SUPPORTSTAFF,"emp_id","emp_id <> '".$empid."'   and emp_email = '".$empemail."' ");
      
       if(count($check_staff_mail) == 0 ){
       $data=array(
         "emp_name"=>$empname,
         "emp_email"=>$empemail,
         "username"=>$username,
         "emp_group"=>$group,
         "department"=>$dept,
         "acc_type"=>$acctype,
         'admin_panel_access'=>$adminacceess,
         "isActive"=>$empstatus
       );
      if($emppwd!="")
      {
          $data['emp_pwd']=md5($emppwd);
      }

     $pdate=updateQuery(SUPPORTSTAFF,$data,"emp_id=".$empid);
     if($pdate)
     {
          if(count($getadmin))
          {
            if($adminacceess=="1")
              {
                    $dataadmin=array(
                       "username"=>$username,
                       "isActive"=>$empstatus
                    );
                    if($emppwd!="")
                      {
                          $dataadmin['password']=md5($emppwd);
                      }
                     $addadmin=updateQuery(USER,$dataadmin,"u_id=".$getadmin[0]['u_id']);
              }
              else
              {
                    $deladmin=deleteQuery(USER,"u_id=".$getadmin[0]['u_id']);
              }
          }
          else
          {
              if($adminacceess=="1")
              {
                   $getlastadmin=selectQuery(USER,"*","u_id=1");
                    $accesstomenu=$getlastadmin[0]['allocatemenu'];
                     $getempdata1=selectQuery(SUPPORTSTAFF,"*","emp_id=".$empid);
                    $adminadata=array(
                     "utype"=>"Admin",
                     "u_name"=>"Admin",
                     "u_mob"=>"",
                     "u_email"=>$empemail,
                     "username"=>$username,
                     "password"=>$getempdata1[0]['emp_pwd'],
                     "secrete_pin"=>"0",
                     "allocatemenu"=>$accesstomenu,
                     "isActive"=>"1"
                    );
                    $addadmin=insertQuery(USER,$adminadata);
              }
          }


         echo 1;
     }
     else
     {
         echo 0;
     }


    }
    else {
      echo 3;
    }    
?>
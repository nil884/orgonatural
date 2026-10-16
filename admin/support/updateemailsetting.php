<?php
     include("../../includes/configuration.php");
     $dept_iso=$_REQUEST['dept_iso'];
     $new_tkt=$_REQUEST['new_tkt'];
 
     $new_msg=$_REQUEST['new_msg'];
     $overdue_tkt=$_REQUEST['overdue_tkt'];
     $new_tkt_alerts=$_REQUEST['new_tkt_alerts'];
     $new_msg_alerts=$_REQUEST['new_msg_alerts'];
     $overdue_alerts=$_REQUEST['overdue_alerts'];

      $imgallowed=$_REQUEST['imgallowed'];
       $appallowed=$_REQUEST['appallowed'];
        $attachmax=$_REQUEST['attachmax'];
       if($_REQUEST['attachmax']!="")
       {
          $attachmax=($_REQUEST['attachmax']*1024)*1024;         
       }
       else
       {
         $attachmax="";
       }
      $new_tkt_alert_client=0;
         $new_tkt_alert_admin=0;
         $new_tkt_alert_mgr=0;
         $new_tkt_alert_staff=0;

        $new_msg_alert_client=0;
         $new_msg_alert_admin=0;
         $new_msg_alert_mgr=0;
         $new_msg_alert_resp=0;

          $overdue_alert_admin=0;
         $overdue_alert_mgr=0;
         $overdue_alert_staff=0;

   if($new_tkt==1)
     {
         if(strpos($new_tkt_alerts, 'Client') !== false)
         {
             $new_tkt_alert_client=1;
         }
          if(strpos($new_tkt_alerts, 'Admin') !== false)
         {
             $new_tkt_alert_admin=1;
         }
          if(strpos($new_tkt_alerts, 'Manager') !== false)
         {
             $new_tkt_alert_mgr=1;
         }
          if(strpos($new_tkt_alerts, 'Staff') !== false)
         {
             $new_tkt_alert_staff=1;
         }

     }
     else
     {

     }

       if($new_msg==1)
     {

         if(strpos($new_msg_alerts, 'Client') !== false)
         {
             $new_msg_alert_client=1;
         }
          if(strpos($new_msg_alerts, 'Admin') !== false)
         {
             $new_msg_alert_admin=1;
         }
          if(strpos($new_msg_alerts, 'Manager') !== false)
         {
             $new_msg_alert_mgr=1;
         }
          if(strpos($new_msg_alerts, 'Respondent') !== false)
         {
             $new_msg_alert_resp=1;
         }
     }
     else
     {

     }

      if($overdue_tkt==1)
     {

            if(strpos($overdue_alerts, 'Admin') !== false)
             {
                 $overdue_alert_admin=1;
             }
              if(strpos($overdue_alerts, 'Manager') !== false)
             {
                 $overdue_alert_mgr=1;
             }
              if(strpos($overdue_alerts, 'Staff') !== false)
             {
                 $overdue_alert_staff=1;
             }
     }
     else
     {

     }


$data=array(
  "department_issolation"=>$dept_iso,
  "new_tkt_alert"=>$new_tkt,
  "new_tkt_alert_admin"=>$new_tkt_alert_admin,
  "new_tkt_alert_mgr"=>$new_tkt_alert_mgr,
  "new_tkt_alert_members"=>$new_tkt_alert_staff,
  "new_tkt_alert_client"=>$new_tkt_alert_client,
  "new_msg_alert"=>$new_msg,
  "new_msg_alert_admin"=>$new_msg_alert_admin,
  "new_msg_alert_mgr"=>$new_msg_alert_mgr,
  "new_msg_alert_respondent"=>$new_msg_alert_resp,
  "new_msg_alert_client"=>$new_msg_alert_client,
  "overdue_tkt"=>$overdue_tkt,
  "overdue_tkt_mgr"=>$overdue_alert_mgr,
  "overdue_tkt_admin"=>$overdue_alert_admin,
  "overdue_tkt_staff"=>$overdue_alert_staff,
  "attachment_size"=>$attachmax,
  "img_types"=>$imgallowed,
  "application_types"=>$appallowed
);


 $support=updateQuery(SUPPORTEMAIL,$data,"alert_id=1");
 if($support)
 {
     echo "1";
 }
 else
 {
     echo 0;
 }

?>
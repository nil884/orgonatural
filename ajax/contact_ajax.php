<?php
include("../includes/configuration.php");
$supportmail=selectQuery(SUPPORTEMAIL,"new_tkt_alert,new_tkt_alert_admin,new_tkt_alert_mgr,new_tkt_alert_members,new_tkt_alert_client");
if($action=="enquiry_on_sms")
{   $staff= array();$staffemails = array();
    if($enquiry_session==$_SESSION['enquiry']&&$name!=""&&$mobile!=""&&$email!=""&&$message!="")
    { 
        $getfirst_department  = selectQuery(SUPPORTDEPT,"dept_id","isDel='0' and isActive= '1'  order by dept_id asc limit 1") ;
        if($getfirst_department[0]['dept_id'] == "") { $deptid="1"; } else { $deptid = $getfirst_department[0]['dept_id']; }
   
    if($deptid!="")
    {
    $getdept=selectQuery(SUPPORTDEPT,"SLAtime,dept_name,dept_mgr","dept_id=".$deptid);
   
    $time=$getdept[0]['SLAtime'];
    if($time!="")
    {
        $a="+".$time." hours";
        $new_time = date("Y-m-d H:i:s",strtotime($a));
    }
    else
    {
        $new_time ="";
    }
    $getstaff=selectQuery(SUPPORTSTAFF,"emp_name,emp_email","department=".$deptid." and isDel='0'");
    for($k=0;$k<count($getstaff);$k++)
    {
        if($getstaff[$k]['emp_name']!=""&&$getstaff[$k]['emp_email']!="")
        {
            array_push($staff,$getstaff[$k]['emp_name']);
            array_push($staffemails,$getstaff[$k]['emp_email']);
        }
    }
    $dept=$getdept[0]['dept_name'];
    $deptstr='For Department '.$dept;
    }
    else
    {
    $dept="";
    $deptstr='For Department - Not Defined';
    }
    $staffemaillist=implode(",",$staffemails);
    $lastRE=selectQuery(CONTACT,"contact_request_id","1 order by req_id DESC LIMIT 1");
    if(count($lastRE))
    {
    $reqid=getleadno($lastRE[0]['contact_request_id']);
    }
    else
    {
    $reqid="000001";
    }
    $data=array(
    "tkt_source"=>"SMS",
    "contact_request_id"=>$reqid,
    "entry_by"=>"Client",
    "Name"=>$name,
    "Email"=>$email,
    "Telephone"=>$mobile,
    "Comment"=>$message,
    "dept"=>$deptid,
    "date"=>date("Y-m-d H:i:s"),
    "overdue_date"=>$new_time,
    "isOpen"=>'1',
    );
    $last=insertQuery(CONTACT,$data);
if($last)
{  $get_manager_email = selectQuery(SUPPORTSTAFF,"emp_email","emp_id=".$getdept[0]['dept_mgr']." and isDel='0'");
    $replacement_array =  array(
    'siteurl' => SITEURL, 
    'sitename' => SITENAME,
    'smssitename' => SMSSITENAME,
    "name" =>  $name,
    "mobile"=> $mobile,
    "email"=>$email,
    "message" => $message,
    "deparment" => $deptstr,
    "Department_manager"=> $get_manager_email[0]['emp_email'],
    "contact_request_id"=> $reqid,
    );
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
if($supportmail[0]['new_tkt_alert']=="1")
{ 
if($supportmail[0]['new_tkt_alert_admin']=="1")
{   
    $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Enquiry on sms' and  mail_to= 'Admin' "); 
    $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
    $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
    $sentmail_admin = mail(EMAIL_CONTACT, $subject_admin, $body_admin, $headers);   
}
if($dept!="")
{
 if($supportmail[0]['new_tkt_alert_mgr']=="1")
    {
    $emailmgr =$get_manager_email[0]['emp_email'];
    $manger_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Enquiry on sms' and  mail_to= 'Department Manager' "); 
    $subject_manager=convertemailstr($replacement_array,$manger_email[0]['subject']);
    $body_manager=convertemailstr($replacement_array,$manger_email[0]['body']);
    $sentmail_manager = mail($emailmgr, $subject_manager, $body_manager, $headers);  
   
    
   }
   if($supportmail[0]['new_tkt_alert_members']=="1")
    {
    if(sizeOf($staffemails))
      {
        $emailstaff =$staffemaillist;
        $staff_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Enquiry on sms' and  mail_to= 'Staff' "); 
        $subject_staff=convertemailstr($replacement_array,$staff_email[0]['subject']);
        $body_staff=convertemailstr($replacement_array,$staff_email[0]['body']);
        $sentmail_staff = mail($emailstaff, $subject_staff, $body_staff, $headers);  
                        
      }
    }  
}  
if($supportmail[0]['new_tkt_alert_client']=="1")
{
    $client_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Enquiry on sms' and  mail_to= 'Client' "); 
    $subject_client=convertemailstr($replacement_array,$client_email[0]['subject']);
    $body_client=convertemailstr($replacement_array,$client_email[0]['body']);
    $sentmail1client = mail($email, $subject_client, $body_client, $headers);
             
   
}


}   


if(SMS_SYSTEM=="ON")
{ $client_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Enquiry on sms' and  sms_to = 'Client' ");
    $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Enquiry on sms' and  sms_to = 'Admin' ");
    $msg= convertsmsstr($replacement_array,$client_sms[0]['sms_text'] );
    $msg_admin= convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
    $templateId = $client_sms[0]['templateId'];
    $sms= sendsms($mobile,$msg,WORKINGKEY,SMS_SENDER,$templateId);   
  $id=(unserialize($sms));
            $msid=$id['data']['0']['id'];
            $status=$id['data']['0']['status'];
            $datasms=array(
              "msg_id"=>$msid,
              "msg_type"=>"Enquiry SMS To Client",
              "user_name"=>$name,
              "mobile_no"=>$mobile,
              "message"=>$msg,
              "date"=>date("Y-m-d H:i:s"),
              "status"=>$status,
            );
        $insert=insertQuery(SMS,$datasms);
      
        $arr = explode(",",ENQ_CONACT);
        for($k=0;$k<sizeOf($arr);$k++)
        {
            $tempmob = $arr[$k];
            $templateId= $admin_sms[0]['templateId'];
           $sms1= sendsms($tempmob,$msg_admin,WORKINGKEY,SMS_SENDER,$templateId);
            $id1=(unserialize($sms1));
            $msid1=$id1['data']['0']['id'];
            $status1=$id1['data']['0']['status'];
            $datasms1=array(
              "msg_id"=>$msid1,
              "msg_type"=>"Enquiry SMS To Admin",
              "user_name"=>"Admin",
              "mobile_no"=>$tempmob,
              "message"=>$msg_admin,
              "date"=>date("Y-m-d H:i:s"),
              "status"=>$status1,
             
            );
            $insert1=insertQuery(SMS,$datasms1);
        }
        }    
        echo base64_encode($reqid);    
}
else
{
    echo "2";
}



} 
else {
    echo "2";
}
      
}



if($action == "contact_request"){
if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0)
 {
   echo "3";
 } 
 else {  
$supportmail=selectQuery(SUPPORTEMAIL,"new_tkt_alert,new_tkt_alert_admin,new_tkt_alert_mgr,new_tkt_alert_members,new_tkt_alert_client");
$staff=array();$staffemails=array();
$deptid = $_REQUEST['dept'];
if($deptid!="")
{
 $getdept=selectQuery(SUPPORTDEPT,"SLAtime,dept_name,dept_mgr","dept_id=".$deptid);
 $time=$getdept[0]['SLAtime'];
if($time!=""){
            $a="+".$time." hours";
            $new_time = date("Y-m-d H:i:s",strtotime($a));
          }
else { $new_time =""; }
$getstaff=selectQuery(SUPPORTSTAFF,"*","department=".$deptid." and isDel='0'");
for($k=0;$k<count($getstaff);$k++)
    {
       if($getstaff[$k]['emp_name']!=""&&$getstaff[$k]['emp_email']!="")
        {
            array_push($staff,$getstaff[$k]['emp_name']);
            array_push($staffemails,$getstaff[$k]['emp_email']);
        }
    }
$dept=$getdept[0]['dept_name'];
$deptstr='For Department '.$dept.'';
}
else
    {
        $dept="";
        $deptstr='For Department - Not Defined';
    }
$staffemaillist=implode(",",$staffemails);
$last=selectQuery(CONTACT,"*","1 order by req_id DESC LIMIT 1");
if(count($last))
        {
            $reqid=getleadno($last[0]['contact_request_id']);
        }
else { $reqid="100000"; }
              $data=array(
                "tkt_source"=>"Website",
                 "contact_request_id"=>$reqid,
                 "Name"=>$name,
                 "Email"=>$email,
                 "Telephone"=>$mobile,
                 "Comment"=>addslashes($message),
                 "dept"=>$deptid,
                 "date"=>date("Y-m-d H:i:s"),
                 "overdue_date"=>$new_time,
                 "isOpen"=>'1'
            );
            $last=insertQuery(CONTACT,$data);
            if($last)
            {  $get_manager_email = selectQuery(SUPPORTSTAFF,"emp_email","emp_id=".$getdept[0]['dept_mgr']." and isDel='0'");
                $replacement_array =  array(
                'siteurl' => SITEURL, 
                'sitename' => SITENAME,
                'smssitename' => SMSSITENAME,
                "name" =>  $name,
                "mobile"=> $mobile,
                "email"=>$email,
                "message" => $message,
                "deparment" => $deptstr,
                "Department_manager"=> $get_manager_email[0]['emp_email'],
                "contact_request_id"=> $reqid,
                );
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
            if($supportmail[0]['new_tkt_alert']=="1")  
            { 
            if($supportmail[0]['new_tkt_alert_admin']=="1")
            {   
                $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Contact' and  mail_to= 'Admin' "); 
                $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
                $sentmail_admin = mail(EMAIL_CONTACT, $subject_admin, $body_admin, $headers);   
            }
            if($dept!="")
            {
             if($supportmail[0]['new_tkt_alert_mgr']=="1")
                {   
                    
                $emailmgr =$get_manager_email[0]['emp_email'];
                $manger_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Contact' and  mail_to= 'Department Manager' "); 
                $subject_manager=convertemailstr($replacement_array,$manger_email[0]['subject']);
                $body_manager=convertemailstr($replacement_array,$manger_email[0]['body']);
                $sentmail_manager = mail($emailmgr, $subject_manager, $body_manager, $headers);  
                
               
                
               }
               if($supportmail[0]['new_tkt_alert_members']=="1")
                {
                   
                if(sizeOf($staffemails))
                  { 
                    $emailstaff =$staffemaillist;
                    $staff_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Contact' and  mail_to= 'Staff' "); 
                    $subject_staff=convertemailstr($replacement_array,$staff_email[0]['subject']);
                    $body_staff=convertemailstr($replacement_array,$staff_email[0]['body']);
                    $sentmail_staff = mail($emailstaff, $subject_staff, $body_staff, $headers);  
                   
                                    
                  }
                }  
            }  
            if($supportmail[0]['new_tkt_alert_client']=="1")
            {
                $client_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Contact' and  mail_to= 'Client' "); 
                $subject_client=convertemailstr($replacement_array,$client_email[0]['subject']);
                $body_client=convertemailstr($replacement_array,$client_email[0]['body']);
                $sentmail1client = mail($email, $subject_client, $body_client, $headers);
             }
            
            
            }   
            
            
            if(SMS_SYSTEM=="ON")
            { $client_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Contact' and  sms_to = 'Client' ");
                $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Contact' and  sms_to = 'Admin' ");
                $msg= convertsmsstr($replacement_array,$client_sms[0]['sms_text'] );
                $msg_admin = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                $templateId = $client_sms[0]['templateId'];
                $sms= sendsms($mobile,$msg,WORKINGKEY,SMS_SENDER,$templateId);  
                $id=(unserialize($sms));
                        $msid=$id['data']['0']['id'];
                        $status=$id['data']['0']['status'];
                        $datasms=array(
                          "msg_id"=>$msid,
                          "msg_type"=>"Enquiry",
                          "user_name"=>$name,
                          "mobile_no"=>$mobile,
                          "message"=>$msg,
                          "date"=>date("Y-m-d H:i:s"),
                          "status"=>$status,
                        );
                    $insert=insertQuery(SMS,$datasms);
                  
                    $arr = explode(",",ENQ_CONACT);
                    for($k=0;$k<sizeOf($arr);$k++)
                    {
                        $tempmob = $arr[$k];
                        $templateId= $admin_sms[0]['templateId'];
                       $sms1= sendsms($tempmob,$msg_admin,WORKINGKEY,SMS_SENDER,$templateId);
                        $id1=(unserialize($sms1));
                        $msid1=$id1['data']['0']['id'];
                        $status1=$id1['data']['0']['status'];
                        $datasms1=array(
                          "msg_id"=>$msid1,
                          "msg_type"=>"Enquiry",
                          "user_name"=>"Admin",
                          "mobile_no"=>$tempmob,
                          "message"=>$msg_admin,
                          "date"=>date("Y-m-d H:i:s"),
                          "status"=>$status1,
                         
                        );
                        $insert1=insertQuery(SMS,$datasms1);
                    }
                    }    
                    echo base64_encode($reqid);    
            }
            else
            {
                echo "2";
            }       



}


}



if($action == "contact_request_new"){
    $replacement_array =  array(
        'siteurl' => SITEURL, 
        'sitename' => SITENAME,
        'smssitename' => SMSSITENAME,
        "name" =>  $name,
        "mobile"=> $mobile,
        "email"=>$email,
        "message" => $message,
        );
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
    
    $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Contact' and  mail_to= 'Admin' "); 
    $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
    $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
    $sentmail_admin = mail(EMAIL_CONTACT, $subject_admin, $body_admin, $headers); 
    
    
    $client_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Contact' and  mail_to= 'Client' "); 
    $subject_client=convertemailstr($replacement_array,$client_email[0]['subject']);
    $body_client=convertemailstr($replacement_array,$client_email[0]['body']);
    $sentmail1client = mail($email, $subject_client, $body_client, $headers);
    
    
     if(SMS_SYSTEM=="ON")
            { $client_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Contact' and  sms_to = 'Client' ");
              $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Contact' and  sms_to = 'Admin' ");
              $msg= convertsmsstr($replacement_array,$client_sms[0]['sms_text'] );
              $msg_admin= convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                $templateId= $client_sms[0]['templateId'];
              $sms= sendsms($mobile,$msg,WORKINGKEY,SMS_SENDER,$templateId);
              $id=(unserialize($sms));
                        $msid=$id['data']['0']['id'];
                        $status=$id['data']['0']['status'];
                        $datasms=array(
                          "msg_id"=>$msid,
                          "msg_type"=>"Contact SMS To Client",
                          "user_name"=>$name,
                          "mobile_no"=>$mobile,
                          "message"=>$msg,
                          "date"=>date("Y-m-d H:i:s"),
                          "status"=>$status,
                        );
                    $insert=insertQuery(SMS,$datasms);
                  
                    $arr = explode(",",ENQ_CONACT);
                    for($k=0;$k<sizeOf($arr);$k++)
                    {
                        $tempmob = $arr[$k];
                        $templateId = $admin_sms[0]['templateId'];
                       $sms1 = sendsms($tempmob,$msg_admin,WORKINGKEY,SMS_SENDER,$templateId);
                        
                        $id1=(unserialize($sms1));
                        $msid1=$id1['data']['0']['id'];
                        $status1=$id1['data']['0']['status'];
                        $datasms1=array(
                          "msg_id"=>$msid1,
                          "msg_type"=>"Contact SMS To Admin",
                          "user_name"=>"Admin",
                          "mobile_no"=>$tempmob,
                          "message"=>$msg_admin,
                          "date"=>date("Y-m-d H:i:s"),
                          "status"=>$status1,
                         
                        );
                        $insert1=insertQuery(SMS,$datasms1);
                    }
                    }
    if($sentmail_admin){
        echo "1";
    }
    else{
          echo "0";
    }

}



if($action == "enquiry_on_sms_new"){
    $replacement_array =  array(
        'siteurl' => SITEURL, 
        'sitename' => SITENAME,
        'smssitename' => SMSSITENAME,
        "name" =>  $name,
        "mobile"=> $mobile,
        "email"=>$email,
        "message" => $message,
        );
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";

    $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Enquiry on sms' and  mail_to= 'Admin' "); 
    $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
    $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
    $sentmail_admin = mail(EMAIL_CONTACT, $subject_admin, $body_admin, $headers);  
    
    $client_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Enquiry on sms' and  mail_to= 'Client' "); 
    $subject_client=convertemailstr($replacement_array,$client_email[0]['subject']);
    $body_client=convertemailstr($replacement_array,$client_email[0]['body']);
    $sentmail1client = mail($email, $subject_client, $body_client, $headers);
    
    if(SMS_SYSTEM=="ON")
            { 
    $client_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Enquiry on sms' and  sms_to = 'Client' ");
    $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Enquiry on sms' and  sms_to = 'Admin' ");
    $msg= convertsmsstr($replacement_array,$client_sms[0]['sms_text'] );
    $msg_admin= convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );  
    $templateId= $client_sms[0]['templateId'];
    $sms= sendsms($mobile,$msg,WORKINGKEY,SMS_SENDER,$templateId);  
    $id=(unserialize($sms));
            $msid=$id['data']['0']['id'];
            $status=$id['data']['0']['status'];
            $datasms=array(
              "msg_id"=>$msid,
              "msg_type"=>"Enquiry SMS To Client",
              "user_name"=>$name,
              "mobile_no"=>$mobile,
              "message"=>$msg,
              "date"=>date("Y-m-d H:i:s"),
              "status"=>$status,
            );
        $insert=insertQuery(SMS,$datasms);
        $arr = explode(",",ENQ_CONACT);
        for($k=0;$k<sizeOf($arr);$k++){
            $tempmob = $arr[$k];
            $templateId = $admin_sms[0]['templateId'];
            $sms1 = sendsms($tempmob,$msg_admin,WORKINGKEY,SMS_SENDER,$templateId);
            $id1=(unserialize($sms1));
            $msid1=$id1['data']['0']['id'];
            $status1=$id1['data']['0']['status'];
            $datasms1=array(
            "msg_id"=>$msid1,
            "msg_type"=>"Enquiry SMS To Admin",
            "user_name"=>"Admin",
            "mobile_no"=>$tempmob,
            "message"=>$msg_admin,
            "date"=>date("Y-m-d H:i:s"),
            "status"=>$status1,
             
            );
            $insert1=insertQuery(SMS,$datasms1);
        }
        }
    
    if($sentmail_admin){
        echo 1;
    }
    else {
        echo 0;
    }

}   




?>



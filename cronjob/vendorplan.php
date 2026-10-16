<?php
 include("../includes/configuration.php");
$date=date('Y-m-d',strtotime("-1 days"));
$tomorrowdate=date('Y-m-d',strtotime("+1 days"));
$getcurrplan=selectQuery(VENDORPLANSELECTED,"sel_id,dealer_id,plan","plan_to<='".$date."' and plan_status='Active'");
$tomorrowExpiry=selectQuery(VENDORPLANSELECTED,"sel_id,dealer_id,plan","plan_to='".$tomorrowdate."' and plan_status='Active'");

for($i=0;$i<count($getcurrplan);$i++){
 $getdealerkey=selectQuery(VENDOR," nickname,personalcontactno,dealer_id,dealer_key,email","dealer_id=".$getcurrplan[$i]['dealer_id']);
    $data=array('plan_status'=>"Expired");
    $updatecurrplan=updateQuery(VENDORPLANSELECTED,$data,"sel_id=".$getcurrplan[$i]['sel_id']);
    if($updatecurrplan){   
        $today=date("Y-m-d");
          $getupcoming=selectQuery(VENDORPLANSELECTED,"sel_id","dealer_id=".$getcurrplan[$i]['dealer_id']." and plan_status='Upcoming' and plan_from<='".$today."'");
          if(count($getupcoming)){   
            
               $data13=array('plan_status'=>"Active");
                updateQuery(VENDORPLANSELECTED,$data13,"sel_id=".$getupcoming[0]['sel_id']);
                $after_update_plan = selectQuery(VENDORPLANSELECTED,"payment_status ,plan","sel_id=".$getupcoming[0]['sel_id']."");
                s$data12=array('plan'=>$getupcoming[0]['sel_id'],'plan_status'=>"Active");
                if($after_update_plan[0]['payment_status'] == 'Received'){ $data12['payment_status']='Received';}
                else {$data12['payment_status']='Pending';}
                  $query12=updateQuery(VENDOR,$data12,"dealer_id=".$getcurrplan[$i]['dealer_id']);
                  if($query12){
                    $replacement_array = array(
                      'siteurl' => SITEURL, 'sitename' => SITENAME,
                      'smssitename' => SMSSITENAME,'vendornickname' => $getdealerkey[0]['nickname'],
                      'vendorcurrentplan' => $after_update_plan[0]['plan']
                    );
                    $mail1=$getdealerkey[0]['email'];
                    $headers  = 'MIME-Version: 1.0' . "\r\n";  
                     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                     $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
                    $vendor_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Plan Upgrade After Current Plan Expiry' and  mail_to= 'Vendor'"); 
                    $subject_vendor=convertemailstr($replacement_array,$vendor_email[0]['subject']);
                    $body_vendor=convertemailstr($replacement_array,$vendor_email[0]['body']);
                    $sentmail = mail($mail1, $subject_vendor, $body_vendor, $headers);
                    
                    $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Plan Upgrade After Current Plan Expiry' and  mail_to= 'Admin'"); 
                    $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
                    $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
                    $sentmail1 = mail(EMAIL_REG, $subject_admin, $body_admin, $headers);
                    
                    if(SMS_SYSTEM=="ON"){
                         $vendor_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Plan Upgrade After Current Plan Expiry' and  sms_to = 'Vendor'");
                         $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Plan Upgrade After Current Plan Expiry' and  sms_to = 'Admin'");
                        $msg= convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
                        $templateId = $vendor_sms[0]['templateId'];
                        $sms = sendsms($getdealerkey[0]['personalcontactno'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                        $id=(unserialize($sms));
                        $msid=$id['data']['0']['id']; $status=$id['data']['0']['status'];
                         $datasms=array(
                           "msg_id"=>$msid,"msg_type"=>"Vendor Plan Upgrade After Current Plan Expiry SMS To Vendor",
                             "user_id"=> "",
                           "user_name"=> $getdealerkey[0]['nickname'],
                           "mobile_no"=>$getdealerkey[0]['personalcontactno'],
                           "message"=>$msg,
                           "date"=>date("Y-m-d H:i:s"),
                           "status"=>$status
                         );    
                        
                         insertQuery(SMS,$datasms);
                         
                        $arr = explode(",",ADMINCONTACT);
                        for($k=0;$k<sizeOf($arr);$k++){
                            $tempmob = $arr[$k];
                            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                            $templateId= $admin_sms[0]['templateId'];
                          $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                            $id1=(unserialize($sms1));
                            $msid1=$id1['data']['0']['id'];
                            $status1=$id1['data']['0']['status'];
                            $datasms1=array(
                              "msg_id"=>$msid1,
                              "msg_type"=>"Vendor Plan Upgrade After Current Plan Expiry SMS To Admin",
                              "user_name"=>"Admin",
                              "mobile_no"=>$tempmob,
                              "message"=>$msg,
                              "date"=>date("Y-m-d H:i:s"),
                              "status"=>$status1);
                            $insert1=insertQuery(SMS,$datasms1);
                        }   
                      }
                      
                  }
          }

          else{
           
                 
                 $query=updateQuery(VENDOR,$data,"dealer_id=".$getdealerkey[0]['dealer_id']);
                 if($query)
                 {
                     $data1=array('isActive' => "0");
                    updateQuery(PRODINFO,$data1,"vendor ='".$getdealerkey[0]['dealer_id']."'");
                    
                     $mail1=$getdealerkey[0]['email'];
                     $headers  = 'MIME-Version: 1.0' . "\r\n";  
                     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                     $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
                     $vendor_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Plan Expire' and  mail_to= 'Vendor'"); 
                     $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Plan Expire' and  mail_to= 'Admin'");  
                     
                     $replacement_array = array(
                        'siteurl' => SITEURL, 
                        'sitename' => SITENAME,
                        'smssitename' => SMSSITENAME,
                        'vendornickname' => $getdealerkey[0]['nickname'],
                        'vendorcurrentplan' => $getcurrplan[$i]['plan']);
  
                     $subject_vendor=convertemailstr($replacement_array,$vendor_email[0]['subject']);
                     $body_vendor=convertemailstr($replacement_array,$vendor_email[0]['body']);
                      mail($mail1, $subject_vendor, $body_vendor, $headers);    
                       
                     $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
                     $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
                     @mail(EMAIL_REG, $subject_admin, $body_admin, $headers); 
                     if(SMS_SYSTEM=="ON"){
                         $vendor_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Plan Expire' and  sms_to = 'Vendor'");
                         $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Plan Expire' and  sms_to = 'Admin'");
                        $msg= convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
                        $templateId= $vendor_sms[0]['templateId'];
                        $sms= sendsms($getdealerkey[0]['personalcontactno'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                        $id=(unserialize($sms));
                        $msid=$id['data']['0']['id'];
                        $status=$id['data']['0']['status'];
                         $datasms=array(
                           "msg_id"=>$msid,
                           "msg_type"=>"Plan Expiration SMS To Vendor",
                             "user_id"=> "",
                           "user_name"=> $getdealerkey[0]['nickname'],
                           "mobile_no"=>$getdealerkey[0]['personalcontactno'],
                           "message"=>$msg,
                           "date"=>date("Y-m-d H:i:s"),
                           "status"=>$status
                         );    
                        
                        insertQuery(SMS,$datasms);
                         
                        $arr = explode(",",ADMINCONTACT);
                        for($k=0;$k<sizeOf($arr);$k++){
                            $tempmob = $arr[$k];
                            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                            $templateId= $admin_sms[0]['templateId'];
                          $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                            $id1=(unserialize($sms1));
                            $msid1=$id1['data']['0']['id'];
                            $status1=$id1['data']['0']['status'];
                            $datasms1=array(
                              "msg_id"=>$msid1,
                              "msg_type"=>"Plan Expiration SMS To Admin",
                              "user_name"=>"Admin",
                              "mobile_no"=>$tempmob,
                              "message"=>$msg,
                              "date"=>date("Y-m-d H:i:s"),
                              "status"=>$status1,
                            
                            );
                            insertQuery(SMS,$datasms1);
                        }   
                      }
                    }
          }
    }
}

//////////////////////////////////////////////////////////////

 $sms = selectQuery(SMS,"*","status<>'DELIVRD'");
    for($i=0;$i<count($sms);$i++){ 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api-alerts.solutionsinfini.com/v3/?method=sms.status&api_key=".WORKINGKEY."&format=PHP&id=".$sms[$i]['msg_id']."&numberinfo=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $arr=(unserialize($output));
        $msg=$arr['message'];
        echo $sms['sms_id'].print_r($arr);  echo "<hr>";
        $status=$arr['data'][0]['status'];
        if($msg=="Processed Successfully" && $status!=""){
            $data = array('status'=>$status);
            updateQuery(SMS,$data,"sms_id=".$sms[$i]['sms_id']);
        }
     }
/* *************************************************************************************** */
$today=date("Y-m-d");
for($i=0;$i<count($tomorrowExpiry);$i++){
  $row=$tomorrowExpiry[$i];
  $vendor=$row['dealer_id'];
  $getdealerkey=selectQuery(VENDOR,"nickname,personalcontactno,dealer_id,dealer_key,email,dealer_name,dealer_mobile","dealer_id=".$vendor);
  
  $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME,'smssitename' => SMSSITENAME,'vendornickname' => $getdealerkey[0]['nickname']);
  $mail1=$getdealerkey[0]['email'];
  $headers  = 'MIME-Version: 1.0' . "\r\n";  
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
  $vendor_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Plan Expiry On Tomorrow' and  mail_to= 'Vendor'"); 
  $subject_vendor=convertemailstr($replacement_array,$vendor_email[0]['subject']);
  $body_vendor=convertemailstr($replacement_array,$vendor_email[0]['body']);
  mail($mail1, $subject_vendor, $body_vendor, $headers);
                     
  $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Plan Expiry On Tomorrow' and  mail_to= 'Admin'"); 
  $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
  $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
  mail(EMAIL_REG, $subject_admin, $body_admin, $headers);
                     
  if(SMS_SYSTEM=="ON"){
    $vendor_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Plan Expiry On Tomorrow' and  sms_to = 'Vendor'");
    $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Plan Expiry On Tomorrow' and  sms_to = 'Admin'");
    $msg= convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
    $templateId = $vendor_sms[0]['templateId'];
    $sms = sendsms($getdealerkey[0]['personalcontactno'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
    $id=(unserialize($sms));
    $msid=$id['data']['0']['id']; $status=$id['data']['0']['status'];
    $datasms=array(
        "msg_id"=>$msid,"msg_type"=>"Vendor Plan Expiry SMS To Vendor",
        "user_id"=> "",
        "user_name"=> $getdealerkey[0]['nickname'],
        "mobile_no"=>$getdealerkey[0]['personalcontactno'],
        "message"=>$msg,
        "date"=>date("Y-m-d H:i:s"),
        "status"=>$status);    
                         
     insertQuery(SMS,$datasms);
                          
     $arr = explode(",",ADMINCONTACT);
     for($k=0;$k<sizeOf($arr);$k++){
        $tempmob = $arr[$k];
        $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
        $templateId= $admin_sms[0]['templateId'];
        $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
        $id1=(unserialize($sms1));
        $msid1=$id1['data']['0']['id'];
        $status1=$id1['data']['0']['status'];
        $datasms1=array(
          "msg_id"=>$msid1,
          "msg_type"=>"Vendor Plan Expiry SMS To Admin",
          "user_name"=>"Admin",
          "mobile_no"=>$tempmob,
          "message"=>$msg,
          "date"=>date("Y-m-d H:i:s"),
          "status"=>$status1);
        insertQuery(SMS,$datasms1);
      }   
   }
                       
            
 }
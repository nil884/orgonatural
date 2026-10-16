<?
 include("../includes/configuration.php");
 include_once('../easebuzz/easebuzz-lib/easebuzz_payment_gateway.php');

 $easeSALT = EASESALT;   $easeMerchant=EASEMARCHANTID;
 $easebuzzObj = new Easebuzz($easeMerchant, $easeSALT, EASEENV);

 $payuSALT = SALT;   $payuMerchant=MARCHANTKEY;  $payuUrl=PAYURL;

  $getrefundable=selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id JOIN ".BUYER." as usr on ord.user_id=usr.u_id","ord.payment_mode,ord.transaction_id,ord.payable_amount,ord.payment_id,ord.order_id,sub.item_id,sub.refundable_amount,sub.cancelation_date,sub.return_request_date,usr.u_id, usr.u_fname, usr.u_lname, usr.u_mobile, usr.u_email","sub.is_refund_appilicable='1' and sub.refund_status='0'");

  if(count($getrefundable)){
       $headers  = 'MIME-Version: 1.0' . "\r\n";
         $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
     for($i=0;$i<count($getrefundable);$i++){
       $row=$getrefundable[$i];
      
      
         $payment_mode=$row['payment_mode'];$transaction_id=$row['transaction_id'];
         $payable_amount=$row['payable_amount'];   $payment_id=$row['payment_id']; 
         $order_id=$row['order_id']; $item_id=$row['item_id'];
         $refundable_amount=$row['refundable_amount'];  $phone=$row['u_mobile']; $email=$row['u_email'];
         $user_id=$row['u_id']; $name=$row['u_fname']; $fullname=$row['u_fname']." ".$row['u_lname'];
         

          if($row['cancelation_date'] != "" && $row['cancelation_date'] != "0000-00-00 00:00:00") { $req_type = "cancellation";  } else if($row['return_request_date'] != "" && $row['return_request_date'] != "0000-00-00 00:00:00" ) { $req_type = "Return"; }
          $replacement_array = array(
                'siteurl' => SITEURL,
                'sitename' => SITENAME,
                'smssitename' => SMSSITENAME,
                 'request_type' => $req_type,
                 'username' => $fullname,
                 "refund_amount" => $refundable_amount, 
                 "order_id"=> $order_id
              );
            
         if($payment_mode=="Easebuzz"){

             $postData = array(
                "txnid" => $transaction_id,"refund_amount" => $refundable_amount,
                "phone" => $phone, "email" => $email, "amount" => $payable_amount
            ); 
            $result = $easebuzzObj->refundAPI($postData);
            if($result['status']=="true"){
                 $msg= $result['reason'];   $refund_id= $result['refund_id'];
                 $updatesubdata=array("refund_status"=>1,"refund_date"=>date("Y-m-d H:i:s"),"refund_id"=>$refund_id,"refund_response"=>$msg,"request_type"=>$req_type);
                 updateQuery(ORDERSUB,$updatesubdata,"item_id=".$item_id);

                $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Buyer' ");
                $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
                $body_user =convertemailstr($replacement_array,$user_email[0]['body']);
                 mail($email, $subject_user, $body_user, $headers);

                $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Admin' ");
                $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
                 mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);
                 
                   if(SMS_SYSTEM=="ON")
                  {
                  $buyer_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Refund Initiated' and  sms_to = 'Buyer' ");
                  $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Refund Initiated' and  sms_to = 'Admin' ");

                  $msg= convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                       
                $templateId= $buyer_sms[0]['templateId'];
                $sms= sendsms($phone,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                       
                  $id=(unserialize($sms));
                  $msid=$id['data']['0']['id'];
                  $status=$id['data']['0']['status'];
                  $datasms=array(
                  "msg_id"=>$msid,
                  "msg_type"=>"Refund SMS To Buyer",
                  "user_id"=> $user_id,
                  "user_name"=> $fullname,
                  "mobile_no"=>$phone,
                  "message"=>$msg,
                  "date"=>date("Y-m-d H:i:s"),
                  "status"=>$status
                );
                insertQuery(SMS,$datasms);
  
              $arr = explode(",",ADMINCONTACT);
                    for($k=0;$k<sizeOf($arr);$k++)
                    {
                        $tempmob = $arr[$k];
                        $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                        
                        $templateId= $admin_sms[0]['templateId'];
                        $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                        
                        $id1=(unserialize($sms1));
                        $msid1=$id1['data']['0']['id'];
                        $status1=$id1['data']['0']['status'];
                        $datasms1=array(
                          "msg_id"=>$msid1,
                          "msg_type"=>"Refund SMS To Admin",
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

         }else  if($payment_mode=="PayU Money"){
       
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, "https://www.payumoney.com/payment/merchant/refundPayment?merchantKey=".$payuMerchant."&paymentId=".$payment_id."&refundAmount=".$refundable_amount);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch1, CURLOPT_POST, true);

             curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:'.PAYUHEADER));
            $output = curl_exec($ch1);
            if (curl_errno($ch1)) {
                $error_msg = curl_error($ch1);
                echo $error_msg;
            }
            $array=json_decode($output,true);
          // echo $output;
           curl_close($ch1); 
            
          
            if($array['status']==0){
                 $msg= $array['message'];   $refund_id= $array['result'];
                 if($refund_id){
                 $updatesubdata=array("refund_status"=>1,"refund_date"=>date("Y-m-d H:i:s"),"refund_id"=>$refund_id,"refund_response"=>$msg);
                 updateQuery(ORDERSUB,$updatesubdata,"item_id=".$getrefundable[$i]['item_id']);

                $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Buyer' ");
                $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
                $body_user =convertemailstr($replacement_array,$user_email[0]['body']);
                 mail($email, $subject_user, $body_user, $headers);

                $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Admin' ");
                $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
                 mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);

                   if(SMS_SYSTEM=="ON")
                    {
                      $buyer_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Refund Initiated' and  sms_to = 'Buyer' ");
                      $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Refund Initiated' and  sms_to = 'Admin' ");

                      $msg= convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                       
                       $templateId = $buyer_sms[0]['templateId'];
                          $sms = sendsms($phone,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                       
                          $id=(unserialize($sms));
                          $msid=$id['data']['0']['id'];
                          $status=$id['data']['0']['status'];
                          $datasms=array(
                          "msg_id"=>$msid,
                          "msg_type"=>"Refund SMS To Buyer",
                          "user_id"=> $user_id,
                          "user_name"=> $fullname,
                          "mobile_no"=>$phone,
                          "message"=>$msg,
                          "date"=>date("Y-m-d H:i:s"),
                          "status"=>$status
                        );
                        insertQuery(SMS,$datasms);

                        $arr = explode(",",ADMINCONTACT);
                        for($k=0;$k<sizeOf($arr);$k++)
                        {
                            $tempmob = $arr[$k];
                            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                            $templateId= $admin_sms[0]['templateId'];
                           $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                            $id1=(unserialize($sms1));
                            $msid1=$id1['data']['0']['id'];
                            $status1=$id1['data']['0']['status'];
                            $datasms1=array(
                              "msg_id"=>$msid1,
                              "msg_type"=>"Refund SMS To Admin",
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
         }  else  if($payment_mode=="Instamojo"){
            include "../instamojo/Instamojo.php";
            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
           $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, INSTAURL.'refunds/');
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                        array("X-Api-Key:".INSTAAPIKEY,
                              "X-Auth-Token:".INSTATOKEN));
            $payload = array(
                'transaction_id'=> $txnid,
                'payment_id' => $payment_id,
                'refund_amount'=>$refundable_amount,
                'type' => 'QFL',
                'body' => "Customer isn't satisfied with the quality"
            );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);

            $array=json_decode($response,true);

            if($array['success']=="true"){
                 $msg= $array['status'];   $refund_id= $array['refund']['id'];
                 if($refund_id){
                 $updatesubdata=array("refund_status"=>1,"refund_date"=>date("Y-m-d H:i:s"),"refund_id"=>$refund_id,"refund_response"=>$msg);
                 updateQuery(ORDERSUB,$updatesubdata,"item_id=".$getrefundable[$i]['item_id']);

                $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Buyer' ");
                $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
                $body_user =convertemailstr($replacement_array,$user_email[0]['body']);
                 mail($email, $subject_user, $body_user, $headers);

                $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Admin' ");
                $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
                 mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);

                   if(SMS_SYSTEM=="ON")
                    {
                      $buyer_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId,templateId","type='Refund Initiated' and  sms_to = 'Buyer' ");
                      $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId,templateId","type='Refund Initiated' and  sms_to = 'Admin' ");

                      $msg= convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                        $templateId= $buyer_sms[0]['templateId'];
                          $sms= sendsms($phone,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                          $id=(unserialize($sms));
                          $msid=$id['data']['0']['id'];
                          $status=$id['data']['0']['status'];
                          $datasms=array(
                          "msg_id"=>$msid,
                          "msg_type"=>"Refund SMS To Buyer",
                          "user_id"=> $user_id,
                          "user_name"=> $fullname,
                          "mobile_no"=>$phone,
                          "message"=>$msg,
                          "date"=>date("Y-m-d H:i:s"),
                          "status"=>$status
                        );
                        insertQuery(SMS,$datasms);

                        $arr = explode(",",ADMINCONTACT);
                        for($k=0;$k<sizeOf($arr);$k++)
                        {
                            $tempmob = $arr[$k];
                            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                            $templateId= $admin_sms[0]['templateId'];
                           $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                            $id1=(unserialize($sms1));
                            $msid1=$id1['data']['0']['id'];
                            $status1=$id1['data']['0']['status'];
                            $datasms1=array(
                              "msg_id"=>$msid1,
                              "msg_type"=>"Refund SMS To Admin",
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
         }else if($payment_mode=="Razorpay"){
          $refunded= $refundable_amount*100;
                $payload = Array(
                    'speed'=> "optimum",
                    "amount"=>trim($refunded),
                  /*   'payment_id' => $payment_id, */
                   /*  'receipt'=> */
                );
            /*     echo "<pre>"; print_r($payload); */
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.razorpay.com/v1/payments/".$payment_id."/refund",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS =>json_encode($payload),
                  CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic ".base64_encode(RAZORAPIKEY.":".RAZORSECRETE),
                    "Content-Type: application/json"
                  ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $array=json_decode($response,true);
                print_r($array);
                if($array['status']){
                 $status= $array['status'];   $refund_id= $array['id'];
                 if($refund_id&&($status=="processed"||$status=="pending")){
                 $updatesubdata=array("refund_status"=>1,"refundable_amount"=>$refunded/100,"refund_date"=>date("Y-m-d H:i:s"),"refund_id"=>$refund_id,"refund_response"=>$status);
                 updateQuery(ORDERSUB,$updatesubdata,"item_id=".$getrefundable[$i]['item_id']);

                $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Buyer' ");
                $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
                $body_user =convertemailstr($replacement_array,$user_email[0]['body']);
                 mail($email, $subject_user, $body_user, $headers);

                $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Initiated' and  mail_to= 'Admin' ");
                $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
                 mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);

                   if(SMS_SYSTEM=="ON")
                    {
                      $buyer_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Refund Initiated' and  sms_to = 'Buyer' ");
                      $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Refund Initiated' and  sms_to = 'Admin' ");

                      $msg= convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                       
                       $templateId= $buyer_sms[0]['templateId'];
                          $sms= sendsms($phone,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                          $id=(unserialize($sms));
                          $msid=$id['data']['0']['id'];
                          $status=$id['data']['0']['status'];
                          $datasms=array(
                          "msg_id"=>$msid,
                          "msg_type"=>"Refund SMS To Buyer",
                          "user_id"=> $user_id,
                          "user_name"=> $fullname,
                          "mobile_no"=>$phone,
                          "message"=>$msg,
                          "date"=>date("Y-m-d H:i:s"),
                          "status"=>$status
                        );
                        insertQuery(SMS,$datasms);

                        $arr = explode(",",ADMINCONTACT);
                        for($k=0;$k<sizeOf($arr);$k++)
                        {
                            $tempmob = $arr[$k];
                            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                            $templateId= $admin_sms[0]['templateId'];
                            $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                            $id1=(unserialize($sms1));
                            $msid1=$id1['data']['0']['id'];
                            $status1=$id1['data']['0']['status'];
                            $datasms1=array(
                              "msg_id"=>$msid1,
                              "msg_type"=>"Refund SMS To Admin",
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
           }else{
            
            $headersmain  = 'MIME-Version: 1.0' . "\r\n";
               $headersmain .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
               $headersmain .= "From:".SITENAME."<".EMAIL_SENDER.">" . "\r\n";
               $headersmain .= 'Cc: support.s@surun.in,surunclients@gmail.com';
               $subjectmain="Alert! Refund Not Completed ".SITENAME." - ". $order_id;
               $bodymain='<div style="font-family: calibri;background-color: #fff;width: 100%;border: 1px solid #e6e6e6;max-width: 600px;padding: 20px;font-size: 18px;">
                <p style="margin-top:0;">Refund of Rs.'.($refunded/100).' for order id '.$order_id.' not completed because of below error -</p>
                <div>'.$array['error']['description'].'</div>
                </div>';
                $mainmailto=MAIN_ADMIN;
               mail($mainmailto, $subjectmain, $bodymain, $headersmain);
              
           }

         }

     }
 }

?>
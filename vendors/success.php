<?php include("../includes/configuration.php");
include_once('../easebuzz/easebuzz-lib/easebuzz_payment_gateway.php');
if(!$_POST){
    $SALT = EASESALT;
    $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $SALT, $ENV = null);
    $result = $easebuzzObj->easebuzzResponse( $_POST );
    $response = json_decode($result);
    $data = $response->data;
    $status = $data->status;
    $firstname = $data->firstname;
    $amount = $data->amount;
    $txnid = $data->txnid;
    $productinfo = $data->productinfo;
    $email = $data->email;
    $invoiceid = $data->udf1;
    $paytype = $data->udf2;
    //$final_amount = $data->udf2;
} else{
    $status = $_POST["status"];
    $firstname = $_POST["firstname"];
    $amount = $_POST["amount"];
    $txnid = $_POST["txnid"];
    $posted_hash = $_POST["hash"];
    $key = $_POST["key"];
    $productinfo = $_POST["productinfo"];
    $email = $_POST["email"];
    $invoiceid = $_POST["udf1"];
    $status1 = $_POST['unmappedstatus'];
    $error = $_POST['error'];
    $salt = SALT;
    $paytype = $_POST['udf2'];    
    if (isset($_POST["additionalCharges"])){
        $additionalCharges = $_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
    } else{
        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
    }
    $hash = hash("sha512", $retHashSeq);
} 

if($error!="E000" && $status!="success"){
    echo "Invalid Transaction. Please try again";
} else{
    $getpaymentdata = selectQuery(VENDORPAYMENT,"*","transaction_id='".$txnid."'");
    $sellerdata = selectQuery(VENDOR,"*" ,"dealer_id=".$getpaymentdata[0]['dealer_id']);
    $plandata = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$getpaymentdata[0]['plan_id']."'");
    $planduration = selectQuery(VENDORPLAN,"*","plan_name= '".$plandata[0]['plan']."'");
    $data = array( "payment_status"=>"Received", "payu_status"=>$status, 'paytype' => $paytype,);
    $update = updateQuery(VENDORPAYMENT,$data,"transaction_id='".$txnid."'");
    if($plandata[0]['plan_type']=="Renew"||$plandata[0]['plan_type']=="Upgrate - Normal"){
        $plandata1 = selectQuery(VENDORPLANSELECTED,"*","dealer_id=".$getpaymentdata[0]['dealer_id']." and payment_status='Received' order by sel_id DESC LIMIT 1");
        $current0 = $plandata1[0]['plan_to'];
        $current = date("Y-m-d", strtotime($plandata1[0]['plan_to'])); // replaced 1yr to DB days
    }
    else{ $current=date("Y-m-d"); }
    $dateOneYearAdded = strtotime(date("Y-m-d", strtotime($current)) . " +".$planduration[0]['plan_duration']." days"); 
    // replaced 1yr to DB days
    $oneyear = date('Y-m-d', $dateOneYearAdded);
    $yesterday1 = strtotime(date("Y-m-d", strtotime($oneyear)) . " -1 days");
    $yesterday = date('Y-m-d', $yesterday1);
    $dateOneYearAdded = strtotime(date("Y-m-d", strtotime($currentDate)) . " +".$planduration[0]['plan_duration']." days");  // replaced 1yr to DB days
    $data2 = array( "payment_status" => "Received", "plan_from" => $current, "plan_to" => $yesterday,
    "payment_date" => date("Y-m-d H:i"), "transaction_id" => $txnid, 'paymentgateway_status' => $status );    
    if(($plandata[0]['plan_type']=="Renew"||$plandata[0]['plan_type']=="Upgrate - Normal")){
        $data2['plan_status']="Upcoming";
        if($sellerdata[0]['plan_status'] == "Expired" ){
            $data2['plan_status']="Active";
        }
    } else{ $data2['plan_status']="Active"; }
    if($plandata[0]['plan_type']=="Upgrate - Immediate"){
        $data11 = array( "Plan"=>$plandata[0]['sel_id'], "payment_status"=>"Received", "plan_status"=>"Active" );
        $sellerupdatedata1 = updateQuery(VENDOR,$data11,"dealer_id=".$getpaymentdata[0]['dealer_id']);
        $data222 = array( "plan_status"=>"Expired",);
        $planupdatedata = updateQuery(VENDORPLANSELECTED,$data222,"invoice_id<>'".$getpaymentdata[0]['plan_id']."' and dealer_id=".$getpaymentdata[0]['dealer_id']." and plan_status='Active'");
    }
    $planupdatedata = updateQuery(VENDORPLANSELECTED,$data2,"invoice_id='".$getpaymentdata[0]['plan_id']."'");
    if($sellerdata[0]['plan']==$plandata[0]['sel_id']){
        $data1 = array( "payment_status"=>"Received", "plan_status"=>"Active" );
        $sellerupdatedata = updateQuery(VENDOR,$data1,"dealer_id=".$getpaymentdata[0]['dealer_id']);
        $data5 = array('seller_plan_expired'=>"0");
        //$updateinv=updateQuery(INV,$data5,"dealer_code='".$sellerdata[0]['dealer_key']."'");
    }
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
    $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Payment Successful' and  mail_to= 'Vendor' "); 
    $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Payment Successful' and  mail_to= 'Admin' "); 
    $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME, 'invoiceid' =>$invoiceid, 'plan' => $plandata[0]['plan'], 'plan_type' => $plandata[0]['plan_type'], 'txnid' =>  $txnid, 'amount' => $amount, 'vendornickname' => $sellerdata[0]['nickname'], 'vendorname' => $firstname, 'vendoremail' => $email, 'vendormobileno' => $sellerdata[0]['personalcontactno'],);
    $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
    $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
    $sentmail = mail($email, $subject_vendor, $body_vendor, $headers);      
    $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
    $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
    $sentmail1 = @mail(EMAIL_REG, $subject_admin, $body_admin, $headers); 
    if(SMS_SYSTEM=="ON"){
        $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Payment Successful' and  sms_to = 'Vendor' ");
        $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Payment Successful' and  sms_to = 'Admin' ");
        $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
        $templateId= $vendor_sms[0]['templateId'];
        $sms = sendsms($sellerdata[0]['personalcontactno'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
        $id = (unserialize($sms));
        $msid = $id['data']['0']['id'];
        $status = $id['data']['0']['status'];
        $datasms = array("msg_id" => $msid, "msg_type" => "Vendor Plan Payment SMS To Vendor", "user_id" => "", "user_name" => $firstname, "mobile_no" => $sellerdata[0]['personalcontactno'], "message" => $msg, "date" => date("Y-m-d H:i:s"), "status" => $status);
        $store = insertQuery(SMS,$datasms);
        $arr = explode(",",ADMINCONTACT);
        for($k=0;$k<sizeOf($arr);$k++){
            $tempmob = $arr[$k];
            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
            $templateId= $admin_sms[0]['templateId'];
            $sms1 = sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
            $id1 = (unserialize($sms1));
            $msid1 = $id1['data']['0']['id'];
            $status1 = $id1['data']['0']['status'];
            $datasms1 = array("msg_id" => $msid1, "msg_type" => "Vendor Plan Payment SMS To Admin", "user_name" => "Admin", "mobile_no" => $tempmob, "message" => $msg, "date"=>date("Y-m-d H:i:s"), "status" => $status1,);
            $insert1 = insertQuery(SMS,$datasms1);
        }
    }
    $path = VENDORURL."paymentsuccess.php?book=".base64_encode($getpaymentdata[0]['pay_id']); ?>
    <script>var x = '<?php echo $path; ?>'; window.location.assign(x);</script>
<?php } ?>
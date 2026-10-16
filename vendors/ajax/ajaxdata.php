<?php include("../../includes/configuration.php");
require_once "../../classes/shiprocket.php";
$shipusername=SHIPUSER; $shippasword=SHIPPWD;
$action = $_REQUEST['action'];
if($action == "vendor_insert"){
    $name = $_REQUEST['name1'];
    $email = $_REQUEST['email'];
    $contactNop = $_REQUEST['contactNop'];
    $a = substr($name, 0, 1);
    $b = date("dmy");
    $getuser = selectQuery(VENDOR,"dealer_id","isDel <> '' order by dealer_id DESC LIMIT 1");
    $dealer_id = $getuser[0]['dealer_id']+1 ;
    $key = $a.$b."_".$dealer_id;
    $chkmail = selectQuery(VENDOR,"*","email='".$email."' and isDel='0'");
    if(count($chkmail)){ echo 2;}
    else{
        $data = array('dealer_name' => ucfirst($name), 'email' => $email, 'personalcontactno' => $contactNop, 'insdate' => date("Y-m-d H:i:s"), 'dealer_key' => $key, 'password' => password_encrypt($_REQUEST['pwd']), 'disbursement_cycle_days' => VENDOR_DISBURSMENT_DAYS,);
        $query = insertQuery(VENDOR,$data);
        if($query){  
            $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Registration' and  mail_to= 'Vendor' "); 
            $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Registration' and  mail_to= 'Admin' "); 
            $getvendor = selectQuery(VENDOR,"*","dealer_key='".$key."' and isDel='0'");
            $_SESSION['seller']=$getvendor[0]['dealer_id'];
            $s=base64_encode($_SESSION['seller']);
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .="From:".SITENAME."<".EMAIL_SENDER.">";
            $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'vendorname' => $name, 'vendoremail' => $email, 'vendorcontact' => $contactNop, 'smssitename' => SMSSITENAME,); 
            $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
            $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
            $sentmail = mail($email, $subject_vendor, $body_vendor, $headers);      
            $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
            $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
            $sentmail1 = @mail(EMAIL_REG, $subject_admin, $body_admin, $headers);   
            if(SMS_SYSTEM=="ON"){
                $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Registration' and  sms_to = 'Vendor' ");
                $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Registration' and  sms_to = 'Admin' ");
                $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text']);
                $templateId= $vendor_sms[0]['templateId'];
                $sms = sendsms($contactNop,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                $id = (unserialize($sms));
                $msid = $id['data']['0']['id'];
                $status = $id['data']['0']['status'];
                $datasms = array("msg_id"=>$msid, "msg_type"=>"Vendor Registration SMS To Vendor", "user_name" => $name, "mobile_no"=>$contactNop, "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status );
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
                    $datasms1 = array( "msg_id" => $msid1, "msg_type" => "Vendor Registration SMS To Admin", "user_name" => "Admin", "mobile_no" => $tempmob, "message" => $msg, "date" => date("Y-m-d H:i:s"), "status" => $status1,);
                    $insert1 = insertQuery(SMS,$datasms1);
                }
            }
            echo $s;
        }else{ echo 0; }
    }
}
if($action == "checknicknameavailablity"){
    $username = $_REQUEST['nickname']; $getvendor = selectQuery(VENDOR,"*","nickname='".$username."'");
    if(count($getvendor)){ echo 0; }
    else{ echo 1; }
}
if($action == "filldealerinfo"){
    $id = $_REQUEST['dealerid']; $getkey = selectQuery(VENDOR,"dealer_key","dealer_id=".$id); $key = $getkey[0]['dealer_key']; $name = ucfirst(trim($_REQUEST['name1'])); $email = trim($_REQUEST['email']); $contactNo = trim($_REQUEST['contactNop']); $contactalt = trim($_REQUEST['contactalt']); $nickname = trim($_REQUEST['name2']); $shopname = ucfirst(trim(addslashes($_REQUEST['shopname']))); $country = ucfirst(trim($_REQUEST['country'])); $state = ucfirst(trim($_REQUEST['state'])); $city = ucfirst(trim($_REQUEST['city'])); $Adress = ucfirst(trim($_REQUEST['Adress'])); $locality = ucfirst(trim($_REQUEST['locality'])); $pin = trim($_REQUEST['pin']);
    $officecontactNo = trim($_REQUEST['officecontactNo']); $officemail = trim($_REQUEST['officemail']); $vatno = strtoupper(trim($_REQUEST['vatno'])); $panno = strtoupper(trim($_REQUEST['panno'])); $tanno = strtoupper(trim($_REQUEST['tanno'])); $regno = strtoupper(trim($_REQUEST['regno'])); $bnkname = ucfirst(trim($_REQUEST['bnkname'])); $branchname = ucfirst(trim($_REQUEST['branchname'])); $beneficiary = ucfirst(trim($_REQUEST['beneficiary'])); $acnt_no = trim($_REQUEST['acnt_no']); $acnttype = ucfirst(trim($_REQUEST['acnttype'])); $ifsc = $_REQUEST['ifsc'];
    $plan0 = trim($_REQUEST['plan']);
    if($plan0!=""&&$plan0!="undefined"){
        $planprice = selectQuery(VENDORPLAN,"*","plan_name='".$plan0."'");
        $money = $planprice[0]['price'];
        $plan = $plan0;
    } else{ $money = "0";$plan=""; }
    $data_date1 = date("ymdhis");
    $target = "../../img/vendordocs_images";
    $allowedExts = array("jpg", "jpeg", "png","JPG");
    if($_FILES['pandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["pandoc"]["name"]));
        if ((($_FILES["pandoc"]["type"] == "image/jpeg") || ($_FILES["pandoc"]["type"] == "image/png") || ($_FILES["pandoc"]["type"] == "image/jpg") || ($_FILES["pandoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo1 = $nickname."-pan".$data_date1.".jpg";
            if ($_FILES["pandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["pandoc"]["tmp_name"],$target."/".$photo1); }
        }
    } if($_FILES['tandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["tandoc"]["name"]));
        if((($_FILES["tandoc"]["type"] == "image/jpeg") || ($_FILES["tandoc"]["type"] == "image/png") || ($_FILES["tandoc"]["type"] == "image/jpg") || ($_FILES["tandoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo2 = $nickname."-tan".$data_date1.".jpg";
            if($_FILES["tandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["tandoc"]["tmp_name"],$target."/".$photo2); }
        }
    } if($_FILES['regdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["regdoc"]["name"]));
        if((($_FILES["regdoc"]["type"] == "image/jpeg") || ($_FILES["regdoc"]["type"] == "image/png") || ($_FILES["regdoc"]["type"] == "image/jpg") || ($_FILES["regdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo3 = $nickname."-company-reg".$data_date1.".jpg";
            if($_FILES["regdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["regdoc"]["tmp_name"],$target."/".$photo3); }
        }
    } if($_FILES['vatdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["vatdoc"]["name"]));
        if((($_FILES["vatdoc"]["type"] == "image/jpeg") || ($_FILES["vatdoc"]["type"] == "image/png") || ($_FILES["vatdoc"]["type"] == "image/jpg") || ($_FILES["vatdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo4 = $nickname."-vat".$data_date1.".jpg";
            if($_FILES["vatdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["vatdoc"]["tmp_name"],$target."/".$photo4); }
        }
    } if($_FILES['chequedoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["chequedoc"]["name"]));
        if((($_FILES["chequedoc"]["type"] == "image/jpeg") || ($_FILES["chequedoc"]["type"] == "image/png") || ($_FILES["chequedoc"]["type"] == "image/jpg") || ($_FILES["chequedoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo5 = $nickname."-cheque".$data_date1.".jpg";
            if($_FILES["chequedoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["chequedoc"]["tmp_name"],$target."/".$photo5); }
        }
    } if($_FILES['otherdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["otherdoc"]["name"]));
        if((($_FILES["otherdoc"]["type"] == "image/jpeg") || ($_FILES["otherdoc"]["type"] == "image/png") || ($_FILES["otherdoc"]["type"] == "image/jpg") || ($_FILES["otherdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo7 = $nickname."-photo".$data_date1.".jpg";
            if($_FILES["otherdoc"]["error"] > 0){ }
            else { move_uploaded_file($_FILES["otherdoc"]["tmp_name"],$target."/".$photo7); }
        }
    }
    if($name==""||$email==""||$contactNo==""||$shopname==""||$country==""||$state==""||$city==""||$pin==""||$officemail==""||$officecontactNo==""||$panno=="" ||$bnkname==""||$branchname==""||$beneficiary==""||$acnttype==""||$acnt_no==""||$ifsc==""){ echo 0; }
    else{
        $data = array('dealer_name' => $name, 'email'  => $email, 'personalcontactno' =>$contactNo, 'extracontactno' =>$contactalt, 'shopname' => $shopname, 'country' => $country, 'state' => $state, 'city' => $city, 'officeadress'  =>addslashes($Adress), 'locality' => $locality, 'pin'  => $pin, 'communicationemail' => $officemail, 'officecontactno' => $officecontactNo, 'tanno' => $tanno, 'panno' => $panno , 'regno' => $regno, 'vatno'=> $vatno, 'bnkname' => $bnkname, 'branchname' => $branchname, 'beneficiary' => $beneficiary, 'acnttype' => $acnttype, 'acntno' =>$acnt_no, 'IFSCcode' =>$ifsc, 'nickname'=>$nickname, 'isComplete'=>'1', 'completeon' => date("Y-m-d H:i:s"),);
        if($photo1!=""){ $data['pandoc']=$photo1; }
        if($photo2!=""){ $data['tandoc']=$photo2; }
        if($photo3!=""){ $data['regdoc']=$photo3; }
        if($photo4!=""){ $data['vatdoc']=$photo4; }
        if($photo5!=""){ $data['chequedoc']=$photo5; }
        if($photo7!=""){ $data['otherdoc']=$photo7; }
        $query = updateQuery(VENDOR,$data,"dealer_id=".$id);
        if($query){
             $ship = new shiprocket($shipusername,$shippasword);
            $token = $ship->authenticate();
            $pickloc = "v".$id;
            $pickup = array("pickup_location" => "v".$id,"name"=>preg_replace("/[^a-zA-Z ]/", " ", $name) ,
                "email" => $officemail,"phone" => $officecontactNo,"address" => $shopname.",".$Adress,"address_2" => $locality,"city"=>$city,"state"=>$state,"country"=>$country,"pin_code"=> $pin);

           $ship->addPickups($token,$pickup);
            $dataship = array("activated_pickup"=>$pickloc);
            updateQuery(VENDOR,$dataship,"dealer_id=".$id);
            $ord = selectQuery(VENDORPLANSELECTED,"*","invoice_id<>'' order by sel_id DESC limit 1");
            if(count($ord)==0){ $orderid=VENDOR_PLAN_INVSTART."-1"; }
            else{
                $get_number =preg_replace('/\D/', '', $ord[0]['invoice_id']);
                $intformat = number_format($get_number)+1;

                $orderid = VENDOR_PLAN_INVSTART."-".$intformat;

            }
            $data1 = array('insert_date'=>date("Y-m-d"), 'invoice_id'=>$orderid, 'dealer_id'=>$id, 'price'=>$money, 'plan_type'=>"New", 'payment_status'=>"Pending", 'plan_status'=>"Initialize");
            if($plan!=""&&$plan!="undefined"){ $data1['plan'] = $plan; }
            else{ $data1['plan']= ""; }
            $query1 = insertQuery(VENDORPLANSELECTED,$data1);
            $data2 = array('plan'=>$query1, 'plan_status'=>"Initialize", 'payment_status'=>"Pending",);
            $query2 = updateQuery(VENDOR,$data2,"dealer_id=".$id);
            $mail1 = $email;
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .="From:".SITENAME."<".EMAIL_SENDER.">";
            $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'vendorname' => $name, 'vendoremail' => $email, 'vendorcontact' => $contactNop,  'smssitename' => SMSSITENAME,); 
            $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Profile Complete' and  mail_to= 'Vendor' "); 
            $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Profile Complete' and  mail_to= 'Admin' "); 
            $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
            $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
            $sentmail = mail($email, $subject_vendor, $body_vendor, $headers);      
            $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
            $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
            $sentmail1 = @mail(EMAIL_REG, $subject_admin, $body_admin, $headers); 
            if(SMS_SYSTEM=="ON"){
                $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Profile Complete' and  sms_to = 'Vendor' ");
                $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Vendor Profile Complete' and  sms_to = 'Admin' ");
                $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
                $templateId = $vendor_sms[0]['templateId'];
                $sms = sendsms($contactNo,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                $id = (unserialize($sms));
                $msid = $id['data']['0']['id'];
                $status = $id['data']['0']['status'];
                $datasms = array("msg_id"=>$msid, "msg_type"=>"Vendor Profile Completion SMS To Vendor", "user_id" => "", "user_name" => $name, "mobile_no"=>$contactNo, "message" => $msg, "date" => date("Y-m-d H:i:s"), "status"=>$status);
                $store = insertQuery(SMS,$datasms);
                $arr = explode(",",ADMINCONTACT);
                for($k=0;$k<sizeOf($arr);$k++){
                    $tempmob = $arr[$k];
                    $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                    $templateId = $admin_sms[0]['templateId'];
                    $sms1 = sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                    $id1 = (unserialize($sms1));
                    $msid1 = $id1['data']['0']['id'];
                    $status1 = $id1['data']['0']['status'];
                    $datasms1 = array("msg_id"=>$msid1,"msg_type"=>"Vendor Profile Completion SMS To Admin","user_name"=>"Admin","mobile_no"=>$tempmob,"message"=>$msg,"date"=>date("Y-m-d H:i:s"),"status"=>$status1,);
                    $insert1 = insertQuery(SMS,$datasms1);
                }
           } echo "planpayment.php?vendor=".base64_encode($_REQUEST['dealerid'])."&plan=".base64_encode($query1);
        } else{ echo 0; }
    }
}
if($action == "Updateplan"){
    $seller = $_REQUEST['dealerid'];
    $planid = $_REQUEST['planid'];
    $requesttype = $_REQUEST['requesttype'];
    $plan = $_REQUEST['plan'];
    if($plan!=""&&$plan!="undefined"){
        $planprice = selectQuery(VENDORPLAN,"*","plan_name='".$plan."'");
        $money = $planprice[0]['price'];
    }
    else{ $money = "0"; }
    $data1 = array('dealer_id'=>$seller, 'price'=>$money, 'payment_status'=>"Pending",);
    if($plan!=""&&$plan!="undefined"){ $data1['plan'] = $plan; }
    else{ $data1['plan'] = ""; }
    if($planid==""||$requesttype=="Renew"){
    $ord = selectQuery(VENDORPLANSELECTED,"*","invoice_id<>'' order by sel_id DESC limit 1");
    if(count($ord)==0){ $orderid=VENDOR_PLAN_INVSTART."-1"; }
            else{
                $get_number =preg_replace('/\D/', '', $ord[0]['invoice_id']);
                $intformat = number_format($get_number)+1;

                $orderid = VENDOR_PLAN_INVSTART."-".$intformat;

            }
    $checkback = selectQuery(VENDORPLANSELECTED,"*","dealer_id=".$seller." order by sel_id DESC limit 1");
    if(count($checkback)){ $data1['plan_type']=$requesttype; }
    else{ $data1['plan_type']="New"; }
        $data1['invoice_id']=$orderid;
        $data1['insert_date']=date("Y-m-d");
        $query1=insertQuery(VENDORPLANSELECTED,$data1);
        $planidfromdata=$query1;
    } else{
        $planidfromdata = $planid;
        $query1 = updateQuery(VENDORPLANSELECTED,$data1,"sel_id=".$planid);
    }
    if($planidfromdata!=""){
        if($requesttype=="New"||$requesttype=="Upgrade - Immediate"||$requesttype==""){
            $data2 = array('plan'=>$planidfromdata, 'payment_status'=>"Pending",);
        }
        $query2 = updateQuery(VENDOR,$data2,"dealer_id=".$seller);
        echo "confirm_details.php?vendor=".base64_encode($seller)."&plan=".base64_encode($planidfromdata);
    }else{ echo 0; }
}
if($action == "offline_pay"){
    $planid = base64_decode($_REQUEST['planid']);
    $plandata = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$planid."'");
    $sellerdata = selectQuery(VENDOR,"*","dealer_id=".$plandata[0]['dealer_id']);
    $data = array("plan_id"=>$planid, "price"=>$plandata[0]['price'], "dealer_id"=>$plandata[0]['dealer_id'], "payment_date"=>date("Y-m-d H:i:s"), "payment_status"=> "Received",);
    $insert = insertQuery(VENDORPAYMENT,$data);
    if($insert){

        $datan = array('plan_status' => "Active", 'payment_status' => "Received");
        $queryn = updateQuery(VENDOR, $datan, "dealer_id=".$plandata[0]['dealer_id']);
        $plandata = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$planid."'");
        $planduration = selectQuery(VENDORPLAN,"*","plan_name= '".$plandata[0]['plan']."'");
        $current = date("Y-m-d");
        $dateOneYearAdded = strtotime(date("Y-m-d", strtotime($current)) . " +".$planduration[0]['plan_duration']." days");
        $oneyear = date('Y-m-d', $dateOneYearAdded);
        $yesterday1 = strtotime(date("Y-m-d", strtotime($oneyear)) . " -1 days");
        $yesterday = date('Y-m-d', $yesterday1);
        $data2 = array("payment_status"=>"Received", "plan_from"=>$current, "plan_to"=>$yesterday,
         "payment_date"=>date("Y-m-d H:i"), "transaction_id"=>"", 'paymentgateway_status'=>"", "plan_status"=>"Active");
        $planupdatedata = updateQuery(VENDORPLANSELECTED,$data2,"invoice_id='".$planid."'");
        echo $insert;
    } else{ echo 0; }
}
if($action == "onlinepay"){
    $planid = base64_decode($_REQUEST['planid']);
    $txnid = $_REQUEST['txnid'];
    $plandata = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$planid."'");
    $sellerdata = selectQuery(VENDOR,"*","dealer_id=".$plandata[0]['dealer_id']);
    $data=array("transaction_id"=>$txnid, "plan_id"=>$planid, "price"=>$plandata[0]['price'], "dealer_id"=>$plandata[0]['dealer_id'], "payment_date"=>date("Y-m-d H:i:s"), "payment_status"=>"Initialize");
    $insert = insertQuery(VENDORPAYMENT,$data);
    if($insert){ echo $insert; }
    else{ echo 0; }
}
if($action == "checknicknameavailablityupdate"){
    $username = $_REQUEST['nickname'];
    $dealerid = $_REQUEST['dealerid'];
    $getseller = selectQuery(VENDOR,"*","nickname='".$username."' and dealer_id<>".$dealerid);
    if(count($getseller)){ echo "0"; }
    else{ echo "1"; }
}
/*
if($action == "filldealerinfo_update"){
    $id = $_REQUEST['dealerid']; $getkey = selectQuery(VENDOR,"*","dealer_id=".$id); $name = ucfirst(trim($_REQUEST['name1'])); $email = trim($_REQUEST['email']); $contactNo = trim($_REQUEST['contactNop']);
    $contactalt = trim($_REQUEST['contactalt']); $nickname = trim($_REQUEST['name2']); $shopname = ucfirst(trim($_REQUEST['shopname'])); $country = ucfirst(trim($_REQUEST['country'])); $state = ucfirst(trim($_REQUEST['state'])); $city = ucfirst(trim($_REQUEST['city'])); $Adress = ucfirst(trim($_REQUEST['Adress'])); $locality = ucfirst(trim($_REQUEST['locality'])); $pin = trim($_REQUEST['pin']); $officecontactNo = trim($_REQUEST['officecontactNo']); $officemail = trim($_REQUEST['officemail']); $vatno = strtoupper(trim($_REQUEST['vatno'])); $panno = strtoupper(trim($_REQUEST['panno'])); $tanno = strtoupper(trim($_REQUEST['tanno'])); $regno = strtoupper(trim($_REQUEST['regno'])); $bnkname = ucfirst(trim($_REQUEST['bnkname'])); $branchname = ucfirst(trim($_REQUEST['branchname'])); $beneficiary = ucfirst(trim($_REQUEST['beneficiary'])); $acnt_no = trim($_REQUEST['acnt_no']); $acnttype = ucfirst(trim($_REQUEST['acnttype'])); $ifsc = $_REQUEST['ifsc']; $view = "Pending"; $data_date1 = date("ymdhis");
    $target = "../../img/vendordocs_images";
    $allowedExts = array("jpg", "jpeg", "png","JPG");
    if($_FILES['pandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["pandoc"]["name"]));
        if((($_FILES["pandoc"]["type"] == "image/jpeg") || ($_FILES["pandoc"]["type"] == "image/png") || ($_FILES["pandoc"]["type"] == "image/jpg") || ($_FILES["pandoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo1 = $nickname."-pan".$data_date1.".jpg";
            if($_FILES["pandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["pandoc"]["tmp_name"],$target."/".$photo1); }
        }
    }
    if($_FILES['tandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["tandoc"]["name"]));
        if((($_FILES["tandoc"]["type"] == "image/jpeg") || ($_FILES["tandoc"]["type"] == "image/png") || ($_FILES["tandoc"]["type"] == "image/jpg") || ($_FILES["tandoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo2 = $nickname."-tan".$data_date1.".jpg";
            if($_FILES["tandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["tandoc"]["tmp_name"],$target."/".$photo2); }
        }
    }
    if($_FILES['regdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["regdoc"]["name"]));
        if((($_FILES["regdoc"]["type"] == "image/jpeg") || ($_FILES["regdoc"]["type"] == "image/png") || ($_FILES["regdoc"]["type"] == "image/jpg") || ($_FILES["regdoc"]["type"] == "image/JPG"))
        && in_array($extension, $allowedExts)){
            $photo3 = $nickname."-company-reg".$data_date1.".jpg";
            if($_FILES["regdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["regdoc"]["tmp_name"],$target."/".$photo3); }
        }
    }
    if($_FILES['vatdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["vatdoc"]["name"]));
        if((($_FILES["vatdoc"]["type"] == "image/jpeg") || ($_FILES["vatdoc"]["type"] == "image/png") || ($_FILES["vatdoc"]["type"] == "image/jpg") || ($_FILES["vatdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo4 = $nickname."-vat".$data_date1.".jpg";
            if($_FILES["vatdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["vatdoc"]["tmp_name"],$target."/".$photo4); }
        }
    }
    if($_FILES['chequedoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["chequedoc"]["name"]));
        if((($_FILES["chequedoc"]["type"] == "image/jpeg") || ($_FILES["chequedoc"]["type"] == "image/png") || ($_FILES["chequedoc"]["type"] == "image/jpg") || ($_FILES["chequedoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo5 = $nickname."-cheque".$data_date1.".jpg";
            if($_FILES["chequedoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["chequedoc"]["tmp_name"],$target."/".$photo5); }
        }
    }
    if($_FILES['otherdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["otherdoc"]["name"]));
        if((($_FILES["otherdoc"]["type"] == "image/jpeg") || ($_FILES["otherdoc"]["type"] == "image/png") || ($_FILES["otherdoc"]["type"] == "image/jpg") || ($_FILES["otherdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo7 = $nickname."-photo".$data_date1.".jpg";
            if($_FILES["otherdoc"]["error"] > 0){ }
            else { move_uploaded_file($_FILES["otherdoc"]["tmp_name"],$target."/".$photo7); }
        }
    }
} */
if($action == "filldealerinfo_update"){
    $id = $_REQUEST['dealerid']; $getkey = selectQuery(VENDOR,"*","dealer_id=".$id); $name = ucfirst(trim($_REQUEST['name1'])); $email = trim($_REQUEST['email']); $contactNo = trim($_REQUEST['contactNop']); $contactalt = trim($_REQUEST['contactalt']); $nickname = trim($_REQUEST['name2']); $shopname = addslashes (ucfirst(trim($_REQUEST['shopname']))); $country = ucfirst(trim($_REQUEST['country'])); $state = ucfirst(trim($_REQUEST['state'])); $city = ucfirst(trim($_REQUEST['city'])); $Adress = ucfirst(trim($_REQUEST['Adress'])); $locality = ucfirst(trim($_REQUEST['locality'])); $pin = trim($_REQUEST['pin']);
    $officecontactNo = trim($_REQUEST['officecontactNo']); $officemail = trim($_REQUEST['officemail']); $vatno = strtoupper(trim($_REQUEST['vatno'])); $panno = strtoupper(trim($_REQUEST['panno'])); $tanno = strtoupper(trim($_REQUEST['tanno'])); $regno = strtoupper(trim($_REQUEST['regno'])); $bnkname = ucfirst(trim($_REQUEST['bnkname'])); $branchname = ucfirst(trim($_REQUEST['branchname'])); $beneficiary = ucfirst(trim($_REQUEST['beneficiary'])); $acnt_no = trim($_REQUEST['acnt_no']); $acnttype = ucfirst(trim($_REQUEST['acnttype'])); $ifsc = $_REQUEST['ifsc']; $data_date = date("d/m/Y H:i:s"); $lastupdate = array(); $view = "Pending"; $tabledata = "";
    $data_date1 = date("ymdhis");
    if($getkey[0]['dealer_name']!=$name){
        array_push($lastupdate,"Name");
        $tabledata = $tabledata."<tr><td>Name</td><td>".$name."</td></tr>";
    } if($getkey[0]['email']!=$email){
        array_push($lastupdate,"Email");
        $tabledata = $tabledata."<tr><td>Email</td><td>".$email."</td></tr>";
    } if($getkey[0]['personalcontactno']!=$contactNo){
        array_push($lastupdate,"Primary Mobile No");
        $tabledata = $tabledata."<tr><td>Primary Mobile No.</td><td>".$contactNo."</td></tr>";
    } if($getkey[0]['extracontactno']!=$contactalt){
        array_push($lastupdate,"Alternative Mobile No");
        $tabledata = $tabledata."<tr><td>Alternative Mobile No.</td><td>".$contactNo."</td></tr>";
    } if($getkey[0]['shopname']!=$shopname){
        array_push($lastupdate,"Shop Name");
        $tabledata = $tabledata."<tr><td>Shop Name</td><td>".$shopname."</td></tr>";
    } if($getkey[0]['officeadress']!=$Adress||$getkey[0]['city']!=$city||$getkey[0]['state']!=$state||$getkey[0]['country']!=$contry){
        array_push($lastupdate," office Address");
        $tabledata=$tabledata."<tr><td>Office Address</td><td>".$Adress.",".$city.",".$state.",".$country."</td></tr>";
    } if($getkey[0]['pin']!=$pin){
        array_push($lastupdate,"Pincode");
        $tabledata = $tabledata."<tr><td>Pincode</td><td>".$pin."</td></tr>";
    } if($getkey[0]['communicationemail']!=$officemail){
        array_push($lastupdate,"Communication Email");
        $tabledata = $tabledata."<tr><td>Communication Email</td><td>".$officemail."</td></tr>";
    } if($getkey[0]['officecontactno']!=$officecontactNo){
        array_push($lastupdate,"Office Contact No");
        $tabledata = $tabledata."<tr><td>Office Contact No </td><td>".$officecontactNo."</td></tr>";
    } if($getkey[0]['tanno']!=$tanno){
        array_push($lastupdate,"TAN No");
        $tabledata = $tabledata."<tr> <td>TAN No</td><td>".$tanno."</td></tr>";
    } if($getkey[0]['panno']!=$panno){
        array_push($lastupdate,"PAN No");
        $tabledata = $tabledata."<tr>  <td>PAN No</td><td>".$panno."</td></tr>";
    } if($getkey[0]['regno']!=$regno){
        array_push($lastupdate,"Company Reg. No");
        $tabledata = $tabledata."<tr> <td>Company Reg. No</td><td>".$regno."</td></tr>";
    } if($getkey[0]['vatno']!=$vatno){
        array_push($lastupdate,$vatno);
          $tabledata = $tabledata."<tr><td>VAT/TIN/CST No</td><td>".$vatno."</td></tr>";
    } if($getkey[0]['bnkname']!=$bnkname){
        array_push($lastupdate,"Bank Name");
         $tabledata = $tabledata."<tr><td>Bank Name</td><td>".$bnkname."</td></tr>";
    } if($getkey[0]['branchname']!=$branchname){
        array_push($lastupdate,"Branch Name");
        $tabledata = $tabledata."<tr><td>Branch Name</td><td>".$branchname."</td></tr>";
    } if($getkey[0]['beneficiary']!=$beneficiary){
        array_push($lastupdate,"Beneficiary Name");
        $tabledata = $tabledata."<tr><td>Beneficiary Name</td><td>".$beneficiary."</td></tr>";
    } if($getkey[0]['acnttype']!=$acnttype){
        array_push($lastupdate,"Account Type");
        $tabledata = $tabledata."<tr><td>Account Type</td><td>".$acnttype."</td></tr>";
    } if($getkey[0]['acntno']!=$acnt_no){
        array_push($lastupdate,"Account No");
        $tabledata = $tabledata."<tr><td>Account No.</td><td>".$acnt_no."</td></tr>";
    } if($getkey[0]['IFSCcode']!=$ifsc){
        array_push($lastupdate,"IFSC Code");
        $tabledata = $tabledata."<tr><td>IFSC Code.</td><td>".$ifsc."</td></tr>";
    }
    $target = "../../img/vendordocs_images";
    $allowedExts = array("jpg", "jpeg", "png","JPG");
    if($_FILES['otherdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["otherdoc"]["name"]));
        if((($_FILES["otherdoc"]["type"] == "image/jpeg") || ($_FILES["otherdoc"]["type"] == "image/png") || ($_FILES["otherdoc"]["type"] == "image/jpg") || ($_FILES["otherdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo7 = $getkey[0]['nickname']."-otherdoc".$data_date1.".jpg";
            if($_FILES["otherdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["otherdoc"]["tmp_name"],$target."/".$photo7); }
            array_push($lastupdate,"Other Doc");
            $tabledata = $tabledata."<tr><td>Other Doc</td><td><img src='".SITEURL."/img/sellerdocs_images/".$photo6."' style='width:150px'></td></tr>";
        }
    }
    if($_FILES['pandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["pandoc"]["name"]));
        if((($_FILES["pandoc"]["type"] == "image/jpeg") || ($_FILES["pandoc"]["type"] == "image/png") || ($_FILES["pandoc"]["type"] == "image/jpg") || ($_FILES["pandoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo1 = $getkey[0]['nickname']."-pan".$data_date1.".jpg";
            if($_FILES["pandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["pandoc"]["tmp_name"],$target."/".$photo1); }
        }
        array_push($lastupdate,"PAN doc");
        $tabledata = $tabledata."<tr><td>PAN Doc.</td><td><img src='".SITEURL."/img/sellerdocs_images/".$photo1."' style='width:150px'></td></tr>";
    }
    if($_FILES['regdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["regdoc"]["name"]));
        if((($_FILES["regdoc"]["type"] == "image/jpeg") || ($_FILES["regdoc"]["type"] == "image/png") || ($_FILES["regdoc"]["type"] == "image/jpg") || ($_FILES["regdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo3 = $getkey[0]['nickname']."-company-reg".$data_date1.".jpg";
            if($_FILES["regdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["regdoc"]["tmp_name"],$target."/".$photo3); }
        }
        array_push($lastupdate,"Company Reg. doc");
        $tabledata = $tabledata."<tr><td>Company Reg Doc.</td><td><img src='".SITEURL."/img/sellerdocs_images/".$photo3."' style='width:150px'></td></tr>";
    }
    if($_FILES['tandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["tandoc"]["name"]));
        if((($_FILES["tandoc"]["type"] == "image/jpeg") || ($_FILES["tandoc"]["type"] == "image/png") || ($_FILES["tandoc"]["type"] == "image/jpg") || ($_FILES["tandoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo2 = $getkey[0]['nickname']."-tan".$data_date1.".jpg";
            if($_FILES["tandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["tandoc"]["tmp_name"],$target."/".$photo2); }
        }
        array_push($lastupdate,"TAN doc");
        $tabledata=$tabledata."<tr><td>TAN Doc.</td><td><img src='".SITEURL."/img/sellerdocs_images/".$photo2."' style='width:150px'></td></tr>";
    }
    if($_FILES['vatdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["vatdoc"]["name"]));
        if((($_FILES["vatdoc"]["type"] == "image/jpeg") || ($_FILES["vatdoc"]["type"] == "image/png") || ($_FILES["vatdoc"]["type"] == "image/jpg") || ($_FILES["vatdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo4 = $getkey[0]['nickname']."-vat".$data_date1.".jpg";
            if($_FILES["vatdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["vatdoc"]["tmp_name"],$target."/".$photo4); }
        }
        array_push($lastupdate,"VAT doc");
        $tabledata = $tabledata."<tr><td>VAT Doc.</td><td><img src='".SITEURL."/img/sellerdocs_images/".$photo4."' style='width:150px'></td></tr>";
    }
    if($_FILES['chequedoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["chequedoc"]["name"]));
        if((($_FILES["chequedoc"]["type"] == "image/jpeg") || ($_FILES["chequedoc"]["type"] == "image/png") || ($_FILES["chequedoc"]["type"] == "image/jpg") || ($_FILES["chequedoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo5 = $getkey[0]['nickname']."-cheque".$data_date1.".jpg";
            if($_FILES["chequedoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["chequedoc"]["tmp_name"],$target."/".$photo5); }
        }
        array_push($lastupdate,"cancelled cheque");
        $tabledata = $tabledata."<tr><td>Cancelled Cheque</td><td><img src='".SITEURL."/img/sellerdocs_images/".$photo5."' style='width:150px'></td></tr>";
    }
    $updated = implode(",",$lastupdate);
    $data = array('dealer_name' => $name, 'email' => $email, 'personalcontactno' => $contactNo, 'extracontactno' =>$contactalt, 'shopname' => $shopname, 'country' => $country, 'state' => $state, 'city' => $city, 'officeadress' => addslashes($Adress), 'locality'=>$locality, 'pin' => $pin, 'communicationemail' => $officemail, 'officecontactno' => $officecontactNo, 'tanno'=> $tanno, 'panno'=>$panno , 'regno'=> $regno, 'vatno'=> $vatno, 'bnkname' =>  $bnkname, 'branchname' => $branchname, 'beneficiary'=> $beneficiary, 'acnttype'=> $acnttype, 'acntno' =>$acnt_no, 'IFSCcode' =>$ifsc, 'nickname'=>$nickname, 'lastupdated'=>$updated, 'updatedon'=>date('Y-m-d H:i:s'), 'updatesviewbyadmin'=>$view, 'isKYCUploaded'=>1,);
    if($photo7!=""){ $data['otherdoc']=$photo7; }
    if($photo1!=""){ $data['pandoc']=$photo1; }
    if($photo3!=""){ $data['regdoc']=$photo3; }
    if($photo2!=""){ $data['tandoc']=$photo2; }
    if($photo4!=""){ $data['vatdoc']=$photo4; }
    if($photo5!=""){ $data['chequedoc']=$photo5; }
    $query = updateQuery(VENDOR,$data,"dealer_id=".$id);
    if($query){ echo 1; }
    else{ echo 0; }
}
if($action == "update_password"){
    $uid = $_REQUEST['uid'] ;
    $oldpwd = $_REQUEST['oldpwd'] ;
    $password = $_REQUEST['password'];
    $encrypt = password_encrypt($password);
    $password1 = $_REQUEST['password1'];
    $encode = password_encrypt($oldpwd);
    $userdata = selectQuery(VENDOR,"*","dealer_id=".$uid);
    $dbpass = $userdata[0]['password'];
    if($encode == $dbpass){
        $data = array( 'password'=> $encrypt, );
        $update = updateQuery(VENDOR,$data,"dealer_id=".$uid);
        if($update)
        echo "2";
        else
        echo "3";
    } else{ echo "0"; }
}

if($action == "forgetpassword"){
    $email1 = $_REQUEST['email1'];
    $vendor = selectQuery(VENDOR,"dealer_id","email='".$email1."' ");
    if(count($vendor)){
        $vendorid = $vendor[0]['dealer_id'];$enocodid =base64_encode( $vendorid);
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
        $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Reset Password' and  mail_to= 'Vendor' "); 
        $replacement_array = array('siteurl' => SITEURL,  'vendorurl' => VENDORURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME, 'vendorid'=> base64_encode($vendorid),);
        $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
        $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
        $sentmail = mail( $email1, $subject_vendor, $body_vendor, $headers);
        if($sentmail){ echo "1";}
        else { echo "0"; }
    } else{ echo "0"; }
}
if($action == "recover_password"){
    $vendorid = $_REQUEST['vendorid'] ;
    $password = $_REQUEST['password'];
    $encrypt = password_encrypt($password);
    $password1 = $_REQUEST['password1'];
    $data = array('password'=> $encrypt,);
    $update = updateQuery(VENDOR,$data,"dealer_id=".$vendorid);
    if($update)
    echo "2";
    else
    echo "3";
} ?>
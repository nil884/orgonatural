<?php include("../../includes/configuration.php");
if($action == "Delete_vednor"){
    $id = $_POST['vendor_id'];
    $que = deleteQuery(VENDOR,'dealer_id='.$id);
    if($que){ 
        include("../../classes/product.php");
        $prod = new Product();
        $getproduct = selectQuery(PRODINFO,"id","vendor =".$id." ");
        for($i=0;$i<count($getproduct);$i++){ $prod->deleteProd($getproduct[$i]['id']); }
        echo 1;
    }else{ echo 0; }
}

if($action == "approve_vendor"){
    $pid = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data2 = array('isApproved'=>$setval,'isKYCUploaded'=>1);
    if($setval==1){ $data2['approveon']=date('Y-m-d H:i:s'); $data2['isActive']='1'; }
    $que = updateQuery(VENDOR,$data2,"dealer_id=".$pid);
    if($que){
        if($setval==1){       
            $getseller = selectQuery(VENDOR,"*","dealer_id=".$pid);
            $email = $getseller[0]['email'];
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From: ".SITENAME."<".EMAIL_SENDER.">";
            $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Approved' and  mail_to= 'Vendor' "); 
            $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text","type='Vendor Approved' and  sms_to = 'Vendor' ");
            $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'vendorname' => $getseller[0]['dealer_name'], 'smssitename' => SMSSITENAME,); 
            $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
            $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
            $sentmail = mail($email, $subject_vendor, $body_vendor, $headers); 
            
            
            $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Approved' and  mail_to= 'Admin' "); 
            $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
            $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
            $sentmail1 = mail(EMAIL_REG, $subject_admin, $body_admin, $headers);
                    

            if(SMS_SYSTEM=="ON"){  
                $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text","type='Vendor Approved' and  sms_to = 'Vendor' ");
                $admin_sms = selectQuery(SMSTEMPLATE,"sms_text","type='Vendor Approved' and  sms_to = 'Admin' ");
                $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
                $sms = sendsms($getseller[0]['personalcontactno'],$msg,WORKINGKEY,SMS_SENDER);
                $id=(unserialize($sms));
                $msid=$id['data']['0']['id'];
                $status=$id['data']['0']['status'];
                $datasms=array("msg_id"=>$msid, "msg_type"=>"Vendor Approved SMS To Vendor", "user_id"=> "", "user_name"=> $getseller[0]['dealer_name'], "mobile_no"=>$getseller[0]['personalcontactno'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
                $store = insertQuery(SMS,$datasms);
                $arr = explode(",",ADMINCONTACT);
                for($k=0;$k<sizeOf($arr);$k++){  
                    $tempmob = $arr[$k];
                    $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                    $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER);
                    $id1=(unserialize($sms1));
                    $msid1=$id1['data']['0']['id'];
                    $status1=$id1['data']['0']['status'];
                    $datasms1=array("msg_id"=>$msid1, "msg_type"=>"Vendor Approved SMS To Admin", "user_name"=>"Admin", "mobile_no"=>$tempmob, "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status1,);
                    $insert1 = insertQuery(SMS,$datasms1);
                }
            }
            echo 1;
        } else{ echo 0; }
    }
}
if($action == "Vendor_status"){
    $pid = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data2 = array('isActive'=>$setval);
    $que = updateQuery(VENDOR,$data2,"dealer_id=".$pid);
    if($que){ echo 1;} else{echo 0;}
}
if($action == "Vendor_auto_approve_product"){
    $pid = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data2 = array('auto_approve_product'=>$setval);
    $que = updateQuery(VENDOR,$data2,"dealer_id=".$pid);
    if($que){ echo 1;} else{echo 0;}
}
if($action == "priority_vendor"){
    $str = $_REQUEST['str'];
    $vendor_ids = explode(",", $str);
    if($str != ""){
        for($i = 0; $i < count($vendor_ids); $i++){
            $data = array('priority' => $i + 1);
            $update = updateQuery(VENDOR, $data, "dealer_id=" .$vendor_ids[$i]);
            if($update){ echo 1; } else{ echo 0; }
        }
    } 
}      
if($action == "filldealerinfo_update"){
    $id = $_REQUEST['dealerid']; $getkey = selectQuery(VENDOR,"*","dealer_id=".$id); $name = ucfirst(trim($_REQUEST['name1'])); $email = trim($_REQUEST['email']); $contactNo = trim($_REQUEST['contactNop']);
    $contactalt = trim($_REQUEST['contactalt']); $nickname = trim($_REQUEST['name2']); $shopname = addslashes (ucfirst(trim($_REQUEST['shopname']))); $country = ucfirst(trim($_REQUEST['country'])); $state = ucfirst(trim($_REQUEST['state'])); $city = ucfirst(trim($_REQUEST['city'])); $Adress = ucfirst(trim($_REQUEST['Adress'])); $locality = ucfirst(trim($_REQUEST['locality'])); $pin = trim($_REQUEST['pin']); $officecontactNo = trim($_REQUEST['officecontactNo']); $officemail = trim($_REQUEST['officemail']); $vatno = strtoupper(trim($_REQUEST['vatno'])); $panno = strtoupper(trim($_REQUEST['panno'])); $tanno = strtoupper(trim($_REQUEST['tanno'])); $regno = strtoupper(trim($_REQUEST['regno']));
    $bnkname = ucfirst(trim($_REQUEST['bnkname'])); $branchname = ucfirst(trim($_REQUEST['branchname']));
    $beneficiary = ucfirst(trim($_REQUEST['beneficiary'])); $acnt_no = trim($_REQUEST['acnt_no']);   $autopay = $_REQUEST['autopay']; $acnttype = ucfirst(trim($_REQUEST['acnttype'])); $ifsc = $_REQUEST['ifsc']; $data_date1 = date("ymdhis"); $target="../../img/vendordocs_images"; $allowedExts = array("jpg", "jpeg", "png","JPG");
    if($_FILES['otherdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["otherdoc"]["name"]));
        if((($_FILES["otherdoc"]["type"] == "image/jpeg") || ($_FILES["otherdoc"]["type"] == "image/png") || ($_FILES["otherdoc"]["type"] == "image/jpg") || ($_FILES["otherdoc"]["type"] == "image/JPG"))
        && in_array($extension, $allowedExts)){
            $photo7 = $getkey[0]['nickname']."-otherdoc".$data_date1.".jpg";
            if($_FILES["otherdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["otherdoc"]["tmp_name"],$target."/".$photo7); }
        }
    }
    if($_FILES['pandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["pandoc"]["name"]));
        if ((($_FILES["pandoc"]["type"] == "image/jpeg") || ($_FILES["pandoc"]["type"] == "image/png") || ($_FILES["pandoc"]["type"] == "image/jpg") || ($_FILES["pandoc"]["type"] == "image/JPG"))
        && in_array($extension, $allowedExts)){
            $photo1 = $getkey[0]['nickname']."-pan".$data_date1.".jpg";
            if ($_FILES["pandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["pandoc"]["tmp_name"],$target."/".$photo1); }
        }       
    }
    if($_FILES['regdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["regdoc"]["name"]));
        if((($_FILES["regdoc"]["type"] == "image/jpeg") || ($_FILES["regdoc"]["type"] == "image/png") || ($_FILES["regdoc"]["type"] == "image/jpg") || ($_FILES["regdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo3 = $getkey[0]['nickname']."-company-reg".$data_date1.".jpg";
            if ($_FILES["regdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["regdoc"]["tmp_name"],$target."/".$photo3); }
        }
    }
    if($_FILES['tandoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["tandoc"]["name"]));
        if((($_FILES["tandoc"]["type"] == "image/jpeg") || ($_FILES["tandoc"]["type"] == "image/png") || ($_FILES["tandoc"]["type"] == "image/jpg") || ($_FILES["tandoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo2 = $getkey[0]['nickname']."-tan".$data_date1.".jpg";
            if ($_FILES["tandoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["tandoc"]["tmp_name"],$target."/".$photo2); }
        }
    }
    if($_FILES['vatdoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["vatdoc"]["name"]));
        if ((($_FILES["vatdoc"]["type"] == "image/jpeg") || ($_FILES["vatdoc"]["type"] == "image/png") || ($_FILES["vatdoc"]["type"] == "image/jpg") || ($_FILES["vatdoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo4 = $getkey[0]['nickname']."-vat".$data_date1.".jpg";
            if($_FILES["vatdoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["vatdoc"]["tmp_name"],$target."/".$photo4); }
        }           
    }
    if($_FILES['chequedoc']['tmp_name']!=""){
        $extension = end(explode(".", $_FILES["chequedoc"]["name"]));
        if((($_FILES["chequedoc"]["type"] == "image/jpeg") || ($_FILES["chequedoc"]["type"] == "image/png") || ($_FILES["chequedoc"]["type"] == "image/jpg") || ($_FILES["chequedoc"]["type"] == "image/JPG")) && in_array($extension, $allowedExts)){
            $photo5 = $getkey[0]['nickname']."-cheque".$data_date1.".jpg";
            if ($_FILES["chequedoc"]["error"] > 0){ }
            else{ move_uploaded_file($_FILES["chequedoc"]["tmp_name"],$target."/".$photo5); }
        } 
    }
    $data = array('dealer_name' => $name, 'email'  => $email, 'personalcontactno' =>$contactNo, 'extracontactno' =>$contactalt, 'shopname' => $shopname, 'country' => $country, 'state' => $state, 'city' => $city, 'officeadress'  => addslashes($Adress), 'locality'=>$locality, 'pin'  => $pin, 'communicationemail' => $officemail, 'officecontactno' => $officecontactNo, 'tanno'=> $tanno, 'panno'=>$panno , 'regno'=> $regno, 'vatno'=> $vatno, 'bnkname' =>  $bnkname, 'branchname'=> $branchname, 'beneficiary'=> $beneficiary, 'acnttype'=> $acnttype, 'acntno' =>$acnt_no, 'IFSCcode' =>$ifsc, 'nickname'=>$nickname, 'disbursement_cycle_days' => $dis_days,"disburstment_auto_pay"=>$autopay);
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
if($action == "checknicknameavailablityupdate"){
    $username = $_REQUEST['nickname'];
    $dealerid = $_REQUEST['dealerid'];
    $getseller = selectQuery(VENDOR,"*","nickname='".$username."' and dealer_id<>".$dealerid);
    if(count($getseller)){ echo "0"; }
    else{ echo "1"; }
}
if($action == "mark_recived"){
    $vendor_id = $_REQUEST['vendor_id'];
    $plan_id = $_REQUEST['plan_id'];
    $data2 = array('plan_status' => "Active", 'payment_status' => "Received");
    $query2 = updateQuery(VENDOR, $data2, "dealer_id=".$vendor_id);
    $getdealer = selectQuery(VENDOR,"plan","dealer_id=".$vendor_id);
    $getcurrplan = selectQuery(VENDORPLANSELECTED,"plan","sel_id=".$getdealer[0]['plan']);
    $planprice = selectQuery(VENDORPLAN,"plan_duration","plan_name='".$getcurrplan[0]['plan']."'");
    $planduration_days = $planprice[0]['plan_duration'];
    $current = date("Y-m-d");
    $dateOneYearAdded = strtotime(date("Y-m-d", strtotime($current)) . "+".$planduration_days." days");
    $oneyear = date('Y-m-d', $dateOneYearAdded);
    $yesterday1 = strtotime(date("Y-m-d", strtotime($oneyear)) . " -1 days");
    $yesterday = date('Y-m-d', $yesterday1);
    $data1 = array("plan_status" => "Active", "payment_date" => date("Y-m-d H:i"),"plan_from"=>$current, "plan_to"=>$yesterday, "payment_status"=>"Received");
    $query2 = updateQuery(VENDORPLANSELECTED, $data1, "sel_id=" . $plan_id);
    $plandata = selectQuery(VENDORPLANSELECTED,"*","sel_id='".$plan_id."'");
    $data3 = array("plan_id"=>$plandata[0]['invoice_id'], "price"=>$plandata[0]['price'], "dealer_id"=>$vendor_id, "payment_date"=>date("Y-m-d H:i:s"), "payment_status"=>'Received',);
    $check_exist = selectQuery(VENDORPAYMENT,"plan_id","plan_id='".$plandata[0]['invoice_id']."'");
    if(count($check_exist)){ $insert = updateQuery(VENDORPAYMENT,$data3,"plan_id='".$plandata[0]['invoice_id']."'");}
    else{ $insert=insertQuery(VENDORPAYMENT,$data3); }
    echo "1";
}
if($action == "updateplan"){
    $vendor = $_REQUEST['vendor'];
    $planid = $_REQUEST['planid'];
    $requesttype = $_REQUEST['requesttype'];
    $plan0 = $_REQUEST['plan'];
    $payment = $_REQUEST['payment'];
    if($plan0!=""&&$plan0!="undefined"){
        $planprice = selectQuery(VENDORPLAN,"price,plan_duration","plan_name='".$plan0."'");
        $money = $planprice[0]['price'];
        $planduration_days = $planprice[0]['plan_duration'];
        $plan = $plan0;
    }
    else{ $money = "0"; $plan=""; }
    /* *************************************************************************************************************** */
    $ord = selectQuery(VENDORPLANSELECTED,"invoice_id","invoice_id<>'' order by sel_id DESC limit 1");
      if(count($ord)==0){ $orderid=VENDOR_PLAN_INVSTART."-1"; }
            else{
                $get_number =preg_replace('/\D/', '', $ord[0]['invoice_id']);
                $intformat = number_format($get_number)+1;

                $orderid = VENDOR_PLAN_INVSTART."-".$intformat;

            }
    /**************************************************************************************************************/
    $data1 = array('insert_date'=>date("Y-m-d"), 'invoice_id'=>$orderid, 'dealer_id'=>$vendor, 'plan'=>$plan, 'price'=>$money, 'payment_status'=>$payment,);
    if($payment=="Received"){
        $data1['payment_date']= date("Y-m-d H:i");
        //$data1['paymentgateway_status']= "success";
    }
    /**********************Getting plan start and end date*************************************/
    if($requesttype=="Renew"||$requesttype=="Upgrate - Normal"){
        $data1['plan_status'] = "Upcoming";
        $plandata1 = selectQuery(VENDORPLANSELECTED,"*","dealer_id=".$vendor." and payment_status='Received' order by sel_id DESC LIMIT 1");
        $current0 = $plandata1[0]['plan_to'];
        $current = date("Y-m-d", strtotime($plandata1[0]['plan_to'] . " +1 day"));
    }else{
        $data222=array("plan_status"=>"Expired",);
        $planupdatedata = updateQuery(VENDORPLANSELECTED,$data222,"dealer_id=".$vendor." and plan_status='Active'");
        $data1['plan_status'] = "Active";
        $current = date("Y-m-d");
    }
    $dateOneYearAdded = strtotime(date("Y-m-d", strtotime($current)) . "+".$planduration_days." days");      // chganged 1 year to db
    $oneyear = date('Y-m-d', $dateOneYearAdded);
    $yesterday1 = strtotime(date("Y-m-d", strtotime($oneyear)) . " -1 days");
    $yesterday = date('Y-m-d', $yesterday1);
    $data1['plan_from']=$current;
    $data1['plan_to']=$yesterday;
    /******************************************End Of gETTING plan start and end date******************************************************************************************* */
    $checkback = selectQuery(VENDORPLANSELECTED,"*","dealer_id=".$vendor." order by sel_id DESC limit 1");
    if(count($checkback)){ $data1['plan_type']=$requesttype; }
    else{ $data1['plan_type']="New"; }       
  
    $query1 = insertQuery(VENDORPLANSELECTED,$data1);
    $planidfromdata = $query1;
    if($query1){
        if($requesttype=="New"||$requesttype=="Upgrate - Immediate"||$requesttype==""){
            $data2 = array('plan'=>$planidfromdata, 'plan_status'=>"Active", 'payment_status'=>$payment);
            $query2 = updateQuery(VENDOR,$data2,"dealer_id=".$vendor);
        }
        $plandata = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$orderid."'");
        $datav = array("plan_id"=>$orderid, "price"=>$plandata[0]['price'], "dealer_id"=>$plandata[0]['dealer_id'], "payment_date"=>date("Y-m-d H:i:s"),
         "payment_status"=>$plandata[0]['payment_status']
        );
        if($plandata[0]['payment_status']=="Received"){ $datav['payu_status']="success"; } 
        $insert = insertQuery(VENDORPAYMENT,$datav);
        if($insert){ echo base64_encode($vendor); }
        else{ echo 0; }
    } else{ echo 0; }
}
if($action == "update_invoice"){
    $payid = $_REQUEST['payid'];
    $invoiceid = $_REQUEST['invoiceid'];
    $plan0 = $_REQUEST['plan'];
    $payment = $_REQUEST['payment'];
    $start = explode(" ",$_REQUEST['start']);
    $end = explode(" ",$_REQUEST['end']);
    $getcurrplan = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$invoiceid."'");
    $getdealer = selectQuery(VENDOR,"*","dealer_id=".$getcurrplan[0]['dealer_id']);
    $data = array("plan_from"=>$start[0], "plan_to"=>$end[0]);
    if($getcurrplan[0]['plan']!=$plan0){
        $data2 = array( );
        if($plan0!=""&&$plan0!="undefined"){
            $planprice = selectQuery(VENDORPLAN,"*","plan_name='".$plan0."'");
            $money = $planprice[0]['price'];
            $data['plan']=$plan0;
            $data['price']=$money;
            $data2['price']=$money;
        }else{
            $data['plan']="";
            $data['price']=0;
            $data2['price']=0;
        }
      if(sizeOf($data2)){ $updatepaymentdata=updateQuery(VENDORPAYMENT,$data2,"pay_id=".$payid); }
    }
    if($getcurrplan[0]['payment_Status']!=$payment){
        $data3 = array( );
        if($payment=="Received"){
            $data['payment_Status']=$payment;
            $data['payment_date']=date("Y-m-d H:i:s");
            $data3['payment_Status']="Received";
            $data3['payu_status']="success";
        } else{
            $data3['payment_Status']="Pending";
            $data3['payu_status']="";
        }
        if(sizeOf($data3)){ $updatepaymentdata = updateQuery(VENDORPAYMENT,$data3,"pay_id=".$payid); }
    }
    $updatecurrplan = updateQuery(VENDORPLANSELECTED,$data,"invoice_id='".$invoiceid."'"); 
    echo 1; 
}

if($action == "bulk_Import"){
    $pid = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data2 = array('bulk_import'=>$setval);
    $que = updateQuery(VENDOR,$data2,"dealer_id=".$pid);
    if($que){ echo 1;} else{echo 0;}
}

if($action=="add_pickup"){
    $id=$_POST['vendor'];
    require_once "../../classes/shiprocket.php";
    $shipusername=SHIPUSER; $shippasword=SHIPPWD;

    $get = selectQuery(VENDOR,"*","dealer_id=".$id);
    $name=$get[0]['dealer_name'];
    $officemail=$get[0]['communicationemail'];
    $officecontactNo=$get[0]['officecontactno'];
    $shopname=$get[0]['shopname'];
    $Adress=$get[0]['officeadress'];
    $locality=$get[0]['locality'];
    $city=$get[0]['city'];
    $state=$get[0]['state'];
    $country=$get[0]['country'];
    $pin=$get[0]['pin'];
    $ship = new shiprocket($shipusername,$shippasword);
    $token = $ship->authenticate();

    $pickup = array("pickup_location" => "v".$id,"name"=>preg_replace("/[^a-zA-Z ]/", " ", $name) ,
    "email" => $officemail,"phone" => $officecontactNo,"address" => $shopname." ".$Adress,"address_2" => $locality,"city"=>$city,"state"=>$state,"country"=>$country,"pin_code"=> $pin);

     $add=$ship->addPickups($token,$pickup);
     if($add['success']==1){echo $add['success'];}else{  print_r($add['errors']);   }
}
if($action=="get_pickup"){
    $id=$_POST['vendor'];
    require_once "../../classes/shiprocket.php";
    $shipusername=SHIPUSER; $shippasword=SHIPPWD;

 }

 ?>
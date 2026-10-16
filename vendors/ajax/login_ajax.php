<?php include("../../includes/configuration.php");
if(!empty($_SERVER["HTTP_CLIENT_IP"])){ $ip = $_SERVER["HTTP_CLIENT_IP"]; }
elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  // Check for the Proxy User
}else{ $ip = $_SERVER["REMOTE_ADDR"]; }
$agent = $_SERVER['HTTP_USER_AGENT'];
$browser = 'NA';
if((preg_match('/MSIE/i',$agent)||preg_match('/Trident/i',$agent)||(preg_match('/Trident/i',$agent)&&stristr($user_agent,'Windows Phone'))) && !preg_match('/Opera/i',$agent)){
    $browser = 'Internet Explorer';
    $ub = "MSIE";
} elseif(preg_match('/Windows NT 10/i',$agent) && preg_match('/Edge/i',$agent)){
    $browser = 'Microsoft Edge';
    $ub = "Edge";
} elseif(preg_match('/Firefox/i',$agent)){
    $browser = 'Mozilla Firefox';
    $ub = "Firefox";
} elseif(preg_match('/Chrome/i',$agent)){ 
    $browser = 'Google Chrome';
    $ub = "Chrome";
} elseif(preg_match('/Safari/i',$agent)){
    $browser = 'Apple Safari';
    $ub = "Safari";
} elseif(preg_match('/Opera/i',$agent)){
    $browser = 'Opera';
    $ub = "Opera";
}elseif(preg_match('/Netscape/i',$agent)){ 
    $browser = 'Netscape';
    $ub = "Netscape";
}
$device = '';
if( stristr($agent,'ipad')){
    $device = "ipad";
} else if((stristr($agent,'iphone') || strstr($agent,'iphone'))&&stristr($agent,'Windows Phone')=== FALSE) {
    $device = "iphone";
} else if( stristr($agent,'Windows Phone')){
    $device = "Windows Phone";
} else if( stristr($agent,'blackberry')){
    $device = "blackberry";
} else if( stristr($agent,'android')){
    $device = "android";
} else if( stristr($agent,'Windows NT 10.0')){
    $device = "Windows 10";
} else if( stristr($agent,'Windows NT 6.1')){
    $device = "Windows 7";
} if($action == "uservalidatewithoutotp"){
    $username = $_REQUEST['luname'];
    $userpass = password_encrypt($_REQUEST['lpassword']);
    $getuser = selectQuery(VENDOR,"dealer_id","email='".$username."' and password='".$userpass."'");
    if(count($getuser)){
        $_SESSION['seller']=$getuser[0]['dealer_id'];
        $_SESSION['last_login_timestamp'] = time();
        $s=base64_encode($_SESSION['seller']);
        $datalog=array('vendor_id' => $getuser[0]['dealer_id'], 'username' => $username, 'password' => $userpass, 'ip_address' => $ip, 'browser_name' => $browser, 'device_type' => $device, 'details' => $agent, 'login_time' => date('Y-m-d H:i:s'), 'login_Attempt' => 'success');
        $sinsert = insertQuery(VENDORLOG,$datalog);
        echo $s;
    }
    else{  
        $datalog = array('username'=>$username, 'password'=>$_REQUEST['lpassword'], 'ip_address'=>$ip, 'browser_name'=>$browser, 'device_type'=>$device, 'details'=>$agent, 'login_time'=>date('Y-m-d H:i:s'), 'login_Attempt'=>'failed');
        $sinsert = insertQuery(VENDORLOG,$datalog);
    }
}
if($action=="uservalidate"){ 
    $uname = $_REQUEST['user'];
    $pwd = password_encrypt($_REQUEST['pwd']);
    $getuser = selectQuery(VENDOR,"personalcontactno,nickname","email='".$uname."' and password='".$pwd."' ");
    if(count($getuser)==1){  
        $mobile = $getuser[0]['personalcontactno'];
        $length = 6;
        $chars = '0123456789';
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string)){ $r = $chars{rand(0, $chars_length)};if ($r != $string{$i - 1}) $string .=  $r;}
        $otp = $string;
        $_SESSION['sellerotp'] = $otp;
        $_SESSION['sellerotptime'] = date('YmdHis');
        $vendor_sms = selectQuery(SMSTEMPLATE,"*","type='Vendor login OTP' and  sms_to = 'Vendor' ");
        $type = "PHP";
        $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => $otp, 'smssitename' => SMSSITENAME,); 
        $replacement_array2 = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => "****", 'smssitename' => SMSSITENAME,); 
        $m = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
        $m1 = convertsmsstr($replacement_array2,$vendor_sms[0]['sms_text'] );
        $templateId = $vendor_sms[0]['templateId'];
        $sms = sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
        $id = (unserialize($sms));
        $msid = $id['data']['0']['id'];
        $status = $id['data']['0']['status'];
        $datasms = array("msg_id"=>$msid, "msg_type"=>"OTP SMS To Vendor", "user_name"=> $getuser[0]['nickname'], "mobile_no"=>$mobile,"message"=>$m1, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
        $store = insertQuery(SMS,$datasms);
        echo 1;
    } else{
        $datalog = array('username'=>$uname, 'password'=>$_REQUEST['pwd'], 'ip_address'=>$ip, 'browser_name'=>$browser, 'device_type'=>$device, 'details'=>$agent, 'login_time'=>date('Y-m-d H:i:s'), 'login_Attempt'=>'failed');
        $sinsert = insertQuery(VENDORLOG,$datalog);
        echo 0;
    }
}
if($action=="verifyotp"){
    $otp = $_REQUEST['otp'];
    $pwd1 = $_REQUEST['pwd1'];
    $uname = $_REQUEST['user'];
    $getuser = selectQuery(VENDOR,"dealer_id","email='".$uname."' and password='".password_encrypt($pwd1)."' ");
    if(count($getuser)==1){
        $sessotp = $_SESSION['sellerotp'];
        $sessotptime = $_SESSION['sellerotptime'];
        $currtime = date('YmdHis');
        $diff = $currtime-$sessotptime;
        if($diff>1500){ echo 1; }
            else{ if($sessotp!=$otp){ echo 0; }
            else{
                $_SESSION['sellerotp']="";
                $_SESSION['sellerotptime']="";
                $_SESSION['seller']=$getuser[0]['dealer_id'];
                $_SESSION['last_login_timestamp'] = time();
                $s = base64_encode($_SESSION['seller']);
                $datalog = array('vendor_id' => $getuser[0]['dealer_id'], 'username' => $uname, 'password' => $pwd1, 'ip_address' => $ip, 'browser_name' => $browser, 'device_type' => $device, 'details' => $agent, 'login_time' => date('Y-m-d H:i:s'), 'login_Attempt' => 'success');
                $sinsert = insertQuery(VENDORLOG,$datalog);
                echo "home.php?s=".$s;
            }
        }
    } else{
        $datalog=array('username'=>$uname, 'password'=>$_REQUEST['pwd1'], 'ip_address'=>$ip, 'browser_name'=>$browser, 'device_type'=>$device, 'details'=>$agent, 'login_time'=>date('Y-m-d H:i:s'), 'login_Attempt'=>'failed');
        $sinsert = insertQuery(VENDORLOG,$datalog);
        echo 2;
    }
}
if($action=="resendotp"){
    $pwd1 = $_REQUEST['pwd1'];
    $uname = $_REQUEST['user'];
    $getuser = selectQuery(VENDOR,"dealer_id,nickname","email='".$uname."' and password='".password_encrypt($pwd1)."' ");
    $mobile = $_REQUEST['mob'];
    $length = 6;
    $chars = '0123456789';
    $chars_length = (strlen($chars) - 1);
    $string = $chars{rand(0, $chars_length)};
    for ($i = 1; $i < $length; $i = strlen($string)){
        $r = $chars{rand(0, $chars_length)};
        if ($r != $string{$i - 1}) $string .=  $r;
    }
    $otp = $string;
    $_SESSION['sellerotp']=$otp;
    $_SESSION['sellerotptime'] = date('YmdHis');
    $vendor_sms = selectQuery(SMSTEMPLATE,"*","type='Vendor login OTP' and  sms_to = 'Vendor' ");
    $type = "PHP";
    $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => $otp, 'smssitename' => SMSSITENAME,); 
    $replacement_array2 = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => "****", 'smssitename' => SMSSITENAME,); 
    $m = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
    $m1 = convertsmsstr($replacement_array2,$vendor_sms[0]['sms_text'] );
    $templateId = $vendor_sms[0]['templateId'];
    $sms = sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
    $id = (unserialize($sms));
    $msid = $id['data']['0']['id'];
    $status = $id['data']['0']['status'];
    $datasms = array("msg_id"=>$msid, "msg_type"=>"OTP SMS To Vendor", "user_name"=> $getuser[0]['nickname'], "mobile_no"=>$mobile, "message"=>$m1, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
    $store = insertQuery(SMS,$datasms);
    echo 1;
}
if($action=="logincancel"){
    $_SESSION['sellerotp']="";
    $_SESSION['sellerotptime'] = "";
    echo 1;
} ?>
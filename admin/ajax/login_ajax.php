<?php include("../../includes/configuration.php");
$uname = $_REQUEST['user'];
$pwd = password_encrypt($_REQUEST['pwd']);
$action = $_REQUEST['action'];
if(!empty($_SERVER["HTTP_CLIENT_IP"])){ $ip = $_SERVER["HTTP_CLIENT_IP"]; }
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
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
} elseif(preg_match('/Netscape/i',$agent)){
    $browser = 'Netscape';
    $ub = "Netscape";
}
$device = '';
if( stristr($agent,'ipad')){ $device = "ipad"; }  
else if((stristr($agent,'iphone') || strstr($agent,'iphone'))&&stristr($agent,'Windows Phone')=== FALSE) {
    $device = "iphone";
} else if( stristr($agent,'Windows Phone')){
    $device = "Windows Phone";
} else if( stristr($agent,'blackberry')) {
    $device = "blackberry";
} else if( stristr($agent,'android')){
    $device = "android";
} else if( stristr($agent,'Windows NT 10.0')){
    $device = "Windows 10";
} else if( stristr($agent,'Windows NT 6.1')){
    $device = "Windows 7";
}
if($action=="uservalidatewithoutotp"){
    $datalog=array('username'=>$uname, 'ip_address'=>$ip, 'browser_name'=>$browser, 'device_type'=>$device, 'details'=>$agent, 'login_time' => date('Y-m-d H:i:s'),);
    $getuser = selectQuery(ADMIN,"u_id,utype","username='".$uname."' and password='".$pwd."' and isActive='1'");
    if(count($getuser)==1){   
        $datalog['password'] = $pwd;
        $datalog['login_Attempt'] = 'success';
        $datalog['admin_id'] = $getuser[0]['u_id'];
        $s=insertQuery(ADMINLOG,$datalog);
        $_SESSION['admin']=$getuser[0]['u_id'];
        $_SESSION['last_login_timestamp'] = time();
        $getuser1=selectQuery(SUPPORTSTAFF,"*","username='".$uname."' and emp_pwd='".$pwd."' and isActive='1'");
        if(count($getuser1)==1){
            $data = array("last_login"=>date("d-m-Y H:i:s"));
            $updateuser=updateQuery(SUPPORTSTAFF,$data,"emp_id=".$getuser1[0]['emp_id']);
            $_SESSION['staff']=$getuser1[0]['emp_id'];
            $_SESSION['dept']=$getuser1[0]['department'];
            $_SESSION['staffname']=$getuser1[0]['emp_name'];
            $_SESSION['acc_type']=$getuser1[0]['acc_type'];
            $_SESSION['uname']=$getuser1[0]['username'];
        }
        if($getuser[0]['utype'] == 'Admin')
        echo "home.php";
        else
        echo "subadminhome.php";
    } else{
          $datalog['password'] = $_REQUEST['pwd'];
          $datalog['login_Attempt'] = 'failed';
          $s = insertQuery(ADMINLOG,$datalog);       
          echo 0;
    }
}

if($action=="uservalidate"){
    $getuser = selectQuery(ADMIN,"u_mob","username='".$uname."' and password='".$pwd."' and isActive='1'");
    if(count($getuser)==1){
        $mobile = $getuser[0]['u_mob'];
        $length = 6;
        $chars = '0123456789';
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string)){
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1}) $string .=  $r;
        }
        $otp = $string;
        $_SESSION['adminotp']=$otp;
        $_SESSION['adminotptime'] = date('YmdHis');
        $admin_sms = selectQuery(SMSTEMPLATE,"*","type='Admin login OTP' and  sms_to = 'Admin' ");
        $type = "PHP";
        $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => $otp, 'smssitename' => SMSSITENAME,); 
        $replacement_array2 = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => "****", 'smssitename' => SMSSITENAME); 
        $m = convertsmsstr($replacement_array,$admin_sms[0]['sms_text']);
        $m1 = convertsmsstr($replacement_array2,$admin_sms[0]['sms_text']);
        $templateId = $admin_sms[0]['templateId'];
        $sms = sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
        $id = (unserialize($sms));
        $msid = $id['data']['0']['id'];
        $status = $id['data']['0']['status'];
        $datasms = array("msg_id"=>$msid, "msg_type"=>"OTP SMS To Admin", "user_name"=> "Admin", "mobile_no"=>$mobile, "message"=>$m1, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
        $store = insertQuery(SMS,$datasms);
        echo 1;
    } else{
        $datalog = array('username'=>$uname, 'ip_address'=>$ip, 'browser_name'=>$browser, 'device_type'=>$device, 'details'=>$agent, 'login_time' => date('Y-m-d H:i:s'), 'password' => $_REQUEST['pwd'], 'login_Attempt' => 'Failed');
        $sinsert = insertQuery(ADMINLOG,$datalog);
        echo 0;
    }
}
if($action=="verifyotp"){
    $otp = $_REQUEST['otp'];
    $pwd1 = password_encrypt($_REQUEST['pwd1']);
    $getuser = selectQuery(ADMIN,"u_id,utype","username='".$uname."' and password='".$pwd1."' and isActive='1'");
    if(count($getuser)==1){
        $sessotp = $_SESSION['adminotp'];
        $sessotptime = $_SESSION['adminotptime'];
        $currtime = date('YmdHis');
        $diff = $currtime-$sessotptime;
        if($diff>1500){ echo 1; }
        else{
            if($sessotp!=$otp){ echo 0; }
            else{
                $_SESSION['adminotp']="";
                $_SESSION['adminotptime']="";
                $_SESSION['admin']=$getuser[0]['u_id'];
                $_SESSION['last_login_timestamp'] = time();
                $s = base64_encode($_SESSION['admin']);
                $getuser1 = selectQuery(SUPPORTSTAFF,"*","username='".$uname."' and emp_pwd='".$pwd1."' and isActive='1'");
                if(count($getuser1)==1){
                    $data=array("last_login"=>date("d-m-Y H:i:s"));
                    $updateuser = updateQuery(SUPPORTSTAFF,$data,"emp_id=".$getuser1[0]['emp_id']);
                    $_SESSION['staff']=$getuser1[0]['emp_id'];
                    $_SESSION['dept']=$getuser1[0]['department'];
                    $_SESSION['staffname']=$getuser1[0]['emp_name'];
                    $_SESSION['acc_type']=$getuser1[0]['acc_type'];
                    $_SESSION['uname']=$getuser1[0]['username'];
                }
                $datalog=array('admin_id' => $getuser[0]['u_id'], 'username'=>$uname, 'password'=>$pwd1, 'ip_address'=>$ip, 'browser_name'=>$browser, 'device_type'=>$device, 'details'=>$agent, 'login_time' => date('Y-m-d H:i:s'), 'admin_id' => $getuser[0]['u_id'], 'login_Attempt'=>'success');
                $sinsert = insertQuery(ADMINLOG,$datalog);
                if($getuser[0]['utype'] == 'Admin'){ echo "home.php?s=".$s; } else{ echo "subadminhome.php?s=".$s; }
            }
        }
    } else{ echo 2; }
}

if($action=="logincancel"){
    $_SESSION['adminotp']="";
    $_SESSION['adminotptime'] = "";
    echo 1;
}

if($action=="resendotp"){
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
    $_SESSION['adminotp']=$otp;
    $_SESSION['adminotptime'] = date('YmdHis');
    $admin_sms = selectQuery(SMSTEMPLATE,"*","type='Admin login OTP' and  sms_to = 'Admin' ");
    $type = "PHP";
    $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => $otp,'smssitename' => SMSSITENAME,); 
    $replacement_array2 = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => "****", 'smssitename' => SMSSITENAME,); 
    $m = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
    $m1 = convertsmsstr($replacement_array2,$admin_sms[0]['sms_text'] );
    $templateId = $admin_sms[0]['templateId'];
    $sms = sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
    $id = (unserialize($sms));
    $msid = $id['data']['0']['id'];
    $status = $id['data']['0']['status'];
    $datasms = array("msg_id"=>$msid, "msg_type"=>"OTP SMS To Admin", "user_name"=> "Admin", "mobile_no"=>$mobile, "message"=>$m1, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
    $store = insertQuery(SMS,$datasms);
    echo 1;
}

if($action=="authenticateuser"){
    $username = base64_decode($_REQUEST['userid1']);
    $password = password_encrypt($_REQUEST['pwduser']);
    $getuser = selectQuery(ADMIN,"u_id","u_id='".$username."' and password='".$password."' and isActive='1'");
    if(count($getuser)==1){ echo 1; }
    else { echo 0; }
}

if($action=="cancel"){
    $_SESSION['profileotp']="";
    $_SESSION['profileotptime'] = "";
    echo 1;
}

if($action=="profile_sendotp"){
    $userid = $_REQUEST['userid'];
    $username = $_REQUEST['username'];
    $mobile = $_REQUEST['mobile'];
    $pwd = $_REQUEST['pwd'];
    $pwd1 = $_REQUEST['pwd1'];
    $getuser = selectQuery(ADMIN,"*","u_id=".base64_decode($userid));
    if(count($getuser)==1){
        $length = 6;
        $chars = '0123456789';
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string)){
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1}) $string .=  $r;
        }
        $otp = $string;
        $_SESSION['profileotp']=$otp;
        $_SESSION['profileotptime'] = date('YmdHis');
        $admin_sms = selectQuery(SMSTEMPLATE,"*","type='Admin Profile Update OTP' and  sms_to = 'Admin'");
        $type="PHP";
        $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => $otp, 'smssitename' => SMSSITENAME,); 
        $replacement_array2 = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => "****", 'smssitename' => SMSSITENAME,); 
        $m = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
        $m1 = convertsmsstr($replacement_array2,$admin_sms[0]['sms_text'] );
        $templateId= $admin_sms[0]['templateId'];
        $sms = sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
        $id = (unserialize($sms));
        $msid = $id['data']['0']['id'];
        $status = $id['data']['0']['status'];
        $datasms = array("msg_id"=>$msid, "msg_type"=>"Admin Profile Update OTP", "user_name"=> "Admin", "mobile_no"=>$mobile, "message"=>$m1, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
        $store = insertQuery(SMS,$datasms);
        echo 1;
    }else { echo 0; }
}

if($action=="profile_resendotp"){
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
    $_SESSION['profileotp']=$otp;
    $_SESSION['profileotptime'] = date('YmdHis');
    $admin_sms = selectQuery(SMSTEMPLATE,"*","type='Admin Profile Update OTP' and  sms_to = 'Admin'");
    $type = "PHP";
    $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => $otp, 'smssitename' => SMSSITENAME,
    ); 
    $replacement_array2 = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => "****", 'smssitename' => SMSSITENAME, ); 
    $m = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
    $m1 = convertsmsstr($replacement_array2,$admin_sms[0]['sms_text'] );
    $templateId= $admin_sms[0]['templateId'];
    $sms = sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
    $id = (unserialize($sms));
    $msid = $id['data']['0']['id'];
    $status = $id['data']['0']['status'];
    $datasms = array( "msg_id"=>$msid, "msg_type"=>"Admin Profile Update OTP", "user_name"=> "Admin", "mobile_no"=>$mobile, "message"=>$m1, "date"=>date("Y-m-d H:i:s"), "status"=>$status );
    $store = insertQuery(SMS,$datasms);
    echo 1;
}

if($action=="Profile_verifyotp"){
    $otp = $_REQUEST['otp'];
    $userid = $_REQUEST['userid'];
    $pwd = $_REQUEST['pwd'];
    $getuser = selectQuery(ADMIN,"*","u_id=".base64_decode($userid));
    if(count($getuser)==1){
        $sessotp = $_SESSION['profileotp'];
        $sessotptime = $_SESSION['profileotptime'];
        $currtime = date('YmdHis');
        $diff = $currtime-$sessotptime;
        if($diff>1500){ echo 1; }
        else{
            if($sessotp!=$otp){ echo 0; }
            else{
                $data = array();
                if($getuser[0]['username']!=$username){ $data['username'] = $username; $unamechng = 1; }
                if($getuser[0]['u_mob']!=$mobile){ $data['u_mob']=$mobile;}
                if(($pwd!=""&&$pwd1!="")&&($pwd===$pwd1)){
                    if($getuser[0]['password']!=password_encrypt($pwd)){  
                        $data['password']=password_encrypt($pwd);
                        $pwdchng=1;
                    }
                }
                if(sizeOf($data)>0){ $updateuser=updateQuery(ADMIN,$data,"u_id=".base64_decode($userid)); }
                $_SESSION['profileotp']="";
                $_SESSION['profileotptime']="";
                if($pwdchng==1||$unamechng==1){ echo "logout.php"; }
                else{  echo "editprofile.php"; }
            }
        }
    } else{ echo 2; }
}

if($action == "getedit_form" ){
  $userid = $_REQUEST['userid'];
  $username = $_REQUEST['username'];
  $mobile = $_REQUEST['mobile'];
  $pwd = $_REQUEST['pwd'];
  $pwd1 = $_REQUEST['pwd1']; ?>
<form class="otpform">
    <div class="col-12 pt-3">
        <div><h6 class="mb-3">Verification Code Sent On Your Mobile Number <?php echo "********".substr($mobile,8,2); ?></h6>
        <div class="msgs"></div>
        <div class="col-sm-8 col-md-8 col-lg-5 col-xl-4 px-0">
        <div class="form-group mb-2"><input type="text" name="otp" id="otp" class="form-control mbottom15" placeholder="Verification Code"/></div></div>
        <div class="mb-2">Not Getting? <span class="resendloader"><button class="btn btn-link resendbtn pl-0" onclick="resendotp('<?php echo $mobile; ?>')">Resend Now</button></span></div>
        </div>        
    </div>
    <div class="card-footer py-2 text-right"><input type="button" class="btn btn-primary" name="login" value="Validate And Update" onclick="verifyotp();"/>
    <input type="button" class="btn btn-secondary" name="back" value="Cancel" onclick="canceledit();"/></div>
</form>
<?php }

if($action=="update_profile"){

    $userid = $_REQUEST['userid'];
    $pwd = $_REQUEST['pwd'];
    $getuser = selectQuery(ADMIN,"*","u_id=".base64_decode($userid));
    if(count($getuser)==1){

        $data = array();
        if($getuser[0]['username']!=$username){ $data['username'] = $username; $unamechng = 1; }
        if($getuser[0]['u_mob']!=$mobile){ $data['u_mob']=$mobile;}
        if(($pwd!=""&&$pwd1!="")&&($pwd===$pwd1)){
            if($getuser[0]['password']!=password_encrypt($pwd)){
                $data['password']=password_encrypt($pwd);
                $pwdchng=1;
            }
        }

        if(sizeOf($data)>0){ $updateuser=updateQuery(ADMIN,$data,"u_id=".base64_decode($userid)); }

        if($pwdchng==1||$unamechng==1){ echo "logout.php"; }
        else{  echo "editprofile.php"; }


    } else{ echo 2; }
}
 ?>
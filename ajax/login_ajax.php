<?php
include("../includes/configuration.php");
if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
//check for ip from share internet
$ip = $_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
// Check for the Proxy User
$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else
{
$ip = $_SERVER["REMOTE_ADDR"];
}
$agent = $_SERVER['HTTP_USER_AGENT'];
$browser = 'NA';
if((preg_match('/MSIE/i',$agent)||preg_match('/Trident/i',$agent)||(preg_match('/Trident/i',$agent)&&stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone'))) && !preg_match('/Opera/i',$agent))
{
    $browser = 'Internet Explorer';
    $ub = "MSIE";
}
elseif(preg_match('/Windows NT 10/i',$agent) && preg_match('/Edge/i',$agent)){
    $browser = 'Microsoft Edge';
    $ub = "Edge";
}
elseif(preg_match('/Firefox/i',$agent))
{
    $browser = 'Mozilla Firefox';
    $ub = "Firefox";
}
elseif(preg_match('/Chrome/i',$agent))
{
    $browser = 'Google Chrome';
    $ub = "Chrome";
}
elseif(preg_match('/Safari/i',$agent))
{
    $browser = 'Apple Safari';
    $ub = "Safari";
}
elseif(preg_match('/Opera/i',$agent))
{
    $browser = 'Opera';
    $ub = "Opera";
}
elseif(preg_match('/Netscape/i',$agent))
{
    $browser = 'Netscape';
    $ub = "Netscape";
}
$device = '';
if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
    $device = "ipad";
}  else if((stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone'))&&stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone')=== FALSE) {
    $device = "iphone";
}
else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone') ) {
    $device = "Windows Phone";
}
else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
    $device = "blackberry";
} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
    $device = "android";
}
else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows NT 10.0') ) {
    $device = "Windows 10";
}
    else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows NT 6.1') ) {
    $device = "Windows 7";
}
if($action == "loginwithoutOTP"){
$loginu=$_REQUEST['loginu'];
$loginp=password_encrypt($_REQUEST['loginp']);
$user=selectQuery(BUYER,"u_id,isActive,email_verified","u_email='".$loginu."' and password='".$loginp."'  ");

      if(count($user)== 0 ){
         
                $datalog=array(
                'username'=>$loginu,
                'login_source'=>'Website',
                'password'=>$_REQUEST['loginp'],
                'ip_address'=>$ip,
                'browser_name'=>$browser,
                'device_type'=>$device,
                'details'=>$agent,
                'login_time'=>date('Y-m-d H:i:s'),
                'login_attempt'=>'failed',
                );
                $s=insertQuery(BUYERLOG,$datalog);
                echo "0";
      }
     else if($user[0]['isActive'] == '0' and $user[0]['email_verified'] == '1' ){
                 echo "2";
             } 
      else if($user[0]['isActive'] == '0' and $user[0]['email_verified'] == '0'){
                echo "3";
      }       
      else if($user[0]['isActive'] == '1' and $user[0]['email_verified'] == '1')
        {   
              $data=array(
                  'last_login'=>date('Y-m-d H:i:s')
                );
                $up=updateQuery(BUYER,$data,"u_id=".$user[0]['u_id']);
                $datalog=array(
                        'user_id'=>$user[0]['u_id'],
                        'login_source'=>'Website',
                      'username'=>$loginu,
                      'password'=>$loginp,
                      'ip_address'=>$ip,
                      'browser_name'=>$browser,
                      'device_type'=>$device,
                      'details'=>$agent,
                      'login_time'=>date('Y-m-d H:i:s'),
                      'login_attempt'=>'success',
                    );
                    $s=insertQuery(BUYERLOG,$datalog);
                $_SESSION['reguser']=$user[0]['u_id'];
                if($_SESSION['wishitems'] != "") { $item_array_id= array_column($_SESSION['wishitems'] ,"prod_id" );}
                if($_SESSION['shopping_cart'] != ""){ $cart_item_array_id= array_column($_SESSION['shopping_cart'] ,"prod_id" );}


                $cartdet1 = $_SESSION['shopping_cart'];
                if(count($cartdet1)){
                    for($i=0;$i<count($cartdet1);$i++){
                        $data=array(
                        'user_id'=>$_SESSION['reguser'],'type'=> 'CART' , 'prod_id'=> $cartdet1[$i]['prod_id'],'quantity'=> $cartdet1[$i]['quantity'],'insert_date'=> date("Y-m-d H:i:s"));
                        $checkincart =  selectQuery(CART,"count(cart_id) as total_cart_id","user_id='".$_SESSION['reguser']."' and type ='CART' and prod_id = '".$cartdet1[$i]['prod_id']."' ");
                       
                       
                        if($checkincart[0]['total_cart_id'] == 0){
                            $insert=insertQuery(CART,$data);
                        }
                    }
                    unset($_SESSION['shopping_cart']);
                }

                $get_cart = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='CART' ");
                $wisharrayl = array();
                if(count($get_cart)) {
                    for($z=0;$z<count($get_cart);$z++){
                        //if(!in_array($get_cart[$z]['prod_id'] ,$cart_item_array_id )){
                            $count= count($_SESSION['shopping_cart']);
                            $item_array=array('prod_id'=>$get_cart[$z]['prod_id'],'quantity'=>$get_cart[$z]['quantity']);
                            $_SESSION['shopping_cart'][$count] = $item_array;
                        //}
                    }
                }

                $wishdetails = $_SESSION['wishitems'];
                if(count($wishdetails)){
                    for($i=0;$i<count($wishdetails);$i++){
                        $data=array(
                        'user_id'=>$_SESSION['reguser'],'type'=> 'WISHLIST' , 'prod_id'=> $wishdetails[$i]['prod_id'],'quantity'=> $wishdetails[$i]['quantity'],'insert_date'=> date("Y-m-d H:i:s"));
                        $checkinwishlist =  selectQuery(CART,"count(cart_id) as total_cart_id","user_id='".$_SESSION['reguser']."'  and type ='WISHLIST' and prod_id = '".$wishdetails[$i]['prod_id']."' ");
                        if($checkinwishlist[0]['total_cart_id'] == 0){
                            $insert=insertQuery(CART,$data);
                        }
                    }
                    unset($_SESSION['wishitems']);
                }

                $get_wishlist = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='WISHLIST' ");
                if(count($get_wishlist)) {
                    for($z=0;$z<count($get_wishlist);$z++){
                       // if(!in_array($get_wishlist[$z]['prod_id'] ,$item_array_id )){
                            $count= count($_SESSION['wishitems']);
                            $item_array=array('prod_id'=>$get_wishlist[$z]['prod_id'],'quantity'=>$get_wishlist[$z]['quantity']);
                            $_SESSION['wishitems'][$count] = $item_array;
                           
                        //}
                    }
                }

              echo "1";
        }
    }  
    

    if($action=="uservalidate")
    {
    
        $uname=$_REQUEST['user'];
    $pwd=password_encrypt($_REQUEST['pwd']);
      $getuser=selectQuery(BUYER,"u_mobile,u_fname","u_email='".$uname."' and password='".$pwd."' and isActive='1' and email_verified ='1'");
     
    if(count($getuser)==1)
        {
        $mobile=$getuser[0]['u_mobile'];
        $length = 6;
        $chars = '0123456789';
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string))
        {
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1}) $string .=  $r;
        }
        $otp = $string;
        $_SESSION['userotp']=$otp;
        $_SESSION['userotptime'] = date('YmdHis');
        $buyer_sms =  selectQuery(SMSTEMPLATE,"*","type='Buyer login OTP' and  sms_to = 'Buyer' ");
        $type="PHP";
        $replacement_array = array(
            'siteurl' => SITEURL,
            'sitename' => SITENAME,
            'OTP' => $otp,
            'smssitename' => SMSSITENAME,
        ); 
        $replacement_array2 = array(
          'siteurl' => SITEURL,
          'sitename' => SITENAME,
          'OTP' => "****",
          'smssitename' => SMSSITENAME,
        ); 
        
        $m = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
        $m1 = convertsmsstr($replacement_array2,$buyer_sms[0]['sms_text'] );
        $templateId= $buyer_sms[0]['templateId'];
        $sms= sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
        $id=(unserialize($sms));
        $msid=$id['data']['0']['id'];
        $status=$id['data']['0']['status'];
        $datasms=array(
        "msg_id"=>$msid,
        "msg_type"=>"OTP SMS To Buyer",
        "user_name"=> $getuser[0]['u_name'],
        "mobile_no"=>$mobile,
        "message"=>$m1,
        "date"=>date("Y-m-d H:i:s"),
        "status"=>$status
        );
        $store=insertQuery(SMS,$datasms);
        echo 1;
    }
    else
    {     $datalog=array(
        'username'=>$uname,
        'login_source'=>'Website',
        'password'=>$_REQUEST['pwd'],
        'ip_address'=>$ip,
        'browser_name'=>$browser,
        'device_type'=>$device,
        'details'=>$agent,
        'login_time'=>date("Y-m-d H:i:s"),
        'login_Attempt'=>'failed'
      );
      $sinsert=insertQuery(BUYERLOG,$datalog);
        echo 0;
    }
    }  
    
    if($action == "getotpform"){
    $uname=$_REQUEST['username'];
    $pwd=md5($_REQUEST['logkey']);
    $getuser=selectQuery(BUYER,"u_mobile","u_email='".$uname."' and password='".$pwd."' and isActive='1' and email_verified ='1'");
      if(count($getuser)==1){ $mobile=$getuser[0]['u_mobile']; }
      ?>
      <form class="loginform">
      <div class="msgsl"></div>
           <div class="form-group text-center">
                     <b> Verification Code Sent On Your Mobile Number
                      <?php echo "********".substr($mobile,8,2); ?></b>
                     Not Getting? <span class="resendloader"><button class="btn btn-link resendbtn" onclick="resendotp('<?php echo $mobile; ?>')"> Resend Now </button></span>
                </div>
      
                      <div class="form-group">
                         <input type="text"  name="otp" id="otp" class="form-control mbottom15" placeholder="Verification Code"/>
                         <input type="hidden"  name="username" id="user" class="form-control mbottom15" placeholder="Username" value="<?php echo $uname; ?>"/>
                         <input type="hidden"  name="logkey" id="password" class="form-control mbottom15" autocomplete="off " placeholder="Password" value="<?php echo $pwd; ?>"/>
                     </div>
                     <div class="text-center">
                      <input type="button"  class="btn btn-primary greenBtn" name="login" value="Validate And Login" onclick="verifyotp();"/>
                      <input type="button" class="btn btn-primary btnred" name="back" value="Cancel" onclick="logincancel();"/>
                      </div>
                </form>
    <?php
    }
    
    
    
    if($action=="verifyotp")
    { 
    $otp=$_REQUEST['otp'];
    $pwd1=$_REQUEST['pwd1'];
    $uname = $_REQUEST['user'];
    $getuser=selectQuery(BUYER,"u_id","u_email='".$uname."' and password='".$pwd1."' and isActive='1' and email_verified ='1'");
    if(count($getuser))
    {
      $sessotp=$_SESSION['userotp'];
      $sessotptime=$_SESSION['userotptime'];
      $currtime=date('YmdHis');
        $diff=$currtime-$sessotptime;
        if($diff>1500)
        {
            echo 1; //expired
        }
        else
        {
            if($sessotp!=$otp)
            {
                echo 0; //invalide
            }
            else
            {
                $_SESSION['userotp']="";
                $_SESSION['userotptime']="";
                $_SESSION['reguser']=$getuser[0]['u_id'];
                $_SESSION['last_login_timestamp'] = time();
                    
                        $datalog=array(
                        'user_id'=>$getuser[0]['u_id'],
                        'login_source'=>'Website',
                        'username'=>$uname,
                        'password'=>$pwd1,
                        'ip_address'=>$ip,
                        'browser_name'=>$browser,
                        'device_type'=>$device,
                        'details'=>$agent,
                        'login_time'=>date("Y-m-d H:i:s"),
                        'login_attempt'=>'success'
                        );
                        $s=insertQuery(BUYERLOG,$datalog);

                        $_SESSION['reguser']=$user[0]['u_id'];
                        if(count($_SESSION['wishitems']) != 0) { $item_array_id= array_column($_SESSION['wishitems'] ,"prod_id" );}
                        if(count($_SESSION['shopping_cart'] != 0)){ $cart_item_array_id= array_column($_SESSION['shopping_cart'] ,"prod_id" );}
        
        
                        $cartdet1 = $_SESSION['shopping_cart'];
                        if(count($cartdet1)){
                            for($i=0;$i<count($cartdet1);$i++){
                                $data=array(
                                'user_id'=>$_SESSION['reguser'],'type'=> 'CART' , 'prod_id'=> $cartdet1[$i]['prod_id'],'quantity'=> $cartdet1[$i]['quantity'],'insert_date'=> date("Y-m-d H:i:s"));
                                $checkincart =  selectQuery(CART,"count(cart_id) as total_cart_id","user_id='".$_SESSION['reguser']."' and type ='CART' and prod_id = '".$cartdet1[$i]['prod_id']."' ");
                               
                               
                                if($checkincart[0]['total_cart_id'] == 0){
                                    $insert=insertQuery(CART,$data);
                                }
                            }
                            unset($_SESSION['shopping_cart']);
                        }
        
                        $get_cart = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='CART' ");
                        $wisharrayl = array();
                        if(count($get_cart)) {
                            for($z=0;$z<count($get_cart);$z++){
                               // if(!in_array($get_cart[$z]['prod_id'] ,$item_array_id )){
                                    $count= count($_SESSION['shopping_cart']);
                                    $item_array=array('prod_id'=>$get_cart[$z]['prod_id'],'quantity'=>$get_cart[$z]['quantity']);
                                    $_SESSION['shopping_cart'][$count] = $item_array;
                                // }
                            }
                        } 
        
                        $wishdetails = $_SESSION['wishitems'];
                        if(count($wishdetails)){
                            for($i=0;$i<count($wishdetails);$i++){
                                $data=array(
                                'user_id'=>$_SESSION['reguser'],'type'=> 'WISHLIST' , 'prod_id'=> $wishdetails[$i]['prod_id'],'quantity'=> $wishdetails[$i]['quantity'],'insert_date'=> date("Y-m-d H:i:s"));
                                $checkinwishlist =  selectQuery(CART,"count(cart_id) as total_cart_id","user_id='".$_SESSION['reguser']."'  and type ='WISHLIST' and prod_id = '".$wishdetails[$i]['prod_id']."' ");
                                if($checkinwishlist[0]['total_cart_id'] == 0){
                                    $insert=insertQuery(CART,$data);
                                }
                            }
                            unset($_SESSION['wishitems']);
                        }
        
                        $get_wishlist = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='WISHLIST' ");
                        $wisharrayl = array();
                        if(count($get_wishlist)) {
                            for($z=0;$z<count($get_wishlist);$z++){
                               // if(!in_array($get_wishlist[$z]['prod_id'] ,$item_array_id )){
                                    $count= count($_SESSION['wishitems']);
                                    $item_array=array('prod_id'=>$get_wishlist[$z]['prod_id'],'quantity'=>$get_wishlist[$z]['quantity']);
                                    $_SESSION['wishitems'][$count] = $item_array;
                               // }
                            }
                        }
        
    
                echo "home.php";
    
    
            }
        }
    }
    else
        {
            echo 2;
        }
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
    $_SESSION['userotp']=$otp;
    $_SESSION['userotptime'] = date('YmdHis');
    $buyer_sms = selectQuery(SMSTEMPLATE,"*","type='Buyer login OTP' and  sms_to = 'Buyer' ");
    $type = "PHP";
    $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => $otp, 'smssitename' => SMSSITENAME,); 
    $replacement_array2 = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'OTP' => "****", 'smssitename' => SMSSITENAME,); 
    $m = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
    $m1 = convertsmsstr($replacement_array2,$buyer_sms[0]['sms_text'] );
    $templateId= $buyer_sms[0]['templateId'];
    $sms = sendsms($mobile,$m,WORKINGKEY,SMS_SENDER,$templateId);
    $id = (unserialize($sms));
    $msid = $id['data']['0']['id'];
    $status = $id['data']['0']['status'];
    $datasms = array("msg_id"=>$msid, "msg_type"=>"OTP SMS To Buyer", "user_name"=> "User", "mobile_no"=>$mobile, "message"=>$m1, "date"=>date("Y-m-d H:i:s"), "status"=>$status );
    $store = insertQuery(SMS,$datasms);
    echo 1;
}
if($action=="logincancel"){
    $_SESSION['userotp']="";
    $_SESSION['userotptime'] = "";
    echo 1;
}    
if($action == "insert_user" ){
    if(($_SESSION['key'] == $key) &&  ($key != "")){
        $result = selectQuery(BUYER,"count(u_id) as total_user","u_email='".$mail1."'");
        if($result[0]['total_user'] > 0){ echo 1; }
        else{
            $data = array('u_fname'=>ucfirst($name), 'u_lname'=>ucfirst($lname), 'u_gender'=>$gender, 'u_mobile'=>$mob, 'u_email'=>$mail1, 'password'=>password_encrypt($mainpass), 'reg_date' =>date("Y-m-d H:i:s") );
            $insert = insertQuery(BUYER,$data);
            if($insert){
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
                $user_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Buyer Registration' and  mail_to= 'Buyer' "); 
                $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Buyer Registration' and  mail_to= 'Admin' "); 
                $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME, 'name' => $name." ".$lname, 'gender' => $gender, 'mobile' => $mob, 'email' => $mail1, 'userid'=> base64_encode($insert), );
                $subject_buyer = convertemailstr($replacement_array,$user_email[0]['subject']);
                $body_buyer = convertemailstr($replacement_array,$user_email[0]['body']);
                $sentmail = mail($mail1, $subject_buyer, $body_buyer, $headers); 
                $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
                $sentmailadmin = mail(EMAIL_REG, $subject_admin, $body_admin, $headers);
                if(SMS_SYSTEM=="ON"){    
                    $buyer_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Buyer Registration' and  sms_to = 'Buyer' ");
                    $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Buyer Registration' and  sms_to = 'Admin' ");
                    $msg= convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                    $templateId= $buyer_sms[0]['templateId'];
                    $sms= sendsms($mob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                    $id = (unserialize($sms));
                    $msid = $id['data']['0']['id'];
                    $status = $id['data']['0']['status'];
                    $datasms = array("msg_id"=>$msid, "msg_type"=>"Buyer Registration SMS To Buyer", "user_id"=> $insert,
                    "user_name"=> $name."".$lname, "mobile_no"=>$mob,
                    "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status );
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
                        $datasms1 = array("msg_id"=>$msid1, "msg_type"=>"Buyer Registration SMS To Admin", "message"=>$msg,
                        "date"=>date("Y-m-d H:i:s"), "status"=>$status1,"user_name"=>"Admin","mobile_no"=>$tempmob);
                        $insert1 = insertQuery(SMS,$datasms1);
                    }
                }
            }
            echo 0;
        }
    }
    else { echo "3"; }   
} ?>
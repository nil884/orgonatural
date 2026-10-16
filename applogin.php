<?php include "includes/configuration.php";

//file_put_contents("outputfile.txt","\n".date("d M Y H:i:s")."    Data : ".file_get_contents("php://input")."\n",FILE_APPEND);
if (!empty($_SERVER["HTTP_CLIENT_IP"])){$ip = $_SERVER["HTTP_CLIENT_IP"];}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];}
else{$ip = $_SERVER["REMOTE_ADDR"];}
$agent = $_SERVER['HTTP_USER_AGENT'];$browser = 'NA';$device = '';
if((preg_match('/MSIE/i',$agent)||preg_match('/Trident/i',$agent)||(preg_match('/Trident/i',$agent)&&stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone'))) && !preg_match('/Opera/i',$agent)){$browser = 'Internet Explorer';$ub = "MSIE";}
elseif(preg_match('/Windows NT 10/i',$agent) && preg_match('/Edge/i',$agent)){$browser = 'Microsoft Edge';$ub = "Edge";}
elseif(preg_match('/Firefox/i',$agent)){$browser = 'Mozilla Firefox';$ub = "Firefox";}
elseif(preg_match('/Chrome/i',$agent)){$browser = 'Google Chrome';$ub = "Chrome";}
elseif(preg_match('/Safari/i',$agent)){$browser = 'Apple Safari';$ub = "Safari";}
elseif(preg_match('/Opera/i',$agent)){$browser = 'Opera';$ub = "Opera";}
elseif(preg_match('/Netscape/i',$agent)){$browser = 'Netscape';$ub = "Netscape";}

if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {$device = "ipad";}  
else if((stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone'))&&stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone')=== FALSE) {$device = "iphone";}
else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone') ) {$device = "Windows Phone";}
else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {$device = "blackberry";} 
else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {$device = "android";}
else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows NT 10.0') ) {$device = "Windows 10";}
else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows NT 6.1') ) {$device = "Windows 7";}

$json=file_get_contents("php://input");

$json=str_replace("(","",$json); $json=str_replace(")","",$json);
$array=explode(" ",$json); $length=count($array);
$emailarr=array_pop($array); $email=$emailarr; $namearr=$array; $lastname=array_pop($namearr); $fnamearr=$namearr; $fname=implode(" ",$fnamearr);

if($email){
$user=selectQuery(BUYER,"u_id","u_email='".$email."'");

if(count($user)){
  //echo "Processing login";
//  file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  In login \n",FILE_APPEND);
  $_SESSION['reguser']=$user[0]['u_id'];
  $get_cart = selectQuery(CART,"prod_id,quantity","user_id=".$user[0]['u_id']." and type='CART' ");
    if(count($get_cart)) {
        for($z=0;$z<count($get_cart);$z++){
            //if(!in_array($get_cart[$z]['prod_id'] ,$cart_item_array_id )){
                $count= count($_SESSION['shopping_cart']);
                $item_array=array('prod_id'=>$get_cart[$z]['prod_id'],'quantity'=>$get_cart[$z]['quantity']);
                $_SESSION['shopping_cart'][$count] = $item_array;
            //}
        }
    }
    $get_wishlist = selectQuery(CART,"prod_id,quantity","user_id=".$user[0]['u_id']." and type='WISHLIST' ");
    if(count($get_wishlist)) {
        for($z=0;$z<count($get_wishlist);$z++){
         
                $count= count($_SESSION['wishitems']);
                $item_array=array('prod_id'=>$get_wishlist[$z]['prod_id'],'quantity'=>$get_wishlist[$z]['quantity']);
                $_SESSION['wishitems'][$count] = $item_array;
            
        }
    }


  if($_SESSION['reguser']){
    $data=array('last_login'=>date('Y-m-d H:i:s'));
    updateQuery(BUYER,$data,"u_id=".$user[0]['u_id']);
    echo $user[0]['u_id']; 
    $datalog=array(
            'user_id'=>$user[0]['u_id'],
            'login_source'=>'google',
          'username'=>$email,
          'password'=>"",
          'ip_address'=>$ip,
          'browser_name'=>$browser,
          'device_type'=>$device,
          'details'=>$agent,
          'login_time'=>date('Y-m-d H:i:s'),
          'login_attempt'=>'success',
        );
        insertQuery(BUYERLOG,$datalog);
  //      file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  Session created for email ".$email." ".$_SESSION['reguser']." ".session_id()."\n",FILE_APPEND);
  //      file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  Sending user id to app ".$user[0]['u_id']." \n",FILE_APPEND);
      //  header("Location:applogincheck.php?user=".$_SESSION['reguser']);
     
  }else{
   // file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  Error in session creation for email ".$email."\n",FILE_APPEND); 
  }
   
}else{
    $inarr=array("u_fname"=>trim($fname),"u_lname"=>trim($lastname),"u_email"=>$email,"source"=>"google","email_verified"=>'1',"reg_date"=>date("Y-m-d H:i:s"),"isActive"=>'1',"last_login"=>date("Y-m-d H:i:s"));
   // file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  In reg ".$inarr."\n",FILE_APPEND);
    $user=insertQuery(BUYER,$inarr);
    $_SESSION['reguser']=$user;
    $get_cart = selectQuery(CART,"prod_id,quantity","user_id=".$user." and type='CART' ");
    
    if(count($get_cart)) {
        for($z=0;$z<count($get_cart);$z++){
            //if(!in_array($get_cart[$z]['prod_id'] ,$cart_item_array_id )){
                $count= count($_SESSION['shopping_cart']);
                $item_array=array('prod_id'=>$get_cart[$z]['prod_id'],'quantity'=>$get_cart[$z]['quantity']);
                $_SESSION['shopping_cart'][$count] = $item_array;
            //}
        }
    }
    $get_wishlist = selectQuery(CART,"prod_id,quantity","user_id=".$user." and type='WISHLIST' ");
    if(count($get_wishlist)) {
        for($z=0;$z<count($get_wishlist);$z++){
         
                $count= count($_SESSION['wishitems']);
                $item_array=array('prod_id'=>$get_wishlist[$z]['prod_id'],'quantity'=>$get_wishlist[$z]['quantity']);
                $_SESSION['wishitems'][$count] = $item_array;
            
        }
    }

    if($_SESSION['reguser']){
      $datalog=array(
        'user_id'=>$user,
        'login_source'=>'google',
      'username'=>$email,
      'password'=>"",
      'ip_address'=>$ip,
      'browser_name'=>$browser,
      'device_type'=>$device,
      'details'=>$agent,
      'login_time'=>date('Y-m-d H:i:s'),
      'login_attempt'=>'success',
    );
    insertQuery(BUYERLOG,$datalog);
   // file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  Session created for email ".$_SESSION['reguser']." - ".$email."\n",FILE_APPEND);
   // file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  Sending user id to app ".$user." \n",FILE_APPEND);
    echo $user;
   // header("Location:applogincheck.php?user=".$_SESSION['reguser']);
    }else{
     // file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  Error in session creation for email ".$email."\n",FILE_APPEND); 
    }
 }
}else{
 // file_put_contents("outputfile.txt",date('d M Y H:i:s')." -  Error Blank data getting ".file_get_contents("php://input")."\n",FILE_APPEND); 
}
?>
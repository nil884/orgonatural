<?php include "../../includes/configuration.php";
if ($action == "contact") {
    $data = array( "address" => $area,"pincode"=>$pincode, "map" => $map);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}
if ($action == "social_link") {
    $data = array("fb_link" => $fb_link, "instagram_link" => $ins_link, "linkedIn_link" => $ldin_link, "Pinterest_link" => $Pinterest_link,"youtube_link"=>$youtube_link,"twitter_link"=>$twitter_link, "playstore_link"=>$playstore_link);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}
if ($action == "Analytics_Id") {
    $data = array("Analytics_Id" => $Analytics_Id, );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

/*if ($action == "Cancellation_charge") {
    $data = array("cancelation_charge_percentage" => $Cancellation_charge);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}*/
if ($action == "social_login") {                   
    $data = array("fb_appId" => $fb_appId, "fb_secretKey" => $fb_secretKey, "fb_callbackURL" => $fb_callbackURL);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "social_login_gp") {
    $data = array("gp_clientId" => $gp_clientId, "gp_secretKey" => $gp_secretKey, "gp_callbackURL" => $gp_callbackURL,"gp_appname" => $gp_appname);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "Payu_money_details") {
    if($pay_url == "https://secure.payu.in" ){
        $data = array("live_payu_merchant_key" => $Merchant_Key, "live_payu_salt_key" => $salt_key, "payu_url" => $pay_url,"payu_auth_header"=>$auth_header);
    }
    else {
        $data = array("payu_url" => $pay_url); 
    }
    
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }

}

if ($action == "cc_Payu_money_details") {
    $data = array("cc_Merchant_Key" => $cc_Merchant_Key, "cc_access_code" => $cc_access_code, "cc_working_code" => $cc_working_code, "cc_pay_url" => $cc_pay_url, "cc_enable" => $cc_enable);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "ease_money_details") {

    if($ease_env == "prod" ){
        $data = array("ease_id" => $ease_id,"live_ease_merchant_key" => $ease_Merchant_Key, "live_ease_salt_key" => $ease_salt_key, "ease_environment" => $ease_env);
      }
      else {
       $data = array("ease_id" => $ease_id,"ease_environment" => $ease_env);
      }
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}


if ($action == "insta_money_details") {
    // instamojo_env, insta_api_Key, insta_auth_token,test_insta_api_Key, test_insta_auth_token

  $data = array("instamojo_liveApiKey" => $insta_api_Key, "instamojo_liveAuthToken" => $insta_auth_token, "instamojo_environment" => $instamojo_env);

    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "razorpay_money_details") {
    // razorpay_env, razorpay_api_Key, razorpay_secrete, test_razorpay_api_Key, test_razorpay_secrete
     $data = array("razorpay_liveApiKey" => $razorpay_api_Key, "razorpay_liveSecrete" => $razorpay_secrete, "razorpay_environment" => $razorpay_env);

    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "sms_gateway") {
    
    $data = array("sms_sender" => $sms_sender, "sms_working_key" => $sms_working_key, "sms_site_name" => $sms_site_name,"sms_url"=>$sms_url); 
    $ch1 = curl_init(); curl_setopt($ch1, CURLOPT_URL, "http://api-alerts.solutionsinfini.com/v3/?method=account.credits&api_key=".$sms_working_key."&format=PHP"); curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); $output = curl_exec($ch1); curl_close($ch1); $arrout = (unserialize($output)); $credit = floor($arrout['data']['credits']); 
     $result =[];
     $result['status'] = $arrout['status'];
     if($arrout['status']=='OK'){
        $result['credit'] = $credit;
         $pdate = updateQuery(CONFIG, $data, "id= 1");

    }
    echo json_encode($result);

}

if($action == "sendmailtoadmin"){
    $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     $headers .= "From:".SITENAME."<".EMAIL_SENDER.">"; 
      $subject_admin= "Request for SMS sender id";
      $body_admin = '<div style="width:600px;max-width:100%;font-size:16px;font-family:calibri;background-color:#fff;border:1px solid #d7d7d7;margin-bottom:20px;">
    <div style="padding: 20px 20px 0 20px;">
        <div style="margin: 0 auto;"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="logo" width="130"></div>
           </div> 
    <div style="padding:20px 20px;font-size:16px;">
        <p style="margin-top:0;"><b>Dear Admin,</b></p>
        <p style="margin-bottom: 0;color:#4d4d4d;"> New SMS sender ID request is generated by <br> </br>
        1)Website: '.SITEURL.' <br> 2)Sender Id :'.strtoupper($sendid).'.</p>
    </div>
    <div style="padding:0 20px;font-size:16px;line-height:20px;border-top:1px solid #d7d7d7">
        <p style="color:#4d4d4d; margin-top:0"><br><i>This is an automated email; if you received it in error, no action is required.<br> For more information, please visit <a href="'.SITEURL.'/">'.SITENAME.'</a></i></p>
    </div>
</div>';
     $sentmail_admin = mail('Support@surun.in', $subject_admin, $body_admin, $headers); 
     $sentmail_admin = mail('surunclients@gmail.com', $subject_admin, $body_admin, $headers);
     if($sentmail_admin) {  echo "1";}  else {  echo "0";}
}

if ($action == "default_timezone") {
    $data = array("default_timezone" =>addslashes($default_timezone) );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {  echo 1;
    } else {   echo 0;
    }
}

if ($action == "email_setting") {
    $data = array(
        "admin_email" => $admin_email,
        "footer_email" => $footer_email,
        "sender_email" => $sender_email,
        "registration_email" => $registration_email,
        "order_email" => $order_email,
        "contact_email" => $contact_email,
    );

    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "sitedetails") {
    $data = array(
        "site_name" => $site_name,
        "seo_title" => $site_title,
        "seo_description" => addslashes($metadesc),
        "seo_keywords" => addslashes($keywords),
    );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "gst_details") {
    $data = array(
        "gst_no" => $gst_no,
        "vat_no" => $vat_no,
        "gst_desc" => $gst_desc,
    );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($action == "ship_management") {
    $data = array(
        "free_shipping_on_order" => $free_shipping_on_order,
        "free_shipping_on_order_cost" => $free_shipping_on_order_cost,
        "cancelation_charge_percentage" => $Cancellation_charge,
        "refund_with_shipping"=>$refund_with_shipping,
        "cut_shipping_charges_on_cancelation"=>$cancalation_shipping,
        "default_vendor_for_pos"=>$pos_vendor,
        "pos_activate"=>($pos_vendor==0?0:1)
    );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {  echo 1;} else {  echo 0; }
}

if ($action == "delmob") {
    $getdetails1 = selectQuery(CONFIG, "*", "id= 1");
    if($type == "Contact") {  
        $sperate = explode(",", $getdetails1[0]['contactus_no']);
        if (($key = array_search($mob, $sperate)) !== false) {
            unset($sperate[$key]);
        }
        $data = array("contactus_no" => implode(",", $sperate));
      
     } 
    else if($type == "Enquiry") {
    $sperate = explode(",", $getdetails1[0]['enquiry_contact']);
    if (($key = array_search($mob, $sperate)) !== false) {
        unset($sperate[$key]);
    }
    $data = array("enquiry_contact" => implode(",", $sperate));
  } 

  else if($type == "Admin"){
    $sperate = explode(",", $getdetails1[0]['Main_admin_contact']);
    if (($key = array_search($mob, $sperate)) !== false) {
        unset($sperate[$key]);
    }
    $data = array("Main_admin_contact" => implode(",", $sperate));
  }
$pdate = updateQuery(CONFIG, $data, "id= 1");
    }

if ($action == "add_admin_mob") {
    $A_contact_no = "+".$code."-".$A_contact_no;
    $getdetails1 = selectQuery(CONFIG, "*", "id= 1");
    if($type == "Enquiry") {
    if ($getdetails1[0]['enquiry_contact'] != "") {
        $sperate = explode(",", $getdetails1[0]['enquiry_contact']);

    } else {
        $sperate = array();
    }
    if(count($sperate)> 4 ){
        echo 2;
    }
    else if (in_array($A_contact_no, $sperate)) {
        echo 1;
    } else {
        array_push($sperate, $A_contact_no);
        $data = array("enquiry_contact" => implode(",", $sperate));
        $pdate = updateQuery(CONFIG, $data, "id= 1");
      }
    }
    else if($type == "Contact") {
        if ($getdetails1[0]['contactus_no'] != "") {
            $sperate = explode(",", $getdetails1[0]['contactus_no']);
    
        } else {
            $sperate = array();
        }

        if(count($sperate)> 4 ){
            echo 2;
        }
        else if (in_array($A_contact_no, $sperate)) {
            echo 1;
        } else {
            array_push($sperate, $A_contact_no);
            $data = array("contactus_no" => implode(",", $sperate));
            $pdate = updateQuery(CONFIG, $data, "id= 1");
          }
    }  
    else if($type == "Admin"){
        if ($getdetails1[0]['Main_admin_contact'] != "") {
            $sperate = explode(",", $getdetails1[0]['Main_admin_contact']);
    
        } else {
            $sperate = array();
        }

        if(count($sperate)> 4 ){
            echo 2;
        }
        else if (in_array($A_contact_no, $sperate)) {
            echo 1;
        } else {
            array_push($sperate, $A_contact_no);
            $data = array("Main_admin_contact" => implode(",", $sperate));
            $pdate = updateQuery(CONFIG, $data, "id= 1");
          }
    }
    
    
    
    }

if ($action == "order_prefix") {
    $data = array(
        "order_id" => $order_id,
         "seller_inv" => $seller_inv,
    );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "payu_enable_disble"){
    $status = $_REQUEST['status'];
       $data = array("payu_enable"=>$status);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "easebuzz_enable_disable"){
    $status = $_REQUEST['status'];
       $data = array("ease_enable"=>$status);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "instamojo_enable_disable"){
    $status = $_REQUEST['status'];
       $data = array("instamojo_enable"=>$status);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "razorpay_enable_disable"){
    $status = $_REQUEST['status'];
       $data = array("razorpay_enable"=>$status);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "sms_enable_disble"){
    $status = $_REQUEST['status'];
       $data = array("sms_enable"=>$status);
        if($status=="OFF"){
           $data["admin_authentication"]=0;$data["vendor_authentication"] =0; $data["user_authentication"] = 0;
       }

         if($status=="ON"){
            $datam=array("isActive"=>"1"); updateQuery(ADMINMENU,$datam,"menu_name='SMS Report'");
        }else if($status=="OFF"){
            $datam=array("isActive"=>"0"); updateQuery(ADMINMENU,$datam,"menu_name='SMS Report'");
        }
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "multiseller"){
    $status = $_REQUEST['status'];
    $data = array("Multi_Seller"=>$Multi_Seller);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "add_plan"){
    $data = array(
        "plan_name" => $plan_name,
        "price" => $plan_price,
        "plan_duration" => $plan_duration,
    );
    $planprice=selectQuery(VENDORPLAN,"*","isDel= '0'");
    if(count($planprice) >= 4){
        echo 2;
    }
    else{
        $check_exist =   selectQuery(VENDORPLAN,"*","isDel= '0' and plan_name= '".$plan_name."'");
        if(count($check_exist)){
            echo 3;
        }
        else {
            $pdate = insertQuery(VENDORPLAN, $data);
            if ($pdate) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }
}

if($action == "del_plan"){
    $planid=$_POST['planid'];
    $data=array('isDel'=>'1','isActive' => '0');
    $check_exist =   selectQuery( VENDOR." as v  join ".VENDORPLANSELECTED."   as vp  on v.plan = vp.sel_id","*","v.isDel = '0' and vp.plan= '".$planname."' ");
 
    if(count($check_exist)){
            echo 2;
        }
        else {
    $que=updateQuery(VENDORPLAN,$data,"p_id=".$planid);
    if($que){
        echo 1;
    }
    else{
        echo 0;
    } 
}  
}

if($action == "login_authentication"){
    
    $data = array("admin_authentication"=>$admin_login_authentication , "vendor_authentication" => $vendor_login_authentication, "user_authentication" => $user_login_authentication );
 $pdate = updateQuery(CONFIG, $data, "id= 1");
 if ($pdate) {
     echo 1;
 } else {
     echo 0;
 }

}


if($action == "upload image"){
     include("../../cropimg/create-thumbnail.php");
    include("../../cropimg/commonfunctions.php");
    $imgs_location=$_POST['imgs_location'];
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path=$_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required'];  $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height=$_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width=$_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width'];   $thumb5width=$_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path=$_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path'];   $thumb5path=$_POST['thumb5_path'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fname = "logo.".$ext;
        if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fname)) {
        throw new Exception($_FILES["avatar"]["error"]);
        }else{
            if($crop_enabled==0){
                list($width, $height, $type, $attr) = getimagesize($target_path.$fname);
                if($default_image_width<$width){
                $dest0=$target_path.$fname;
                if($width>$height)
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                else if($width<$height)
                    createThumbnail($target_path.$fname, $dest0, "", $default_image_height);
                else
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                }
            }
            $data0=array("logo"=>$fname);
			updateQuery(CONFIG, $data0, "id= '1'");

            if($thumbnail_required){
               if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
           echo "Upload Success";
        }
    }catch(exception $e){  echo 'Upload Failed :File did not upload :' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]; }
}

if($action == "upload favicon"){
     include("../../cropimg/create-thumbnail.php");
    include("../../cropimg/commonfunctions.php");
    $imgs_location=$_POST['imgs_location'];
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path=$_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required'];  $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height=$_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width=$_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width'];   $thumb5width=$_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path=$_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path'];   $thumb5path=$_POST['thumb5_path'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fname = "favicon.".$ext;
        if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fname)) {
        throw new Exception($_FILES["avatar"]["error"]);
        }else{
            if($crop_enabled==0){
                list($width, $height, $type, $attr) = getimagesize($target_path.$fname);
                if($default_image_width<$width){
                $dest0=$target_path.$fname;
                if($width>$height)
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                else if($width<$height)
                    createThumbnail($target_path.$fname, $dest0, "", $default_image_height);
                else
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                }
            }
            $data0=array("favicon"=>$fname);
			updateQuery(CONFIG, $data0, "id= '1'");

            if($thumbnail_required){
               if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }

            $folder_path = "../../img/favicon";
                $files = glob($folder_path.'/*');

                // Deleting all the files in the list
                foreach($files as $file) {
                   if(is_file($file))
                    // Delete the given file
                    unlink($file);
                }
            require '../../cropimg/favicon-generator/vendor/autoload.php';

                $fav = new FaviconGenerator('../../img/projectimage/favicon.png');

                $fav->setCompression(FaviconGenerator::COMPRESSION_VERYHIGH);

                $fav->setConfig(array(
                    'apple-background'    => FaviconGenerator::COLOR_TEAL,
                    'apple-margin'        => 15,
                    'android-background'  => FaviconGenerator::COLOR_TEAL,
                    'android-margin'      => 15,
                    'android-name'        => SITENAME,
                    'android-url'         => SITEURL,
                    'android-orientation' => FaviconGenerator::ANDROID_PORTRAIT,
                    'ms-background'       => FaviconGenerator::COLOR_TEAL,
                ));

               $fav->createAllAndGetHtml();
           echo "Upload Success";
        }
    }catch(exception $e){  echo 'Upload Failed :File did not upload :' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]; }
}

if($action == "upload ogimage"){
     include("../../cropimg/create-thumbnail.php");
    include("../../cropimg/commonfunctions.php");
    $imgs_location=$_POST['imgs_location'];
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path=$_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required'];  $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height=$_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width=$_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width'];   $thumb5width=$_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path=$_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path'];   $thumb5path=$_POST['thumb5_path'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fname = "ogimage.".$ext;
        if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fname)) {
        throw new Exception($_FILES["avatar"]["error"]);
        }else{
            if($crop_enabled==0){
                list($width, $height, $type, $attr) = getimagesize($target_path.$fname);
                if($default_image_width<$width){
                $dest0=$target_path.$fname;
                if($width>$height)
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                else if($width<$height)
                    createThumbnail($target_path.$fname, $dest0, "", $default_image_height);
                else
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                }
            }
            $data0=array("og_image"=>$fname);
			updateQuery(CONFIG, $data0, "id= '1'");

            if($thumbnail_required){
               if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
           echo "Upload Success";
        }
    }catch(exception $e){  echo 'Upload Failed :File did not upload :' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]; }
}

if ($action == "shipping_gateway") {
     require_once "../../classes/shiprocket.php";
     $username = $api_user; $pasword=$api_pwd;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    if($token == ""){
        echo "3";
    }else {
    $data = array(
        "shippingPanel_username" => $api_user,
        "shippingPanel_password" => $api_pwd,
        "shippingBy"=>$shipby
    );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
 }
}

if($action == "add_Footer_script"){

    $data = array('name' => $scriptname , 'vesrion' => $scriptversion  , 'script' => addslashes($script_text));
    $pdate =insertQuery(FOOTERSCRIPT, $data);
    if ($pdate) {
        echo 1;
    } else {
        echo 0;
    }
}

if($action == "del_Footer_script"){
     $que = deleteQuery(FOOTERSCRIPT, "id=".$id);
    if ($que){ echo 1; } else{ echo 0; }
}

if($action == "footer_Script_statuschnage"){
    $data = array('isActive'=>$status,);
   
    $que = updateQuery(FOOTERSCRIPT,$data,"id=".$uid);
    if($que){ echo 1; } else{ echo 0; }
}


if($action == "priority_script"){
    $str = $_REQUEST['str'];
    $script_ids = explode(",", $str);
    if ($str != ""){
        for ($i = 0; $i < count($script_ids); $i++){
        $data = array('priority' => $i + 1);
            $update = updateQuery(FOOTERSCRIPT, $data, "id=" .$script_ids[$i]);
            if($update){ echo 1; } else{ echo 0; }
        }
    }
}

if($action=="add_domain_records"){
    include_once("../../classes/surunapi.php");
    $srn=new surun();
    $type=$_POST['rectype'];
     $parse = parse_url(SITEURL);
    $domainName=str_replace("www.","",$parse['host']);


    if($type=="MX"){
       $host=$_POST['mxhost']; $provider=$_POST['mxprovider']; $priority=$_POST['mxpriority']; $ttl=$_POST['mxttl'];
       $port=""; $weight=""; $flags=""; $tag="";
       $data=array("domain"=>$domainName, "type"=>$type,"host"=>$host , "val"=>$provider.(substr($provider,-1)=="."?"":"."),"priority"=> $priority ,"port"=> $port,"weight"=> $weight ,"flags"=> $flags ,"tag"=> $tag ,"ttl"=> $ttl);
       $res= $srn->addrecords($data);
       if($res['id']){
           echo 1;
       }else{
           echo 0;
       }

    }else if($type=="TXT"){
        $host=$_POST['txthost'];  $val=$_POST['txtval']; $ttl=$_POST['txtttl'];
        $port=""; $weight=""; $flags=""; $tag=""; $priority="";
       $data=array("domain"=>$domainName, "type"=>$type,"host"=> $host,"val"=> $val, "priority"=>$priority ,"port"=> $port,"weight"=> $weight ,"flags"=> $flags ,"tag"=> $tag ,"ttl"=> $ttl);
       $res=$srn->addrecords($data);

       if($res['id']){
           echo 1;
       }else{
           echo 0;
       }
    }else if($type=="SRV"){
          $host=$_POST['srvhost'];  $redirectedTo=$_POST['srvredir']; $port=$_POST['srvport']; 
         $weight=$_POST['srvweight']; $flags=""; $tag=""; $priority=$_POST['srvpriority'];  $ttl=$_POST['srvttl'];
        $data=array("domain"=>$domainName,"type"=> $type,"host"=> $host,"val"=> $redirectedTo,"priority"=> $priority ,"port"=> $port,"weight"=> $weight ,"flags"=> $flags ,"tag"=> $tag ,"ttl"=> $ttl);
        $res= $srn->addrecords($data);

         if($res['id']){
           echo 1;
       }else{
           echo 0;
       }
    }

}

if($action=="delete_domain_records"){
    include_once("../../classes/surunapi.php");
    $srn=new surun();
    $id=$_POST['recid'];
     $parse = parse_url(SITEURL);
    $domainName=str_replace("www.","",$parse['host']);

       $data=array("domain"=>$domainName, "id"=>$id);
       $res= $srn->deleterecords($data);

    echo 1;

}
if($action=="oneSignalStatus"){
    $status = $_REQUEST['pushstat'];
    $data = array("isActive_oneSignal" => $status);
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    if($status==0){
        $datam=array("isActive"=>"0");
        updateQuery(ADMINMENU,$datam,"menu_name='Push Notification' AND parent_id=0");
    }else{
        $getsignal=selectQuery(CONFIG,"oneSignal_appId,oneSignal_apiKey","1");
        if($getsignal[0]['oneSignal_appId']!=""&&$getsignal[0]['oneSignal_apiKey']!=""){
           $datam=array("isActive"=>"1");
            updateQuery(ADMINMENU,$datam,"menu_name='Push Notification' AND parent_id=0");
        }

    }
    echo 1;
}

if($action=="oneSignalSUpdate"){
    $api_user = $_REQUEST['appid'];
    $api_pwd = $_REQUEST['apikey'];
     $data = array(
        "oneSignal_appId" => $api_user,
        "oneSignal_apiKey" => $api_pwd,
    );
    $pdate = updateQuery(CONFIG, $data, "id= 1");
    $datam=array("isActive"=>"1");
    updateQuery(ADMINMENU,$datam,"menu_name='Push Notification' AND parent_id=0");
    echo 1;
}

if($action=="whatsapp_message"){       
    $wp_number = $_POST['wp_number'];
    $country_code = $_POST['country_code'];
    $wp_button = ($_POST['wp_button']!=""?$_POST['wp_button']:"Whatsapp");
    $wp_message= ($_POST['wp_message']!=""?$_POST['wp_message']:"Hi, I am interested in product, please send me more details - {{PRODUCT}}  {{URL}}");
     $data = array(
        "wp_phone_code"=>($wp_number!=""?$country_code:""),
        "wp_number" => $wp_number,
        "wp_button_name" => $wp_button,
        "wp_message" => $wp_message,
    );
    $pdate = updateQuery(CONFIG, $data, "id= 1");

    echo 1;
}

if($action=="fraudDetectionStatus"){
    $status = $_REQUEST['pushstat'];
    $data = array("fraud_detection" => $status);
     updateQuery(CONFIG, $data, "id= 1");
  
    echo 1;
}

if($action=="fraudUpdate"){
    $api_secrete = $_REQUEST['apisecrete'];
    $api_key = $_REQUEST['apikey'];
     $data = array(
        "fraud_apikey" => $api_key,
        "fraud_apisecrete" => $api_secrete,
    );
    updateQuery(CONFIG, $data, "id= 1");
   
    echo 1;
}
?>
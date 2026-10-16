<?php include("../includes/configuration.php");
if($action == "getoffer_details"){
$getoffer = selectQuery(OFFER,"*","offer_id='".$_REQUEST['img_id']."'"); ?>
<div class="offer-description">
    <h1 class="h4"><?php if($getoffer[0]['offer_name'] == ""){ echo "NA"; } else{ echo $getoffer[0]['offer_name']; }; ?></h1>
    <ul class="list-unstyled offer-details">
        <li><?php if($getoffer[0]['offer_valid_from'] !=""){ ?> Start From : <?php echo date("d-m-Y ", strtotime($getoffer[0]['offer_valid_from'] )) ; ?><? } ?></li>
        <li><?php if($getoffer[0]['offer_valid_to'] !=""){ ?> End Date : <?php echo date("d-m-Y ", strtotime($getoffer[0]['offer_valid_to'])); ?><? } ?></li>
    </ul>
    <div><?php if($getoffer[0]['offer_info'] == ""){ echo "NA"; } else{ echo $getoffer[0]['offer_info']; } ?></div>
</div>
<?php }
if($action == "subscribe_user"){
    $emailsub = $_REQUEST['emailsub'];
    $subscription_session = $_REQUEST['subscription_session'];
    if($subscription_session == $_SESSION['createnumforsub']){
        $result1 = selectQuery(SUBSCRIBE,"*"," email ='".$emailsub."'");
        if(count($result1)){ 
            echo 1;
        } else { 
            $data = array('email'=>$emailsub, 'subscribe'=>'1', 'date' =>date("Y-m-d"),);
            $insert = insertQuery(SUBSCRIBE,$data);
            if($insert){
                $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME,'smssitename' => SMSSITENAME,'user_email' =>$emailsub,'subscription_id' => base64_encode($insert),);
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
                $user_email = selectQuery(EMAILTEMPLATE,"subject,body","type='User Subscribe' and  mail_to= 'User' "); 
                $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='User Subscribe' and  mail_to= 'Admin' "); 
                $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
                $body_user = convertemailstr($replacement_array,$user_email[0]['body']);
                $sentmail = mail($emailsub, $subject_user, $body_user, $headers);   
                $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
                $sentmail_admin = mail(MAIN_ADMIN, $subject_admin, $body_admin, $headers);   
                if(SMS_SYSTEM=="ON"){    
                    $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='User Subscribe' and  sms_to = 'Admin' ");
                    $arr = explode(",",ADMINCONTACT);
                    for($k=0;$k<sizeOf($arr);$k++){
                        $tempmob = $arr[$k];
                        $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text']);
                        $templateId= $admin_sms[0]['templateId'];
                        $sms1 = sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                        $id1 = (unserialize($sms1));
                        $msid1 = $id1['data']['0']['id'];
                        $status1 = $id1['data']['0']['status'];
                        $datasms1 = array("msg_id"=>$msid1, "msg_type"=>"Subscription SMS To Admin", "user_name"=>"Admin", "mobile_no"=>$tempmob, "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status1,);
                        $insert1 = insertQuery(SMS,$datasms1);
                    }
                }
                if($sentmail){ echo 2; } else{ echo "not"; }
            }
        }
    } else{  echo 4; }
}
if($action == "unsubscribe"){
    $sub_id1 = $_REQUEST['sub_id1'];
    $data = array( "subscribe"=>'0', "unsubscribedate" =>date("Y-m-d"));
    $update=updateQuery(SUBSCRIBE,$data,"sub_id=".$sub_id1);
    if($update){ echo 1; }else{ echo 0; }
}
if($action == "update_user_basic"){
    $data = array('u_fname'=>$uname,'u_lname'=>$ulname,'u_gender' => $gender,'u_mobile' => $umbl,);
    $update = updateQuery(BUYER,$data,"u_id=".$uid);
    if($update)
    echo "1";
    else
    echo "0";
}
if($action == "update_tax_details"){
    $data = array('company_address'=>$company_add,'company_name'=>$company_name,'tax_no' => $tax_no,);
    $update = updateQuery(BUYER,$data,"u_id=".$uid);
    if($update)
    echo "1";
    else
    echo "0";
}
if($action == "change_password"){
    $uid = $_REQUEST['uid'] ;
    $oldpwd = $_REQUEST['oldpwd'] ;
    $password = $_REQUEST['password'];
    $encrypt = password_encrypt($password);
    $password1 = $_REQUEST['password1'];
    $encode= password_encrypt($oldpwd);
    $userdata = selectQuery(BUYER,"*","u_id=".$uid);
    $dbpass = $userdata[0]['password'];
    if($encode == $dbpass){
    $data = array('password'=> $encrypt,);
    $update = updateQuery(BUYER,$data,"u_id=".$uid);
    if($update)
    echo "2";
    else
        echo "3";
    } else{
        echo "0";
    }
}

if($action == "upload image"){
    include("../cropimg/create-thumbnail.php");
    include("../cropimg/commonfunctions.php");
    $checkpreviusimage= selectQuery(BUYER,"profile_pic","u_id=".$_SESSION['reguser']);
    $imgs_location = $_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $get_name = selectQuery(BUYER,"u_fname","u_id = '".$_SESSION['reguser']."'");
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required'];  $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height=$_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width=$_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width=$_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path=$_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path=$_POST['thumb5_path'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
        if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
            if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
        }
        try{
            $fname = replace_nonletter($get_name[0]['u_fname'])."-".rand(100,999).".".$ext;
            if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fname)){
                throw new Exception($_FILES["avatar"]["error"]);
            }else{
                if($crop_enabled==0){
                    list($width, $height, $type, $attr) = getimagesize($target_path.$fname);
                    if($default_image_width<$width){
                        $dest0 = $target_path.$fname;
                        if($width>$height)
                        createThumbnail($target_path.$fname, $dest0, $default_image_width);
                        else if($width<$height)
                        createThumbnail($target_path.$fname, $dest0, "", $default_image_height);
                        else
                        createThumbnail($target_path.$fname, $dest0, $default_image_width);
                    }
                }
                $data0=array("profile_pic" => $fname);
                $update=updateQuery(BUYER,$data0,"u_id=".$_SESSION['reguser']);
                if($thumbnail_required){
                    if($thumb1width){ $thumb1store = getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
                    if($thumb2width){ $thumb2store = getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
                    if($thumb3width){ $thumb3store = getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
                    if($thumb4width){ $thumb4store = getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
                    if($thumb5width){ $thumb5store = getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
                }
                if($checkpreviusimage[0]['profile_pic'] != ""){
                    deleteimg('buyer profile',$checkpreviusimage[0]['profile_pic'] );
                }
                echo "Upload Success";
            }
        }catch(exception $e){ echo 'Upload Failed :File did not upload :' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]; }
}
if($action== "Delete_profile_image"){
    include("../cropimg/commonfunctions.php");
    $data0 = array("profile_pic" => "");
    $update = updateQuery(BUYER,$data0,"u_id=".$profile_id);
    if($update){
        deleteimg('buyer profile',$_REQUEST['img_name'] );
        echo "1";
    } else{echo "0";}
}
if($action == "update_bank"){
    $data = array ("bank_name" => $bank, "account_name" => ucfirst($account_name), "account_number" => $account_number, "ifsc_code"  => $ifsc_code, "upi_id" => $Upi_id,
    );
    $update = updateQuery(BUYER,$data,"u_id=".$uid);
    if($update)
    echo "1";
    else
    echo "0";
}

if($action == "Delete_adress"){
    $del_adress = deleteQuery(ADDRESS,'id="'.$adress_id.'"');
    if($del_adress){ echo "1"; } else { echo "0"; }
}
if($action == "get_adress_details"){
$get_adress = selectQuery(ADDRESS,"*",'id="'.$adress_id.'"'); ?>
<form class="form">
    <input type="hidden" id="adress_id" value="<?php echo $adress_id ?>">  
    <div class="form-group row mb-2 mb-sm-3 mb-md-1">
        <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field pt-0">Address Type</label>
        <div class="col-sm-9 col-md-12">
            <div class="custom-control custom-radio custom-control-inline">
                <input class="adress_type custom-control-input" id="home" type="radio" name="adress_type" value="Home" <?php  if($get_adress[0]['address_type'] == "Home"){ echo "checked"; } ?> > 
                <label class="custom-control-label" for="home">Home </label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="adress_type custom-control-input" id="office" type="radio" name="adress_type" value="Office" <?php if($get_adress[0]['address_type'] == "Office"){ echo "checked"; } ?>>
                <label class="custom-control-label" for="office">Office</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row mb-2 mb-sm-3 mb-md-1">
                <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field">Full Name</label>
                <div class="col-sm-9 col-md-12"><input type="text" class="form-control fullname" id="fullname" onkeyup="fullnamechk('fullname')" maxlength="50" autocomplete="off" value="<?php echo $get_adress[0]['address_name'] ?>"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row mb-2 mb-sm-3 mb-md-1">
                <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field">Mobile No</label>
                <div class="col-sm-9 col-md-12"><input type="text" class="form-control mobile" id="mobile" onkeyup="mobnumbercheck('mobile')" maxlength="10" autocomplete="off" value="<?php echo $get_adress[0]['mobile_number'] ?>"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row mb-2 mb-sm-3 mb-md-1">
                <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field">Country</label>
                <div class="col-sm-9 col-md-12">
                    <select id="country" class="form-control"> 
                        <?php $get_country = selectQuery(COUNTRY,"name"," id <> '' order by name asc");
                        for($i=0;$i<count($get_country);$i++){ ?>
                        <option value="<?php echo $get_country[$i]['name'] ?>" <?php if($get_country[$i]['name'] == $get_adress[0]['country']){ echo "selected"; } ?>><?php echo $get_country[$i]['name']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row mb-2 mb-sm-3 mb-md-1">
                <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field">Pincode</label>
                <div class="col-sm-9 col-md-12"><input type="text" class="form-control pincode" onchange="getpincode()" maxlength="6" id="pincode" onkeyup="numbercheck('pincode')" autocomplete="off"  value="<?php echo $get_adress[0]['pincode'] ?>"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row mb-2 mb-sm-3 mb-md-1">
                <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field">Address</label>
                <div class="col-sm-9 col-md-12"><textarea class="form-control address" maxlength="200" autocomplete="off"><?php echo $get_adress[0]['address'] ?></textarea></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">  
            <div class="form-group row mb-2 mb-sm-3 mb-md-1">
                <label class="col-sm-3 col-md-12 col-form-label">Location</label>
                <div class="col-sm-9 col-md-12"><input type="text" class="form-control location" maxlength="50" autocomplete="off" value="<?php echo $get_adress[0]['landmark'] ?>"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row mb-2 mb-sm-3 mb-md-1">
                <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field">City</label>
                <div class="col-sm-9 col-md-12"><input type="text" class="form-control city" readonly autocomplete="off" value="<?php echo $get_adress[0]['city'] ?>"></div>
            </div> 
        </div>
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-3 col-md-12 col-form-label cc-mandatary-field">State</label>
                <div class="col-sm-9 col-md-12"><input type="text" class="form-control state" readonly autocomplete="off" value="<?php echo $get_adress[0]['state'] ?>"></div>
            </div>
        </div>
    </div>
    <div class="adrmsg"></div>
    <div class="row"><div class="col-sm-9 offset-sm-3 col-md-12 offset-md-0"><button type="button" class="btn btn-primary" onclick="editDelivery()">Edit Adress</button></div></div>
</form>             
<?php } ?>
<?php if($action == "editAddress"){
    $adress_type = $_POST['adress_type']; $country =  $_POST['country']; 
    $user = $_POST['user'];$fullname=$_POST['fullname'];$mobile=$_POST['mobile'];$pincode=$_POST['pincode'];$address=$_POST['address'];$location = $_POST['addlocation'];$city = $_POST['city'];$state = $_POST['state'];
    $adress_exist = selectQuery(ADDRESS, "id", "user_id=" . $user." and id <>" . $adress_id." and address_name='".ucwords($fullname)."'  and mobile_number='".$mobile."'  and address='".$address."'  and landmark='".$location."'  and city='".$city."'  and state='".$state."'  and pincode='".$pincode."'");
    if($adress_exist){
        echo 2;
    } else{
        $data = array("address_name"=>ucwords($fullname),"mobile_number"=>$mobile,"address"=>$address,"landmark"=>$location,"city"=>$city,"state"=>$state,"pincode"=>$pincode,"address_type" => $adress_type , "country" => $country );
        $update = updateQuery(ADDRESS,$data,"id=".$adress_id);
        if($update)
        echo "1";
        else
        echo "0";
    }
} ?>
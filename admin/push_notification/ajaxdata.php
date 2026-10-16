<?php
include "../../includes/configuration.php";
include("../../cropimg/create-thumbnail.php");
include("../../cropimg/commonfunctions.php");
$action=$_POST['action'];

if($action=="sendnotification"){
$isActive=$getconfigdetails[0]['isActive_oneSignal'];
$appid=$getconfigdetails[0]['oneSignal_appId'];
$apikey=$getconfigdetails[0]['oneSignal_apiKey'];
$not_title=$_POST['not_title']; $not_message=$_POST['not_message']; $not_url=$_POST['not_url'];   $img_url=$_POST['img_url'];
$notifschedule=$_POST['notifschedule'];$timelive=$_POST['timelive'];
 $content      = array( "en" => $not_message );
 $heading      = array( "en" => $not_title );
    $hashes_array = array();

       array_push($hashes_array, array( "id" => "like-button", "text" => "Like","icon" => "http://i.imgur.com/N8SN8ZS.png", "url" => SITEURL));


    $fields = array(
        'app_id' => $appid,
        'included_segments' => array( 'All' ),
       // 'data' => array( "foo" => "bar"),
        'headings' =>$heading,
        'contents' => $content,
        'web_buttons' => $hashes_array,
        'ios_sound'=>"default",
        'wp_wns_sound'=>"default",
        "ttl"=>259200                     /*3 days - 259200   28 days - 2419200 */
    );
     if($not_url!=""){$fields['web_url']=$not_url;$fields['app_url']=$not_url; $json=array("app_url"=>"app://".$not_url);/*$fields['data']=$json;*/$fields['data']=$json; }
     if($img_url!=""){$fields['big_picture']=$img_url; $fields['chrome_web_image']=$img_url; $fields['chrome_big_picture']=$img_url; }
     if($notifschedule=="After"&&$timelive!=""){
       $time=date("Y-m-d H:i:s",strtotime($timelive));
       $utc=gmdate("l jS \of F Y, h:i:s A e",strtotime($time));
       $fields['send_after']= $utc;
     }
      $fields = json_encode($fields);


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic '.$apikey
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    $return["allresponses"] = $response;
    $return = json_encode($return);

    $data = json_decode($response, true);
    //print_r($data);
    $error=$data['errors'];
    $id = $data['id'];
    if($id){echo 1;}else{
        print_r($error);
    }
    //print_r($id);

    /*print("\n\nJSON received:\n");
    print($return);
    print("\n");
    */
}

if($action=="upload image"){
    $notification_id = $_POST['notification_id'];
    $imgs_location = $_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $get_name = selectQuery(BLOG,"title ","id = '".$blog_id."'");
    $crop_enabled = $_POST['crop_enabled']; $thumbnail_required = $_POST['thumbnail_required']; $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height = $_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width = $_POST['thumb2_width']; $thumb3width = $_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width = $_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path = $_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path = $_POST['thumb5_path'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fname = "notification-".rand(100,999).".".$ext;
        if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fname)){
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

            if($thumbnail_required){
               if($thumb1width){ $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){ $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
          $data=array("status"=>"success","image"=>"https://".$path."/".$fname,"imagesrc"=>$target_path.$fname);
        }
    }catch(exception $e){  $data=array("status"=>"failed","message"=>'Upload Failed :File did not upload :' . $e->getMessage());   }
    }else if(!isset($_FILES['avatar'])){
       $data=array("status"=>"failed","message"=>"Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']]);
    }else{  $data=array("status"=>"failed","message"=>"Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]);  }
    echo json_encode($data,true);
}
?>
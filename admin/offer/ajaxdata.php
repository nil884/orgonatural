<?php include ("../../includes/configuration.php");
include("../../cropimg/create-thumbnail.php");
include("../../cropimg/commonfunctions.php");
if($action=="upload image"){
    $imgs_location = $_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required'];  $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height = $_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width = $_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width = $_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path = $_POST['thumb2_path']; $thumb3path = $_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path = $_POST['thumb5_path'];   $webp_quality = $_POST['webp_quality'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fn = "offer-".rand(100,999);
        $fnameo =$fn.".".$ext;
        if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fnameo)){
            throw new Exception($_FILES["avatar"]["error"]);
        }else{
            if($img_extension=="webp"){

                 $fname = $fn.'.webp';
                if(createwebp($target_path,$fnameo,$fname,$webp_quality)){ //if webp image created then delete old image
                    unlink($target_path.$fnameo);
                }else{ $fname=  $fnameo;}
            }else{ $fname=  $fnameo; }

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
            $data0 = array("img"=>$fname,"inserted_date" => date("Y-m-d H:is:") );
            insertQuery(OFFER,$data0);
            if($thumbnail_required){
               if($thumb1width){ $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){ $thumb2store = getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store = getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store = getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store = getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
            echo "Upload Success";
        }
    }catch(exception $e){  echo 'Upload Failed :File did not upload :' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]; }
}
if($action == "Delete_offer"){
    $imgid = $_POST['imgid'];
    $del_img =  deleteQuery(OFFER,'offer_id="'.$offerid.'"');
    if($del_img){
        deleteimg('offer',$_REQUEST['img_name'] );
        echo "1";
    } else {echo "0";}
}
if ($action == "active_deactive"){
    $id = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data = array('isActive' => $setval);
    if($setval==0){ $data['showonhomepage']=0; }
    $que = updateQuery(OFFER, $data, "offer_id=" . $requestedid);
    if($que){echo 1; }else{echo 0;}
}
if($action == "show_on_home_page"){
    $id = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data = array('showonhomepage' => $setval);
    $que = updateQuery(OFFER, $data, "offer_id=" . $requestedid);
    if ($que){echo 1; } else{echo 0;}
}

if($action == "get_form_detail"){ 
$getoffer = selectQuery(OFFER,"*","offer_id=".$_REQUEST['offerid']);?>
<form name="offerdata">
    <div class="modal-body">
        <div class="offermsgs"></div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label cc-mandatary-field">Offer Name</label>
            <div class="col-md-10"><input type="text" id="offername" class="form-control" maxlength="100" value="<?php echo $getoffer[0]['offer_name']; ?>"/></div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Offer Link</label>
            <div class="col-md-10"><input type="text" name="offer_link" id="offer_link" class="form-control" maxlength="300" placeholder="Enter offer Link" value="<?php echo $getoffer[0]['offer_link']; ?>" ></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Start Date</label>
                    <div class="col-md-8"><input type="text" class="form-control" id="startdat" name="startdat" value="<?php echo date("d-m-Y", strtotime($getoffer[0]['offer_valid_from'])); ?>"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">End Date</label>
                    <div class="col-md-8"><input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($getoffer[0]['offer_valid_to'])); ?>" id="enddate"></div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Description</label>
            <div class="col-md-10"><textarea name="offer_info" id="offer_info" class="form-control summernote" maxlength="500" row="300" placeholder="Enter offer information(Max 500 Characters)" required><?php echo $getoffer[0]['offer_info']; ?></textarea></div>
        </div>
    </div>
    <div class="modal-footer py-2 text-right">
        <input type="hidden" name="img_id" id="img_id" required value="<?php echo $getoffer[0]['offer_id']; ?>"/>
        <input type="button" name="savedata" id="savedata" class="btn btn-primary ml-auto" value="Save" onclick="updatedata()" />
    </div>
</form>
<?php } 
if($action == "update_offer"){
    if($_REQUEST['offer_info']==""){$offer_info="";}
    else{ $offer_info=addslashes($_REQUEST['offer_info']);}
    if($_REQUEST['startdate'] != ""){ $start_date = date("Y-m-d", strtotime($_REQUEST['startdate'])); } else{ $start_date == "0";}
    if($_REQUEST['enddate'] != ""){ $end_date = date("Y-m-d", strtotime($_REQUEST['enddate']));  }  else{ $end_date == "0"; }
    $data=array("offer_name"=>addslashes($_REQUEST['offer_name']), "offer_info"=>addslashes($_REQUEST['offer_info']), "offer_link"=>$_REQUEST['offer_link'], "offer_valid_from"=> $start_date, "offer_valid_to"=> $end_date,);
    $getuser = updateQuery(OFFER,$data,"offer_id=".$_REQUEST['img_id']);
    if($getuser){ echo 1; } else{ echo 0; }
} 

if($action == "priority"){
    $str = $_REQUEST['str'];
    $image_ids = explode(",", $str);
    if ($str != ""){
        for ($i = 0; $i < count($image_ids); $i++){
        $data = array('priority' => $i + 1);
            $update = updateQuery(OFFER, $data, "offer_id=" .$image_ids[$i]);
            if($update){ echo 1; } else{ echo 0; }
        }
    } 
}

?>
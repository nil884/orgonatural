<?php
include("../../includes/configuration.php");
include("../../classes/product.php");
include("../../cropimg/create-thumbnail.php");
include("../../cropimg/commonfunctions.php");
$prod = new Product();
$action = $_REQUEST['action'];
 if($action=="add_category"){
    $category = $_REQUEST['category'];
    $cattype = $_REQUEST['cattype'];
    if($cattype == "Parent"){ $parentid_id =0; } else { $parentid_id = $_REQUEST['parentid_id']; }
    if($category!=""){
        $chkdup = selectQuery(PRODCAT,"count('id') as total_count","cat_name='".ucwords($category) ."' and type= '".$cattype."'  and parent_id= '".$parentid_id."'   ");
        if($chkdup[0]['total_count'] >0){ echo "Exist"; }
        else{
          $data=array("type" => $cattype, "cat_name"=>ucwords(addslashes($category)), "parent_id" => $parentid_id,"page_title" => ucwords(addslashes($category)), "keywords" => ucwords(addslashes($category)), "metadescription" => ucwords(addslashes($category)));
            $indata = insertQuery(PRODCAT,$data);
            if($indata){
                $parent = $parentid_id;
                $name = ucwords(addslashes($category));
                $url = $prod->createCatURL($name,$parent);
                $data_url = array('url_title' => $url);
                $update = updateQuery(PRODCAT,$data_url,"id='".$indata."'");
                echo $indata;
            }
            else{ echo "Not"; }
        }
    } else{ echo "Invalid"; }
}
if($action=="edit_category"){
    $catid = $_REQUEST['catid'];
    $category = $_REQUEST['edit_cat_name'];
    $cattype = $_REQUEST['cattype'];
    if($category!=""){
        if($cattype == "Parent"){ $parentid_id =0; } else { $parentid_id = $_REQUEST['parentid_id']; }
        $chkdup=selectQuery(PRODCAT,"count('id') as total_count","cat_name='".ucwords($category) ."'  and type = '".$cattype."' and parent_id= '".$parentid_id."' and id <> '".$catid."' ");
        if($chkdup[0]['total_count']){ echo "Exist"; }
        else{
            $getCatDetails = selectQuery(PRODCAT,"cat_name,template,excelFile,type","id=".$catid);
            $catName = $getCatDetails[0]['cat_name'];$catType=$getCatDetails[0]['type'];$catTemplate=$getCatDetails[0]['template'];
            $catExcelFile = $getCatDetails[0]['excelFile'];

            $data = array("type" => $cattype,  "cat_name"=>ucwords(addslashes($category)), "page_title" => addslashes($pagetitle), "keywords" => addslashes($keywords), "metadescription" => addslashes($metadesc));
             if(ucwords($category)!=$catName){
                $parent = $parentid_id;
                $name = ucwords(addslashes($category));
                $url = $prod->createCatURL($name,$parent);
                $data['url_title']= $url;
             }
             $indata = updateQuery(PRODCAT,$data,"id='".$catid."'");
            if($indata){
                if($catType=="Sub"&&ucwords($category)!==$catName){
                    $tablename = "template_".$catid."_".str_replace(" ","",replace_nonletter(trim($category)));
                    $excelname = $tablename.".xlsx";
                    rename("../../templatexcels/".$catExcelFile,"../../templatexcels/".$excelname);
                    renameTable($catTemplate,$tablename);
                    $dataup = array("template" => $tablename, "excelFile"=>$excelname,);
                    updateQuery(PRODCAT,$dataup,"id=".$catid);
                }
                echo "1";
            }else{ echo "Not"; }
        }
    } else{ echo "Invalid"; }
}

if($action == "Active_deactive"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('isActive' => $status);
    $upategetsubcat = updateQuery(PRODCAT, $data, "id=" . $requestid);
    if ($upategetsubcat) { echo 1; } else{ echo 0; }
}
 if($action == "getedit_form"){
    $catid = $_REQUEST['catid'];
    $get_cat_detail = selectQuery(PRODCAT,"id,cat_name,parent_id,type,page_title,metadescription,keywords","id=".$catid." ");




    ?>
    <div class="modal-content" id="edit_modal_content">
    <div class="modal-header">
        <h4 class="modal-title" id="modal_title">Edit SEO Details - <?php echo $get_cat_detail[0]['cat_name']; ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="seo_alert_msgs"></div>
        <div class="form-group">
            <div class="row">
                <input type="hidden" id="parentid_id_edit" value="<?php echo $get_cat_detail[0]['parent_id'] ?>">
                <label class="col-md-3 col-form-label cc-mandatary-field">Name</label>
                <div class="col-md-9"><input type="text" class="form-control" id="edit_cat_name" maxlength="50" value="<?php echo $get_cat_detail[0]['cat_name'] ?>"></div>
            </div>
        </div>
        <div class="form-group" >
            <div class="row">
                <label class="col-md-3 col-form-label cc-mandatary-field">Page Title</label>
                <div class="col-md-9"><input type="text" name="pagetitle" id="pagetitle" class="form-control" value="<?php  echo $get_cat_detail[0]['page_title'];  ?>"  maxlength="70" placeholder="Max. Character Limit 70"/></div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-md-3 col-form-label cc-mandatary-field">Meta Description</label>
                <div class="col-md-9"><textarea name="metadesc" id="metadesc" class="form-control" maxlength="160" placeholder="Max character limit 160" ><?php echo $get_cat_detail[0]['metadescription']; ?></textarea></div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-md-3 col-form-label cc-mandatary-field">Meta Keywords</label>
                <div class="col-md-9">
                <textarea name="keywords" id="keywords" class="form-control" maxlength="255" placeholder="Max Character Limit 255"><?php  echo $get_cat_detail[0]['keywords'];  ?></textarea>
                </div>
            </div>
        </div>

    </div>
        <div class="modal-footer py-2 text-right">
            <div class="ml-auto">
            <button type="button" class="btn btn-primary" onclick="edit_cat('<?php echo $catid; ?>','<?php echo $get_cat_detail[0]['type']; ?>')" id="edit_cat_btn">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
        </div>
<? }
if($action == "priority"){
    $str = $_REQUEST['str'];
    $master_cat_ids = explode(",", $str);
    if ($str != ""){
        for ($i = 0; $i < count($master_cat_ids); $i++){
            $data = array('priority' => $i + 1);
            $update = updateQuery(PRODCAT, $data, "id=" .$master_cat_ids[$i]);
            if($update){ echo 1; } else{ echo 0;}
        }
    }
}
if($action == "get_category"){ ?>
    <option value=""> - Select <?php echo $type ?> category - </option>
    <?php $getcat=selectQuery(PRODCAT,"id,template,cat_name"," parent_id=".$cat." order by cat_name ASC");
    if(count($getcat)){
    for($i=0;$i<count($getcat);$i++) { ?>
    <option value="<?php echo $getcat[$i]['id']; ?>"  data-id= "<?php echo $getcat[$i]['template'];?>"><?php echo $getcat[$i]['cat_name']; ?> </option>
    <? } } else{ ?>
     <option value="">Not Found</option>
   <?php } ?>
<?php }
if($action == "get_template_field"){
    $fld = $_REQUEST['fld'];
    $arr = explode(",",$fld); ?>
    <div class="row">
        <div class="col-md-7 col-sm-12 col-xs-12 selectedattr">
            <div class="row">
                <input type="hidden" name="cnt" id="cnt" value="<?php echo count($arr); ?>">
                <?php for($i=0;$i<count($arr);$i++) {
                $sepratetemplte_atr =  explode("%*%",$arr[$i]); ?>
                <div class="col-md-4">
                    <div class="cc-col-pad-view">
                        <input type="text" name="attr<?php echo $i; ?>" id="attr<?php echo $i; ?>" class="attr form-control cc-display-none" value="<?php echo $sepratetemplte_atr['1']; ?>" readonly required>
                        <?php echo $sepratetemplte_atr['0']; ?> (<? echo  getAttributeCat($sepratetemplte_atr['1'] )  ?>)
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="attrtype<?php echo $i; ?>" id="attrtype<?php echo $i; ?>" class="mb-2 attrtype form-control" required onchange="getval(<?php echo $i;?>);">
                        <option value="">- Select Type -</option>
                        <option value="INT">Numeric</option>
                        <option value="VARCHAR" selected="selected">Alphanumeric</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="attrsize<?php echo $i; ?>" id="attrsize<?php echo $i; ?>" class="numberinput attrsize form-control" placeholder="Field Size" maxlength="4" required  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" value="100" max="5000">
                </div>
                <? } ?>
            </div>
        </div>
    </div>
    <script>
    function getval(val){
        var attrtype = $("#attrtype"+val).val();
        if(attrtype == "INT"){ $("#attrsize"+val).val("10"); }
        if(attrtype == "VARCHAR"){ $("#attrsize"+val).val("100"); }
    }
    </script>
<?php }
if($action == "add_attr_edit"){
$fld = $_REQUEST['fld'];
$fldsize = $_REQUEST['fldsize'];
$fldtype = $_REQUEST['fldtype'];
$action = $_REQUEST['action'];
$cat = $_REQUEST['cat'];
$getcat = selectQuery(PRODCAT,"template","id=".$cat);
$tablename = $getcat[0]['template'];
$retval = alterQuery($tablename,"ADD ".str_replace(" ","",$fld)." ".$fldtype."(".$fldsize.")");
if($retval){
    include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
    include_once('../../PHPExcel/excelfunctions.php');
        $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $excel=createExcel($url,$tablename);
        $data12=array("excelFile"=>$excel);
        $insertsbcat=updateQuery(PRODCAT,$data12,"template='".$tablename."'");
 echo 1;} else { echo 0;}
}
if($action=="create_excel"){
    include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
    include_once('../../PHPExcel/excelfunctions.php');
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $tablename = $_POST['template'];
    $excel = createExcel($url,$tablename);
    if($excel){
        $data12 = array("excelFile"=>$excel);
        $insertsbcat = updateQuery(PRODCAT,$data12,"template='".$tablename."'");
        echo 1;
    }else{echo 0;}
}
if($action == "Delete_cat"){
    //include("../../cropimg/commonfunctions.php");
    function delsubcat($cat_id){
        $getallproduct = selectQuery(PRODINFO,"id","sub_cat = ".$cat_id);
        for($i=0;$i<count($getallproduct);$i++){
            $getimg_name = selectQuery(PRODIMG,"id,img_name",'prod_id="'.$getallproduct[$i]['id'].'"');
            for($k=0;$k<count($getimg_name);$k++){
                deleteimg('product',$getimg_name[$k]['img_name'] );
            }
            deleteQuery(PRODIMG,'prod_id="'.$getallproduct[$i]['id'].'"');
        }
        deleteQuery(PRODINFO,'sub_cat="'.$cat_id.'"');
        $gettemplte = selectQuery(PRODCAT,"template",'id="'.$cat_id.'"');
        $query = "DROP TABLE ".$gettemplte[0]['template'];
        dropQuery($query);
        deleteQuery(PRODCAT,'id="'.$cat_id.'"');
    }
    if($type == "Sub" ){ delsubcat($cat_id);  echo 1;}
    else if($type =="Master"){
        $getsubcat = selectQuery(PRODCAT,"id","parent_id=".$cat_id);
        for($i=0;$i<count($getsubcat);$i++){  $id = $getsubcat[$i]['id'];
            echo $getsubcat[$i]['id']; delsubcat($id);
        }
        deleteQuery(PRODCAT,'id="'.$cat_id.'"');
        echo 1;
    }
    else if($type == "Parent"){
        $getmaster = selectQuery(PRODCAT,"id","parent_id=".$cat_id);
        for($j=0;$j<count($getmaster);$j++){
            $getsubcat = selectQuery(PRODCAT,"id","parent_id=".$getmaster[$j]['id']);
            for($i=0;$i<count($getsubcat);$i++){
                delsubcat($getsubcat[$i]['id']);
            }
            deleteQuery(PRODCAT,'id="'.$getmaster[$j]['id'].'"');
        }
        deleteQuery(PRODCAT,'id="'.$cat_id.'"');
        echo 1;
    }
}
if($action == "Delete_attr_column"){
    $getcat = selectQuery(PRODCAT,"template","id=".$cat);
    $tablename = $getcat[0]['template'];
    if($tablename != ""){
        $retval = alterQuery($tablename,"drop ".$fld);
        if($retval){
            include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
            include_once('../../PHPExcel/excelfunctions.php');
            $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $excel = createExcel($url,$tablename);
            if($excel){
                $data12 = array("excelFile"=>$excel);
                $insertsbcat = updateQuery(PRODCAT,$data12,"template='".$tablename."'");
                echo 1;
            }
        } else{ echo 0; }
    }
}
if($action == "set_size"){
    $retval = alterQuery($template,"MODIFY ".$coloumn_name." ".$column_type." (".$column_size.")");;
    if($retval){ echo 1;} else{ echo 0; }
}



if($action=="upload image"){
    $catid = $_POST['catid'];
    $imgs_location = $_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $get_name = selectQuery(PRODCAT,"cat_name","id = '".$catid."'");

    $crop_enabled = $_POST['crop_enabled']; $thumbnail_required = $_POST['thumbnail_required']; $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height = $_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width = $_POST['thumb2_width']; $thumb3width = $_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width = $_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path = $_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path = $_POST['thumb5_path'];    $webp_quality = $_POST['webp_quality'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fn = replace_nonletter($get_name[0]['cat_name'])."-".rand(100,999);
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
            $data0 = array("img_name"=>$fname);
           $update = updateQuery(PRODCAT,$data0,"id='".$catid."'");
            if($thumbnail_required){
               if($thumb1width){ $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){ $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
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
if($action == "Delete_image"){
    $catid=$_POST['imgid'];
    $data = array("img_name" => '');
    $del_img =  updateQuery(PRODCAT,$data,"id='".$catid."'");
    if($del_img){
        deleteimg('category',$_REQUEST['img_name'] );
        echo "1";
    } else {echo "0";}
}

?>
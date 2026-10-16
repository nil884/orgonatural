<?php include ("../../includes/configuration.php");
include("../../cropimg/create-thumbnail.php");
include("../../cropimg/commonfunctions.php");
$action = $_REQUEST['action'];
if($action == "addblog"){
    $title = ucfirst(addslashes($_REQUEST['title']));
    $Summaryhtml = $_REQUEST['Summaryhtml'];
    $posted_by = $_REQUEST['posted_by'];
    $posted_date = explode ("/" , $_REQUEST['posted_date']);
    $postdate = $posted_date[2]."-".$posted_date[1]."-".$posted_date[0];
    $check = selectQuery(BLOG,"id","title='".$title."' ");
    $catname = selectQuery(BLOGCAT, "category_name", "cat_id='" . $category . "' "); 
    if(count($check)){ echo "2";}
    else{
        $data = array("title" => $title, "summary" => addslashes($Summaryhtml), "posted_date" => $postdate, "posted_by" => ucwords($posted_by), "category" => $category, "url_title" => createurltitle($catname[0]['category_name'])."/".createurltitle($title),);
        $insert = insertQuery(BLOG, $data);
        if($insert){ echo "1";} else{echo "0"; }
    }
}
if ($action == "statuschng"){
    $id = $_REQUEST['pid'];
    $action = $_REQUEST['action'];
    $setval = $_REQUEST['status'];
    $data = array('isActive' => $setval);
    $que = updateQuery(BLOG, $data, "id=" . $id);
    if($que){echo 1;}else{echo 0;}
}
if ($action == "delblog"){
    $id = $_REQUEST['pid'];
    $que = deleteQuery(BLOG, "id=" . $id);
    if ($que){
    $getblogimgs = selectQuery(BLOGIMG,"id,img_name"," blogid = '".$id."'");
    for($i=0;$i<count($getblogimgs);$i++){
        deleteQuery(BLOGIMG, "id=" . $getblogimgs[$i]['id']);
        deleteimg('blog',$getblogimgs[$i]['img_name']);
    }
    echo 1;} else{echo 0;}
}
if($action == "update"){
    $title = ucfirst(addslashes($_REQUEST['title']));
    $Summaryhtml = $_REQUEST['Summaryhtml'];
    $blogid = $_REQUEST['blogid'];
    $posted_by = $_REQUEST['posted_by'];
    $posted_date = explode ("/" , $_REQUEST['posted_date']);
    $postdate = $posted_date[2]."-".$posted_date[1]."-".$posted_date[0];
    $check = selectQuery(BLOG,"id","title='".$title."'  and id <> '".$blogid."'");
    $catname =  selectQuery(BLOGCAT, "category_name", "cat_id='" . $category . "' ");
    if(count($check)){ echo "2"; }
    else{
        $data = array("title" => $title, "summary" => addslashes($Summaryhtml), "posted_date" => $postdate, "posted_by" => ucwords($posted_by), "category" => $category, "url_title" => createurltitle($catname[0]['category_name'])."/".createurltitle($title),);
        $update = updateQuery(BLOG, $data, "id=" . $blogid);
        if($update){ echo "1";} else{ echo "0"; }
    }
}

if ($action == "updateseo"){
    $blogid = $_REQUEST['blogid'];
    $pagetitle = $_REQUEST['pagetitle'];
    $keywords = $_REQUEST['keywords'];
    $metadesc = $_REQUEST['metadesc'];
    $data = array("page_title" => addslashes($pagetitle), "keywords" => addslashes($keywords), "metadescription" => addslashes($metadesc),);
    $update = updateQuery(BLOG, $data, "id=" . $blogid);
    if($update){echo "1"; } else{  echo "0"; }
}
if ($action == "showonhome") {
    $blogid = $_REQUEST['pid'];
    $action = $_REQUEST['action'];
    $setval = $_REQUEST['status'];
    $data = array('showonhomepage' => $setval);
    $que = updateQuery(BLOG, $data, "id=" . $blogid);
    if($que){echo "1"; } else{  echo "0"; }
}
if ($action == "insertscat"){
    $cat = $_REQUEST['newcat'];
    $result = selectQuery(BLOGCAT, "category_name", "category_name='" . $cat . "' ");
    if(count($result)){ echo "1"; }
    else {
        $catname = ucfirst(addslashes($cat));
        $data = array("category_name" => $catname , "url_title" => createurltitle($catname) );
        $insert = insertQuery(BLOGCAT, $data);
        if($insert){echo "2"; }
        else{  echo "0"; }
    }
}

if($action=="upload image"){
    $blog_id = $_POST['blog_id'];
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
        $fname = replace_nonletter($get_name[0]['title'])."-".rand(100,999).".".$ext;
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
            $data0 = array("img_name"=>$fname,"blogid" => $blog_id);
            insertQuery(BLOGIMG,$data0);
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
    $imgid=$_POST['imgid'];
    $del_img =  deleteQuery(BLOGIMG,'id="'.$imgid.'"');
    if($del_img){
        deleteimg('blog',$_REQUEST['img_name'] );
        echo "1";
    } else {echo "0";}
}
if($action == "commentaction"){
    $pid = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data = array( 'isApproved'=>$setval);
    $que = updateQuery(BLOGCMNT,$data,"comment_id=".$pid);
    if($que){echo 1; }else{echo 0;}
}
if($action == "deletecomment"){
    $comment_id=$_POST['comment_id'];
    $del_comment = deleteQuery(BLOGCMNT,'comment_id='.$comment_id);
    if($del_comment){echo 1; }
    else {echo 0;}
} ?>
<? include("../../includes/configuration.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Product Category</title>
    <? include "../commoncss.php"; ?>
</head>
<body class="reload-pg">
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert alert-info mb-0">
                    <h6><b>Information</b></h6>
                    <ul class="mb-0 pl-3"><li class="mb-1">Product Heirachy = Parent Category <i class="fa fa-long-arrow-right" aria-hidden="true"></i> Master Category <i class="fa fa-long-arrow-right" aria-hidden="true"></i> Sub Category <i class="fa fa-long-arrow-right" aria-hidden="true"></i> Product</li><li class="mb-1">Use Pencil Icon To Edit SEO Details</li></ul>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title mb-2 mb-sm-0">Category Management</h5></div>
                    <div class="btn-actions-pane-right"><button type="button" onclick="openmodel(0,'','Parent','')" class="btn btn-primary btn-sm" >Add Parent Category</button></div>
                </div>
                <div class="card-body">                    
                    <div id="all_category" class="mainlist"><?php echo get_cat_tree2(0); ?></div>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
    <?php function get_cat_tree2($parent_id){
    $menu = "";
    $prod_cat = selectQuery(PRODCAT,"*","parent_id=".$parent_id."  order by priority");
    if(count($prod_cat)){
        for($i=0;$i<count($prod_cat);$i++){
            $cat_id = $prod_cat[$i]['id'];
            $cat_name = $prod_cat[$i]['cat_name'];
            $template = $prod_cat[$i]['template']; $excelfile=$prod_cat[$i]['excelFile'];
            $cattype = $prod_cat[$i]['type'];
            //$activfun = "active(".$cat_id.",'checkbox".$cat_id."','".addslashes($cattype)."')";
            $activfun = 'active("'.$cat_id.'","checkbox'.$cat_id.'","'.addslashes($cattype).'")';
            $excelfunc = 'createexcel("'.$cat_id.'","'.$template.'")';
            //$delete='delete_cat("'.$cat_id.'","Parent")';
            if($prod_cat[$i]['isActive'] == 1) { $checkvalue = "checked"; } else{ $checkvalue = ""; }
            if($cattype == "Parent"){
                $internal = "masterlist"; 
                $classd = 'parent-category'; 
                $str = "'".$cat_id."','".addslashes($cat_name)."','Master','Parent Category'";
                $add_child = '<button type="button" class="btn btn-default"  onclick ="openmodel('.$str.')"><i class="fa fa-plus" aria-hidden="true"></i></button>' ; 
                $delete='delete_cat("'.$cat_id.'","Parent")';
            } 
            else if($cattype == 'Master'){ $delete='delete_cat("'.$cat_id.'","Master")';  $internal = "sublist";  $classd = 'master-category sub-cat-body';$str = "'".$cat_id."','".addslashes($cat_name)."','Sub','Master Category'";  $add_child = '<button type="button" class="btn btn-default" onclick ="openmodel('.$str.')"><i class="fa fa-plus" aria-hidden="true"></i></button>' ; }
            else  if($cattype == 'Sub'){ $delete= 'delete_cat("'.$cat_id.'","Sub")'; $classd = 'sub-category border-bottom cc-margin-bottom-0'; if($template == "") { $disablevalue = "disabled";  $addtemplete = "<a href='".ADMINURL."category/createtemplate.php?catid=".base64_encode($cat_id)."' class='btn btn-primary btn-sm mb-2 mb-sm-0 mt-lg-1 mt-xl-0'>Create Template</a>";  }  else { $disablevalue= ""; $addtemplete =  "<a href='".ADMINURL."category/editemplate.php?catid=".base64_encode($cat_id)."' class='btn btn-primary btn-sm mb-2 mb-sm-0 mt-lg-1 mt-xl-0'><i class='fa fa-file-text mr-1 d-none d-sm-inline-block'></i> Template <span class='d-none'>".$template."</span></a>" ; }
            if($template!="" && $excelfile == "") { $addExcel = "<button type='button' class='btn btn-primary btn-sm mt-lg-1 mt-xl-0'  onclick='".$excelfunc."'><i class='fa fa-file-excel-o mr-1 d-none d-sm-inline-block'></i> Create Excel </button>"; } else{ $disablevalue= ""; $addExcel = "<a href='".SITEURL."/templatexcels/".$excelfile."' class='btn btn-primary btn-sm mb-2 mb-sm-0 mt-lg-1 mt-xl-0'><i class='fa fa-file-excel-o d-none d-sm-inline-block'></i> Excel<span class='d-none'>".$excelfile."</span></a>"; }
            }
            $menu .="<div class='menu-catgory-panel ".$classd."'  data-id='".$cat_id."' >
            <div class='mng-cat-head'>
            <div class='row'>
            <div class='col-sm-6 col-md-6 col-lg-4 col-xl-4 menu-cat-title align-self-center cc-cursor-pointer mb-2 mb-sm-0' onclick='toggle(".$cat_id.")' id=".$cat_id.">".$cat_name."</div><div class='col-sm-12 col-md-12 col-lg-4 col-xl-5 order-sm-3 order-lg-2 mb-sm-1 mb-lg-0 mt-md-2 mt-lg-0'>
            <span><a class='btn btn-primary btn-sm mb-2 mb-sm-0' href='uploadimg.php?id=".base64_encode($cat_id)."' ><span class='fa fa-upload d-none d-sm-inline-block'></span> Upload Image</a></span><span class='align-self-center ml-1 mr-1'>".$addtemplete."</span><span class='cre-excel-btn createexcel align-self-center'>".$addExcel."</span></div>
            <div class='cat-head-elements pt-2 pb-1 py-md-0 col-sm-6 col-md-6 col-lg-4 col-xl-3 text-right order-sm-2 order-lg-3'> 
            <label class='switch btn btn-primary'> 
            <input type='checkbox' data-id='".$cat_id."' id='checkbox".$cat_id."' name='checkbox".$cat_id."'  ".$checkvalue." onchange='".$activfun."'  ".$disablevalue.">
            <span class='slider round'><span class='on'>Active</span><span class='off'>Deactive</span></span>
            </label>
            ".$add_child." 
            <button type='button' class='btn btn-default' onclick='open_edit_modal(".$cat_id.")'><i class='fa fa-pencil' aria-hidden='true'></i></button>
            <button type='button' class='btn btn-default' onclick='".$delete."' ><i class='fa fa-trash' aria-hidden='true'></i></button></div>
            </div>
            </div> ";
            $menu .="<div class='menu-catgory cc-display-none border border-top-0 ".$internal."' >".get_cat_tree2($cat_id)."</div>";
            $menu .="</div>";
        }
    }  
    return $menu;
} ?> 
<div class="modal" id="add_cat_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body cc-padding-bottom-0">
                <div class="cat_alert_msgs"></div>
                <label id="Cat_head_label"></label>
                <div class="form-group">
                    <input type="hidden" id="parentid_id">
                    <input type="hidden" id="cat_type">
                    <input type="text" class="form-control" id="cat_name" maxlength="50" placeholder="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="save_cat()" id="add_cat_btn">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="edit_cat_modal" tabindex="-1" role="dialog"><div class="modal-dialog modal-lg" role="document"><div class="modal-content" id="edit_modal_content"></div></div></div>
</div>
<script src="<?php echo SITEURL; ?>/js/validation.js"></script>
<script src="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
<script>
/* CATEGORY DRAG AND DROP ANIMATION - CHANGE THE SEQUENCE OF CATEGORY */
$(function(){
    $('.mainlist').sortable({
        connectWith: '.mainlist',
        update: function() {
            str = "";
            $(".mainlist .menu-catgory-panel").each(function(index) { str += $(this).attr('data-id') + ","; });
            str = str.replace(/,\s*$/, "");
            var info = { action: "priority",str: str };
            $.ajax({
                data: info,
                type: 'POST',
                url: 'ajaxdata.php',
                success: function(result) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
                }
            })
        }
    });
    $('.masterlist').sortable({
        connectWith: '.master-category',
        update: function() {
            str = "";
            $(".master-category").each(function(index) { str += $(this).attr('data-id') + ","; });
            str = str.replace(/,\s*$/, "");
            var info = { action: "priority",str: str };
            $.ajax({
                data: info,
                type: 'POST',
                url: 'ajaxdata.php',
                success: function(result) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
                }
            })
        }
    });
    $('.sublist').sortable({
        connectWith: '.sub-category',
        update: function(){
            str = "";
            $(" .sub-category").each(function(index) { str += $(this).attr('data-id') + ","; });
            str = str.replace(/,\s*$/, "");
            var info = { action: "priority",str: str };
            $.ajax({
                data: info,
                type: 'POST',
                url: 'ajaxdata.php',
                success: function(result) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
                }
            })
        }
    }); 
});
function save_cat(){
    category= $.trim($("#cat_name").val());
    cattype = $.trim($("#cat_type").val());
    parentid_id = $.trim($("#parentid_id").val());
    if($.trim(category).length==0){
        $('.cat_alert_msgs').fadeIn().html("Enter valid "+cattype+" category details").addClass("alert alert-danger").delay(5000).fadeOut();
    } else{
        $("#add_cat_btn").attr("disabled",true);
        var info = {category:category,cattype:cattype,parentid_id:parentid_id,action:"add_category"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response=response.trim();        
                $("#add_cat_btn").attr("disabled",false);
                if(response== "Exist"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(cattype+" already exist").delay(3000).fadeOut();
                } else if(response=="Not"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                } else if(response=="Invalid"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter valid "+cattype+" category details").delay(3000).fadeOut();
                } else{
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(cattype+" category added successfully").delay(3000).fadeOut();
                    $("#cat_name").val("");
                    $( "#all_category").load(" #all_category");
                    setTimeout(function(){ 
                        if(!$("#"+response).parents(".menu-catgory").hasClass("show")){
                            $("#"+response).parents(".menu-catgory").show();
                        }
                        $('.menu-catgory:empty').remove(); 
                    },1000)
                    $("#add_cat_modal").modal("hide");
                }
            }
        });
    }  
}
    
function openmodel(parent_id,parent_cat,cat_type,parent_type){
    $("#add_cat_modal").modal("show")
    if(cat_type == "Parent"){ $("#modal_title").html("Add "+cat_type+" Category");$("#Cat_head_label").html("");}
    else{ 
        $("#modal_title").html("Add "+cat_type+" Category For ");
        $("#Cat_head_label").html(parent_type+" : "+parent_cat);
    }
    $('#cat_name').attr("placeholder","Enter "+cat_type+" Category Name" );
    $("#parentid_id").val(parent_id);
    $("#cat_type").val(cat_type);
}

 function open_edit_modal(catid){
    info = {catid:catid,action:'getedit_form'}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            $("#edit_modal_content").replaceWith(response);
            $("#edit_cat_modal").modal("show");
        }
    })    
}

function edit_cat(catid,cattype){
    var edit_cat_name = $("#edit_cat_name").val();
    var pagetitle = $("#pagetitle").val();
    var keywords = $("#keywords").val();
    var metadesc = $("#metadesc").val();
    parentid_id = $.trim($("#parentid_id_edit").val());
    if($.trim(edit_cat_name).length==0){
    $('.seo_alert_msgs').fadeIn().html('Please enter  page title').addClass("alert alert-danger").delay(5000).fadeOut();
    } else if($.trim(pagetitle).length==0){
        $('.seo_alert_msgs').fadeIn().html('Please enter  page title').addClass("alert alert-danger").delay(5000).fadeOut();
    } else if($.trim(metadesc).length==0){
        $('.seo_alert_msgs').fadeIn().html('Please enter meta description').addClass("alert alert-danger").delay(5000).fadeOut();
    } else if($.trim(keywords).length==0){
        $('.seo_alert_msgs').fadeIn().html('Please enter keywords').addClass("alert alert-danger").delay(5000).fadeOut();
    }
    else {
        info = {parentid_id:parentid_id,catid:catid,cattype:cattype,edit_cat_name:edit_cat_name,pagetitle:pagetitle,keywords:keywords,metadesc:metadesc,action:'edit_category'}
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response = response.trim();
                $("#edit_cat_btn").attr("disabled",false);
                if(response== "Exist"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(cattype+" category already exist").delay(3000).fadeOut();
                } else if(response=="Not"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                } else if(response=="Invalid"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter valid "+cattype+" category name").delay(3000).fadeOut();
                } else if(response=="1"){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html( cattype+" category updated successfully").delay(3000).fadeOut();
                    $("#edit_cat_modal").modal("hide");
                    $( "#all_category").load(" #all_category");
                    setTimeout(function(){
                        // $("[data-toggle='toggle']").bootstrapToggle('destroy');
                        $("[data-toggle='toggle']").bootstrapToggle();
                        if(!$("#"+catid).parents(".menu-catgory").hasClass("show")){
                            $("#"+catid).parents(".menu-catgory").show();
                        }
                    },1000)
                }
            }
        })
    }        
}
function toggle(id){
    var $this = $("#"+id).parents(".mng-cat-head");
    if ($this.next().hasClass('show')){
        $this.next().removeClass('show'); $this.next().slideUp(350);
    } else {
        $this.parent().parent().find('.cat-collaps').removeClass('show');
        $this.parent().parent().find('.cat-collaps').slideUp(350);
        $this.next().toggleClass('show');
        $this.next().slideToggle(350);
    }
}
function active(v1,v2,v3){
    var requestedid = v1;
    var attributeid = v2;
    var requestedtype = v3;
    var c = $("#"+attributeid+":checked").val();
    if(c=="on"){
        var info = {requestedid:requestedid,status:"1",requestedtype:requestedtype,action:"Active_deactive"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(requestedtype+" category activated").delay(3000).fadeOut();
                }
                else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
                }
            }
        });
    }
    else{
        var info = {requestedid:requestedid,status:"0",requestedtype:requestedtype,action:"Active_deactive"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(requestedtype+" category deactivated").delay(3000).fadeOut();
                }
                else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
                }
            }
        });
    }
}

function createexcel(catid,template){
    var info = {template:template,action:"create_excel"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response = response.trim();
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Excel created successfully").delay(3000).fadeOut();
                $( "#all_category").load(" #all_category");
                setTimeout(function(){
                    // $("[data-toggle='toggle']").bootstrapToggle('destroy');
                    $("[data-toggle='toggle']").bootstrapToggle();
                    if(!$("#"+catid).parents(".menu-catgory").hasClass("show")){
                        $("#"+catid).parents(".menu-catgory").show();
                    }
                },1000)
                }else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }

        }
    })
}
    
$(document).ready(function(){ $('.menu-catgory:empty').remove(); });

function delete_cat(i,type){
    if(type == "Parent"){
        $msg = "deleteting parent category will delete all its master category, sub category and all product";
    } else if(type == "Master"){
        $msg = "deleteting master category will delete all its sub category and all product";
    } else if(type == "Sub"){
        $msg = "deleteting sub category will delete all its product";
    }
    del_alertbox("Do you reallly want to delete?", i ,"del_cat_db",type);
}
//this", i,"del_cat_db",type
function del_cat_db(id,type){
    info = {cat_id:id,action:"Delete_cat",type:type}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response = response.trim();
            $("#add_attr_bt").attr("disabled",false);
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(type+" Category deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $( "#all_category").load(" #all_category");
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
</script>  
</body>
</html>
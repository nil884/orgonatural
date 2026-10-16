<? include("../../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Add Product</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap4-toggle.min.css" />
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php';
    function get_cat_tree2($parent_id){
        $menu = "";
        $prod_cat = selectQuery(PRODCAT,"*","parent_id=".$parent_id." order by priority");
        if(count($prod_cat)){
            for($i=0;$i<count($prod_cat);$i++){
                $cat_id = $prod_cat[$i]['id'];
                $cat_name = $prod_cat[$i]['cat_name'];
                $template = $prod_cat[$i]['template']; $excelfile=$prod_cat[$i]['excelFile'];
                $cattype = $prod_cat[$i]['type'];
                if($prod_cat[$i]['isActive'] == 1){ $checkvalue = "checked"; }else{ $checkvalue = ""; }
                if($cattype == "Parent") {
                    $internal = "masterlist";
                    $classd = 'parent-category';
                    $str = "'".$cat_id."','".addslashes($cat_name)."','Master'";
                } 
                else if($cattype == 'Master'){$internal = "sublist";  $classd = 'master-category sub-cat-body';$str = "'".$cat_id."','".addslashes($cat_name)."','Sub'";  $add_child = '<button type="button" class="btn btn-default"  onclick ="openmodel('.$str.')"><i class="fa fa-plus" aria-hidden="true"></i></button>'; }
                else  if($cattype == 'Sub'){ $classd = 'sub-category border-bottom cc-margin-bottom-0'; if($template == "") { $disablevalue = "disabled";  $addtemplete = "<a href='".ADMINURL."category/createtemplate.php?catid=".base64_encode($cat_id)."' > Create Template  </a>"; } else{ $disablevalue= ""; $addtemplete = $template; }
                if($template!="" && $excelfile == ""){ $addExcel = "Excel Not Available"; }else{ $disablevalue= ""; $addExcel = "<a href=".SITEURL."/templatexcels/".$excelfile." class='btn btn-primary btn-sm'><i class='fa fa-file-excel-o'></i> Excel</a>"; } }
                $menu .="<div class='menu-catgory-panel ".$classd."'  data-id='".$cat_id."' >
                <div class='mng-cat-head'>
                    <div class='row'>
                        <div class='col-sm-10'>
                        <div class='menu-cat-title snehalclass cc-cursor-pointer' onclick='toggle(".$cat_id.")' id=".$cat_id." >".$cat_name."</div><div>".$addtemplete."</div></div>
                            <div class='col-sm-2'>".$addExcel."</div>
                            <div class='cat-head-elements'>
                        </div>
                    </div>
                </div> ";
                $menu .="<div class='menu-catgory cc-display-none border border-top-0 ".$internal."' >".get_cat_tree2($cat_id)."</div>";
                $menu .="</div>";
            }
        }
        return $menu;
        echo get_cat_tree2(0);
    } ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Bulk Import Products</h2></div></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div id="all_category" class="mainlist">
                                <h6 class="mb-3">Get Your Excel From required category</h6>
                                <?php echo get_cat_tree2(0); ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h6 class="mb-3">Import Your Excel</h6>
                            <div class="panel-body alert alert-info">
                                <h6 class="mb-2">Before Upload</h6>
                                <ol class="pl-4 mb-0">
                                    <li>Please make sure, you have latest excel file to import</li>
                                    <li>Please read all instructions in excel file before entering your product details. </li>
                                    <li>Please make sure, you have entered all details correctly. Invalid details may impact your product listing</li>
                                    <li>Browse And Import The final excel file using import data button</li>
                                </ol>
                            </div>
                            <form id="prodct_add_form" action="save_basic.php" method="post">
                                <div class="file-field">
                                    <input type="file" id="filein" accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    <div class="mt-2">Acepted File Format : .csv,.xls,.xlsx</div>
                                </div>
                                <div class="form-group mt-3 mb-0"><button type="button" class="btn btn-primary btntopmargin" id="importBtn" onclick="importnow()">Import Data</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alltemplate_attribute"></div>
            <div class="specification"></div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/bootstrap4-toggle.min.js"></script>
<script>
function toggle(id){
    var $this = $("#"+id).parents(".mng-cat-head");
    if ($this.next().hasClass('show')) {
        $this.next().removeClass('show');
        $this.next().slideUp(350);
    } else {
        $this.parent().parent().find('.cat-collaps').removeClass('show');
        $this.parent().parent().find('.cat-collaps').slideUp(350);
        $this.next().toggleClass('show');
        $this.next().slideToggle(350);
    }
}
function importnow(){
    if($('#filein').val()!=""){
        $("#importBtn").attr("disabled",true).html("Importing Data...");
        var attachment = $('#filein').prop('files')[0];
        var attachmenttype = ($('#filein'))[0].files[0].type;
        var form_data1 = new FormData();
        form_data1.append('attachment', attachment);
        $.ajax({
            type:"POST",
            url:"ajaxuploadExcel.php",
            data:form_data1,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                $("#importBtn").attr("disabled",false).html("Import Data");
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Your file is in process. We will update you once all done").delay(10000).fadeOut();
                    $("#filein").val("");
                    setTimeout(function(){ location.reload(); }, 3000);
                }else{
                    $("#filein").val("");     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(response).delay(10000).fadeOut();
                }
            }
        });
    }
}
</script>
</body>
</html>
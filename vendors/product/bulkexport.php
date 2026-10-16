<? include("../../includes/configuration.php"); 
ini_set('display_errors', 1);
include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php'); include_once('../../PHPExcel/excelfunctions.php');
if(isset($_POST)){
    if($_POST['subcategory']!=""){
        $subcatid = $_POST['subcategory']; $seller = $_POST['seller'];
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $getcat = selectQuery(PRODCAT,"template","id =".$subcatid."");
        $excel = createExcelForExport($getcat[0]['template'],$url,$seller);
    }
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Exoprt Your Products</title>
    <? include "../commoncss.php";  ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap4-toggle.min.css" />
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <form id="prodct_add_form" action="<?=$_SERVER['PHP_SELF'];?>"  method="post">
                <div class="card mb-0">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Exoprt Products</h2></div></div>
                    <div class="card-body">
                        <div class="alert alert-info">Please don't open excel file directly, first save and then open the excel file.</div>
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group row">
                                <label class="col-sm-3 col-md-3 col-lg-4 col-form-label">Parent Category</label>
                                    <div class="col-sm-8 col-md-6 col-lg-8">
                                        <input type="hidden" name ="seller" value="<?=$_SESSION['seller']; ?>">
                                        <select name="parent_category" class="form-control" id="Parent-category" required onchange="catchng('Parent-category','master-category','master')">
                                            <option value="">Select parent category</option>
                                            <?php $getcat=selectQuery(PRODCAT,"id,cat_name","parent_id ='0' and isActive='1' and type= 'Parent' order by cat_name ASC");
                                            for($i=0;$i<count($getcat);$i++){ ?>
                                            <option value="<?php echo $getcat[$i]['id']; ?>" <? if($parent_id == $getcat[$i]['id']){ echo "selected";} ?>><?php echo $getcat[$i]['cat_name']; ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-md-3 col-lg-4 col-form-label">Master Category</label>
                                    <div class="col-sm-8 col-md-6 col-lg-8">
                                        <select name="master_category" class="form-control" id="master-category" required onchange="catchng('master-category','subcategory','sub')">
                                            <option value="">Select master category</option>
                                            <?php $getcat=selectQuery(PRODCAT,"id,cat_name","parent_id =".$parent_id." and type= 'Master' order by cat_name ASC");
                                            for($i=0;$i<count($getcat);$i++){ ?>
                                            <option value="<?php echo $getcat[$i]['id']; ?>" <? if($mastercatid == $getcat[$i]['id']){ echo "selected";} ?>><?php echo $getcat[$i]['cat_name']; ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-md-3 col-lg-4 col-form-label">Sub Category</label>
                                    <div class="col-sm-8 col-md-6 col-lg-8">
                                        <select class="form-control" name="subcategory" id="subcategory" required>
                                            <option value="">Select sub category</option>
                                            <?php $getcat = selectQuery(PRODCAT,"id,cat_name,template","parent_id =".$mastercatid." and template<>'' and type= 'Sub' order by cat_name ASC");
                                            for($i=0;$i<count($getcat);$i++) { ?>
                                            <option value="<?php echo $getcat[$i]['id']; ?>" <? if($subcatid == $getcat[$i]['id']){ echo "selected"; } ?> data-id = "<?php echo $getcat[$i]['template']; ?>"><?php echo $getcat[$i]['cat_name']; ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="card-footer py-2 text-right"><button class="btn btn-primary" type="submit">Export Products</button></div>
                </div>
            </form>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script>
    function catchng(input,replacediv,type){
    var selectedval = $("#"+input+"  option:selected").val();
    var info = {cat:selectedval,type:type,action:"get_category"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==0){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try Again Later").delay(3000).fadeOut();
            }
            else {
                $("#"+replacediv).html(response);
                if(type == "master"){
                    $("#subcategory").prop('selectedIndex', 0);
                    $("#subcategory").prop('disabled',true)
                } else{
                    $("#subcategory").prop('disabled',false);
                    $('input[name=Variation_Available]').attr('checked',false);
                }
            }
        }
    });
}
</script>
</body>
</html>
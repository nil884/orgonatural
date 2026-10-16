<? include("../../includes/configuration.php");
include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
include_once('../../PHPExcel/excelfunctions.php');
$subcatid = base64_decode($_REQUEST['catid']);
$getsubcat_details = selectQuery(PRODCAT,"parent_id","id = ".$subcatid."  ");
$mastercatid = $getsubcat_details[0]['parent_id']; 
$getparent_id = selectQuery(PRODCAT,"parent_id","id = ".$getsubcat_details[0]['parent_id']."  ");
$parent_id = $getparent_id[0]['parent_id'];
if(isset($_REQUEST['create'])&&isset($_REQUEST['cname'])){ 
    $arr = array(); $arr2 = array(); $arr3 = array(); $dup = 0; $cnt = $_REQUEST['cnt'];
    for($i=0;$i<$cnt;$i++){  
        $fld='attr'.$i;
        $fldt='attrtype'.$i;
        $flds='attrsize'.$i;
        if($_POST[$fld]!=""){
            array_push($arr,$_POST[$fld]);
        } if($_POST[$fldt]!=""){
            array_push($arr2,$_POST[$fldt]);
        } if($_POST[$flds]!=""){
            array_push($arr3,$_POST[$flds]);
        }
    }
    $tablename="template_".$_REQUEST['subcatid']."_".str_replace(" ","",replace_nonletter(trim($_REQUEST['cname'])));
    $sql = "CREATE TABLE ".$tablename." ( "."id INT NOT NULL AUTO_INCREMENT,prod_id INT(11),highlight TEXT, ";
    $values = "";
    for($i=0;$i<sizeOf($arr);$i++){
        $values = $values."".str_replace(" ","",$arr[$i])." ".$arr2[$i]."(".$arr3[$i].") NOT NULL,";
    }
    $mid = $midvalues;
    $pri = "PRIMARY KEY (id)); ";
    $query = $sql."".$mid."".$values."".$pri;
    $retval = createQuery($query);
    if($retval){
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $data12 = array('template'=>$tablename);
        updateQuery(PRODCAT,$data12,"id=".$_REQUEST['subcatid']);
        $excel = createExcel($url,$tablename);
        $dataExcel = array("excelFile"=>$excel);
        updateQuery(PRODCAT,$dataExcel,"id=".$_REQUEST['subcatid']);
    } ?>
    <script> window.location="index.php";</script>
<?php } ?>                                  
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Create Template</title>
    <? include "../commoncss.php"; ?>
</head>
<body class="reload-pg">
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Step1. Create Template</h2></div></div>                
                <form class="form-horizontal">
                    <div class="card-body pb-0">
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label">Parent Category</label>
                            <div class="col-sm-8 col-md-6 col-lg-4 col-xl-4">
                                <select name="category" class="form-control" id="Parent-category" onchange="catchng('Parent-category','master-category','Master')" >
                                    <option value="">-Select Category-</option>
                                    <?php $getcat=selectQuery(PRODCAT,"*","parent_id ='0' and type= 'Parent' order by cat_name ASC");
                                    for($i=0;$i<count($getcat);$i++){ ?>
                                    <option value="<?php echo $getcat[$i]['id']; ?>" <? if($parent_id == $getcat[$i]['id']){ echo "selected";} ?>><?php echo $getcat[$i]['cat_name']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label">Master Category</label>
                            <div class="col-sm-8 col-md-6 col-lg-4 col-xl-4">
                                <select name="category" class="form-control" id="master-category" onchange="catchng('master-category','subcategory','Sub')" >
                                    <option value="">-Select Category-</option>
                                    <?php $getcat=selectQuery(PRODCAT,"*","parent_id =".$parent_id." and type= 'Master' order by cat_name ASC");
                                    for($i=0;$i<count($getcat);$i++){ ?>
                                    <option value="<?php echo $getcat[$i]['id']; ?>" <? if($mastercatid == $getcat[$i]['id']){ echo "selected";} ?>><?php echo $getcat[$i]['cat_name']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label">Sub Category</label>
                            <div class="col-sm-8 col-md-6 col-lg-4 col-xl-4">
                                <select class="form-control" name="subcategory" id="subcategory">
                                    <option value="">-Select Category-</option>
                                    <?php $getcat=selectQuery(PRODCAT,"*","parent_id =".$mastercatid." and type= 'Sub' order by cat_name ASC");
                                    for($i=0;$i<count($getcat);$i++){ ?>
                                    <option value="<?php echo $getcat[$i]['id']; ?>" <? if($subcatid == $getcat[$i]['id']) { echo "selected"; } ?> data-id = "<?php echo $getcat[$i]['template']; ?>"><?php echo $getcat[$i]['cat_name']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div> 
                    </div>
                    <div class="card-footer py-2 text-right">
                        <input type="button" id="proceed0" class="btn btn-primary" value="Proceed To Step 2">
                        <input type="button" id=selectothercategory class="btn btn-primary cc-display-none" value="Select Other Category">
                    </div>
                </form>
            </div>  
            <div class="card step2 mb-0 cc-display-none">  
                <div class="card-header sec-card-head justify-content-between align-items-center spc-list-check py-2">
                    <div><h2 class="card-head-title">Step2. Attribute List</h2></div>
                    <div><a href="<?php echo ADMINURL; ?>attribute/" class="btn btn-primary btn-sm">Add New Attribute</a></div>
                </div>
                <?php $get_atrr_cat = selectQuery(PRODATTR,"attr_id,attr_name"," parent_id='0' and isActive='1' order by attr_name ");
                if(count($get_atrr_cat)){
                for($i=0;$i<count($get_atrr_cat);$i++){ 
                $get_atrr_cat_child =selectQuery(PRODATTR,"attr_id,attr_name,attr_for_template"," parent_id='".$get_atrr_cat[$i]['attr_id']."' and isActive='1' order by attr_name"); ?>
                <div class="row m-0 border-bottom px-3 pt-3 pb-2 prod-attr-col attr_of<? echo $get_atrr_cat[$i]['attr_id'] ?>">
                <div class="col-12 custom-control custom-checkbox h6 mb-3 cc-font-weight-6"><input type="checkbox" class="custom-control-input" id="checkbox<?php echo $get_atrr_cat[$i]['attr_id']; ?>" onclick="select_all('<? echo $get_atrr_cat[$i]['attr_id'] ?>')"><label class="custom-control-label" for="checkbox<?php echo $get_atrr_cat[$i]['attr_id']; ?>"><?php echo $get_atrr_cat[$i]['attr_name'] ?></label></div>
                <?php if(count($get_atrr_cat_child)){ ?>
                    <?php for($j=0;$j<count($get_atrr_cat_child);$j++){ ?>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mr-0 custom-control custom-checkbox mb-2 custom-control-inline">
                        <input type="checkbox" id="proattr<?=$i.$j;?>" class="optattributes custom-control-input" value="<?php echo $get_atrr_cat_child[$j]['attr_name']; ?>" data-id="<?php echo $get_atrr_cat_child[$j]['attr_for_template']; ?>"> 
                        <label class="custom-control-label" for="proattr<?=$i.$j;?>"><?php echo ucfirst($get_atrr_cat_child[$j]['attr_name']); ?></label>
                    </div>
                <?php } ?>                           
                <? } else{ echo "<div class='text-muted col-md-12 px-0'>Attribute Not Available</div>"; } ?></div> <?php } } else{
                echo "<div class='text-muted col-md-12 px-0'><a href=".ADMINURL."attribute >Please Add Attribute Category</a></div>";
                } ?>
                <div class="col-md-12 py-2 text-right"><input type="button" id="proceed" class="btn btn-primary" value="Proceed To Step 3"></div>
            </div>
            <div class="card templateform cc-display-none">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Step3. Define Database Structure For This Product Template</h2></div></div>
                <div class="card-body">
                    <form method="POST" class="form-horizontal" id="templatefrom" action="<?=$_SERVER['PHP_SELF']; ?>" onsubmit="return validate()">
                        <div class="alert alert-info">
                            <ul class="mb-0 pl-0">
                                <li>Numeric Field Type will store only numeric data.</li>
                                <li>Alphanumeric Field Type will store numeric as well as characters data.</li>
                                <li>Field Size will be character limits accepted for the field.</li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="form-group cc-display-none">
                                <label class="col-md-3 control-label">Template Name</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <input type="text" name="cname" id="cname" onblur="firstcapital('cname')" class="form-control text-capitalize" placeholder="Saree, Shoes, Mobile" maxlength="30" />
                                    <input type="hidden" name="subcatid" id="subcatid" class="form-control" value=""/>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <label>Specification For Template</label>
                                <div class="selectedattr"><span>Select Specification From Available Specification To Create Template</span></div>
                            </div>
                            <div class="mt-2"><button type="submit" name="create" id="submit" class="btn btn-primary">Create Template</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/validation.js"></script>
<script src="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
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
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            } else{
                $("#"+replacediv).html(response);
                if(type == "Master"){
                    $("#subcategory").prop('selectedIndex', 0);
                    $("#subcategory").prop('disabled',true)    
                } else{ $("#subcategory").prop('disabled',false) }
            }
        }
    });
}

$("#proceed0").click(function(){
    var Parent = $("#Parent-category option:selected").val();
    var selectedval = $("#master-category option:selected").val();
    var selectedval1 = $("#subcategory option:selected").val();
    var template = $("#subcategory option:selected").attr('data-id');
    $('input:checkbox').removeAttr('checked');
    if(Parent == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Select parent category").delay(3000).fadeOut();
    } else if(selectedval == ""){ }
    else if(selectedval1 == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Select sub category").delay(3000).fadeOut();
    } else if($.trim(template) != "" ){
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Template for this subcategory allready exist ").delay(3000).fadeOut(); 
    } else {
        $(".step2").removeClass('cc-display-none');
        $("#selectothercategory").removeClass('cc-display-none');
        $("#Parent-category ,#master-category , #subcategory, #proceed0").attr("disabled",true);
        $("#Parent-category","#subcategory").attr("disabled", true);
    }
});
    
$("#proceed").click(function(){
    var arr = [];
    $(".optattributes:checked").each(function(){
        var combine = $(this).val()+"%*%"+$(this).attr('data-id');
        arr.push(combine);
    });
    var str= arr.toString();
    if(str!=""){
        $(".templateform").removeClass('cc-display-none');
        var info = {'fld':str,action:"get_template_field"};
        $.ajax({   
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                $(".selectedattr").replaceWith(response);
                $(".optattributes, #proceed").attr("disabled", true);
                var selectsub = $( "#subcategory option:selected" ).text();
                var selectsub_id = $( "#subcategory option:selected" ).val();
                $("#cname").val(selectsub);
                $("#subcatid").val(selectsub_id);
            }
        });
    } else{    
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Select atleast one attribute to proceed").delay(3000).fadeOut(); 
    }
 });

function validate(){
    var cat = $("#cname").val();
    if(cat==""){     
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill template name").delay(3000).fadeOut()
        return false;
    }
    var fldsel=[];
    $(".attr").each(function(){
        if($(this).val()!=""){
            if(fldsel.length==0){
                fldsel.push($(this).val());
            } else{
                for(i=0;i<fldsel.length;i++){
                    if(fldsel[i]==$(this).val()){
                        var fnd=1;
                            if(fnd==1){ $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Duplicate specification found in selection..").delay(3000).fadeOut()
                            return false;
                        }
                    }
                }
                fldsel.push($(this).val());
            }
        }
    });
    if(fldsel.length==0){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select atleast one specification.").delay(3000).fadeOut();
        return false;
    }
    var fldtypesel=[];
    $(".attrtype option:selected").each(function(){
        if($(this).val()!=""){ fldtypesel.push($(this).val()); }
    });
    var fldsize=[];
    $(".attrsize").each(function(){
        if($(this).val()!=""){ fldsize.push($(this).val()); }
    });
    if(fldsel.length>fldtypesel.length){ 
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select field type for each selected specification.").delay(3000).fadeOut();
        return false;
    }
    if(fldsel.length>fldsize.length){ 
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter field size for each selected specification").delay(3000).fadeOut();
        return false;
    }
}

$("#selectothercategory").click(function(){
    $("#Parent-category ,#master-category , #subcategory, #proceed0, #proceed").attr("disabled",false);
    $(".step2").addClass('cc-display-none');
    $('input:checkbox').prop('checked',false).removeAttr("disabled");
    $('#templatefrom').find('input').val('');    
    $(".templateform").addClass('cc-display-none');
})  

function select_all(id){
    var checked =  $("#checkbox"+id ).is(':checked'); 
    if(checked == true){
    $('.attr_of'+id+' :checkbox').prop("checked", true);
    } else{ $('.attr_of'+id+' :checkbox').prop("checked", false); }
}
</script>
</body>
</html>
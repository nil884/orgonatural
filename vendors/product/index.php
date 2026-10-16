<? include("../../includes/configuration.php");
unset($_SESSION['table_attribute']);
unset($_SESSION['inventry_array']);
unset($_SESSION['inventry_attribute_array']);
unset($_SESSION['sku_array']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo SITE_TITLE; ?> Add Product</title>
<? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <form id="prodct_add_form" action="save_basic.php"  method="post">
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center">
                        <div><h5 class="card-head-title">Step1. Add Basic Details</h5></div>
                    </div>
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-12 col-lg-4 col-xl-4 col-form-label cc-mandatary-field">Parent Category</label>
                                    <div class="col-sm-12 col-lg-8 col-xl-8">
                                        <input type="hidden" name ="inventry_added" id="inventry_added">
                                        <input type="hidden" name ="prod_exist" id="prod_exist" value="0"> 
                                        <input type="hidden" name ="sku_exist" id="sku_exist" value="0">
                                        <select name="parent_category" class="form-control" id="Parent-category" onchange="catchng('Parent-category','master-category','master')">
                                        <option value="">Select Parent Category</option>
                                        <?php $getcat=selectQuery(PRODCAT,"id,cat_name","parent_id ='0' and isActive='1' and type= 'Parent' order by cat_name ASC");
                                        for($i=0;$i<count($getcat);$i++){ ?>
                                        <option value="<?php echo $getcat[$i]['id']; ?>" <? if($parent_id == $getcat[$i]['id']) { echo "selected";} ?>><?php echo $getcat[$i]['cat_name']; ?></option>
                                        <? } ?>
                                        </select>
                                    </div>
                                </div>       
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-12 col-lg-4 col-xl-4 col-form-label cc-mandatary-field">Master Category</label>
                                    <div class="col-sm-12 col-lg-8 col-xl-8">
                                        <select name="master_category" class="form-control" id="master-category" onchange="catchng('master-category','subcategory','sub')">
                                            <option value="">Select Master Category</option>
                                            <?php $getcat=selectQuery(PRODCAT,"*","parent_id =".$parent_id." and type= 'Master' order by cat_name ASC");
                                            for($i=0;$i<count($getcat);$i++){ ?>
                                            <option value="<?php echo $getcat[$i]['id']; ?>" <? if($mastercatid == $getcat[$i]['id']){ echo "selected";} ?>><?php echo $getcat[$i]['cat_name']; ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-12 col-lg-4 col-xl-4 col-form-label cc-mandatary-field">Sub Category</label>
                                    <div class="col-sm-12 col-lg-8 col-xl-8">
                                        <select class="form-control" name="subcategory" id="subcategory" onchange= "activateradio()" >
                                            <option value="">Select Sub Category</option>
                                            <?php $getcat=selectQuery(PRODCAT,"*","parent_id =".$mastercatid." and type= 'Sub' order by cat_name ASC");
                                            for($i=0;$i<count($getcat);$i++) { ?>
                                            <option value="<?php echo $getcat[$i]['id']; ?>" <? if($subcatid == $getcat[$i]['id']) { echo "selected"; } ?> data-id = "<?php echo $getcat[$i]['template']; ?>"><?php echo $getcat[$i]['cat_name']; ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group mb-2 row <?php if(SERVICE_AVAILABLE == "NO"){  echo "cc-display-none"; } ?> ">
                                    <label class="col-sm-12 col-lg-4 col-xl-4 col-form-label cc-mandatary-field">Product Type</label>
                                    <div class="col-sm-12 ccol-lg-8 ol-xl-8">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="product_type" value="Product" id="addProType" checked> 
                                            <label class="custom-control-label" for="addProType">Product</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="product_type" id="addProSer" value="Service"> 
                                            <label class="custom-control-label" for="addProSer">Service</label>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-12 col-lg-4 col-xl-4 col-form-label cc-mandatary-field">Product Name</label>
                                    <div class="col-sm-12 col-lg-8 col-xl-8">
                                        <input type="text" placeholder="Product Name (Max.200 Characters)" name="productname" class="form-control text-capitalize" autocomplete="off" id="productname" value="<?php echo $productname; ?>" maxlength="200" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-12 col-lg-4 col-xl-4 col-form-label">Product Company</label>
                                    <div class="col-sm-12 col-lg-8 col-xl-8">
                                        <input type="text" placeholder="Product Company" name="Company" class="form-control text-capitalize" autocomplete="off" id="model" value="" maxlength="200" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-12 col-lg-4 col-xl-4 col-form-label cc-mandatary-field">HSN Code</label>
                                    <div class="col-sm-12 col-lg-8 col-xl-8">
                                        <input type="text" placeholder="HSN Code" name="HSN_code" class="form-control text-capitalize" autocomplete="off" id="HSN_code" value="" maxlength="50"  onkeyup="letter_number('HSN_code')" >
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-12 col-lg-2 col-xl-2 col-form-label cc-mandatary-field">Description</label>
                            <div class="col-sm-12 col-lg-10 col-xl-10">
                                <textarea name ="description" id="prod_description"> </textarea>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-12 col-lg-2 col-xl-2 col-form-label pt-0 cc-mandatary-field">Product Variation</label>
                            <div class="col-sm-12 col-lg-10 col-xl-10">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input  class="Variation_Available custom-control-input" id="prcvaryes" type="radio" name="Variation_Available" onchange="decide_variation(this)" value="1" >
                                    <label class="custom-control-label" for="prcvaryes">Yes (This product have variations)</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input class="Variation_Available custom-control-input" id="prcvarno" type="radio" name="Variation_Available" onchange="decide_variation(this)" value="0" >
                                    <label class="custom-control-label" for="prcvarno">No (This product Don't have variations)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alltemplate_attribute"></div>
                <div class="specification"></div>
                <div class="text-right"><input type="button" id="next1" class="btn btn-primary" value="Next"></div>
            </form>  
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/validation.js"></script>
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$('#prod_description').summernote({
    height: 200,
    callbacks: {
        onPaste: function(e){
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            //Firefox fix
            setTimeout(function(){
                document.execCommand('insertText', false, bufferText);
            }, 10);
        }
    }
});
$(document).ready(function(){ $('#prodct_add_form')[0].reset(); });
function catchng(input,replacediv,type){
    var selectedval=$("#"+input+"  option:selected").val();
    var info={cat:selectedval,type:type,action:"get_category"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==0){  
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }else{
                $("#"+replacediv).html(response);
                if(type == "master"){
                    $("#subcategory").prop('selectedIndex', 0); $("#subcategory").prop('disabled',true);   
                }else{
                    $("#subcategory").prop('disabled',false); $('input[name=Variation_Available]').attr('checked',false);
                }
            }
        }
    });
}
function activateradio(){
    var subcategory=$("#subcategory option:selected").val();
    template = $("#subcategory option:selected").attr("data-id");
    $(".Variation_Available"). prop("checked", false);
    $(".specification").html("");
    $(".inventry").html("");
    $(".alltemplate_attribute").html("");
    if(subcategory != "" && template != ""){
        $(".Variation_Available").attr('disabled', false);
    }else {
        if(subcategory  == ""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select subcategory").delay(3000).fadeOut();
        }
        else if(template == ""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please create template for selected subcategory").delay(3000).fadeOut();
        }
        $(".Variation_Available").attr('disabled', true);
    }
}

function decide_variation(aa){
    var Variation_Available = $("input[name='Variation_Available']:checked").val();
    subcategory =  $("#subcategory option:selected").val();
    template = $("#subcategory option:selected").attr("data-id");
    if(subcategory == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select sub category first").delay(3000).fadeOut(); 
        $("input[name='Variation_Available']").prop('checked', false);
    } else if(template == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please create template for selected subcategory").delay(3000).fadeOut() 
        $("input[name='Variation_Available']").prop('checked', false);
    } else { 
        if(Variation_Available == 1){
            $("#next1").prop("disabled",true);
            var info={subcategory:subcategory,action:"select_attribute_for_variation"};
            $.ajax({
                type:"POST",
                url:"ajaxdata.php",
                data:info,
                success:function(response){
                    if(response){
                        $(".alltemplate_attribute").replaceWith(response);
                        $(".specification").html("");
                    }
                }
            });
        }
        else{
            var info={action:"clear_attribute_in_session"};
            $.ajax({
                type:"POST",
                url:"ajaxdata.php",
                data:info,
                success:function(response){
                    if(response){ getspecification(); $(".alltemplate_attribute").html(""); }
                }
            });
        }
    }  
}
function getspecification(){
    $("#next1").prop("disabled",true);
    subcategory =  $("#subcategory option:selected").val();
    template = $("#subcategory option:selected").attr("data-id");
    if(subcategory == "") {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select sub category first").delay(3000).fadeOut() 
        $("input[name='Variation_Available']").prop('checked', false);
    } else if(template == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please create template for selected subcategory").delay(3000).fadeOut() 
        $("input[name='Variation_Available']").prop('checked', false);
    } else{
        var info = {subcategory:subcategory,action:"getspecification"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response){
                    $(".specification").replaceWith(response);
                    $(".addinventry").html("");
                     $('#Sale_start_date').datetimepicker({
                        ignoreReadonly: true,
                        minDate:moment(),
                        format: 'DD-MM-YYYY HH:mm',
                        disabledTimeIntervals:false,
                        useCurrent:false,
                    }).on("dp.change", function (e) {
                        $("#Sale_end_date").data("DateTimePicker").minDate(e.date);
                    });
                    $('#Sale_end_date').datetimepicker({
                        ignoreReadonly: true,
                        minDate:moment(),
                        format: 'DD-MM-YYYY HH:mm',
                        disabledTimeIntervals:false,
                        useCurrent:false,
                    });
                    $('[data-toggle="popover"]').popover({html : true});
                    $("#next1").prop("disabled",false);
                }
            }
        });
    }
}
function getttributeforvariation(){
    var checkboxValues = [];
    var checkboxValues_dataid = [];
    $('input:checkbox[name=attribute]:checked').each(function(){
        checkboxValues.push($(this).val());
        checkboxValues_dataid.push($(this).attr('data-id'));
    })
    if(checkboxValues.length == 0) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select specification for variation").delay(3000).fadeOut()
    }
    else if(checkboxValues.length > 3){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("You can select max three specification for variation ").delay(3000).fadeOut()
    }else{
        var info={attributes:checkboxValues,table_attribute:checkboxValues_dataid,action:"save_variation_attribute"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                $("#check_variation").attr("disabled",false);
                $("#check_variation img").remove();
                if(response){
                    getspecification();
                    $(".alltemplate_attribute").html("");
                }
            }
        });
    }
}
function addvariation(){
    var variant_0_name = $("#variant_0").attr('data-id');
    var variant_1_name = $("#variant_1").attr('data-id');
    var variant_2_name = $("#variant_2").attr('data-id');
    var variant_0_name1 = $("#variant_0").attr('data-id1');
    var variant_1_name1 = $("#variant_1").attr('data-id1');
    var variant_2_name1 = $("#variant_2").attr('data-id1');
    var variant_0_val = $("#variant_0").val();
    var varinat_1_val = $("#variant_1").val();
    var varinat_2_val = $("#variant_2").val();
    var sku = $("#sku").val();
    var sku_exist = $("#sku_exist").val();
    var quantity = $("#quantity").val();
    tax = $("#tax").val();
    mrp = $("#mrp").val();
    var regular_price = $("#regular_price").val();
    var Sale_price = $("#Sale_price").val();
    var Sale_start_date = $("#Sale_start_date").val();
    var Sale_end_date = $("#Sale_end_date").val();
    var weight = $("#weight").val();
    var Length = $("#Length").val();
    var Height = $("#Height").val();
    var Width = $("#Width").val();
    var Cancellation_days = $("input[name='cancelation_Available']:checked").val();
    var cod_Available = $("input[name='cod_Available']:checked").val();
    var Return_days = $("#Return_days").val();
    if(variant_0_val == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill "+variant_0_name1+" Detail").delay(3000).fadeOut();
    } else if(varinat_1_val == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill "+variant_1_name1+" Detail").delay(3000).fadeOut()
    } else if(varinat_2_val == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill "+variant_2_name1+" Detail").delay(3000).fadeOut()
    }
    else if(sku == ""){
     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please add SKU code").delay(3000).fadeOut()
    }
     else if(sku_exist == 1){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product with same SKU code allready exist").delay(3000).fadeOut() 
    }
     else if(quantity == ""  || quantity == "0"){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter quantity").delay(3000).fadeOut()
    } else if(tax == ""  ) {
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please ente tax value").delay(3000).fadeOut()
    } else if(mrp == ""  || mrp == "0") {
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter MRP value").delay(3000).fadeOut()
    } else if(regular_price == "" || regular_price == "0"){
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter vendor price").delay(3000).fadeOut()
    } else if( (Sale_start_date == "") &&  (Sale_price  != "" || Sale_price == "0")){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select discount start date").delay(3000).fadeOut()
    } else if( (Sale_end_date == "")  && (Sale_price  != "" || Sale_price == "0")) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select discount end date").delay(3000).fadeOut()
    } else if(Sale_start_date != "" && Sale_end_date != "" && (Sale_price == "0" || Sale_price == "") ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter discount price").delay(3000).fadeOut()
    } else {
        $("#add_more_price_variant").attr("disabled",true);
        var info={variant_0_name:variant_0_name,variant_1_name:variant_1_name,variant_2_name:variant_2_name,variant_0_val:variant_0_val,varinat_1_val:varinat_1_val,varinat_2_val:varinat_2_val,quantity:quantity,mrp:mrp,tax:tax,regular_price:regular_price,Sale_price:Sale_price,Sale_start_date:Sale_start_date,Sale_end_date:Sale_end_date,weight:weight,Length:Length,Height:Height,Width:Width,Cancellation_days:Cancellation_days,Return_days:Return_days,sku:sku,cod_Available:cod_Available,action:"addinv_new"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                $("#add_more_price_variant").attr("disabled",false);
                if(response == 1){
                    $("#sku_exist").val("0");
                    viewinventry();
                    $("#variant_0, #variant_1,variant_2, #quantity,#regular_price, #Sale_price, #Sale_start_date , #Sale_end_date,#weight,#Length,#Height,#Width,#tax,#mrp,#Return_days,#sku").val("");
                } else if(response == 3 ){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product with same SKU code allready exist").delay(3000).fadeOut() 
                }else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("This inventory allready exist").delay(3000).fadeOut()
                }
            }
        });
    }
}
 
function delete_inv(i,type) { var msg = "Do you really want to delete this Invetory"; del_alertbox(msg, i,"del_inv_seesion",type); }

function del_inv_seesion(id,type) {
    info  = {invid:id,action:"remove_inv"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            $("#add_attr_bt").attr("disabled",false);
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Inventory deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                viewinventry();
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
$("#next1").click(function(){
    Parentcategory =  $.trim($("#Parent-category option:selected").val());
    mastercategory =  $.trim($("#master-category option:selected").val());
    subcategory =  $.trim($("#subcategory option:selected").val());
    template = $.trim($("#subcategory option:selected").attr("data-id"));
    productname = $.trim($("#productname").val());
    HSN_code = $.trim($("#HSN_code").val());
    prod_description = $.trim($("#prod_description").val());
    variation_avail = $("input[name='Variation_Available']:checked").val();
    inventry_added = $("#inventry_added").val();
    prod_exist = $("#prod_exist").val();
    tax = $("#tax").val();
    mrp = $("#mrp").val();
    sku = $("#sku").val();
    sku_exist = $("#sku_exist").val();
    quantity = $.trim($("#quantity").val());
    regular_price = $.trim($("#regular_price").val());
    Sale_price = $.trim($("#Sale_price").val());
    Sale_start_date = $.trim($("#Sale_start_date").val());
    Sale_end_date = $.trim($("#Sale_end_date").val());
    dispmsg= 0;
    variation_attr_cnt= 0;
    var checkboxValues = [];
    $('[id^=spec]').each(function(i, item) {
        var specvalue =  $.trim($(item).val());
        if(specvalue == ""){  dispmsg = 1; }
    });
    $('input:checkbox[id^=variation_attr]:checked').each(function(i, item) {
        checkboxValues.push($(this).val());
        variation_attr_cnt+= 1;
    });
    if(Parentcategory == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select parent category").delay(3000).fadeOut()
    } else if(mastercategory == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select master category").delay(3000).fadeOut()
    } else if(subcategory == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select sub category").delay(3000).fadeOut()
    } else if(template == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please create template for selected subcategory ").delay(3000).fadeOut()
    } else if(productname == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter product name").delay(3000).fadeOut()
    } else if(prod_exist == 1){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product with same name allready exist. Please edit existing one").delay(3000).fadeOut()
    } else if(HSN_code == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter HSN code").delay(3000).fadeOut()
    } else if(prod_description == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter product description").delay(3000).fadeOut()
    } else if(variation_avail == ""  || variation_avail == undefined){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select variation available or not").delay(3000).fadeOut()
    } else if(dispmsg == 1){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all attribute details").delay(3000).fadeOut()
    } else if(variation_attr_cnt > 3){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Maximum three attribute can be selected for variation").delay(3000).fadeOut() 
    } else if( (variation_avail == 1)  && (inventry_added == 0)) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please add inventry").delay(3000).fadeOut()
    } else if((variation_avail == 0) && (sku == "") ){
     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please add SKU code").delay(3000).fadeOut()
    } else if(sku_exist == 1){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product with same SKU code allready exist").delay(3000).fadeOut() 
    } else if((variation_avail == 0) && (quantity == "" || quantity == "0") ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter quantity ").delay(3000).fadeOut() 
    } else if(variation_avail == 0 && tax == ""  ) {
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please ente tax value").delay(3000).fadeOut()
    } else if(variation_avail == 0 && (mrp == ""  || mrp == "0")) {
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter MRP value").delay(3000).fadeOut()
    } else if(variation_avail == 0 && (regular_price == ""  || regular_price == "0")) {
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter vendor price").delay(3000).fadeOut()
    } else if( (variation_avail == 0 && Sale_start_date == "") &&  (Sale_price  != "" || Sale_price == "0")){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select discount start date").delay(3000).fadeOut()
    } else if( (variation_avail == 0  && Sale_end_date == "")  && (Sale_price  != "" || Sale_price == "0")) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select discount end date").delay(3000).fadeOut()
    } else if(Sale_start_date != "" && Sale_end_date != "" && (Sale_price == "0"  || Sale_price == "")  ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter discount price").delay(3000).fadeOut()
    } else {
        $( "#prodct_add_form" ).submit();
    }; 
})
function viewinventry(){
    var info = {action:'view_inventry'};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            $(".inventry").replaceWith(response);
            var info={action:'getcount'};
            $.ajax({
                type:"POST",
                url:"ajaxdata.php",
                data:info,
                success:function(response){ $("#inventry_added").val(parseInt(response)); }
            });
        }
    });
}
$("#productname").keyup(function(){
    prodname = $.trim($("#productname").val());
     var info={prodname:prodname,action:"checkavailable"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){ response = $.trim(response); $("#prod_exist").val(response);
        }
    });
});
function chgdistbtntext(th){
   if(th.innerHTML=="Enable") th.innerHTML = "Disable";
    else th.innerHTML = "Enable";
}
</script>
</body>
</html>
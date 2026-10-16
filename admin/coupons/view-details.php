<? include("../../includes/configuration.php");
    $couponId = base64_decode($_GET['coupon_id']);
    $getCoupon = selectQuery(COUPON,"*","couponId=".$couponId);
    $cpCode = $getCoupon[0]['couponCode'];
    $ids = $getCoupon[0]['applicableId'];
    $idarr = explode(",",$ids);
    $jsonarr = json_encode($idarr,true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Edit Coupons</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
        <div class="card card-body">
                <div class="alert alert-info mb-0">
                    <h6><b>Important Note : </b></h6>
                    <ul class="mb-0 pl-3">
                        <li><b>Coupon Type - Public</b> : This coupon will be visible to all users</li>
                        <li><b>Coupon Type - Private</b> : This coupon will not be visible to any user, admin can distribute this coupon for special users separately</li>
                    </ul>
                </div>
            </div>
            <form id="coupon_add_form" action="#" method="post">
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center py-2"><h5 class="card-head-title">Edit Coupon</h5><div class="btn-actions-pane-right"><a href="javascript:history.go(-1)" class="btn btn-secondary btn-sm">Back</a></div></div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">Coupon Code</label>
                            <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5"><input type="text" placeholder="Coupon Code" name="couponcode" class="form-control text-capitalize" autocomplete="off" id="couponcode" maxlength="20" value="<?=$cpCode; ?>" disabled="disabled"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">Description</label>
                            <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5"><textarea name ="description" id="coupon_description" class="form-control" maxlength="500"><?=$getCoupon[0]['description']; ?></textarea></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label pt-0 cc-mandatary-field">Discount Type</label>
                            <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5">
                                <div class="custom-control custom-radio custom-control-inline"><input class="discType custom-control-input" id="discPrice" type="radio" name="discType" value="Price" <?=($getCoupon[0]['discountType']=="Price"?"checked":""); ?>><label class="custom-control-label" for="discPrice">Price</label></div>
                                <div class="custom-control custom-radio custom-control-inline"><input class="discType custom-control-input" id="discPerc" type="radio" name="discType" value="Percentage" <?=($getCoupon[0]['discountType']=="Percentage"?"checked":""); ?>><label class="custom-control-label" for="discPerc">Percetage</label></div> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">Discount Value</label>
                            <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5">
                                <input type="text" placeholder="Discount Value" name="discvalue" class="form-control" autocomplete="off" id="discvalue" maxlength="5" onchange="chceckdiscval()" value="<?=$getCoupon[0]['discountValue']; ?>" onkeyup="numbercheck('discvalue')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">Date</label>
                            <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <input type="text" placeholder="From" name="fromdt" class="form-control" autocomplete="off" id="fromdt" value="<?=date("d-m-y H:i",strtotime($getCoupon[0]['validFrom'])); ?>">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <input type="text" placeholder="To" name="todt" class="form-control" autocomplete="off" id="todt" value="<?=date("d-m-y H:i",strtotime($getCoupon[0]['validTill'])); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label pt-0 cc-mandatary-field">Min Order Value</label>
                            <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input class="minorder custom-control-input" id="prcvarno" type="radio" name="minorder" onchange="changeminval(this)" value="0" <?=($getCoupon[0]['minOrderValueRequire']=="1"?"checked":""); ?>>
                                    <label class="custom-control-label" for="prcvarno">Not Applicable</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input class="minorder custom-control-input" id="prcvaryes" type="radio" name="minorder" onchange="changeminval(this)" value="1" <?=($getCoupon[0]['minOrderValueRequire']=="0"?"checked":""); ?>>
                                    <label class="custom-control-label" for="prcvaryes">Applicable</label>
                                </div>
                            </div>
                        </div>
                        <div class="minval <?=($getCoupon[0]['minOrderValueRequire']=="0"?"":"d-none"); ?> ">
                            <div class="form-group row">
                                 <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">Min Order Value</label>
                                <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5">
                                    <input type="text" placeholder="Min Order Value" name="ordminval" class="form-control" autocomplete="off" id="ordminval" maxlength="5" value="<?=$getCoupon[0]['minOrderValue']; ?>" onkeyup="numbercheck('ordminval')">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label ">Limit Per User</label>
                            <div class="col-sm-8 col-md-8 col-lg-6 col-xl-5">
                            <select name="userlimit" class="form-control" id="userlimit" > <? for($i=1;$i<=100;$i++){?> <option value="<?=$i; ?>" <?=($getCoupon[0]['limitPerUser']==$i?"selected":""); ?>><?=$i; ?></option> <?} ?> </select>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label pt-0 cc-mandatary-field">Coupon Type</label>
                            <div class="col-sm-8 col-md-8 col-lg-5 col-xl-8">
                                <div class="custom-control custom-radio custom-control-inline mb-2">
                                    <input class="showAll custom-control-input" id="showAll2" type="radio" name="showAll"  value="0" <?=($getCoupon[0]['showToAll']=="0"?"checked":""); ?>>
                                    <label class="custom-control-label" for="showAll2">Private</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-2">
                                    <input class="showAll custom-control-input" id="showAll1" type="radio" name="showAll"  value="1" <?=($getCoupon[0]['showToAll']=="1"?"checked":""); ?>>
                                    <label class="custom-control-label" for="showAll1">Public</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label pt-0 cc-mandatary-field">Apply Coupon On</label>
                            <div class="col-sm-8 col-md-8 col-lg-5 col-xl-8">
                                <div class="custom-control custom-radio custom-control-inline mb-2">
                                    <input class="applyon custom-control-input" id="allOrder" type="radio" name="applyon" onchange="getprods(this)" value="Order" disabled <?=($getCoupon[0]['applicableOn']=="Order"?"checked":""); ?>>
                                    <label class="custom-control-label" for="allOrder">Order</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input class="applyon custom-control-input" id="allPro" type="radio" name="applyon" onchange="getprods(this)" value="All Products" disabled <?=($getCoupon[0]['applicableOn']=="All Products"?"checked":""); ?>>
                                    <label class="custom-control-label" for="allPro">All Products</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input class="applyon custom-control-input" id="subpro" type="radio" name="applyon" onchange="getprods(this)" value="Subcategories Products" disabled <?=($getCoupon[0]['applicableOn']=="Subcategories Products"?"checked":""); ?>>
                                    <label class="custom-control-label" for="subpro">On Subcategories Products</label>
                                </div>
                                 <div class="custom-control custom-radio custom-control-inline">
                                    <input class="applyon custom-control-input" id="selpro" type="radio" name="applyon" onchange="getprods(this)" value="Selected Products" disabled <?=($getCoupon[0]['applicableOn']=="Selected Products"?"checked":""); ?>>
                                    <label class="custom-control-label" for="selpro">On Selected Products</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label pt-0 ">Applied On </label>
                                <div class="col-sm-8 col-md-8 col-lg-5 col-xl-8">
                            <? 
                                if($getCoupon[0]['applicableOn']=="All Products"){?>All Products<? }
                                else if($getCoupon[0]['applicableOn']=="Order"){?>Order<? }
                                else if($getCoupon[0]['applicableOn']=="Subcategories Products"){ $con=explode(",",$getCoupon[0]['applicableId']); ?> <a href="applicableList.php?coupon_id=<?=base64_encode($couponId) ?>" href="_blank"><?=count($con) ?> Sub-categories</a> <?  }
                                else if($getCoupon[0]['applicableOn']=="Selected Products"){ $con=explode(",",$getCoupon[0]['applicableId']); ?> <a href="applicableList.php?coupon_id=<?=base64_encode($couponId) ?>" href="_blank"><?=count($con) ?> Products</a> <?  }
                                ?>
                            </div>   
                        </div>
                        <div class="row"><div class="applybox col-md-12"></div></div>
                        <div class="alltemplate_attribute"></div>
                        <div class="specification"></div>
                    </div>
                    <div class="card-footer py-2 text-right"><input type="button" id="next1" class="btn btn-primary" value="Update" onclick="submitdata()"></div>
                </div>
            </form>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?=SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?=SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?=SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/validation.js"></script>
<script>$('.all-coupon').DataTable(); </script>
 <script>
 function changeminval(minval){
    var ismin = $(minval).val();
    if(ismin==1){ $(".minval").removeClass("d-none"); }else{$(".minval").addClass("d-none"); } }    
    var frmdateval = $("#fromdt").val();
    $('#fromdt').datetimepicker({
        ignoreReadonly: true,
        minDate:moment(),
        format: 'DD-MM-YYYY HH:mm',
        disabledTimeIntervals:false
    }).on("dp.hide", function (e) {
        if($(this).val() == ""){ $("#fromdt").val(frmdateval); }
    });
    $("#fromdt").val(frmdateval);
    $('#todt').datetimepicker({
        ignoreReadonly: true, minDate:moment(), format: 'DD-MM-YYYY HH:mm', disabledTimeIntervals:false
    });
    getprods();
    function getprods(){
        var aplon = $(".applyon:checked").val();
        coupon = "<?=$couponId ?>";
         if(aplon!="Order"){
        if(aplon=="All Products"){prodtype="All";}
        else if(aplon=="Subcategories Products"){prodtype="Subcategory";}
        else if(aplon=="Selected Products"){prodtype="Product";}
        var info = {applicableOn:prodtype,couponId:coupon,action:"getProdDataForEdit"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){ $(".applybox").html(response) }
        });
       }else{
       $(".applybox").html("");
    }
    }
    function toggle(id){
        var $this = $("#"+id).parents(".mng-cat-head");
        if($this.next().hasClass('show')){
            $this.next().removeClass('show');
            $this.next().slideUp(350);
        } else{
            $this.parent().parent().find('.cat-collaps').removeClass('show');
            $this.parent().parent().find('.cat-collaps').slideUp(350);
            $this.next().toggleClass('show');
            $this.next().slideToggle(350);
        }
    }
    function getprod(id){
        var jsonarr = <?=$jsonarr; ?>;
        var $this = $("#"+id).parents(".mng-cat-head");
        chkhtml = $this.next().html();
        if( chkhtml.trim()==""){
            var info={subcatid:id,action:"getSubCatProd"};
            $.ajax({
                type:"POST",
                url:"ajaxdata.php",
                data:info,
                success:function(response){
                    data=JSON.parse(response);
                    mystr="";
                    for(i=0;i<data.length;i++){
                        var str="<div class='col-md-6 mb-1'><div ='custom-control custom-checkbox custom-control-inline'> <input type='checkbox' class='mr-1 products-"+id+" custom-control-input' name='products' id='tree_struct' value='"+data[i]['id']+"' "+(jsonarr.includes(data[i]['id'])?'checked':'')+"> <label class='custom-control-label' for='tree_struct'>"+data[i]['prod_name']+"</label></div></div>";
                        mystr+= str
                        $this.next().html("<div class='row mx-0'>"+mystr+"</div>");
                    }
                    var allstr = " <input type='checkbox' onchange='allchange(this,"+id+")'> All";
                   $(".check-"+id).html(allstr)
                }
            });
        }
    }
    function allchange(fld,id){
        if($(fld).prop("checked")){ $(".products-"+id).attr("checked",true) }else{$(".products-"+id).attr("checked",false)}
    }
    function chceckdiscval(){
        discType = $(".discType:checked").val();
        discvalue = $("#discvalue").val();
        if(discType=="Percentage"&&(isNaN(discvalue)||parseInt(discvalue)<=0||parseInt(discvalue)>=100) ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Discount value must be between 0 & 100").delay(3000).fadeOut();
        setTimeout(function(){$("#discvalue").focus();$("#next1").attr("disabled",true);  },500)
        }else if(discType=="Price"&&isNaN(discvalue)){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Only numbers are allowed").delay(3000).fadeOut();
            setTimeout(function(){$("#discvalue").focus();$("#next1").attr("disabled",true);  },500)
        }else{ $("#next1").attr("disabled",false); }
    }

    function submitdata(){
        couponId = "<?=$couponId; ?>"
        couponcode = $("#couponcode").val();
        coupon_description = $("#coupon_description").val();
        discType = $(".discType:checked").val();
        discvalue = $("#discvalue").val();
        fromdt = $("#fromdt").val();todt=$("#todt").val();
        minorder = $(".minorder:checked").val();
        ordminval = $("#ordminval").val();
        userlimit = $("#userlimit option:selected").val();
        applyon = $(".applyon:checked").val();
        showAll = $(".showAll:checked").val();
        products = [];
        if(couponcode.trim()==""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter coupon code").delay(3000).fadeOut();
        }else  if(coupon_description.trim()==""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter coupon code description").delay(3000).fadeOut();
        } else if(discvalue == ""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter discount value").delay(3000).fadeOut();
        } else if( (discType=="Percentage")&&(isNaN(discvalue)||parseInt(discvalue)<=0||parseInt(discvalue)>=100) ){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Discount value must be between 0 & 100").delay(3000).fadeOut();
        } else if(discType=="Price"&&isNaN(discvalue)){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Only numbers are allowed in discount value").delay(3000).fadeOut();
        } else if(fromdt == "" || todt == "" ){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter valid From date and To date").delay(3000).fadeOut();
        } else if(fromdt == todt){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("From date and To date should not be equal").delay(3000).fadeOut();
        } else if(minorder=="1"&&ordminval==""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter minimum order value").delay(3000).fadeOut();
        } else if(isNaN(ordminval)){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter valid order value").delay(3000).fadeOut();
        } else{
            if(applyon!="All Products"){
            $("input[name=products]:checked").each(function(){
                products.push($(this).val());
            })
        }
        prodstring = products.toString()
        var info ={couponId:couponId,couponcode:couponcode,coupon_description:coupon_description,discType:discType,discvalue:discvalue,fromdt:fromdt,todt:todt,minorder:minorder,ordminval:ordminval,userlimit:userlimit,applyon:applyon,prodstring:prodstring,showAll:showAll,action:"editCoupon"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response!=0){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Coupon updated..").delay(3000).fadeOut();
                }else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error..").delay(3000).fadeOut();
                }
            }
        });
    }
}
</script>
</body>
</html>
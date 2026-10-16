<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Order Management</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Order Related Configuration</h2></div></div>
                <form>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group row">
                                    <div class="col-12"><h6 class="text-primary mb-2">Free Shipping</h6></div>
                                    <label class="col-sm-4 col-md-4 col-lg-12 col-form-label cc-mandatorystar pt-0">Allow Free Shipping? </label>
                                    <div class="col-sm-8 col-md-5 col-lg-12">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="alofrshipyes" class="custom-control-input" name="free_shipping_on_order" value="ON" <? if ($getconfigdetails[0]['free_shipping_on_order'] == "ON"){ echo "checked"; } ?>> <label class="custom-control-label" for="alofrshipyes">YES</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="alofrshipno" class="custom-control-input" name="free_shipping_on_order" value="OFF" <? if ($getconfigdetails[0]['free_shipping_on_order'] == "OFF"){ echo "checked"; } ?>> <label class="custom-control-label" for="alofrshipno">NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-3">
                                    <label class="col-sm-4 col-md-4 col-lg-12 col-form-label cc-mandatorystar pt-0">For Amount Above</label>
                                    <div class="col-sm-8 col-md-5 col-lg-3"><input type="text" name="free_shipping_on_order_cost" id="free_shipping_on_order_cost" class="form-control" placeholder="Enter Shipping Charges" maxlength="50" value="<? echo $getconfigdetails[0]['free_shipping_on_order_cost']; ?>" onkeyup=" numbercheck('free_shipping_on_order_cost')" <? if ($getdetails[0]['free_shipping_on_order'] == "OFF"){ echo "disabled"; } ?>/></div>
                                </div>
                                 <div class="form-group row">
                                     <div class="col-12"><h6 class="text-primary mb-2">Cancellation Charges (Pre-Delivery)</h6></div>
                                    <label class="col-sm-4 col-md-4 col-lg-12 col-form-label cc-mandatorystar pt-0">Deduct Shipping Charges When End-User Raise Order Cancellation Request? <span class="d-none"><?php echo $getconfigdetails[0]['cut_shipping_charges_on_cancelation'];  ?></span></label>
                                    <div class="col-sm-8 col-md-8 col-lg-12">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="canamtyes" class="custom-control-input" name="cut_shipping" value="1" <? if ($getconfigdetails[0]['cut_shipping_charges_on_cancelation'] == "1") { echo "checked"; } ?>> <label class="custom-control-label" for="canamtyes">YES</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="canamtno" class="custom-control-input" name="cut_shipping" value="0" <? if ($getconfigdetails[0]['cut_shipping_charges_on_cancelation'] == "0") { echo "checked"; } ?>> <label class="custom-control-label" for="canamtno">NO</label>
                                        </div>
                                    </div>
                                </div>
                                 <div class="form-group row border-bottom pb-3">
                                    <label class="col-sm-4 col-md-4 col-lg-12 col-form-label cc-mandatorystar pt-0">Apply Cancellation Charges On Product Price (In Percentage) When End-User Raise Order Cancellation Request</label>
                                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-12">
                                        <div class="input-group cancel-charg qtybox">
                                            <input type="number" min="0" max="100" name="Cancellation_charge" id="Cancellation_charge" class="form-control rounded calcel-charg-inp rounded-0" value="<? echo $getconfigdetails[0]['cancelation_charge_percentage']; ?>" onkeyup="numbercheck('Cancellation_charge')"/>
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-3">
                                    <div class="col-12"><h6 class="text-primary mb-2">Refund Charges (Post-Delivery)</h6></div>
                                    <label class="col-sm-4 col-md-4 col-lg-12 col-form-label cc-mandatorystar pt-0">Deduct Shipping Charges When End-User Raise Order Refund Request? <span class="d-none"><?php  echo $getconfigdetails[0]['refund_with_shipping'];  ?></span></label>
                                    <div class="col-sm-8 col-md-8 col-lg-12">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="refamtyes" class="custom-control-input" name="refund_with_shipping" value="1" <? if ($getconfigdetails[0]['refund_with_shipping'] == "1") { echo "checked"; } ?>> <label class="custom-control-label" for="refamtyes">YES</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="refamtno" class="custom-control-input" name="refund_with_shipping" value="0" <? if ($getconfigdetails[0]['refund_with_shipping'] == "0") { echo "checked"; } ?>> <label class="custom-control-label" for="refamtno">NO</label>
                                        </div>
                                        <div class="mt-1">(Select YES, if you want to refund the order amount along with shipping charges to the end-user, when Return Order is executed.)</div>
                                    </div>
                                </div>

                                 <div class="row">
                                    <div class="col-12"><h6 class="text-primary mb-2">POS (Point Of Sell Orders)</h6></div>
                                    <label class="col-sm-4 col-md-4 col-lg-12 col-form-label cc-mandatorystar pt-0">Select Vendor For POS </label>
                                    <div class="col-sm-8 col-md-8 col-lg-12">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <? $getvendor=selectQuery(VENDOR,"dealer_id,dealer_name","isComplete='1' AND isActive='1' order by dealer_name ASC"); ?>
                                            <select class="form-control" id="pos_vendor">
                                            <option value="0" <?=($getconfigdetails[0]['default_vendor_for_pos']==0?"selected":""); ?>>select</option>
                                            <? for($i=0;$i<count($getvendor);$i++){
                                               ?> <option value="<?=$getvendor[$i]['dealer_id']; ?>" <?=($getconfigdetails[0]['default_vendor_for_pos']==$getvendor[$i]['dealer_id']?"selected":""); ?>><?=$getvendor[$i]['dealer_name']; ?></option><?
                                             } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
$(document).ready(function(){
    if ($("input[name='free_shipping_on_order']:checked"). val() == "OFF"){
        $("input[type='text']").attr("disabled", true);
    }
    $("input[type='radio']").on("click", function() {
        if ($("input[name='free_shipping_on_order']:checked"). val() == "ON"){
            $("input[type='text']").attr("disabled", false);
        } else{
            $("input[type='text']").attr("disabled", true);
            document.getElementById('free_shipping_on_order_cost').value = '0';
        }
    })
});
$(document).ready(function(){
    $("#submit").on("click", function(){
        var free_shipping_on_order = $("input[name='free_shipping_on_order']:checked"). val();
        var free_shipping_on_order_cost = $("#free_shipping_on_order_cost").val();
        var Cancellation_charge = $("#Cancellation_charge").val();
        var refund_with_shipping = $("input[name='refund_with_shipping']:checked").val();
        var cancalation_shipping = $("input[name='cut_shipping']:checked").val();
        var pos_vendor = $("#pos_vendor option:selected").val();
        if(free_shipping_on_order != ""  && free_shipping_on_order_cost != ""&& Cancellation_charge!=""){
            var info = {free_shipping_on_order: free_shipping_on_order, free_shipping_on_order_cost:free_shipping_on_order_cost, Cancellation_charge:Cancellation_charge, refund_with_shipping:refund_with_shipping,cancalation_shipping:cancalation_shipping, pos_vendor:pos_vendor, action:'ship_management' };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    }
                    if (response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields correctly").delay(3000).fadeOut();
        }
    });
});
$('#Cancellation_charge').on('keyup keydown', function(e){
    if ($(this).val() > 100 && e.keyCode !== 46 && e.keyCode !== 8){
        e.preventDefault();
        $(this).val(100);
    } else if($(this).val() < 0 && e.keyCode !== 46 && e.keyCode !== 8){
        e.preventDefault();
        $(this).val(0);
    }
});

(function($){
    $.fn.spinner = function(){
        this.each(function(){
            var el = $(this);
            el.wrap('<span class="spinner pr-1"></span>');
            el.before('<span class="sub rounded-left">-</span>');
            el.after('<span class="add rounded-right">+</span>');
            el.parent().on('click', '.sub', function(){
                if (el.val() > parseInt(el.attr('min')))
                el.val( function(i, oldval) { return --oldval; });
            });
            el.parent().on('click', '.add', function(){
                if (el.val() < parseInt(el.attr('max')))
                el.val( function(i, oldval) { return ++oldval; });
            });
        });
    };
})(jQuery);
$('input[type=number]').spinner();
</script>
</body>
</html>
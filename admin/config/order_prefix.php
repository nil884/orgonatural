<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Record Prefix</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Define Prefix For All Records</h2></div></div>
                <form>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Prefix For Order ID</label>
                            <div class="col-sm-8 col-md-6 col-lg-5"><input type="text" name="order_id" id="order_id" class="form-control" placeholder="Enter Order ID" maxlength="3" value="<? echo $getconfigdetails[0]['order_id']; ?>" onKeyup="charcheck('order_id')"/></div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Prefix For Seller Plan Invoice</label>
                            <div class="col-sm-8 col-md-6 col-lg-5"><input type="text" name="seller_inv" id="seller_inv" class="form-control" placeholder="Enter Seller Plan Invoice" maxlength="3" value="<? echo $getconfigdetails[0]['seller_inv']; ?>" onKeyup="charcheck('seller_inv')" /></div>
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
    $("#submit").on("click", function(){
        var order_id = $("#order_id").val();
        var seller_inv = $("#seller_inv").val();
       
        if (order_id == "" || seller_inv == "" ){
           $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill compulsary fields").delay(3000).fadeOut(); 
        }
         else if(order_id.length != 3){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Order id must contain 3 character").delay(3000).fadeOut();
            } 
            else if(seller_inv.length != 3){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Seller invoice must contain 3 character").delay(3000).fadeOut();
            }
         else{
            var info = { order_id: order_id, seller_inv:seller_inv, action:'order_prefix' };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    $(".loader").html('');
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        }
    });
});
</script>
</body>
</html>
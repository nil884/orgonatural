<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Backup</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Backup</h2></div></div>
                <div class="card-body pb-0">
                    <div class="message"></div>
                    <div id="webbackup">
                        <form id="webbackform">
                            <div class="row border-bottom">
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="vendor_data" value="vendor_data" checked><label class="custom-control-label mb-1" for="vendor_data">Vendor Data</label></div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="vendor_doc" value="vendor_documents" checked><label class="custom-control-label mb-1" for="vendor_doc">Vendor Documents</label></div>
                                </div>
                            </div>
                            <div class="row border-bottom pt-3">
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="buyer_data" value="buyer_data" checked><label class="custom-control-label mb-1" for="buyer_data">Buyer Data</label></div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="buyer_doc" value="buyer_documents" checked> <label class="custom-control-label mb-1" for="buyer_doc">Buyer Profile Pics</label></div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="buyer_address" value="buyer_address" checked> <label class="custom-control-label mb-1" for="buyer_address">Buyer Address Data</label></div>
                                </div>
                            </div>
                            <div class="row border-bottom pt-3">
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="prod_data" value="product_data" checked><label class="custom-control-label mb-1" for="prod_data">Product Data</label></div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="prod_images" value="product_images" checked><label class="custom-control-label mb-1" for="prod_images">Product Images</label></div>
                                </div>
                            </div>
                            <div class="row border-bottom pt-3">
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="prodcat_data" value="productcat_data" checked> <label class="custom-control-label mb-1" for="prodcat_data">Product Categories Data</label></div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="prodcat_images" value="productcat_images" checked><label class="custom-control-label mb-1" for="prodcat_images">Product Categories Images</label></div>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="cart" value="cart_data" checked> <label class="custom-control-label mb-1" for="cart">Cart And Wishlist Data</label></div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2">
                                    <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="menu custom-control-input" id="order" value="order_data" checked><label class="custom-control-label mb-1" for="order">Order Data</label></div>
                                </div>
                            </div>
                            <div class="row"><div class="card-footer py-2 text-right col-12"><button type="button" name="create" id="submit" class="btn btn-primary">Generate Backup</button></div></div>
                        </form>
                    </div>
                </div>
                <div class="downloadable cc-display-none"></div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#submit").on("click", function(){
        var vendor_data = (typeof($("#vendor_data:checked").val())!='undefined'?'yes':'no');
        var vendor_doc = (typeof($("#vendor_doc:checked").val())!='undefined'?'yes':'no');
        var buyer_data = (typeof($("#buyer_data:checked").val())!='undefined'?'yes':'no');
        var buyer_doc = (typeof($("#buyer_doc:checked").val())!='undefined'?'yes':'no');
        var buyer_address = (typeof($("#buyer_address:checked").val())!='undefined'?'yes':'no');
        var prod_data = (typeof($("#prod_data:checked").val())!='undefined'?'yes':'no');
        var prod_images = (typeof($("#prod_images:checked").val())!='undefined'?'yes':'no');
        var prodcat_data = (typeof($("#prodcat_data:checked").val())!='undefined'?'yes':'no');
        var prodcats_images = (typeof($("#prodcats_images:checked").val())!='undefined'?'yes':'no');
        var cart_data = (typeof($("#cart:checked").val())!='undefined'?'yes':'no');
        var order_data = (typeof($("#order:checked").val())!='undefined'?'yes':'no');
        if (vendor_data == "yes" ||vendor_doc == "yes" ||buyer_data == "yes" ||buyer_doc == "yes" ||buyer_address == "yes" ||prod_data == "yes"||prod_images == "yes" ||prodcat_data == "yes" ||prodcats_images == "yes" ||cart_data == "yes" ||order_data == "yes"  ){
            var info = { vendor_data: vendor_data, vendor_doc: vendor_doc, buyer_data: buyer_data, buyer_doc: buyer_doc, buyer_address: buyer_address, prod_data: prod_data, prod_images: prod_images, prodcat_data: prodcat_data, prodcats_images: prodcats_images, cart_data: cart_data, order_data: order_data };
            $('#submit').attr('disabled',true);
            $('.message').addClass('alert alert-info').html('<div class="media"><img class="align-self-center mr-2" src="<?=SITEURL; ?>/img/projectimage/loader.gif" alt="loader" width="30"><div class="media-body">We Are generating backup for you. <br>It may take longer time, please do not refresh page while we are processing your request</div></div>');
            $.ajax({
                type: "POST", url: "backup-ajax.php", data: info,
                success: function(response){
                    timeoutreload();
                    $('.message').removeClass('alert alert-info').html('');
                    $('#submit').attr('disabled',false);
                    $(".downloadable").append('<a href="'+response+'" id="downlink">Download</a>'); 
                    document.getElementById('downlink').click();
                }
            });
        } else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Select Any Type To Generate Backup").delay(3000).fadeOut();
        }
    });
});
function timeoutreload(){
    setTimeout(function(){ location.reload(); }, 1000);
}
</script>
</body>
</html>
<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title>Config : Shipping Management</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Add Shipping  Details</h5></div></div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-12 col-lg-9">
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-form-label cc-mandatorystar pt-0">Allow free Shipping? </label>
                                    <div class="col-md-5 col-lg-5">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="alofrshipyes" class="custom-control-input" name="free_shipping_on_order" value="ON" <? if ($getconfigdetails[0]['free_shipping_on_order'] == "ON") { echo "checked"; } ?>> <label class="custom-control-label" for="alofrshipyes">YES</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="alofrshipno" class="custom-control-input" name="free_shipping_on_order" value="OFF" <? if ($getconfigdetails[0]['free_shipping_on_order'] == "OFF") { echo "checked"; } ?>> <label class="custom-control-label" for="alofrshipno">NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-lg-3 col-form-label cc-mandatorystar">For Amount Above</label>
                                    <div class="col-md-5 col-lg-5">
                                        <input type="text" name="free_shipping_on_order_cost" id="free_shipping_on_order_cost" class="form-control" placeholder="Enter Shipping Charges"  maxlength="50"  value="<? echo $getconfigdetails[0]['free_shipping_on_order_cost']; ?>" onkeyup=" numbercheck('free_shipping_on_order_cost')" <? if ($getdetails[0]['free_shipping_on_order'] == "OFF"){ echo "disabled"; } ?>/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 offset-md-4 col-lg-4 offset-lg-3">
                                        <button type="button" name="create" id="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
    $(document).ready(function() {
        $("input[type='radio']").on("click", function() {
            if ($("input[name='free_shipping_on_order']:checked"). val() == "ON"){
                $("input[type='text']").attr("disabled", false);
            }
            else{
                $("input[type='text']").attr("disabled", true);
                document.getElementById('free_shipping_on_order_cost').value = '0';
            }
        })
    });

    $(document).ready(function() {
        $("#submit").on("click", function() {
            var free_shipping_on_order = $("input[name='free_shipping_on_order']:checked"). val();
            var free_shipping_on_order_cost = $("#free_shipping_on_order_cost").val();
            if (free_shipping_on_order != ""  && free_shipping_on_order_cost != "") {
                var info = {
                    free_shipping_on_order: free_shipping_on_order,
                    free_shipping_on_order_cost:free_shipping_on_order_cost,
                    action:'ship_management'
                };
                $.ajax({
                    type: "POST",
                    url: "ajaxdata.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                            $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details Updated Successfully").delay(3000).fadeOut();
                        }
                        if (response == 0) {
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Try after some time").delay(3000).fadeOut();
                        }
                    }
                });
            } else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Fill All Mandatory Fields Correctly").delay(3000).fadeOut();
            }
        });
    });
</script>
</body>
</html>
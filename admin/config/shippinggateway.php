<?php include("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Shipping Gateway</title>
    <?php include('../commoncss.php') ?>
   
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Shipping Gateway Details</h2></div>
                    <div class="btn-actions-pane-right">
                        <a href="<?=DOCUMENTURL;?>" class="btn btn-primary btn-sm" target="_blank">Help?</a>
                        
                    </div>
                </div>
                <form class="sms_gateway_form">
                    <div class="card-body">
                    <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Shipping By</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><select  name="shipby" id="shipby"  class="form-control" disabled><option value="SHIPROCKET" <?=($getconfigdetails[0]['shippingBy']=="SHIPROCKET"||$getconfigdetails[0]['shippingBy']==""?"selected":""); ?>>Shiprocket</option></select></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">API Username</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><input type="text" name="api_user" id="api_user" class="form-control" placeholder="Enter Shipping API Username" maxlength="100" value="<? echo $getconfigdetails[0]['shippingPanel_username']; ?>" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">API Password</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><input type="text" name="api_pwd" id="api_pwd" class="form-control" placeholder="Enter Shipping API Password" maxlength="50" value="<? echo $getconfigdetails[0]['shippingPanel_password']; ?>" /></div>
                        </div>

                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Shiprocket Order Status Grouping</h2></div></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="cc-font-weight-5">New Order</h6>
                            <ul class="pl-3 text-muted">
                                <li>Generated</li> 
                                <li>New Order</li>
                                <li>New</li>
                                <li>Invoiced</li>
                            </ul>
                            <h6 class="cc-font-weight-5">Cancelled Order</h6>
                            <ul class="pl-3 text-muted"> 
                                <li>Cancellation Request</li>
                                <li>Cancellation Requested</li>
                                <li>Canceled</li>
                            </ul>
                            <h6 class="cc-font-weight-5">Returned Order </h6>
                            <ul class="pl-3 text-muted"> 
                                <li>Return Delivered</li>
                                <li>Returned</li>
                            </ul>
                            <h6 class="cc-font-weight-5">Reject Return </h6>
                            <ul class="pl-3 text-muted"> 
                                <li>Return Rejected</li>
                                <li>Return Cancelled</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6 class="cc-font-weight-5">Waiting For Pickups Order</h6>
                            <ul class="pl-3 text-muted">
                                <li>Waiting For Ship</li>
                                <li>Picked Up</li>
                                <li>Ready To Ship</li>
                                <li>Pickup Scheduled</li>
                                <li>Pickup Queue</li>
                                <li>Pickup Rescheduled</li>
                                <li>Pickup Error</li>
                                <li>Pickup Exception</li>
                                <li>Out For Pickup</li>
                                <li>Awb Assigned</li>
                                <li>Label Generated</li>
                                <li>Manifest Generated</li>
                            </ul>
                            <h6 class="cc-font-weight-5">Failed Order</h6>
                              <ul class="pl-3 text-muted">
                                <li>failure</li>
                                <li>Destroyed</li>
                                <li>Damaged</li>
                                <li>ePayment Failed</li>
                                <li>Unmapped</li>
                                <li>Unfulfillable</li>
                              </ul>
                        </div>
                        <div class="col-md-3">
                            <h6 class="cc-font-weight-5">Shipped Order</h6>
                            <ul class="pl-3 text-muted">
                                <li>Shipped</li>
                                <li>Out for Delivery</li>
                                <li>In Transit</li>
                                <li>Reached Destination Hub</li>
                                <li>Misrouted</li>
                                <li>Lost</li>
                                <li>Undelivered</li>
                                <li>Delayed</li>
                                <li>Pending</li>
                            </ul> 
                            <h6 class="cc-font-weight-5">Return Requested</h6>
                            <ul class="pl-3 text-muted">
                                <li>Return Requested</li>
                            </ul>
                            <h6 class="cc-font-weight-5">RTO Orders</h6>
                            <ul class="pl-3 text-muted">
                                <li>Rto Acknowledged</li>
                                <li>Rto Initiated</li>
                                <li> Rto Delivered</li>
                                <li>Rto Ofd</li>
                                <li>Rto Ndr</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6 class="cc-font-weight-5">Delivered Order</h6>
                            <ul class="pl-3 text-muted">
                                <li>Delivered</li>
                                <li>Fulfilled</li>
                                <li>Archived</li>
                                <li>Partial Delivered</li>
                            </ul>
                            <h6 class="cc-font-weight-5">Return Initiated</h6>
                            <ul class="pl-3 text-muted">
                                <li>Return Initiated</li>
                                <li>Return Pending</li>
                                <li>Return Pickup Queued  </li>
                                <li>Return Pickup Error  </li>
                                <li>Return In Transit  </li>
                                <li>Return Pickup Generated  </li>
                                <li>Return Cancellation Requested</li>
                                <li>Return Pickup Cancelled</li>
                                <li>Return Pickup Resheduled</li>
                                <li>Return Pickedup</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
$(document).ready(function(){
    $("#submit").on("click", function(){
        var api_user = $("#api_user").val();
        var api_pwd = $("#api_pwd").val();
        var shipby=$("#shipby option:selected").val();
        if (api_user != "" && api_pwd != "" ) {
        var info = {api_user: api_user, api_pwd: api_pwd ,shipby:shipby,action:'shipping_gateway'};
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                $(".loader").html('');
                if (response == 1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Thank you, we are saving your Shiprocket account details").delay(3000).fadeOut();
                } if (response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
                if(response == 3){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Unfortunately the entered credentials are not matching with Shiprocket Account Details, please enter correct credentials for your Shiprocket Account").delay(3000).fadeOut();
                }
            }
        });
        } else {
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        }
    });
});

</script>
</body>
</html>
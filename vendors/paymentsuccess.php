<? include("../includes/configuration.php");
    $insid = base64_decode($_REQUEST['book']);
    $getpaymentdata = selectQuery(VENDORPAYMENT,"*","pay_id=".$insid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Payment Success</title>
    <? include "commoncss.php"; ?>
</head>
<body>
<div class="seller-body-wrap">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="shadow-lg card border-0 p-4 mb-4 bg-white text-center">
                    <div><img src="<?php echo SITEURL; ?>/img/projectimage/pay-success.png" alt="pay-success" class="mb-4"/></div>
                    <h4 class="text-success cc-font-weight-6">THANK YOU</h4>
                    <p class="lead text-muted">Your payment success with id</p>
                    <?php echo $getpaymentdata[0]['transaction_id']; ?>
                    <a href="<?php echo VENDORURL; ?>" class="btn btn-primary">Please click here for login</a>
                </div>
            </div>
        </div> 
    </div>
</div>
<? include "footer.php"; ?>
</body>
</html>
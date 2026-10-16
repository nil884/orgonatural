<?php include("../includes/configuration.php");
include_once('../easebuzz/easebuzz-lib/easebuzz_payment_gateway.php');
if(!$_POST){
    $SALT = EASESALT;
    $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $SALT, $ENV = null);
    $result = $easebuzzObj->easebuzzResponse( $_POST );
    $response = json_decode($result);
    $data = $response->data;
    $status = $data->status;
    $firstname = $data->firstname;
    $amount = $data->amount;
    $txnid = $data->txnid;
    $productinfo = $data->productinfo;
    $email = $data->email;
    $bookingid = $data->udf1;
    $paytype = $data->udf2;
}
else{
    $status = $_POST["status"];
    $firstname = $_POST["firstname"];
    $amount = $_POST["amount"];
    $txnid = $_POST["txnid"];
    $posted_hash = $_POST["hash"];
    $key = $_POST["key"];
    $productinfo = $_POST["productinfo"];
    $email = $_POST["email"];
    $orderid = $_POST["udf1"];
    $status1 = $_POST['unmappedstatus'];
    $error = $_POST['error'];
    $salt = SALT;
    $paytype = $_POST['udf2'];  
    If (isset($_POST["additionalCharges"])) {
        $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
    } else{	  
        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
    }
    $hash = hash("sha512", $retHashSeq);
}
$data = array( "payment_status"=>"Failed", "payu_status"=>$status, 'paytype' => $paytype, );
$update = updateQuery(VENDORPAYMENT,$data,"transaction_id='".$txnid."'"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Payment Failure</title>
    <? include "commoncss.php"; ?>
</head>
<body>
<div class="seller-body-wrap">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="shadow-lg card border-0 p-4 mb-4 bg-white text-center">
                    <div><img src="<?php echo SITEURL; ?>/img/projectimage/pay-fail.png" alt="pay-fail" class="mb-4"/></div>
                    <div class="noneed">
                        <?php echo "<h4 class='text-danger'>Your payment is ". $status ."</h4>";
                        echo "<p class='lead text-muted'>Your transaction id for this transaction is ".$txnid.".<br> You may try again.</p>"; ?>
                        <a href="<?php echo VENDORURL; ?>" class="btn btn-primary">Please click here for login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<? include "footer.php"; ?>
</body>
</html>
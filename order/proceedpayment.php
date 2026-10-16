<?php
include("../includes/configuration.php");
    include("../classes/order.php");
    require_once('../classes/user.php');
    require_once('../classes/product.php');
      $prod = new Product();  $user = new User(); $order = new Order($prod,$user);
      $transid=$_GET['transid'];

      $data=$order->getOrderDetails($transid);

    $MERCHANT_KEY = MARCHANTKEY;
    $SALT = SALT;
    $PAYU_BASE_URL = PAYURL;
    $action = '';
    $posted = array();
    if(!empty($_POST)){  foreach($_POST as $key => $value) {  $posted[$key] = $value;  } }
    $formError = 0;

    $hash = '';
    // Hash Sequence
    $hashSequence ="key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
    $array=array("key"=>$MERCHANT_KEY,"txnid"=>$data['transaction_id'],"amount"=>$data['payable_amount'],"productinfo"=>"Product Purchase","firstname"=>$data['u_fname']." ".$data['u_lname'],"email"=>$data['u_email'],"udf1"=>"","udf2"=>"","udf3"=>"","udf4"=>"","udf5"=>"","udf6"=>"","udf8"=>"","udf9"=>"","udf10"=>"");

        $posted['productinfo'] ="Purchase Product";
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        foreach($hashVarsSeq as $hash_var) {
            $hash_string .= isset($array[$hash_var]) ? $array[$hash_var] : '';
            $hash_string .= '|';
        }
        $hash_string .= $SALT;
        $hash = strtolower(hash('sha512', $hash_string));

    if($data['payment_mode']=="Easebuzz"){
      $action=SITEURL."/easebuzz/view/initiate_payment.php";
    }else if($data['payment_mode']=="PayU Money"){
         $action = $PAYU_BASE_URL . '/_payment';
    }else if($data['payment_mode']=="COD"){
        $action = SITEURL."/paymentresponse";
    }
 ?>
 <style>#overlay {
  position: fixed; /* Sit on top of the page content */
  display: none; /* Hidden by default */
  width: 100%; /* Full width (cover the whole page) */
  height: 100%; /* Full height (cover the whole page) */
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5); /* Black background with opacity */
  z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
  cursor: pointer; /* Add a pointer on hover */
}
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 60px;
  height: 60px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
 display:table-cell;vertical-align:middle;
 left:0;right:0;top:0;bottom:0; margin:auto;position:absolute
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
 <?
 if($data['payment_mode']=="Easebuzz"||$data['payment_mode']=="PayU Money"){
?>
<body  class="cc-grey-background">
 <form action="<?=$action; ?>" method="POST" name="payuForm" id="payuForm" class="pull-left" style="display:none">
    <input type="hidden" name="key" value="<?=$MERCHANT_KEY ?>" />
    <input type="hidden" name="hash" value="<?=$hash ?>"/>
    <input type="hidden" name="txnid" value="<?=$data['transaction_id']; ?>" />
    <input  type="hidden" name="amount" value="<?=$data['payable_amount']; ?>" />
    <input  type="hidden" name="firstname" id="firstname" value="<?=$data['u_fname']." ".$data['u_lname']; ?>" />
    <input  type="hidden" name="email" id="email" value="<?=$data['u_email']; ?>" />
    <input  type="hidden" name="phone" value="<?=$data['u_mobile']; ?>" />
    <input  type="hidden" name="udf1" id="udf1" value="" />
    <input  type="hidden" name="udf2" id="udf2" value="" />
    <input  type="hidden" name="productinfo" class="dispnone" value="Product Purchase">
    <input  type="hidden" name="surl" value="<?=SITEURL; ?>/paymentresponse" />
    <input  type="hidden" name="furl" value="<?=SITEURL; ?>/paymentresponse" />
    <input  type="hidden" name="service_provider" value="payu_paisa" />
    <button type="submit"  class="mp btn btn-default cc-margin-bottom-15" />  Pay </button>
</form>
</body>
  <script>
      document.getElementById("payuForm").submit();
    </script>
<? }else if($data['payment_mode']=="COD"){?>
<body  class="cc-grey-background">
 <form action="<?=$action; ?>" method="POST" name="payuForm" id="payuForm" class="pull-left">
    <input type="hidden" name="payment_source" value="COD" />
   <input type="hidden" name="txnid" value="<?=$data['transaction_id']; ?>" />
</form>
</body>
  <script>
      document.getElementById("payuForm").submit();
 </script>
<?}else if($data['payment_mode']=="Instamojo"){
    include "../instamojo/Instamojo.php";
     /*echo  INSTAAPIKEY."<br>";
     echo  INSTATOKEN."<br>";
     echo  INSTAURL."<br>";*/
    $api = new Instamojo\Instamojo(INSTAAPIKEY,INSTATOKEN, INSTAURL);
    	try {

    $response = $api->paymentRequestCreate(array(
        "purpose" => "Purchase",//required
        "amount" => $data['payable_amount'], //required
        "send_email" => true,
		"buyer_name"=>$data['u_fname']." ".$data['u_lname'],"phone"=>$data['u_mobile'],"send_sms"=>true,"allow_repeated_payments"=>false,
        "email" => $data['u_email'],
        "redirect_url" => SITEURL."/paymentresponse"
        ));
      /*  print_r($response);  */
   /*  [id] => 311434fbbc3348c8816d87b412b82561 */
   $pay_id= $response['id'];
    $pay_url= $response['longurl'];
    if($pay_id){
         $orddata=array("transaction_id"=>$pay_id);
        updateQuery(ORDER,$orddata,"transaction_id='".$data['transaction_id']."'");
    }

   	header("Location:".$pay_url);
	}catch(Exception $e){
	    echo $e->getMessage();
	}
}else if($data['payment_mode']=="Razorpay"){
   
    include '../razorpay-php/Razorpay.php';
     
    //use Razorpay\Api\Api;

    $api = new Razorpay\Api\Api(RAZORAPIKEY, RAZORSECRETE);

    $orderData = [
    'receipt'         => $data['transaction_id'],
    'amount'          => $data['payable_amount'] * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
    ];

    // try {
    // $razorpayOrder = $api->order->create($orderData);
    // $razorpayOrderId = $razorpayOrder['id'];
    // } catch (Exception $e) {
    //     die("Razorpay Error: " . $e->getMessage());
    // }

    $razorpayOrder = $api->order->create($orderData);
     $razorpayOrderId = $razorpayOrder['id'];

     if($razorpayOrderId){
         $orddata=array("transaction_id"=>$razorpayOrderId);
        updateQuery(ORDER,$orddata,"transaction_id='".$data['transaction_id']."'");
    }
    $_SESSION['razorpay_order_id'] = $razorpayOrderId;

    $displayAmount = $amount = $orderData['amount'];

    if ($displayCurrency !== 'INR')
    {
        $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
        $exchange = json_decode(file_get_contents($url), true);

        $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
    }

    $checkout = 'automatic';

    if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
    {
        $checkout = $_GET['checkout'];
    }

    $data = [
        "key"               => RAZORAPIKEY,
        "amount"            => $amount,
        "name"              => SITENAME,
        "description"       => "Purchase",
        "image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
        "prefill"           => [
        "name"              => $data['u_fname']." ".$data['u_lname'],
        "email"             => $data['u_email'],
        "contact"           =>$data['u_mobile'] ,
        ],
        "notes"             => [
        "address"           => "Hello World",
        ],
        "theme"             => [
        "color"             => "#F37254"
        ],
        "order_id"          => $razorpayOrderId,
    ];

    if ($displayCurrency !== 'INR')
    {
        $data['display_currency']  = $displayCurrency;
        $data['display_amount']    = $displayAmount;
    }

    $json = json_encode($data);

    //require("../razorpay_checkout/{$checkout}.php");
    require("../razorpay_checkout/manual.php");
    ?><script> document.getElementById('transaction_id').value = "<?php echo $razorpayOrderId; ?>";    </script><?
} ?>
<div id="overlay">
   <div class="loader"></div>
</div>
<script> document.getElementById("overlay").style.display = "block";         </script>
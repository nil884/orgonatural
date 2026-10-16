<?
 include("../includes/configuration.php");
  require_once "../classes/shiprocket.php";
   include("../classes/order.php");
    require_once('../classes/user.php');
    require_once('../classes/product.php');
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
     $token = $ship->authenticate();
    $prod = new Product();  $user = new User(); $order = new Order($prod,$user);


     echo MARCHANTKEY;

/*$retorderStatus=selectQuery(ORDERSUB,"item_id,return_order_id,return_shipment_id","is_returned='1' and order_current_Status<>'Returned'");

 if(count($retorderStatus)){

     for($i=0;$i<count($retorderStatus);$i++){
         $shippingOrderId=$retorderStatus[$i]['return_order_id'];
         if($shippingOrderId!=""){
             $data=$ship-> showOrderDetails($token,$shippingOrderId);

             $status=ucfirst(strtolower($data['data']['status']));

             $updatedata=array();
             $updatedata['return_status']=$status;
            if($status=="Returned"){

            }
            updateQuery(ORDERSUB,$updatedata,"item_id=".$retorderStatus[$i]['item_id']);
        }
    }

 }*/

?>
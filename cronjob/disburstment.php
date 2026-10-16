<?php 
include "../includes/configuration.php";
include("../classes/order.php"); require_once('../classes/user.php'); require_once('../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user);
$orderdata = $order->disbursmentOrders("overdue");
for($i=0;$i<count($orderdata);$i++){
    $r=$orderdata[$i];
    $purid=$r['pur_id'];
    $vendor = $order->getOrderItemDetatils($r['item_id'],"vendor");
    $getseller = selectQuery(VENDOR,"disburstment_auto_pay","dealer_id=".$vendor);
    if($getseller[0]['disburstment_auto_pay']=='1'){
        $data = array('payment_mode' => "Online","payment_date" =>date("Y-m-d H:i:s"),'payment_remark'=>"Auto Pay","ispaid"=> 1);
        updateQuery(PURCH, $data, "pur_id=" .$purid);
    }
}
?>
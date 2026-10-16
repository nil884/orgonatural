<?php include("../includes/configuration.php");
include("../classes/order.php"); require_once('../classes/user.php'); require_once('../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user);
if($action == "date_range_search"){
     $fromdt = date("Y-m-d 00:00:00", strtotime($_REQUEST['fromdt']));
    $todt = date("Y-m-d 23:59:59", strtotime($_REQUEST['todt']) ) ;
    
    // $order_of_currant_month = selectQuery(PURCH,"count(pur_id) as pur_id","(purchase_date  between '".$fromdt."' and '".$todt."') and (o.order_current_Status='Delivered' or o.order_current_Status='Return Rejected')  and purchase_from_vendor = '".$_SESSION['seller']."' ");
     
    
    $order_of_currant_month = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","count(p.pur_id) as pur_id","(purchase_date  between '".$fromdt."' and '".$todt."') and (o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and  p.purchase_from_vendor=".$_SESSION['seller']."");
  
     $vendor_total_sale = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total","(purchase_date  between '".$fromdt."' and '".$todt."') and (o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and  p.purchase_from_vendor=".$_SESSION['seller']."");
  
    $vendor_total_recieved = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total","(purchase_date  between '".$fromdt."' and '".$todt."') and (o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and ispaid = '1' and p.purchase_from_vendor=".$_SESSION['seller']."");
   
    $vendor_total_Pending = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total"," (purchase_date  between '".$fromdt."' and '".$todt."') and (o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and ispaid = '0' and p.purchase_from_vendor=".$_SESSION['seller']."");
    
    
   
    $resdata = array( "order"=>$order_of_currant_month[0]['pur_id'], "vendor_total_recieved"=> round($vendor_total_recieved[0]['total']),"vendor_total_sale"=> round($vendor_total_sale[0]['total']),"vendor_total_Pending" => round($vendor_total_Pending[0]['total']));  
    echo json_encode($resdata);
} ?> 
<?php include "../../includes/configuration.php"; require_once "../../classes/shiprocket.php"; require_once("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
    $username = SHIPUSER; $pasword = SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $prod = new Product();  $user = new User(); $ord = new Order($prod,$user);
    $token = $ship->authenticate();
    $action = $_POST['action'];
    $fromdt = $_POST['fromdt']." 00:00";
    $todt = $_POST['todt']." 23:59";
    $report = $_POST['report'];
    if($action=="generateGSTReport"){
    $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_order_id,p.purchase_date,p.purchase_from_vendor,o.vendor_per_item_price_withoutgst,o.order_current_Status,o.item_id,o.display_product_name,o.quantity,o.hsn_code,o.sku","p.purchase_from_vendor=".$_SESSION['seller']." AND (p.purchase_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND (o.order_current_Status<>'Canceled' AND o.order_current_Status<>'Returned'  and  o.order_current_Status<>'Return Delivered' and o.order_current_Status <> 'failure'  and  o.order_current_Status <> 'Destroyed' and o.order_current_Status <> 'Damaged' and o.order_current_Status <> 'ePayment Failed' and o.order_current_Status<>'Unmapped' and o.order_current_Status<>'Unfulfillable') and o.is_returned='0'");
    if(count($getdata)){
     $data=array("headers"=>array("Date","Time"," Order ID", "Status" , "Sold To","Tax No","Product","HSN Code","SKU Code", "Rate","Qty","Taxable","CGST(%)","CGST","SGST(%)","SGST","IGST(%)","IGST","Total"),"data"=>array());
        $adminpin=$getconfigdetails[0]['pincode'];
        for($i=0;$i<count($getdata);$i++){
            $purchaseId = $getdata[$i]['purchase_order_id'];
            $vendor = "vendor".$getdata[$i]['purchase_from_vendor'];
            $pickups = $ship->getPickups($token);
            $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
            $pickupdetails = $pickups[$pickupid];
            $vendorPincode = $pickupdetails['pin_code'];
            $vendorData = $prod->getVendorDetails($getdata[$i]['purchase_from_vendor']);
            $nickname = $vendorData[0]['nickname'];
            $datapin = array("postcode"=>$adminpin);
            $res = $ship->getPincodeData($token,$datapin); $pinRes = $res['postcode_details']; $adminState = $pinRes['state'];
            $datapin1 = array("postcode"=>$vendorPincode);
            $res1 = $ship->getPincodeData($token,$datapin1); $pinRes1 = $res1['postcode_details']; $vendorState = $pinRes1['state'];
            $details = $ord-> getPurchasePriceDetails($purchaseId,$vendorState,$adminState);
            $subdata = array(date("d-m-Y",strtotime($getdata[$i]['purchase_date'])),date("h:i a",strtotime($getdata[$i]['purchase_date'])),$getdata[$i]['purchase_order_id'],$getdata[$i]['order_current_Status'] ,SITENAME,GSTNO,$getdata[$i]['display_product_name'],$getdata[$i]['hsn_code'],$getdata[$i]['sku'],number_format($getdata[$i]['vendor_per_item_price_withoutgst'],2),$getdata[$i]['quantity'],number_format($details['taxable'],2),$details['cgst1'],number_format($details['cgst2'],2),$details['sgst1'],number_format($details['sgst2'],2),$details['igst1'],number_format($details['igst2'],2),number_format($details['total'],2));
            array_push($data['data'],$subdata);
        }
    }else{ $data=array(); } 
    echo json_encode($data);
} ?>
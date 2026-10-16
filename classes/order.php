<? class Order{
    public $user; public $product;
    public function __construct(Product $product,User $user){
        $this->product = $product; $this->user = $user;
    }
    public function getOrderDetails($transid){
        $getDetails = selectQuery(ORDER." as o LEFT JOIN ".BUYER." as b on o.user_id=b.u_id","*","o.transaction_id='".$transid."'");
        $data=array("id"=>$getDetails[0]['id'],"transaction_id"=>$getDetails[0]['transaction_id'],"payment_mode"=>$getDetails[0]['payment_mode'],"order_date"=>$getDetails[0]['order_date'],"payable_amount"=>$getDetails[0]['payable_amount'],
        "u_fname"=> $getDetails[0]['u_fname'],"u_lname"=> $getDetails[0]['u_lname'],"u_email"=> $getDetails[0]['u_email'],"u_mobile"=> $getDetails[0]['u_mobile'],
        "shipping_type"=>$getDetails[0]['shippingAddr_type'],"shipping_name"=>$getDetails[0]['shippingAddr_name'],"shipping_mobile"=>$getDetails[0]['shippingAddr_mobile'],
        "address"=>$getDetails[0]['shippingAddr_address'],"landmark"=>$getDetails[0]['shippingAddr_landmark'],"city"=>$getDetails[0]['shippingAddr_city'],
        "state"=>$getDetails[0]['shippingAddr_state'],"pincode"=>$getDetails[0]['shippingAddr_pincode']);  return $data;
    }
    public function getOrderFullDetails($transid){
        $getDetails = selectQuery(ORDER." as o LEFT JOIN ".BUYER." as b on o.user_id=b.u_id","*","o.transaction_id='".$transid."'");
        $shippingname=explode(" ",$getDetails[0]['shippingAddr_name']); $fname=$shippingname[0]; $ncnt=count($shippingname);  $lname=($ncnt>1?$shippingname[$ncnt-1]:"");
        $data=array("id" => $getDetails[0]['id'],"transaction_id" => $getDetails[0]['transaction_id'],"payment_mode" => $getDetails[0]['payment_mode'],"payment_id" => $getDetails[0]['payment_id'],"payment_status" => $getDetails[0]['payment_status'],"total_taxable_amount" => $getDetails[0]['total_taxable_amount'],"total_gst" => $getDetails[0]['total_gst'], "isFreeShipping "  => $getDetails[0]['isFreeShipping'], "total_shipping_charges" => $getDetails[0]['total_shipping_charges'], "discount_code" => $getDetails[0]['discount_code'], "discount_amount" => $getDetails[0]['discount_amount'], "total_bill_amount" => $getDetails[0]['total_bill_amount'], "order_id" => $getDetails[0]['order_id'], "order_date" => $getDetails[0]['order_date'],"payable_amount" => $getDetails[0]['payable_amount'], "user_id" => $getDetails[0]['u_id'],"u_fname" =>$fname,"u_lname" =>$lname,"u_email" => $getDetails[0]['u_email'],"u_mobile" => $getDetails[0]['shippingAddr_mobile'], "company_name" => $getDetails[0]['company_name'],"tax_no" => $getDetails[0]['tax_no'],"shipping_type" => $getDetails[0]['shippingAddr_type'],"shipping_name" => $getDetails[0]['shippingAddr_name'],"shipping_mobile" => $getDetails[0]['shippingAddr_mobile'],
        "address" => $getDetails[0]['shippingAddr_address'],"landmark" => $getDetails[0]['shippingAddr_landmark'],"city" => $getDetails[0]['shippingAddr_city'],
        "state" => $getDetails[0]['shippingAddr_state'],"country" => $getDetails[0]['shippingAddr_country'],"pincode" => $getDetails[0]['shippingAddr_pincode'],"Freeshippingreason" => $getDetails[0]['Freeshippingreason']);  return $data;
    }
   public function getVendorOrders($vendorId= null,$orderType=null,$not=null,$where=null){
        if($where!=""){ $whrstr=$where." AND "; }else{$whrstr="";}
       /* if($vendorId != ""){
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.shipping_order_id,o.shipping_order_error,o.shipping_by,od.transaction_id,o.order_current_Status",$whrstr."p.purchase_from_vendor=".$vendorId." and o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.") order by p.pur_id DESC");
        }
        else {
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,od.transaction_id,od.order_id,o.vendor_name,o.shipping_order_id,o.shipping_order_error,o.shipping_by,od.transaction_id,o.vendor,o.order_current_Status",$whrstr."o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.") order by p.pur_id DESC");
        }*/
         if($vendorId != ""){
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id inner JOIN ".ORDER." as od on o.order_id= od.id LEFT JOIN ".BUYER." as b on od.user_id=b.u_id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.shipping_order_id,od.order_id,o.shipping_order_error,o.shipping_by,od.transaction_id,o.order_current_Status,od.total_bill_amount,od.user_id,CONCAT(b.u_fname,' ',b.u_lname) as uname,od.shippingAddr_name",$whrstr."p.purchase_from_vendor=".$vendorId." and o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.") group by od.order_id order by p.pur_id DESC");
        }
        else {
           $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id LEFT JOIN ".BUYER." as b on od.user_id=b.u_id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,od.transaction_id,od.order_id,o.vendor_name,o.shipping_order_id,o.shipping_order_error,o.shipping_by,od.transaction_id,o.vendor,o.order_current_Status,od.total_bill_amount,od.user_id,CONCAT(b.u_fname,' ',b.u_lname) as uname,od.shippingAddr_name",$whrstr."o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.")  group by od.order_id order by p.pur_id DESC");
        }
        return $getdata;
    }
    
    public function missingstatusOrders($vendorId= null,$orderType=null){
        $expType=explode(",",$orderType);

          if($vendorId != "") {
             $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.shipping_order_id,o.order_current_Status,od.transaction_id,od.total_bill_amount","p.purchase_from_vendor=".$vendorId." and o.order_current_Status NOT  IN(".$orderType.") order by p.pur_id DESC");
           }
          else {
              $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,od.transaction_id,od.order_id,o.vendor_name,o.shipping_order_id,o.order_current_Status,od.transaction_id,o.vendor,od.total_bill_amount","o.order_current_Status NOT IN(".$orderType.") order by p.pur_id DESC");
          }
       return $getdata;
    } 
    public function getVendorOrdersCount($vendorId= null,$orderType=null,$not=null,$where=null){
         if($where!=""){ $whrstr=$where." AND "; }else{$whrstr="";}
       /* if($vendorId != ""){
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","count(o.item_id) as ordcnt",$whrstr."p.purchase_from_vendor=".$vendorId." and o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.") ");
        }
        else {
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","count(o.item_id) as ordcnt",$whrstr."o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.") ");
        }*/
          if($vendorId != ""){
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id inner JOIN ".ORDER." as od on o.order_id= od.id","count(distinct od.order_id) as ordcnt",$whrstr."p.purchase_from_vendor=".$vendorId." and o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.") group by od.order_id");
        }
        else {
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","count(distinct od.order_id) as ordcnt",$whrstr."o.order_current_Status ".($not?'NOT':'')." IN(".$orderType.") group by od.order_id");
        }
        return (count($getdata)?count($getdata):0);
    }

    public function disbursmentOrdersCount($disbursment_type,$vendorId=null){
          if($disbursment_type =="Today"){
           $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *( vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total , count(item_id) as ordcnt","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered')  and o.disbustment_date='".date("Y-m-d")."' and ispaid = '0'");
         }
         
        else if($disbursment_type =="Tomorrow"){
                  $date = date("Y-m-d", strtotime('+ 1 day'));
           $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total , count(item_id) as ordcnt","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and o.disbustment_date='".$date."' and ispaid = '0'"); 
          }
          else if($disbursment_type =="Upcoming"){
               $date = date("Y-m-d", strtotime('+ 1 day'));;
              $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total , count(item_id) as ordcnt","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and o.disbustment_date != '".$date."' and o.disbustment_date > '".$date."' and ispaid = '0'"); 
          }
          else if($disbursment_type =="overdue"){
              $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total , count(item_id) as ordcnt","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered' )  and o.disbustment_date < '".date("Y-m-d")."' and ispaid = '0'"); 
          }
          else if($disbursment_type =="Paid"){
           $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total,count(item_id) as ordcnt ","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and ispaid = '1'"); 
          }
          
          else if($disbursment_type =="Vendor_recieved" ){
          $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total,count(item_id) as ordcnt","(o.order_current_Status='Delivered'OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered' ) and ispaid = '1' and p.purchase_from_vendor=".$vendorId."");
          }
          else if($disbursment_type =="Vendor_pending"){
         $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total,count(item_id) as ordcnt","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and ispaid = '0' and p.purchase_from_vendor=".$vendorId.""); 
          }
          else if($disbursment_type =="Vendor_today"){
             $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *(vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100)) ) as total,count(item_id) as ordcnt","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and o.disbustment_date='".date("Y-m-d")."'  and p.purchase_from_vendor=".$vendorId."");
            }
          return $getdata; 
    }  
    public function disbursmentOrders($disbursment_type , $vendorId = null ){
        if($disbursment_type =="Today"){
         $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and o.disbustment_date='".date("Y-m-d")."' and ispaid = '0'");
        }
        else if($disbursment_type =="Tomorrow"){
                $date = date("Y-m-d", strtotime('+ 1 day'));
         $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and o.disbustment_date='".$date."' and ispaid = '0'");
        }
        else if($disbursment_type =="Upcoming"){
             $date = date("Y-m-d", strtotime('+ 1 day'));;
            $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name,o.disbustment_date","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and o.disbustment_date != '".$date."' and o.disbustment_date > '".$date."' and ispaid = '0'");
        } 
        else if($disbursment_type =="overdue"){
            $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name,o.disbustment_date","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered')  and o.disbustment_date < '".date("Y-m-d")."' and ispaid = '0'");
        }
        else if($disbursment_type =="Paid"){
         $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name,o.disbustment_date, p.payment_mode,p.payment_date,p.payment_remark","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and ispaid = '1'");
        }
        else if($disbursment_type =="Vendor_recieved" ){
        $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name,o.disbustment_date, p.payment_mode,p.payment_date,p.payment_remark,p.ispaid","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered' ) and ispaid = '1' and p.purchase_from_vendor=".$vendorId."");
      
        }
        else if($disbursment_type =="Vendor_pending"){
       $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name,o.disbustment_date, p.payment_mode,p.payment_date,p.payment_remark,p.ispaid,o.disbustment_date","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered' ) and ispaid = '0' and p.purchase_from_vendor=".$vendorId."");
        }
        else if($disbursment_type =="Vendor_today"){
           $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","p.pur_id , p.purchase_date,p.purchase_order_id,o.product_id,o.vendor,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.vendor_name,o.disbustment_date, p.payment_mode,p.payment_date,p.payment_remark,p.ispaid,o.disbustment_date","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered')  and o.disbustment_date='".date("Y-m-d")."'  and p.purchase_from_vendor=".$vendorId."");
          }
           return $getdata;
  }
    
    public function getVendororder_item($order_id){
        $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","*","p. reference_order_id =".$order_id." order by p.pur_id DESC");
        return $getdata;
    }
    public function getVendor_seprate_order($itemid){
        $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_date,p.purchase_order_id,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.user_per_unit_withoutgst_price ,o.taxable_without_gst,o.discount_code , o.discount_amount, o.product_image,o.display_product_name,o.variation_on,o.variation_values,o.item_id,o.refundable_amount,o.quantity,od.shippingAddr_type,od.shippingAddr_mobile,od.shippingAddr_address , od.shippingAddr_landmark,od.shippingAddr_city , od.shippingAddr_state ,od.shippingAddr_country, od.shippingAddr_pincode ,od.isFreeShipping ,od.Freeshippingreason  , o.order_current_Status,o.cancel_reason,o.cgst1,o.delivered_on,o.cgst2,o.sgst1,o.sgst2,o.igst1,o.igst2,o.isFreeShipping,o.shipping_charges,od.payment_mode,o.total_payable,o.shipping_shipment_id,return_reason,o.cancelled_by,o.hsn_code,o.sku","o.item_id =".$itemid." order by p.pur_id DESC");
        return $getdata;
    }
    public function getUserOrders($userid){
        $getDetails = selectQuery(ORDER." as o JOIN ".BUYER." as b on o.user_id=b.u_id","*","o.user_id='".$userid."' and  o.payment_status= 'success'  order by id desc");
        return $getDetails;
    }
    public function getShipmentId($itemId){
        $getdata = selectQuery(ORDERSUB,"shipping_shipment_id","item_id =".$itemId);
        return $getdata[0]['shipping_shipment_id'];
    }
    public function getCourierId($itemId){
        $getdata = selectQuery(ORDERSUB,"selected_courier_id","item_id =".$itemId);
        return $getdata[0]['selected_courier_ id'];
    }
    public function getAWBNO($itemId){
        $getdata = selectQuery(ORDERSUB,"shipping_awb_no","item_id =".$itemId);
        return $getdata[0]['shipping_awb_no'];
    }
    public function updateItem($itemId,$data){
        updateQuery(ORDERSUB,$data,"item_id =".$itemId);
    }
    public function getPurchasePriceDetails($purchaseId,$vendorState,$adminState){
        $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_from_vendor,o.vendor_per_item_price_withoutgst,o.quantity,o.tax_percentage","p.purchase_order_id= '".$purchaseId."'");
        $perPrice = $getdata[0]['vendor_per_item_price_withoutgst']; $cgst1=0; $sgst1=0; $igst1=0; $cgst2 = 0;$sgst2=0;$igst2=0;
        $quantity=$getdata[0]['quantity'];
        $tax=$getdata[0]['tax_percentage'];
        $vendor = $getdata[0]['purchase_from_vendor'];
        $without_gst = round($perPrice*$quantity);
        $taxamount = round(($without_gst/100)* $tax);
        if($vendorState==$adminState){
        $cgst1=round($tax/2,2);$sgst1=round($tax/2,2); $cgst2=round($taxamount/2);$sgst2=round($taxamount/2);
        }else{$igst1=$tax; $igst2=$taxamount;}
        $withgstval= $without_gst+ $cgst2+$sgst2+$igst2;
        $data=array("taxable"=>$without_gst, "cgst1"=>$cgst1,"cgst2"=>$cgst2, "sgst1"=>$sgst1,"sgst2"=>$sgst2, "igst1"=>$igst1,"igst2"=>$igst2, "total"=>$withgstval);
        return $data;
    }
   
   public function getOrderItemDetatils($itemId,$fld){
       $getdata=selectQuery(ORDERSUB,$fld,"item_id =".$itemId);
      return $getdata[0][$fld];
  }
}
?>
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
        if($report=="Sale"){
            $getdata = selectQuery(ORDERSUB." as o JOIN ".ORDER." as m on o.order_id=m.id","m.id,m.transaction_id,m.order_id,m.order_date,m.user_id,o.order_current_Status,o.item_id,o.display_product_name,o.product_image,o.user_per_unit_withoutgst_price,o.quantity,o.taxable_without_gst,o.discount_amount,o.cgst1,o.cgst2,o.sgst1,o.sgst2,o.igst1,o.igst2,o.total_with_gst,o.shipping_charges,o.total_payable,hsn_code,sku","(m.order_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND (o.order_current_Status='Delivered' OR o.order_current_Status='Partial Delivered' OR o.order_current_Status='Return Canceled')");
            $getdata_total = selectQuery(ORDERSUB." as o JOIN ".ORDER." as m on o.order_id=m.id","count(o.item_id) as total_count , sum(o.total_payable) as total_payable","(m.order_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND (o.order_current_Status='Delivered' OR o.order_current_Status='Partial Delivered' OR o.order_current_Status='Return Canceled')"); 
            if(count($getdata)){
                $data=array("headers"=>array("Date","Time","Order ID","Status", "Sold To","Tax No","Product","HSN Code","SKU Code" ,"Rate","Qty","Discount","CGST(%)","CGST","SGST(%)","SGST","IGST(%)","IGST","Shipping Charges","Total"),"data"=>array());
                for($i=0;$i<count($getdata);$i++){
                    $getUser = selectQuery(BUYER,"u_fname,u_lname","u_id=".$getdata[$i]['user_id']);
                    $subdata = array(date("d-m-Y",strtotime($getdata[$i]['order_date'])),date("h:i a",strtotime($getdata[$i]['order_date'])),$getdata[$i]['order_id'],$getdata[$i]['order_current_Status'] ,$getUser[0]['u_fname']." ".$getUser[0]['u_lname'],"",$getdata[$i]['display_product_name'],$getdata[$i]['hsn_code'],$getdata[$i]['sku'],   $getdata[$i]['user_per_unit_withoutgst_price'],$getdata[$i]['quantity'],$getdata[$i]['discount_amount'],$getdata[$i]['cgst1'],$getdata[$i]['cgst2'],$getdata[$i]['sgst1'],$getdata[$i]['sgst2'],$getdata[$i]['igst1'],$getdata[$i]['igst2'],$getdata[$i]['shipping_charges'],$getdata[$i]['total_payable']);
                    /*$subdata=array(
                    "id"=>$getdata[$i]['id'], "txn"=>$getdata[$i]['transaction_id'],
                    "orderId"=>$getdata[$i]['order_id'], "orderDate"=>$getdata[$i]['order_date'],
                    "user"=>$getUser[0]['u_fname']." ".$getUser[0]['u_lname'],
                    "itemId"=>$getdata[$i]['item_id'],
                    "status"=>$getdata[$i]['order_current_Status'], "product"=>$getdata[$i]['display_product_name'],
                    "image"=>$getdata[$i]['product_image'],"rate"=>$getdata[$i]['user_per_unit_withoutgst_price'],
                    "quantity"=>$getdata[$i]['quantity'],"taxableWithoutGst"=>$getdata[$i]['taxable_without_gst'],
                    "discount"=>$getdata[$i]['discount_amount'],"cgst1"=>$getdata[$i]['cgst1'],"cgst2"=>$getdata[$i]['cgst2']
                    ,"sgst1"=>$getdata[$i]['sgst1'],"sgst2"=>$getdata[$i]['sgst2'],"igst1"=>$getdata[$i]['igst1'],"igst2"=>$getdata[$i]['igst2'],
                    "totalWithGst"=>$getdata[$i]['total_with_gst'], "shippingCharges"=>$getdata[$i]['shipping_charges'], "totalPayable"=>$getdata[$i]['total_payable'],
                    );*/ 
                    array_push($data['data'],$subdata);
                }
                $data['total_count'] = $getdata_total[0]['total_count'];
                $data['total_amount'] = $getdata_total[0]['total_payable'];  
            }else{ $data=array(); }
            echo json_encode($data);
        }else if($report=="Purchase"){
            $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","p.purchase_order_id,p.purchase_date,p.purchase_from_vendor,o.vendor_per_item_price_withoutgst,o.display_product_name,o.quantity,o.order_current_Status,o.hsn_code,o.sku","(p.purchase_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND (o.order_current_Status='Delivered' OR o.order_current_Status='Partial Delivered' OR o.order_current_Status='Return Canceled')");
            $getdata_total = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","count(o.item_id) as total_count ","(p.purchase_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND (o.order_current_Status='Delivered' OR o.order_current_Status='Partial Delivered' OR o.order_current_Status='Return Canceled')");
            if(count($getdata)){
                $data = array("headers"=>array("Date","Time","Purchase Order ID", "Status" ,"Purchase From","Tax No","Product","HSN Code","SKU Code" ,"Rate","Qty","Taxable","CGST(%)","CGST","SGST(%)","SGST","IGST(%)","IGST","Total"),"data"=>array());
                $adminpin=$getconfigdetails[0]['pincode'];
                for($i=0;$i<count($getdata);$i++){
                    $purchaseId = $getdata[$i]['purchase_order_id'];
                    $vendor = "vendor".$getdata[$i]['purchase_from_vendor'];
                    $pickups = $ship->getPickups($token);
                    $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
                    $pickupdetails = $pickups[$pickupid];
                    $vendorPincode = $pickupdetails['pin_code'];
                    $nickname = $prod->getVendorFld($getdata[$i]['purchase_from_vendor'],"shopname");
                    $taxno = $prod->getVendorFld($getdata[$i]['purchase_from_vendor'],"vatno");
                    $datapin = array("postcode"=>$adminpin);
                    $res = $ship->getPincodeData($token,$datapin); $pinRes = $res['postcode_details']; $adminState = $pinRes['state'];
                    $datapin1 = array("postcode"=>$vendorPincode);
                    $res1 = $ship->getPincodeData($token,$datapin1); $pinRes1 = $res1['postcode_details']; $vendorState = $pinRes1['state'];
                    $details = $ord-> getPurchasePriceDetails($purchaseId,$vendorState,$adminState);
                    $total = $total + $details['total'];
                    $subdata = array(date("d-m-Y",strtotime($getdata[$i]['purchase_date'])),date("h:i a",strtotime($getdata[$i]['purchase_date'])),$getdata[$i]['purchase_order_id'], $getdata[$i]['order_current_Status'],$nickname,$taxno,$getdata[$i]['display_product_name'],$getdata[$i]['hsn_code'], $getdata[$i]['sku'],number_format($getdata[$i]['vendor_per_item_price_withoutgst'],2),$getdata[$i]['quantity'],number_format($details['taxable'],2),$details['cgst1'],number_format($details['cgst2'],2),$details['sgst1'],number_format($details['sgst2'],2),$details['igst1'],number_format($details['igst2'],2),number_format($details['total'],2));
                    array_push($data['data'],$subdata);
                }
                $data['total_count'] = $getdata_total[0]['total_count'];
                $data['total_amount'] =number_format($total,2);
            } else{ $data=array(); }
            echo json_encode($data);
        }
    }
    if($action == "savedata"){
        if($paytype=="vendor"){
            // $r['quantity']*($r['vendor_per_item_price_withoutgst']+($r['vendor_per_item_price_withoutgst']*$r['tax_percentage']/100))
            $getdata=simpleQuery("select p.purchase_order_id, (o.quantity*(o.vendor_per_item_price_withoutgst+(o.vendor_per_item_price_withoutgst*o.tax_percentage/100))) as amount,o.vendor_name,b.email as sendto From ".PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id JOIN ".VENDOR." as b on o.vendor=b.dealer_id where  p.pur_id=" .$purchase_id);
                  
            $data = array('payment_mode' => $mode,"payment_date" =>date("Y-m-d H:i:s",strtotime($dateval)),'payment_remark'=>$remark,"ispaid"=> 1);
            $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, "order" =>$getdata[0]['purchase_order_id'], "name"=>$getdata[0]['vendor_name'], "amount" => sprintf("%0.2f", $getdata[0]['amount']), "reference" => $remark, "datetime" => date("d M Y h:i a"));
            $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Vendor Disbursement' and  mail_to= 'All' ");
        }else{
            $getdata=simpleQuery("select p.purchase_order_id,p.affiliation_amount,concat(b.u_fname,' ',b.u_lname) as name,b.u_email as sendto From ".PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id JOIN ".BUYER." as b on p.affiliation_user=b.u_id where p.pur_id=" .$purchase_id);
            $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, "order" =>$getdata[0]['purchase_order_id'], "name"=>$getdata[0]['name'], "amount" => sprintf("%0.2f", $getdata[0]['affiliation_amount']), "reference" => $remark, "datetime" => date("d M Y h:i a"));
            $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Affiliation Disbursement' and  mail_to= 'All' ");
            $data = array('affiliation_payment_mode' => $mode,"affiliation_payment_date" =>date("Y-m-d H:i:s",strtotime($dateval)),'affiliation_payment_remark'=>$remark,"is_affiliation_paid"=> 1);
        }
        
        $que = updateQuery(PURCH, $data, "pur_id=" .$purchase_id);
        if($que){
            
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From:".SITENAME."<".EMAIL_SENDER.">". "\r\n";
            $headers .= 'Cc: '.EMAIL_ORDER ;
           
            $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
            $body_user = convertemailstr($replacement_array,$user_email[0]['body']);
            $sentmail_user = mail($getdata[0]['sendto'], $subject_user, $body_user, $headers);
           
            echo 1; 
        } else{ echo 0; }
    } 
    
    if($action == "generatevendor_report"){
        $fromdt = date("Y-m-d H:i",strtotime($fromdt));
        $todt = date("Y-m-d H:i",strtotime($todt));
        $getpaymentdata = selectQuery(VENDORPAYMENT,"*","payment_date  between  '".$fromdt."' and '".$todt."' and payment_status = 'Received'  order by pay_id DESC");
        $getpaymentdata_total = selectQuery(VENDORPAYMENT,"sum(price) as total_price","payment_date  between  '".$fromdt."' and '".$todt."' and payment_status = 'Received'  order by pay_id DESC");
        if(count($getpaymentdata)){ ?>
        <div class="row"> 
            <div class="col-sm-6 col-md-5 col-lg-5 col-xl-3 reg-update-dash">
                <div class="dash-update-tiles rounded bg-success mb-3 p-3 text-white position-relative ">
                    <span class="fa fa-file-text position-absolute bg-white text-success"></span>
                        <div class="dash-update-body">
                        <div class="h5" id="total_order"><?php echo count($getpaymentdata) ?></div>
                        <small>Total</small>
                        <h6 class="mb-0">Registrations</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-5 col-lg-5 col-xl-3 reg-update-dash ">
                <div class="dash-update-tiles rounded bg-primary mb-3 p-3 text-white position-relative ">
                    <span class="fa fa-inr position-absolute bg-white text-success"></span>
                    <div class="dash-update-body">
                        <div class="h5" id="total_amount"><?php echo $getpaymentdata_total[0]['total_price']; ?></div>
                        <small>Total</small>
                        <h6 class="mb-0">Amount</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Vendor Invoice Report</h2></div><div class="btn-actions-pane-right"></div></div>
            <div class="card-body">  
                <table class="table table-bordered mb-0 data-table w-100">
                    <thead><tr><th>#</th><th>Date</th><th>Time</th><th>Invoice ID</th><th>Vendor Name</th><th>Company Name</th><th>Address</th><th>GST NO</th><th>Plan Name</th><th>Price</th><th>CGST(%)</th><th>CGST</th><th>SGST(%)</th><th>SGST</th><th>Total</th></tr></thead>
                    <tbody>
                        <?php for($i=0;$i<count($getpaymentdata);$i++){
                        $getplandata = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$getpaymentdata[$i]['plan_id']."'"); 
                        $getdealer = selectQuery(VENDOR,"*","dealer_id=".$getplandata[0]['dealer_id']);
                        $taxonadminprice=$getplandata[0]['price']*(18/(100+18));
                        $seprate_tax = $taxonadminprice/2;
                        $priceWithoutGst = $getplandata[0]['price']-$taxonadminprice; ?>
                        <tr> 
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo date("d-m-Y", strtotime($getpaymentdata[$i]['payment_date'])); ?></td>
                            <td><?php echo date("h:i", strtotime($getpaymentdata[$i]['payment_date'])); ?></td>
                            <td><?php echo $getpaymentdata[$i]['plan_id']; ?></td> 
                            <td><?php echo $getdealer[0]['dealer_name']; ?></td>
                            <td><?php echo $getdealer[0]['shopname']; ?></td>
                            <td class="col-4"><?php echo $getdealer[0]['officeadress']; ?></td>
                            <td><?php echo $getdealer[0]['vatno']; ?></td>
                            <td><?php echo $getplandata[0]['plan'].""; ?></td>
                            <td><i class="fa fa-inr"></i><?php echo number_format($priceWithoutGst,2); ?></td>
                            <td><?php if($getplandata[0]['price'] == 0 ){ echo "0 %"; } else{echo "9 %"; } ?></td>
                            <td><?php if($getplandata[0]['price'] == 0 ){ echo "0 %"; } else{echo number_format($seprate_tax,2); } ?></td>
                            <td><?php if($getplandata[0]['price'] == 0 ){ echo "0 %"; } else{echo "9 %"; } ?></td>
                            <td><?php if($getplandata[0]['price'] == 0 ){ echo "0 %"; } else{echo number_format($seprate_tax,2); } ?> </td>
                            <td><i class="fa fa-inr"></i><?php echo $getplandata[0]['price'] ?></td>
                        </tr>
                        <? } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } else{ echo "<div class='text-center card card-body h6'>No record found</div>"; }
    }
    if($action == "generateprofit_report"){ 
        $getdata = selectQuery(ORDERSUB." as o JOIN ".ORDER." as m on o.order_id=m.id","o.vendor_nickname ,m.id,m.transaction_id,m.order_id,m.order_date,m.user_id,o.order_current_Status,o.item_id,o.display_product_name,o.product_image,o.user_per_unit_withoutgst_price,o.quantity,o.taxable_without_gst,o.discount_amount,o.cgst1,o.cgst2,o.sgst1,o.sgst2,o.igst1,o.igst2,o.total_with_gst,o.shipping_charges,o.total_payable,o.vendor_per_item_price_withoutgst , o.tax_percentage,m.is_cod,o.hsn_code,o.sku","(m.order_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND (o.order_current_Status='Delivered' OR o.order_current_Status='Partial Delivered' OR o.order_current_Status='Return Canceled')");
        if(count($getdata)){ ?>
        <div class="card mb-0"> 
            <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Profit Report</h2></div><div class="btn-actions-pane-right prof-rep-excel"></div></div>
            <div class="card-body">                
                <table class="table table-bordered mb-0 data-table w-100">
                    <thead><tr><th>#</th><th>Date</th><th>Time</th><th>Order Type</th><th>Order ID</th><th>Status</th><th>Product</th><th>HSN Code</th><th>SKU Code </th><th>Buyer</th><th>Vendor</th><th>Order Amount</th><th>Purchase Amount</th><th>Gross Profit</th><th>Shipping Amount</th><th>Tax Amount</th><th>Net Profit</th></tr></thead> 
                    <?php for($i=0;$i<count($getdata);$i++){ 
                    $getUser = selectQuery(BUYER,"u_fname,u_lname","u_id=".$getdata[$i]['user_id']);
                    $perPrice = $getdata[$i]['vendor_per_item_price_withoutgst'];
                    $quantity = $getdata[$i]['quantity'];
                    $tax = $getdata[$i]['tax_percentage'];
                    $without_gst_purchase = round($perPrice*$quantity);
                    $perchase_taxamount = round(($without_gst_purchase/100)* $tax);   
                    $withgstval_purchase = $without_gst_purchase + $perchase_taxamount;
                    $sale_tax_amount = $getdata[$i]['cgst2']+$getdata[$i]['sgst2']+$getdata[$i]['igst2'];
                    $total_tax = $sale_tax_amount - $perchase_taxamount;
                    $gross_profit = $getdata[$i]['total_payable'] - $withgstval_purchase;
                    $total_gross = $total_gross + $gross_profit; ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo date("d-m-Y",strtotime($getdata[$i]['order_date'])); ?></td>
                        <td><?php echo date("h:i:a",strtotime($getdata[$i]['order_date'])); ?></td>
                        <td><?php if($getdata[$i]['is_cod'] == 1 ){ echo "COD"; } else { echo "Online";}  ?></td>
                        <td><a href="<?php echo ADMINURL ?>order/order_details.php?transid=<? echo $getdata[$i]['transaction_id']; ?>"> <?php echo $getdata[$i]['order_id'] ?></a></td>
                        <td><?php echo $getdata[$i]['order_current_Status'] ?></td>
                        <td><?php echo $getdata[$i]['display_product_name'] ?></td>
                        <td><?php echo $getdata[$i]['hsn_code'] ?></td>
                        <td><?php echo $getdata[$i]['sku'] ?></td>
                        <td><?php echo $getUser[0]['u_fname']." ".$getUser[0]['u_lname']?></td>
                        <td><?php echo $getdata[$i]['vendor_nickname'] ?></td>
                        <td><?php echo $getdata[$i]['total_payable'] ?></td>
                        <td><?php echo $withgstval_purchase ?></td>
                        <td><?php echo $gross_profit;  ?></td>
                        <td><?php echo $getdata[$i]['shipping_charges'] ?></td>
                        <td><?php echo $total_tax ; echo "<br>(".$sale_tax_amount."<br>-".$perchase_taxamount.")"; ?> </td>
                        <td><?php echo $net = number_format($gross_profit - $getdata[$i]['shipping_charges'] - $total_tax,2); ?></td>
                    </tr> 
                    <?php $total_net = $total_net + $net; } ?>
                </table>
            </div>
        </div>        
        <?php  //echo "NET".$total_net;  echo "<br>";  echo "GROSS".$total_gross; echo "<br>"; 
    } else{
        echo "<div class='text-center card card-body h6'>No record found</div>";
    }  
 } ?>
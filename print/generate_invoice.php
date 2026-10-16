<?php require_once "../includes/configuration.php";
require_once '../mpdf/vendor/autoload.php';
require_once "../classes/shiprocket.php";
ini_set("display_errors",1);

    $css = cssparse('../css/dynamic-style.css');
    $bodytxtcolor = $css[".inv-bodycolor"]["color"];
    $tableheadbg = $css[".inv-tabhead"]["background-color"];
    $tableheadclr = $css[".inv-tabhead"]["color"];
    $textprimecolor = $css[".inv-primary-color"]["color"];
    $headstr = "background-color:".$tableheadbg.";color:".$tableheadclr; //he tak jith takaych ahe tithe
    $username = SHIPUSER; $pasword = SHIPPWD; $ship = new shiprocket($username,$pasword);
    $auth = $_GET['auth']; $bill = $_GET['bill'];  $inv = $_GET['inv'];  $billno=$_GET['billno'];
    if($auth!=""&&$bill!=""){

    $userType = base64_decode($auth); $billType = base64_decode($bill); $itemno = base64_decode($inv);  $orderid= base64_decode($billno);

    $data = "p.purchase_order_id,p.purchase_date,b.u_fname,b.u_lname,b.u_mobile,od.shippingAddr_name,od.shippingAddr_mobile,od.shippingAddr_address,od.shippingAddr_landmark,od.shippingAddr_city,od.shippingAddr_state,od.shippingAddr_country,od.shippingAddr_pincode,od.order_id,od.order_date,od.total_shipping_charges,od.total_bill_amount,od.discount_code,od.discount_amount as orddis,od.payable_amount,o.display_product_name,o.variation_values,o.user_per_unit_withoutgst_price,o.quantity,o.taxable_without_gst,o.discount_amount,o.cgst2,o.sgst2,o.igst2,o.total_with_gst,o.cgst1,o.sgst1,o.igst1,o.isFreeShipping,o.shipping_charges,o.total_payable,o.vendor_per_item_price_withoutgst,o.tax_percentage,o.order_current_Status,v.shopname,v.officecontactno,v.officeadress,v.locality,v.city,v.state,v.country,v.pin,v.vatno,o.hsn_code,o.sku";
    if($itemno!=""){
        $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id JOIN ".ORDER." as od on o.order_id= od.id LEFT JOIN ".VENDOR." as v on o.vendor=v.dealer_id LEFT JOIN ".BUYER." as b on od.user_id=b.u_id",$data,"o.item_id=".$itemno);
    }else if($orderid!=""){
        $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id JOIN ".ORDER." as od on o.order_id= od.id LEFT JOIN ".VENDOR." as v on o.vendor=v.dealer_id LEFT JOIN ".BUYER." as b on od.user_id=b.u_id",$data,"od.order_id='".$orderid."'");
    }

    $row= $getdata[0];

    /**************Purchase User**************/

    if(($userType=="user"&&$billType=="purchase")||($userType=="admin"&&$billType=="sale")){ /* user purchase bill user->adamin */
    $str = '<div class="maindiv" style="width:700px;margin:auto;font-family:arial; background-color:#fff;font-size:11px;color:'.$bodytxtcolor.';">
            <div class="top" style="text-align:center;color:'.$textprimecolor.'"><h2 style="margin-top:0;">TAX INVOICE </h2></div>
            <div class="header" style="width:100%;border-bottom:1px solid #9f9f9f;padding-bottom:10px;">
                <table style="font-family:arial;font-size:11px;vertical-align:top;">
                    <tr>
                        <td class="left"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="Logo" height="60"></td>
                        <td style="width:40%;line-height:16px;color:'.$bodytxtcolor.';">
                            <h4 style="margin:0 0 10px 0;">'.SITENAME.'</h4>
                            <div>'.$getconfigdetails[0]["address"].' '.$getconfigdetails[0]["pincode"].'</div>
                            <div style="margin-bottom:5px;">Office Phone No : '.$getconfigdetails[0]["Main_admin_contact"].'</div>
                            <div><b>GSTIN : </b>'.$getconfigdetails[0]["gst_no"].'</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="mainsection">
                <div>
                    <div style="width:100%;padding:15px 0;">
                         <div style="width:33%;float:left;">
                            <div><h4 style="margin-top:0;margin-bottom:5px;"><b>Buyer</b></h4></div>
                            <div>'.($row['u_fname']!=""||$row['u_lname']!=""?$row['u_fname']." ".$row['u_lname']:$row['shippingAddr_name']).'</div>
                            <div style="margin:5px 0;">Contact No : '.($row['u_mobile']?$row['u_mobile']:($row['shippingAddr_mobile']!="0000000000"?$row['shippingAddr_mobile']:"")).'</div>
                        </div>
                        <div style="width:34%;float:left;">
                            <div><h4 style="margin-top:0;margin-bottom:5px;"><b>Shipping Address</b></h4></div>
                            <div style="line-height:16px;">'.$row['shippingAddr_address'].'<br>'.$row['shippingAddr_landmark'].($row['shippingAddr_landmark']?"<br>":"").$row['shippingAddr_city'].' '.$row['shippingAddr_state'].($row['shippingAddr_city']?"<br>":"").$row['shippingAddr_country'].' '.$row['shippingAddr_pincode'].'</div>
                        </div>
                        <div style="width:33%;float:left;">
                            <table style="width:100%;font-family:arial;font-size:11px;vertical-align:top;color:'.$bodytxtcolor.';">
                                <tr><td style="padding:0 15px 5px 5px;border:0;"><b>Tax Invoice No</b></td><td style="border:0;padding:0 0px 5px 0px;"><b>'.$row['order_id'].'</b></td></tr>
                                <tr><td style="padding-left: 5px;padding-right:15px;border:0;">Date & Time</td><td style="border: 0;padding-right:0;">'.date("d M Y h:i a",strtotime($row['order_date'])).'</td></tr>
                            </table>
                        </div>
                    </div>
                <div>
                <table cellpadding="0" cellspacing="0" style="font-family:arial;width:100%;font-size:11px;border-collapse:collapse;color:'.$bodytxtcolor.'">
                    <thead><tr>
                            <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Item</th>
                             <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">HSN Code</th>
                             <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">SKU Code</th>
                            <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Rate</th>
                            <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Qty</th>
                            <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Amount</th>';
                                if($row['discount_amount']!=0.00){
                                    $str.= '<th style="padding: 8px 10px;border-color:'.$tableheadbg.';text-align:left;'.$headstr.'">Discount</th> ';
                                }
                                
                                    $str.= '<th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">CGST</th>
                                    <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">SGST</th>';
                               
                                    $str.= '<th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">IGST</th> ';
                              
                            $str.= '<th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Total</th>
                        </tr></thead>
                    <tbody> ';
                     $minus=0;
                    for($i=0;$i<count($getdata);$i++){
                        $row1=$getdata[$i];
                        $status=$row1['order_current_Status'];
                        $str.= '<tr style="text-align:left;">
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['display_product_name'].'<br>'.$row1['variation_values'];
                             if($status=="Canceled"){
                               $str.='<br><b style="color:red">Canceled</b>';
                               $minus+= $row1['total_payable'];
                             }
                           $str.='</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['hsn_code'].'</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['sku'].'</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">₹'.$row1['user_per_unit_withoutgst_price'].'</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['quantity'].'</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['taxable_without_gst'].'</td>';
                            if($row1['discount_amount']!=0.00){
                                $str.='<td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['discount_amount'].'</td> ';
                            }
                           
                                $str.='<td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['cgst2'].' ('.$row1['cgst1'].'%)</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['sgst2'].' ('.$row1['sgst1'].'%)</td>';
                           
                                $str.='<td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row1['igst2'].' ('.$row1['igst1'].'%)</td>';
                          

                            $str.= '<td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">₹'.($status=="Canceled"?"-":""). $row1['total_payable'].'</td>
                        </tr>';
                    }


                  $str.= '</tbody>
                </table>
            </div>
            <div style="padding:15px 0 15px 0;border-bottom:1px solid #9f9f9f;">
                <table cellpadding="0" cellspacing="0" align="right" style="font-family:arial;width:35%;font-size:12px;border-collapse:collapse;">
                    <tr><td style="border: 0;"><b>Shipping Charges</b></td><td style="border: 0;text-align:right;"><b>₹'.$row['total_shipping_charges'].'</b></td></tr>';
                    if($row['orddis']!=0){
                        $str.= '<tr><td style="border: 0;"><b>Discount ('.$row["discount_code"].')</b></td><td style="border: 0;text-align:right;"><b>₹'.sprintf("%0.2f",($row['orddis'])).'</b></td></tr>';
                      }
                    if($row['isFreeShipping']==1){ $str.= ' <tr><td style="border: 0;"><b>Shipping Discount</b></td><td style="border: 0;text-align:right;"><b>- ₹'.$row['total_shipping_charges'].'</b></td></tr>'; }
                  
                   $str.= ' <tr><td style="border: 0;"><b>Grand Total</b></td><td style="border: 0;text-align:right;"><b>₹'.sprintf("%0.2f",($row['payable_amount'])).'</b></td></tr>
                </table>
            </div>
            <div class="condition" style="border-bottom: 1px solid #9f9f9f;padding:15px 0 8px 0;">
                <h3 style="margin-bottom:10px;margin-top:0;">Terms and Conditions</h3>
               '.$getconfigdetails[0]["gst_desc"].'
            </div>
            <div style="padding-top:15px;">
                <div class="footerleft" style="width:50%;float:left;text-align:center;"><div style="margin-top: 70px;">Prepared By</div></div>
                <div class="footerright" style="width:50%;float:left;">
                    <h4 style="margin:0;text-align:center;">For '.SITENAME.'</h4>
                    <div style="margin-top: 53px;text-align:center;">Authorised Signatory</div>
                </div>
            </div>
        </div>
    </div>';
    /**************Purchase Admin***************/
    }else if(($userType=="admin"&&$billType=="purchase")||($userType=="vendor"&&$billType=="sale")){ /*admin purchase bill admin->vendor */
    $token = $ship->authenticate();
    $seller = $row["pin"];  $dataUserPin= array("postcode"=>$seller);
    $resUPin = $ship->getPincodeData($token,$dataUserPin);  $pinResUser= $resUPin['postcode_details'];
    $sellerState = $pinResUser['state'];
    $data = array("postcode"=>$getconfigdetails[0]["pincode"]);
    $res = $ship->getPincodeData($token,$data);  $pinRes= $res['postcode_details']; $adminState=$pinRes['state'];
    $tax = $row["tax_percentage"];   $qty=$row['quantity'];  $price=$row['vendor_per_item_price_withoutgst'];
    $taxable = round($price*$qty,2);
    $taxamount = round(($taxable/100)* $tax);
    $cgst1=0;$sgst1=0;$igst1=0;  $cgst2=0;$sgst2=0;$igst2=0;
    if($sellerState==$adminState){
    $cgst1 = round($tax/2,2);$sgst1=round($tax/2,2); $cgst2=round($taxamount/2);$sgst2=round($taxamount/2);
    }else{$igst1=$tax; $igst2=$taxamount;}
    $total = $taxable+$taxamount;
       $str='<div class="maindiv" style="width:700px;margin:auto;font-family:arial; background-color:#fff;font-size:11px;color:'.$bodytxtcolor.'">
            <div class="top" style="text-align:center;color:'.$textprimecolor.'"><h2 style="margin-top:0;margin-bottom:10px;">TAX INVOICE</h2></div>
            <div class="header" style="width:100%;border-bottom:1px solid #9f9f9f;padding-bottom:15px;">
                <div style="width:33%;float:right;">
                        <table style="width:100%;font-family:arial;font-size:11px;vertical-align:top;color:'.$bodytxtcolor.'">
                            <tr><td style="padding:0 15px 3px 5px;border:0;"><b>Tax Invoice No</b></td><td style="border:0;padding:0 0px 3px 0px;"><b>'.$row['purchase_order_id'].'</b></td></tr>
                            <tr><td style="padding-left: 5px;padding-right:15px;border:0;">Date & Time</td><td style="border: 0;padding-right:0;">'.date("d M Y h:i a",strtotime($row['purchase_date'])).'</td></tr>
                        </table>
                    </div>
            </div>
            <div class="mainsection">
                <div style="width:100%;padding:15px 0;">
                     <div style="width:45%;float:left;padding-right:20px;">
                        <div><h3 style="margin-top:0;margin-bottom:5px;"><b>Sold By</b></h3></div>
                        <div style="line-height:17px;">
                        <div>'.$row["shopname"].'</div>
                        <div><b>Contact No : </b>'.$row["officecontactno"].'</div>
                        <div><b>Address : </b>'.$row["officeadress"].'<br> '.$row["locality"].'<br> '.$row["city"].' '.$row["state"].' <br>'.$row["country"].' '.$row["pin"].'</div>
                         <div><b>GSTIN/VAT : </b>'.$row["vatno"].'</div>
                         </div>
                    </div>
                     <div style="width:50%;float:left;">
                        <div class="upperdiv">
                        <div><h3 style="margin-top:0;margin-bottom:5px;"><b>Buyer</b></h3></div>
                        <div style="line-height:17px;">
                           <div>'.SITENAME.'</div>
                        <div><b>Contact No : </b>'.$getconfigdetails[0]["Main_admin_contact"].'</div>
                         <div><b>Address : </b>'.$getconfigdetails[0]["address"].'<br> '.$getconfigdetails[0]["pincode"].'</div>
                        </div>
                         <div><b>GSTIN : </b>'.$getconfigdetails[0]["gst_no"].'</div>
                         </div>
                    </div>
                </div>
                <div class="productdetail" style="width:100%;border:0">
                    <table cellpadding="0" cellspacing="0" style="font-family:arial;width:100%;font-size:11px;border-collapse:collapse;color:'.$bodytxtcolor.'">
                        <thead>
                            <tr style="'.$headstr.'">
                                <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Item</th>
                                <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">HSN Code</th>
                                <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">SKU Code</th>
                                <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Rate</th>
                                <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Qty</th>
                                <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Amount</th>';
                                if($cgst1!=0){
                                    $str.='<th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">CGST</th>
                                    <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">SGST</th>';
                                }else{
                                    $str.='<th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">IGST</th> ';
                                }
                                $str.='<th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row['display_product_name'].'</td>
                                <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row['hsn_code'].'</td>
                                <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row['sku'].'</td>
                                <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">₹'.$price.'</td>
                                <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$row['quantity'].'</td>
                                <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$taxable.'</td>';
                                     if($cgst1!=0){
                                      $str.= ' <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$cgst2.' ('.$cgst1.'%)</td>
                                        <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$sgst2.' ('.$sgst1.'%)</td>';
                                    }else{
                                       $str.= '<td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$igst2.' ('.$igst1.'%)</td>';
                                    }
                                $str.= '<td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">₹'.$total.'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="padding:15px 0;border-bottom:1px solid #9f9f9f;">
                    <table cellpadding="0" cellspacing="0" align="right" style="font-family:arial;width:25%;font-size:12px;border-collapse:collapse;">
                    <tr><td style="border: 0;"><b>Grand Total</b></td><td style="border: 0;text-align:right;"><b>₹'.$total.'</b></td></tr>
                    </table>
                </div>
                <div class="condition" style="border-bottom: 1px solid #9f9f9f;padding:15px 0 8px 0;">
                    <h3 style="margin-bottom:10px;margin-top:0;">Terms and Conditions</h3>
                    '.$getconfigdetails[0]["gst_desc"].'
                </div>
                <div style="padding-top:15px;">
                    <div class="footerleft" style="width:50%;float:left;text-align:center;"><div style="margin-top: 70px;">Prepared By</div></div>
                    <div class="footerright" style="width:50%;float:left;">
                        <h4 style="margin:0;text-align:center;">For '.$row["shopname"].'</h4>
                        <div style="margin-top: 53px;text-align:center;">Authorised Signatory</div>
                    </div>
                </div>
            </div>
        </div>';
}

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8']);

$mpdf->WriteHTML($str);
$mpdf->autoLangToFont = true;
$mpdf->autoScriptToLang = true;
$mpdf->baseScript = 1;
//call watermark content aand image

  $mpdf->SetWatermarkText(SITENAME);


$mpdf->showWatermarkText = true;
$mpdf->watermarkTextAlpha = 0.1;


$mpdf->Output();

} ?>
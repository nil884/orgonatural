<?php

function sendmail($txnid,$order=null){

    $orderdata = $order->getOrderFullDetails($txnid); 

$all_item_detail = ""; $free_ship = "";

     $getbuyer_details=selectQuery(BUYER,"u_id,u_fname,u_lname,u_email,u_mobile","u_id=".$orderdata['user_id']); 
     $item_detail =  $order->getVendororder_item($orderdata['id']);
         for($i=0;$i<count($item_detail);$i++) {   
          $order_item = $item_detail[$i];
             if($order_item['product_image']){
                $img = SITEURL."/img/order_img/".$order_item['product_image'];
            }else{
                $img = SITEURL."/img/projectimage/product-default.png";
            }
            if($order_item['isFreeShipping'] ==1  && $order_item['shipping_charges'] != 0 ){ $free_ship = "Free Shipping";  }  
         if($order_item['isFreeShipping'] ==1  && $order_item['shipping_charges'] != 0 ){ $free_ship_charge = "0    <del>".$order_item['shipping_charges']."</del>";  } else {$free_ship_charge = $order_item['shipping_charges']; ; }  
   
         
         if($order_item['discount_code']!=""){ $promo_details = "<div>-".$order_item['discount_amount']."  (".$order_item['discount_code'].")</div>";   } else { $promo_details = "NA"; }
           $all_item_detail= $all_item_detail."<tr>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'>
            <img src='".$img."' style='width:100px;max-width:100%;' alt='item-thumb'>
            </td> 
            <td style='padding:10px 12px;border:1px solid #D9D9D9;'>
            <span style='margin-bottom:10px;display:block;font-weight: 600;'>".$order_item['product_name']."</span>
            <div style='color:green;margin-bottom:5px;'>".$free_ship."</div>
            </td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'>".$order_item['taxable_without_gst']."</td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'>".$order_item['quantity']."</td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'>".$promo_details."</td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'><small>". $order_item['cgst1']."% </small><br><b>".$order_item['cgst2']."</b></td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'><small>" .$order_item['sgst1']."% </small><br><b>".$order_item['sgst2']."</b></td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'><small>" .$order_item['igst1']."% </small><br><b>".$order_item['igst2']."</b></td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'>". $free_ship_charge."</td>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'><b>".$order_item['total_payable']."</b></td>
            
             </tr>";

     }
     $replacement_array = array(
        'siteurl' => SITEURL,
        'sitename' => SITENAME,
        'smssitename' => SMSSITENAME,
        'username' => $getbuyer_details[0]['u_fname']." ".$getbuyer_details[0]['u_lname'],
         'order_id' => $orderdata['order_id'],
         'payment_mode' => ($orderdata['payment_mode']=="COD"?"COD":"ONLINE"),
        'product_detail' => $all_item_detail,
        'shipping_name' => $orderdata['shipping_name'], 
        'address' =>$orderdata['address'],
        'landmark'=>$orderdata['landmark'],
        'city' =>$orderdata['city'],
        'state' =>$orderdata['state'],
        'country' =>$orderdata['country'],
        'pincode'=>$orderdata['pincode'],
        'shipping_mobile'=> $orderdata['shipping_mobile'],
     );
    
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     $headers .= "From:".SITENAME."<".EMAIL_SENDER.">"; 
     
     $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Order' and  mail_to= 'Admin' ");
     $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
     $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);
     mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers); 

     $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Order' and  mail_to= 'Buyer' ");
     $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
     $body_user =convertemailstr($replacement_array,$user_email[0]['body']);
     mail($getbuyer_details[0]['u_email'], $subject_user, $body_user, $headers); 
    
     
     if(SMS_SYSTEM=="ON"){
    $buyer_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Order' and  sms_to = 'Buyer' ");
    $msg = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
     $templateId= $buyer_sms[0]['templateId'];
    $sms= sendsms($getbuyer_details[0]['u_mobile'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
    $id=(unserialize($sms));
    $msid=$id['data']['0']['id'];  
    $status=$id['data']['0']['status'];
    $datasms=array(
   "msg_id"=>$msid,
   "msg_type"=>"Order Placed SMS To Buyer",
   "user_id" =>   $getbuyer_details[0]['u_id'], 
   "user_name"=> $getbuyer_details[0]['u_fname']." ".$getbuyer_details[0]['u_lname'],
   "mobile_no"=> $getbuyer_details[0]['u_mobile'],
   "message"=>$msg,
   "date"=>date("Y-m-d H:i:s"),
   "status"=>$status
);
insertQuery(SMS,$datasms);

    $admin_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Order' and  sms_to = 'Admin' ");
  $arr = explode(",",ADMINCONTACT);
        for($k=0;$k<sizeOf($arr);$k++)
        {
            $tempmob = $arr[$k];
            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
            $templateId = $admin_sms[0]['templateId'];
            $sms1= sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
            $id1=(unserialize($sms1));
            $msid1=$id1['data']['0']['id'];
            $status1=$id1['data']['0']['status'];
            $datasms1=array(
              "msg_id"=>$msid1,
              "msg_type"=>"Order Placed SMS To Admin",
              "user_name"=>"Admin",
              "mobile_no"=>$tempmob,
              "message"=>$msg,
              "date"=>date("Y-m-d H:i:s"),
              "status"=>$status1,
             
            );
            insertQuery(SMS,$datasms1);
        }
     }

   
  $item_detail = $order->getVendororder_item($orderdata['id']); 

 
    $getsub = selectQuery(ORDERSUB,'DISTINCT vendor','order_id='.$orderdata['id']);
    for($i=0;$i<count($getsub);$i++) { 
      $all_item_detail="";
      $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","*","p. reference_order_id =".$orderdata['id']." AND o.vendor=".$getsub[$i]['vendor']." order by p.pur_id DESC");
      for($g=0;$g<count($getdata);$g++) { 
            $order_item = $getdata[$g];
            if($order_item['product_image']){
              $img = SITEURL."/img/order_img/".$order_item['product_image'];
            }else{
              $img = SITEURL."/img/projectimage/product-default.png";
            }
            $all_item_detail= $all_item_detail."<tr>
            <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'>
            <img src='".$img."' style='width:100px;max-width:100%;' alt='item-thumb'>
            </td> 
            <td style='padding:10px 12px;border:1px solid #D9D9D9;'>
            <span style='margin-bottom:10px;display:block;font-weight: 600;'>".$order_item['product_name']."</span>
           </td>
           <td style='padding:10px 12px;border:1px solid #D9D9D9;width: 80px;'>".$order_item['quantity']."</td>
           </tr>";
      }

        $replacement_array = array(
          'siteurl' => SITEURL,
          'sitename' => SITENAME,
          'smssitename' => SMSSITENAME,
          'username' => $getbuyer_details[0]['u_fname']." ".$getbuyer_details[0]['u_lname'],
          "vendor_name" => $order_item['vendor_nickname'], 
           'order_id' => $orderdata['order_id'],
          'product_detail' => $all_item_detail
          
       );
       $vendor_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Order' and  mail_to='Vendor'");
       $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
       $body_vendor =convertemailstr($replacement_array,$vendor_email[0]['body']);
        mail($order_item['vendor_email'], $subject_vendor, $body_vendor, $headers); 
     
     
     if(SMS_SYSTEM=="ON")
     {
        $vendor_sms =  selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Order' and  sms_to = 'Vendor'");
        $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
          $templateId = $vendor_sms[0]['templateId'];
        $sms = sendsms($order_item['vendor_contact'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
        $id=(unserialize($sms));
        $msid=$id['data']['0']['id'];  
        $status=$id['data']['0']['status'];
        $datasms=array(
          "msg_id"=>$msid,
          "msg_type"=>"Order Placed SMS To Vendor",
          "user_name"=> $order_item['vendor_nickname'],
          "mobile_no"=>$order_item['vendor_contact'],
          "message"=>$msg,
          "date"=>date("Y-m-d H:i:s"),
          "status"=>$status
        );
        insertQuery(SMS,$datasms);
      } 
    }

 

}
 ?>
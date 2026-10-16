<?php include("../includes/configuration.php");
    include("../classes/product.php");
    include("../classes/user.php");
    $imgtype = "product";
    include("../getimgpath.php");
    $prod = new Product();
    $user = new User();
    require_once '../mpdf/vendor/autoload.php';
    $filename1 = "";$filename2 = ""; $filename3 = "";$filename4 = "";$filename5 = "";$filename6 = "";
    if(ADDRESSDETAIL != ""){ $adress = ADDRESSDETAIL; } else{ $adress = "NA";}
    if(ADMINCONTACT != ""){ $admin_contact = ADMINCONTACT; } else{ $admin_contact = "NA";}
    if(TAXTYPE == "GST"){ $tax_str = "<b>GST NO : </b>".GSTNO; } 
    else if(TAXTYPE == "VAT"){ $tax_str = "<b>VAT NO : </b>".VATNO;}
    $loguser = $_SESSION['reguser'];
    $getdetails = selectQuery(STATIC_PAGE,"terms_condition_data,terms_condition","id= 1");  
    //$loguser = 22;
    $getlogin_user = $user -> getUserDetails('u_fname,u_lname,u_email,tax_no,company_name,company_address ',$loguser);
    $username = $getlogin_user[0]['u_fname'].' '.$getlogin_user[0]['u_lname'];
    $email = $getlogin_user[0]['u_email'];
    $proddata = array();
    $getcart = selectQuery(CART,"*","user_id=".$loguser." and type='WISHLIST'");
    for($i=0;$i<count($getcart);$i++){                    
        $product = $getcart[$i]['prod_id']; $cart_id= $getcart[$i]['cart_id'];  $quantity= $getcart[$i]['quantity'];
        $productdata = $prod->getProductFullDetails($product);
        $sub = array("productid"=>$product,"quantities"=>$quantity,"withGst"=> $productdata['withGst'], "perUnitPrice"=>$productdata['withoutGst'],"name"=>addslashes($productdata['name']),"company"=>$productdata['company'],"vendorId"=>$productdata['vendorId'],"vendor"=>$productdata['vendor'],"image"=>$productdata['image'],"stock"=>$productdata['stock'],"variations"=>$productdata['variations'],"currentVariartions"=>$productdata['currentVariartions'],"tax"=>$productdata['tax']); $sub1 = array("productid"=>$product,"quantities"=>$quantity,"perUnitPrice"=>$productdata['price'],"vendorId"=>$productdata['vendorId'],"tax"=>$productdata['tax']);
        array_push($proddata,$sub); 
    }
    $strcmp = "";
    if($getlogin_user[0]['company_name'] != "" || $getlogin_user[0]['company_address'] !=""  || $getlogin_user[0]['tax_no'] != "" ){
        $strcmp = $strcmp."<div style='width:100%;float:left;padding:15px 15px;border-top:1px solid #777'> <b>To</b>";
        $strcmp = $strcmp."<br> <b>Name : </b>".$getlogin_user[0]['company_name'];
        if($getlogin_user[0]['company_address'] != ""){
            $strcmp = $strcmp."<br> <b>Address : </b>".$getlogin_user[0]['company_address'];
        }
        if($getlogin_user[0]['tax_no'] != ""){
            $strcmp = $strcmp."<br><b>GST No : </b>".$getlogin_user[0]['tax_no'];
        }
        $strcmp = $strcmp."</div>";      
    }
    $str = '<div style="font-family:calibri;width:700px;margin:auto;"><h3 style="margin:0 0 15px 0; text-align:center;font-size: 20px;">QUOTATION</h3></div>
    <div style="font-family:calibri;width:700px;margin:auto;font-size:12px;border:1px solid #777;padding:3px;">
        <div style="border:1px solid #777;overflow:hidden;margin-bottom:3px;">
            <div style="width:48%;float:left;padding:15px 15px;"><img src= "'.SITEURL.'/img/projectimage/logo.png" alt="Logo" width="160"></div>';
            $str = $str.'<div style="width:45%;float:left;padding:15px 15px 15px 0;">
            <div style="margin-bottom:1px;"><b>Address : </b>'.$adress.'</div>
            <div style="margin-bottom:1px;">'.$tax_str.'</div>
            <div style="margin-bottom:1px;"><b>Phone No : </b>'.$admin_contact.'</div>
            <div style="margin-bottom:1px;"><b>Date :</b> '.date('d F Y').'</div>
           </div>
           '.$strcmp.'
    </div>';     
    $str = $str.'<table style="border:1px solid #777;border-collapse: collapse;font-size:12px;width:100%;font-family:calibri;" cellpadding="0" cellspacing="0">
        <thead>
            <tr style="text-align:left;">
                <th style="border:1px solid #777;padding:10px;">Items</th>
                <th style="border:1px solid #777;padding:10px;">Details</th>
                <th style="border:1px solid #777;padding:10px;">Price Per Unit</th>
                <th style="border:1px solid #777;padding:10px;">Quantity</th>
                <th style="border:1px solid #777;padding:10px;">Taxable Amount</th>
                <th style="border:1px solid #777;padding:10px;text-align:left;">GST %</th>
                <th style="border:1px solid #777;padding:10px;text-align:left;">Tax Payable</th>
                <th style="border:1px solid #777;padding:10px;">Total</th>
            </tr>
        </thead>
    <tbody>';
    if(count($proddata)){ ?>
        <? for($i=0;$i<count($proddata);$i++){
            $varstr = ""; ?>   
            <?php if($proddata[$i]['name'] != ""){
                $images = $proddata[$i]['image'];
                $variations = $proddata[$i]['variations']; $currentvar = $proddata[$i]['currentVariartions'];
                $variationarr = array();  $varcnt=0;
                foreach($variations as $key=>$val){ $variationarr[$key]= $currentvar[$varcnt];  $varcnt++; } 
                if(count($variationarr)){ 
                    foreach($variationarr as $key=>$val){ 
                        $varstr = $varstr.'<span class="text-muted">'.$key.'</span> : '.$val.'</span>&nbsp;';
                    }
                }
                $unit_total = $proddata[$i]['perUnitPrice'] * $proddata[$i]['quantities'];
                $tax_amount = ($proddata[$i]['perUnitPrice']*$proddata[$i]['tax']/100) * $proddata[$i]['quantities'];
                $total_tax_amount = $total_tax_amount + $tax_amount;  
                $with_tax = $proddata[$i]['withGst'] * $proddata[$i]['quantities'];
                $all_total = $all_total + $with_tax;
                $all_unit_total = $all_unit_total + $unit_total;
                if(count($images)){ $img= SITEURL."/".$thumb2_path."/".$images[0]['img_name'];}
                else{ $img = SITEURL."/img/projectimage/product-default.png";  } ?>
                <?php $str = $str.'<tr> ';
                $str = $str.'<td style="border:1px solid #777;padding:10px;text-align:center;"><img src='.$img.' height="80" alt="order-item-thumb"></td>
                <td style="border:1px solid #777;padding:10px;">
                <h4 style="margin:0 0 10px 0;">'.$proddata[$i]['name'].'</h4>
                <div style="margin-right:10px;">'.$varstr.'</div></td>
                <td style="border:1px solid #777;padding:10px;">₹'.$proddata[$i]['perUnitPrice'].'</td>
                <td style="border:1px solid #777;padding:10px;">'.$proddata[$i]['quantities'].'</td>        
                <td style="border:1px solid #777;padding:10px;">₹'.$unit_total.'</td>
                <td style="border:1px solid #777;padding:10px;">'.$proddata[$i]['tax'].' %</td>
                <td style="border:1px solid #777;padding:10px;">₹'.$tax_amount.'</td>
                <td style="border:1px solid #777;padding:10px;">₹'.$with_tax.'</td>';
                $str = $str.'</tr> ';
            }
        } 
    }
    // $str = $str.'<tr> <td> </td> <td> </td> <td style="border:1px solid #777;padding:10px;">₹'.$total_per_unit.'</td> <td style="border:1px solid #777;padding:10px;">'.$total_quantity.'</td>  <td style="border:1px solid #777;padding:10px;">₹'.$all_unit_total.'</td> <td style="border:1px solid #777;padding:10px;"> </td> <td style="border:1px solid #777;padding:10px;" >₹'.$all_total.'</td></tr>';
    $str = $str."<tr style='font-weight:600;font-size:16px;'><td style='border:1px solid #777;padding:10px;text-align:right;' colspan='7'><b>Sub Total</b></td><td style='border:1px solid #777;padding:10px;'><b>₹".$all_unit_total."</b></td></tr>";
    $str = $str."<tr style='font-weight:600;font-size:16px;'><td style='border:1px solid #777;padding:10px;text-align:right;' colspan='7'><b>GST Total</b></td><td style='border:1px solid #777;padding:10px;'><b>₹".$total_tax_amount."</b></td></tr>";
    $str = $str."<tr style='font-weight:600;font-size:16px;'><td style='border:1px solid #777;padding:10px;text-align:right;' colspan='7'><b>Grand Total</b></td><td style='border:1px solid #777;padding:10px;'><b>₹".$all_total."</b></td></tr>";
    $str = $str.'</tbody></table>';
    if( trim($getdetails[0]['terms_condition_data']) == 1){
        $str = $str.'
        <div style="border:1px solid #777;padding:5px 15px;margin-top:3px;">
        <h3>Terms and Conditions</h3>'.$getdetails[0]['terms_condition'].'</div>';
    }
    $str = $str.'
    <div style="border:1px solid #777;padding:15px 15px;margin-top:3px;text-align:right;">
        <span style="border-bottom:1px solid #777;display:inline-block;padding-bottom:5px;margin-bottom:5px;"><b>For '.SITENAME.'</b></span>
        <div>(This is electronically generated quotation, it does not required signature.)</div>
    </div>
</div>';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($str);
$filename = date("Ymdhis")."quotation.pdf";
$mpdf->Output('../img/wishlist_quotation/'.$filename);
$filename1 = '../img/wishlist_quotation/'.$filename;
$replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME,'smssitename' => SMSSITENAME,"name" => $username,'email' => $email ,);
$admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Wishlist Quotation' and  mail_to= 'Admin' "); 
$subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
$body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
$sentmail = mail_multiattachment($filename1,$filename2, $filename3,$filename4,$filename5,$filename6,EMAIL_ORDER, EMAIL_SENDER, SITENAME, $email, $subject_admin, $body_admin);
$buyer_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Wishlist Quotation' and  mail_to= 'Buyer' "); 
$subject_buyer = convertemailstr($replacement_array,$buyer_email[0]['subject']);
$body_buyer = convertemailstr($replacement_array,$buyer_email[0]['body']);
$sentmail = mail_multiattachment($filename1,$filename2, $filename3,$filename4,$filename5,$filename6,$email, EMAIL_ORDER, SITENAME, $email, $subject_buyer, $body_buyer);
echo 1; ?> 
   
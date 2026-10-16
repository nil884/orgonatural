<?php require_once "../includes/configuration.php";
require_once '../mpdf/vendor/autoload.php';
$css = cssparse('../css/dynamic-style.css');

$dealerid = base64_decode($_REQUEST['vendor']);
    $payid = base64_decode($_REQUEST['inv']);
    $getpaymentdata = selectQuery(VENDORPAYMENT,"*","pay_id=".$payid);
    $getdealer = selectQuery(VENDOR,"*","dealer_id=".$dealerid);
    $getcurrplan = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$getpaymentdata[0]['plan_id']."'");
    $bodytxtcolor = $css[".inv-bodycolor"]["color"];
    $tableheadbg = $css[".inv-tabhead"]["background-color"];
    $tableheadclr = $css[".inv-tabhead"]["color"];
    $textprimecolor = $css[".inv-primary-color"]["color"];
    $headstr = "background-color:".$tableheadbg.";color:".$tableheadclr; 
     $str = '<div class="maindiv" style="width:700px;margin:auto;font-family:arial; background-color:#fff;font-size:11px;color:'.$bodytxtcolor.';">
            <div class="top" style="text-align:center;color:'.$textprimecolor.'"><h2 style="margin-top:0;">TAX INVOICE </h2></div>
            <div class="header" style="width:100%;border-bottom:1px solid #9f9f9f;padding-bottom:10px;">
                <table style="font-family:arial;font-size:11px;vertical-align:top;">
                    <tr>
                        <td class="left"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="Logo" height="25"></td>
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
                            <div><h4 style="margin-top:0;margin-bottom:5px;"><b>Vendor</b></h4></div>
                            <div>'.$getdealer[0]['dealer_name'].'</div>
                            <div style="margin:5px 0;">Contact No : '.$getdealer[0]['personalcontactno'].'</div>
                        </div>
                        <div style="width:34%;float:left;">
                            <div><h4 style="margin-top:0;margin-bottom:5px;"><b> Address</b></h4></div>
                            <div style="line-height:16px;">'.$getdealer[0]['officeadress'].'<br>'.$getdealer[0]['locality'].'<br>'.$getdealer[0]['city'].' '.$getdealer[0]['state'].'<br>'.$getdealer[0]['country'].' '.$getdealer[0]['pin'].'</div>
                        </div>
                        <div style="width:33%;float:left;">
                              <table style="width:100%;font-family:arial;font-size:11px;vertical-align:top;color:'.$bodytxtcolor.';">
                                <tr><td style="padding:0 15px 5px 5px;border:0;"><b>Tax Invoice No</b></td><td style="border:0;padding:0 0px 5px 0px;"><b>'.$getpaymentdata[0]['plan_id'].'</b></td></tr>
                                <tr><td style="padding-left: 5px;padding-right:15px;border:0;">Date & Time</td><td style="border: 0;padding-right:0;">'.date("d M Y h:i a",strtotime($getpaymentdata[0]['payment_date'])).'</td></tr>
                            </table>
                        </div>
                        </div>
                    </div>
                <div>
                <table cellpadding="0" cellspacing="0" style="font-family:arial;width:100%;font-size:11px;border-collapse:collapse;color:'.$bodytxtcolor.'">
                    <tr>
                        <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Plan Name</th>
                        <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">HSN Code</th>
                        <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Duration</th>
                        <th style="padding: 8px 10px;border:1px solid '.$tableheadbg.';text-align:left;'.$headstr.'">Amount</th>
                    
                    </tr>  
                    <tbody>
                        <tr style="text-align:left;">
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$getcurrplan[0]['plan'].'</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.PLANHSNCODE.'</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$getcurrplan[0]['plan_from'].' To '.$getcurrplan[0]['plan_to'].'</td>
                            <td style="padding: 10px 10px;border:1px solid '.$tableheadbg.';">'.$getcurrplan[0]['price'].'</td>    
                         </tr>        
                   </tbody>
                  </table>
               </div>    

                <div class="condition" style="border-bottom: 1px solid #9f9f9f;padding:15px 0 8px 0;">
                    <h3 style="margin-bottom:10px;margin-top:0;">Terms and Conditions</h3>
                    '.$getconfigdetails[0]["gst_desc"].'
                </div>
                <div style="padding-top:15px;">
                    <div class="footerleft" style="width:50%;float:left;text-align:center;"><div style="margin-top: 70px;">Prepared By</div></div>
                    <div class="footerright" style="width:50%;float:left;">
                        <h4 style="margin:0;text-align:center;">For '.$getdealer[0]["shopname"].'</h4>
                        <div style="margin-top: 53px;text-align:center;">Authorised Signatory</div>
                    </div>
                </div>
            </div>
        </div>
            ';
//echo $str;
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($str);
$mpdf->autoLangToFont = true;
$mpdf->autoScriptToLang = true;
$mpdf->baseScript = 1;
//call watermark content
$mpdf->SetWatermarkText(SITENAME);
$mpdf->showWatermarkText = true;
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->Output();




?>
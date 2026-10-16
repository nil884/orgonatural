<?php
ini_set("display_errors",1);
require_once("vendor/autoload.php");

/* Start to develop here. Best regards https://php-download.com/ */

$html='    <div class="maindiv" style="width:600px;margin:auto;font-family:calibri; background-color:#fff;font-size:11px;">

    <div class="top" style="text-align:center;color:#840A0A;"><h2 style="margin:0px 0 20px 0 ;">TAX INVOICE</h2></div>
     <div class="container" style="border: 1px solid #000;padding: 2px;">
    <div class="header" style="margin-bottom: 1px 0;border: 1px solid #000;overflow: auto;width:100%;">
        <div class="left" style="width:30%;float:left;padding: 15px 0 0 15px;">
           <img src="https://www.surun.in/images/logo.png" width="100">
        </div>
        <div style="width:60%;float:left;padding:10px 15px 10px 20px;">
            <p style="margin:0;"><b>Address :</b> 301, Gaurav Rajni Apt, Chaitraban, Samarth Nagar, Navi Sangvi, Pune. <br><b>Office Phone No :</b> 820 818-5264</p>
        </div>
    </div>
    <div class="mainsection" style="width: 100%;">
        <div style="margin: 1px 0;border: 1px solid #000;overflow: auto;">
            <div style="width:45%;padding: 10px 12px;float:left;">
                  <div><b>Sold To :<br>
                  </b> LPP Pvt Ltd</div>
                    <p style="margin:5px 0;">sdfsdf sf s sdfdfsdf</p>
                    <div style="overflow:hidden;">
                        <div><b>GSTN No :</b> gsntno</div>
                    </div>
            </div>
            <div  style="width:42%;padding: 10px 15px 10px 35px;float:right;">
                <table style="font-family:calibri;font-size:11px;">
                <tr><td style="padding:0 5px 5px 0;"><b>Invoice No</b></td> <td style="padding:0 0 5px 0;">: gret434354534</td></tr>
                <tr><td style="padding:0 5px 5px 0;"><b>Date</b></td> <td style="padding:0 0 5px 0;">: 26/04/2019</td></tr>
                <tr><td style="padding:0 5px 5px 0;"><b>GSTN No</b></td> <td style="padding:0 0 5px 0;">: gsntno</td></tr>
                </table>
            </div>
        </div>

        <div class="productdetail" style="width:100%;">
            <table cellpadding="0" cellspacing="0" style="border: 1px solid #000;width: 100%;border-collapse: collapse;font-family:calibri;font-size:11px;">
                <thead><tr><th style="padding: 5px 15px;border: 1px solid #000;text-align: left;">Item</th><th style="padding: 5px 15px;border: 1px solid #000;text-align: right;width: 125px;">Amount</th></tr></thead>
                <tbody>
                   <tr style="text-align:left;">
                    <td style="padding: 5px 15px; border: 1px solid #000;">
                    <p>ERP Plan For 1 Year</p>
                    <p>Domain - lpinfo.easierdesk.com</p>
                    <p>For Year 01/04/2019 To 31/03/2019</p>
                    </td>
                    <td style="padding: 5px 15px; text-align:right;border: 1px solid #000;">23434</td>
                    </tr>
                    <tr>
                    <td style="padding: 5px 15px;text-align:right;"><b>Sub Total</b></td>
                    <td style="padding: 5px 15px;text-align:right;border: 1px solid #000;">345345345</td>
                    </tr>
                     <tr>
                    <td style="padding: 5px 15px;text-align:right;" ><b>CGST</b></td>
                    <td style="padding: 5px 15px;text-align:right;border: 1px solid #000;">345345345</td>
                    </tr>
                     <tr>
                    <td style="padding: 5px 15px;text-align:right;"><b>SGST</b></td>
                    <td style="padding: 5px 15px;text-align:right;border: 1px solid #000;">345345345</td>
                    </tr>
                     <tr>
                    <td style="padding: 5px 15px;text-align:right;"><b>IGST</b></td>
                    <td style="padding: 5px 15px;text-align:right;border: 1px solid #000;">345345345</td>
                    </tr>
                     <tr>
                    <td style="padding: 5px 15px;text-align:right;"> <b>Total Payable</b></td>
                    <td style="padding: 5px 15px;text-align:right;border: 1px solid #000;">345345345</td>
                    </tr>
                </tbody>


            </table>
        </div>
         <div class="buyerdetail" style="margin: 1px 0;border: 1px solid #000;padding: 10px 15px;">
            <span style="color:#840A0A;"><b>Rupees :</b> </span> <b> Only.</b>
        </div>
        <div class="buyerdetail" style="margin: 1px 0;border: 1px solid #000;padding: 10px 15px;">
            <h3 style="margin-bottom: 5px;margin-top:0 ;color:#840A0A;">Our Bank Details</h3>
            <p style="margin-bottom: 5px;margin-top:5px ;"><br></p>
        </div>

        <div class="condition" style="margin: 1px 0; border: 1px solid #000;padding: 15px;">
            <h3 style="margin-bottom: 5px;margin-top:0 ;color:#840A0A;">Terms and Conditions</h3>
            <ol style="padding-left: 25px;margin-top: 5px;margin-bottom:0;line-height: 22px;">
                <li>Genuinine Complain if any will be entertained only within 10 days of receipt of goods at buyers godown only at grey stage.</li>
                <li>Over due interest@18%per annum will be charged if bill not paid as per terms of payment.</li>
                <li>All payments to be made in the name of Surun By A/c Payee Cheque / Draft /Pay Order.</li>
                <li>No third party payment will be accepted.</li>
                <li>No dudctions to be made in the invoice value while making the payment without prior permission from the mills other that agreed discount.</li>
            </ol>
        </div>
        <div class="footer" style="margin-top: 1px; border: 1px solid #000;width: 592px;">
            <div class="footerleft" style="width:40%;float:left;padding: 15px 15px;">
                <p style="margin-top: 67px;margin-bottom: 0;">Prepared By</p>
            </div>
            <div class="footerright" style="width:54%;float:left;padding: 15px 0px;">
                <h4 style="margin:0;text-align:center;">For Surun</h4>
                <p style="margin-top: 53px;text-align:center;margin-bottom: 0;">Authorised Signatory</p>
            </div>
        </div>
    </div>
    </div>
</div>';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
/*$mpdf->Output('../invoices/payment.pdf','F');*/
$mpdf->Output();     
?>
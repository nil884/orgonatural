<?
 include("../includes/configuration.php");
 include_once('../easebuzz/easebuzz-lib/easebuzz_payment_gateway.php');

 $easeSALT = EASESALT;   $easeMerchant=EASEMARCHANTID;
 $easebuzzObj = new Easebuzz($easeMerchant, $easeSALT, EASEENV);

 $payuSALT = SALT;   $payuMerchant=MARCHANTKEY;  $payuUrl=PAYURL;

  $getrefundable=selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id JOIN ".BUYER." as usr on ord.user_id=usr.u_id","*","sub.is_refund_appilicable='1' and sub.refund_status='1' AND (sub.refund_id<>''&&sub.refund_id<>'n')");
 
  if(count($getrefundable)){
       $headers  = 'MIME-Version: 1.0' . "\r\n";
         $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
     for($i=0;$i<count($getrefundable);$i++){
         $payment_mode=$getrefundable[$i]['payment_mode'];$transaction_id=$getrefundable[$i]['transaction_id'];
         $payable_amount=$getrefundable[$i]['payable_amount'];   $payment_id=$getrefundable[$i]['refund_id'];$pay_id=$getrefundable[$i]['payment_id'];
         $refundable_resp=$getrefundable[$i]['refund_response'];$refundable_amount=$getrefundable[$i]['refundable_amount'];  $phone=$getrefundable[$i]['u_mobile']; $email=$getrefundable[$i]['u_email'];
         $name=$getrefundable[$i]['u_fname'];

          if($getrefundable[$i]['cancelation_date'] != "" && $getrefundable[$i]['cancelation_date'] != "0000-00-00 00:00:00") { $req_type = "cancellation";  } else if($getrefundable[$i]['return_request_date'] != "" && $getrefundable[$i]['return_request_date'] != "0000-00-00 00:00:00" ) { $req_type = "Return"; }
         
        if($payment_id!=""){
         if($payment_mode=="Easebuzz"){
          $SALT = EASESALT; $easemrch=EASEMARCHANTID;
       
          $hash=hash(sha512,strtolower($easemrch."|".$pay_id."|".$SALT));
         
          $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dashboard.easebuzz.in/refund/v1/retrieve",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
           CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'key='.$easemrch.'&easebuzz_id='.$pay_id.'&hash='.$hash."&merchant_refund_id=".$payment_id,
            CURLOPT_HTTPHEADER => array( 'Content-Type: application/x-www-form-urlencoded'),
          ));

            $response = curl_exec($curl);

            curl_close($curl);
            $array=json_decode($response,true);
            if($result['status']=="true"){
                 $msg= $result['refund_status'];   $refund_id= $result['refund_id'];
                 $updatesubdata=array("refund_response"=>$msg);
                 updateQuery(ORDERSUB,$updatesubdata,"item_id=".$getrefundable[$i]['item_id']);
            }

         }else  if($payment_mode=="PayU Money"){
          if($refundable_resp!="success"){ 
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.payumoney.com/treasury/ext/merchant/getRefundDetails?merchantKey=".$payuMerchant."&refundId=".$payment_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array('Authorization: '.PAYUHEADER),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $array=json_decode($response,true);
            
         
            if($array['status']==0){
                 $msg= $array['result']['Refund Status'];  
                if($msg){
                  $updatesubdata=array("refund_response"=>$msg);
                  updateQuery(ORDERSUB,$updatesubdata,"item_id=".$getrefundable[$i]['item_id']);
 
                }
                
               }
           }
         }  else  if($payment_mode=="Instamojo"){
          
            include "../instamojo/Instamojo.php";
            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
           $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, INSTAURL.'refunds/'.$payment_id);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                        array("X-Api-Key:".INSTAAPIKEY,
                              "X-Auth-Token:".INSTATOKEN));
         /*    $payload = array(
                'transaction_id'=> $txnid,
                'payment_id' => $payment_id,
                'refund_amount'=>$refundable_amount,
                'type' => 'QFL',
                'body' => "Customer isn't satisfied with the quality"
            );
            curl_setopt($ch, CURLOPT_POST, true); */
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);

            $array=json_decode($response,true);

            if($array['success']=="true"){
                 $msg= $array['refund']['status']; 
                 if($msg){
                 $updatesubdata=array("refund_response"=>$msg);
                 updateQuery(ORDERSUB,$updatesubdata,"item_id=".$getrefundable[$i]['item_id']);

                }
           }
         }else if($payment_mode=="Razorpay"){
           
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.razorpay.com/v1/refunds/".$payment_id,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic ".base64_encode(RAZORAPIKEY.":".RAZORSECRETE),
                    "Content-Type: application/json"
                  ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $array=json_decode($response,true);

             if(count($array)){
                 $status= $array['status'];  
                 if($status){
                  $updatesubdata=array("refund_response"=>$status);
                  updateQuery(ORDERSUB,$updatesubdata,"item_id=".$getrefundable[$i]['item_id']);
                 }
              } 

         }
      }    
     }
 }

?>
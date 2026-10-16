<?php 
include "../includes/configuration.php";

$conf=$getconfigdetails[0]; 
if($conf['fraud_detection']=='1'&&($conf['fraud_apikey']!=''&&$conf['fraud_apisecrete']!='')){$verifyfraud=1;}else{$verifyfraud=0;}
$data=array();
$m = $_POST['m'];
if($verifyfraud==1){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.hellofraud.com/api/check/".$m,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
          "Accept:application/json",
        "Authorization: Basic ".base64_encode($conf['fraud_apikey'].":".$conf['fraud_apisecrete']),
      ),
    ));
    $response = curl_exec($curl);

    curl_close($curl);
    $array=json_decode($response,true);
   if($array['status']==1){
    $data['status']="success";
    $data['isFraud']=$array['isFraud'];
    $data['message']=$array['fraudStatus'];
    echo json_encode($data);
   }else{
    $data['status']="failed";
    $data['message']=$array['message'];
    echo json_encode($data);
   }
   
}else{
    $data['status']="failed";
    $data['message']="Hello Fraud Detection Not Activated";
    echo json_encode($data);
}
      
?>
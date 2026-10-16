<?php

class shiprocket{
    private $username; private $password; private $apiurl="https://apiv2.shiprocket.in/v1/external";
    
    public function __construct($username,$password){
      $this->username=$username;$this->password=$password;
    }

    public function execData($method,$url,$data=NULL,$action=NULL,$token=NULL){
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $this->apiurl."/".$url);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLINFO_HEADER_OUT, true);
        if($method=="POST"){curl_setopt($ch1, CURLOPT_POST, true);}
         if($data!=""&&$method=="GET"){curl_setopt( $ch1, CURLOPT_CUSTOMREQUEST, $method ); }
        if($data!=""){curl_setopt($ch1, CURLOPT_POSTFIELDS, $data);}
        if($action=="login"){
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json') );
        }else{
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$token));
        }

        $output = curl_exec($ch1);
         curl_close($ch1);
        $array=json_decode($output,true);
        return $array;
    }

    public function authenticate(){
       $method="POST"; $url="auth/login"; $action="login";
       $data=array("email"=> $this->username,"password"=> $this->password);
       $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action);
       return $res['token'];
    }

    public function addPickups($token,$data){
       $method="POST"; $url="settings/company/addpickup"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
        return $res;
    }

    public function getPickups($token){
       $method="GET"; $url="settings/company/pickup"; $action=""; $data="";
       $res=$this->execData($method,$url,$data,$action,$token);
       return $res['data']['shipping_address'];
    }

    public function getServiceability($token,$data){
       $method="GET"; $url="courier/serviceability"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       $status=$res['status_code'];
       return $res;
    }

    public function createOrder($token,$data){
       $method="POST"; $url="orders/create/adhoc"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function createAWBNo($token,$data){
       $method="POST"; $url="courier/assign/awb"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function getPincodeData($token,$data){
       $method="GET"; $url="open/postcode/details"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function requestForPickup($token,$data){
       $method="POST"; $url="courier/generate/pickup"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function generateManifest($token,$data){
       $method="POST"; $url="manifests/generate"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function printManifest($token,$data){
       $method="POST"; $url="manifests/print"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function generateLabel($token,$data){
       $method="POST"; $url="courier/generate/label"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function generateInvoice($token,$data){
       $method="POST"; $url="orders/print/invoice"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function trackOrder($token,$shipmentid){
       $method="GET"; $url="courier/track/shipment/".$shipmentid; $action=""; $data="";
       $res=$this->execData($method,$url,$data,$action,$token);
       return $res;
    }

    public function cancelOrder($token,$data){
       $method="POST"; $url="orders/cancel"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }

    public function showOrderDetails($token,$orderId){
          $method="GET"; $url="orders/show/".$orderId; $action=""; $data="";
       $res=$this->execData($method,$url,$data,$action,$token);
       return $res;

    }

     public function returnOrder($token,$data){
       $method="POST"; $url="orders/create/return"; $action=""; $json=json_encode($data);
       $res=$this->execData($method,$url,$json,$action,$token);
       return $res;
    }
}
?>
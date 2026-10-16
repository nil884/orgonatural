<?php
class surun{
   
    private $apiurl="https://www.surun.in/api";

    public function __construct(){}

    public function execData($method,$url,$data=NULL){

        $ch1 = curl_init();
        //curl_setopt($ch1, CURLOPT_URL, $this->apiurl."/".$url);
        curl_setopt($ch1, CURLOPT_URL, $url);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json') );

        $output = curl_exec($ch1);
       $array=json_decode($output,true);

         curl_close($ch1);
         return $array;

    }

    public function getrecords($data){
       $method="POST"; $url=$this->apiurl."/getrecords.php";  $json=json_encode($data);
       return $this->execData($method,$url,$json);
    }

     public function addrecords($data){
       $method="POST"; $url=$this->apiurl."/addrecords.php";  $json=json_encode($data);
       return $this->execData($method,$url,$json);
    }

     public function deleterecords($data){
       $method="POST"; $url=$this->apiurl."/deleterecord.php";  $json=json_encode($data);
        return $this->execData($method,$url,$json);
    }

     public function getPincodeData($data){
       $method="POST"; $url=$this->apiurl."/getpincode.php";  $json=json_encode($data);
       
       return $this->execData($method,$url,$json);
    }

    public function getNewFeature($url){ 
      $data=array("reqFrom"=>$url);
      $method="POST"; $url=$this->apiurl."/checkNewFeature.php";  $json=json_encode($data);
      
      $res=$this->execData($method,$url,$json);

      return $res['status'];
   }

   public function getFeatureLog($url,$categories=null,$months=null){
      $data=array("reqFrom"=>$url,"categories"=>$categories,"months"=>$months);
    $method="POST"; $url=$this->apiurl."/getFeatureLog.php";  $json=json_encode($data);
    
    return $this->execData($method,$url,$json);
 }

 public function getNewChange($url){ 
   $data=array("reqFrom"=>$url);
   $method="POST"; $url=$this->apiurl."/checkNewChange.php";  $json=json_encode($data);
   
   $res=$this->execData($method,$url,$json);

   return $res['status'];
}

public function getChangeLog($url,$categories=null,$months=null,$types=null){
   $data=array("reqFrom"=>$url,"categories"=>$categories,"months"=>$months,"changeType"=>$types);
 $method="POST"; $url=$this->apiurl."/getChangeLog.php";  $json=json_encode($data);
 
 return $this->execData($method,$url,$json);
}

public function getChangeLogCount($url,$reqType,$searchFor){
   $data=array("reqFrom"=>$url,"reqType"=>$reqType,"searchFor"=>$searchFor);
 $method="POST"; $url=$this->apiurl."/getChangeLogCount.php";  $json=json_encode($data);
 
 $res =$this->execData($method,$url,$json);  
 return $res['counts']; 
}

public function getFeatureCount($url,$reqType,$searchFor){
  
   $data=array("reqFrom"=>$url,"reqType"=>$reqType,"searchFor"=>$searchFor);
 $method="POST"; $url=$this->apiurl."/getFeatureCount.php";  $json=json_encode($data);

 $res =$this->execData($method,$url,$json);  
 return $res['counts']; 
 //return $this->execData($method,$url,$json);  
}

public function getFilterNewFeatureCount($url,$reqType,$searchFor){
  $data=array("reqFrom"=>$url,"reqType"=>$reqType,"searchFor"=>$searchFor);
 $method="POST"; $url=$this->apiurl."/isNewFeatureForFilter.php";  $json=json_encode($data);

 $res =$this->execData($method,$url,$json);  
 return $res['counts']; 
}

public function getFilterNewChangeCount($url,$reqType,$searchFor){
   $data=array("reqFrom"=>$url,"reqType"=>$reqType,"searchFor"=>$searchFor);
   $method="POST"; $url=$this->apiurl."/isNewChangeForFilter.php";  $json=json_encode($data);
 
   $res =$this->execData($method,$url,$json);  
   return $res['counts']; 
}

}
?>
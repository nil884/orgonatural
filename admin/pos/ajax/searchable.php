<?php
include "../../../includes/configuration.php";
$vendor=($getconfigdetails[0]['default_vendor_for_pos']==0?1:$getconfigdetails[0]['default_vendor_for_pos']);
 $action=$_REQUEST['action'];
 $fld1=base64_decode($_POST['fld1']); //table
 $fld2=base64_decode($_POST['fld2']); //fields
 $fld3=base64_decode($_POST['fld3']); //where condition
 $allflds=explode(",",$fld2);
  $fld5=$_POST['fld5'];
 if($action=="getallRecords"){
   $firstgp=selectQuery($fld1,$fld2,$fld3);
     $allarr=array();

  for($i=0;$i<count($firstgp);$i++){
   $data=array();
   for($j=0;$j<count($allflds);$j++){
       $fl=$allflds[$j];
      /*  $datatype=getdatatype($fld1,$allflds[$j]);
       $coltype=$datatype; */
        if($j==0){array_push($data,base64_encode($firstgp[$i][$fl]));
        }else{array_push($data, $firstgp[$i][$fl]);  }
          // }else{array_push($data,($coltype=="datetime"?date("Y-m-d",strtotime($firstgp[$i][$fl])): $firstgp[$i][$fl]));  }
   }
 array_push($allarr,$data);
  }
   echo json_encode($allarr);
 }

if($action=="getrecordforfind"){
  $allarr=array();
   $findby=$_POST['fldvalues'];
 $dataflds= json_decode($findby,true);
 $wherearr=array();
    if($fld3!=""){ array_push($wherearr,$fld3);}
  if(count($dataflds)){
   foreach ($dataflds as $key => $value) {
      if($value!=""){
          $where=$key." like '%".$value."%'";
          //$where="MATCH(".$key.") AGAINST('".$value."' IN NATURAL LANGUAGE MODE)";
         array_push($wherearr,$where);
       }
   }
  }

    $firstgp=selectQuery($fld1,$fld2,implode(" AND ",$wherearr));
   for($i=0;$i<count($firstgp);$i++){
     $data=array();
       for($j=0;$j<count($allflds);$j++){
           $fl=$allflds[$j];
         /*   $datatype=getdatatype($fld1,$allflds[$j]);
           print__r($datatype); */
          // $coltype=$datatype[0]['DATA_TYPE'];
            if($j==0){array_push($data,base64_encode($firstgp[$i][$fl]));
            }else{array_push($data, $firstgp[$i][$fl]);  }
       }
      array_push($allarr,$data);
   }
    echo json_encode($allarr);
 }

if($action=="getrecordjsononfind"){
 $groupid= base64_decode($_POST['groupid']);
      $firstgp=selectQuery($fld1,$fld5,$allflds[0]."= ".$groupid);
      $data=array();  array_push($data,$firstgp[0][$fld5]);
    echo json_encode($data);
}


?>
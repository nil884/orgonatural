<?php include("../../includes/configuration.php");
if($action == "sms_status"){
    $sms = selectQuery(SMS,"*","status<>'DELIVRD'");
    for($i=0;$i<count($sms);$i++){ 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api-alerts.solutionsinfini.com/v3/?method=sms.status&api_key=".WORKINGKEY."&format=PHP&id=".$sms[$i]['msg_id']."&numberinfo=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $arr=(unserialize($output));
        $msg=$arr['message'];
        $status=$arr['data'][0]['status'];
        if($msg=="Processed Successfully"&&$status!=""){
            $data = array('status'=>$status);
            $up = updateQuery(SMS,$data,"sms_id=".$sms[$i]['sms_id']);
        }
    }
} ?>
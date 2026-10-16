<?PHP include("../../includes/configuration.php");
$date1 = date("Y-m-d", strtotime($_REQUEST["pd"]) )." 00:00:00";
$date2 = date("Y-m-d", strtotime($_REQUEST["pd1"]) )." 23:59:59";
$data = array();
$c = $_REQUEST['smstype'];
if($c=="All"){ $getsms=selectQuery(SMS,"date,msg_type,user_name,mobile_no,message,status","1 and (date between '".$date1."' and '".$date2."')  order by sms_id DESC");}
else { $getsms=selectQuery(SMS,"date,msg_type,user_name,mobile_no,message,status"," msg_type='".$c."' and (date between '".$date1."' and '".$date2."') order by sms_id DESC");}
for($i=0;$i<count($getsms);$i++){ 
    $data1=array( "Date Time"=>$getsms[$i]['date'], "Message Type"=> $getsms[$i]['msg_type'], "User"=> $getsms[$i]['user_name'], "Mobile No"=>$getsms[$i]['mobile_no'], "SMS"=>$getsms[$i]['message'], "Status"=>$getsms[$i]['status'],);
    array_push($data,$data1);
} 
function cleanData(&$str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}
// filename for download
$filename = "smsreport_" . date('Ymd') . ".xls";
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");
$flag = false;
foreach($data as $row){
    if(!$flag){
        // display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
} ?>
<?PHP include("../../includes/configuration.php");
    $date1 = date("Y-m-d", strtotime($_REQUEST["from_date"]) )." 00:00:00";
    $date2 = date("Y-m-d", strtotime($_REQUEST["to_date"]) )." 23:59:59";
    $data = array();
    $get_visitor_list = selectQuery(VISITORLIST,"ip,device,browser,details,date","ip <>'' and (date between '".$date1."' and '".$date2."') order by id DESC");
    for($i=0;$i<count($get_visitor_list);$i++){ 
        $data1 = array("ip" => $get_visitor_list[$i]['ip'], "Device" => $get_visitor_list[$i]['device'], "Browser"=> $get_visitor_list[$i]['browser'], "Details" => $get_visitor_list[$i]['details'], "date" => date("d-m-Y h:i:s", strtotime($get_visitor_list[$i]['date'])),);
        array_push($data,$data1);
    } 
    function cleanData(&$str){
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }
    // filename for download
    $filename = "visitor_report_" . date('Ymd') . ".xls";
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel");
    $flag = false;
    foreach($data as $row){
    if(!$flag) {
        //display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
        echo implode("\t", array_values($row)) . "\r\n";
    }
?>
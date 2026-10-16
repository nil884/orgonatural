<?php include("../../includes/configuration.php");
$action = $_REQUEST['action'];
if($action == "add_attr"){
    $attr_name = $_REQUEST['attr_name'];
    $cattype = $_REQUEST['cattype'];
    if($cattype == "Category"){ $parentid_id =0; } else { $parentid_id = $_REQUEST['parentid_id']; }
    $chkdup = selectQuery(PRODATTR,"count('attr_id') as total_count","attr_name='".ucwords($attr_name) ."'  and type='".$cattype."'  and parent_id='".$parentid_id."'  ");
    if($chkdup[0]['total_count'] > 0){ echo "Exist";}
    else{
        $data= array('attr_name' => trim(ucwords(addslashes($attr_name))), "type" => $cattype, "parent_id" =>  $parentid_id,);
        $indata = insertQuery(PRODATTR,$data);
        if($indata){     
            if($cattype == "Attribute"){
                $data= array('attr_for_template' => "attr_".$indata,);
                $update = updateQuery(PRODATTR,$data,"attr_id='".$indata."'");
            }
            echo $indata;
        } else{ echo "Not"; }
    }  
}

if($action == "Delete_attr"){
    $get_atrr = selectQuery(PRODATTR,"attr_id,attr_name,attr_for_template,parent_id","parent_id  <> '0' and  isActive='1'  order by attr_name ");
    $dont_delete_array = array();
    for($j=0;$j<count($get_atrr);$j++){
        $gettables = selectQuery(PRODCAT,"template","template <> '' ");
        for($k=0;$k<count($gettables);$k++){
        $tablename = $gettables[$k]['template'];
        $results = showQuery($tablename,"field='".$get_atrr[$j]['attr_for_template']."'");
        if(count($results)){
        if( !in_array($get_atrr[$j]['attr_id'], $dont_delete_array)){ array_push($dont_delete_array,$get_atrr[$j]['attr_id']); } 
        if( !in_array($get_atrr[$j]['parent_id'], $dont_delete_array)){ array_push($dont_delete_array,$get_atrr[$j]['parent_id']); } 
    } } }  
                   
    if( !in_array($attr_id, $dont_delete_array)){ 
        function del_attribute($attr_id){
            $gettemplate_attribute = selectQuery(PRODATTR,"attr_for_template",'attr_id="'.$attr_id.'"');
            $que = deleteQuery(PRODATTR,'attr_id="'.$attr_id.'"');
            if($que){
                $gettables = selectQuery(PRODCAT,"template","template <> '' ");
                for($i=0;$i<count($gettables);$i++){
                    $tablename = $gettables[$i]['template'];
                    $results = showQuery($tablename,"field='".$gettemplate_attribute[0]['attr_for_template']."'");
                    if(count($results)==1){
                        $dropquery = alterQuery($tablename,"DROP COLUMN ".$gettemplate_attribute[0]['attr_for_template']);
                        include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
                        include_once('../../PHPExcel/excelfunctions.php');
                        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                        $excel = createExcel($url,$tablename);
                        $data12 = array("excelFile"=>$excel);
                        $insertsbcat = updateQuery(PRODCAT,$data12,"template='".$tablename."'");
                    }
                }
            }
        }
        if($type == "Attribute"){ del_attribute($attr_id); echo 1; } else{
            $get_all_attri = selectQuery(PRODATTR,"attr_id",'parent_id ="'.$attr_id.'"');
            for($i=0;$i<count($get_all_attri);$i++){ del_attribute($get_all_attri[$i]['attr_id']); }
            $que = deleteQuery(PRODATTR,'attr_id="'.$attr_id.'"');
            echo 1;
        }
    } else{ echo "3"; }
}

if($action == "edit_attr"){
    $edit_attr_name = $_REQUEST['edit_attr_name'];
    $attr_id = $_REQUEST['attr_id'];
    $cattype = $_REQUEST['cattype'];
    $gettemplate_attribute = selectQuery(PRODATTR,"attr_for_template",'attr_id="'.$attr_id.'"');
    $chkdup = selectQuery(PRODATTR,"count('attr_id') as total_count","attr_name='".ucwords($attr_name) ."'  and type='".$cattype."'  and attr_id <> '.$attr_id.' ");     
    if($chkdup[0]['total_count'] >0){ echo "Exist"; }
    else{
        $data = array('attr_name' => ucwords(addslashes($edit_attr_name)));
        $indata = updateQuery(PRODATTR,$data,"attr_id='".$attr_id."'");
        $gettables = selectQuery(PRODCAT,"template","template <> '' ");
        for($i=0;$i<count($gettables);$i++){
            $tablename = $gettables[$i]['template'];
            $results = showQuery($tablename,"field='".$gettemplate_attribute[0]['attr_for_template']."'");
            if(count($results)==1){
                include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
                include_once('../../PHPExcel/excelfunctions.php');
                $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $excel = createExcel($url,$tablename);
                $data12 = array("excelFile"=>$excel);
                $insertsbcat = updateQuery(PRODCAT,$data12,"template='".$tablename."'");
            }
        }
        if($indata){ echo 1; }else{ echo "Not"; }
    }  
} ?>
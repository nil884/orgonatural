<?php include("../includes/configuration.php");
    $requestData = $_REQUEST;
    $columns = array(
        0 =>'#',
        1 => 'Case ID',
        2=> 'Client Details',
        3=> 'Status',
        4=> 'Assigned Staff',
        5=> 'Department',
        6=> 'Created On'
    );
    $where=$requestData['wherecond'];
    $column=$requestData['order'];
    $getguest1=selectQuery(CONTACT,"*",$where);
    $totalData = count($getguest1);
    $totalFiltered = $totalData;
    $getopen=selectQuery(CONTACT,"*",$where." order by contact_request_id DESC LIMIT ".$requestData['start']." ,".$requestData['length']."");
    
    /*$getopen=selectQuery(CONTACT,"*",$where." LIMIT ".$requestData['start']." ,".$requestData['length']."");*/
    $data = array();
    for($i=0;$i<count($getopen);$i++){
        $nestedData=array();
        if($getopen[$i]['request_type']=="External"){
            $fontawesome ="<i class='fa fa-copyright' aria-hidden='true' style='color:#CC0066;'></i>";
        } else{
           $fontawesome='<i class="fa fa-info-circle" aria-hidden="true" style="color:#30CC36;"></i>';
        }
        $caseid=$fontawesome.' <a href="requestdetails.php?req='.base64_encode($getopen[$i]['contact_request_id']).'">'.$getopen[$i]['contact_request_id'].'</a><br> Source : '.$getopen[$i]['tkt_source'];
        $clientdata=$getopen[$i]['Name']."<br><b>Email : </b>".$getopen[$i]['Email']."<br><b>Mobile : </b>".$getopen[$i]['Telephone'];
        if($getopen[$i]['isOpen']=="1"){
            $status="<span class='badge badge-primary'>Open</div>";
        } else if($getopen[$i]['isAnswered']=="1"){
            $status="<span class='badge badge-info'>Answered</div>";
        } else if($getopen[$i]['isClosed']=="1"){
            $status="<span class='badge badge-danger'>Closed</div>";
        } else if($getopen[$i]['isConfirmed']=="1"){
            $status="<span class='badge badge-info'>Confirm</div>";
        } else if($getopen[$i]['isTerminated']=="1") {
            $status="<span class='badge badge-warning'>Terminated</div>";
        } else if($getopen[$i]['isReopen']=="1"){
            $status="<span class='badge badge-secondary'>Reopen</div>";
        }
        /* Assigned staff name */
        if($getopen[$i]['assign_to']!=""){
            $getassigned = selectQuery(SUPPORTSTAFF,"*","emp_id='".$getopen[$i]['assign_to']."' ");      // Dash Issue, Client not displaying
            $asign=$getassigned[0]['emp_name'];
        } else {
           $asign="[NOT ASSIGNED]";
        }
        /* Assigned Department name */
        if($getopen[$i]['dept']!=""){
            $getassigned = selectQuery(SUPPORTDEPT,"*","dept_id='".$getopen[$i]['dept']."' ");      // Dash Issue, Client not displaying
            $deprt=$getassigned[0]['dept_name'];
        } else {
            $deprt="[NOT ASSIGNED]";
        }
        $trunc_time = date("d M Y",strtotime($getopen[$i]['date']))."<br>".date("h:m a",strtotime($getopen[$i]['date']));
        $nestedData[] = $requestData['start']+$i+1;
        $nestedData[] = $caseid;
        $nestedData[] = $clientdata;
        $nestedData[] = $status;
        $nestedData[] = $asign;
        $nestedData[] = $deprt;
        $nestedData[] = $trunc_time;
        $data[] = $nestedData;
    }
    $json_data = array(
    "draw" => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal" => intval( $totalData ),  // total number of records
    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data" => $data // total data array
    );
    echo json_encode($json_data);  // send data as json format
?>
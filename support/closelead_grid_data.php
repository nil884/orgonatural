<?php include("../includes/configuration.php");
    $requestData= $_REQUEST;
    $columns = array(
        0 =>'#',
        1 => 'Ticket #',
        2=> 'Client Details',
        3=> 'Status',
        4=> 'Assigned To',
        5=> 'Closed On'
    );
    $where = $requestData['wherecond'];
    $getguest1 = selectQuery(CONTACT,"*",$where);
    $totalData = count($getguest1);
    $totalFiltered = $totalData;
    $getopen=selectQuery(CONTACT,"*",$where." ORDER BY closedDate DESC LIMIT ".$requestData['start'].",".$requestData['length']." ");
    $data = array();
    for($i=0;$i<count($getopen);$i++){
        $getassigned = selectQuery(SUPPORTSTAFF,"*","emp_id='".$getopen[$i]['assign_to']."' ");
        for($k=0;$k<count($getassigned);$k++){
        	$nestedData=array();
            $caseid='<a href="requestdetails.php?req='.base64_encode($getopen[$i]['contact_request_id']).'">'.$getopen[$i]['contact_request_id'].'</a><br> Source : '.$getopen[$i]['tkt_source']; 
            $clientdata=$getopen[$i]['Name']."<br><b>Email : </b>".$getopen[$i]['Email']."<br><b>Mobile : </b>".$getopen[$i]['Telephone'];
            if($getopen[$i]['isOpen']=="1"){
                $status = "Open";
            } else if($getopen[$i]['isAnswered']=="1"){
                $status = "Answered";
            } else if($getopen[$i]['isClosed']=="1"){
                $status = "Closed";
            } else if($getopen[$i]['isConfirmed']=="1"){
                $status = "Confirm";
            } else if($getopen[$i]['isTerminated']=="1"){
                $status = "Terminated";
            } else if($getopen[$i]['isReopen']=="1"){
                $status = "Reopen";
            }
            $assigned_to = $getassigned[$k]['emp_name'];
            $trunc_time = date("d M Y",strtotime($getopen[$i]['date']))."<br>".date("h:m a",strtotime($getopen[$i]['closedDate']));
            $getacctype = selectQuery(SUPPORTSTAFF,"*","emp_id='".$getopen[$i]['entry_by']."' ");
            if($getacctype[0]['acc_type']=="Client"){
               $fontawesome ="<i class='fa fa-copyright' aria-hidden='true' style='color:#CC0066;'></i>";
            }
            else{
               $fontawesome="&nbsp;&nbsp;&nbsp;";
            }
        	$nestedData[] = $requestData['start']+$i+1;
           	$nestedData[] = $caseid;
            $nestedData[] = $clientdata;
            $nestedData[] = $status;
            $nestedData[] = $assigned_to;
            $nestedData[] = $trunc_time;
        	$data[] = $nestedData;
        }
    }
    $json_data = array(
    "draw" => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal" => intval( $totalData ),  // total number of records
    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data" => $data   // total data array
    );
    echo json_encode($json_data);  // send data as json format
?>

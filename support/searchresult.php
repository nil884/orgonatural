<?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Search Result</title>
    <?php include 'commoncss.php';?>
</head>
<body>
<div class="page-body-wrapper">
<?php include('menu.php');
$id1 = $_REQUEST['id1'];
$id = base64_decode($id1);
if($id=='b') {
    $searchstring1 = $_REQUEST['searchfld'];
    $searchstring = base64_decode($searchstring1);
    $q2 = selectQuery(SUPPORTSTAFF,"*","emp_name LIKE '%".$searchstring."%'  ");
    $q3 = selectQuery(SUPPORTDEPT,"*","dept_name LIKE '%".$searchstring."%' ");
    if($searchstring=="tester"){
        $q1 = selectQuery(CONTACT,"*","assign_to='3' and isDel='0' AND (isOpen='1' || isAnswered='1' || isOverdue='1') ");
    } else {
        $q1 = selectQuery(CONTACT, "*","(internal_lead_id LIKE '%".$searchstring."%' OR project_name LIKE '%".$searchstring."%' OR assign_to='".$q2[0]['emp_id']."' OR dept='".$q3[0]['dept_id']."') AND isClosed<>'1' and isDel='0'");
    }
} else {
    $searchstring = $_REQUEST['searchfld'];
    $dept = $_REQUEST['dept'];
    $sts = $_REQUEST['sts'];
    $query = "";
    if($searchstring!=""){
        $q2 = selectQuery(SUPPORTSTAFF,"*","emp_name LIKE '%".$searchstring."%' ");
        $q3 = selectQuery(SUPPORTDEPT,"*","dept_name LIKE '%".$searchstring."%' ");
        /*$q1 = selectQuery(CONTACT, "*","(internal_lead_id LIKE '%".$searchstring."%' OR project_name LIKE '%".$searchstring."%' OR project_name LIKE '%".$searchstring."%' OR assign_to='".$q2[0]['emp_id']."' OR dept='".$q3[0]['dept_id']."') ");*/
        $query=" AND (contact_request_id LIKE '%".$searchstring."%' OR Name LIKE '%".$searchstring."%' OR Email LIKE '%".$searchstring."%' OR Telephone LIKE '%".$searchstring."%' OR assign_to='".$q2[0]['emp_id']."' OR dept='".$q3[0]['dept_id']."')";
    } if($dept!=""){
        $q3 = selectQuery(SUPPORTDEPT,"*","dept_name LIKE '%".$dept."%' ");
        /*$q1 = selectQuery(CONTACT, "*","dept='".$q3[0]['dept_id']."' ");*/
        if($query==""){
           $query.= " AND dept='".$q3[0]['dept_id']."' ";
        }
        else{
            $query.= " AND (dept='".$q3[0]['dept_id']."') ";
        }
    }
    if($sts!=""){
        if($sts=="Open"){
            /*$q1 = selectQuery(CONTACT,"*","isOpen='1' ");*/
            if($query==""){
                $query .=" AND isOpen='1' ";
            }
            else{
                $query .=" AND (isOpen='1') ";
            }
        }
        else if($sts=="Answered"){
            /*$q1 = selectQuery(CONTACT,"*","isClosed='1' ");*/
            if($query==""){
                $query.=" AND isAnswered='1'";
            } else {
                $query.=" AND (isAnswered='1')";
            }
        } else if($sts=="Overdue"){
            if($query==""){
                $query.=" AND isOverdue='1'";
            }
            else{
                $query.=" AND (isOverdue='1')";
            }
        } else if($sts=="Closed") {
            /*$q1 = selectQuery(CONTACT,"*","isClosed='1' ");*/
            if($query==""){
                $query.=" AND isClosed='1' ";
            }else{
                $query.=" AND (isClosed='1')";
            }
        } else{ }
    }
    $q1 = selectQuery(CONTACT,"*","isDel='0'".$query);
} ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <?php include('search.php'); ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div>
                        <h5 class="card-head-title"><?php $count = count($q1); ?>
                        Showing <?php echo $count; ?> Result for : <?php echo $searchstring." ".$dept." ".$sts;?></h5>
                    </div>
                    <div class="btn-actions-pane-right"><button type="button" class="btn btn-outline-light text-dark btn-sm" onclick="goBack()">Back</button></div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="example display table table-striped" id="search">
                                    <?php if(count($q1)){?>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ticket #</th>
                                            <th>Client Details</th>
                                            <th>Assigned Staff</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Created On</th>
                                        </tr>
                                    </thead>
                                    <?php } ?>
                                    <tbody>
                                        <?php $cnt = 1;
                                        for($i=0;$i<count($q1);$i++){
                                        /* Assigned staff name */
                                        if($q1[$i]['assign_to']!=""){
                                            $getassigned = selectQuery(SUPPORTSTAFF,"emp_name","emp_id='".$q1[$i]['assign_to']."' ");      // Dash Issue, Client not displaying
                                            $asign=$getassigned[0]['emp_name'];
                                        } else {
                                            $asign="[NOT ASSIGNED]";
                                        }
                                        /* Assigned Department name */
                                        if($q1[$i]['dept']!="") {
                                            $getassigned = selectQuery(SUPPORTDEPT,"*","dept_id='".$q1[$i]['dept']."' ");    // Dash Issue, Client not displaying
                                            $deprt=$getassigned[0]['dept_name'];
                                        } else {
                                            $deprt="[NOT ASSIGNED]";
                                        }
                                        /* client Details */
                                        $clientdata=$q1[$i]['Name']."<br><b>Email : </b>".$q1[$i]['Email']."<br><b>Mobile : </b>".$q1[$i]['Telephone'];
                                        /* ticket Status*/
                                        if($q1[$i]['isOpen']=="1") {
                                            $status="Open";
                                        } else if($q1[$i]['isAnswered']=="1") {
                                            $status="Answered";
                                        } else if($q1[$i]['isClosed']=="1"){
                                            $status="Closed";
                                        } else if($q1[$i]['isConfirmed']=="1") {
                                            $status="Confirm";
                                        } else if($q1[$i]['isTerminated']=="1") {
                                            $status="Terminated";
                                        } else if($q1[$i]['isReopen']=="1") {
                                            $status="Reopen";
                                        }
                                        $trunc_time = date("d M Y",strtotime($q1[$i]['date']))."<br>".date("h:m a",strtotime($q1[$i]['date']));
                                        if($q1[$i]['request_type']=="External") {
                                            $fontawesome ="<i class='fa fa-copyright' aria-hidden='true'></i>";
                                        } else {
                                            $fontawesome='<i class="fa fa-info-circle" aria-hidden="true"></i>';
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt++;?></td>
                                            <td><?php echo $fontawesome; ?> <a href="requestdetails.php?req=<?php echo base64_encode($q1[$i]['contact_request_id']); ?>"><?php echo $q1[$i]['contact_request_id']; ?></a></td>
                                            <td><?php echo $clientdata;?></td>
                                            <td><?php echo $asign;?></td>
                                            <td><?php echo $deprt;?></td>
                                            <td><?php echo $status;?></td>
                                            <td><?php echo $trunc_time; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php if(count($q1)<=0){?> <p class="text-muted mb-0">Records Not Found</p><?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php';?>
    </div>
</div>
</body>
</html>
<!doctype html>
<?php
     include("../../includes/configuration.php");

     $deptid=base64_decode($_REQUEST['dept']);

     if($deptid==1)
     {
         $page=1;
     }
     else if($deptid==2)
     {
        $page=2;
     }
     else if($deptid==3)
     {
        $page=3;
     }
     else
     {
        $page=4;
     }


    /* $getrequest=selectQuery(CONTACT." as c LEFT JOIN ".SUPPORTDEPT." as d on c.dept=d.dept_id","*","c.dept=".$deptid);   */
      $getopen=selectQuery(CONTACT,"*","dept=".$deptid." and isOpen='1' and isClosed='0'");

      $getans=selectQuery(CONTACT,"*","dept=".$deptid." and isAnswered='1'");
     $getclosed=selectQuery(CONTACT,"*","dept=".$deptid." and isClosed='1'");
      $getoverdue=selectQuery(CONTACT,"*","dept=".$deptid." and isOverdue='1'");
     /* echo  $getoverdue; */
     $getdeptname=selectQuery(SUPPORTDEPT,"*","dept_id=".$deptid);

?>

<html>
<head>
    <title><?php echo SITE_TITLE; ?> : Support Requests - <?php echo $getdeptname[0]['dept_name']; ?></title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="../../css/bootstrap-table.css" />
    <link rel="stylesheet" href="../../css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="../../css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="../../css/bootstrap-toggle.min.css" />
</head>

<body>
<div class="dashcontainer">
    <?php include('../header.php') ?>

        <?php include('../sidebar.php') ?>

<div class="right_col">
        <div class="dashbody">
        <div class="card">
        <div class="panel-body">
            <div class="panel-heading"><div class="panel-title">Support Requests - <?php echo $getdeptname[0]['dept_name']; ?></div></div>
            <div class="msgs"></div>
                   <div id="tabs">
                         <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#open">Open (<?php echo count($getopen); ?>)</a></li>
                            <li><a data-toggle="tab" href="#answered">Answered (<?php echo count($getans); ?>)</a></li>
                            <li><a data-toggle="tab" href="#overdue">Overdue (<?php echo count($getoverdue); ?>)</a></li>
                             <li><a data-toggle="tab" href="#closed">Closed (<?php echo count($getclosed); ?>)</a></li>
                          </ul>

                        <div class="tab-content">
                            <div id="open" class="tab-pane active">
                                 <h4 class="marginbot25 text-success"> Open Tickets (<?php echo count($getopen); ?>)</h4>
                                    <table class="example display table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ticket ID</th>
                                        <th>Date</th>
                                        <th>Department</th>
                                        <th>Client</th>
                                         <th>Status</th>
                                         </tr>
                                    </thead>
                                    <tbody class="text-muted">
                                    <?php
                                      for($i=0;$i<count($getopen);$i++)
                                      {
                                          ?>
                                          <tr>
                                          <td><?php echo $i+1; ?></td>
                                           <td><a href="requestdetails.php?req=<?php echo base64_encode($getopen[$i]['contact_request_id']); ?>"><?php echo $getopen[$i]['contact_request_id']; ?></a></td>
                                           <td><?php echo $getopen[$i]['date']; ?></td>
                                           <td><?php echo $getdeptname[0]['dept_name']; ?></td>
                                            <td><?php echo $getopen[$i]['Name']; ?></td>
                                            <td><?php echo $getopen[$i]['status']; ?></td>
                                          </tr>

                                          <?
                                      }
                                    ?>
                                    </tbody>
                           </table>
                            </div>
                             <div id="answered" class="tab-pane">
                              <h4 class="marginbot25 text-success"> Answered Tickets (<?php echo count($getans); ?>)</h4>
                                <div class="table-responsive">
                                    <table class="example display table table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ticket ID</th>
                                            <th>Date</th>
                                            <th>Department</th>
                                            <th>Client</th>
                                             <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-muted">
                                        <?php
                                          for($i=0;$i<count($getans);$i++)
                                          {
                                              ?>
                                              <tr>
                                                  <td><?php echo $i+1; ?></td>
                                               <td><a href="requestdetails.php?req=<?php echo base64_encode($getans[$i]['contact_request_id']); ?>"><?php echo $getans[$i]['contact_request_id']; ?></a></td>
                                               <td><?php echo $getans[$i]['date']; ?></td>
                                               <td><?php echo $getdeptname[0]['dept_name']; ?></td>
                                                <td><?php echo $getans[$i]['Name']; ?></td>
                                                <td><?php echo $getans[$i]['status']; ?></td>
                                              </tr>

                                              <?
                                          }
                                        ?>
                                        </tbody>
                               </table>
                               </div>
                             </div>
                              <div id="overdue" class="tab-pane">
                              <h4 class="marginbot25 text-success"> Overdue Tickets (<?php echo count($getoverdue); ?>)</h4>
                                <div class="table-responsive">
                                     <table class="example display table table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ticket ID</th>
                                            <th>Date</th>
                                            <th>Department</th>
                                            <th>Client</th>
                                             <th>Status</th>
                                          </tr>
                                        </thead>
                                        <tbody class="text-muted">
                                        <?php
                                          for($i=0;$i<count($getoverdue);$i++)
                                          {
                                              ?>
                                              <tr>
                                                <td><?php echo $i+1; ?></td>
                                               <td><a href="requestdetails.php?req=<?php echo base64_encode($getoverdue[$i]['contact_request_id']); ?>"><?php echo $getoverdue[$i]['contact_request_id']; ?></a></td>
                                               <td><?php echo $getoverdue[$i]['date']; ?></td>
                                               <td><?php echo $getdeptname[0]['dept_name']; ?></td>
                                                <td><?php echo $getoverdue[$i]['Name']; ?></td>
                                                <td><?php echo $getoverdue[$i]['status']; ?></td>
                                              </tr>

                                              <?
                                          }
                                        ?>
                                        </tbody>
                               </table>
                               </div>
                             </div>
                              <div id="closed" class="tab-pane">
                              <h4 class="marginbot25 text-success"> Closed Tickets (<?php echo count($getclosed); ?>)</h4>
                                <div class="table-responsive">
                                      <table class="example display table table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ticket ID</th>
                                            <th>Date</th>
                                            <th>Department</th>
                                            <th>Client</th>
                                             <th>Status</th>
                                         </tr>
                                        </thead>
                                        <tbody class="text-muted">
                                        <?php
                                          for($i=0;$i<count($getclosed);$i++)
                                          {
                                              ?>
                                              <tr>
                                                <td><?php echo $i+1; ?></td>
                                               <td><a href="requestdetails.php?req=<?php echo base64_encode($getclosed[$i]['contact_request_id']); ?>"><?php echo $getclosed[$i]['contact_request_id']; ?></a></td>
                                               <td><?php echo $getclosed[$i]['date']; ?></td>
                                               <td><?php echo $getdeptname[0]['dept_name']; ?></td>
                                                <td><?php echo $getclosed[$i]['Name']; ?></td>
                                                <td><?php echo $getclosed[$i]['status']; ?></td>
                                              </tr>

                                              <?
                                          }
                                        ?>
                                        </tbody>
                               </table>
                               </div>
                             </div>
                        </div>
                   </div>
           </div>
  </div>

</div>
<?php include('../footer.php');?>
</div>
</div>

<script type="text/javascript" src="../../js/bootstrap-table.js"></script>
<script type="text/javascript" src="../../js/restrict.js"></script>
<script src="../../js/bootstrap-switch.js"></script>
<script src="../../js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="../../js/dataTables.buttons.min.js"></script>

<script>
    $(document).ready(function() {
        $('.example').DataTable({
            "lengthMenu": [10, 25, 50, 75, 100],

        });
        $(".tg").on("change", function() {
            var uid = $(this).attr('data-id');
            var getid = $(this).attr('id');
            var c = $("#" + getid + ":checked").val();
            if (c == "on") {
                var info = {
                    uid: uid,
                    status: "1"
                };
                $.ajax({
                    type: "POST",
                    url: "statuschng.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                            $('.msgs').fadeIn();
                            $('.msgs').addClass("alert alert-success");
                            $('.msgs').html("Subadmin Activated Successfully");
                            $('.msgs').delay(5000).fadeOut();
                        } else {
                            $('.msgs').fadeIn();
                            $('.msgs').addClass("alert alert-danger");
                            $('.msgs').html("ERROR..");
                            $('.msgs').delay(5000).fadeOut();
                        }
                    }
                });
            } else {
                var info = {
                    uid: uid,
                    status: "0"
                };
                $.ajax({
                    type: "POST",
                    url: "statuschng.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                            $('.msgs').fadeIn();
                            $('.msgs').addClass("alert alert-success");
                            $('.msgs').html("Subadmin Deactivated Successfully");
                            $('.msgs').delay(5000).fadeOut();
                        } else {
                            $('.msgs').fadeIn();
                            $('.msgs').addClass("alert alert-danger");
                            $('.msgs').html("ERROR..");
                            $('.msgs').delay(5000).fadeOut();
                        }
                    }
                });
            }
        });
    })

    function del(id) {
        var uid = id;
        if (id != "") {
            if (confirm("Are You Sure To Delete This SUBADMIN? ")) {
                var info = {
                    uid: uid
                };
                $.ajax({
                    type: "POST",
                    url: "del.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                            $('.msgs').fadeIn();
                            $('.msgs').addClass("alert alert-success");
                            $('.msgs').html("Subadmin Deleted Successfully");
                            $('.msgs').delay(5000).fadeOut();
                            location.reload();
                        } else {
                            $('.msgs').fadeIn();
                            $('.msgs').addClass("alert alert-danger");
                            $('.msgs').html("ERROR..");
                            $('.msgs').delay(5000).fadeOut();
                        }
                    }
                });

            } else {

            }
        }
    }
</script>

</body>
</html>


<!doctype html>
<?php
     include("../../includes/configuration.php");
     $getclient=selectQuery(SUPPORTSTAFF,"*","isActive='1' AND isDel='0' AND acc_type='Client' order by emp_name ASC ");
     $getgroup=selectQuery(SUPPORTSTAFFGROUP,"*","group_status='1' and isDel='0'");
?>
<html lang='en'>
<head>
    <title><?php echo SITE_TITLE; ?> : Add Project</title>
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
</head>
<body>
<div class="dashcontainer">
      <?php include('../header.php') ?>

    <?php include('../sidebar.php') ?>

<div class="right_col">
    <div class="dashbody">
        <div class="card">
            <div class="panel-body">
                <div class="panel-heading">
                    <div class="panel-title pull-left">Projects</div>
                </div>
                    <div class="table-responsive">
                        <table class="example display table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Project</th>
                                <th>Client Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Action</th>
                             </tr>
                            </thead>
                            <tbody class="text-muted">
                                <?php
                                    for($i=0;$i<count($getclient);$i++)
                                    {
                                    ?>
                                        <tr>
                                            <td class="td2"><?php echo $i+1; ?></td>
                                            <td><a href="updateproject.php?empid=<?php echo base64_encode($getclient[$i]['emp_id'])?>" > <?php echo $getclient[$i]['emp_name']; ?> </a></td>
                                            <td><?php echo $getclient[$i]['projects']; ?></td>
                                            <td><?php echo $getclient[$i]['emp_email']; ?></td>
                                            <td><?php echo $getclient[$i]['mobile']; ?></td>
                                            <td>
                                                <?php
                                                    if($getclient[$i]['isActive']==1)
                                                    {
                                                        echo "Active";
                                                    }
                                                    else
                                                    {
                                                        "Deactive";
                                                    }
                                                ?>
                                            </td>
                                            <td> <a href="" onClick="delclient('<?php echo $getclient[$i]['emp_id']?>');" class="deletebtn red"><i class="fa fa-trash-o"></i></a></td>  <!--Just call script function for delete -->
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
        <?php include('../footer.php');?>
    </div>
</div>
<script src="../../js/bootstrap-switch.js"></script>
<script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="../../js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../../js/buttons.html5.min.js"></script>
<script src="../../js/bootstrap-toggle.min.js"></script>

<script>
 function delclient(clientid)
 {
    if(confirm("Are you sure want to delete?"))
    {

    info11={clientid:clientid,action:"del_client"};
        $.ajax({
            type:"POST",
            url:"addclientdata.php",
            data:info11,
            success:function(response){
                if(response==1)
                {
                    window.location="<?php echo ADMINURL; ?>/support/viewclient.php";
                }
                else
                {
                    alert("Error In Delete");
                }
            }
        });
    }
 }
</script>
</body>
</html>
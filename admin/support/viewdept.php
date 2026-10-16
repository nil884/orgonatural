<?php include("../../includes/configuration.php");
    $dept=base64_decode($_REQUEST['deptid']);
    $getdept=selectQuery(SUPPORTDEPT,"*","dept_id=".$dept);
    $staffs=selectQuery(SUPPORTSTAFF,"*","(acc_type='Staff' OR acc_type='Admin') AND  isActive='1' AND isDel='0' order by emp_name");
?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITE_TITLE; ?> : Department Settings</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php'); ?>
    <?php include('../sidebar.php'); ?>
      <div class="main-panel">
        <div class="dashbody">
            <?php include('supp-nav.php'); ?>
            <div class="tech-dept">
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center">
                        <div><h5 class="card-head-title"><?=$getdept[0]['dept_name']; ?> Configuration</h5></div>
                        <div class="btn-actions-pane-right"><a href="index.php" class="btn btn-secondary btn-sm"> Back</a>      </div>
                    </div>

                    <div class="card-body">
                        <div class="msgs"></div>
                        <div class="updateslatime">
                            <div class="innerupdateslatime">
                                <form class="form-horizontal">
                                    <input type="hidden" id="deptid" value="<?=$dept; ?>"/>
                                    <div class="form-group">
                                        <label class="col-md-3 mandatorystar">Department Name </label>
                                        <div class="col-md-4">
                                            <input type="text" name="deptName" id="deptName" class="form-control" onkeyup="namechk1('deptName')" value="<?=$getdept[0]['dept_name']; ?>" maxlength="20"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 mandatorystar">SLA Time <span>(SLA Time In Hours)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="sla" id="sla" class="form-control" onkeyup="chkno('sla')" value="<?=$getdept[0]['SLAtime']; ?>" maxlength="3"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 mandatorystar">Department Manager</label>
                                        <div class="col-md-4">
                                            <select class="form-control mgr">
                                                <option value="">- Select Manager -</option>
                                                <?php for($i=0;$i<count($staffs);$i++) { ?>
                                                <option value="<?=$staffs[$i]['emp_id']; ?>" <?=($staffs[$i]['emp_id']==$getdept[0]['dept_mgr']?"selected":"");?>><?=$staffs[$i]['emp_name']; ?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3"></label>
                                        <div class="col-md-9">
                                            <button type="button" class="btn btn-primary" id="updatesla"> Update</button>
                                            <a href="<?=ADMINURL?>support"  id="cancel" class="btn btn-secondary">Cancel  </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
    function chkno(inpid) {
        var entered = $("#" + inpid).val();
        if (isNaN(entered)) {
            $("#" + inpid).val("");
        }
    }
    $(document).ready(function() {
        $(".chngsla").click(function() {
            $(".updateslatime").toggle();
        })
        $("#cancelsla").click(function() {
            $(".updateslatime").hide();
        })
        $("#updatesla").click(function() {
            var sla = $("#sla").val();
            var deptid = $("#deptid").val();
            var deptmgr = $(".mgr option:selected").val();
            var deptName = $("#deptName").val();
            if (typeof deptName == "") {
                $("#deptName").css("border", "1px solid red");
            } else if (sla == "" || sla == 0) {
                $("#sla").css("border", "1px solid red");
            } else if (typeof deptmgr == "undefined") {
                $(".mgr").css("border", "1px solid red");
            } else {
                $("#sla,.mgr").css("border", "1px solid #A8A8A8");
                var info = {
                    deptid: deptid,
                    sla: sla,
                    deptmgr: deptmgr,
                    deptName: deptName
                };
                $.ajax({
                    type: "POST",
                    url: "updateSLA.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                             $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Updated Successfully").delay(1000).fadeOut();
                            location.reload();
                        } else {
                             $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Not Updated").delay(1000).fadeOut();
                        }
                    }
                });
            }
        })
    });
</script>
</body>
</html>
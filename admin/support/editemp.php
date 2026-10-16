
<?php include("../../includes/configuration.php");

    $emp=base64_decode($_REQUEST['emp']);
    $getdept=selectQuery(SUPPORTDEPT,"dept_id,dept_name","isActive='1'  and isDel='0'");
    $getgroup=selectQuery(SUPPORTSTAFFGROUP,"group_id,group_name","group_status='1' and isDel='0'");
    $getemp=selectQuery(SUPPORTSTAFF,"*","emp_id=".$emp);
?>
<!doctype html>
<html lang='en'>
<head>
    <title>Customer Helpdesk - Edit Staff Details</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?=METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?=METADESCRIPTION; ?>">
    <?php include('../commoncss.php'); ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php'); ?>
    <?php include('../sidebar.php'); ?>
      <div class="main-panel">
        <div class="dashbody">
            <?php include('supp-nav.php'); ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Edit Staff Details</h5></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-default btn-sm" onclick="goBack()"> Back</button>   </div>
                </div>

                <div class="card-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-2">Department<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select class="form-control dept">
                                    <option value="">Select Department</option>
                                    <?php for($i=0;$i<count($getdept);$i++) { ?>
                                    <option value="<?=$getdept[$i]['dept_id']; ?>" <?=($getdept[$i]['dept_id']==$getemp[0]['department']?"selected":""); ?>><?=$getdept[$i]['dept_name']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Group<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select class="form-control group">
                                    <option value="">Select Groups</option>
                                    <?php for($i=0;$i<count($getgroup);$i++) { ?>
                                    <option value="<?=$getgroup[$i]['group_id']; ?>" <?=($getgroup[$i]['group_id']==$getemp[0]['emp_group']?"selected":""); ?>><?=$getgroup[$i]['group_name']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Employee Name<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="empname" id="empname" onblur="firstcapital('empname')" class="form-control empname text-capitalize" maxlength="50" value="<?=$getemp[0]['emp_name']; ?>" />
                                <input type="hidden" name="empid" class="form-control empid" value="<?=$emp; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Email<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="empemail" id="empemail" class="form-control empemail" maxlength="70" value="<?=$getemp[0]['emp_email']; ?>" onblur="checkemail('empemail')" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Username<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="username" id="username"  class="form-control username" maxlength="20" value="<?=$getemp[0]['username']; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Set Password</label>
                            <div class="col-md-4">
                                <input type="password" name="emppwd" class="form-control emppwd" maxlength="20" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Status</label>
                            <div class="col-md-4">
                                <input type="radio" name="status" class="status"  value="1" <?=($getemp[0]['isActive']=="1"?"checked":""); ?> /> Active&nbsp;&nbsp;
                                <input type="radio" name="status" class="status"  value="0" <?=($getemp[0]['isActive']=="0"?"checked":"");  ?> /> Deactive&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Account Type</label>
                            <div class="col-md-4">
                                <input type="radio" name="acctype" class="acctype"  value="Admin" <?=($getemp[0]['acc_type']=="Admin"?"checked":""); ?> /> Admin&nbsp;&nbsp;
                                <input type="radio" name="acctype" class="acctype"  value="Staff" <?=($getemp[0]['acc_type']=="Staff"?"checked":"");  ?> /> Staff&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2"></label>
                            <div class="col-md-4">
                                <input type="checkbox" name="access" class="access" value="1" <?=($getemp[0]['admin_panel_access']=="1"?"checked":""); ?> /> Give Admin Panel Access&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2"></label>
                            <div class="col-md-4">
                                <button type="button" id="submit" class="btn btn-primary"> Update </button>
                                <a href="<?=ADMINURL?>support/staff.php"  id="cancel" class="btn btn-danger">Cancel  </a>
                                <button type="button" id="delbtn" class="btn btn-primary" onclick="del('<?=$emp; ?>')"> Delete Staff </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('../footer.php'); ?>
     </div>
</div>
<script>
    function checkemail(inpid) {
        var email = $("#" + inpid).val();
        var patt = new RegExp(/^[a-zA-Z0-9._\-]+@[a-zA-Z.\-]+\.[a-zA-Z]{1,4}$/);
        var res = patt.test(email);
        if (res) {
            $("#" + inpid).css("border", "1px solid #c3c3c3");
            $("#submit").prop("disabled", false);
        } else {
            $("#" + inpid).css("border", "1px solid red");
            $("#submit").prop("disabled", true);
        }
    }
     function del(i){
          msg= "Do u really want to delete this staff? "+
          "Info : Deleting employee will NOT afffect the previously defined tickets";
            del_alertbox(msg, i,"delstaff");
        }
    function delstaff(empid) {
        var info = {empid: empid};
        $.ajax({
            type: "POST",
            url: "deletestaff.php",
            data: info,
            success: function(response) {
                if (response == 1) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Staff  Deleted Successfully").delay(1000).fadeOut();
                   window.setTimeout(function() {window.location = "staff.php"; }, 1000);
                } else {
                     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                }
            }
        });

    }
    $(document).ready(function() {
        $("#submit").on("click", function() {
            var empid = $(".empid").val();
            var username = $(".username").val();
            var dept = $(".dept option:selected").val();
            var group = $(".group option:selected").val();
            var empname = $(".empname").val();
            var empemail = $(".empemail").val();
            var emppwd = $(".emppwd").val();
            var status = $(".status:checked").val();
            var acctype = $(".acctype:checked").val();
            var encoded = $(".encodeddept").val();
            var access = $(".access:checked").val();
            if (typeof access == "undefined") { adminacceess = "0";
            } else {adminacceess = "1"; }

            if ((username != "") && (empname != "") && (dept != "") && (group != "") && (empemail != "")) {
                var info = {
                    empid: empid,username: username,dept: dept,group: group,empname: empname,empemail: empemail,emppwd: emppwd,status: status,acctype: acctype,adminacceess: adminacceess
                };
                $.ajax({
                    type: "POST",
                    url: "updatestaffdata.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                             $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Staff Details Updated Successfully").delay(1000).fadeOut();
                            window.setTimeout(function() {window.location = "staff.php"; }, 1000);
                        }
                        else if(response == 3){
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Staff with same Email-id allready Exist.").delay(1000).fadeOut();
                        }
                        else if (response == 0) {
                              $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Not Inserted").delay(1000).fadeOut();
                        }
                    }
                });
            } else {
                 $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Fill All Requierd Fields").delay(1000).fadeOut();
            }
        });
    });
</script>
</body>
</html>
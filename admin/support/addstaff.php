<?php include("../../includes/configuration.php");
 $getdept = selectQuery(SUPPORTDEPT,"dept_id,dept_name","isActive='1' and isDel='0'");
 $getgroup = selectQuery(SUPPORTSTAFFGROUP,"group_id,group_name","group_name!='Client' AND group_status='1' and isDel='0'"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Add New Staff</title>
    <?php include('../commoncss.php'); ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php'); ?>
    <?php include('../sidebar.php'); ?>
      <div class="main-panel">
        <div class="dashbody">
            <?php include('supp-nav.php'); ?>
            <div class="card mb-0">
                 <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Add Staff</h2></div><div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()">Back</button></div></div>
                <div class="card-body">
                    <div class="msgs"></div>
                     <form>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label">Department<span class="text-danger">*</span></label>
                            <div class="col-sm-8 col-md-8 col-lg-5 col-xl-4">
                                <select class="form-control dept">
                                    <option>Select Department</option>
                                    <?php for($i=0;$i<count($getdept);$i++){ ?>
                                    <option value="<?=$getdept[$i]['dept_id']; ?>"><?=$getdept[$i]['dept_name']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Group<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select class="form-control group">
                                    <option value="">Select Groups</option>
                                    <?php for($i=0;$i<count($getgroup);$i++){ ?>
                                    <option value="<?=$getgroup[$i]['group_id']; ?>" ><?=$getgroup[$i]['group_name']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Employee Name<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="empname" id="empname" onblur="firstcapital('empname')" onkeyup="namechk1('empname')" class="form-control empname text-capitalize" maxlength="50" value="" />
                                <input type="hidden" name="deptid" id="deptid" class="deptid" value="<?=$dept; ?>"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Email<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="empemail" id="empemail" class="form-control empemail" autocomplete="off" value="" maxlength="70" onblur="mailchk('empemail');checkemail('empemail');">
                                <input type="hidden" name="isemail" class="form-control isemail" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Username<span class="text-danger">*</span></label>
                            <div class="col-md-4"><input type="text" name="username" id="username" class="form-control username" maxlength="20" /></div>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label">Set Password<span class="text-danger">*</span></label>
                            <div class="col-md-4"><input type="password" name="emppwd" class="form-control emppwd" maxlength="20" value="" autocomplete="off" /></div>
                         </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label">Account Type</label>
                            <div class="col-md-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="acctype" class="acctype custom-control-input" id="actypeadmin" value="Admin" /><label class="custom-control-label" for="actypeadmin">Admin</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="acctype" class="acctype custom-control-input" id="actypestaff" value="Staff" checked /><label class="custom-control-label" for="actypestaff">Staff</label>
                                </div>
                            </div>
                         </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"></label>
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="access" class="access custom-control-input" id="adpanelacc" value="1" <? if($getemp[0]['admin_panel_access']=="1"){echo "checked";}else{}  ?> />
                                <label class="custom-control-label" for="adpanelacc">Give Admin Panel Access</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-md-2 col-form-label"></label>
                            <div class="col-md-4">
                                <button type="button" id="submit" class="btn btn-primary">Add</button>
                                <button type="reset" id="cancel" class="btn btn-danger">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
$(".empemail").val(""); $(".emppwd").val("");
function checkemail(inpid){
    var email = $("#" + inpid).val();
    var checktype = "checkemail";
    var patt = new RegExp(/^[a-zA-Z0-9._\-]+@[a-zA-Z.\-]+\.[a-zA-Z]{1,4}$/);
    var res = patt.test(email);
    if(res){
        $("#" + inpid).css("border", "1px solid #c3c3c3");
        $("#submit").prop("disabled", true);
        var info = { email: email,action: checktype };
        $.ajax({
            type: "POST",url: "checkavalability.php",data: info,
            success: function(response){
                $("#submit").prop("disabled", false);
                if(response == "1") {
                    $(".isemail").val("1");
                    $("#submit").prop("disabled", false);
                } if(response == "0"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("One Employee Is Active With Same Email. Please Change Email").delay(1000).fadeOut();
                    $(".isemail").val("0");
                    $("#submit").prop("disabled", false);
                }
            }
        });
    } else{
        $("#" + inpid).css("border", "1px solid red");
        $("#submit").prop("disabled", true);
    }
}
$(document).ready(function(){
    $("#submit").on("click", function(){
        checkemail('empemail');
        var empid = $(".empid").val();
        var username = $(".username").val();
        var dept = $(".dept option:selected").val();
        var group = $(".group option:selected").val();
        var empname = $(".empname").val();
        var empemail = $(".empemail").val();
        var emppwd = $(".emppwd").val();
        /*var status= $(".status:checked").val();*/
        /*var checkpost=$(".ispost").val();*/
        var checkmail = $(".isemail").val();
        var acctype = $(".acctype:checked").val();
        var access = $(".access:checked").val();
        if(typeof access == "undefined"){ adminacceess = "0"; } else{ adminacceess = "1"; }
        if((username == "") || (empname == "") || (dept == "") || (group == "") || (empemail == "") || (emppwd == "")){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Fill All Requierd Fields").delay(1000).fadeOut();
        } else{
            $("#submit").prop("disabled", true);
            var info = {
                username: username,
                empname: empname,
                empemail: empemail,
                emppwd: emppwd,
                dept: dept,
                group: group,
                acctype: acctype,
                adminacceess: adminacceess
            };
            $.ajax({
                type: "POST",
                url: "addstaffdata.php",
                data: info,
                success: function(response) {
                    $("#submit").prop("disabled", false);
                    if (response == 0) {
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Not Inserted").delay(1000).fadeOut();
                    } else if(response == 2){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("One Employee Is Active With Same Email. Please Change Email").delay(1000).fadeOut();
                    } else{
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Staff Added Successfully").delay(1000).fadeOut();
                        window.setTimeout(function(){ window.location = "staff.php"; }, 500);
                    }
                }
            });
        }
    });
});
</script>
</body>
</html>
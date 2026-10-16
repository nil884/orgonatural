<!doctype html>
<?php include("../../includes/configuration.php");
    $getalldept=selectQuery(SUPPORTDEPT,"dept_id,dept_name",'isActive="1"');
?>
<html lang='en'>
<head>
    <title>Customer Helpdesk - Add New Staff group </title>
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
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Add New Staff group</h5></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-default btn-sm" onclick="goBack()"> Back</button> </div>
                </div>

                <div class="card-body">
                    <div class="msgs"></div>
                    <form autocomplete="off">

                            <div class="form-group row">
                                <label class="col-md-3 control-label">Staff Group</label>
                                <div class="col-md-4">
                                    <input type="text" name="grpname" id="grpname"  class="form-control grpname text-capitalize" maxlength="50" value="" onblur ="checkname();"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Status</label>
                                <div class="col-md-4">
                                    <label class="radio-inline">
                                    <input type="radio"  class="status"  value ="1" name="status" checked="checked">Active
                                    </label>
                                    <label class="radio-inline">
                                    <input type="radio"  class="status" value="0"  name="status" >Deactive
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Dept Access</label>
                                <div class="col-md-4">
                                    <?php for($i= 0;$i< count($getalldept);$i++ ){ ?>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="dept" class="dept" value ="<?=$getalldept[$i]['dept_id']; ?>"/> <?=$getalldept[$i]['dept_name']; ?></label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Can Create Ticket</label>
                                <div class="col-md-4">
                                    <label class="radio-inline">
                                        <input type="radio"  class="Create"  value ="1" name="Create">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"  class="Create" value="0"  name="Create"  checked="checked">No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Can Edit Ticket</label>
                                <div class="col-md-4">
                                    <label class="radio-inline">
                                        <input type="radio"  class="Edit"  value ="1" name="Edit">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"  class="Edit" value="0"  name="Edit" checked="checked" >No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Can Close Ticket</label>
                                <div class="col-md-4">
                                    <label class="radio-inline">
                                        <input type="radio"  class="Close"  value ="1" name="Close">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"  class="Close" value="0"  name="Close"  checked="checked">No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Can Transfer Ticket</label>
                                <div class="col-md-4">
                                    <label class="radio-inline">
                                        <input type="radio"  class="Transfer"  value ="1" name="Transfer">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"  class="Transfer" value="0"  name="Transfer"  checked="checked" >No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Can Delete Ticket</label>
                                <div class="col-md-4">
                                    <label class="radio-inline">
                                        <input type="radio"  class="Delete1"  value ="1" name="Delete1">Yes
                                    </label>
                                        <label class="radio-inline">
                                    <input type="radio"  class="Delete1" value="0"  name="Delete1" checked="checked" >No
                                    </label>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                            <label class="col-md-2">Can Create Guest</label>
                            <div class="col-md-4">
                            <label class="radio-inline">
                            <input type="radio"  class="createguest"  value ="1" name="createguest">Yes
                            </label>
                            <label class="radio-inline">
                            <input type="radio"  class="createguest" value="0"  name="createguest" checked="checked" >No
                            </label>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-md-2">Can Edit Guest</label>
                            <div class="col-md-4">
                            <label class="radio-inline">
                            <input type="radio"  class="editguest"  value ="1" name="editguest">Yes
                            </label>
                            <label class="radio-inline">
                            <input type="radio"  class="editguest" value="0"  name="editguest" checked="checked" >No
                            </label>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-md-2">Can Delete Guest</label>
                            <div class="col-md-4">
                            <label class="radio-inline">
                            <input type="radio"  class="Deleteguest"  value ="1" name="Deleteguest">Yes
                            </label>
                            <label class="radio-inline">
                            <input type="radio"  class="Deleteguest" value="0"  name="Deleteguest" checked="checked" >No
                            </label>
                            </div>
                            </div>-->
                            <div class="row">
                                <label class="col-md-3"></label>
                                <div class="col-md-4">
                                    <button type="button" id="submit" class="btn btn-primary"> Add </button>
                                    <a href="<?=ADMINURL?>support/staffgrouplist.php"  id="cancel" class="btn btn-default">Cancel</a>
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
    $(document).ready(function() {
        $("#submit").on("click", function() {
            var grpname = $(".grpname").val();
            var status = $('input[name="status"]:checked').val();
            var Create = $('input[name="Create"]:checked').val();
            var Edit = $('input[name="Edit"]:checked').val();
            var Close = $('input[name="Close"]:checked').val();
            var Transfer = $('input[name="Transfer"]:checked').val();
            var Delete1 = $('input[name="Delete1"]:checked').val();
            var createguest = $('input[name="createguest"]:checked').val();
            var editguest = $('input[name="editguest"]:checked').val();
            var Deleteguest = $('input[name="Deleteguest"]:checked').val();
            var action = "add";
            if ($('.dept:checked').length) {
                var chkId = '';
                $('.dept:checked').each(function() {
                    chkId += $(this).val() + ",";
                });
                chkId = chkId.slice(0, -1);
            }

            if ((grpname == "") || (status == "undefined") || (Create == "undefined") || (Edit == "undefined") || (Transfer == "undefined" || Delete1 == "")) {
             $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all requierd fields").delay(1000).fadeOut();

            } else {
                var info = {
                    grpname: grpname,
                    status: status,
                    chkId: chkId,
                    Create: Create,
                    Edit: Edit,
                    Close: Close,
                    Transfer: Transfer,
                    Delete1: Delete1,
                    action: action
                };
                $.ajax({
                    type: "POST",
                    url: "staffgroupdata.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                            $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Staff group Added Successfully").delay(1000).fadeOut();
                            window.setTimeout(function() {
                                window.location.href = "<?=SITEURL ?>/admin/support/staffgrouplist.php";
                            }, 500);

                        } else if (response == 3) {
                             $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Group Name already Exist").delay(1000).fadeOut();
                        } else {
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Not Inserted").delay(1000).fadeOut();
                        }
                    }
                });
            }
        });
    });

    function checkname() {
        var grpname = $(".grpname").val();
        var action = "checkavailabilty";
        var info = { grpname: grpname,action: action }
        $.ajax({
            type: "POST", url: "staffgroupdata.php",data: info,
            success: function(response) {
                if (response == "1") {
                     $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Staff Group name available").delay(1000).fadeOut();
                    $("#submit").removeAttr("disabled", "disabled");
                } else {
                     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Staff Group name Not available").delay(1000).fadeOut();
                    $("#submit").prop("disabled", "disabled");
                }
            }
        });
    }
</script>
</body>
</html>
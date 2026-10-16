<?php include("../../includes/configuration.php");
    $getalldept=selectQuery(SUPPORTDEPT,"dept_id,dept_name",'isActive="1"');
    $staffgrpid = base64_decode($_REQUEST['gid']);
    $getstafgrpdetail=selectQuery(SUPPORTSTAFFGROUP,"*","group_id='".$staffgrpid."'");
    $dept = explode(",", $getstafgrpdetail[0]['access_to_dept']);

?>
<!doctype html>
<html lang='en'>
<head>
    <title>Customer Helpdesk - Update Staff Group</title>
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
                    <div><h5 class="card-head-title">Update Staff Group</h5></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-default btn-sm" onclick="goBack()"> Back</button>   </div>
                </div>

                <div class="card-body">
                    <div class="msgs"></div>
                    <form autocomplete="off" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3">Staff Group</label>
                            <div class="col-md-4">
                                <?=$getstafgrpdetail[0]['group_name'];?>
                                <input type="hidden" name="grpname" id="grpname"  class="form-control grpname text-capitalize"  value="<?=$getstafgrpdetail[0]['group_name'];?>" />
                                <input type="hidden" name="grid" id="grid"  class="form-control grid text-capitalize"  value="<?=$getstafgrpdetail[0]['group_id'];?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Status</label>
                            <div class="col-md-4">
                                <label class="radio-inline">
                                <input type="radio"  class="status"  value ="1" name="status" <?=($getstafgrpdetail[0]['group_status'] == 1?"checked":""); ?>>Active
                                </label>
                                <label class="radio-inline">
                                <input type="radio"  class="status" value="0"  name="status" <?=($getstafgrpdetail[0]['group_status'] == 0?"checked":"");?> >DeActive
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Department Access</label>
                            <div class="col-md-7">
                                <?php for($i= 0;$i< count($getalldept);$i++ ){ ?>
                                <label class="checkbox-inline paddtop">
                                <input type="checkbox" name="dept" class="dept" value ="<?=$getalldept[$i]['dept_id']; ?>"   <?=(in_array($getalldept[$i]['dept_id'], $dept)?"checked":""); ?>/> <?=$getalldept[$i]['dept_name']; ?>
                                </label>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Can Create Ticket</label>
                            <div class="col-md-8">
                                <label class="radio-inline paddtop">
                                <input type="radio"  class="Create"  value ="1" name="Create"   <?=($getstafgrpdetail[0]['Create'] == 1?"checked":"");?>>Yes
                                </label>
                                <label class="radio-inline paddtop">
                                <input type="radio"  class="Create" value="0"  name="Create" <?=($getstafgrpdetail[0]['Create'] == 0?"checked":""); ?> >No
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Can Edit Ticket</label>
                            <div class="col-md-8">
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Edit"  value ="1" name="Edit" <?=($getstafgrpdetail[0]['Edit'] == 1?"checked":""); ?> >Yes
                                </label>
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Edit" value="0"  name="Edit" <?=($getstafgrpdetail[0]['Edit'] == 0?"checked":""); ?>>No
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Can Close Ticket</label>
                            <div class="col-md-8">
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Close"  value ="1" name="Close"  <?=($getstafgrpdetail[0]['Close'] == 1?"checked":""); ?> >Yes
                                </label>
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Close" value="0"  name="Close" <?=($getstafgrpdetail[0]['Close'] == 0?"checked":""); ?>>No
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Can Transfer Ticket</label>
                            <div class="col-md-8">
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Transfer"  value ="1" name="Transfer" <?=($getstafgrpdetail[0]['Transfer'] == 1?"checked":""); ?> >Yes
                                </label>
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Transfer" value="0"  name="Transfer" <?=($getstafgrpdetail[0]['Transfer'] == 0?"checked":"");  ?>>No
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Can Delete Ticket</label>
                            <div class="col-md-8">
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Delete1"  value ="1" name="Delete1" <?=($getstafgrpdetail[0]['Delete1'] == 1?"checked":""); ?> >Yes
                                </label>
                                <label class="radio-inline paddtop">
                                    <input type="radio"  class="Delete1" value="0"  name="Delete1" <?=($getstafgrpdetail[0]['Delete1'] == 0?"checked":""); ?>>No
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3"></label>
                            <div class="col-md-4">
                                <button type="button" id="submit" class="btn btn-primary"> Update </button>
                                <a href="<?=ADMINURL?>support/staffgrouplist.php"  id="cancel" class="btn btn-danger">Cancel</a>
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
            var grid = $(".grid").val();
            var grpname = $(".grpname").val();
            var status = $('input[name="status"]:checked').val();
            var Create = $('input[name="Create"]:checked').val();
            var Edit = $('input[name="Edit"]:checked').val();
            var Close = $('input[name="Close"]:checked').val();
            var Transfer = $('input[name="Transfer"]:checked').val();
            var Delete1 = $('input[name="Delete1"]:checked').val();
            var action = "update";
            if ($('.dept:checked').length) {
                var chkId = '';
                $('.dept:checked').each(function() {
                    chkId += $(this).val() + ",";
                });
                chkId = chkId.slice(0, -1);
            }

            if ((grpname == "") || (status == "undefined") || (Create == "undefined") || (Edit == "undefined") || (Transfer == "undefined" || Delete1 == "")) {
                $('.msgs').fadeIn().addClass("alert alert-danger").html("Please fill all requierd fieldss").delay(5000).fadeOut();
            } else {
                var info = {
                    grid: grid,grpname: grpname,status: status,chkId: chkId,Create: Create, Edit: Edit,Close: Close,Transfer: Transfer,Delete1: Delete1,action: action
                };
                $.ajax({
                    type: "POST",
                    url: "staffgroupdata.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                             $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Staff Group Updated Successfully").delay(1000).fadeOut();

                            window.setTimeout(function() {
                                window.location.href = "<?=SITEURL ?>/admin/support/staffgrouplist.php";
                            }, 1000);

                        } else {
                           $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                        }
                    }
                });
            }
        });
    });
</script>
</body>
</html>
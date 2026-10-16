<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title>Customer Helpdesk - Add New Department</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
      <div class="main-panel">
        <div class="dashbody">
            <?php include('supp-nav.php') ?> 
            <div class="card">
                <div class="card-header-tab card-header"><div class="card-header-title">Add New Department</div></div>
                <div class="card-body">
                   <div class="row">
                        <div class="msgs1"></div>
                        <form class="form-horizontal col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label mandatorystar">Department Name</label>
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                   <input type="text" name="dept" id="dept" onkeyup="checkchar(event,'dept')" onblur="firstcapital('dept')" class="form-control text-capitalize" placeholder="Department Name"  maxlength="20" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label mandatorystar">SLA Time <span><small class="text-muted">(SLA Time In Hours)</small></span></label>
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                    <input type="text" name="sla" id="sla" class="form-control" onkeyup="chkno('sla')" maxlength="3" placeholder="SLA Time"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-md-offset-3">
                                    <button type="button" name="create" id="submit" class="btn btn-primary" >Add Department <span class="loader"></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
     </div>
</div>
<script>
    function checkchar(event, id) {
        var key = event.which || event.keyCode;
        var inp = $("#" + id).val();
        if (key >= 65 && key <= 90 ||
            // Backspace and Tab and Enter and shift
            key == 8 || key == 9 || key == 13 || key == 16 ||
            // Space and Home and End
            key == 32 || key == 35 || key == 36 ||
            // Del and Ins
            key == 46 || key == 45) {
            $("#" + id).val(inp);
        } else {
            var newstr = inp.slice(0, -1);
            $("#" + id).val(newstr);
        }
    };

    function chkno(inpid) {
        var entered = $("#" + inpid).val();
        if (isNaN(entered)) { $("#" + inpid).val("");  }
    }

    $(document).ready(function() {
        $("#submit").on("click", function() {
            var dept = $("#dept").val();
            var sla = $("#sla").val();
            if ((dept != "") && (sla != "")) {
                var info = { dept: dept, sla: sla
                };
                $.ajax({
                    type: "POST",
                    url: "deptdata.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                             $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Department Added Successfully").delay(1000).fadeOut();

                            window.setTimeout(function() {  window.location = "index.php"; }, 500);
                        }
                        if (response == 0) {
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Department Already available").delay(1000).fadeOut();
                        }
                        if (response == 2) {
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Not Inserted").delay(1000).fadeOut();
                        }
                    }
                });
            } else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all requierd fields").delay(1000).fadeOut();
            }
        });
    });
</script>
</body>
</html>
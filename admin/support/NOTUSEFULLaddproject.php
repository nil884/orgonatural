<!doctype html>
<?php
     include("../../includes/configuration.php");
     $getclient=selectQuery(SUPPORTCLIENT,"*","isActive='1'");
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
            <div class="panel-title pull-left">Add New Project</div>
            <div class="pull-right"><button class="btn btn-default pull-right btn-sm" onclick="goBack()"> Back</button>  </div>
        </div>

          <div class="msgs"></div>
         <form class="form-horizontal col-md-12">
         <div class="row">

            <div class="form-group">
                <label class="col-md-2">Client<span style="color:red;">*</span></label>
                <div class="col-md-4">

                    <select class="form-control" id="client">
                        <option>Select Client</option>
                    <?php
                        for($i=0;$i<count($getclient);$i++)
                        {
                        ?>
                            <option value="<?php echo $getclient[$i]['client_id']; ?>"><?php echo $getclient[$i]['client_name']; ?></option>
                        <?}
                    ?>

                    </select>
                </div>
            </div>

             <div class="form-group">
                <label class="col-md-2">Project Name<span style="color:red;">*</span></label>
                <div class="col-md-4">
                  <input type="text" name="projectname" id="projectname" onblur="firstcapital('projectname')" onkeyup="checkchar(event,'projectname')" class="form-control projectname text-capitalize" maxlength="50" value="" />

                </div>
             </div>

              <div class="form-group col-md-12">
                <label class="col-md-2"></label>
                <div class="col-md-4">
                  <button type="button" id="submit" class="btn btn-primary"> Add </button>
                  <button type="reset" id="cancel" class="btn btn-danger"> Cancel </button>
                </div>
             </div>
             </div>
         </form>
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
    $(".clientemail").val("");
    $(".clientpwd").val("");
function goBack() {
        window.history.back();
}

function checkchar(event,id)
{
    var key = event.which || event.keyCode;
    var inp=$("#"+id).val();
    if (key >= 65 && key <= 90 ||
        // Backspace and Tab and Enter and shift
            key == 8 || key == 9 || key == 13 ||key == 16 ||
        // Space and Home and End
            key == 32 ||key == 35 || key == 36 ||
        // Del and Ins
            key == 46 || key == 45)
        {
            $("#"+id).val(inp);
        }
        else
        {
            var newstr = inp.slice(0, -1);
            $("#"+id).val(newstr);
        }
 };


$(document).ready(function() {
    $("#submit").on("click",function() {
        var client_id =$("#client option:selected").val();
        var projectname= $(".projectname").val();

        if ((client_id=="")||(projectname == ""))
        {
            $('.msgs').fadeIn();
            $('.msgs').addClass("alert alert-danger");
            $('.msgs').html("Please Fill All Required Fields");
            $('.msgs').delay(5000).fadeOut();
        }
        else
        {
            $("#submit").prop("disabled",true);
            var infodata={client_id:client_id,projectname:projectname,action:"addproject"};
            $.ajax({
                type:"POST",
                url:"addclientdata.php",
                data:infodata,
                success:function(response){

                    if(response==2)
                    {
                        $('.msgs').fadeIn();
                        $('.msgs').addClass("alert alert-success");
                        $('.msgs').html("Info Added Successfully!!!");
                        $('.msgs').delay(5000).fadeOut();
                    }
                    else
                    {
                        $('.msgs').fadeIn();
                        $('.msgs').addClass("alert alert-success");
                        $('.msgs').html("Info Not Added");
                        $('.msgs').delay(5000).fadeOut();

                    }
                }
            });
        }
    });
});
</script>

<script>
    $("#menu27").addClass('active') ;
    $("#menu27").children("ul").show();
</script>
</body>
</html>
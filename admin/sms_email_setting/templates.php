<?php include ("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Update SMS</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $getblankTemp = selectQuery(SMSTEMPLATE,"*","1 order by type");
     ?>
    <div class="main-panel">
        <div class="dashbody mail-temp-var">
            <div class="card mb-0 border-bottom-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2 border-bottom-0">
                    <div class="card-head-title">SMS Templates</div>
                    <div class="btn-actions-pane-right"><button onclick="window.history.back();" class="btn btn-secondary btn-sm">Back</button></div>
                </div>
            </div>

            <div class="card mail-com-varb mb-0 rounded-bottom">

                <div class="card-body">
                    <div id="tablereload">
                        <table class="table table-bordered mb-0 smstemplate">
                        <thead><tr><th>No.</th><th>SMS Type</th><th>Send To</th><th>Template</th><th>Template ID</th><th>Action</th></tr></thead>
                        <tbody>
                        <? for($i=0;$i<count($getblankTemp);$i++){
                            $row=$getblankTemp[$i];
                            ?><tr id="row<?=$row['id']; ?>"><td><?= $i+1; ?></td><td><?=$row['type']; ?></td><td><?=$row['sms_to']; ?></td><td id="sms<?=$row['id']; ?>"><?=$row['sms_text']; ?></td><td><?=$row['templateId']; ?></td><td><button type="button" class="btn btn-sm btn-info <?=($row['templateId']==''?'':'cc-display-none'); ?>" onclick="addid('<?=$row['id']; ?>','<?=$row['type']; ?>','<?=$row['sms_to']; ?>','sms<?=$row['id']; ?>')">Add Template ID</button><button type="button" class="btn btn-sm btn-info <?=($row['templateId']==''?'cc-display-none':''); ?>" onclick="updateid('<?=$row['id']; ?>','<?=$row['type']; ?>','<?=$row['sms_to']; ?>','sms<?=$row['id']; ?>','<?=$row['templateId']; ?>')">Update Template ID</button></td></tr> <?
                        } ?>
                        </tbody>
                        </table>
                   </div>
                </div>
            </div>


        </div>
        <?php include '../footer.php'; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>$(document).ready(function(){$('.smstemplate').DataTable({ "scrollX": true });});</script>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal body -->
      <div class="modal-body">
            <div class="form-group">
                <label><b>SMS Type</b></label>
                <p id="smstype"></p>
            </div>
            <div class="form-group">
                <label><b>SMS To</b></label>
                <p id="smsto"></p>
            </div>
            <div class="form-group">
                <label><b>SMS Template</b></label>
                <p id="smstemp"></p>
            </div>
            <div class="form-group">
                <label for="templateid"><b>Template ID</b></label>
                <input type="hidden" class="form-control" id="smsid" name="smsid">
                <input type="text" class="form-control" id="templateid" placeholder="Enter Template ID" name="templateid" maxlength="40">
            </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="addnow()">Update Template ID</button>   <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<script>
function addid(id,smstype,smsto,sms){
    smstext=$("#"+sms).html()
  $("#smsid").val(id);$("#smstype").html(smstype);$("#smsto").html(smsto);$("#smstemp").html(smstext);
  $("#myModal").modal("show");
}

function updateid(id,smstype,smsto,sms,templateId){
    smstext=$("#"+sms).html(); $("#templateid").val(templateId);
  $("#smsid").val(id);$("#smstype").html(smstype);$("#smsto").html(smsto);$("#smstemp").html(smstext);
  $("#myModal").modal("show");
}

function addnow(){
  var smsid=$("#smsid").val();
  var templateid=$("#templateid").val();
  if(templateid!=""){
        var info = {smsid:smsid,templateid:templateid,action:"addSMSTemplateId"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $("#myModal").modal("hide");    
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Template ID Added ").delay(3000).fadeOut();
                   setTimeout(function(){ $("#tablereload").load(" #tablereload > *");}, 500);
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
                }
            }
        });
  }

}

</script>
</body>
</html>
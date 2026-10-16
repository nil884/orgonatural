<? include("../../includes/configuration.php");
$Footer_script = selectQuery(FOOTERSCRIPT,"*","1 order by priority"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Third Party Scripts</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert alert-info mb-0">
                    <h6><b>Important</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Adding Third-Party Scripts may interfere with website security policies, in such situations top priority will be given to the website`s security and the script will be made non-functional. But still, you want to use the script - please send an email to <strong>support@surun.in</strong> along with the script details. We will analyze your script and if found script is harmless then we will allow its integration into your website.</li>
                        <li class="mb-1">When you add the script, the script itself contains the icon placement so if you observe the icons are overlapping, then you need to understand and modify the script accordingly</li>
                        <li>Version on this page is just for reference, so if you don’t have right version you can enter 1.0</li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h5 class="card-head-title">Add Third Party Script</h5></div><div class="btn-actions-pane-right"><a href="<?=DOCUMENTURL;?>google-analytics.php" class="btn btn-primary btn-sm" target="_blank">Google Analytics</a></div></div> 
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-12 col-lg-6 form-group">
                            <label class="cc-mandatary-field">Script Name</label>
                            <input type="text" placeholder="Please Enter Script Name" class="form-control" id="scriptname">
                        </div>
                        <div class="col-sm-6 col-md-12 col-lg-6 form-group">
                            <label class="cc-mandatary-field">Script Version</label>
                            <input type="text" placeholder="Please Enter Script Version" class="form-control" id="scriptversion">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 form-group mb-0">
                            <label class="cc-mandatary-field">Script Text</label>
                            <textarea name="script_text" id="script_text" rows="10" placeholder="Please Enter Script Text" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2 text-right"><input type="button" value="Submit" class="btn btn-primary text-right" name="Footer script" id="Footer_scriptsubmit"></div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Third Party Scripts</h5></div>
                <div class="card-body foot-scr-load">
                    <div id="footerscriptlist-col">
                        <table class="example table table-bordered w-100" id="Footer_script_table">
                            <thead><tr><th>#</th><th>Script Name</th><th>Script Version</th><th>Script</th><th>Status</th><th>Delete</th></tr></thead>
                            <tbody>
                                <?php for($i=0;$i<count($Footer_script);$i++){ ?>
                                <tr id="<?php echo $Footer_script[$i]['id']; ?>">
                                    <td><?php echo $i+1; ?></td>
                                    <td class="script-name"><?php echo $Footer_script[$i]['name']; ?></td>
                                    <td><?php echo $Footer_script[$i]['vesrion']; ?></td>
                                    <td><button type="button" class="btn btn-primary btn-sm show-script" data-toggle="modal" data-target="#myModal">Show Script</button>
                                    <pre class="mb-0 scr-view d-none"><?php echo htmlentities($Footer_script[$i]['script']); ?></pre></td>
                                    <td> 
                                        <label class="switch btn btn-primary"><input type="checkbox" data-id="<?php echo $Footer_script[$i]['id']; ?>" data-width="100" data-height="30" id="checkbox0_<?php echo $Footer_script[$i]['id']; ?>" name="checkbox0_<?php echo $Footer_script[$i]['id']; ?>" <? if($Footer_script[$i]['isActive']==1){echo "checked";}else{echo "";} ?> onchange="active('<?php echo $Footer_script[$i]['id']; ?>','checkbox0_<?php echo $Footer_script[$i]['id']; ?>')"><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label>
                                    </td>
                                    <td><button type="button" onclick="del('<?php echo $Footer_script[$i]['id']; ?>')" class="btn btn-danger btn-sm deletebtn"><i class="fa fa-trash-o"></i></button></td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?> 
    </div>    
</div> 
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title mod-src-name">Third Party Scripts</h4></div>
            <div class="modal-body"><pre class="foo-scr-dis mb-0"></pre></div>
            <div class="modal-footer text-right py-2">
            <button type="button" class="btn btn-danger ml-auto" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(".show-script").click(function(){
    var getscr = $(this).next().text();
    var srcname = $(this).parents("tr").find(".script-name").text();
    $(".foo-scr-dis").text(getscr);
    $(".mod-src-name").text(srcname);
})
function loaddatatable(){$('.example').DataTable({"scrollX": true});}
loaddatatable();
$("table tbody").sortable({
    update: function(){
        str = "";
        $(this).children().each(function(index) { str += $(this).attr('id') + ","; });
        str = str.replace(/,\s*$/, "");
        var info = { action: "priority_script",str: str };
        $.ajax({
            data: info,
            type: 'POST',
            url: 'ajaxdata.php',
            success: function(result){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
            }
        })
    }
}); 
function del(i){
    msg = "Do you really want to delete this third party script";
    del_alertbox(msg, i,"del_Footer");
}
function del_Footer(id){
    var id = id;
    var info = {id: id, action: "del_Footer_script" };
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data: info,
        success: function(response){
            if (response == 1) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Third party script deleted successfully').delay(1000).fadeOut();
                $("#footerscriptlist-col").load(location.href + " #Footer_script_table"); setTimeout(function(){ loaddatatable();}, 500);
                $("#del_popup").modal('hide');
            } else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please try after some time').delay(1000).fadeOut();
            }
        }
    });
} 
$("#Footer_scriptsubmit").click(function(){
    var scriptname = $("#scriptname").val();
    var scriptversion = $("#scriptversion").val();
    var script_text = $("#script_text").val();
    if (scriptname == ""){ 
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter script name").delay(3000).fadeOut();
    }
    else if(scriptversion == ""){
     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter script version").delay(3000).fadeOut();
    }
    else if(script_text == ""){
     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter script ").delay(3000).fadeOut();
    }
    else {
    var info = {
        script_text: script_text,
        scriptname: scriptname,
        scriptversion: scriptversion,
        action:"add_Footer_script",
    };
    $.ajax({
        type : "POST",
        url : "ajaxdata.php", 
        data : info,
        success : function(response){
            if (response == 1) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Third party script  added successfully").delay(3000).fadeOut();
                $("#scriptname , #scriptversion , #script_text").val(""); 
                location.reload();
            }  else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
    }
})
function active(v1,v2){
    var uid = v1;
    var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){ status = 1;  msg = "activated"; } else{ status = 0; msg = "deactivated"; }
    var info = {uid:uid,status:status,action:"footer_Script_statuschnage"}
    $.ajax({
        type : "POST",
        url : "ajaxdata.php",
        data : info,
        success : function(response){
            $("#footerscriptlist-col").load(location.href + " #Footer_script_table"); setTimeout(function(){ loaddatatable();}, 500);
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Third party script  "+msg+" successfully").delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}
</script>   
</body>
</html>
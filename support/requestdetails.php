<?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> Support</title>
    <?php include 'commoncss.php';?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('menu.php');
    $req=base64_decode($_REQUEST['req']);
    $ticketdetails=selectQuery(CONTACT,"*","contact_request_id=".$req);
    $comments=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." and comment_type='Internal' order by comment_id ASC");
    $externalcomments=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." and comment_type='External' order by comment_id ASC");
    $lastresponse=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." order by comment_id DESC LIMIT 1");
    $getdeptname=selectQuery(SUPPORTDEPT,"*","dept_id=".$ticketdetails[0]['dept']);
    $assignedstaff=selectQuery(SUPPORTSTAFF,"*","emp_id=".$ticketdetails[0]['assign_to']);
    if($ticketdetails[0]['entry_by']!="Client"){
        $entry_by=selectQuery(SUPPORTSTAFF,"*","emp_id=".$ticketdetails[0]['entry_by']);
        $creator_name=$entry_by[0]['emp_name'];
    } else{
        $creator_name="Client";
    }
    $supportstaffgroup = selectQuery(SUPPORTSTAFFGROUP,"*");
    if($allowedimgtypes!=""&&$allowedapptypes!=""){
        $allowed= $allowedimgtypes.",".$allowedapptypes;
    }
    else if($allowedimgtypes!=""&&$allowedapptypes==""){
        $allowed= $allowedimgtypes;
    }
    else if($allowedimgtypes==""&&$allowedapptypes!=""){
        $allowed= $allowedapptypes;
    }
    else if($allowedimgtypes==""&&$allowedapptypes==""){
        $allowed= "";
    }
    $transfer=$loginstaff[0]['Transfer'];
    ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <?php if($ticketdetails[0]['isOverdue']==1){ ?>
            <div class="overduebox alert alert-danger">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Ticket Marked As Overdue
            </div>
            <? } ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div>
                        <h5 class="card-head-title">
                            <?php $getacctype = selectQuery(SUPPORTSTAFF,"*","emp_id='".$ticketdetails[0]['entry_by']."' ");
                            if($ticketdetails[0]['request_type']=="External"){
                                $fontawesome ="<i class='fa fa-copyright' aria-hidden='true'></i>";
                            } else{
                                $fontawesome='<i class="fa fa-info-circle" aria-hidden="true"></i>';
                            } ?> Case : #<?php echo $fontawesome." ".$req; ?>
                        </h5>
                    </div>
                    <div class="btn-actions-pane-right">
                        <?php if($loginstaff[0]['Delete1']=="1") {?>
                        <button type="button" class="btn btn-outline-light text-dark btn-sm" onclick="dellead('<?php echo $req; ?>')">Delete Case</button>
                        <? } ?>
                        <?php if($loginstaff[0]['Edit']=="1") {?>
                        <a href="lead_update.php?lead=<?php echo base64_encode($req); ?>" class="btn btn-outline-light text-dark btn-sm">Edit</a>
                        <?php }?>
                        <button type="button" class="btn btn-outline-light text-dark btn-sm" onclick="goBack()">Back</button>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <table class="table ticketStatus">
                                <!--<tr>
                                    <td class="detail borderTop0"><b>Client Name</b></td>
                                    <td class="detail borderTop0"><?php if($ticketdetails[0]['client_name']){echo $ticketdetails[0]['client_name'];}else{ echo "-"; } ?></td>
                                </tr>-->
                                <tr>
                                    <td class="pl-0 pt-0 border-top-0" style="width:30%;"><b>Creator Name</b></td>
                                    <td class="pt-0 border-top-0"><?php echo $creator_name; ?></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 py-2"><b>Status</b></td>
                                    <td class="py-2">
                                        <?php if($ticketdetails[0]['isOpen']=="1") {
                                            echo "Open";
                                        } else if($ticketdetails[0]['isAnswered']=="1"){
                                            echo "Answered";
                                        } else if($ticketdetails[0]['isClosed']=="1"){
                                            echo "Closed";
                                        } else if($ticketdetails[0]['isConfirmed']=="1"){
                                            echo "Confirm";
                                        } else if($ticketdetails[0]['isTerminated']=="1"){
                                            echo "Terminated";
                                        } else if($ticketdetails[0]['isReopen']=="1") {
                                            echo "Reopen";
                                        } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-0 py-2"><b>Created Date</b></td>
                                    <td class="py-2"><?php echo date('d/m/Y H:i:s', strtotime($ticketdetails[0]['date'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 py-2"><b>Last Response</b></td>
                                    <td class="py-2"><?php if($lastresponse[0]['comment_date']){echo $lastresponse[0]['comment_date'];}else{ echo "-"; } ?></td>
                                </tr>
                                <?php if($ticketdetails[0]['isClosed']=="1") { ?>
                                <tr>
                                    <td class="pl-0 py-2"><b>Closed On</b></td>
                                    <td class="py-2"><?php echo date('d/m/Y H:i:s', strtotime($ticketdetails[0]['closedDate'])); ?></td>
                                </tr>
                                <? } ?>
                                <?php if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff") { ?>
                                <tr>
                                    <td class="pl-0 py-2"><b>Assigned Staff</b></td>
                                    <td class="py-2"><?php echo $assignedstaff[0]['emp_name']; ?></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 py-2"><b>Department</b></td>
                                    <td class="py-2"><?php echo $getdeptname[0]['dept_name']; ?></td>
                                </tr>
                                <?}?>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <table class="table ticketStatus">
                                <tr>
                                    <td class="pl-0 pt-0 border-top-0 py-2" style="width:30%;"><b>Full Name</b></td>
                                    <td class="pt-0 border-top-0 py-2"><?php if($ticketdetails[0]['Name']!=""){ echo $ticketdetails[0]['Name'];}else{ echo "Not Defined"; }  ?></td>
                                </tr>
                                <tr>
                                   <td class="pl-0 py-2"><b>Email Id</b></td>
                                   <td class="py-2"><?php if($ticketdetails[0]['Email']!=""){ echo $ticketdetails[0]['Email'];}else{ echo "Not Defined"; } ?></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 py-2"><b>Mobile Number</b></td>
                                    <td class="py-2"><?php if($ticketdetails[0]['Telephone']!=""){ echo $ticketdetails[0]['Telephone'];}else{ echo "Not Defined"; } ?></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 py-2"><b>Comment</b></td>
                                    <td class="py-2"><?php if($ticketdetails[0]['Comment']!=""){ echo $ticketdetails[0]['Comment'];}else{ echo "Not Defined"; } ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff") { ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-cursor-pointer" onclick="showthreads('internalthreads','externalthreads')">
                    <div><h5 class="card-head-title">Internal Communication (<?php echo count($comments); ?>)</h5></div>
                </div>
                <div class="card-body internalthreads pb-3">
                    <!--<div class="row">
                        <button class="btn btn-link" onclick="showthreads('internalthreads','externalthreads')"><b><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Internal Communication (<?php echo count($comments); ?>)</b> </button>
                    </div>-->
                    <div>
                        <?php for($i=0;$i<count($comments);$i++){
                        $attachment=selectQuery(SUPPORTIMG,"*","response_id=".$comments[$i]['comment_id']);
                        if($comments[$i]['comment_by']!="client"){
                            $commentby=selectQuery(SUPPORTSTAFF,"*","emp_id=".$comments[$i]['comment_by']);
                            $commenter=$commentby[0]['emp_name'];
                        } else{
                            $commenter= $ticketdetails[0]['Name'];
                        } ?>
                        <div class="list-view mb-2 card <?=($comments[$i]['comment_by']!='client'?'border-success':'border-info'); ?>">
                            <div class="py-2 px-3 text-white <?=($comments[$i]['comment_by']!='client'?'bg-success':'bg-info'); ?>">
                                <?php echo $comments[$i]['comment_date']." - ".$commenter. " - ".$comments[$i]['extra']; ?>
                            </div>
                            <?php if(count($attachment)) { ?>
                            <div class="attachbox py-2 px-3 bg-light text-dark">
                                <?php for($a=0;$a<count($attachment);$a++) { ?>
                                <span>
                                    <a href="<?php echo SITEURL; ?>/img/support_tkt_comments/<?php echo $attachment[$a]['img_name']; ?>" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> Attachment-<?php echo $a+1; ?></a>
                                </span>
                                <? } ?>
                            </div>
                            <? } ?>
                            <div class="commentbox pt-2 pb-0 px-3">
                                <?php echo $comments[$i]['comment']; ?>
                            </div>
                        </div>
                        <? } ?>
                    </div>
                </div>
            </div>
            <?php }?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-cursor-pointer" onclick="showthreads('externalthreads','internalthreads')">
                    <div><h5 class="card-head-title">Client Communication (<?php echo count($externalcomments); ?>)</h5></div>
                </div>
                <div class="card-body externalthreads pb-3">
                    <!--<div class="row">
                        <button class="btn btn-link" onclick="showthreads('internalthreads','externalthreads')"><b><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Internal Communication (<?php echo count($comments); ?>)</b> </button>
                    </div>-->
                    <div>
                        <?php for($i=0;$i<count($externalcomments);$i++) {
                        $attachment=selectQuery(SUPPORTIMG,"*","response_id=".$externalcomments[$i]['comment_id']);
                        if($externalcomments[$i]['comment_by']!="client") {
                        $commentby=selectQuery(SUPPORTSTAFF,"*","emp_id=".$externalcomments[$i]['comment_by']);
                        $commenter=$commentby[0]['emp_name'];
                        } else{
                            $commenter= $ticketdetails[0]['Name'];
                        } ?>
                        <div class="list-view mb-2 card <?=($externalcomments[$i]['comment_by']!='client'?'border-success':'border-info'); ?>">
                            <div class="py-2 px-3 text-white <?=($externalcomments[$i]['comment_by']!='client'?'bg-success':'bg-info'); ?>">
                                <?php echo $externalcomments[$i]['comment_date']." - ".$commenter. " - ".$externalcomments[$i]['extra']; ?>
                            </div>
                            <?php if(count($attachment)) { ?>
                            <div class="attachbox py-2 px-3 bg-light text-dark">
                                <?php for($a=0;$a<count($attachment);$a++) { ?>
                                <span><a href="<?php echo SITEURL; ?>/img/support_tkt_comments/<?php echo $attachment[$a]['img_name']; ?>" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> Attachment-<?php echo $a+1; ?></a>
                                </span>
                                <? } ?>
                            </div>
                            <? } ?>
                            <div class="commentbox pt-2 pb-0 px-3">
                                <?php echo $externalcomments[$i]['comment']; ?>
                            </div>
                        </div>
                    </div>
                    <? } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="card mb-0">
                        <div class="card-header sec-card-head justify-content-between align-items-center" onclick="showthreads('internalthreads','externalthreads')">
                            <div><h5 class="card-head-title">Post Your Reply</h5></div>
                        </div>                   
                        <div class="card-body">
                            <div class="errormsg"></div>
                            <div>
                                <form  name="reply" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <?php if($_SESSION['acc_type']=="Client") { ?>
                                        <div class="hideoption cc-display-none">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="postoption" class="postoption custom-control-input" value="Internal" id="inernal-reply"/>
                                                <label class="custom-control-label" for="inernal-reply">Internal Reply</label>                                                
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="postoption" class="postoption" value="Post Reply" checked id="postclient"/>
                                                <label class="custom-control-label" for="postclient">Post Reply To Client</label>
                                            </div>
                                        </div>
                                        <?} else { ?>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="postoption" class="postoption custom-control-input" id="intrreply" value="Internal" checked/> 
                                            <label class="custom-control-label" for="intrreply">Internal Reply</label> 
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="postoption" class="postoption custom-control-input" id="postoclient" value="Post Reply" />
                                            <label class="custom-control-label" for="postoclient">Post Reply To Client</label>
                                        </div>
                                        <?php } ?>
                                        <div class="custom-control custom-radio custom-control-inline <? if($transfer=="0"){ ?> cc-display-none <? } ?>">
                                            <input id="transticket" type="radio" name="postoption" class="postoption custom-control-input" value="Transfer Ticket" /> 
                                            <label class="custom-control-label" for="transticket">Transfer Ticket</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline <? if($transfer=="0"){ ?> cc-display-none <? } ?>">
                                            <input id="reassigntask" type="radio" name="postoption" class="postoption custom-control-input" value="Reassign Ticket"/> 
                                            <label class="custom-control-label" for="reassigntask">Reassign Ticket</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-inline transfertodept">
                                        <?php $dept=selectQuery(SUPPORTDEPT,"*","isDel='0' and isActive='1' and dept_id<>".$ticketdetails[0]['dept']); ?>
                                        <label class="mr-2">Transfer To</label>
                                        <select name="chngdept" class="chngdept form-control form-control-sm">
                                            <option value="">Transfer to department</option>
                                            <?php for($i=0;$i<count($dept);$i++) { ?>
                                            <option value="<?php echo $dept[$i]['dept_id']; ?>"><?php echo $dept[$i]['dept_name']; ?> </option>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="form-group reassigntkt form-inline">
                                        <?php $staff=selectQuery(SUPPORTSTAFF,"*","acc_type='Admin' || acc_type='Staff' and isActive='1' order by emp_name ASC"); ?>
                                        <label class="mr-2">Assign To</label>
                                        <select name="assignto" class="assignto form-control form-control-sm">
                                            <option value="">Reassign</option>
                                            <?php for($i=0;$i<count($staff);$i++) {
                                            if($assignedstaff[0]['emp_id']!=$staff[$i]['emp_id']) { ?>
                                            <option value="<?php echo $staff[$i]['emp_id']; ?>"><?php echo $staff[$i]['emp_name']; ?></option>
                                            <? } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="staff" id="staff" value="<?php echo $_SESSION['staff']; ?>"/>
                                        <input type="hidden" name="requestid"  id="requestid" value="<?php echo $req; ?>"/>
                                        <input type="hidden" name="allowedimgformat" id="allowedimgformat" value="<?php echo $allowedimgtypes; ?>"/>
                                        <input type="hidden" name="allowedappformat" id="allowedappformat" value="<?php echo $allowedapptypes; ?>"/>
                                        <input type="hidden" name="allowedmaxsize" id="allowedmaxsize" value="<?php echo $attachmentmax; ?>"/>
                                        <textarea class="summernote" id="editor1" name="text_details"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="mb-1">Attach File (Allowed Type: <?php echo $allowed; ?> || Max File Size : <?php echo ($attachmentmax/1024)/1024; ?> MB)</div>
                                        <input type="file" name="attach" id="attach" onchange="validateimg('attach')"/>
                                    </div>
                                    <!--<?php if($ticketdetails[0]['isClosed']=="1") { ?>-->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                        <input id="reopen" type="checkbox" name="reopentkt" class="reopentkt custom-control-input" <? if($ticketdetails[0]['isClosed']=="0"){?> style="display:none;" <?} ?><? if($loginstaff[0]['Close']=="0"){?> style="display:none"; <? } ?>/>                                   
                                        <label class="custom-control-label" for="reopen"><? if($loginstaff[0]['Close']=="1"){?> Re-open On Reply <?php } ?></label>
                                        </div>
                                    </div>
                                    <!-- <? } else { ?>-->
                                    <div class="form-group <?=($ticketdetails[0]['isClosed']=="1"||$loginstaff[0]['Close']=="0"?'cc-display-none':''); ?>">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input id="clonrep" type="checkbox" name="closetkt" class="closetkt custom-control-input"/>
                                            <label class="custom-control-label" for="clonrep"><? if($loginstaff[0]['Close']=="1"){?> Close On Reply <?php } ?></label>
                                        </div>
                                    </div>
                                    <!--<? } ?>-->
                                    <div>
                                        <button type="button"  class="btn btn-primary postreply" name="postreply" id="btnloader">Post Reply</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <?php include('footer.php'); ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script>
$(function() {
    $('.summernote').summernote({height: 200});
});  
$('.summernote').val("");
$('.summernote').val("");
$('#attach').val("");
$(document).ready(function(){
    $(".postreply").click(function(){
        var dept=$(".transfertodept option:selected").val();
        var assignto = $(".assignto option:selected").val();
        var chngdept = $(".chngdept option:selected").val();
        var sel = $(".postoption:checked").val();
        var staff = $("#staff").val();
        var requestid = $("#requestid").val();
        var textareaValue = $('.summernote').summernote('code');
        var cleanText = $(".summernote").summernote('code').replace(/<\/?[^>]+(>|$)/g, "");
        var isclose = $(".closetkt:checked").val();
        var isreopen = $(".reopentkt:checked").val();
        if(isclose == "on") {
            var closed=1;
        } else {
            var closed = 0;
        } if(isreopen == "on") {
            var reopen = 1;
        } else{
            var reopen = 0;
        }
        if($('#attach').val()!=""){
            var attachment=$('#attach').prop('files')[0];
            var attachmentname = ($('#attach'))[0].files[0].name;
        } else{
            var attachment="";
        }
        var form_data = new FormData();
        form_data.append('staff', staff);
        form_data.append('requestid', requestid);
        form_data.append('comment', textareaValue);
        form_data.append('closed', closed);
        form_data.append('reopen', reopen);
        form_data.append('dept', dept);
        form_data.append('posttype', sel);
        form_data.append('assignto', assignto);
        form_data.append('chngdept', chngdept);
        form_data.append('attachment',attachment);
        if(cleanText==""){
            $(".errormsg").fadeIn().html("Please enter comment to submit your post").addClass("alert alert-             danger").delay(3000).fadeOut();
        } else if(sel=="Transfer Ticket" && chngdept=="") {
        $(".errormsg").fadeIn().html("Please Select Department To Transfer Lead").addClass("alert alert-    danger").delay(3000).fadeOut();
        } else if(sel=="Reassign Ticket" && assignto==""){
            $(".errormsg").fadeIn().html("Please Select Staff To Reassign Lead").addClass("alert alert-danger").delay(3000).fadeOut();
        } else{
            $("#btnloader").html("Loading...");
            $(".postreply").attr("disabled",true);
            $.ajax({
                type:"POST",
                url:"savecomments.php",
                data:form_data,
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    $(".postreply img").remove();
                    $(".postreply").attr("disabled",false);
                    if(response){
                        if(response==1){
                            location.reload();
                        } else {
                            $(".loader").html('Error while insert.. try again later');
                        }
                    }
                }
            });
        }
    });
});

function validateimg(id){
    var allowedimgformat=$("#allowedimgformat").val();
    var allowedappformat=$("#allowedappformat").val();
    var allowedmaxsize=$("#allowedmaxsize").val();
    maxsize=(allowedmaxsize/1024)/1024;
    if($('#'+id).val()!=""){
        $(".imgloader").fadeIn().removeClass("alert alert-success").html(" Please Wait While We Are Validating Your Attachment").addClass("alert alert-danger");
        $(".postreply").attr("disabled",true);
        var attachment = $('#'+id).prop('files')[0];
        var form_data1 = new FormData();
        form_data1.append('attachment', attachment);
        form_data1.append('allowformat', allowedappformat);
        form_data1.append('maxsize', allowedmaxsize);
        $.ajax({
            type:"POST",
            url:"validateattachment.php",
            data:form_data1,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if(response=="1"){
                    $(".imgloader").fadeIn().removeClass("alert alert-danger").html("<i class='fa fa-check'></i> Valid Attachment").addClass("alert alert-success").delay(5000).fadeOut();
                    $(".postreply").attr("disabled",false);
                }
                else{
                 $(".imgloader").fadeIn().removeClass("alert alert-success").html(response).addClass("alert alert-danger").delay(3000).fadeOut();
                }
            }
        });
    }
}
function showthreads(class1,class2){$("."+class1).slideToggle();}
function dellead(leadid){
    if(confirm("Are You Sure To Delete This Lead")){
        info = {leadid:leadid,action:"del_lead"}
        $.ajax({
            type:"POST",
            url:"leadentry.php",
            data:info,
            success:function(response){
                if(response=="1") {
                    window.location="<?php echo SUPPORTURL; ?>home.php";
                }
                else{
                    alert("Error In Delete");
                }
            }
        });
    }
}

$(".transfertodept").hide();
$(".reassigntkt").hide();
$(".postoption").on("change",function(){
    $(".transfertodept").hide();
    $(".reassigntkt").hide();
    var sel= $(".postoption:checked").val();
    if(sel=="Transfer Ticket"){
        $(".transfertodept").show();
        $(".reassigntkt").val("");
    } else if(sel=="Reassign Ticket"){
        $(".reassigntkt").show();
        $(".transfertodept").val("");
    } else{
        $(".transfertodept").hide();
        $(".reassigntkt").hide();
        $(".transfertodept").val("");
        $(".reassigntkt").val("");
    }
});
</script>
</body>
</html>
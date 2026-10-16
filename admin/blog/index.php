<?php include ("../../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Add Blog</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css"/>
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php'); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Add New Blog</h2></div><div class="btn-actions-pane-right"><button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div></div>
                <div class="card-body">
                    <div class="msg"></div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-md-3 col-lg-2 col-form-label cc-mandatorystar">Category</label>
                        <div class="col-sm-9 col-md-9 col-lg-10" id="catbox">
                            <select class="form-control" name="category" id="category" onchange="catchng()">
                                <option value="">Select Category</option>
                                <option value="addnewcat">Add New Category</option>
                                <?php $getcat=selectQuery(BLOGCAT,"cat_id,category_name","isActive='1' order by category_name ASC");
                                for($i=0;$i<count($getcat);$i++){
                                ?> <option value="<?php echo $getcat[$i]['cat_id']; ?>"><?php echo $getcat[$i]['category_name']; ?> </option> <? } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-md-3 col-lg-2 col-form-label cc-mandatorystar">Title</label>
                        <div class="col-sm-9 col-md-9 col-lg-10"><input type="text" class="form-control" id="title" name="title" placeholder="Blog Title Max 600 Character Limit"></div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-md-3 col-lg-2 col-form-label cc-mandatorystar">Post</label>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <div class="row mr-0">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 pr-0">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="pd1" placeholder="Posted date" class="form-control border-right-0" id="datepicker1">
                                            <div class="input-group-append"><span class="input-group-text bg-white"><span class="fa fa-calendar"></span></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 pr-0">
                                    <div class="form-group"><input type="text" class="form-control" id="posted_by" placeholder="Posted By" name="posted_by"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-md-3 col-lg-2 col-form-label cc-mandatorystar">Details</label>
                        <div class="col-sm-9 col-md-9 col-lg-10"><textarea class="summernote" id="Summary" name="Summary"></textarea></div>
                    </div>                    
                </div>
                <div class="card-footer py-2 text-right"><button type="button" onClick="addblock()" name="Add Block" class="btn btn-primary addit">Add Blog</button></div>
            </div>
        </div>
        <?php include '../footer.php'; ?>
    </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title" id="myModalLabel">Add Category</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button></div>
            <div class="modal-body">
                <div class="maincat"></div>
                <div class="form-group"><input type="text" name="newcat" id="newcat" placeholder="New Category Max 200 Character Limit" class="form-control text-capitalize" maxlength="50"/></div>
                <div><input type="button" id="addnewscat" value="Add New Category" class="btn btn-primary"/></div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$('.summernote').summernote({
    toolbar: [
    ['style', ['style']],
    ['font', ['bold', 'underline', 'clear']],
    ['fontname', ['fontname']],
    ['fontSizes', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['table', ['table']],
    ['insert', ['link', 'picture', 'video']],
    ['view', ['fullscreen', 'codeview', 'help']],
    ],
    fontSizes: ['8', '10', '12', '14', '16', '18', '20', '22', '24', '26' , '28', '30', '32'],
    height: 200,
    callbacks: {
        onPaste: function(e){
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            //Firefox fix
            setTimeout(function(){
                document.execCommand('insertText', false, bufferText);
            }, 10);
        }
    }
});
$("#datepicker1").datetimepicker({format: 'DD/MM/YYYY'});
function addblock(){
    category = $("#category").val();
    title  = $("#title").val();
    Summaryhtml = document.getElementById("Summary").value;
    posted_date = $("#datepicker1").val();
    posted_by = $("#posted_by").val();
    var form_data = new FormData();
    form_data.append("category", category);
    form_data.append("title", title);
    form_data.append("Summaryhtml", Summaryhtml);
    form_data.append("posted_date", posted_date);
    form_data.append("posted_by", posted_by);
    form_data.append("action", "addblog");
    if((Summaryhtml == "") || (title == "") || (posted_date == "") || (posted_by == "") || (category == "") || (category == "addnewcat")){
        if((category == "")|| (category == "addnewcat")){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select category").delay(1000).fadeOut();
        } else if (title == ""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter title").delay(1000).fadeOut();
        } else if (Summaryhtml == ""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter summary").delay(1000).fadeOut();
        } else if(posted_date == ""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter posted date").delay(1000).fadeOut();
        } else if(posted_by == ""){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter posted-by name").delay(1000).fadeOut();
        }
    } else{
        $(".addit").html("Adding...").attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){
                $(".addit").html("Add Blog").attr('disabled',false);
                $(".addit img").remove();
                if(response.trim() == "0"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please try after some time').delay(1000).fadeOut();
                } else if(response.trim() == "1"){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Blog added successfully').delay(1000).fadeOut();
                    setTimeout(function(){ location.href="viewlist.php"}, 1000);
                } else if(response.trim() == "2"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Blog with same title already exist').delay(1000).fadeOut();
                }
            }
        })
    }
}
function catchng(){
    var selectedval = $("#category option:selected").val();
    if(selectedval=="addnewcat"){ $("#myModal2").modal('show'); $('#category').prop('selectedIndex',0); }
    else{ $("#myModal2").modal('hide');}
}
$("#addnewscat").click(function(){
    var newcat = $("#newcat").val();
    if(newcat == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Details are not available").delay(1000).fadeOut();
    } else{
        $("#addnewscat").prop("disabled",true);
        var info = { action:"insertscat",newcat:newcat};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                $("#addnewscat").prop("disabled",false);
                if(response==0){ $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                } else if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Category already exist").delay(1000).fadeOut();
                } else if(response==2){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Category added successfully").delay(1000).fadeOut();
                    $("#myModal2").modal('hide'); location.reload();
                }
            }
        });
    }
});
$(".nav-list-1").find(".dropdownMenu").slideToggle();
</script>
</body>
</html>
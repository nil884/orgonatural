<? include("../../includes/configuration.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Manage Category - Product Specifications</title>
<? include "../commoncss.php"; ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Image Upload Configuration</h5></div>
                </div>

            </div>
            <div id="prodcut_attribute">

                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center">
                        <div><h5 class="card-head-title"></h5></div>
                    </div>
                    <div class="card-body">

                    </div>

            </div>

            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>

<script>


function Edit_attr(attr_id,title,Category,name){
    $("#edit_attr_modal").modal("show");
    $("#modal_title").html("Edit "+title);
    $("#attr_id").val(attr_id);
    $("#attr_cat").val(Category);
    $("#edit_attr_name").val(name);
}

function edit_attr(){
    edit_attr_name = $("#edit_attr_name").val();
    attr_id = $("#attr_id").val();
    cattype = $('#cattype').val();
    if($.trim(edit_attr_name) == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Enter"+title).delay(3000).fadeOut();
    }
    else {
        info = {edit_attr_name:edit_attr_name,cattype:cattype,attr_id:attr_id,action:'edit_attr'}
        $("#edit_attr_btn").attr("disabled",true);
  $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response=response.trim();
                $("#edit_attr_btn").attr("disabled",false);
                if(response== "Exist"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Already Exist").delay(3000).fadeOut();
                }
                else if(response=="Not"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try Again Later").delay(3000).fadeOut();
                }

                else if(response=="1"){
                  $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Updated Successfully").delay(3000).fadeOut();
                   $("#edit_attr_modal").modal("hide");
                  $("#attr_cat").val("");


                 $("#product_attr_cat").load(" #product_attr_cat");
                 $("#prodcut_attribute").load(" #prodcut_attribute");
                }
                }
            })
    }
}

function add_attr(){
    attr_name  = $("#attr_name").val();
    parentid_id = $("#parentid_id").val();

    if(parentid_id == "" ||  parentid_id == null){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Select Attribute Category").delay(3000).fadeOut();
    }
    else if($.trim(attr_name) == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Enter Attribute Name").delay(3000).fadeOut();
    }
   /* else if (!/^[a-zA-Z ]*$/g.test(attr_name)) {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Enter Valid Attribute Name").delay(3000).fadeOut();
            }*/
    else {
        info = {attr_name:attr_name,cattype:'Attribute',parentid_id:parentid_id,action:'add_attr'}
        $("#add_attr_bt").attr("disabled",true);
  $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response=response.trim();
                $("#add_attr_bt").attr("disabled",false);
                if(response== "Exist"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Attribute Already Exist").delay(3000).fadeOut();
                }
                else if(response=="Not"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try Again Later").delay(3000).fadeOut();
                }

                else {
                  $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Attribute Added Successfully").delay(3000).fadeOut();
                  $("#attr_name").val("");

                  $( "#prodcut_attribute").load(window.location.href + " #prodcut_attribute" );

                }


                }
            })
    }
}



</script>
</body>
</html>
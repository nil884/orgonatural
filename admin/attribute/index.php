<? include("../../includes/configuration.php");?>
<?php $get_atrr = selectQuery(PRODATTR,"attr_id,attr_name,attr_for_template,parent_id","parent_id  <> '0' and  isActive='1'  order by attr_name ");
$dont_delete_array = array();
for($j=0;$j<count($get_atrr);$j++){
$gettables = selectQuery(PRODCAT,"template","template <> '' ");
for($k=0;$k<count($gettables);$k++){
$tablename = $gettables[$k]['template'];
$results = showQuery($tablename,"field='".$get_atrr[$j]['attr_for_template']."'");
if(count($results)){
    if( !in_array($get_atrr[$j]['attr_id'], $dont_delete_array)){ array_push($dont_delete_array,$get_atrr[$j]['attr_id']); } 
    if( !in_array($get_atrr[$j]['parent_id'], $dont_delete_array)){ array_push($dont_delete_array,$get_atrr[$j]['parent_id']); } 
} } } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Product Specifications</title>
    <? include "../commoncss.php"; ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert alert-info mb-0">
                    <h6><b>Note :</b></h6>
                    <ul class="mb-0 pl-3"><li>Delete attribute is only possible when the attribute is not connected with any active product</li></ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Product Attribute Category</h2></div></div>
                <div class="card-body">
                    <form class="form-inline">
                        <label class="mb-2 mb-sm-0">New Product Attribute Category</label>
                        <input type="text" class="form-control mx-sm-3 mb-2 mb-sm-0" placeholder="Attribute Category" id="attr_cat">
                        <button type="button" class="btn btn-primary" id="add_attr_cat" onclick="add_attr_category()">Add</button>
                    </form> 
                </div>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">New Attribute To The Category</h2></div></div>   
                <div class="card-body pb-0 pb-xl-3">
                    <form class="form-inline" id="product_attr_cat">
                        <label class="mb-3 mb-xl-0 cc-mandatary-field">Product Attribute Category</label>
                        <select class="form-control mx-sm-3 mb-3 mb-xl-0" id="parentid_id">
                            <option value="">-Select Category-</option>
                            <?php $get_atrr_cat =selectQuery(PRODATTR,"attr_id,attr_name"," parent_id='0' and isActive='1' order by attr_name ");
                            for($i=0;$i<count($get_atrr_cat);$i++){ ?>
                            <option value="<?php echo $get_atrr_cat[$i]['attr_id'] ?>"><?php echo $get_atrr_cat[$i]['attr_name'] ?></option>
                            <?php } ?>
                        </select>
                        <label class="mb-3 mb-xl-0 cc-mandatary-field">New Product Attribute</label>
                        <input type="text" class="form-control mx-sm-3 mb-3 mb-xl-0" placeholder="" id="attr_name">
                        <button type="button" class="btn btn-primary mb-3 mb-xl-0" id="add_attr_bt" onclick="add_attr()">Add</button>
                    </form> 
                </div>
            </div>
            <div id="prodcut_attribute">
                <?php if(count($get_atrr_cat)){ ?>
                <div class="attribute_manage">
                    <?php for($i=0;$i<count($get_atrr_cat);$i++){ ?>
                    <div class="prod-attr-col card">
                        <?php $get_atrr_cat_child =selectQuery(PRODATTR,"attr_id,attr_name,attr_for_template"," parent_id='".$get_atrr_cat[$i]['attr_id']."' and isActive='1' order by attr_name ");?>
                        <div class="sec-card-head justify-content-between align-items-center card-header py-2">
                            <div><h5 class="card-head-title"><?php echo $get_atrr_cat[$i]['attr_name'] ?></h5></div>
                            <div class="btn-actions-pane-right">
                                <button type="button" class="btn btn-primary btn-sm" onclick="Edit_attr('<?php echo $get_atrr_cat[$i]['attr_id']; ?>','Attribute Category','Category','<? echo addslashes($get_atrr_cat[$i]['attr_name']) ?>')">Edit</button>
                                <?php if(!in_array($get_atrr_cat[$i]['attr_id'], $dont_delete_array)){ ?> <button type="button" class="btn btn-danger btn-sm" onclick="del_attr('<?php echo $get_atrr_cat[$i]['attr_id'] ?>','Category')"><i class="fa fa-trash"></i></button><?php } ?>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                        <?php if(count($get_atrr_cat_child)){ ?>
                        <ul class="list-unstyled mb-1">
                            <?php for($j=0;$j<count($get_atrr_cat_child);$j++){ ?>
                            <li class="border">
                                <i class="fa fa-check-circle text-success"></i>                                    
                                <span class="attr-nm"><?php echo $get_atrr_cat_child[$j]['attr_name'];?></span>
                                <span onclick="Edit_attr('<?php echo $get_atrr_cat_child[$j]['attr_id'];  ?>','Attribute','Attribute','<? echo addslashes($get_atrr_cat_child[$j]['attr_name'])?>' )" class="pro-attr-badge-action"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                <?php if (!in_array($get_atrr_cat_child[$j]['attr_id'], $dont_delete_array)) { ?> 
                                <span class="removeopt pro-attr-badge-action" onclick="del_attr('<?php echo $get_atrr_cat_child[$j]['attr_id'] ?>','Attribute')"><i class="fa fa-trash text-danger"></i></span>
                                <?php } ?>
                            </li>
                            <?php } ?>
                        </ul>
                        <? } ?>
                    </div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<div class="modal" id="edit_attr_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body cc-padding-bottom-0">
                <div class="attr_alert_msgs"></div>
                <div class="form-group">
                    <input type="hidden" id="attr_id">
                    <input type="text" class="form-control" id="edit_attr_name" maxlength="50" placeholder="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="edit_attr()" id="edit_attr_btn">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
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
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter"+title).delay(3000).fadeOut();
    } else {
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
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Already exist").delay(3000).fadeOut();
                } else if(response=="Not"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                } else if(response=="1"){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Updated successfully").delay(3000).fadeOut();
                    $("#edit_attr_modal").modal("hide");
                    $("#attr_cat").val("");
                    $("#product_attr_cat").load(" #product_attr_cat");
                    $("#prodcut_attribute").load(" #prodcut_attribute");
                }
            }
        })
    }
}
function add_attr_category(){
    attr_name  = $("#attr_cat").val();
    if($.trim(attr_name) == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter categoy name").delay(3000).fadeOut();
    } else {
        info = {attr_name:attr_name,cattype:'Category',action:'add_attr'}
        $("#add_attr_cat").attr("disabled",true);
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response=response.trim();
                $("#add_attr_cat").attr("disabled",false);
                if(response== "Exist"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Category  already exist").delay(3000).fadeOut();
                } else if(response=="Not"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                } else{
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(" Category  added successfully").delay(3000).fadeOut();
                    $("#attr_cat").val("");
                    location.reload();
                }
            }
        })
    }
}

function add_attr(){
    attr_name  = $("#attr_name").val();
    parentid_id = $("#parentid_id").val();
    if(parentid_id == "" ||  parentid_id == null){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select attribute category").delay(3000).fadeOut();
    }
    else if($.trim(attr_name) == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter attribute name").delay(3000).fadeOut();
    } else{
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
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Attribute already exist").delay(3000).fadeOut();
                } else if(response=="Not"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                } else{
                  $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Attribute added successfully").delay(3000).fadeOut();
                  $("#attr_name").val("");
                  $("#prodcut_attribute").load(window.location.href + " #prodcut_attribute");
                }
            }
        })
    }
}

function del_attr(i,type){  
    if(type == "Attribute"){
        var msg = "Do you really want to delete this attribute? Because after deletion, this Attribute will be get removed from all the product templates";
    } else if(type == "Category"){
        var msg = "Do you really want to delete this attribute category? Because after deletion, its attribute will also be get removed from the product templates too"
    }
    del_alertbox(msg, i,"del_attr_db",type);
}

function del_attr_db(id,type) {
    info  = {attr_id:id,action:"Delete_attr",type:type}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            $("#add_attr_bt").attr("disabled",false);
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Attribute deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $( "#prodcut_attribute").load(" #prodcut_attribute" );
                $("#product_attr_cat").load(" #product_attr_cat");
            } else if(response== "3"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("You can not delete this attribute as its used in category template").delay(3000).fadeOut();
            $("#del_popup").modal("hide"); 
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
</script>
</body>
</html>
<? include("../../includes/configuration.php");
    $catid = base64_decode($_REQUEST['catid']);
    $getcat = selectQuery(PRODCAT,"template, cat_name","id=".$catid);
    if($getcat[0]['template'] != ""){
        $results = showQuery($getcat[0]['template']);
        $arrcol = array(); $arrcol2 = array();
        $arrtype = array();
        for($i=0;$i<count($results);$i++){
            array_push($arrcol, $results[$i]['Field']);
            array_push($arrtype, $results[$i]['Type']);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Product Category</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap4-toggle.min.css" />
</head>
<body class="reload-pg">
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card edit_template">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title mb-2 mb-sm-0">Edit Template For Category - <?php echo $getcat[0]['cat_name']?></h2></div><div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()">Back</button></div>
                </div>
                <div class="card-body">
                    <?php if($getcat[0]['template'] != ""){?> 
                    <div class="table-responsive" id="edittempcat">
                        <table class="table table-bordered mb-0" id="edittemload">
                            <thead><tr><th>Attribute Name</th><th>Attribute Type</th><th>Attribute Size (Max Range 5000)</th>
                            <?php $check_prod = selectQuery($getcat[0]['template'],"count(id) as total_count","id<> '' ");
                            if($check_prod[0]['total_count'] == 0 ){ ?><th>Drop</th><?php } ?>
                            <th>Save</th>
                            </tr></thead>
                            <tbody>
                                <?php for($i=3;$i<sizeOf($arrcol);$i++){  
                                $t = explode("(",$arrtype[$i]);
                                array_push($arrcol2, $arrcol[$i]); ?> 
                                <tr>
                                    <td><?php echo getOriginalName($arrcol[$i]);?>(<?php echo getAttributeCat($arrcol[$i]); ?>)</td>
                                    <td><?php if($t[0]=='varchar'){echo "Alphanumeric";}else if($t[0]=='numeric'){echo "Numeric";} else if($t[0]=='text'){echo "Long Text";} else if($t[0]=='int'){echo "numeric"; }?></td>
                                    <td><?php $m = explode(")",$t[1]); ?>
                                    <div class="qtybox"><input type="number" name="quantity" class ="quantity_no" id="quantity_<?php echo $i; ?>" min="<?php echo $m[0]; ?>" max="5000" value="<?php echo $m[0]; ?>" autocomplete="off"  maxlength="4"></div> 
                                    <!--<div class="position-relative">
                                    <input type="number" class="form-control specsize" id="quantity_<?php echo $i; ?>" name="quantity" min="<?php echo $m[0]; ?>" max="5000" value="<?php echo $m[0]; ?>">
                                    <div class="invalid-tooltip"></div>
                                    </div>-->
                                    </td> 
                                    <?php if($check_prod[0]['total_count'] == "0"){ ?> <td><button type="button" class="removeopt pro-attr-badge-action btn btn-danger btn-sm" onclick="del_column('<?php echo $arrcol[$i] ?>')"><i class="fa fa-trash"></i></button></td><?php } ?>
                                    <td><button type="button" class="btn btn-primary btn-sm"  onclick="savesize('<?php echo $i; ?>','<?php echo $arrcol[$i] ?>','<?php  echo $t[0]; ?>')">Save</button></td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>  
                </div>
             </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Add New Attribute In Template</h2></div></div>
                <div class="card-body pb-0">  
                    <div class="row">
                        <?php $columnname = implode("','", $arrcol2);
                        $attr=selectQuery(PRODATTR,"attr_for_template,attr_name","isActive='1' and type='Attribute' and attr_for_template not in('".$columnname."') order by attr_name ASC");
                        if(count($attr)) { ?>  
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label>Attribute Name</label>
                            <select name="newattr" id="newattr" class="newattr form-control">
                                <option value="">Select Attribute</option>
                                <?php for($j=0;$j<count($attr);$j++){ ?>
                                <option value="<?php echo $attr[$j]['attr_for_template']; ?>"><?php echo $attr[$j]['attr_name']; ?>(<?php echo getAttributeCat( $attr[$j]['attr_for_template']);   ?>)</option>
                                <?php } ?>
                            </select> 
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label>Attribute Type</label>
                            <select name="newattrtype" id="newattrtype" class="newattrtype form-control"   onchange="getval();">
                                <option value="">Select Type</option>
                                <option value="INT">Numeric</option>
                                <option value="VARCHAR" selected>Alphanumeric</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label>Attribute Size</label> 
                            <div class="qtybox">
                                <input type="number" name="newattrsize" id="newattrsize" class="form-control" placeholder="Size" max="5000"  maxlength="4" value="100" onkeyup="numbercheck('newattrsize')">
                        </div>
                             </div>
                        <div class="card-footer py-2 col-12 text-right ">
                            <button type="button" name="addnewattr" id="addnewattr" class="btn btn-primary"  onclick="addnewattr()">Add Attribute</button>
                            <a href="<?php echo ADMINURL; ?>/attribute/" class="btn btn-secondary">Add New Attribute</a>
                        </div>
                        <?php } else {
                        echo "<div class='col-md-12 text-muted'>All Attribute Avaialble in Table</div>";    
                        }?>  
                    </div> 
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/validation.js"></script>
<script>
function getval(){
    var attrtype = $("#newattrtype").val();
    if(attrtype == "INT"){
        $("#newattrsize").val("10").attr("maxlength",2);
    } if(attrtype == "VARCHAR"){
        $("#newattrsize").val("100").attr("maxlength",3);
    }  
}

function addnewattr(){
    var fld = $("#newattr").val();
    var fldsize = $("#newattrsize").val();
    var fldtype = $("#newattrtype").val();
    var cat = '<?php echo $catid; ?>';
    var max_size = parseInt(5000); 
    var min_size = parseInt($("#newattrsize").attr("min"));
    if(fld == ""||fldtype == ""||fldsize == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Enter all Details").delay(3000).fadeOut();
    }
    else if(isNaN(fldsize)) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter Valid Attribute Size").delay(3000).fadeOut();
    } 
    else if(fldsize < min_size){  
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Attribute Size Must be greater than "+min_size).delay(3000).fadeOut();
        $("#newattrsize").next(".invalid-tooltip").stop(true, true).fadeIn().html("Please select a value " + min_size + " to " + max_size).delay(3000).fadeOut();
    } else if(fldsize > max_size ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Attribute Size Must be Less than "+max_size).delay(3000).fadeOut();
        $("#newattrsize").next(".invalid-tooltip").stop(true, true).fadeIn().html("Please select a value " + min_size + " to " + max_size).delay(3000).fadeOut();
    }
    else {
        var info={'fld':fld,'fldsize':fldsize,'fldtype':fldtype,'action':'add_attr_edit','cat':cat};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){    
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("New field added successfully").delay(3000).fadeOut();
                location.reload(); 
                } else{     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
                }
            }
        });
    }
}

function del_column(i){ msg = 'Do you really want to delete this Attribute?'; del_alertbox(msg, i,"del_attr_column_db"); }
    
function del_attr_column_db(id){
    var cat = '<?php echo $catid; ?>';
    info  = {fld:id,cat:cat,action:"Delete_attr_column"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Attribute  deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                location.reload(); 
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}

function savesize(id,coloumn_name,column_type){ 
    var column_size = parseInt($("#quantity_"+id).val()); var max_size = parseInt(5000); var min_size = parseInt($("#quantity_"+id).attr("min")); var template = '<?php echo $getcat[0]['template']; ?>'
    if(column_size < min_size){  
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Attribute size must be greater than "+min_size).delay(3000).fadeOut();
        $("#quantity_"+id).next(".invalid-tooltip").stop(true, true).fadeIn().html("Please select a value " + min_size + " to " + max_size).delay(3000).fadeOut();
    } else if(column_size > max_size ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Attribute size must be less than "+max_size).delay(3000).fadeOut();
        $("#quantity_"+id).next(".invalid-tooltip").stop(true, true).fadeIn().html("Please select a value " + min_size + " to " + max_size).delay(3000).fadeOut();
    } else { 
        info  = {template:template,coloumn_name:coloumn_name,column_size:column_size,column_type:column_type,action:"set_size"}
        $.ajax({
            type:"POST", 
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response = response.trim();
                if(response== "1"){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Attribute size updated successfully").delay(3000).fadeOut();
                } else if(response=="0"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                }
            }
        })
    }  
}
 
$('.specsize').on('keyup', function(e){
    var specmax = parseInt(this.max);
    var specmin = parseInt(this.min);
    if(parseInt(this.value) > specmax && e.keyCode !== 46 && e.keyCode !== 8){
        $(this).next(".invalid-tooltip").stop(true, true).fadeIn().html("Please select a value " + specmin + " to " + specmax).delay(3000).fadeOut();    
        /* $(this).val(specmax); */                   
    }else if(parseInt(this.value) < specmin && e.keyCode !== 46 && e.keyCode !== 8){
        $(this).next(".invalid-tooltip").stop(true, true).fadeIn().html("Please select a value " + specmin + " to " + specmax).delay(3000).fadeOut();  
    }
});

(function($){
    $.fn.spinner = function(){
        this.each(function(){
            var el = $(this);
            el.wrap('<span class="spinner"></span>');
            el.before('<span class="sub rounded-left">-</span>');
            el.after('<span class="add rounded-right">+</span>');
            el.parent().on('click', '.sub', function(){
                if (el.val() > parseInt(el.attr('min')))
                el.val( function(i, oldval){ return --oldval; });
            });
            el.parent().on('click', '.add', function(){
                if (el.val() < parseInt(el.attr('max')))
                el.val( function(i, oldval){ return ++oldval; });
            });
        });
    };
})(jQuery);
$('input[type=number]').spinner();
</script>  
</body>
</html>
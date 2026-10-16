<?php include("../../includes/configuration.php");
    if($_GET['id'] != ""){ 
    $id12 = base64_decode($_GET['id']);
    $getsubadmin = selectQuery(ADMIN,"*","u_id=".$id12);
    $allocatetmenu = explode(",",$getsubadmin[0]['allocatemenu']);
} ?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Add SubAdmin</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <form>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h5 class="card-head-title">Add Subadmin</h5></div><div class="btn-actions-pane-right"><a href="<?php echo ADMINURL;?>setting/viewsubadminlist.php" class="btn btn-secondary btn-sm">Back</a></div></div>
                    <div class="card-body pb-2">
                        <div class=" msgs1"></div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5">
                                <div class="form-group row">
                                    <label class="col-md-12 col-lg-4 col-form-label">Username</label>
                                    <div class="col-md-12 col-lg-8"><input type="text" name="uname" id="uname" class="form-control" placeholder="Enter Username" maxlength="20" onkeyup="this.value=this.value.replace(/[^a-z]/gi,'');" value="<?php echo $getsubadmin[0]['username'];?>" /></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5">
                                <div class="form-group row <?php if($_GET['id'] != ""){ echo "cc-display-none"; }?>">
                                    <label class="col-md-12 col-lg-4 col-form-label">Password</label>
                                    <div class="col-md-12 col-lg-8"><input type="password" name="pass" id="pass" class="form-control" placeholder="Enter Password" maxlength="20" value="<?php echo $getsubadmin[0]['password'];?>" /></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5">
                                <div class="form-group row">
                                    <label class="col-md-12 col-lg-4 col-form-label">Email ID</label>
                                    <div class="col-md-12 col-lg-8"><input type="text" name="uemail" id="uemail" class="form-control" placeholder="Enter Email ID" maxlength="50" onblur="mailchk('uemail')" value="<?php echo $getsubadmin[0]['u_email'] ?>"/></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5">
                                <div class="form-group row">
                                    <label class="col-md-12 col-lg-4 col-form-label">Mobile No</label>
                                    <div class="col-md-12 col-lg-8"><input type="text" name="umbl" id="umbl" class="form-control number" placeholder="Enter Mobile No" maxlength="10" onkeyup="mobnumbercheck('umbl')" value="<?php echo $getsubadmin[0]['u_mob'] ?>" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Select Menu</h5></div></div>
                    <div>
                        <?php if(in_array(2, $allocatetmenu)){ $ischecked = "checked";} 
                        $allocated_menu = array();
                        echo get_menu_tree2(0,$allocatetmenu);
                        function get_menu_tree2($parent_id,$allocatetmenu){
                            $menu = "";
                            $prod_cat = selectQuery(ADMINMENU,"*","parent_id=".$parent_id."  and isActive='1'");
                            $array1 = array();
                            $array2_checked = array();
                            if(count($prod_cat)){
                            for($i=0;$i<count($prod_cat);$i++){ ?>
                            <?php $cat_id = $prod_cat[$i]['id'];$par = $prod_cat[$i]['parent_id'];$ischeckedvalue = "";  
                            if($_GET['id'] != ""){ if(in_array($cat_id, $allocatetmenu)){ $ischeckedvalue = "checked"; array_push($array2_checked,$prod_cat[$i]['id']); } }
                            if($parent_id == 0) { $boldclass= "cc-font-weight-6 text-success text-uppercase"; $classn="col-md-12 border-bottom pl-3 pr-3 pt-3 pb-2 tabpane"; $functionnew = "onchange = deselectall(".$cat_id.")"; 
                            $selectall = '<div class="custom-control custom-checkbox custom-control-inline mb-1"><input type="checkbox" class="menu custom-control-input" id="allcheckbox'.$prod_cat[$i]['id'].'"   data-id="'.$par.'"  data-id1= "'.$cat_id.'" onclick = select_all('.$cat_id.')> <label class="custom-control-label cc-font-weight-6" for="allcheckbox'.$prod_cat[$i]['id'].'">'.$prod_cat[$i]['menu_name'].'</label></div>' ; } 
                            else { $checkbox_class= "child_of".$par;  
                            array_push($array1,$prod_cat[$i]['id']); }
                            $menu .='<div class="'.$classn.'"> <div class="mb-2">'.$selectall;
                            if($par != 0){
                            $menu.= '<div class="custom-control custom-checkbox custom-control-inline mb-1"><input type="checkbox" id="checkbox'.$prod_cat[$i]['id'].'"  data-id="'.$par.'" onchange ="deselect_main('.$prod_cat[$i]['id'].')"   class="menu custom-control-input '.$checkbox_class.'" value='.$prod_cat[$i]['id'].'  '.$functionnew.' '.$ischeckedvalue.'><label class="custom-control-label '.$boldclass.'" for="checkbox'.$prod_cat[$i]['id'].'">'.$prod_cat[$i]['menu_name'].'</label></div>'; }
                            $menu.=  '</div>';   
                            $prod_cat_2 = selectQuery(ADMINMENU,"*","parent_id=".$prod_cat[$i]['id']." and isActive='1'");
                            if(count($prod_cat_2)){
                                $menu .= "<div class='row m-0'>".get_menu_tree2($prod_cat[$i]['id'],$allocatetmenu)."</div>"; //call  recursively 
                            } 
                            $menu .= "</div>"; ?>
                            <?php } }
                        return $menu; } ?>
                        <div class="msgs"></div>
                        <div class="py-2 px-3 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Submit</button></div>
                    </div>
                </div>
            </form>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script  src="<?php echo SITEURL ?>/js/validation.js"> </script>
<script>
$(document).ready(function() {
    $("#submit").on("click",function() {
        var uname  = $.trim($("#uname").val()); var pass = $.trim($("#pass").val()); var uemail = $.trim($("#uemail").val()); var umbl = $("#umbl").val(); var arr = []; var arr_cat = [];
        $(".menu:checked").each(function(){
            if($(this).val() != "on") { arr.push($(this).val()); }
            parent_cat = $(this).attr('data-id');
            res = jQuery.inArray(parent_cat, arr_cat );
            if(res == -1  && parent_cat != 0) { arr_cat.push(parent_cat); }
            if(parent_cat == 0){  
                main_cat = $(this).attr('data-id1');
                res = jQuery.inArray(main_cat, arr_cat);
                if(res == -1){ arr_cat.push(main_cat); }
            } 
        });
        str1 = arr.toString(); str2 = arr_cat.toString(); var str = str1+","+str2;
        <?php if($_GET['id'] != "") { $action = "editsubadmin";$subadmin_id = $_GET['id']; } else { $action = "addsubadmin";$subadmin_id =''; } ?>
        var action = '<?php echo $action; ?>';
        var subadmin_id = '<?php echo $subadmin_id ?>';
        if((str !="") && (uname != "") && (pass != "") && (umbl != "") && (uemail != "") && (umbl.length == 10)){
            var info = {menuid:str,uname:uname,pass:pass,umbl:umbl,uemail:uemail,action:action,subadmin_id:subadmin_id};
            $.ajax({
                type:"POST",
                url:"ajaxdata.php",
                data:info,
                success:function(response){
                    response = $.trim(response);
                    if(response==1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Subadmin details saved successfully").delay(3000).fadeOut()
                        setTimeout(function(){ location.href="viewsubadminlist.php" ;}, 2000);
                    }if(response==0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try other username").delay(3000).fadeOut()
                    }if(response==2){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut()
                    }
                }
            });
        } else {
            if(uname == ""){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter user name").delay(3000).fadeOut();  
            } else if(pass == ""){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter password").delay(3000).fadeOut()  
            } else if(uemail == ""){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter user email-id").delay(3000).fadeOut();  
            } else if((umbl == "") || (umbl.length < 10) ){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter correct mobile no").delay(3000).fadeOut()  
            } else if(str == ""){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select menu").delay(3000).fadeOut();  
            }
        }   
    });
}); 
function select_all(id){
    var checked = $("#allcheckbox"+id ).is(':checked'); 
    if(checked == true){ $('.child_of'+id ).prop("checked", true); $("#checkbox"+id ).prop("checked", true); } else{
        $('.child_of'+id ).prop("checked", false); $("#checkbox"+id ).prop("checked", false);
    }
}
function checkparaent(id){
    var checked = $("#checkbox"+id ).is(':checked'); 
    var child = $('.child_of'+id ).is(':checked'); 
    if(child == true  && checked == false){
        $('.child_of'+id ).prop("checked", false);
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select main category first").delay(3000).fadeOut();
    }
}
function deselectall(id){
    var checked = $("#checkbox"+id ).is(':checked');
    if(checked == false){ $('.child_of'+id ).prop("checked", false); $("#checkbox"+id ).prop("checked", false); } 
}
function deselect_main(id){
    var checked = $("#checkbox"+id ).is(':checked'); 
    var parent = $("#checkbox"+id ).attr('data-id')
    if(checked == false){ $("#allcheckbox"+parent ).prop("checked", false); }
    else {
        $('.tabpane').each(function(){
            var checkbox_length = $(this).find('input:checkbox:not(:first)').length;
            var checked_check_box_length = $(this).find('input:checkbox:not(:first):checked').length;
            if(checkbox_length == checked_check_box_length){ }
        });
    }
}
$(document).ready(function(){
    $('.tabpane').each(function(){
        var checkbox_length = $(this).find('input:checkbox:not(:first)').length;
        var checked_check_box_length = $(this).find('input:checkbox:not(:first):checked').length;
        if (checkbox_length == checked_check_box_length) { }
    });
});
</script>
</body>
</html>
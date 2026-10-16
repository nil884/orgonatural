<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Contact Details</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <form>
                 <div class="card  <?php if(SMS_SYSTEM == "ON") { echo "d-none"; } ?>">
                     <div class="card-body">
                 <div class="alert alert-danger mb-0">SMS Gateway status is <b>OFF</b></div>
             </div>
             </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Add Contact Details</h2></div></div>
                    <div class="card-body">
                        <div class="row">
                            <label class="col-md-12 col-lg-12 col-xl-2 col-form-label cc-mandatorystar">Admin Contact No</label>
                            <div class="col-sm-3 col-md-4 col-lg-3 col-xl-2 mb-3">
                                <select id="Main_admin_code" class="form-control">
                                    <?php $get_country = selectQuery(COUNTRY,"name,phonecode"," id <> '' order by name asc");
                                    for($i=0;$i<count($get_country);$i++){ ?>
                                    <option value="<?php echo $get_country[$i]['phonecode'] ?>"  <?php if($get_country[$i]['phonecode'] == 91) { echo "selected"; } ?>><?php echo $get_country[$i]['name'].'-'.$get_country[$i]['phonecode']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-9 col-md-4 col-lg-4 col-xl-3 mb-3"><input type="text" name="Main_admin" id="Main_admin" onblur="numbercheck('Main_admin')" class="form-control" placeholder="Enter Admin Contact No" maxlength="10" /></div>
                            <div class="col-md-4 col-lg-3 col-xl-3"><button type="button" name="create" onclick="addmobile('Main_admin','Admin')"  class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <div class="admin_mobile_nodiv cc-custom-badge">
                            <div class="form-group row mb-0">
                                <div class="col-md-offset-3 col-md-5 attribute_manage">
                                    <input type="hidden" value="<?php echo $getconfigdetails[0]['Main_admin_contact']  ?>" id="mobilenomainadmin">
                                    <?php $sperate= explode(",",$getconfigdetails[0]['Main_admin_contact']) ;
                                    if(count($sperate) && $getconfigdetails[0]['Main_admin_contact'] != ""){ ?>
                                    <ul class="list-unstyled mb-0">
                                        <? for($i=0;$i<count($sperate);$i++) {?>
                                        <li class="border rounded"><span><?php echo $sperate[$i]  ?></span><span class="cc-cursor-pointer" onclick="del('<?php echo $sperate[$i]; ?>','Admin')"> &nbsp;<i class="fa fa-trash text-danger" aria-hidden="true"></i></span></li>
                                        <? } ?>
                                    </ul>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Contact Us Page</h2></div></div>
                    <div class="card-body">
                        <div class="row">
                            <label class="col-md-12 col-lg-12 col-xl-2 col-form-label cc-mandatorystar">Mobile No</label>
                            <div class="col-sm-3 col-md-4 col-lg-3 col-xl-2 mb-3">
                                <select id="contact_no_code" class="form-control">
                                    <?php $get_country = selectQuery(COUNTRY,"name,phonecode"," id <> '' order by name asc");
                                    for($i=0;$i<count($get_country);$i++) { ?>
                                    <option value="<?php echo $get_country[$i]['phonecode'] ?>" <?php if($get_country[$i]['phonecode'] == 91){ echo "selected"; } ?>><?php echo $get_country[$i]['name'].'-'.$get_country[$i]['phonecode']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-9 col-md-4 col-lg-4 col-xl-3 mb-3"><input type="text" name="contact_no" id="contact_no" onblur="numbercheck('contact_no')" class="form-control" placeholder="Enter Contact No" maxlength="10" /></div>
                            <div class="col-md-4 col-lg-3 col-xl-3"><button type="button" name="create" onclick="addmobile('contact_no','Contact')" class="btn btn-primary">Save</button></div>
                        </div>
                        <div class="contact_mobile_nodiv cc-custom-badge">
                            <div class="form-group row mb-0">
                                <div class="col-md-offset-3 col-md-5 attribute_manage">
                                    <input type="hidden" value="<?php echo $getconfigdetails[0]['contactus_no']  ?>" id="mobileNOcontact">
                                    <?php $sperate= explode(",",$getconfigdetails[0]['contactus_no']) ;
                                    if(count($sperate) && $getconfigdetails[0]['contactus_no'] != ""){ ?>
                                    <ul class="list-unstyled mb-0">
                                        <? for($i=0;$i<count($sperate);$i++){ ?>
                                        <li class="border rounded"><span><?php echo $sperate[$i] ?></span><span class="cc-cursor-pointer" onclick="del('<?php echo $sperate[$i]; ?>','Contact')"> &nbsp;<i class="fa fa-trash text-danger" aria-hidden="true"></i></span></li>
                                        <? } ?>
                                    </ul>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Enquiry On SMS</h2></div></div>
                    <div class="card-body">

                        <div class="row">
                        <label class="col-md-12 col-lg-12 col-xl-2 col-form-label cc-mandatorystar">Mobile No</label>
                        <div class="col-sm-3 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <select id="A_contact_no_code" class="form-control">
                                <?php $get_country = selectQuery(COUNTRY,"name,phonecode"," id <> '' order by name asc");
                                for($i=0;$i<count($get_country);$i++){ ?>
                                <option value="<?php echo $get_country[$i]['phonecode'] ?>" <?php if($get_country[$i]['phonecode'] == 91){ echo "selected"; } ?>><?php echo $get_country[$i]['name'].'-'.$get_country[$i]['phonecode']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-9 col-md-4 col-lg-4 col-xl-3 mb-3"><input type="text" name="A_contact_no" id="A_contact_no" onblur="numbercheck('A_contact_no')" class="form-control cc-margin-bottom-15" placeholder="Enter Mobile Number Comma Separated" maxlength="10" /></div>
                        <div class="col-md-4 col-lg-3 col-xl-3"><button type="button" name="create" class="btn btn-primary" onclick="addmobile('A_contact_no','Enquiry')">Save</button></div>
                        </div>
                        <div class="mobile_nodiv cc-custom-badge">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-5 attribute_manage">
                                    <input type="hidden" value="<?php echo $getconfigdetails[0]['enquiry_contact']  ?>" id="mobileNOA">
                                    <?php $sperate= explode(",",$getconfigdetails[0]['enquiry_contact']) ;
                                    if(count($sperate) && $getconfigdetails[0]['enquiry_contact'] != ""){ ?>
                                    <ul class="list-unstyled mb-0">
                                        <? for($i=0;$i<count($sperate);$i++) {?>
                                        <li class="border rounded"><span><?php echo $sperate[$i]  ?></span><span class="cc-cursor-pointer" onclick="del('<?php echo $sperate[$i]; ?>','Enquiry')"> &nbsp;<i class="fa fa-trash text-danger" aria-hidden="true"></i></span></li>
                                        <? } ?>
                                    </ul>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Address</h2></div></div>
                    <div class="card-body">
                        <div class="form-group row"><label class="col-md-12 col-lg-12 col-xl-2 col-form-label cc-mandatorystar">Address</label><div class="col-md-12 col-lg-9 col-xl-7"><textarea id="area" class="form-control" placeholder="Enter Address" rows="3"><? echo $getconfigdetails[0]['address']; ?></textarea></div></div>
                        <div class="form-group row"><label class="col-md-12 col-lg-12 col-xl-2 col-form-label cc-mandatorystar">Pincode</label><div class="col-md-12 col-lg-4 col-xl-4"><input type="text" id="pincode" class="form-control" placeholder="Pincode" maxlength="6" value="<? echo $getconfigdetails[0]['pincode']; ?>"></div></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Embeded Google Map Link</h2></div></div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-12 col-lg-12 col-xl-2 col-form-label">Map Link</label>
                            <div class="col-md-12 col-lg-9 col-xl-7"><textarea id="map" class="form-control" placeholder="Enter Embeded Link of Google Map" rows="3"><? echo $getconfigdetails[0]['map']; ?></textarea></div>
                        </div>
                        <div class="row"><div class="offset-xl-2 col-xl-5"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">How to Embeded Link of Google Map?</button></div></div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </div>
            </form>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title">How To Get Google Embed Map Link?</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <ol class="mb-0 pl-3">
                    <li>Visit Google Map And Locate The Desired Location</li>
                    <li>Locate The Share Button</li>
                    <li>Select Embeded Map Tab And Copy The Embeded Map Link</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
function del(mob,type){
    del_alertbox("Do you reallly want to delete this mobile number?",mob,'del_mobl',type);
}
function del_mobl(mob,type){
    var mob = mob
    var info = {mob:mob,action:'delmob',type:type}
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data: info,
        success: function(response){
            if(type == "Contact"){ $(".contact_mobile_nodiv").load( " .contact_mobile_nodiv"); }
            else if(type == "Enquiry"){ $(".mobile_nodiv").load( " .mobile_nodiv"); }
            else if(type == "Admin"){ $(".admin_mobile_nodiv").load( " .admin_mobile_nodiv"); }
            $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Mobile no deleted successfully.").delay(3000).fadeOut();
        $("#del_popup").modal("hide");
    }
    });
}
function addmobile(id,type){
    A_contact_no = $("#"+id).val();
    code = $("#"+id+"_code").val();
    var info = {A_contact_no:A_contact_no,action:'add_admin_mob',type:type,code:code,  }
    if(A_contact_no != "") {
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                if(response == 1){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Allready exist").delay(3000).fadeOut();
                }
                else if(response == 2){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("You can add maximum 5 Mobile No").delay(3000).fadeOut();
                } else{
                    $("#"+id).val("");
                    if(type == "Contact"){ $(".contact_mobile_nodiv").load( " .contact_mobile_nodiv"); }
                    else if(type == "Enquiry"){
                        $("#A_contact_no").val(""); $(".mobile_nodiv").load( " .mobile_nodiv");
                    }else if(type == "Admin"){ $(".admin_mobile_nodiv").load( " .admin_mobile_nodiv"); }
                }
            }
        });
    } else {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter valid  mobile no").delay(3000).fadeOut();
    }
}
$(document).ready(function(){
    $("#submit").on("click", function(){
        var contact_no = $("#contact_no").val(); var Main_admin = $("#Main_admin").val(); var area = $("#area").val(); var pincode=$("#pincode").val(); var map = $("#map").val(); var mobileNOA = $("#mobileNOA").val(); var mobileNOcontact = $("#mobileNOcontact").val(); var mobilenomainadmin = $("#mobilenomainadmin").val();
        if ((area != "") && (mobileNOA!= "") && (mobileNOcontact != "") && (mobilenomainadmin != "") &&pincode!=""){
            var info = {contact_no: contact_no, Main_admin:Main_admin, area: area, pincode:pincode, map:map, action:'contact'};
             var info0 = { pincode:pincode, action:'pincodedetails'};
               $.ajax({
                type: "POST",
                url: "<?=SITEURL; ?>/ajax/order_ajax.php",
                data: info0,
                success: function(response){
                    jsondata=JSON.parse(response);
                    if(jsondata['status'] == "success"){
                         $.ajax({
                        type: "POST",
                        url: "ajaxdata.php",
                        data: info,
                        success: function(response){
                            if(response == 1){
                                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                            } if(response == 0){
                                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Try after some time").delay(3000).fadeOut();
                            }
                        }
                    });
                    }else{
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Pincode is not valid").delay(3000).fadeOut();
                    }
                }
            });

        } else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Fill all mandatory fields correctly").delay(3000).fadeOut();
        }
    });
});
</script>
</body>
</html>
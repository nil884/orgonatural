<?php include("../includes/configuration.php"); require_once('../classes/user.php');   
$user = new User();  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shipping Address : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
      <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "../commoncss.php" ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
</head>
<body>
<div class="main-wrap"> 
<?php include "notification_account.php" ?>    
    <?php include "../menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">Shipping Address</li></ul></div></div></div>
    <div class="content py-3">        
        <div class="container"> 
            <h2 class="mb-0 cc-fw-5 h5">Shipping Address</h2>
            <p class="mb-3 text-muted">Manage your shipping and billing addresses.</p>
            <div class="card cc-shadow-1">
                <div class="row mx-0">
                    <?php include('sidebar.php')?>
                    <div class="col-sm-12 col-md-12 col-lg-9 py-3">
                        <h6 class="mb-4 cc-fw-5">Order Address</h6>
                        <? $addr = $user->getShippingAddress($loguser);
                        if(count($addr)){
                        for($i=0;$i<count($addr);$i++){?>
                        <div class="user-del-address mb-3 pb-3 border-bottom" id="adress_<?php echo $addr[$i]['id'];  ?>">
                            <div class="cc-primary-color"><?=$addr[$i]['address_type']; ?></div>
                            <h6 class="text-dark cc-fw-5"><?=$addr[$i]['address_name']; ?></h6>
                            <div class="mb-1 text-muted"><?=$addr[$i]['address']; ?>, <?=$addr[$i]['landmark']; ?>, <?=$addr[$i]['city']; ?>, <?=$addr[$i]['state']; ?>,<?=$addr[$i]['country']; ?> <?=$addr[$i]['pincode']; ?>.</div>
                            <div class="mb-2 text-muted">Contact No. <?=$addr[$i]['mobile_number']; ?></div>
                            <button class="btn btn-primary mt-2" onclick="edit_adress('<?=$addr[$i]['id']; ?>')">Edit Address</button>
                            <button class="btn btn-secondary mt-2" onclick="delete_adress('<?=$addr[$i]['id']; ?>')">Delete</button>                                        
                        </div>
                        <?} }else{?>
                            <div class="pt-4 pb-5 text-center">
                            <img src="<?php echo SITEURL; ?>/img/projectimage/address-not-found.svg" alt="address-not-found" width="120" class="m-auto">
                            <p class="lead mt-3 text-muted">No address found for you</p>
                            <a href='<?=SITEURL;?>' class='btn btn-primary py-2'>Start Shopping</a></div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
<div id="order-addres" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md edit-address-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0 cc-fw-5 modal-title">Address Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body"><div class='address_detail'></div></div>
        </div>
    </div>
</div>
<?php include "../footer.php" ?> 
<?php include "profilejs.php" ?> 
<script>
var siteurl = "<?=SITEURL;?>";
function getpincode(){
    pincode = $(".pincode").val();
    if(pincode!=""){
        $.ajax({
            type : "POST", url: siteurl+"/ajax/order_ajax.php",
            data : {pincode:pincode,action:"pincodedetails"},
            success: function(resr){
                resdata=JSON.parse(resr);
                if(resdata['status']=="success"){ $(".city").val(resdata['city']);$(".state").val(resdata['state']);
                } else{
                    $(".adrmsg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html(resdata['message']).fadeOut(5000);
                    $(".city").val("");$(".state").val("");
                }
            }
        });
    }
}
function editDelivery(){
    adress_id = $("#adress_id").val();  adress_type = $(".adress_type:checked").val(); country = $("#country").val(); fullname = $(".fullname").val(); mobile = $(".mobile").val(); pincode = $(".pincode").val();address = $(".address").val(); addlocation = $(".location").val(); city = $(".city").val();state = $(".state").val();
    if(fullname.trim()!=""&&mobile.trim()!=""&&pincode.trim()!=""&&address.trim()!=""&&city.trim()!=""&&state.trim()!=""){
    $.ajax({
        type : "POST", url: siteurl+"/ajax/common_ajax.php",
        data : {adress_id:adress_id,adress_type:adress_type ,country:country,fullname:fullname,mobile:mobile,pincode:pincode,address:address,addlocation:addlocation,city:city,state:state,user:"<?=$loguser;?>",action:"editAddress"},
        success: function(resr){
                if(resr==2){
                    $(".adrmsg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html("Same address allready exist").fadeOut(5000);
                } else if(resr==0){
                    $(".adrmsg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html("Invalid data.. please enter valid details").fadeOut(5000);
                } else{ 
                    $(".adrmsg").fadeIn().removeClass("alert alert-danger").addClass("alert alert-success").html("Address updated successfully").fadeOut(5000);
                    setTimeout(function(){
                        $("#adress_"+adress_id).load(" #adress_"+adress_id);
                        $("#order-addres").modal("hide");
                    }, 3000);
                }
            }   
        });
    }
} 
function edit_adress(id){
    info  = {adress_id:id,action:"get_adress_details"}
    $.ajax({
        type:"POST",
        url:"<?php echo SITEURL; ?>/ajax/common_ajax.php",
        data:info,
        success:function(response){
            $("#order-addres").modal("show");
            $(".address_detail").html(response);        
        }
    })
}
function delete_adress(adress_id,type){ del_alertbox("Do you really want to delete this adress?", adress_id,"del_adress_db",""); }
function del_alertbox(msg,i,funct,type){
    $("#del_popup").modal("show"); 
    $("#del_popup .modal-dialog").addClass("modal-sm"); 
    $("#del_message").html(msg);
    $("#popup_ok").attr("onclick", funct+"('" + i + "','" + type + "')");
}
function del_adress_db(id,type){
    info  = {adress_id:id,action:"Delete_adress"}
    $.ajax({
        type:"POST",
        url:"<?php echo SITEURL; ?>/ajax/common_ajax.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Adress deleted successfully").delay(3000).fadeOut();
                $("#adress_"+id).remove();
                $("#del_popup").modal("hide");
               
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
} 
$('#del_popup').on('shown.bs.modal', function(){
    $("body.modal-open").removeAttr("style");
    $("#del_popup").css("padding-right","0");
});
</script>
</body>
</html>
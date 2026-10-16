<?php include("../includes/configuration.php"); $bankarray = array("Axis Bank Limited","Central Bank of India","Bank of Maharashtra","HDFC Bank Limited","ICICI Bank Limited","IDFC Bank Limited"); ?>
<!doctype html>
<html lang="en">
<head>
    <title>Bank Details : <?php echo SITE_TITLE; ?></title>
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
      <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
    <?php include('../commoncss.php')?>
 </head>
<body>
<?php include 'notification_account.php' ?>
<div class="main-wrap">
    <?php include '../menu.php' ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">Bank Details</li></ul></div></div></div>
    <div class="content py-3">
        <div class="container"> 
            <h2 class="mb-0 cc-fw-5 h5">Bank Details</h2>
            <p class="mb-3 text-muted">Manage your Bank preferences.</p>
            <div class="card cc-shadow-1">
                <div class="row mx-0">
                    <?php include('sidebar.php')?>
                    <div class="col-sm-12 col-md-12 col-lg-9 py-3">
                        <h6 class="mb-4 cc-fw-5">Bank Details</h6>
                        <div class="acntmsg"></div>
                        <form autocomplete="off" id="bankform">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label cc-mandatary-field">Bank</label>
                                        <select id="bank" class="form-control"  onchange="checkbank()" required>
                                            <option value="">Select Bank</option>  
                                            <?php for($i=0;$i<count($bankarray);$i++){ ?>
                                            <option value= "<? echo $bankarray[$i]; ?>" <?php if($getbuyer_details[0]['bank_name'] == $bankarray[$i]){ echo "selected"; } ?>><? echo $bankarray[$i]; ?></option><?php } ?>
                                            <option value="other" <? if(!in_array($getbuyer_details[0]['bank_name'],$bankarray) && ($getbuyer_details[0]['bank_name']!= "")){ echo "selected" ;} ?>>Other</option>
                                        </select>
                                        <div class="invalid-tooltip"  id="bank_tooltip">Please Select Bank</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 other_bank_detail <? if(in_array($getbuyer_details[0]['bank_name'],$bankarray) || ($getbuyer_details[0]['bank_name'] == "")){ echo "cc-display-none"; } ?>">
                                    <div class="form-group">
                                        <label class="control-label cc-mandatary-field">Other Bank Name</label>
                                        <input type="text" class="form-control" id="bank_name" placeholder="Enter Bank Name" value="<?php echo $getbuyer_details[0]['bank_name']; ?>" maxlength="250" required="">
                                    <div class="invalid-tooltip"  id="bank_name_tooltip">Please Enter Bank Name</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label cc-mandatary-field">Account Number</label>
                                        <input type="text" class="form-control" id="account_number" placeholder="Enter Account Number" onkeyup="numbercheck('account_number')" value="<?php echo $getbuyer_details[0]['account_number']; ?>"  required>
                                    <div class="invalid-tooltip"  id="accountnum_tooltip">Please Enter Account Number</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label cc-mandatary-field">IFSC Code</label>
                                        <input type="text" class="form-control" id="ifsc_code" placeholder="Enter IFSC Code" value="<?php echo $getbuyer_details[0]['ifsc_code']; ?>" maxlength="11" required>
                                    <div class="invalid-tooltip"  id="ifsc_tooltip">Please Enter IFSC Code</div>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">UPI ID</label>
                                        <input type="text" class="form-control" id="Upi_id" placeholder="Enter UPI ID" value="<?php echo $getbuyer_details[0]['upi_id']; ?>" maxlength="30" >
                                    <div class="invalid-tooltip"  id="upi_tooltip">Please Enter UPI ID</div>
                                    </div>
                                </div>
                                <div class="col-12"><button type="button" class="btn btn-primary" id="edit_bank_details">Submit</button></div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php' ?>
<script>
function checkbank(){
    bank = $("#bank").val();
    if(bank == "other"){ $(".other_bank_detail").removeClass("cc-display-none"); }
    else { $(".other_bank_detail").addClass("cc-display-none"); }
}
$("#edit_bank_details").click(function(){
    uid = '<?php echo $getbuyer_details[0]['u_id'];?>'; bank = $("#bank").val();  
    account_number = $("#account_number").val(); ifsc_code = $("#ifsc_code").val();
    Upi_id = $("#Upi_id").val();
    bank_name = $("#bank_name").val();
    
    var form = $("#bankform"); 
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
    if(bank == "" || account_number ==  "" || ifsc_code == "" ){
          if(bank == ""){
              $("#bank_tooltip").show();
          }
          else {
              $("#bank_tooltip").hide();
          }
          if(account_number == ""){
               $("#accountnum_tooltip").show();
          }
          else {
              $("#accountnum_tooltip").hide();
          }
          if(ifsc_code == ""){
               $("#ifsc_tooltip").show();
          }
          else{
              $("#ifsc_tooltip").hide();
          }

    }
    else if(bank == "other"  && bank_name == ""){
        if(bank_name == ""){
               $("#bank_name_tooltip").show();
          }
          else {
              $("#bank_name_tooltip").hide();
          }
          
    }
    else {
        if(bank == "other"){ bank = $("#bank_name").val(); } else{ bank = $("#bank").val(); }
       $("#edit_bank_details").prop("disabled",true);
    var info ={uid:uid,bank:bank,account_number:account_number,ifsc_code:ifsc_code,Upi_id:Upi_id,action:"update_bank"};
    $.ajax({
        type : "post",
        url : "<?php echo SITEURL; ?>/ajax/common_ajax.php",
        data : info,
        success : function(response){
            response = $.trim(response);
            $("#edit_bank_details").prop("disabled",false);
            if(response == 1){ $(".acntmsg").fadeIn().addClass("alert alert-success").html("Bank detail updated successfully").delay(3000).fadeOut(); location.reload(); } if(response == 0){ $(".acntmsg").fadeIn().addClass("alert alert-danger").html("Problem occurs while updating").delay(3000).fadeOut(); }
        }
    });
    }
})
</script>
</body>
</html>
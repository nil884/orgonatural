<?php include("../includes/configuration.php"); $bankarray = array("Axis Bank Limited","Central Bank of India","Bank of Maharashtra","HDFC Bank Limited","ICICI Bank Limited","IDFC Bank Limited"); ?>
<!doctype html>
<html lang="en">
<head>
    <title>Tax Details : <?php echo SITE_TITLE; ?></title>
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
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">Tax Details</li></ul></div></div></div>
    <div class="content pt-3 pb-4">
        <div class="container"> 
            <h2 class="mb-0 cc-fw-5 h5">Tax Details</h2>
            <p class="mb-3 text-muted">Manage your company and tax details</p>
            <div class="card">
                <div class="row mx-0">
                    <?php include('sidebar.php')?>
                    <div class="col-sm-12 col-md-12 col-lg-9 py-3">
                        <h6 class="mb-4 cc-fw-5">Tax Details</h6>
                        <div class="acntmsg"></div>
                        <form autocomplete="off" id="tax_detail">
                            <div class="row">
                                <div class="col-sm-6 other_company_detail">
                                    <div class="form-group position-relative">
                                        <label class="control-label cc-mandatary-field">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" placeholder="Enter Company Name" value="<?php echo $getbuyer_details[0]['company_name']; ?>" maxlength="250" required>
                                        <div class="invalid-tooltip" id="compony_name_tooltip">Please Enter Company Name</div>
                                    </div>
                                </div>   
                                <div class="col-sm-6 other_company_detail">
                                    <div class="form-group position-relative">
                                        <label class="control-label cc-mandatary-field">Company Address</label>
                                        <input type="text" class="form-control" id="company_add" placeholder="Enter Company Address" value="<?php echo $getbuyer_details[0]['company_address']; ?>" maxlength="500" required>
                                        <div class="invalid-tooltip" id="compony_address_tooltip">Please Enter Company Address</div>
                                    </div>
                                </div>
                                 <div class="col-sm-6 col-md-6">
                                    <div class="form-group position-relative">                                       
                                        <div class="form-group">
                                            <label class="control-label cc-mandatary-field">GST / VAT Number</label>
                                            <input type="text" class="form-control" id="tax_no" placeholder="Enter TAX Number" value="<?php echo $getbuyer_details[0]['tax_no']; ?>" maxlength="15" required>
                                            <div class="invalid-tooltip" id="gst_tooltip">Please Enter GST Number</div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                                         
                            <button type="button" class="btn btn-primary" id="edit_company_details">Submit</button>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php' ?>
<script>
$("#edit_company_details").click(function(){
    uid = '<?php echo $getbuyer_details[0]['u_id'];?>';
    tax_no = $("#tax_no").val(); 
    company_name = $("#company_name").val(); 
    company_add = $("#company_add").val();
    var form = $("#tax_detail");
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
    if(company_name == ""  || company_add == ""  || tax_no == ""){
        if(company_name == ""){
             $("#compony_name_tooltip").show();
        } else{
            $("#compony_name_tooltip").hide();
        } if(company_add == ""){
            $("#compony_address_tooltip").show();
        } else{
            $("#compony_address_tooltip").hide();
        } if(tax_no == ""){
            $("#gst_tooltip").show();
        } else{
            alert("in gst loop");
            $("#gst_tooltip").hide();
        }
    } else{
        $("#edit_company_details").prop("disabled",true);
        var info = {uid:uid,tax_no:tax_no,company_name:company_name,company_add:company_add,action:"update_tax_details"};
        $.ajax({
            type : "post",
            url : "<?php echo SITEURL; ?>/ajax/common_ajax.php",
            data : info,
            success : function(response){
            response = $.trim(response);
            $("#edit_company_details").prop("disabled",false);
            if(response == 1){ $(".acntmsg").fadeIn().addClass("alert alert-success").html("Company details updated successfully").delay(3000).fadeOut(); }
            if(response == 0){ $(".acntmsg").fadeIn().addClass("alert alert-danger").html("Problem occurs while updating").delay(3000).fadeOut(); }
            }
        });
    }  
}) 
</script>
</body>
</html>
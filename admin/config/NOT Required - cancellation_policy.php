<?php include("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title>Config : Cancellation Charges </title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Add Cancellation Charges in percentage </h5></div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group row mb-2">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Cancellation Charges</label>
                            <div class="col-6 col-sm-3 col-md-4 col-lg-3 col-xl-2">
                                <div class="input-group cancel-charg qtybox">
                                <input type="number" min="0" max="100" name="Cancellation_charge" id="Cancellation_charge" class="form-control rounded calcel-charg-inp rounded-0" value="<? echo $getconfigdetails[0]['cancelation_charge_percentage']; ?>" onkeyup="numbercheck('Cancellation_charge')"/>
                                <div class="input-group-append">
                            
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 offset-sm-4 col-md-5 offset-md-4 col-lg-4 offset-lg-3 col-xl-3 offset-xl-2"><button type="button" name="create" id="submit" class="btn btn-primary" >Save <span class="loader"></span></button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>    
<script>
$(document).ready(function() {
    $("#submit").on("click", function(){
        var Cancellation_charge = $("#Cancellation_charge").val();
        if (Cancellation_charge == "") {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Enter Cancellation charges").delay(3000).fadeOut();
        }
        else if(Cancellation_charge < 0 || Cancellation_charge >100) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Cancellation charges must be between 0 - 100").delay(3000).fadeOut();
        } else{
            var info = {
            Cancellation_charge: Cancellation_charge,
            action:'Cancellation_charge'
            };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response) {
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details Updated Successfully").delay(3000).fadeOut();
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } 
    });
});
    
$('#Cancellation_charge').on('keyup keydown', function(e){
    if ($(this).val() > 100 && e.keyCode !== 46 && e.keyCode !== 8) {
       e.preventDefault();     
       $(this).val(100);
    } else if($(this).val() < 0 && e.keyCode !== 46 && e.keyCode !== 8){
        e.preventDefault();     
        $(this).val(0);    
    }
});

(function($){
    $.fn.spinner = function(){
        this.each(function(){
            var el = $(this);
            el.wrap('<span class="spinner pr-1"></span>');
            el.before('<span class="sub rounded-left">-</span>');
            el.after('<span class="add rounded-right">+</span>');
            el.parent().on('click', '.sub', function(){
                if (el.val() > parseInt(el.attr('min')))
                el.val( function(i, oldval) { return --oldval; });
            });
            el.parent().on('click', '.add', function(){
                if (el.val() < parseInt(el.attr('max')))
                el.val( function(i, oldval) { return ++oldval; });
            });
        });
    };
})(jQuery);
$('input[type=number]').spinner(); 
</script>
</body>
</html>
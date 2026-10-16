<?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> Lead Update</title>
    <?php include 'commoncss.php';?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('menu.php');
    $lead=base64_decode($_REQUEST['lead']);
    $ticketdetails=selectQuery(CONTACT,"*","contact_request_id=".$lead); ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <form class="form tktform">
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center">
                        <div><h5 class="card-head-title">Ticket # - <span id="leadno" class="text-info"><a href="requestdetails.php?req=<?php echo base64_encode($lead); ?>"><?php echo $lead; ?></a></span></h5></div>
                        <div class="btn-actions-pane-right"><button type="button" class="btn btn-outline-light text-dark btn-sm" onclick="goBack()"> Back </button></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Enter Name</label>
                                    <input type="text" maxlength="50" minlength="3" onblur="firstcapital('name1')" onkyeup="namechk1('name1')" class="form-control newattr" name="name" placeholder="Enter Name" id="name1" value="<?php echo $ticketdetails[0]['Name']; ?>"/>
                                    <input type="hidden" id="encleadno" value="<?php echo base64_encode($lead); ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Enter Email Address</label>
                                    <input  type="text" class="form-control" maxlength="70" minlength="7" name="email"  placeholder="Enter Email Address." id="cemail" onblur="mailchk('cemail')" value="<?php echo $ticketdetails[0]['Email']; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Enter Mobile Number</label>
                                    <input type="text"  class="form-control" name="tele" id="tele"  onkeyup="mobnumbercheck('tele')" placeholder="Enter Mobile Number" maxlength="10" value="<?php echo $ticketdetails[0]['Telephone']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="overduedate" class="cc-mandatary-field">Overdue Date</label>
                                    <input type='text' class="form-control" id='overduedate' placeholder="Overdue Date" value="<?php if($ticketdetails[0]['overdue_date']!=""){echo date('d-m-Y H:i:s', strtotime($ticketdetails[0]['overdue_date']));}  ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Enter Comment</label>
                                    <textarea name="cmnt" placeholder="Enter Comment" class="form-control" id="textareas" cols="5" rows="4" maxlength="400"><?php echo $ticketdetails[0]['Comment']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Reason to update</label>
                                    <textarea class="form-control" id="Reason" onkeyup="checkreason();" placeholder="Reason to update" maxlength="500" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="msg"></div>
                        <div>
                            <button type="button" class="btn btn-primary submit" onclick="saveentry()">Update Ticket</button>
                            <button type="button" class="btn btn-secondary" onclick="goBack()">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$(function(){
    $('#dateoftravel').datetimepicker({
        ignoreReadonly: true,
        minDate:moment(),
        format: 'DD-MM-YYYY',
        disabledTimeIntervals:false
    });

    $('#overduedate').datetimepicker({
        format: 'DD-MM-YYYY HH:mm:ss',
        disabledTimeIntervals:false
    });
});

function mobnumbercheck(id){
    var inp = $("#"+id).val();
    var last = inp.charAt(0);
    if(isNaN(inp)){
    var newstr = inp.slice(0, -1);
    $("#"+id).val(newstr);
    } if(last!=7&&last!=8&&last!=9){
        $("#"+id).val("");
    }
}
       
function numbercheck(id){
    var inp = $("#"+id).val();
    if(isNaN(inp)){
    var newstr = inp.slice(0, -1);
    $("#"+id).val(newstr);
    }
    else{ }
}
       
function namechk(id){
    var name =  $('#'+id).val();
    var alphanumers = /^[a-zA-Z]+$/;
    if(!alphanumers.test(name)){
        var newstr = name.slice(0, -1);
        $("#"+id).val(newstr);
    }
}
       
function namechk1(id){
    var name =  $('#'+id).val();
    var alphanumers = /^[a-zA-Z ]+$/;
    if(!alphanumers.test(name)){
        var newstr = name.slice(0, -1);
        $("#"+id).val(newstr);
    }
}
       
function mailchk(id){
    var email = $("#"+id).val();
    var patt = new RegExp(/^[a-zA-Z0-9.\-]+@[a-zA-Z.\-]+\.[a-zA-Z]{1,4}$/);
    var res = patt.test(email);
    if(res){
    }
    else{
        $("#"+id).val("");
        $("#"+id).focus();
    }
}
       
function saveentry(){
    var name = $("#name1").val();
    var email = $("#cemail").val();
    var tele =  $("#tele").val();
    var cmnt =  $("#textareas").val();
    var overduedate=$("#overduedate").val().trim();
    var Reason=$("#Reason").val().trim();
    var leadno = "<?php echo $lead;?>";
    var  encleadno = $("#encleadno").val();
    if(name.length==0||email.length==0||tele.length==0||cmnt.length==0||overduedate.length==0||Reason.length==0){
        $(".msg").fadeIn().html("Please Select Mandatory Fields").addClass("alert alert-danger").delay(5000).fadeOut()
    } else {
        var loader="<?php echo LOADER; ?>";
        var loadimg="<img src='"+loader+"' style='width:20%'>";
        $(".submit").attr("disabled",true);
        $(".submit").append(" "+loadimg);
        var form_data = new FormData();
        form_data.append('leadno', leadno);
        form_data.append('name', name);
        form_data.append('email', email);
        form_data.append('tele', tele);
        form_data.append('cmnt', cmnt);
        form_data.append('overduedate', overduedate);
        form_data.append('Reason', Reason);
        form_data.append('action',"update_lead");
        $.ajax({
            type:"POST",
            url:"ajax/createticket.php",
            data:form_data,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                $(".submit").attr("disabled",false);
                $(".submit img").remove();
                if(response) {
                    if(response==0) {
                        $(".msg").fadeIn().html("Error While Ticket Update").addClass("alert alert-danger").delay(3000).fadeOut();
                    } else if(response==1) {
                        $(".msg").fadeIn().html("Ticket Updated Successfully").addClass("alert alert-success").delay(3000).fadeOut();
                        setTimeout(function(){ window.location="requestdetails.php?req= "+encleadno }, 3000);
                    }
                    else{ }
                }
            }
        });
    }
}
</script>
</body>
</html>
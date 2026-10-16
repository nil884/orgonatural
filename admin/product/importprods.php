<? include("../../includes/configuration.php");
include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
include_once('../../PHPExcel/excelfunctions.php');
if(isset($_POST)){
    if($_POST['subcategory']!=""){
        $subcatid = $_POST['subcategory']; $seller=$_POST['seller'];
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $getcat = selectQuery(PRODCAT,"template","id =".$subcatid."");
        $excel = createExcelForExportAdmin($getcat[0]['template'],$url);
    }
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Import Products</title>
    <? include "../commoncss.php"; ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Bulk Import Products</h2></div></div>
                <div class="card-body pb-2">
                    <h6 class="mb-3">Import Your Excel</h6>
                    <div class="alert alert-info">
                        <h6><b>Before Upload</b></h6>
                        <ul class="mb-0 pl-3">
                            <li class="mb-1">Please make sure, you have latest excel file to import</li>
                            <li class="mb-1">Please read all instructions in excel before fill your product details, Invalid details may impact your product </li>
                            <li class="mb-1">Please make sure, you have entered all details correctly</li>
                            <li class="mb-1">Browse And Import The Final Excel File</li>
                        </ul>
                    </div>
                    <form id="prodct_add_form" action="save_basic.php" method="post">
                        <div class="file-field mb-2">
                            <input type="file" id="filein" accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="d-none">
                            <div id="custom-text" class="mb-2 text-muted">No file chosen, yet.</div>
                            <button type="button" class="btn btn-primary" id="custom-button">Choose a file</button> 
                        </div>
                        <div class="mb-3">Acepted File Format : .csv,.xls,.xlsx</div>
                        <div class="border-top row pt-2 text-right"><div class="col-12"><button type="button" class="btn btn-primary btntopmargin" id="importBtn" onclick="importnow()">Import Data</button></div></div>
                   </form>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script>
function importnow(){
    if($('#filein').val()!=""){
        $("#importBtn").attr("disabled",true).html("Importing Data...");
        var attachment = $('#filein').prop('files')[0];
        var attachmenttype = ($('#filein'))[0].files[0].type;
        var form_data1 = new FormData();
        form_data1.append('attachment', attachment);
        $.ajax({
            type:"POST",
            url:"ajaxuploadExcel.php",
            data:form_data1,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                $("#importBtn").attr("disabled",false).html("Import Data");
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Your file is in process. We will update you once all done").delay(10000).fadeOut();
                    $("#filein").val("");
                    setTimeout(function(){ location.reload(); }, 3000);
                }else{
                    $("#filein").val("");     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(response).delay(10000).fadeOut();
                }
            }
        });
    }
}
</script>
<script>
const realFileBtn = document.getElementById("filein");
const customBtn = document.getElementById("custom-button");
const customTxt = document.getElementById("custom-text");
customBtn.addEventListener("click", function(){ realFileBtn.click(); });
realFileBtn.addEventListener("change", function(){
    if(realFileBtn.value){
        customTxt.innerHTML = realFileBtn.value.match(
        /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1];
    } else{ customTxt.innerHTML = "No file chosen, yet."; }
});  
</script>
</body>
</html>
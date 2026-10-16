<?php include("../../includes/configuration.php");
ini_set("display_errors",1);
include("../../classes/surunapi.php");
?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : DNS Zone</title>
    <?php include('../commoncss.php'); ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php'); ?>
    <?php include('../sidebar.php');
    $srn=new surun();
    ?>
    <div class="main-panel">
        <div class="dashbody" id="dashbody">
        <?
       /* $url="https://www.aerotechit.com"; */
        $url=SITEURL;
        $records=$srn->getrecords(array("sitename"=>$url));
     // echo "<pre>"; print_r($records);  echo "</pre>";

        ?>
            <div class="bg-white rounded-top border">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">MX Records</h2></div></div>
                <div class="card-body">
                    <div class="alert alert-info mb-2">Note : MX records specify the mail servers responsible for accepting emails on behalf of your domain</div>
                    <div class="row mr-0 mb-3">
                        <div class="col-md-3 pr-0">
                            <label>Hostname</label>
                            <input type="text" class="form-control" placeholder="Enter @ OR Hostname" id="mxhost" maxlength="100">
                        </div>
                         <div class="col-md-4 pr-0">
                            <label>Mail Providers</label>
                            <input type="text" class="form-control" placeholder="eg.aspmx.l.google.com"  id="mxprovider" maxlength="300">
                        </div>
                         <div class="col-md-2 pr-0">
                            <label>Priority</label>
                            <input type="text" class="form-control" placeholder="Enter Priority"  id="mxpriority" maxlength="4">
                        </div>
                         <div class="col-md-2 pr-0">
                            <label>TTL (Seconds)</label>
                            <input type="text" class="form-control" placeholder="TTL eg 14400"  id="mxttl" maxlength="6">
                        </div>
                         <div class="col-md-1 pr-0">
                            <label>&nbsp;</label>
                            <div><button type="button" class="btn btn-primary btn-block add" onclick="addrecord('MX')">Add</button></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Hostname</th>
                                    <th>Mail Providers</th>
                                    <th>Priority</th>
                                    <th>TTL (Seconds)</th>
                                    <th class="del-btn-tr">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?
                             $keys= array_keys(array_column($records, 'type'), "MX");
                             if(count($keys)){
                                 for($i=0;$i<count($keys);$i++){
                                    $data= $records[$keys[$i]];
                                     ?>
                                     <tr>
                                        <td class="py-2"><?=$data['name']; ?></td><td class="py-2"><?=$data['data']; ?></td><td class="py-2"><?=$data['priority']; ?></td><td class="py-2"><?=$data['ttl']; ?></td>
                                        <td class="py-2"><button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('<?=$data['id']; ?>')"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    <?
                                  }

                             }else{
                                 ?><tr><td colspan="5">No Record Added</td></tr><?
                             }
                             ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-white border-left border-right">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">TXT Records</h2></div></div>
                <div class="card-body">
                    <div class="alert alert-info mb-2">Note : TXT records are used to associate a string of text with a hostname. These are primarily used for verification.</div>
                    <div class="row mr-0 mb-3">
                        <div class="col-md-5 pr-0">
                            <label>Value</label>
                            <input type="text" class="form-control" placeholder="Paste TXT String Here"  id="txtval" >
                        </div>
                         <div class="col-md-4 pr-0">
                            <label>Hostname</label>
                            <input type="text" class="form-control" placeholder="Enter @ OR Hostname"  id="txthost" maxlength="100">
                        </div>
                         <div class="col-md-2 pr-0">
                            <label>TTL (Seconds)</label>
                            <input type="text" class="form-control" placeholder="TTL eg. 3600"  id="txtttl" maxlength="6">
                        </div>
                         <div class="col-md-1 pr-0">
                            <label>&nbsp;</label>
                            <div><button type="button" class="btn btn-primary btn-block" onclick="addrecord('TXT')">Add</button></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                 <th>Value</th>
                                 <th>Hostname</th>

                                    <th>TTL (Seconds)</th>
                                    <th class="del-btn-tr">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?
                             $tkeys= array_keys(array_column($records, 'type'), "TXT");
                             if(count($tkeys)){

                                 for($i=0;$i<count($tkeys);$i++){
                                  $r=$tkeys[$i];
                                  $data= $records[$r];
                                  ?>
                                     <tr>
                                        <td class="py-2"><?=$data['data']; ?></td><td class="py-2"><?=$data['name']; ?></td><td class="py-2"><?=$data['ttl']; ?></td>
                                        <td class="py-2"><button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('<?=$data['id']; ?>')"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    <?
                                  }

                             }else{
                                 ?><tr><td colspan="4">No Record Added</td></tr><?
                             }
                             ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           <!-- <div class="bg-white border rounded-bottom">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">SRV Records</h2></div></div>
                <div class="card-body">
                    <div class="alert alert-info mb-2">Note : SRV records specify the location (hostname and port number) of servers for specific services. You can use service records to direct certain types of traffic to particular servers.</div>
                    <div class="row mr-0 mb-3">
                        <div class="col-md-3 pr-0">
                            <label>Hostname</label>
                            <input type="text" class="form-control" placeholder="eg. _service_protocol"  id="srvhost" maxlength="100">
                        </div>
                         <div class="col-md-3 pr-0">
                            <label>Will Direct To</label>
                            <input type="text" class="form-control" placeholder="Enter @ OR Hostname"  id="srvredir" maxlength="100">
                        </div>
                         <div class="col-md-1 pr-0">
                            <label>Port</label>
                            <input type="text" class="form-control" placeholder="eg. 5060"  id="srvport" maxlength="100">
                        </div>
                         <div class="col-md-1 pr-0">
                            <label>Priority</label>
                            <input type="text" class="form-control" placeholder="eg. 1"  id="srvpriority" maxlength="100">
                        </div>
                        <div class="col-md-1 pr-0">
                            <label>Weight</label>
                            <input type="text" class="form-control" placeholder="eg. 5060"  id="srvweight" maxlength="100">
                        </div>
                        <div class="col-md-1 pr-0">
                            <label>TTL</label>
                            <input type="text" class="form-control" placeholder="eg. 14400"  id="srvttl" maxlength="100">
                        </div>
                         <div class="col-md-1 pr-0">
                            <label>&nbsp;</label>
                            <div><button type="button" class="btn btn-primary btn-block" onclick="addrecord('SRV')">Add</button></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Hostname</th>
                                    <th>Will Direct To</th>
                                    <th>Port</th>
                                    <th class="del-btn-tr">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                             $keys= array_keys(array_column($records, 'type'), "SRV");

                             if(count($keys)){
                                 for($i=0;$i<count($keys);$i++){
                                    $data= $records[$keys[$i]];
                                     ?>
                                     <tr>
                                         <td class="py-2"><?=$data['name']; ?></td><td class="py-2"><?=$data['data']; ?></td><td class="py-2"><?=$data['port']; ?></td>
                                        <td class="py-2"><button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('<?=$data['id']; ?>')"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    <?
                                  }

                             }else{
                                 ?><tr><td colspan="4">No Record Added</td></tr><?
                             }
                             ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>-->
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
function addrecord(rectype){

     mxhost=$("#mxhost").val(); mxprovider=$("#mxprovider").val(); mxpriority=$("#mxpriority").val(); mxttl=$("#mxttl").val();
     txtval=$("#txtval").val(); txthost=$("#txthost").val(); txtttl=$("#txtttl").val();
     srvhost=$("#srvhost").val(); srvredir=$("#srvredir").val(); srvport=$("#srvport").val();
     srvpriority=$("#srvpriority").val(); srvweight=$("#srvweight").val(); srvttl=$("#srvttl").val();

    if(rectype=="MX"&&(mxhost==""||mxprovider==""||mxpriority==""||mxttl=="")){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("All details are mandatory").delay(3000).fadeOut();
    }else if(rectype=="TXT"&&(txtval==""||txthost==""||txtttl=="")){
         $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("All details are mandatory").delay(3000).fadeOut();
    }else if(rectype=="SRV"&&(srvhost==""||srvredir==""||srvport==""||srvpriority==""||srvweight==""||srvttl=="")){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("All details are mandatory").delay(3000).fadeOut();
    }else{
        if(rectype=="MX"){
           var info = {rectype:rectype,  mxhost: mxhost,mxprovider:mxprovider,mxpriority:mxpriority,mxttl:mxttl, action:'add_domain_records' };
        }else if(rectype=="TXT"){
           var info = {rectype:rectype,txtval:txtval, txthost:txthost, txtttl: txtttl, action:'add_domain_records' };
        }else if(rectype=="SRV"){
           var info = {rectype:rectype,  srvhost: srvhost,srvredir:srvredir,srvport:srvport,srvpriority:srvpriority ,srvweight:srvweight,srvttl:srvttl,action:'add_domain_records' };
        }
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                if(response == 1){
                  $("#dashbody").load(" #dashbody");  $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                } if(response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    }
}

function deleteRecord(id){
   msg = "Do you really want to delete this record?";
    del_alertbox(msg, id,"del_record");
}

function del_record(id){
    var info = {recid:id,action:'delete_domain_records' };
   $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                  $("#del_popup").modal("hide");
                if(response == 1){
                  $("#dashbody").load(" #dashbody");  $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Record deleted..").delay(3000).fadeOut();
                } if(response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
}

</script>
</body>
</html>
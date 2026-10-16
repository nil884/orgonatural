<?php include("../../includes/configuration.php");
$vendor = ($getconfigdetails[0]['default_vendor_for_pos']==0?1:$getconfigdetails[0]['default_vendor_for_pos']); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : POS</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <form id="finishsale">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#creinvoice">Create POS Invoice</a></li>
                    <!--<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cashinvoice">Create Cash Invoice</a></li>-->
                </ul>
            <!-- Tab panes -->
            <div class="tab-content bg-white border-left border-bottom border-right rounded-bottom mb-3">
                <div id="creinvoice" class="card-body tab-pane active pb-0">
                    <h2 class="card-head-title mb-3">Create POS Invoice</h2>
                    <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control phoneno" name="phoneno" placeholder="Enter Phone Number" value="0000000000" onchange="fetchcustdata()">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control username" name="username" placeholder="Enter Name" value="POS">
                            </div>
                        </div>
                        
                        <!--<div class="col-md-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control email" name="email" placeholder="Enter Email ID">
                            </div>
                        </div>-->

                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>GST No</label>
                                <input type="text" class="form-control gstno" name="gstno" placeholder="GST No" value="">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control address" name="address" placeholder="Enter Address" value="POS">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" class="form-control pincode" name="pincode" placeholder="Enter Pincode" maxlength="6" value="<?php echo $getconfigdetails[0]['pincode']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" class="vsubrowcnt" name="vsubrowcnt" value="1">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Item List</h2></div></div>
                <div class="card-body border-bottom positemview">
                    <div class="table-responsive">
                    <table class="table table-bordered" id="qualityid">
                        <thead><tr><th>Item</th><th>HSN</th><th>Rate</th><th>Qty</th><th>Taxable</th><th>CGST</th><th>SGST</th><th>IGST</th><th>Total</th><th>Remove</th></tr></thead>
                        <tbody>
                            <tr> <? $wherestr='vendor='.$vendor.' AND isActive=1 AND ((parent_id=0 AND variation_available="0") OR (parent_id<>0))'; ?>
                                <td style="width:200px;" class="p-0"><input type="hidden" name="vsubid" class="form-control vsubid border-0 rounded-0"><input type="text" name="vSubQCode" class="form-control vSubQCode border-0 rounded-0" onkeyup="openfldsuggession(event,'<?=SITEURL;?>','Item','<?=base64_encode(PRODINFO);?>','<?=base64_encode('id,prod_name');?>','<?=base64_encode($wherestr);?>','vSubQCode','prod_name');"  data-checkify="required" autocomplete="off"></td>
                                <td class="p-0"><input type="text" name="vSubHsn" class="form-control vSubHsn border-0 rounded-0" data-checkify="required" autocomplete="off" tabindex="-1" readonly></td>
                                <td class="p-0"><input type="text" name="vSubRate" class="form-control vSubRate border-0 rounded-0" data-checkify="required" autocomplete="off"   onchange="calculaterow('vsubid','vSubQCode','vSubRate','vSubQty','vSubTaxable','vTax','vSubcgst','vSubsgst','vSubigst','vSubTotal')"></td>
                                <td style="width:100px;" class="p-0 position-relative"><div class="invqtybox"><span class="minusqty cc-cursor-pointer" onclick="minuschng(this,'vSubQty')">-</span><input type="number" name="vSubQty" class="form-control vSubQty border-0 rounded-0 pr-0" max="3" min="1" value="1" data-checkify="required" autocomplete="off" onchange="calculaterow('vsubid','vSubQCode','vSubRate','vSubQty','vSubTaxable','vTax','vSubcgst','vSubsgst','vSubigst','vSubTotal')" onkeypress="validate(event)"><span class="plusqty cc-cursor-pointer border-top-0 border-bottom-0" onclick="pluschng(this,'vSubQty')">+</span></div></td>
                                <td class="p-0"><input type="text" name="vSubTaxable" class="form-control vSubTaxable border-0 rounded-0" data-checkify="required" readonly tabindex="-1" autocomplete="off"></td>
                                <td class="p-0"><input type="hidden" name="vTax" class="form-control vTax border-0 rounded-0" data-checkify="required" tabindex="-1" autocomplete="off"><input type="text" name="vSubcgst" class="form-control vSubcgst border-0 rounded-0" data-checkify="required" tabindex="-1" readonly autocomplete="off"></td>
                                <td class="p-0"><input type="text" name="vSubsgst" class="form-control vSubsgst border-0 rounded-0" data-checkify="required" readonly tabindex="-1" autocomplete="off"></td>
                                <td class="p-0"><input type="text" name="vSubigst" class="form-control vSubigst border-0 rounded-0" data-checkify="required" readonly tabindex="-1" autocomplete="off"></td>
                                <td class="p-0"><input type="text" name="vSubTotal" class="form-control vSubTotal border-0 rounded-0" data-checkify="required" readonly tabindex="-1" autocomplete="off" onchange="calculateTotal()"></td>
                                <td class="p-0 text-center"><button type="button" name="removerow" class="removerow rmvbtn btn btn-xs btn-danger Cloth Sales my-1" onclick="removefunc('removerow','mainsub');"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="text-right"><button type="button" name="addrow1" class="addrow1 btn btn-primary" onclick="addrow('vsubid,vSubQCode,vSubHsn,vSubRate,vSubQty,vSubTaxable,vTax,vSubcgst,vSubsgst,vSubigst,vSubTotal,removerow','yes');">Add More</button></div>
                </div>
                <div class="card-body">
                    <div class="row">
                            <div class="col-sm-6 col-md-8 col-lg-7 col-xl-7">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-12 col-lg-5 col-xl-5 pt-0">Payment Mode</label>
                                    <div class="col-md-12 col-lg-7 col-xl-6"><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="paymodcash" class="paymentMode custom-control-input" name="paymentMode" value="Cash" checked> <label class="custom-control-label" for="paymodcash">Cash</label></div>
                                    <div class="custom-control custom-radio custom-control-inline"><input type="radio" id="paymodonline" class="paymentMode custom-control-input" name="paymentMode" value="Online"> <label class="custom-control-label" for="paymodonline">Online</label></div>
                                    <div class="custom-control custom-radio custom-control-inline"><input type="radio" id="paymodcod" class="paymentMode custom-control-input" name="paymentMode" value="COD"> <label class="custom-control-label" for="paymodcod">COD</label></div>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <label class="col-form-label col-md-12 col-lg-5 col-xl-5">Transaction ID (If Required)</label>
                                    <div class="col-md-7 col-lg-7 col-xl-5"><input type="text" class="form-control transactionId" name="transactionId" placeholder="Enter Transaction ID"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-5 col-xl-5">
                            <div class="form-group row">
                                    <label class="col-form-label col-md-12 col-lg-5 col-xl-5">Total</label>
                                    <div class="col-md-12 col-lg-7 col-xl-7"><input type="text" class="billAmt form-control" name="billAmt" readonly></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-12 col-lg-5 col-xl-5">Delivery Charges</label>
                                    <div class="col-md-12 col-lg-7 col-xl-7"><input type="text" class="deliveryCharge form-control" name="deliveryCharge" value="0" onchange="calculateTotal()"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-12 col-lg-5 col-xl-5">Discount</label>
                                    <div class="col-md-12 col-lg-7 col-xl-7"><input type="text" class="discount form-control" name="discount" value="0" onchange="calculateTotal()"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-12 col-lg-5 col-xl-5">Total Payable</label>
                                    <div class="col-md-12 col-lg-7 col-xl-7"><input type="text" class="payable form-control" name="payable" readonly></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="text-right mt-3"><button type="button" class="btn btn-primary" onclick="savedata()">Create Order</button></div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<div id="srchablefldmodal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body p-0 itemsrchmodal"></div></div></div></div>
<script>
    function fetchcustdata(){
        var phoneno = $(".phoneno").val();
        var IndNum = /^[0]?[6789]\d{9}$/;

        if(IndNum.test(phoneno)){
            $.ajax({
            url : "ajax/ajaxdata.php", type : "post", data : {action:"getcustdata", phoneno:phoneno},
            success : function(res3){
                if(res3!=""){
                    var res = JSON.parse(res3);
                    if(res['name']!=""){$(".username").val(res['name']);$(".gstno").val(res['gstno']);$(".address").val(res['address']);$(".pincode").val(res['pincode']);}
                }
            }
        });
        }else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter valid number").delay(3000).fadeOut(); 
        }
    }
$(".pincode").change(function(){
     pincode = $(".pincode").val();
     var info0 = { pincode:pincode, action:'pincodedetails'};
   $.ajax({
    type: "POST",
    url: "<?=SITEURL; ?>/ajax/order_ajax.php",
    data: info0,
    success: function(response){
        jsondata=JSON.parse(response);
        if(jsondata['status'] == "success"){
             $("input[class*='vSubRate']").each(function(){ $(this).change(); })
        }else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Pincode is not valid").delay(3000).fadeOut();
            $(".pincode").focus();
        }
    }
    });
});
//$('#qualityid tbody tr:first-child td:nth-last-of-type(1) button').hide();
function addrow(data,manual="no"){
    vsubrowcnt = $(".vsubrowcnt").val(); arr=data.split(","); trlenght = $('#qualityid tbody tr').length;   newcnt = parseInt(trlenght)+1;
    var content = $('#qualityid >tbody >tr').first(),
    element = null, element = content.clone();  // console.log(element);  alert(element[0]['innerHTML'])
    inhtml = element[0]['innerHTML'];
    for(var i=0;i<arr.length;i++){ oldval=arr[i];  newval=arr[i]+newcnt;  var newstr= inhtml.replace(new RegExp(oldval, 'g'),newval);inhtml=  newstr; }
    $('#qualityid tbody').append("<tr>"+inhtml+"</tr>");
    var rowcnt=parseInt(vsubrowcnt)+1;  $(".vsubrowcnt").val(rowcnt);
    $('#qualityid tbody tr:not(:first-child) td:nth-last-of-type(1) button').show();
    $('#qualityid tbody tr:last-child  input').val("");
    $('#qualityid tbody tr:last-child td:first-child input:visible').focus();
    $('#qualityid tbody tr:last-child td:first-child input:visible').focus();
    if(rowcnt==1){$(".removerow ").show(); }else{$(".removerow ").hide(); }
}
function removefunc(oButton,subamt){
   rows= $(".vsubrowcnt").val();
   if(rows!=1){
       var fldval = $("."+oButton).parents("tr").find("input[class*='vsubid']").val();
       if(fldval!=""){
            vsubrowcnt = $(".vsubrowcnt").val(); var empTab = document.getElementById('qualityid');  var rowcnt = parseInt(vsubrowcnt)-1; $("."+oButton).parents("tr").remove(); $(".vsubrowcnt").val(rowcnt); $(".addrow1").focus();
       }else{
           vsubrowcnt = $(".vsubrowcnt").val(); var empTab = document.getElementById('qualityid'); var rowcnt = parseInt(vsubrowcnt)-1;
            $("."+oButton).parents("tr").remove(); $(".vsubrowcnt").val(rowcnt); $(".addrow1").focus();
       }
        if(rowcnt==1){$(".removerow ").show(); }else{$(".removerow ").hide(); }
   }else{
       $(".vsubid,.vSubQCode,.vSubHsn,.vSubRate,.vSubTaxable,.vSubcgst,.vSubsgst,.vSubigst,.vSubTotal,.vTax").val("");
       $(".vSubQty").val(1)
   }


   realignclasses(); calculateTotal();
}
function realignclasses(){
    arr=['vsubid','vSubQCode','vSubHsn','vSubRate','vSubQty','vSubTaxable','vTax','vSubcgst','vSubsgst','vSubigst','vSubTotal','removerow'];
    var cnt = 0;
    $('#qualityid tbody tr').each(function(){
        trdata = $(this).html(); fldname = $(this).find("input[class*='vsubid']").attr("name");
        if(cnt==0){ myval=""; expected = "vsubid";
        } else{ myval=cnt+1; expected="vsubid"+myval; }
        lastchar = fldname.charAt(fldname.length-1);changable=trdata;
        var arrfld=[];
        for(var i=0;i<arr.length;i++){
            if(!isNaN(lastchar)){ oldcnt=lastchar }else{ oldcnt=''; }
            oldval = arr[i]+oldcnt;  newval=arr[i]+myval;
            var getval = $("."+oldval).val();
            arrfld[newval] = getval;
            var newstr= changable.replace(new RegExp(oldval, 'g'),newval);
            changable = newstr;
        }
        $(this).html(changable);
        for(var i in arrfld){ $("."+i).val(arrfld[i]); }
        cnt++;
    })
}
function openfldsuggession(e,siteurl,fld1,fld2,fld3,fld4=null,fld5,fld6){
    if(e.key=="Control"||e.key=="F3"||e.key=="Tab"||e.key=="Enter"){ } else{
    $("."+fld5).blur();
    $.ajax({
        url : siteurl+"/admin/pos/srchable.php", type : "post", data : { fld1:fld1, fld2:fld2, fld3:fld3, fld4:fld4, fld5:fld5, fld6:fld6},
        success : function(res3){
            var fldval=$("."+fld5).val();
            $("#srchablefldmodal .modal-body").html(res3); $("#srchablefldmodal").modal("show"); $(".srchablefld1").val(fldval); $(".srchablefld1").focus(); $("."+fld5).val("");
            $('#srchablefldmodal').on('shown.bs.modal', function (e){ $(".srchablefld1").focus();   findvaluessrchable(e,'srchablefld1',siteurl);});
            $('#srchablefldmodal').on('hidden.bs.modal', function (){ $("."+fld5).focus();
                var lastChar = fld5.substr(fld5.length - 1);  var last2Char = fld5.substr(fld5.length - 2);  if(isNaN(lastChar)){ getrateunit(0); }else if(!isNaN(last2Char)){ getrateunit(last2Char); }else{  getrateunit(lastChar);  }
             });
        }
    });
}}
function getrateunit(row){
    if(row==0){ postfix=""; }else{postfix=row; }
    var qualityname = $(".vSubQCode"+postfix).val();
    if(qualityname!=""){
        $.ajax({
            url : "ajax/ajaxdata.php", type : "post", data : {action:"getqualityrates", qualityname:qualityname},
            success : function(res3){
                if(res3!=""){
                    var res = JSON.parse(res3);
                     $(".vTax"+postfix).val(res['tax']);
                     $(".vSubRate"+postfix).val(res['rate']);
                    if($(".vSubHsn"+postfix).val()!=res['hsn']){$(".vSubHsn"+postfix).val(res['hsn']);}
                    $(".vSubQty"+postfix).val(1).change();
                    if(res['rate']!=""){ $(".vSubRate"+postfix).removeClass("checkify__has-error"); }
                }
            }
        });
    }
}

function calculaterow(vsubid,vSubQCode,vSubRate,vSubQty,vSubTaxable,vSubTax,vSubcgst,vSubsgst,vSubigst,vSubTotal){
    item=$("."+vSubQCode).val();  rate=$("."+vSubRate).val(); qty=$("."+vSubQty).val(); tax=$("."+vSubTax).val();    pincode = $(".pincode").val();
    if(item!=""&&rate!=""&&qty!=""){

        if(parseInt(qty)>0){
             $.ajax({
            url:"ajax/ajaxdata.php", type : "post", data : { action:"validateitem",qualityname:item,qty:qty,pincode:pincode,vSubQCode:vSubQCode,vSubRate:vSubRate,vsubid:vsubid,vSubQty:vSubQty,vSubTaxable:vSubTaxable,vSubTax:vSubTax,vSubcgst:vSubcgst,vSubsgst:vSubsgst,vSubigst:vSubigst,vSubTotal:vSubTotal },
            success : function(res3){
                if(res3!=0){
                    json=JSON.parse(res3);
                    if(json['stock']>0){
                     rate=$("."+json['vSubRate']).val(); qty=$("."+json['vSubQty']).val(); tax=$("."+json['vSubTax']).val();
                    isIgst=json['igst'];
                    $("."+json['vsubid']).val(json['id']);
                    taxable = parseFloat(rate)*parseFloat(qty);
                    $("."+json['vSubTaxable']).val(taxable);

                    if(isIgst=="false"){
                        cgst = ((parseFloat(taxable)/100)*parseFloat((tax/2))).toFixed(2);
                        sgst = ((parseFloat(taxable)/100)*parseFloat((tax/2))).toFixed(2);
                        igst = 0;
                        $("."+vSubcgst).val(cgst);   $("."+vSubsgst).val(sgst);   $("."+vSubigst).val(0);
                    }else{
                        cgst = 0; sgst = 0;
                        igst = ((parseFloat(taxable)/100)*parseFloat(tax)).toFixed(2);

                       $("."+vSubcgst).val(0);   $("."+vSubsgst).val(0);   $("."+vSubigst).val(igst);
                    }

                    total=Math.round(parseFloat(taxable)+parseFloat(cgst)+parseFloat(sgst)+parseFloat(igst)+parseFloat(0));
                    $("."+vSubTotal).val(total);  calculateTotal();
                }else{
                    $("."+vsubid).val(""); $("."+vSubQCode).val("");   $("."+vSubRate).val("");  $("."+vSubQty).val("1");
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Out Of Stock Product. Auto Removed").delay(3000).fadeOut(); 
                }  
                }else{ $("."+vsubid).val(""); $("."+vSubQCode).val("");   $("."+vSubRate).val("");  $("."+vSubQty).val("1"); }
            }
        });
        } else{
           $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Quantity must not be 0").delay(3000).fadeOut();
           $("."+vSubQty).val(0);
        }

    }
}
function calculateTotal(){
    total = 0; delivery= $(".deliveryCharge").val(); discount= $(".discount").val();
    $("input[name*='vSubTotal']").each(function(){ total = parseFloat(total)+parseFloat($(this).val()) });
    $(".billAmt").val(total)
    total = parseFloat(total)+parseFloat(delivery) - parseFloat(discount); 
   if(isNaN(total)){ $(".payable").val(0); }else{ $(".payable").val(total); }
}
function savedata(){
    username = $(".username"); phoneno = $(".phoneno").val(); email = $(".email").val(); address = $(".address").val(); gstno = $(".gstno").val();  pincode = $(".pincode").val();
    vsubrowcnt = $(".vsubrowcnt").val(); payable = $(".payable").val();
    if(payable==0 || payable==""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Enter Atleast 1 Item").delay(3000).fadeOut();
    } else{
        var info0 = { pincode:pincode, action:'pincodedetails'};
               $.ajax({
                type: "POST",
                url: "<?=SITEURL; ?>/ajax/order_ajax.php",
                data: info0,
                success: function(response){
                    jsondata=JSON.parse(response);
                    if(jsondata['status'] == "success"){
                        $.ajax({
                            url : "ajax/ajaxdata.php", type : "post",
                            data:$('#finishsale').serialize()+'&'+$.param({ action: "addData" }),
                            success : function(res3){
                                $("#btnsave").attr("disabled",false);
                                if(res3!=0){
                                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Order Created Successfully").delay(3000).fadeOut();
                                    setTimeout(function(){window.location=res3 }, 3000);
                                } else{ alerttoast();$(".msgs").html(res3); }
                            }
                        });
                    }else{
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Pincode is not valid").delay(3000).fadeOut();
                    }
                }
            });
    }
}
function validate(event){
    if(event.key == "-"){ event.preventDefault(); return false; }
    if(event.key == "+"){ event.preventDefault(); return false; }
}
function minuschng(th,qty){
    vqty=$("."+qty).val();
    var count = parseInt(vqty) - 1;
    count = count < 1 ? 1 : count;
    $("."+qty).val(count);
    $("."+qty).change();
    return false;
}
function pluschng(th,qty){
    vqty=$("."+qty).val();
    var newval = parseInt(vqty) + 1;
    $("."+qty).val(newval);
    $("."+qty).change();

}
</script>
</body>
</html>
<div id="order-item" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg delved-order-pop"><div class="modal-content item_detail"></div></div>
</div>


<div class="modal confirm-modal px-4" id="confirm_popup">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-body p-4">
                <h5 id="confirm_message" class="cc-fw-5 mb-3">&nbsp;</h5>
                <!--<h6 class="mt-2 mb-3"> Order Cancellation Charges are Rs.<b id="canc_charge"></b></h6>-->
                <form>

                    <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" id="prc-to-high" name="cancel_reason" type="radio" value="Vendor is not available">
                        <label class="custom-control-label" for="prc-to-high">Vendor is not available</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" id="ord-mistake" name="cancel_reason" type="radio" value="Product inventory was not up to date">
                        <label class="custom-control-label" for="ord-mistake">Product inventory was not up to date</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" id="wrong_packed" name="cancel_reason" type="radio" value="Wrong product packed">
                        <label class="custom-control-label" for="wrong_packed">Wrong product packed</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" id="Delivery_not_possible" name="cancel_reason" type="radio" value="Delivery is not possible">
                        <label class="custom-control-label" for="Delivery_not_possible">Delivery is not possible</label>
                    </div>
                    <div class="custom-control custom-radio mb-4">
                        <input class="custom-control-input" id="ord-can-other" name="cancel_reason" type="radio" value="Other">
                        <label class="custom-control-label" for="ord-can-other">Other</label>
                    </div>
                    <button type="button" class="btn btn-primary px-3" id="confirm_ok">Yes</button>
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">No</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function get_item(itemid){ 
    $.ajax({
        url : "<?php echo ADMINURL ?>/order/ajaxdata.php",
        type : "post",
        data : {itemid:itemid, action:"get_order_item_detail"},
        success : function(res){
            $("#order-item").modal("show");
            $("#order-item").find(".modal-dialog");
            $(".item_detail").html(res);
        }
    })
}
</script>
<script>
function track(shipmentId,divid){
    var info = {shipmentId:shipmentId,action:"trackOrder"};
    $.ajax({
        type: "POST",
        url: "<?=SITEURL; ?>/ajax/order_ajax.php",
        data: info,
        success: function(response){
            if(response){
                jssonarr=JSON.parse(response);
                str='<div class="row m-0">';
                for(i=0;i<jssonarr.length;i++){
                    str+='<div class="col-sm-12 py-2 border-bottom ord-status-col"><span class="small">'+jssonarr[i]["date"]+'</span><div>'+jssonarr[i]["activity"]+'</div> </div>'
                }
                str+='</div>';
                $("#"+divid).html(str)
            }
        }
    });
}
function trackReturn(shipmentId,divid){
    var info = {shipmentId:shipmentId,action:"trackReturnOrder"};
    $.ajax({
        type: "POST",
        url: "<?=SITEURL; ?>/ajax/order_ajax.php",
        data: info,
        success: function(response){
            if(response){
                jssonarr=JSON.parse(response);
                str='<div class="row m-0">';
                for(i=0;i<jssonarr.length;i++){
                    str+='<div class="col-sm-12 py-2 border-bottom"><span class="small">'+jssonarr[i]["date"]+'</span><div>'+jssonarr[i]["activity"]+'</div> </div>'
                }
                str+='</div>';
                $("#"+divid).html(str)
            }
        }
    });
 }
$(".track-ord-status").click(function(){ $(this).parents(".order-status-col").next().slideToggle(); });


function confirmCancel(shipmentId,txnId,itemId,canc_charge){
   confirm_alertbox(shipmentId,txnId,itemId,canc_charge)
}

function confirm_alertbox(id,txnId,itemId,canc_charge){
    $("#canc_charge").html(canc_charge);
    $("#confirm_popup").modal("show");
    $("#confirm_popup").find(".modal-dialog").addClass("order-cancel-popup");
    $("#confirm_message").html("Do You Want To Cancel This Item?");
    $("#confirm_ok").attr("onclick", "cancelOrder('"+id+"','"+txnId+"','"+itemId+"')");
}


function cancelOrder(shippingOrderId,txnId,itemId){
    cancel_reason = $("input[name='cancel_reason']:checked"). val();
    if(cancel_reason  == "" || cancel_reason == undefined ) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select Cancellation Reason").delay(1000).fadeOut();
    }else {
        var info ={shippingOrderId:shippingOrderId,txnId:txnId,itemId:itemId,cancel_reason:cancel_reason,action:"cencelOrder",canceled_by:'Admin'};
        $.ajax({
            type: "POST",
            url: "<?=SITEURL; ?>/ajax/order_ajax.php",
            data:info,
            success: function(response){
                $("#confirm_popup").modal("hide");
                if(response){
                    jsondata = JSON.parse(response);
                    if(jsondata['status']=="failed"){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Your Order "+jsondata['status']+"").delay(1000).fadeOut();
                    }else{
                        $("#inprocess").load(" #inprocess");
                        $("#wait_for_pickup").load(" #wait_for_pickup");
                        $("#Shipped").load(" #Shipped");
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Your Order "+jsondata['status']+" Successfully").delay(1000).fadeOut();
                    }
                }
            } 
        });
    }
}

</script>
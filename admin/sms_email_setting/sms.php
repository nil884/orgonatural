<?php include ("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Update SMS</title>
    <?php include('../commoncss.php'); ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $getblankTemp = selectQuery(SMSTEMPLATE,"count(id) as blanktemp","templateId=''");
     ?>
    <div class="main-panel">
        <div class="dashbody mail-temp-var">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2 border-bottom-0">
                    <div class="card-head-title">SMS Setting</div>
                    <div class="btn-actions-pane-right"><a href="sms_template.xlsx" download class="cc-display-none dn" >Excel</a><button type="button" class="btn btn-primary btn-sm mr-1 export" onclick="generateexcel()"> Export SMS Templates</button><a href='variabledata.php' class="btn btn-primary btn-sm mr-1"> Variable Details</a><button onclick="window.history.back();" class="btn btn-secondary btn-sm">Back</button></div>
                </div>
            </div>
            <?
            if($getblankTemp[0]['blanktemp']){
                ?> <div class="alert alert-danger"> <?=$getblankTemp[0]['blanktemp']; ?> SMS Templates do not have Template ID. <a href="<?=ADMINURL;?>sms_email_setting/templates.php">Click Here To Add Template ID</a> </div> <?
            }else{
               ?> <div class="alert alert-success"> All SMS Templates have Template ID. <a href="<?=ADMINURL;?>sms_email_setting/templates.php">Click Here To View/Update Template ID</a> </div> <?
            }
             ?>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div class="card-head-title">Vendor Registration</div></div>
                <div class="card-body p-0">
                    <div id="accordion">
                        <?php $getvendor_sms = selectQuery(SMSTEMPLATE,"*","type in ('Vendor Registration') ");
                        for($i=0;$i<count($getvendor_sms);$i++) { $row=$getvendor_sms[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#vendor_reg<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="vendor_reg<?php echo  $i; ?>" class="collapse" data-parent="#accordion"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div class="card-head-title">Vendor Profile Complete</div></div>
                <div class="card-body p-0">
                    <div id="accordion1">
                        <?php $getvendor_sms = selectQuery(SMSTEMPLATE,"*","type in ('Vendor Profile Complete') ");
                        for($i=0;$i<count($getvendor_sms);$i++) {$row=$getvendor_sms[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#vendor_profile<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="vendor_profile<?php echo $i; ?>" class="collapse" data-parent="#accordion1"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div class="card-head-title">Vendor Payment Successful</div></div>
                <div class="card-body p-0">
                    <div id="accordion2">
                        <?php $getvendor_sms = selectQuery(SMSTEMPLATE,"*","type in ('Vendor Payment Successful') ");
                        for($i=0;$i<count($getvendor_sms);$i++) {$row=$getvendor_sms[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#vendor_payment<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="vendor_payment<?php echo  $i; ?>" class="collapse" data-parent="#accordion2"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div class="card-head-title">Vendor Approved </div></div>
                <div class="card-body p-0">
                    <div id="accordion3">
                        <?php $getvendor_sms = selectQuery(SMSTEMPLATE,"*","type in ('Vendor Approved') ");
                        for($i=0;$i<count($getvendor_sms);$i++) {$row=$getvendor_sms[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#vendor_approve<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to'] ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="vendor_approve<?php echo  $i; ?>" class="collapse" data-parent="#accordion3"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div class="card-head-title">Vendor Plan Expire</div></div>
                <div class="card-body p-0">
                    <div id="accordion4">
                        <?php $getvendor_sms = selectQuery(SMSTEMPLATE,"*","type in ('Vendor Plan Expire') ");
                        for($i=0;$i<count($getvendor_sms);$i++) {$row=$getvendor_sms[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#vendor_plan_expire<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to'] ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="vendor_plan_expire<?php echo  $i; ?>" class="collapse" data-parent="#accordion4"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div class="card-head-title">Vendor Plan Upgrade After Current Plan  expiry</div></div>
                <div class="card-body p-0">
                    <div id="accordion5">
                        <?php $getvendor_sms = selectQuery(SMSTEMPLATE,"*","type in ('Vendor Plan Upgrade After Current Plan Expiry') ");
                        for($i=0;$i<count($getvendor_sms);$i++) { $row=$getvendor_sms[$i];?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#vendor_upgrade<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to'] ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId'];} ?> </div></div>
                            <div id="vendor_upgrade<?php echo  $i; ?>" class="collapse" data-parent="#accordion5"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div> 
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Login and Profile Update OTP</div>  
                <div class="card-body p-0">
                    <div id="accordion6">
                        <?php $get_login_sms = selectQuery(SMSTEMPLATE,"*","type in ('Vendor login OTP','Admin login OTP','Buyer login OTP','Admin Profile Update OTP') ");
                        for($i=0;$i<count($get_login_sms);$i++) {$row=$get_login_sms[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#login<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="login<?php echo  $i; ?>" class="collapse" data-parent="#accordion6"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Enquiry And  Contact</div> 
                <div class="card-body p-0">
                    <div id="accordion7">
                        <?php $contact = selectQuery(SMSTEMPLATE,"*","type in ('Enquiry on sms','Contact') ");
                        for($i=0;$i<count($contact);$i++) {$row=$contact[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#contact<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to'] ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="contact<?php echo $i; ?>" class="collapse" data-parent="#accordion7"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Buyer Registration</div>  
                <div class="card-body p-0">
                    <div id="accordion8">
                        <?php $buyer_reg = selectQuery(SMSTEMPLATE,"*","type in ('Buyer Registration') ");
                        for($i=0;$i<count($buyer_reg);$i++){$row=$buyer_reg[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#reg<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php  echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="reg<?php echo $i; ?>" class="collapse" data-parent="#accordion8"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">User Subscribe </div>  
                <div class="card-body p-0">
                    <div id="accordion9">
                        <?php $Subscribe = selectQuery(SMSTEMPLATE,"*","type in ('User Subscribe') ");
                        for($i=0;$i<count($Subscribe);$i++){$row=$Subscribe[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#Subscribe<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="Subscribe<?php echo $i; ?>" class="collapse" data-parent="#accordion9"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div>          
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Bulk Order</div>  
                <div class="card-body p-0">
                    <div id="accordion10">
                        <?php $bulk_order = selectQuery(SMSTEMPLATE,"*","type in ('Bulk order') ");
                        for($i=0;$i<count($bulk_order);$i++){ $row=$bulk_order[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#bulk_order<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="bulk_order<?php echo $i; ?>" class="collapse" data-parent="#accordion10"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div> 
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Order</div>  
                <div class="card-body p-0">
                    <div id="accordion11">
                        <?php $get_sms = selectQuery(SMSTEMPLATE,"*","type in ('order') ");
                        for($i=0;$i<count($get_sms);$i++){ $row=$get_sms[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#order<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php  echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="order<?php echo $i; ?>" class="collapse" data-parent="#accordion11"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div>
             <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Order Cancellation</div>  
                <div class="card-body p-0">
                    <div id="accordion12">
                        <?php $get_sms_order_cancel = selectQuery(SMSTEMPLATE,"*","type in ('Order Cancellation') ");
                        for($i=0;$i<count($get_sms_order_cancel);$i++){ $row=$get_sms_order_cancel[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#order_cancel<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php  echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="order_cancel<?php echo $i; ?>" class="collapse" data-parent="#accordion12"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Refund Initiated</div>  
                <div class="card-body p-0"> 
                    <div id="accordion13">
                        <?php $refund = selectQuery(SMSTEMPLATE,"*","type in ('Refund Initiated') ");
                        for($i=0;$i<count($refund);$i++){$row=$refund[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#refund<?php echo  $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="refund<?php echo $i; ?>" class="collapse" data-parent="#accordion13"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?>
                    </div> 
                </div> 
            </div> 
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Return Request</div>
                <div class="card-body p-0">
                    <div id="accordion14">
                        <?php $return_req = selectQuery(SMSTEMPLATE,"*","type in ('Return Request') ");
                        for($i=0;$i<count($return_req);$i++){$row=$return_req[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#return_req<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php  echo $row['sms_to'] ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="return_req<?php echo $i; ?>" class="collapse" data-parent="#accordion14"><div class="card-body"><?php echo $row['sms_text']; ?></div></div>
                        </div>
                        <?php } ?> 
                    </div> 
                </div> 
            </div>  
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Return Initiated</div>   
                <div class="card-body p-0"> 
                    <div id="accordion15">
                        <?php $return_ini = selectQuery(SMSTEMPLATE,"*","type in ('Return Initiated') ");
                        for($i=0;$i<count($return_ini);$i++){ $row=$return_ini[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#return_ini<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php  echo $row['sms_to']; ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="return_ini<?php echo $i; ?>" class="collapse" data-parent="#accordion15"><div class="card-body"><?php echo $row['sms_text']; ?></div> </div>
                        </div>
                        <?php } ?> 
                    </div> 
                </div> 
            </div>
            <div class="card mb-0 mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center cc-font-weight-5 h6">Return Cancel</div>   
                <div class="card-body p-0"> 
                    <div id="accordion16">
                        <?php $return_canc = selectQuery(SMSTEMPLATE,"*","type in ('Return Cancel') ");
                        for($i=0;$i<count($return_canc);$i++){ $row=$return_canc[$i]; ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header d-flex justify-content-between align-items-center"><a class="card-link" data-toggle="collapse" href="#return_canc<?php echo $i; ?>"><?php echo $row['type']; ?> - sms to <?php echo $row['sms_to'] ?></a><div class="btn-actions-pane-right"> <? if($row['templateId']!=""){?>Template ID - <?php echo $row['templateId']; } ?> </div></div>
                            <div id="return_canc<?php echo $i; ?>" class="collapse" data-parent="#accordion16">
                                <div class="card-body"><?php echo $row['sms_text']; ?></div>
                            </div>
                        </div>
                        <?php } ?> 
                    </div> 
                </div> 
            </div>
            <nav><ul class="pagination justify-content-end mb-0"></ul></nav>
        </div> 
        <?php include '../footer.php'; ?>
    </div>
</div>
<script>
function getPageList(totalPages, page, maxLength){
    if (maxLength < 5) throw "maxLength must be at least 5";
        function range(start, end){
        return Array.from(Array(end - start + 1), (_, i) => i + start);
    }
    var sideWidth = maxLength < 9 ? 1 : 2;
    var leftWidth = (maxLength - sideWidth * 2 - 3) >> 1;
    var rightWidth = (maxLength - sideWidth * 2 - 2) >> 1;
    if (totalPages <= maxLength){
        return range(1, totalPages);
    }
    if(page <= maxLength - sideWidth - 1 - rightWidth){
        return range(1, maxLength - sideWidth - 1).concat([0]).concat(range(totalPages - sideWidth + 1, totalPages));
    }
    if(page >= totalPages - sideWidth - 1 - rightWidth){
        return range(1, sideWidth).concat([0]).concat(
            range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages)
        );
    }
    return range(1, sideWidth)
    .concat([0]).concat(range(page - leftWidth, page + rightWidth)).concat([0]).concat(range(totalPages - sideWidth + 1, totalPages));
}

$(function(){
    var numberOfItems = $(".mail-temp-var .mail-com-varb").length;
    var limitPerPage = 4;
    var totalPages = Math.ceil(numberOfItems / limitPerPage);
    var paginationSize = 7;
    var currentPage;
    function showPage(whichPage){
        if (whichPage < 1 || whichPage > totalPages) return false;
        currentPage = whichPage;
        $(".mail-temp-var .mail-com-varb").hide().slice((currentPage - 1) * limitPerPage, currentPage * limitPerPage).show();
        $(".pagination li").slice(1, -1).remove();
        getPageList(totalPages, currentPage, paginationSize).forEach(item => {
            $("<li>").addClass("page-item " + (item ? "current-page " : "") + (item === currentPage ? "active " : "")).append($("<a>").addClass("page-link").attr({href: "javascript:void(0)"}).text(item || "...")).insertBefore("#next-page");
        });
        return true;
    }
    $(".pagination").append(
        $("<li>").addClass("page-item").attr({ id: "previous-page" }).append(
        $("<a>").addClass("page-link").attr({href: "javascript:void(0)"}).text("Prev")),
        $("<li>").addClass("page-item").attr({ id: "next-page" }).append(
            $("<a>").addClass("page-link").attr({href: "javascript:void(0)"}).text("Next")
        )
    );
    $(".mail-temp-var").show();
    showPage(1);
    $(document).on("click", ".pagination li.current-page:not(.active)", function(){
        return showPage(+$(this).text());
    });
    $("#next-page").on("click", function(){
        return showPage(currentPage + 1);
    });
    $("#previous-page").on("click", function(){
        return showPage(currentPage - 1);
    });
    $(".pagination").on("click", function(){
        $("html,body").animate({ scrollTop: 0 }, 0);
    });
});

function generateexcel(){
        $(".export").html("Exporting SMS Templates").attr("disabled",true);
            $.ajax({

                type: 'POST',
                url: 'export_sms.php',
                success: function(result) {
                   $(".dn")[0].click(); $(".export").html("Export SMS Templates").attr("disabled",false);
                }
            })
}
</script>
</body>
</html>
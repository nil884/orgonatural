<?php include ("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Update Email</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php'); ?>
    <div class="main-panel">
        <div class="dashbody mail-temp-var">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2 border-bottom-0">
                    <h2 class="card-head-title">Email Setting</h2>
                    <div class="btn-actions-pane-right"><a href='variabledata.php' target="_blank" class="btn btn-primary btn-sm mr-2">Variable Details</a><button onclick="window.history.back();" class="btn btn-secondary btn-sm">Back</button></div>
                </div>  
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Vendor Registration</h2></div>
                <div class="card-body p-0">
                    <div id="accordion">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Vendor Registration') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#vendor<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to']  ?></a></div>
                            <div id="vendor<?php echo $i ?>" class="collapse" data-parent="#accordion">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Vend_reg_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Vend_reg_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required>  <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Vend_reg_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Vend_reg_sub<?php echo $i ?>','Vend_reg_body<?php echo $i ?>','Vend_reg_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div> 
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Vendor Profile Complete</h2></div>
                <div class="card-body p-0">
                    <div id="accordion1">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Vendor Profile Complete') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#vendor_profile<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="vendor_profile<?php echo $i ?>" class="collapse" data-parent="#accordion1">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Vend_profile_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Vend_profile_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required>  <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary"  value="Submit" id="Vend_profile_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Vend_profile_sub<?php echo $i ?>','Vend_profile_body<?php echo $i ?>','Vend_profile_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Vendor Payment Successful</h2></div>
                <div class="card-body p-0">
                    <div id="accordion2">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Vendor Payment Successful') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#vendor_payment<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php  echo $get_mail[$i]['mail_to'] ?></a>
                            </div>
                            <div id="vendor_payment<?php echo  $i ?>" class="collapse" data-parent="#accordion2">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Vend_payment_sub<?php echo $i ?>'>
                                            </div>
                                            <textarea class="form-control summernote" id="Vend_payment_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required> <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Vend_payment_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Vend_payment_sub<?php echo $i ?>','Vend_payment_body<?php echo $i ?>','Vend_payment_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Vendor Approved</h2></div>
                <div class="card-body p-0">
                    <div id="accordion3">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Vendor Approved') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#vendor_approve_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="vendor_approve_div<?php echo $i ?>" class="collapse" data-parent="#accordion3">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Vend_approve_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Vend_approve_body<?php echo $i ?>" maxlength="5000"  placeholder="Enter information(Max 500 Characters)" required> <?php  echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Vend_approve_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Vend_approve_sub<?php echo $i ?>','Vend_approve_body<?php echo $i ?>','Vend_approve_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Vendor Plan Expiring On Tomorrow</h2></div>
                <div class="card-body p-0">
                    <div id="accordion19">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Vendor Plan Expiry On Tomorrow') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#vendor_plan_exp_tmrw_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="vendor_plan_exp_tmrw_div<?php echo  $i ?>" class="collapse" data-parent="#accordion19">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Vend_plan_exp_tmrw_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Vend_plan_exp_tmrw_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required> <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Vend_plan_exp_tmrw_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Vend_plan_exp_tmrw_sub<?php echo $i ?>','Vend_plan_exp_tmrw_body<?php echo $i ?>','Vend_plan_exp_tmrw_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Vendor Plan Expire</h2></div>
                <div class="card-body p-0">
                    <div id="accordion4">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Vendor Plan Expire') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#vendor_plan_exp_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="vendor_plan_exp_div<?php echo  $i ?>" class="collapse" data-parent="#accordion4">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Vend_plan_exp_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Vend_plan_exp_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required> <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Vend_plan_exp_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Vend_plan_exp_sub<?php echo $i ?>','Vend_plan_exp_body<?php echo $i ?>','Vend_plan_exp_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Vendor Plan Upgrade After Current Plan expiry</h2></div>
                <div class="card-body p-0">
                    <div id="accordion5">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Vendor Plan Upgrade After Current Plan Expiry') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#vendor_plan_upg_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a>
                            </div>
                            <div id="vendor_plan_upg_div<?php echo $i ?>" class="collapse" data-parent="#accordion5">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Vend_plan_upg_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Vend_plan_upg_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required> <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Vend_plan_upg_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Vend_plan_upg_sub<?php echo $i ?>','Vend_plan_upg_body<?php echo $i ?>','Vend_plan_upg_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>   
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Buyer Registration</h2></div>
                <div class="card-body p-0">
                    <div id="accordion6">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Buyer Registration') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#buyer_reg_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="buyer_reg_div<?php echo $i ?>" class="collapse" data-parent="#accordion6">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='buyer_reg_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="buyer_reg_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required> <?php  echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="buyer_reg_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','buyer_reg_sub<?php echo $i ?>','buyer_reg_body<?php echo $i ?>','buyer_reg_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">User Subscribe</h2></div>
                <div class="card-body p-0">
                    <div id="accordion7">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('User Subscribe') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#buyer_subscribe_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php  echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="buyer_subscribe_div<?php echo $i ?>" class="collapse" data-parent="#accordion7">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='buyer_subscribe_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="buyer_subscribe_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="buyer_subscribe_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','buyer_subscribe_sub<?php echo $i ?>','buyer_subscribe_body<?php echo $i ?>','buyer_subscribe_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Reset Password</h2></div>
                    <div id="accordion8">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Reset Password') ");
                        for($i=0;$i<count($get_mail);$i++){ ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Reset_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a>
                            </div>
                            <div id="Reset_div<?php echo $i ?>" class="collapse" data-parent="#accordion8">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Reset_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Reset_body<?php echo $i ?>" maxlength="5000"  placeholder="Enter information(Max 500 Characters)" required> <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Reset_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Reset_sub<?php echo $i ?>','Reset_body<?php echo $i ?>','Reset_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Enquiry on sms</h2></div>
                <div class="card-body p-0">
                    <div id="accordion9">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Enquiry on sms') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#enq_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="enq_div<?php echo $i ?>" class="collapse" data-parent="#accordion9">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='enq_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="enq_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="enq_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','enq_sub<?php echo $i ?>','enq_body<?php echo $i ?>','enq_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Contact</h2></div>
                <div class="card-body p-0">
                    <div id="accordion10">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Contact') ");
                        for($i=0;$i<count($get_mail);$i++){ ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#contact_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to']  ?></a>
                            </div>
                            <div id="contact_div<?php echo  $i ?>" class="collapse" data-parent="#accordion10">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='contact_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="contact_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="contact_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','contact_sub<?php echo $i ?>','contact_body<?php echo $i ?>','contact_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Bulk order</h2></div>
                <div class="card-body p-0">
                    <div id="accordion11">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Bulk order') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#bulk_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="bulk_div<?php echo  $i ?>" class="collapse" data-parent="#accordion11">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='bulk_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="bulk_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required>  <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="bulk_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','bulk_sub<?php echo $i ?>','bulk_body<?php echo $i ?>','bulk_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Wishlist Quotation</h2></div>
                <div class="card-body p-0">
                    <div id="accordion12">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Wishlist Quotation') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Wishlist_div<?php echo $i ?>"> <?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a></div>
                            <div id="Wishlist_div<?php echo $i ?>" class="collapse" data-parent="#accordion12">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Wishlist_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Wishlist_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required>  <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Wishlist_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Wishlist_sub<?php echo $i ?>','Wishlist_body<?php echo $i ?>','Wishlist_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Order</h2></div>
                <div class="card-body p-0">
                    <div id="accordion13">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Order') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Order_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a>
                            </div>
                            <div id="Order_div<?php echo $i ?>" class="collapse" data-parent="#accordion13">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Order_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Order_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Order_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Order_sub<?php echo $i ?>','Order_body<?php echo $i ?>','Order_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Order Cancellation</h2></div>
                <div class="card-body p-0"> 
                    <div id="accordion19">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Order Cancellation') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Order_cancel_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a>
                            </div> 
                            <div id="Order_cancel_div<?php echo $i ?>" class="collapse" data-parent="#accordion19">
                                <div class="border-bottom">
                                    <form action="#"> 
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Order_cancel_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Order_cancel_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Order_cancel_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Order_cancel_sub<?php echo $i ?>','Order_cancel_body<?php echo $i ?>','Order_cancel_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Refund Initiated</h2></div>
                <div class="card-body p-0"> 
                    <div id="accordion15">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Refund Initiated') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Refund_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a>
                            </div> 
                            <div id="Refund_div<?php echo $i ?>" class="collapse" data-parent="#accordion15">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Refund_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Refund_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Refund_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Refund_sub<?php echo $i ?>','Refund_body<?php echo $i ?>','Refund_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Return Request</h2></div>
                <div class="card-body p-0"> 
                    <div id="accordion16">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Return Request') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Return_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a>
                            </div> 
                            <div id="Return_div<?php echo $i ?>" class="collapse" data-parent="#accordion16">
                                <div class="border-bottom">
                                    <form action="#"> 
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Return_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Return_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Return_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Return_sub<?php echo $i ?>','Return_body<?php echo $i ?>','Return_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Return Initiated</h2></div>
                <div class="card-body p-0"> 
                    <div id="accordion17">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Return Initiated') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Return_initiate_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to'] ?></a>
                            </div> 
                            <div id="Return_initiate_div<?php echo $i ?>" class="collapse" data-parent="#accordion17">
                                <div class="border-bottom">
                                    <form action="#"> 
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Return_initiate_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Return_initiate_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Return_initiate_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Return_initiate_sub<?php echo $i ?>','Return_initiate_body<?php echo $i ?>','Return_initiate_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Return Cancel</h2></div>
                <div class="card-body p-0"> 
                    <div id="accordion18">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Return Cancel') ");
                        for($i=0;$i<count($get_mail);$i++){ ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#Return_cancel_div<?php echo $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php  echo $get_mail[$i]['mail_to'] ?></a>
                            </div> 
                            <div id="Return_cancel_div<?php echo $i ?>" class="collapse" data-parent="#accordion18">
                                <div class="border-bottom">
                                    <form action="#"> 
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='Return_cancel_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="Return_cancel_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required><?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="Return_cancel_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','Return_cancel_sub<?php echo $i ?>','Return_cancel_body<?php echo $i ?>','Return_cancel_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                        <?php } ?>
                    </div> 
                </div>
            </div>
            <div class="card mail-com-varb">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Requirement Request</h2></div>
                <div class="card-body p-0">
                    <div id="accordion14">
                        <?php $get_mail = selectQuery(EMAILTEMPLATE,"*","type in ('Requirement Request') ");
                        for($i=0;$i<count($get_mail);$i++) { ?>
                        <div class="card rounded-0 mb-0 border-top-0 border-bottom-0 border-left-0 border-right-0">
                            <div class="card-header"><a class="card-link" data-toggle="collapse" href="#req_div<?php echo  $i ?>"><?php echo $get_mail[$i]['type']; ?> - Mail to <?php echo $get_mail[$i]['mail_to']  ?></a></div>
                            <div id="req_div<?php echo  $i ?>" class="collapse" data-parent="#accordion14">
                                <div class="border-bottom">
                                    <form action="#">
                                        <div class="card-body pb-1">
                                            <div class="form-group"><input type="text" class="form-control" value="<?php echo $get_mail[$i]['subject']; ?>" id='req_sub<?php echo $i ?>'></div>
                                            <textarea class="form-control summernote" id="req_body<?php echo $i ?>" maxlength="5000" placeholder="Enter information(Max 500 Characters)" required> <?php echo $get_mail[$i]['body']; ?></textarea>
                                        </div>
                                        <div class="card-footer text-right py-2"><input type="button" class="btn btn-primary" value="Submit" id="req_btn<?php echo $i ?>" onclick="save('<?php echo $get_mail[$i]['type']; ?>','<?php echo $get_mail[$i]['mail_to']; ?>','req_sub<?php echo $i ?>','req_body<?php echo $i ?>','req_btn<?php echo $i ?>')"></div>
                                    </form>
                                </div>
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
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script>$(function(){ $('.summernote').summernote({height: 200}); });
function save(type,mail_to,subject,body,btn){
    var type = type;
    var mailto = mail_to;
    var subject = $("#"+subject).val();
    var body = $("#"+body).val();
    if(subject == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Enter Subject").delay(3000).fadeOut();
    } else if(body == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Enter Body").delay(3000).fadeOut();
    } else {
        $("#"+btn).prop("disabled",true);
        var info = {type:type,mailto:mailto,subject:subject,body:body,action:'Update_mail'};
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response) {
                $("#"+btn).prop("disabled",false);
                    if (response == 1) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details Updated Successfully").delay(3000).fadeOut();
                } if (response == 0) {
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Try after some time").delay(3000).fadeOut();
                }
            }
        });
    }
} 
</script>
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
    $(document).on("click", ".pagination li.current-page:not(.active)", function(){ return showPage(+$(this).text()); });
    $("#next-page").on("click", function(){ return showPage(currentPage + 1); });
    $("#previous-page").on("click", function(){ return showPage(currentPage - 1); });
    $(".pagination").on("click", function(){ $("html,body").animate({ scrollTop: 0 }, 0); });
});    
</script>
</body>
</html>
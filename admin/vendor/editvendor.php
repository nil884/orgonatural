<?php include("../../includes/configuration.php");
    $vendor_id = base64_decode($_REQUEST['vendor']);
    $getvendor = selectQuery(VENDOR,"*","dealer_id=".$vendor_id);
    if($getvendor[0]['updatesviewbyadmin']=="Pending"){
        $changedarr = explode(",",$getvendor[0]['lastupdated']);
        $updatedata = array( 'updatesviewbyadmin'=>"Viewed" );
        $up = updateQuery(VENDOR,$updatedata,"dealer_id=".$vendor_id);
    }
    $getcurrplan = selectQuery(VENDORPLANSELECTED,"*","sel_id=".$getvendor[0]['plan']);
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Vendor Profile</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <form action="#" method="post" id="dealerform" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Personal Details</h2></div><div class="btn-actions-pane-right">
                    <a href="editprofile.php?vendor=<?php echo base64_encode($getvendor[0]['dealer_key']); ?>" class="btn btn-primary btn-sm mr-1"><i class="fa fa-user" aria-hidden="true"></i> Edit Profile</a><button type="button" onclick="goBack()" class="btn btn-secondary btn-sm">Back</button>   
                    </div></div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <input type="hidden" name="randnum" id="randnum" value=<?php echo $createnum; ?> />
                            <input type="hidden" name="dealerid" id="dealerid" value=<?php echo $s; ?> />
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 mb-2">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Name</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php echo $getvendor[0]['dealer_name']; ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 mb-2">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Profile ID</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if($getvendor[0]['nickname']!=""){echo $getvendor[0]['nickname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-sm-6 col-md-6 col-lg-6 mb-2">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Primary Mobile No</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if($getvendor[0]['personalcontactno']!=""){echo $getvendor[0]['personalcontactno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-sm-6 col-md-6 col-lg-6 mb-2">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Alt Mobile No</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if($getvendor[0]['extracontactno']!=""){echo $getvendor[0]['extracontactno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-sm-6 col-md-12 col-lg-6 mb-2">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Email</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if($getvendor[0]['email']!=""){echo $getvendor[0]['email'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                             <div class="form-group col-sm-6 col-sm-6 col-md-12 col-lg-6 mb-2">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Disbursement Days</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if($getvendor[0]['disbursement_cycle_days']!=""){echo $getvendor[0]['disbursement_cycle_days'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row"> <div class="form-group col-sm-12 col-sm-12 col-md-12 col-lg-6 mb-2"><div class="row">
                            <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Automatically Mark Disbursement Payment As Paid</label>
                            <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?=($getvendor[0]['disburstment_auto_pay']==1?"Yes":"No"); ?> </div>
                        </div></div>  </div>
                    </div>
                </div>  
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Vendor Registration Details</h2></div></div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="form-group col-sm-6 col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <div class="col-4 col-sm-12 col-md-12 col-lg-5 accdates cc-font-weight-5">Registered On</div>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php echo date("d-m-Y", strtotime($getvendor[0]['insdate'])); ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <div class="col-4 col-sm-12 col-md-12 col-lg-5 accdates cc-font-weight-5">Profile Status</div>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if($getvendor[0]['isComplete']){?><span class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Completed</span> (<?php echo date("d-m-Y", strtotime($getvendor[0]['completeon'])); ?>) <?} else {?><span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Incompleted</span><?} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <div class="col-4 col-sm-12 col-md-12 col-lg-5 accdates cc-font-weight-5">Account Status</div>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if($getvendor[0]['isApproved']){?><span class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Approved</span> (<?php echo date("d-m-Y", strtotime($getvendor[0]['approveon'])); ?>) <?} else {?><span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Pending</span> <?} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <div class="col-4 col-sm-12 col-md-12 col-lg-5 accdates"><b>Payment Status</b></div>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7 text-secondary"><?php if( $getvendor[0]['plan']!=""){if($getcurrplan[0]['payment_status']=="Received"){?><span class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Received</span><?}else{?><span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Pending</span><?}}else{echo "NA";} ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($getcurrplan[0]['plan'] != ""){ ?> 
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                        <div><h2 class="card-head-title">Plan Details</h2></div>
                        <div class="btn-actions-pane-right"><?php if($getcurrplan[0]['plan'] != ""){ ?><a href="plandetails.php?vendor=<?php echo base64_encode($vendor_id); ?>" class="btn btn-primary btn-sm">View & Edit Payments</a><?php } ?></div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 <?php echo $class12; ?>">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Selected Plan</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7"><?php echo $getcurrplan[0]['plan']; ?></div>
                                </div>
                            </div>
                            <?php if($getcurrplan[0]['plan']!="") { ?>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 <?php echo $class12; ?>">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Amount</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7">
                                        <?php if($getcurrplan[0]['price']!="NULL"){?><span class="text-success"> <i class="fa fa-inr"></i> <?php echo $getcurrplan[0]['price'];?>/-&nbsp;</span><?}else{?><span class="text-danger">Pending</span><? } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 <?php echo $class12; ?>">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Payment Status</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7"><?php if($getcurrplan[0]['payment_status']=="Received"){?><span class="text-success">Paid</span><?}else{?><span class="text-danger">Pending</span><?} ?></div>
                                </div>
                            </div>
                            <?php if($getcurrplan[0]['payment_status']=="Received"){ ?>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 <?php echo $class12; ?>">
                                <div class="row">
                                    <label class="col-4 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Plan Ends On</label>
                                    <div class="col-8 col-sm-12 col-md-12 col-lg-7"><?php echo date("d-m-Y", strtotime($getcurrplan[0]['plan_to'])); ?></div>
                                </div>
                            </div>
                            <? } } ?>
                        </div>
                    </div>
                </div>                    
                <?php } ?>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Business Details</h2></div></div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Company Name</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['shopname']!=""){echo $getvendor[0]['shopname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Registration No</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['regno']!=""){echo $getvendor[0]['regno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Office Mobile</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['officecontactno']!=""){echo $getvendor[0]['officecontactno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Communication Mail</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['communicationemail']!=""){echo $getvendor[0]['communicationemail'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">PAN No</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['panno']!=""){echo $getvendor[0]['panno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">TAN No</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['tanno']!=""){echo $getvendor[0]['tanno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">VAT/TIN/GST No</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['vatno']!=""){echo $getvendor[0]['vatno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Country</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['country']!=""){echo $getvendor[0]['country'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">State</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['state']!=""){echo $getvendor[0]['state'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">City</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['city']!=""){echo $getvendor[0]['city'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Office Address</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary">
                                        <!-- <?php if($getvendor[0]['pickupadress']!=""){echo $getvendor[0]['pickupadress']." - ".$getvendor[0]['pin'];}else{echo "Not Defined";} ?>-->
                                        <?php if($getvendor[0]['officeadress']!=""){echo $getvendor[0]['officeadress'];}else{echo "[Not Defined]";} ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Locality</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['locality']!=""){echo $getvendor[0]['locality'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">PIN Code</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['pin']!=""){echo $getvendor[0]['pin'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Banking Details</h2></div></div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Bank Name</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['bnkname']!=""){echo $getvendor[0]['bnkname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Branch Name</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['branchname']!=""){echo $getvendor[0]['branchname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Beneficiary Name</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['beneficiary']!=""){echo $getvendor[0]['beneficiary'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Account No</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['acntno']!=""){echo $getvendor[0]['acntno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">Account Type</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['acnttype']!=""){echo $getvendor[0]['acnttype'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <div class="row">
                                    <label class="col-4 col-md-12 col-lg-5 control-label cc-font-weight-5">RTGS/NEFT IFSC</label>
                                    <div class="col-8 col-md-12 col-lg-7 col-sm-12 text-secondary"><?php if($getvendor[0]['IFSCcode']!=""){echo $getvendor[0]['IFSCcode'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                           
                <div class="imgonmbl">
                    <div class="card mb-0">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Documents</h2></div></div>                                    
                        <div class="card-body documentattch pb-0">
                            <h6 class="cc-font-weight-5 mb-3">Personal KYC Documents</h6>
                            <div class="row">
                                <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                    <div class="mb-2">PHOTO</div>
                                    <div class="documentthumb">
                                        <?php if($getvendor[0]['otherdoc']!=""){ ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['otherdoc']; ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['otherdoc']; ?>" alt="<?php echo $getvendor[0]['otherdoc']; ?>" class="img-fluid"/></a>
                                        <? } else { ?>
                                        <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                    <div class="mb-2">PAN</div>
                                    <div class="documentthumb">
                                        <?php if($getvendor[0]['pandoc']!="") { ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['pandoc']; ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['pandoc']; ?>" alt="<?php echo $getvendor[0]['pandoc']; ?>" class="img-fluid"/></a>
                                        <? } else { ?>
                                        <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6 class="cc-font-weight-5 mb-3">Business KYC Documents</h6>
                            <div class="row">
                                <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                    <div class="mb-2">REGISTRATION</div>
                                    <div class="documentthumb">
                                        <?php if($getvendor[0]['regdoc']!="") { ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['regdoc']; ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['regdoc']; ?>" alt="<?php echo $getvendor[0]['regdoc']; ?>" class="img-fluid"/></a>
                                        <? } else { ?>
                                        <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3 pl-0 pl-sm-3">
                                    <div class="mb-2">TAN</div>
                                     <div class="documentthumb">
                                        <?php if($getvendor[0]['tandoc']!="") { ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['tandoc']; ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['tandoc']; ?>" alt="<?php echo $getvendor[0]['tandoc']; ?>" class="img-fluid"/></a>
                                        <? } else{ ?>
                                        <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                    <div class="mb-2">VAT/TIN/CST</div>
                                    <div class="documentthumb">
                                        <?php if($getvendor[0]['vatdoc']!="") { ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['vatdoc']; ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['vatdoc']; ?>" alt="<?php echo $getvendor[0]['vatdoc']; ?>" class="img-fluid"/></a>
                                        <? } else{ ?>
                                        <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3 pl-0 pl-sm-3">
                                    <div class="mb-2">CANCELLED CHEQUE</div>
                                    <div class="documentthumb">
                                        <?php if($getvendor[0]['chequedoc']!=""){ ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['chequedoc']; ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['chequedoc']; ?>" alt="<?php echo $getvendor[0]['chequedoc']; ?>" class="img-fluid"/></a>
                                        <? } else { ?>
                                        <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php include '../footer.php' ?>
    </div>
</div>
</body>
</html>
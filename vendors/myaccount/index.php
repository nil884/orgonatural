<?php include("../../includes/configuration.php");
$getvendor = selectQuery(VENDOR,"*","dealer_id=".$_SESSION['seller']);
$getcurrplan = selectQuery(VENDORPLANSELECTED,"*","sel_id=".$getvendor[0]['plan']); ?>
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
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center border-bottom-0 py-2">
                    <div><h2 class="card-head-title mb-2 mb-sm-0">Edit Profile</h2></div>
                    <div class="btn-actions-pane-right"><a href="editprofile.php?sid= <?php echo base64_encode($getvendor[0]['dealer_key']); ?>" class="btn btn-primary btn-sm"><i class="fa fa-user" aria-hidden="true"></i> Edit Profile</a>
                    <a href="chngpwd.php" class="btn btn-secondary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Change Password</a></div>
                </div>
            </div>
            <div>
                <form action="#" method="post" id="dealerform" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Plan Details</h2></div></div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="form-group mb-2 row col-sm-6 col-md-6 <?php echo $class12; ?>">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Selected Plan</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php echo $getcurrplan[0]['plan']; ?></div>
                                </div>
                                <?php if($getcurrplan[0]['plan']!="") { ?>
                                <div class="form-group mb-2 row col-sm-6 col-md-6 <?php echo $class12; ?>">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Amount</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getcurrplan[0]['price']!="NULL"){?><span><i class="fa fa-inr"></i> <?php echo $getcurrplan[0]['price'];?>/-&nbsp;</span><?}else{?><span class="text-danger">Pending</span><?} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-sm-6 col-md-6 <?php echo $class12; ?>">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Payment Status</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getcurrplan[0]['payment_status']=="Received"){?><span class="text-success">Paid</span><?}else{?><span class="text-danger">Pending</span><? } ?></div>
                                </div>
                                <?php if($getcurrplan[0]['payment_status']=="Received"){ ?>
                                <div class="form-group mb-2 row col-sm-6 col-md-6 <?php echo $class12; ?>">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Plan Ends On</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php echo date("d-m-Y", strtotime($getcurrplan[0]['plan_to'])); ?></div>
                                </div>
                                <? } } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Personal Details</h2></div></div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <input type="hidden" name="randnum" id="randnum" value=<?php echo $createnum; ?> />
                                <input type="hidden" name="dealerid" id="dealerid" value=<?php echo $s; ?> />
                                <div class="form-group mb-2 row col-sm-6 col-md-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Name</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php echo $getvendor[0]['dealer_name']; ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5"> Profile ID </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['nickname']!=""){echo $getvendor[0]['nickname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Primary Mobile No </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['personalcontactno']!=""){echo $getvendor[0]['personalcontactno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Alt Mobile No</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['extracontactno']!=""){echo $getvendor[0]['extracontactno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Email</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['email']!=""){echo $getvendor[0]['email'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                    </div>                      
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Business Details</h2></div></div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6" >
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5"> Company Name</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['shopname']!=""){echo $getvendor[0]['shopname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Registration No</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['regno']!=""){echo $getvendor[0]['regno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Office Mobile </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['officecontactno']!=""){echo $getvendor[0]['officecontactno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Communication Mail </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['communicationemail']!=""){echo $getvendor[0]['communicationemail'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">PAN No</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['panno']!=""){echo $getvendor[0]['panno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">TAN No</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['tanno']!=""){echo $getvendor[0]['tanno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">VAT/TIN/GST No</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['vatno']!=""){echo $getvendor[0]['vatno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6" >
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Country</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['shopname']!=""){echo $getvendor[0]['country'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6" >
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">State</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['shopname']!=""){echo $getvendor[0]['state'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6" >
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">City</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['shopname']!=""){echo $getvendor[0]['city'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-12">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Office Address </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7">
                                        <!-- <?php if($getvendor[0]['pickupadress']!=""){echo $getvendor[0]['pickupadress']." - ".$getvendor[0]['pin'];}else{echo "Not Defined";} ?>-->
                                        <?php if($getvendor[0]['officeadress']!=""){echo $getvendor[0]['officeadress'];}else{echo "[Not Defined]";} ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-12">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Locality</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['locality']!=""){echo $getvendor[0]['locality'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-12">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">PIN Code </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['pin']!=""){echo $getvendor[0]['pin'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Banking Details</h2></div></div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Bank Name </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['bnkname']!=""){echo $getvendor[0]['bnkname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5" >Branch Name </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['branchname']!=""){echo $getvendor[0]['branchname'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5" >Beneficiary Name </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['beneficiary']!=""){echo $getvendor[0]['beneficiary'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Account No </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['acntno']!=""){echo $getvendor[0]['acntno'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">Account Type </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['acnttype']!=""){echo $getvendor[0]['acnttype'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                                <div class="form-group mb-2 row col-md-6 col-sm-6">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-5 control-label cc-font-weight-5">RTGS/NEFT IFSC </label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-7"><?php if($getvendor[0]['IFSCcode']!=""){echo $getvendor[0]['IFSCcode'];}else{echo "[Not Defined]";} ?></div>
                                </div>
                            </div>
                        </div>
                    </div>                           
                    <div class="imgonmbl">
                        <div class="card mb-0">
                            <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Documents</h2></div></div>                                    
                            <div class="card-body documentattch pb-2">
                                <h6 class="cc-font-weight-5 mb-3">Personal KYC Documents</h6>
                                <div class="row">
                                    <div class="col-6 col-sm-6 col-md-3 col-sm-4 ven-doc-col mb-3">
                                        <div class="mb-2">PHOTO</div>
                                        <div class="documentthumb">
                                            <?php if($getvendor[0]['otherdoc']!=""){ ?>
                                            <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['otherdoc']; ?>" target="_blank"><img class="img-fluid img-thumbnail" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['otherdoc']; ?>" alt="<?php echo $getvendor[0]['otherdoc']; ?>" /></a>
                                            <? } else { ?>
                                            <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-3 col-sm-4 ven-doc-col mb-3">
                                        <div class="mb-2">PAN</div>
                                        <div class="documentthumb">
                                            <?php if($getvendor[0]['pandoc']!=""){ ?>
                                            <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['pandoc']; ?>" target="_blank"><img class="img-fluid img-thumbnail" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['pandoc']; ?>" alt="<?php echo $getvendor[0]['pandoc']; ?>" /></a>
                                            <? } else { ?>
                                            <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="cc-font-weight-5 mb-3">Business KYC Documents</h6>
                                <div class="row">
                                    <div class="col-6 col-sm-6 col-md-3 col-sm-4 ven-doc-col mb-3">
                                        <div class="mb-2">REGISTRATION</div>
                                        <div class="documentthumb">
                                            <?php if($getvendor[0]['regdoc']!=""){ ?>
                                            <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['regdoc']; ?>" target="_blank"><img class="img-fluid img-thumbnail" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['regdoc']; ?>" alt="<?php echo $getvendor[0]['regdoc']; ?>" /></a>
                                            <? } else { ?>
                                            <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-3 col-sm-4 ven-doc-col mb-3 pl-0 pl-sm-3">
                                        <div class="mb-2">TAN</div>
                                         <div class="documentthumb">
                                            <?php if($getvendor[0]['tandoc']!=""){ ?>
                                            <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['tandoc']; ?>" target="_blank"><img class="img-fluid img-thumbnail" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['tandoc']; ?>" alt="<?php echo $getvendor[0]['tandoc']; ?>" /></a>
                                            <? } else { ?>
                                            <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-3 col-sm-4 ven-doc-col mb-3">
                                        <div class="mb-2">VAT/TIN/CST</div>
                                        <div class="documentthumb">
                                            <?php if($getvendor[0]['vatdoc']!=""){ ?>
                                            <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['vatdoc']; ?>" target="_blank"><img class="img-fluid img-thumbnail" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['vatdoc']; ?>" alt="<?php echo $getvendor[0]['vatdoc']; ?>"/></a>
                                            <? } else { ?>
                                            <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-3 col-sm-4 ven-doc-col mb-3 pl-0 pl-sm-3">
                                        <div class="mb-2">CANCELLED CHEQUE</div>
                                        <div class="documentthumb">
                                            <?php if($getvendor[0]['chequedoc']!=""){ ?>
                                            <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['chequedoc']; ?>" target="_blank"><img class="img-fluid img-thumbnail" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['chequedoc']; ?>" alt="<?php echo $getvendor[0]['chequedoc']; ?>" /></a>
                                            <? } else { ?>
                                            <div class="notfound"><img alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php include '../footer.php' ?>
    </div>
</div>
</body>
</html>
<?php include("../../includes/configuration.php");
    $id = base64_decode($_REQUEST['sid']);
    $getvendor = selectQuery(VENDOR,"*","dealer_key='".$id."'");
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Edit Profile</title>
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
                    <div><h2 class="card-head-title">Edit Profile</h2></div>
                    <div class="btn-actions-pane-right"><button onclick="goBack()" class='btn btn-secondary btn-sm'>Back</button></div>
                </div>
            </div>
            <form action="#" method="post" id="dealerform" class="form-horizontal" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Personal Details</h5></div></div>
                    <div class="card-body pb-2">
                        <input type="hidden" name="randnum" id="randnum" value=<?php echo $createnum; ?> />
                        <input type="hidden" name="dealerid" id="dealerid" value=<?php echo $getvendor[0]['dealer_id']; ?> />
                        <input type="hidden" name="kycsts" id="kycsts" value=<?php echo $getvendor[0]['isKYCUploaded']; ?> />
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Name</label>
                                    <input type="text" name="name1" id="name1" class="form-control text-capitalize" onkeyup="charcheck('name1')" maxlength="30" tabindex="1" value="<?php echo $getvendor[0]['dealer_name']; ?>"/>
                                    <div id="name1err" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Email</label>
                                    <input type="text" name="email" id="email" onblur="mailchk('email')" class="form-control" maxlength="50" tabindex="2" autocomplete="off" value="<?php echo $getvendor[0]['email']; ?>" />
                                    <div id="emailerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Primary Mobile</label>
                                    <input type="text" name="contactNop" id="contactNop" class="form-control numberinput1"  onkeyup="numbercheck('contactNop')" maxlength="10" tabindex="3" autocomplete="off" value="<?php echo $getvendor[0]['personalcontactno']; ?>" />
                                    <div id="primoberr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Alt Mobile </label>
                                    <input type="text" name="contactNot" id="contactNot" onkeyup="check1('contactNot')" onkeypress="numbercheck('contactNot')" value="<?php echo $getvendor[0]['extracontactno']; ?>" class="form-control numberinput2" maxlength="10" tabindex="4" autocomplete="off" placeholder="Enter alt mobile"/>
                                    <div id="altmoberr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <label  class="cc-mandatary-field"> Profile ID</label>
                                <input type="text" name="name2" id="name2" onblur="chknick()" onkeyup="check2('name2')" class="form-control"  maxlength="30" tabindex="5" value="<?php echo $getvendor[0]['nickname']; ?>" disabled=""/>
                                <div id="profileerr" class="mt-1"></div>
                                <div class="nickmsg mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Business Details</h2></div></div>
                    <div class="card-body pb-2">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Company Name</label>
                                    <input type="text" name="shopname" id="shopname" class="form-control text-capitalize" maxlength="100" tabindex="6" placeholder="Enter company name" value="<?php echo $getvendor[0]['shopname']; ?>"/>
                                    <div id="companyerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Country</label>
                                    <input type="text" name="country" id="country"  onkeyup="check4('country')" class="form-control text-capitalize" maxlength="20" tabindex="7" placeholder="Enter country" value="<?php echo $getvendor[0]['country']; ?>"/>
                                    <div id="countryerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Office Address</label>
                                    <input class="form-control text-capitalize" name="Adress" id="Adress" tabindex="8" maxlength="300" placeholder="Enter office address" value="<?php echo $getvendor[0]['officeadress']; ?>"/>
                                    <div id="officeadderr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">PIN Code</label>
                                    <input type="text" name="Pin" id="Pin" onkeyup="check8('Pin')"  onchange="getpincode()" class="form-control" minlength="6" maxlength="6" autocomplete="off" tabindex="9" placeholder="Enter pincode" value="<?php echo $getvendor[0]['pin']; ?>" />
                                    <div id="pinerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field"> State</label>
                                    <input type="text" name="state" id="state" onkeyup="check5('state')" class="form-control text-capitalize" maxlength="30" tabindex="10" placeholder="Enter state" value="<?php echo $getvendor[0]['state']; ?>" disabled/>
                                    <div id="stateerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">City</label>
                                    <input type="text" name="city" id="city" onkeyup="check6('city')" class="form-control text-capitalize" maxlength="30" tabindex="11" placeholder="Enter city" value="<?php echo $getvendor[0]['city']; ?>" disabled/>
                                    <div id="cityerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Locality</label>
                                    <input type="text" name="locality" id="locality" class="form-control text-capitalize" maxlength="20" autocomplete="off" tabindex="12" placeholder="Enter Locality" value="<?php echo $getvendor[0]['locality']; ?>" onkeyup="localityvalidate('locality')"/>
                                    <div id="localityerr" class="mt-1"></div>
                                </div>
                            </div>                            
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Office Email</label>
                                    <input type="text" name="officeemail" id="officeemail" onkeyup="check9()" onblur="mailchk('officeemail')" class="form-control" maxlength="50" tabindex="13" autocomplete="off" placeholder="Enter office email" value="<?php echo $getvendor[0]['communicationemail']; ?>"/>
                                    <div id="offemailerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Office Mobile</label>
                                    <input type="text" name="officemob" id="officemob" onkeyup="check10()" onkeypress="numbercheck('officemob')" class="form-control" maxlength="10" tabindex="14" autocomplete="off" placeholder="Enter office mobile" value="<?php echo $getvendor[0]['officecontactno']; ?>" />
                                    <div id="offmoberr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">PAN No</label>
                                    <input type="text" name="panno" id="panno" onkeyup="check11()" class="form-control text-uppercase" maxlength="10" tabindex="15" placeholder="Enter Pan" value="<?php echo $getvendor[0]['panno']; ?>"/>
                                    <div id="panerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>TAN No</label>
                                    <input type="text" name="tan" id="tanno" onkeyup="check12()" class="form-control text-uppercase" maxlength="10" tabindex="16" placeholder="Enter Tan" value="<?php echo $getvendor[0]['tanno']; ?>"/>
                                    <div id="tanerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Registration No</label>
                                    <input type="text" name="regno" id="regno" onkeyup="check13()" class="form-control text-uppercase" maxlength="8" tabindex="17" placeholder="Enter registration number" value="<?php echo $getvendor[0]['regno']; ?>"/>
                                    <div id="regnoerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>VAT/TIN/GST No</label>
                                    <input type="text" name="vatno" id="vatno" class="form-control text-uppercase" onkeyup="numbercheck('vatno')" maxlength="15" tabindex="18" placeholder="Enter VAT/TIN/CST number" value="<?php echo $getvendor[0]['vatno']; ?>"/>
                                    <div id="vaterr" class="mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Banking Details</h2></div></div>
                    <div class="card-body pb-2">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Bank Name</label>
                                    <input type="text" name="bnkname" id="bnkname" onkeyup="check15('bnkname')" class="form-control text-capitalize" maxlength="60" tabindex="19" placeholder="Enter bank name" value="<?php echo $getvendor[0]['bnkname']; ?>"/>
                                    <div id="bankerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Branch Name</label>
                                    <input type="text" name="branchname" id="branchname" onkeyup="check16('branchname')" class="form-control text-capitalize" maxlength="30" tabindex="20" placeholder="Enter branch name" value="<?php echo $getvendor[0]['branchname']; ?>"/>
                                    <div id="brancherr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Beneficiary Name</label>
                                    <input type="text" name="beneficiary" id="beneficiary" onkeyup="check17('beneficiary')" class="form-control text-capitalize" maxlength="40" tabindex="21" placeholder="Enter beneficiary" value="<?php echo $getvendor[0]['beneficiary']; ?>"/>
                                    <div id="beneferr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Account No</label>
                                    <input type="text" name="acnt_no" id="acnt_no" onkeyup="check18('acnt_no')" class="form-control numberinput5" maxlength="20" tabindex="22" value="<?php echo $getvendor[0]['acntno']; ?>"/>
                                    <div id="accerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Account Type</label>
                                    <select class="form-control" name="acnttype" id="acnttype" onchange="check19(this.value)" tabindex="23" >
                                    <option value="Saving" <? if($getvendor[0]['acnttype']=="Saving"){echo "selected";} ?>>Saving</option>
                                    <option value="Current" <? if($getvendor[0]['acnttype']=="Current"){echo "selected";} ?>>Current</option></select>
                                    <div id="acctypeerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field"> IFSC Code</label>
                                    <input type="text" name="ifsc" id="ifsc" onkeyup="check20('ifsc')" class="form-control text-uppercase" maxlength="11" tabindex="24" placeholder="Enter IFSC code"  value="<?php echo $getvendor[0]['IFSCcode']; ?>"/>
                                    <div id="ifscerr" class="mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Documents</h2></div></div>
                    <div class="card-body pb-2 documentattch editprofile">
                        <h6 class="cc-font-weight-5 mb-3">Personal KYC Documents</h6>
                        <div class="row">
                            <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                <div class="mb-2 cc-mandatary-field">PHOTO</div>
                                <?php if($getvendor[0]['otherdoc']!="") { ?>
                                <div class="documentthumb"><a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['otherdoc']; ?>" target="_blank"><img id="other" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['otherdoc']; ?>" alt="<?php echo $getvendor[0]['otherdoc']; ?>" class="img-fluid img-thumbnail"/></a></div>
                                <div class="kycstatus">
                                    <input type="file" name="otherdoc" id="otherdoc" tabindex="25" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('otherdoc','other','error7');" />
                                    <label for="otherdoc" class="browsFile"><svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><span>Attach File</span><span id="error7"></span></label>&nbsp;<span class="pull-left attchFileName"></span>
                                </div>
                                <? } else{ ?>
                                <div class="documentthumb"><img alt="doc-thumb" id="other" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                <input type="file" name="otherdoc" id="otherdoc" tabindex="26" class="form-control inputfile inputfile-5 fileAttach d-none" onchange="readURL('otherdoc','other','error7');" />
                                <label for="otherdoc" class="browsFile"><svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><span>Attach File</span><span id="error7"></span></label>
                                <? } ?>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                <div class="mb-2 cc-mandatary-field">PAN</div>
                                <?php if($getvendor[0]['pandoc']!="") { ?>
                                <div class="documentthumb"><a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['pandoc']; ?>" target="_blank"><img id="pan" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['pandoc']; ?>" alt="<?php echo $getvendor[0]['pandoc']; ?>" class="img-fluid img-thumbnail"/></a></div>
                                <div class="kycstatus">
                                    <input type="file" name="pandoc" id="pandoc" tabindex="27" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('pandoc','pan','error1');" />
                                    <label for="pandoc" class="browsFile"><svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><span>Attach File</span><span id="error1"></span></label><span class="pull-left attchFileName"></span>
                                </div>
                                <? } else { ?>
                                <div class="documentthumb"><img alt="doc-thumb" id="pan" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                <div><input type="file" name="pandoc" id="pandoc" tabindex="28" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('pandoc','pan','error1');"/><label for="pandoc" class="browsFile"><svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><span>Attach File</span><span id="error1"></span></label><span class="pull-left attchFileName"></span>
                                </div>
                                <? } ?>
                            </div>
                        </div>
                        <hr>
                        <h6 class="cc-font-weight-5 mb-3">Business KYC Documents</h6>
                        <div class="row">
                            <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                <div class="mb-2">REGISTRATION</div>
                                <?php if($getvendor[0]['regdoc']!="") { ?>
                                <div class="documentthumb"><a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['regdoc']; ?>" target="_blank"><img id="reg" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['regdoc']; ?>" alt="<?php echo $getvendor[0]['regdoc']; ?>" class="img-fluid img-thumbnail"/></a></div>
                                <div class="kycstatus"><input type="file" name="regdoc" id="regdoc" tabindex="29" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('regdoc','reg','error2');" /><label for="regdoc" class="browsFile"><svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><span>Attach File</span><span id="error2"></span></label><span class="pull-left attchFileName"></span>
                                </div>
                                <? } else { ?>
                                <div class="documentthumb"><img alt="doc-thumb" id="reg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                <div>
                                    <input type="file" name="regdoc" id="regdoc" tabindex="30" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('regdoc','reg','error2');"/>
                                    <label for="regdoc" class="browsFile">
                                        <svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                        <span>Attach File</span>
                                        <span id="error2"></span>
                                    </label>
                                </div>
                                <? } ?>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3 pl-0 pl-sm-3">
                                <div class="mb-2">TAN</div>
                                <?php if($getvendor[0]['tandoc']!="") { ?>
                                <div class="documentthumb">
                                    <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['tandoc']; ?>" target="_blank"><img id="tan1" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['tandoc']; ?>" alt="<?php echo $getvendor[0]['tandoc']; ?>" class="img-fluid img-thumbnail"/></a>
                                </div>
                                <div class="kycstatus">
                                    <input type="file" name="tandoc" id="tandoc" tabindex="31" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('tandoc','tan1','error3');" />
                                    <label for="tandoc" class="browsFile">
                                        <svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                                        </svg>
                                        <span>Attach File</span>
                                        <span id="error3"></span>
                                    </label><span class="pull-left attchFileName"></span>
                                </div>
                                <? } else { ?>
                                <div class="documentthumb"><img alt="doc-thumb" id="tan1" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                <div>
                                    <input type="file" name="tandoc" id="tandoc" tabindex="32" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('tandoc','tan1','error3');"/>
                                    <label for="tandoc" class="browsFile">
                                    <svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span class="float-left">Attach File</span><span id="error3"></span>
                                    </label>
                                </div>
                                <? } ?>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3">
                                <div class="mb-2">VAT/TIN/GST</div>
                                <?php if($getvendor[0]['vatdoc']!="") { ?>
                                <div class="documentthumb"><a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['vatdoc']; ?>" target="_blank"><img id="vat" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['vatdoc']; ?>" alt="<?php echo $getvendor[0]['vatdoc']; ?>" class="img-fluid img-thumbnail"/></a></div>
                                <div class="kycstatus">
                                    <input type="file" name="vatdoc" id="vatdoc" tabindex="33" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected"  onchange="readURL('vatdoc','vat','error4');" />
                                    <label for="vatdoc" class="browsFile">
                                    <svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span>Attach File</span>pan>
                                    <span id="error4"></span>
                                    </label>&nbsp;<span class="pull-left attchFileName"></span>
                                </div>
                                <? } else { ?>
                                <div class="documentthumb"><img alt="doc-thumb" id="vat" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                <div>
                                    <input type="file" name="vatdoc" id="vatdoc" tabindex="34" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('vatdoc','vat','error4');" />
                                    <label for="vatdoc" class="browsFile">
                                    <svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span>Attach File</span><span id="filename4"></span><span id="error4"></span>
                                    </label>
                                </div>
                                <? } ?>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 ven-doc-col mb-3 pl-0 pl-sm-3">
                                <div class="mb-2">CANCELLED CHEQUE</div>
                                <?php if($getvendor[0]['chequedoc']!=""){ ?>
                                <div class="documentthumb">
                                <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['chequedoc']; ?>" target="_blank"><img id="can" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $getvendor[0]['chequedoc']; ?>" alt="<?php echo $getvendor[0]['chequedoc']; ?>" class="img-fluid img-thumbnail"/></a>
                                </div>
                                <div class="kycstatus">
                                    <input type="file" name="checkdoc" id="checkdoc" tabindex="35" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('checkdoc','can','error5');"/>
                                    <label for="checkdoc" class="browsFile">
                                    <svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span class="float-left">Attach File</span>
                                    <span id="error5"></span>
                                    </label><span class="pull-left attchFileName"></span>
                                </div>
                                <? }
                                else { ?>
                                <div class="documentthumb"><img alt="" id="can" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                <div>
                                    <input type="file" name="checkdoc" id="checkdoc" tabindex="36" class="form-control inputfile inputfile-5 fileAttach d-none" data-multiple-caption="{count} files selected" onchange="readURL('checkdoc','can','error5');"/>
                                    <label for="checkdoc" class="browsFile">
                                        <svg class="pull-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                        <span>Attach File</span>
                                        <span id="error5"></span>
                                    </label>
                                </div>
                                <? } ?>
                            </div>
                        </div>
                        <span class="showerror"></span>
                    </div>
                    <div class="card-footer text-right py-2"><button type="button" name="createsell" id="submitsell" class="btn btn-primary" onclick="chkdata()" tabindex="37">Update</button></div>
                </div>
            </form>
        </div>
        <?php include '../footer.php'?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/vendor_registration.js"></script>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
var siteurl="<?=SITEURL;?>";
function getpincode(){
    pincode = $("#Pin").val();
    if(pincode!=""){ 
        $.ajax({
            type : "POST", url: siteurl+"/ajax/order_ajax.php",
            data : {pincode:pincode,action:"pincodedetails"},
            success: function(resr){
                resdata=JSON.parse(resr);
                if(resdata['status']=="success"){ $("#city").val(resdata['city']);$("#state").val(resdata['state']);
                }else{
                    $(".adrmsg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html(resdata['message']).fadeOut(5000);
                    $("#city").val("");$("#state").val("");
                    $("#pinerr").html("Please enter Valid Pincode").addClass("text-danger");
                    $("#Pin").css("border","1px solid red");$("#Pin").val("");
                }
            }
        });
    }
}  
var sts = $("#kycsts").val();
if(sts==1){ $(".kycstatus").hide(); }
else{ $(".kycstatus").show(); }
function chkdata(){
    pincode = $("#Pin").val();
    if(pincode!=""){
        $.ajax({
        type : "POST", url: siteurl+"/ajax/order_ajax.php",
        data : {pincode:pincode,action:"pincodedetails"},
        success: function(resr){
            var dealerid = $("#dealerid").val();
            var name1 = $("#name1").val();
            var email = $("#email").val();
            //var patt = new RegExp(/^[a-zA-Z0-9._\-]+@[a-zA-Z.\-]+\.[a-zA-Z]{1,4}$/);
            //var patt = new RegExp(/^[a-zA-Z0-9.\-_]+@[0-9a-zA-Z.\-]+\.[a-zA-Z \t]{1,4}$/);
            //var res = patt.test(email);
            var contactNop = $("#contactNop").val();
            var patt1 = new RegExp(/^[7-9][0-9]{9}$/);
            var res2 = patt1.test(contactNop);
            var contactalt = $("#contactNot").val();
            var res3 = patt1.test(contactalt);
            var name2 = $("#name2").val();
            var shopname = $("#shopname").val();
            var country = $("#country").val();
            var state = $("#state").val();
            var city = $("#city").val();
            var Adress = $("#Adress").val();
            var locality = $("#locality").val();
            var pin = $("#Pin").val();
            var officecontactNo = $("#officemob").val();
            var officemail = $("#officeemail").val();
            var panno = $("#panno").val();
            var tanno = $("#tanno").val();
            var regno = $("#regno").val();
            var vatno = $("#vatno").val();
            var bnkname = $("#bnkname").val();
            var branchname = $("#branchname").val();
            var beneficiary = $("#beneficiary").val();
            var acnt_no = $("#acnt_no").val();
            var acnttype = $("#acnttype option:selected").val();
            var ifsc = $("#ifsc").val();
            var alphanumeric = /^[0-9a-zA-Z]+$/;
            var onlyalphabets = /[^\w\s]/gi;
            var panregExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/;
            if(($("#pandoc"))[0].files.length > 0){
                var pandoc=$('#pandoc').prop('files')[0]; var panfsize = ($('#pandoc'))[0].files[0].size; var panftype = ($('#pandoc'))[0].files[0].type; var panfname = ($('#pandoc'))[0].files[0].name;
            } else{ var pandoc=""; }
            if(($("#tandoc"))[0].files.length > 0){
                var tandoc = ($('#tandoc')).prop('files')[0]; var tanfsize = ($('#tandoc'))[0].files[0].size; var tanftype = ($('#tandoc'))[0].files[0].type; var tanfname = ($('#tandoc'))[0].files[0].name;
            } else{ var tandoc=""; }
            if(($("#regdoc"))[0].files.length > 0){
                var regdoc = ($('#regdoc')).prop('files')[0]; var regfsize =($('#regdoc'))[0].files[0].size; var regftype = ($('#regdoc'))[0].files[0].type; var regfname =($('#regdoc'))[0].files[0].name;
            } else{ var regdoc=""; }
            if(($("#vatdoc"))[0].files.length > 0){
                var vatdoc = ($('#vatdoc')).prop('files')[0]; var vatfsize =($('#vatdoc'))[0].files[0].size; var vatftype = ($('#vatdoc'))[0].files[0].type; var vatfname =($('#vatdoc'))[0].files[0].name;
            }
            else{ var vatdoc=""; }
            if(($("#checkdoc"))[0].files.length > 0){
                var checkdoc = ($("#checkdoc")).prop('files')[0]; var checkfsize =($('#checkdoc'))[0].files[0].size; var checkftype = ($('#checkdoc'))[0].files[0].type; var checkfname = ($('#checkdoc'))[0].files[0].name;
            } else{ var checkdoc=""; }
            if(($("#otherdoc"))[0].files.length > 0){
                var otherdoc = ($("#otherdoc")).prop('files')[0]; var otherfsize =($('#otherdoc'))[0].files[0].size; var otherftype = ($('#otherdoc'))[0].files[0].type; var otherfname = ($('#otherdoc'))[0].files[0].name;
            } else{ var otherdoc=""; }
            var form_data = new FormData();
            form_data.append('dealerid', dealerid);
            form_data.append('name1', name1);
            form_data.append('email', email);
            form_data.append('contactNop', contactNop);
            form_data.append('contactalt', contactalt);
            form_data.append('name2', name2);
            form_data.append('shopname', shopname);
            form_data.append('country', country);
            form_data.append('state', state);
            form_data.append('city', city);
            form_data.append('Adress', Adress);
            form_data.append('locality', locality);
            form_data.append('pin', pin);
            form_data.append('officecontactNo', officecontactNo);
            form_data.append('officemail', officemail);
            form_data.append('panno', panno);
            form_data.append('tanno', tanno);
            form_data.append('regno', regno);
            form_data.append('vatno', vatno);
            form_data.append('bnkname', bnkname);
            form_data.append('branchname', branchname);
            form_data.append('beneficiary', beneficiary);
            form_data.append('acnt_no', acnt_no);
            form_data.append('acnttype', acnttype);
            form_data.append('ifsc', ifsc);
            form_data.append('pandoc', pandoc);
            form_data.append('tandoc', tandoc);
            form_data.append('regdoc', regdoc);
            form_data.append('vatdoc', vatdoc);
            form_data.append('chequedoc', checkdoc);
            form_data.append('otherdoc', otherdoc); 
            form_data.append('action', "filldealerinfo_update");
            if((name1 == "") || (email == "") || (contactNop == "") || (res2 == false) || (contactalt != "" && res3 == false) ||(name2=="")||(shopname=="")||(country=="")||(state=="")||(city=="")||(Adress=="")||(locality=="")||(pin=="")||(officemail=="")||(officecontactNo=="")||(panno=="")||(bnkname=="")||(branchname=="")||(beneficiary=="")||(acnt_no=="")||(acnttype=="")||(ifsc=="")){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Fill All Fields Correctly").delay(3000).fadeOut();
                if(name1 == ""){ $("#name1").css("border", "1px solid red"); }
                else { $("#name1").css("border", "1px solid #BDBDBD"); } 
                if (email == ""){ $("#email").css("border", "1px solid red"); }
                else{ $("#email").css("border", "1px solid #BDBDBD"); }
                if(contactNop == "" || (res2 == false)){ $("#contactNop").css("border", "1px solid red"); }
                else{ $("#contactNop").css("border", "1px solid #BDBDBD"); }
                if(!(contactNop.charAt(0) == "9" || contactNop.charAt(0) == "8" || contactNop.charAt(0) == "7")){ $("#contactNop").css("border", "1px solid red"); }
                else{ $("#contactNop").css("border", "1px solid #BDBDBD"); }
                if(!(contactalt.charAt(0) == "9" || contactalt.charAt(0) == "8" || contactalt.charAt(0) == "7")) { $("#contactNot").css("border", "1px solid red"); }
                else{ $("#contactNot").css("border", "1px solid #BDBDBD"); }
                if(name2 == ""){ $("#name2").css("border","1px solid red"); }
                else{ $("#name2").css("border","1px solid #BDBDBD"); }
                if(shopname == ""){ $("#shopname").css("border","1px solid red"); }
                else{ $("#shopname").css("border","1px solid #BDBDBD"); }
                if(country == ""){ $("#country").css("border","1px solid red"); }
                else{ $("#country").css("border","1px solid #BDBDBD"); }
                if(state == ""){ $("#state").css("border","1px solid red"); }
                else{ $("#state").css("border","1px solid #BDBDBD"); }
                if(city == ""){ $("#city").css("border","1px solid red"); }
                else{ $("#city").css("border","1px solid #BDBDBD"); }
                if(Adress == ""){ $("#Adress").css("border","1px solid red"); }
                else{ $("#Adress").css("border","1px solid #BDBDBD"); }
                if(locality == ""){ $("#locality").css("border","1px solid red"); }
                else{ $("#locality").css("border","1px solid #BDBDBD"); }
                if(pin == ""){ $("#Pin").css("border","1px solid red"); }
                else{ $("#Pin").css("border","1px solid #BDBDBD"); }
                if(officemail == ""){ $("#officeemail").css("border","1px solid red"); }
                else{ $("#officeemail").css("border","1px solid #BDBDBD"); }
                if(officecontactNo == ""){ $("#officemob").css("border","1px solid red"); }
                else{ $("#officemob").css("border","1px solid #BDBDBD"); }
                if(panno == ""){ $("#panno").css("border","1px solid red"); }
                else{ $("#panno").css("border","1px solid #BDBDBD"); }
                if(bnkname == ""){ $("#bnkname").css("border","1px solid red"); }
                else{ $("#bnkname").css("border","1px solid #BDBDBD"); }
                if(branchname == ""){ $("#branchname").css("border","1px solid red");}
                else{ $("#branchname").css("border","1px solid #BDBDBD"); }
                if(beneficiary == ""){ $("#beneficiary").css("border","1px solid red"); }
                else{ $("#beneficiary").css("border","1px solid #BDBDBD"); }
                if(acnt_no == ""){ $("#acnt_no").css("border","1px solid red"); }
                else{ $("#acnt_no").css("border","1px solid #BDBDBD"); }
                if(acnttype == ""){ $("#acnttype").css("border","1px solid red"); }
                else{ $("#acnttype").css("border","1px solid #BDBDBD"); }
                if(ifsc == ""){ $("#ifsc").css("border","1px solid red"); }
                else{ $("#ifsc").css("border","1px solid #BDBDBD"); } 
            } if(name1 == ""){
                $("#name1").css("border", "1px solid red");
                $("#name1err").html("Please enter name").addClass("text-danger");
            }else if (email == "") {
                $("#email").css("border", "1px solid red");
                $("#emailerr").html("Please enter email adress").addClass("text-danger");
            }else if(contactNop == "" || (res2 == false)) {
                $("#contactNop").css("border", "1px solid red");
                $("#primoberr").html("Please enter Mobile No").addClass("text-danger");
            }else if(name2==""){
                $("#name2").css("border","1px solid red");
                $("#profileerr").html("Please enter profile ID.").addClass("text-danger");
            } else if(name2.length<=4){
                $("#name2").css("border","1px solid red");
                $("#profileerr").html("Enter minimum 5 character.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Profile ID Field").delay(3000).fadeOut();
            } else if(shopname == ""){
                $("#shopname").css("border","1px solid red");
                $("#companyerr").html("Please enter Company name.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Company Field").delay(3000).fadeOut();
            } else if(shopname.length <=4) {
                $("#shopname").css("border","1px solid red");
                $("#companyerr").html("Enter minimum 5 character").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Company Name Field").delay(3000).fadeOut();
            } else if(country=="") {
                $("#country").css("border","1px solid red");
                $("#countryerr").html("Please enter Country name.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Country Field").delay(3000).fadeOut();
            } else if(country.length <=2){
                $("#country").css("border","1px solid red");
                $("#countryerr").html("Enter minimum 3 character.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Country Field").delay(3000).fadeOut();
            } else if(state==""){
                $("#state").css("border","1px solid red");
               // $("#stateerr").html("Please enter State name.").addClass("text-danger");
                //$('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In State Field").delay(3000).fadeOut();
            } else if(state.length <=2){
                $("#state").css("border","1px solid red");
                //$("#stateerr").html("Enter minimum 3 character.").addClass("text-danger");
                //$('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In State Field").delay(3000).fadeOut();
            } else if(city=="") {
                $("#city").css("border","1px solid red");
                //$("#cityerr").html("Please enter City name").addClass("text-danger");
                //$('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In City Field").delay(3000).fadeOut();
            } else if(city.length <=2){
                $("#city").css("border","1px solid red");
                //$("#cityerr").html("Enter minimum 3 character").addClass("text-danger");
                //$('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In City Field").delay(3000).fadeOut();
            } else if(Adress=="") {
                $("#Adress").css("border","1px solid red");
                $("#officeadderr").html("Please enter Office Address.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Address Field").delay(3000).fadeOut();
            } else  if(Adress.length <=3) {
                $("#Adress").css("border","1px solid red");
                $("#officeadderr").html("Enter minimum 5 character....").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Address Field").delay(3000).fadeOut();
            } else if(locality=="") {
                $("#locality").css("border","1px solid red");
                $("#localityerr").html("Please enter locality.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Locality Field").delay(3000).fadeOut();
            } else if(pin==""){
                $("#Pin").css("border","1px solid red");
                $("#pinerr").html("Please enter PIN code.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In PIN Code Field").delay(3000).fadeOut();
            }
            else if(pin.length!=6) {
                $("#Pin").css("border","1px solid red");
                $("#pinerr").html("Pincode must be 6 digit.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In PIN Code Field").delay(3000).fadeOut();
            } else if(officemail=="") {
                $("#officeemail").css("border","1px solid red");
                $("#offemailerr").html("Please enter Office Email Adress.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Country Field").delay(3000).fadeOut();
            } else if(!(validateEmail(officemail))) {
                $("#officeemail").css("border","1px solid red");
                $("#offemailerr").html("Please enter correct email.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Office Email Field").delay(3000).fadeOut();
            } else if(officecontactNo=="") {
                $("#officemob").css("border","1px solid red");
                $("#offmoberr").html("Please enter office mobile no.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Country Field").delay(3000).fadeOut();
            } else if(!(officecontactNo.charAt(0)=="9" || officecontactNo.charAt(0)=="8" || officecontactNo.charAt(0)=="7")) {
                $("#officemob").css("border","1px solid red");
                $("#offmoberr").html("Number should start with 9,8 or 7.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Office Mobile Field").delay(3000).fadeOut();
            } else if(officecontactNo.length!=10) {
                $("#officemob").css("border","1px solid red");
                $("#offmoberr").html("Please check the mobile number.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Office Mobile Field").delay(3000).fadeOut();
            } else if(panno=="") {
                $("#panno").css("border","1px solid red");
                $("#panerr").html("Please enter PAN ID.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter PAN ID").delay(3000).fadeOut();
            } else if (!(panno.match(panregExp))) {
                $("#panno").css("border","1px solid red");
                $("#panerr").html("Not a valid PAN number.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In PAN No Field").delay(3000).fadeOut();
            } else if (panno.length != 10 ) {
                $("#panno").css("border","1px solid red");
                $("#panerr").html("Please enter 10 digits for a valid PAN number.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In PAN No Field").delay(3000).fadeOut();
            } else if(bnkname=="") {
                $("#bnkname").css("border","1px solid red");
                $("#bankerr").html("Please enter bank name.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter Bank Name").delay(3000).fadeOut();
            } else if(bnkname.length <=2) {
                $("#bnkname").css("border","1px solid red");
                $("#bankerr").html("Enter minimum 3 character.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Bank Name Field").delay(3000).fadeOut();
            } else if(branchname=="") {
                $("#branchname").css("border","1px solid red");
                $("#brancherr").html("Please enter branch name.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter Branch Name").delay(3000).fadeOut();
            } else if(branchname.length <=2){
                $("#branchname").css("border","1px solid red");
                $("#brancherr").html("Enter minimum 3 character.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Branch Name Field").delay(3000).fadeOut();
            } else if(beneficiary=="") {
                $("#beneficiary").css("border","1px solid red");
                $("#beneferr").html("Please enter beneficiary name.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter Beneficiary Name.").delay(3000).fadeOut();
            } else if(beneficiary.length <=2) {
                $("#beneficiary").css("border","1px solid red");
                $("#beneferr").html("Enter minimum 3 character.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In Beneficiary Field").delay(3000).fadeOut();
            } else if(acnt_no == ""){
                $("#acnt_no").css("border","1px solid red");
                $("#accerr").html("Please enter account number.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter Account Number").delay(3000).fadeOut();
            } else if(acnttype == ""){
                  $("#acnttype").css("border","1px solid red");
                  $("#acctypeerr").html("Please select account type.").addClass("text-danger");
                  $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select Account Type.").delay(3000).fadeOut();
            } else if(ifsc=="") {
                $("#ifsc").css("border","1px solid red");
                $("#ifscerr").html("Please enter IFSC code.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter IFSC Code").delay(3000).fadeOut();
            } else if (!(ifsc.match(alphanumeric))) {
                $("#ifsc").css("border","1px solid red");
                $("#ifscerr").html("Only numeric or alphanumeric allowed.").addClass("text-danger");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In IFSC Field").delay(3000).fadeOut();
            } else if ($('#otherdoc').val() != "" && ((otherftype != 'image/jpg' && otherftype != 'image/png' && otherftype != 'image/jpeg'))) {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Upload Photo").delay(3000).fadeOut();
            }  else if ($('#pandoc').val() != "" && ((panftype != 'image/jpg' && panftype != 'image/png' && panftype != 'image/jpeg'))) {
                $("#pandoc").css("border","1px solid red");
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Upload PAN Document").delay(3000).fadeOut();
            } else {   
                $(".submitsell").attr("disabled", true);
                $(".submitsell").html("Please Wait...");
                $.ajax({
                    type: "POST",
                    url: "../ajax/ajaxdata.php",
                    data:form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        $("#submitsell img").remove();
                        var vendorurl = "<?php echo VENDORURL; ?>";
                        if (response == 0){
                        }if(response == 1){
                            $("#dealerform").hide();
                            $("#otherdoc").hide();
                            window.location = vendorurl + "myaccount/";
                        }
                    }
                });
            }
        }
        })
    }  
}
</script>
<script>
function readURL(val1, val2, error){
    var inp = $('#' + val1).prop('files')[0];
    if (($('#' + val1))[0].files.length > 0){
        var filedoc = ($('#' + val1)).prop('files')[0];
        var filesize = ($('#' + val1))[0].files[0].size;
        var filetype = ($('#' + val1))[0].files[0].type;
        var filename = ($('#' + val1))[0].files[0].name;
    } else{ var filedoc = ""; }
    var allowedFiles = ["image/jpg", "image/jpeg", "image/png"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    if ((filedoc != "" && allowedFiles.indexOf(filetype) == -1)){
        $("#" + error).fadeIn()
        $("#" + error).html("Only JPG / JPEG / PNG").css("color", "red").delay(5000).fadeOut();
        $("#" + error).html('');
    } else {}
    var ss = filesize / 1024;
    if (ss > 2048){
        $("#" + error).fadeIn();
        $("#" + error).html("Max Image Size - 2MB").css("color", "red").delay(5000).fadeOut();
    } else {
        $('#' + val2).attr('src', URL.createObjectURL(filedoc));
    }
}

function chknick(){
    var nickname = $("#name2").val();
    var dealerid = $("#dealerid").val();
    if($.trim(nickname)!=""){
        $("#profileerr").html("");
        var info2 = {nickname:nickname,dealerid:dealerid,action:"checknicknameavailablityupdate"};
        $.ajax({
            type: "POST",
            url: "../ajax/ajaxdata.php",
            data: info2,
            success: function(response){
                response = $.trim(response);
                if(response==0){
                    $("#name2").css("border","1px solid red");
                    $("#submitsell").prop("disabled",true);
                    $('.nickmsg').fadeIn().addClass("text-danger").html("<i class='fa fa-times-circle' aria-hidden='true'></i> Not Available");
                } else{
                    $("#name2").css("border","1px solid #BDBDBD");
                    $("#submitsell").prop("disabled",false);
                    $('.nickmsg').fadeIn().addClass("text-success").html(" <i class='fa fa-check-circle' aria-hidden='true'></i> Available");
                }
            }
        });
    }
}  
</script>
</body>
</html>
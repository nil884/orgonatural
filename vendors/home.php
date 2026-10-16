<? include("../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Dashboard</title>
    <? include "commoncss.php"; ?>
</head>
<body>
<div class="seller-body-wrap">
    <? include 'header.php'; ?> 
    <div class="container-fluid">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="msgs1"></div>
                <?php if($get_vendor_details[0]['isComplete']=='0'||$get_vendor_details[0]['isApproved']=='0'||$get_vendor_details[0]['isActive']=='0'||$get_vendor_details[0]['plan_status']=='Expired'||(($get_vendor_details[0]['plan_status']=='Initialize'||$get_vendor_details[0]['plan_status']=='Active')&&$get_vendor_details[0]['payment_status']!='Received')){ ?>
                <?php if($get_vendor_details[0]['isComplete']=='0'){ ?>
                <form id="dealerform">
                    <div class="personal-details card-body border-bottom pb-0">
                        <h6 class="cc-font-weigh-6 text-info mb-3">Personal Details</h6>
                        <div class="row">
                            <input type="hidden" name="randnum" id="randnum" value=<?php echo $createnum; ?> />
                            <input type="hidden" name="dealerid" id="dealerid" value=<?php echo $_SESSION['seller']; ?> />
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Name</label>
                                    <input type="text" name="name1" id="name1" class="form-control text-capitalize" onkeyup="charcheck('name1')" maxlength="30" value="<?php echo $get_vendor_details[0]['dealer_name']; ?>" disabled/>
                                    <div id="nameerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Email</label>
                                    <input type="text" name="email" id="email" onblur="mailchk('email')" class="form-control" maxlength="50" autocomplete="off" value="<?php echo $get_vendor_details[0]['email']; ?>" disabled/>
                                    <div id="emailerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Primary Mobile</label>
                                    <input type="text" name="contactNop" id="contactNop" class="form-control numberinput1" onkeyup="numbercheck('contactNop')" maxlength="10" autocomplete="off" value="<?php echo $get_vendor_details[0]['personalcontactno']; ?>" disabled/>
                                    <div id="primoberr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Alt Mobile</label>
                                    <input type="text" name="contactNot" id="contactNot" onkeyup="check1('contactNot')"  onkeypress="numbercheck('contactNot')" value="<?php echo $get_vendor_details[0]['extracontactno']; ?>" class="form-control numberinput2" maxlength="10" autocomplete="off" placeholder="Enter alt mobile"/>
                                    <div id="altmoberr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Unique Profile ID</label>
                                    <div class="input-group"><input type="text" name="name2" id="name2" onblur="chknick()" onkeyup="check2('name2')" class="form-control" minlength="5" maxlength="100" value="<?php echo $get_vendor_details[0]['nickname']; ?>" placeholder="Enter Profile ID" <?php if($get_vendor_details[0]['nickname']!=""){ echo "disabled"; } ?>/><div class="input-group-append cc-cursor-pointer"><span class="input-group-text rounded" data-toggle="modal" data-target="#myModal"><i class="fa fa-question-circle"></i></span></div></div>
                                    <div id="profileerr" class="mt-1"></div><div class="nickmsg mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="business-details card-body border-bottom pb-0">
                        <h6 class="cc-font-weigh-6 text-info mb-3">Business Details</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Company</label>
                                    <input type="text" name="shopname" id="shopname" class="form-control text-capitalize" maxlength="100" placeholder="Enter company name" value="<?php echo $get_vendor_details[0]['shopname']; ?>"/><div id="companyerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Country</label>
                                    <input type="text" name="country" id="country" onkeyup="check4('country')" class="form-control text-capitalize" maxlength="20" placeholder="Enter country" value="<?php echo $get_vendor_details[0]['country']; ?>"/><div id="countryerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="cc-mandatary-field">Office Address</label>
                                <input class="form-control text-capitalize" name="Adress" id="Adress" maxlength="300" placeholder="Enter office address" value="<?php echo $get_vendor_details[0]['officeadress']; ?>" /><div id="officeadderr" class="mt-1"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">PIN Code</label>
                                    <input type="text" name="Pin" id="Pin" onkeyup="check8('Pin')" onchange="getpincode()" class="form-control" minlength="6" maxlength="6" placeholder="Enter pincode" value="<?php echo $get_vendor_details[0]['pin']; ?>"/><div id="pinerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">State</label>
                                      <input type="text" name="state" id="state" onkeyup="check5('state')" class="form-control text-capitalize" maxlength="30" placeholder="Enter state" value="<?php echo $get_vendor_details[0]['state']; ?>" disabled/><div id="stateerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">City</label>
                                    <input type="text" name="city" id="city" onkeyup="check6('city')" class="form-control text-capitalize" maxlength="30" placeholder="Enter city" value="<?php echo $get_vendor_details[0]['city']; ?>" disabled=""/><div id="cityerr" class="mt-1"></div>
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Locality</label>
                                    <input type="text" name="locality" id="locality" class="form-control text-capitalize" maxlength="20" autocomplete="off" placeholder="Enter Locality" onkeyup="localityvalidate('locality')" value="<?php echo $get_vendor_details[0]['locality']; ?>" /><div id="localityerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Office Email</label>
                                    <input type="text" name="officeemail" id="officeemail" onkeyup="check9()"  onblur="mailchk('officeemail')" class="form-control" maxlength="50" autocomplete="off" placeholder="Enter office email" value="<?php echo $get_vendor_details[0]['email']; ?>"/><div id="offemailerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Office Mobile</label>
                                    <input type="text" name="officemob" id="officemob" onkeyup="check10()" onkeypress="numbercheck('officemob')" class="form-control" maxlength="10" autocomplete="off" placeholder="Enter office mobile" value="<?php echo $get_vendor_details[0]['personalcontactno']; ?>" /><div id="offmoberr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">PAN No</label>
                                    <input type="text" name="panno" id="panno" onkeyup="check11()" class="form-control text-uppercase" maxlength="10" placeholder="Enter Pan" value="<?php echo $get_vendor_details[0]['panno']; ?>"/><div id="panerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>TAN No</label>
                                    <input type="text" name="tan" id="tan" onkeyup="check12()" class="form-control text-uppercase" maxlength="10" placeholder="Enter Tan" value="<?php echo $get_vendor_details[0]['tanno']; ?>"/><div id="tanerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label >Registration No</label>
                                    <input type="text" name="regno" id="regno" onkeyup="check13()" class="form-control text-uppercase" maxlength="8" placeholder="Enter registration number" value="<?php echo $get_vendor_details[0]['regno']; ?>" /><div id="regnoerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label >VAT/TIN/GST No</label>
                                    <input type="text" name="vatno" id="vatno" onkeyup="check14()" class="form-control text-uppercase" onkeyup="numbercheck('vatno')" maxlength="15" placeholder="Enter VAT/TIN/CST number" value="<?php echo $get_vendor_details[0]['vatno']; ?>"/><div id="vaterr" class="mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ban-details card-body border-bottom pb-0">
                        <h6 class="cc-font-weigh-6 text-info mb-3">Banking Details</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Bank Name</label>
                                    <input type="text" name="bnkname" id="bnkname" onkeyup="check15('bnkname')" class="form-control text-capitalize" maxlength="60" placeholder="Enter bank name" value="<?php echo $get_vendor_details[0]['bnkname']; ?>"/><div id="bankerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Branch Name</label>
                                    <input type="text" name="branchname" id="branchname" onkeyup="check16('branchname')" class="form-control text-capitalize" maxlength="30" placeholder="Enter branch name" value="<?php echo $get_vendor_details[0]['branchname']; ?>"/><div id="brancherr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Beneficiary</label>
                                    <input type="text" name="beneficiary" id="beneficiary" onkeyup="check17('beneficiary')" class="form-control text-capitalize" maxlength="40" placeholder="Enter beneficiary" value="<?php echo $get_vendor_details[0]['beneficiary']; ?>"/><div id="beneferr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Account No</label>
                                    <input type="text" name="acnt_no" id="acnt_no" onkeyup="check18('acnt_no')" class="form-control numberinput5" maxlength="18" onkeyup="numbercheck('acnt_no')" maxlength="15" placeholder="Enter account number" value="<?php echo $get_vendor_details[0]['acntno']; ?>"/><div id="accerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">Account Type</label>
                                    <select class="form-control" name="acnttype" id="acnttype" onchange="check19(this.value)">
                                        <option value="">-Select Type-</option>
                                        <option value="Saving" <?php if($get_vendor_details[0]['acnttype']== "Saving"){ echo "selected"; } ?>>Saving</option>
                                        <option value="Current" <?php if($get_vendor_details[0]['acnttype'] == "Current") { echo "selected"; } ?>>Current</option>
                                    </select><div id="acctypeerr" class="mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="cc-mandatary-field">IFSC Code</label>
                                    <input type="text" name="ifsc" id="ifsc" onkeyup="check20('ifsc')" class="form-control text-uppercase" maxlength="11" placeholder="Enter IFSC code" value="<?php echo $get_vendor_details[0]['IFSCcode']; ?>" /><div id="ifscerr" class="mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="plan-details paymentdetails card-body border-bottom pb-0">
                        <h6 class="cc-font-weigh-6 text-info mb-3">Plan Details</h6> 
                        <div class="highlight row">
                            <?php $planprice = selectQuery(VENDORPLAN,"*","isDel= '0' and isActive = '1' ");
                            for($p=0;$p<count($planprice);$p++){?>
                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                <div class="plan-pay-details shadow-lg mb-4 card cc-cursor-pointer"> 
                                    <div class="p-3">
                                        <div class="media">
                                            <img src="<?php echo SITEURL; ?>/img/projectimage/package-icon.svg" alt="package-icon" class="package-icon mr-3" width="25"/>
                                            <div class="media-body">
                                                <input type="radio" name="plan" id="plan<?=$p?>" class="plan_select custom-control-input cc-display-none" data-price="<?php echo $planprice[$p]['price']; ?>" value="<?php echo $planprice[$p]['plan_name']; ?>" /> 
                                                <h5 class="text-capitalize"><?php echo $planprice[$p]['plan_name'];?></h5>
                                                <div class="currencysymbol lead">
                                                    <?php if($planprice[$p]['price']==0){echo "Free";}else{echo " <i class='fa fa-inr'></i>" .$planprice[$p]['price']."";} ?>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer"><?php echo $planprice[$p]['plan_duration'] ?> Days</div>
                                </div>
                            </div>
                            <? } ?>
                        </div>
                    </div>
                    <div class="cocumnt card-body border-bottom">
                        <h6 class="cc-font-weigh-6 text-info mb-3">Documents</h6> 
                        <div class="documentattch">
                            <h6>Personal KYC Documents</h6>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 docList">
                                    <p class="cc-mandatary-field">PHOTO</p>
                                    <div class="documentthumb">
                                        <?php if($get_vendor_details[0]['otherdoc']!=""){ ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['otherdoc']; ?>" target="_blank"><img class="img-fluid" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['otherdoc']; ?>" alt="<?php echo $get_vendor_details[0]['otherdoc']; ?>" /></a>
                                        <? } else { ?>
                                        <div class="notfound"><img class="img-fluid" id="other" alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                        <? } ?>
                                    </div>
                                    <input type="hidden" name="otherdoc_old" id="otherdoc_old" value="<?php echo $get_vendor_details[0]['otherdoc']; ?>">
                                    <input type="file" name="otherdoc" id="otherdoc" tabindex="20" class="form-control inputfile inputfile-5 cc-display-none" onchange="readURL('otherdoc','other','error7');"/>
                                    <label for="otherdoc" class="browsFile mb-0"><svg class="float-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><p class="float-left">Attach File</p></label>
                                    <span id="error7"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 docList">
                                    <p class="cc-mandatary-field">PAN</p>
                                    <div class="documentthumb">
                                        <?php if($get_vendor_details[0]['pandoc']!=""){ ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['pandoc']; ?>" target="_blank">
                                        <img src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['pandoc']; ?>" alt="<?php echo $get_vendor_details[0]['pandoc']; ?>" class="img-fluid" /></a>
                                        <? } else { ?>
                                        <div class="notfound"><img id="pan" alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg" class="img-fluid"/></div>
                                        <? } ?>
                                    </div> 
                                    <input type="hidden" name="pandoc_old" id="pandoc_old" value="<?php echo $get_vendor_details[0]['pandoc']; ?>" >
                                    <input type="file" name="pandoc" id="pandoc" tabindex="13" class="form-control inputfile inputfile-5 cc-display-none" onchange="readURL('pandoc','pan','error1');"/>
                                    <label for="pandoc" class="browsFile mb-0"><svg class="float-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><p class="float-left">Attach File</p></label>
                                    <span id="error1"></span>
                                </div>
                                <hr>
                            </div>
                            <hr>
                            <h6>Business KYC Documents</h6>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 docList">
                                    <p>REGISTRATION</p>
                                    <div class="documentthumb">
                                        <?php if($get_vendor_details[0]['regdoc']!=""){ ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['regdoc']; ?>" target="_blank"><img class="img-fluid" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['regdoc']; ?>" alt="<?php echo $get_vendor_details[0]['regdoc']; ?>" /></a>
                                        <? } else { ?>
                                        <div class="notfound"><img class="img-fluid" id="reg" alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                        <? } ?>
                                    </div>
                                    <input type="file" name="regdoc" id="regdoc" tabindex="16" class="form-control inputfile inputfile-5 cc-display-none" onchange="readURL('regdoc','reg','error2');"/>
                                    <label for="regdoc" class="browsFile mb-0"><svg class="float-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><p class="float-left">Attach File</p></label>
                                    <span id="error2"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 docList">
                                    <p>TAN</p>
                                    <div class="documentthumb">
                                        <?php if($get_vendor_details[0]['tandoc']!=""){ ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['tandoc']; ?>" target="_blank"><img class="img-fluid" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['tandoc']; ?>" alt="<?php echo $get_vendor_details[0]['tandoc']; ?>" /></a>
                                        <? } else { ?>
                                        <div class="notfound"><img class="img-fluid" id="tan1" alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                        <? } ?>
                                    </div>
                                    <input type="file" name="tandoc" id="tandoc" tabindex="14" class="form-control inputfile inputfile-5 cc-display-none" onchange="readURL('tandoc','tan1','error3');"/>
                                    <label for="tandoc" class="browsFile mb-0"><svg class="float-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <p class="float-left">Attach File</p>
                                    </label>
                                    <span id="error3"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 docList">
                                    <p>VAT/TIN/GST</p>
                                    <div class="documentthumb">
                                        <?php if($get_vendor_details[0]['vatdoc']!="") { ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['vatdoc']; ?>" target="_blank">
                                        <img class="img-fluid" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['vatdoc']; ?>" alt="<?php echo $get_vendor_details[0]['vatdoc']; ?>"/></a>
                                        <? } else { ?>
                                        <div class="notfound"><img class="img-fluid" id="vat" alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                        <? } ?>
                                    </div>
                                    <input type="file" name="vatdoc" id="vatdoc" tabindex="18" class="form-control inputfile inputfile-5 cc-display-none" onchange="readURL('vatdoc','vat','error4');"/>
                                    <label for="vatdoc" class="browsFile mb-0">
                                        <svg class="float-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                        <p class="float-left">Attach File</p>
                                    </label>
                                    <span id="error4"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 docList">
                                    <p>CANCELLED CHEQUE</p>
                                    <div class="documentthumb">
                                    <?php if($get_vendor_details[0]['chequedoc']!=""){ ?>
                                        <a href="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['chequedoc']; ?>" target="_blank"><img class="img-fluid" src="<?php echo SITEURL; ?>/img/vendordocs_images/<?php echo $get_vendor_details[0]['chequedoc']; ?>" alt="<?php echo $get_vendor_details[0]['chequedoc']; ?>" /></a>
                                        <? } else { ?>
                                        <div class="notfound"><img class="img-fluid" id="can" alt="uploadimg" src="<?php echo SITEURL; ?>/img/projectimage/uploadimg.jpg"/></div>
                                        <? } ?>
                                    </div>
                                    <input type="file" name="checkdoc" id="checkdoc" tabindex="20" class="form-control inputfile inputfile-5 cc-display-none" onchange="readURL('checkdoc','can','error5');"/>
                                    <label for="checkdoc" class="browsFile mb-0">
                                        <svg class="float-left" width="20" height="20" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                        <p class="float-left">Attach File</p>
                                    </label>
                                    <span id="error5"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"><div class="msgs1"></div></div>
                    <div class="msgs2"></div>
                    <div class="card-body text-right">
                        <div class="loader"></div><span id="waitmsg"></span>
                        <?php if(VENDOR_PLAN_PAYMENT_MODE =="Offline") { ?>
                        <button type="button" name="createsell" id="submitsell" class="btn btn-primary submitsell" onclick="chkdata('Offline')" tabindex="28"> SUBMIT</button>
                        <?php } else if(VENDOR_PLAN_PAYMENT_MODE=="Online") { ?>
                        <button type="button" name="createsell1" id="submitsell" class="btn btn-primary greenBtn submitsell" onclick="chkdata('Online')" tabindex="28"> Save And Continue</button>
                        <? } ?>
                    </div>
                    <input type="hidden" value="<? echo VENDOR_PLAN_PAYMENT_MODE ?>" id="pay_mode">
                </form>
                <? }
            else if($get_vendor_details[0]['isComplete']==1 && $get_vendor_details[0]['isApproved']==0&&$get_vendor_details[0]['payment_status']=="Received"){ ?>
            <div class="sell-per-det-success alert alert-success mb-0">
                <h4 class="cc-font-weigh-6">Dear <?php echo $get_vendor_details[0]['nickname']; ?>,</h4>
                <p class="mb-0">Thank you for joining <strong><?php echo SITENAME; ?></strong> family. Your profile is under verification process. After successful verification we will approve your account and you will be notified via email</p>
            </div>
            <? } else if($get_vendor_details[0]['isActive']==0){ ?>
            <div class="alert alert-info mb-0">
                <h5 class="cc-font-weigh-6">Dear <?php echo $get_vendor_details[0]['nickname']; ?>,</h5>
                <div>Your account is deactivated by system admin. Please contact system admin to access your account</div>
            </div>
            <? } else if($get_vendor_details[0]['plan_status']=="Expired") {
            $getvendorcurrplan = selectQuery(VENDORPLANSELECTED,"*","sel_id=".$get_vendor_details[0]['plan']); ?>
            <div class="sell-per-det-success">
                <h4>Dear <?php echo $get_vendor_details[0]['nickname']; ?>,</h4>
                <?php if(VENDOR_PLAN_PAYMENT_MODE=="Offline"){ ?>
                <p class="text-danger cc-font-weigh-6">Your current plan is expired, Please contact system admin to access your account</p>
                <div class="row">
                    <div class="col-md-10">
                        <div class="row form-group">
                            <div class="col-3"><b>Plan</b></div>
                            <div class="col-9 text-muted"><?php echo $getvendorcurrplan[0]['plan'].""; ?></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-3"><b>Plan Expired Date</b></div>
                            <div class="col-9 text-muted"><?php echo $getvendorcurrplan[0]['plan_to']; ?></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-3"><b>Contact No</b></div>
                            <div class="col-9 text-muted"><?php echo ADMINCONTACT; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-3"><b>Email</b></div>
                            <div class="col-9 text-muted"><?php echo EMAIL_REG; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <? } else { ?>
            <h5 class="text-success">Your current plan is expired</h5>
            <div class="row form-group">
                <div class="col-3"><b>Plan</b></div>
                <div class="col-9 text-muted"><?php echo $getvendorcurrplan[0]['plan'].""; ?></div>
            </div>
            <div class="row">
                <div class="col-3"><b>Plan Expired Date</b></div>
                <div class="col-9 text-muted"><?php echo $getvendorcurrplan[0]['plan_to']; ?></div>
            </div>
            <hr>
            <a href="planpayment.php?vendor=<?php echo base64_encode($get_vendor_details[0]['dealer_id']) ?>">Select Plan And Pay Now</a>
            <? } ?>
            <? } else if(($get_vendor_details[0]['plan_status']=='Initialize'||$get_vendor_details[0]['plan_status']=='Active')&&$get_vendor_details[0]['payment_status']!="Received") {
            $getvendorcurrplan=selectQuery(VENDORPLANSELECTED,"*","sel_id=".$get_vendor_details[0]['plan']); ?>
            <div class="sell-per-det-success">
                <h5>Dear <?php echo $get_vendor_details[0]['nickname']; ?>,</h5>
                <?php if(VENDOR_PLAN_PAYMENT_MODE=="Offline" || $getvendorcurrplan[0]['price'] == "0") { ?>
                <p class="lead text-success mb-3">Thank you for successfully submitting your application, your profile is under approval..</p>
                <p class="text-muted">Please contact system admin to access your account <br></p>
                <div class="row">
                    <div class="col-md-10">
                        <div class="row form-group">
                            <div class="col-2"><b>Contact No</b></div>
                            <div class="col-10"><?php echo ADMINCONTACT; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-2"><b>Email</b></div>
                            <div class="col-10"><?php echo  EMAIL_REG; ?></div>
                        </div>
                    </div>
                </div>
            </div> 
            <? } else{ ?>
                <h6 class="text-info mb-3">Your payment for current plan not received</h6>
                <div class="row">
                    <div class="col-md-10">
                        <div class="row form-group">
                            <div class="col-3"><b>Selected Plan</b></div>
                            <div class="col-9"><?php echo $getvendorcurrplan[0]['plan'].""; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-3"><b>Payment Pending</b></div>
                            <div class="col-9"><span class="currencysymbol"></span>&nbsp;<?php echo $getvendorcurrplan[0]['price']." /-"; ?></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer"><a href="planpayment.php?vendor=<?php echo base64_encode($get_vendor_details[0]['dealer_id']) ?>&plan=<?php echo base64_encode($getvendorcurrplan[0]['sel_id']); ?>" class="btn btn-primary">Pay Now <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
                <? } ?>
                <? } } else{ ?> <script>window.location="dashboard.php";</script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Important Note</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <ol class="pl-3 mb-0">
                    <li class="mb-2">This is a unique fields and directly displayed to the end-user.</li>
                    <li class="mb-2">You can use the company name or shop name which will be best used to identify your products.( No special characters are allowed )</li>
                    <li>Once created you can not change this ID later.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<? include "footer.php"; ?>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script src="<?php echo SITEURL ?>/js/vendor_registration.js"></script>
<script>
    siteurl = "<?php echo SITEURL ?>";
    function getpincode(){
    pincode = $("#Pin").val();
    if(pincode!=""){
        $.ajax({
            type : "POST", url: siteurl+"/ajax/order_ajax.php",
            data : {pincode:pincode,action:"pincodedetails"},
            success: function(resr){
                resdata = JSON.parse(resr);
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
function chknick(){
    var nickname = $("#name2").val();
    if($.trim(nickname)!="" && $.trim(nickname).length >= 5){
        var info2 = {nickname:nickname,action:"checknicknameavailablity"};
        $.ajax({
            type: "POST",
            url: "ajax/ajaxdata.php",
            data: info2,
            success: function(response){
                response = $.trim(response);
                if(response==0){
                    $("#name2").css("border","1px solid red");
                    $("#submitsell").prop("disabled",true);
                    $('.nickmsg').fadeIn().addClass("text-danger").html("<i class='fa fa-times-circle' aria-hidden='true'></i> Not Available");
                }
                else{
                    $("#name2").css("border","1px solid #BDBDBD");
                    $("#submitsell").prop("disabled",false);
                    $('.nickmsg').fadeIn().addClass("text-success").html(" <i class='fa fa-check-circle' aria-hidden='true'></i> Available");
                }
            }
        });
    }
}

function chkdata(val1){
    var dealerid = $("#dealerid").val(); var name1 = $("#name1").val(); var email = $("#email").val(); var contactNop = $("#contactNop").val(); var patt1 = new RegExp(/^[7-9][0-9]{9}$/); var res2 = patt1.test(contactNop); var contactalt =$("#contactNot").val(); var res3 = patt1.test(contactalt); var name2  = $("#name2").val(); var shopname = $("#shopname").val(); var country = $("#country").val(); var state = $("#state").val(); var city = $("#city").val(); var Adress = $("#Adress").val(); var locality = $("#locality").val(); var pin = $("#Pin").val(); var officecontactNo = $("#officemob").val(); var officemail = $("#officeemail").val(); var panno = $("#panno").val(); var tanno = $("#tan").val(); var regno = $("#regno").val(); var vatno = $("#vatno").val(); var bnkname = $("#bnkname").val();
    var branchname = $("#branchname").val(); var beneficiary = $("#beneficiary").val(); var acnt_no = $("#acnt_no").val();
    var acnttype = $("#acnttype option:selected").val(); var ifsc = $("#ifsc").val(); var plan = $('input[name=plan]:checked').val(); var alphanumeric = /^[0-9a-zA-Z]+$/; var onlyalphabets = /[^\w\s]/gi; var panregExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/;
    if(($("#pandoc"))[0].files.length > 0){
        var pandoc=$('#pandoc').prop('files')[0]; var panfsize = ($('#pandoc'))[0].files[0].size; var panftype = ($('#pandoc'))[0].files[0].type; var panfname = ($('#pandoc'))[0].files[0].name;
    }
    else{ var pandoc=""; }
    if(($("#tandoc"))[0].files.length > 0){
        var tandoc = ($('#tandoc')).prop('files')[0]; var tanfsize = ($('#tandoc'))[0].files[0].size; var tanftype = ($('#tandoc'))[0].files[0].type; var tanfname = ($('#tandoc'))[0].files[0].name;
    }
    else{ var tandoc=""; }
    if(($("#regdoc"))[0].files.length > 0){
        var regdoc = ($('#regdoc')).prop('files')[0]; var regfsize =($('#regdoc'))[0].files[0].size; var regftype = ($('#regdoc'))[0].files[0].type; var regfname =($('#regdoc'))[0].files[0].name;
    }
    else{ var regdoc=""; }
    if(($("#vatdoc"))[0].files.length > 0){
        var vatdoc = ($('#vatdoc')).prop('files')[0]; var vatfsize =($('#vatdoc'))[0].files[0].size; var vatftype = ($('#vatdoc'))[0].files[0].type; var vatfname =($('#vatdoc'))[0].files[0].name;
    }
    else{ var vatdoc=""; }
    if(($("#checkdoc"))[0].files.length > 0){
        var checkdoc = ($("#checkdoc")).prop('files')[0]; var checkfsize =($('#checkdoc'))[0].files[0].size; var checkftype = ($('#checkdoc'))[0].files[0].type; var checkfname = ($('#checkdoc'))[0].files[0].name;
    }
    else{ var checkdoc=""; }
    if(($("#otherdoc"))[0].files.length > 0){
        var otherdoc = ($("#otherdoc")).prop('files')[0]; var otherfsize =($('#otherdoc'))[0].files[0].size; var otherftype = ($('#otherdoc'))[0].files[0].type; var otherfname = ($('#otherdoc'))[0].files[0].name;
    }
    else{ var otherdoc=""; }
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
    form_data.append('plan', plan);
    form_data.append('otherdoc', otherdoc);
    form_data.append('action', "filldealerinfo");
    if((name2=="")||(shopname=="")||(country=="")||(state=="")||(city=="")||(Adress=="")||(locality=="")||(pin=="")||(officemail=="")||(officecontactNo=="")||(panno=="")||(bnkname=="")||(branchname=="")||(beneficiary=="")||(acnt_no=="")||(acnttype=="")||(ifsc=="") || (typeof plan == 'undefined')){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Fill All Fields Correctly").delay(3000).fadeOut();
        if(name2 == ""){ $("#name2").css("border","1px solid red"); }
        else{ $("#name2").css("border","1px solid #BDBDBD"); }
        if(shopname == ""){ $("#shopname").css("border","1px solid red"); }
        else{ $("#shopname").css("border","1px solid #BDBDBD"); }
        if(country == ""){  $("#country").css("border","1px solid red"); }
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
        if(branchname == ""){ $("#branchname").css("border","1px solid red"); }
        else{ $("#branchname").css("border","1px solid #BDBDBD"); }
        if(beneficiary == ""){ $("#beneficiary").css("border","1px solid red"); }
        else{ $("#beneficiary").css("border","1px solid #BDBDBD"); }
        if(acnt_no == ""){ $("#acnt_no").css("border","1px solid red"); }
        else{ $("#acnt_no").css("border","1px solid #BDBDBD"); }
        if(acnttype == ""){ $("#acnttype").css("border","1px solid red"); }
        else{ $("#acnttype").css("border","1px solid #BDBDBD"); }
        if(ifsc == ""){ $("#ifsc").css("border","1px solid red"); }
        else{ $("#ifsc").css("border","1px solid #BDBDBD"); }
        }
        if(name2==""){
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
           // $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error In City Field").delay(3000).fadeOut();
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
            $("#offmoberr").html("Please enter office Contact No.").addClass("text-danger");
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
        } else if(typeof plan == 'undefined') { 
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select plan").delay(3000).fadeOut();
        } else if( ($('#otherdoc').val()=="" && $('#otherdoc_old').val()=="")) {
            $("#othercheckdoc").css("border","1px solid red");
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Upload Photo").delay(3000).fadeOut();
        } else if(($('#pandoc').val()=="" && $('#pandoc_old').val()=="")) {
            $("#pandoc").css("border","1px solid red");
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Upload PAN Document").delay(3000).fadeOut();
        } else {  
            if(pin!=""){
                $.ajax({
                type : "POST", url: siteurl+"/ajax/order_ajax.php",
                data : {pincode:pin,action:"pincodedetails"},
                success: function(resr){ 
                    $(".submitsell").attr("disabled", true);
                    $(".submitsell").html("Please Wait...");
                    $.ajax({
                        type: "POST",
                        url: "ajax/ajaxdata.php",
                        data:form_data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response){
                            if(response==0){ $(".submitsell").attr("disabled", false); }
                            else{
                                if(val1=="Offline"){
                                    $("#dealerform").hide();
                                    $(".msgs1").html("Dear Vendor, Thank you for completing your profile. Your profile is under verification process. After successful verification we will approve your account and you will be notified via email");
                                    location.reload();
                                }
                                else if(val1=="Online"){ location.href=$.trim(response); }
                            }
                        }
                    });
                }
            })
        }
    }
}
function readURL(val1,val2,error){
    var inp = $('#'+val1).prop('files')[0];
    if(($('#'+val1))[0].files.length > 0){
        var filedoc = ($('#'+val1)).prop('files')[0]; var filesize = ($('#'+val1))[0].files[0].size; var filetype = ($('#'+val1))[0].files[0].type; var filename = ($('#'+val1))[0].files[0].name;
    } else { var filedoc=""; }
    var allowedFiles = ["image/jpg", "image/jpeg", "image/png"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    if((filedoc!=""&&allowedFiles.indexOf(filetype)==-1) ){
        $("#"+error).fadeIn()
        $("#"+error).html("Only JPG / JPEG / PNG").css("color","red").delay(5000).fadeOut();
        $("#"+error).html('');
    }
    else{ }
    var ss = filesize/1024;
    if(ss>1024){
        $("#"+error).fadeIn();
        $("#"+error).html("Max Image Size - 1MB").css("color","red").delay(5000).fadeOut();
    }
    else{ $('#'+val2).attr('src',URL.createObjectURL(filedoc)); }
}
$('.plan-pay-details').click(function(){
    $(this).find('input').prop('checked', true);
    $('.plan-pay-details').removeClass('plan-selected');
    $(this).toggleClass('plan-selected');
});
$(document).ready(function(){ $('[data-toggle="popover"]').popover(); });
</script>    
</body>
</html>
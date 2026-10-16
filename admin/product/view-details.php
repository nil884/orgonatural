<? include("../../includes/configuration.php");
    $prod_id = base64_decode($_REQUEST['prod_id']);
    $getproduct = selectQuery(PRODINFO,"*","id = ".$prod_id." ");
    $getparent_catdetails = selectQuery(PRODCAT,"cat_name,isActive","id=".$getproduct[0]['parent_cat']);
    $getmaster_catdetails = selectQuery(PRODCAT,"cat_name,isActive","id=".$getproduct[0]['master_cat']);
    $getsbcatdetails = selectQuery(PRODCAT,"cat_name,template,isActive","id=".$getproduct[0]['sub_cat']);
    $table_attribute = array();
    $invarray = array();
    $getconfingdetails = json_decode(getimgconfig('product'));
    $img_location = $getconfingdetails[0]->imgs_location; // Access Object data
    $getinv = selectQuery(PRODINFO,"*","parent_id  = ".$prod_id."    order by id asc ");
    if(count($getinv)==0 ){ $getinv = selectQuery(PRODINFO,"*","id = ".$prod_id." "); }
    for($i=0;$i<count($getinv);$i++){  
        array_push($invarray, $getinv[$i]['id']);  
    }
    $invstr = implode("','",$invarray);
    $getsingle_image = selectQuery(PRODIMG,"id,img_name"," prod_id in('".$invstr."') order by id DESC  limit 1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Product Details</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <h2 class="card-head-title">Product Details</h2>
                    <div class="btn-actions-pane-right"><a href="javascript:history.go(-1)" class="btn btn-secondary btn-sm">Back</a></div>
                </div>
                <div class="card-body pb-2">
                    <div class="row"> 
                        <div class="col-md-12">  
                        <? if($getparent_catdetails[0]['isActive']==0||$getmaster_catdetails[0]['isActive']==0||$getsbcatdetails[0]['isActive']==0){?>
                                <div class="alert alert-danger">Important : The <b><?=($getparent_catdetails[0]['isActive']==0?"Parent Category":($getmaster_catdetails[0]['isActive']==0?"Master Category":($getsbcatdetails[0]['isActive']==0?"Sub Category":""))); ?></b> of this product is disabled. Because of this product will not be visible on the frontend (Link - <a href="<?=ADMINURL; ?>/category" target="_blank">Category Management</a>)</div>    
                            <?} ?> 
                            <?php $getinv = selectQuery(PRODINFO,"*","parent_id = ".$prod_id."  order by id asc ");
                            $getseller = selectQuery(VENDOR,"nickname","dealer_id= '".$getproduct[0]['vendor']."' ");
                            if(count($getinv) == 0 ){ $getinv = selectQuery(PRODINFO,"*","id = ".$prod_id."  "); } ?> 
                            <h5 class="mb-1"><?php echo $getproduct[0]['prod_name'] ?> by <?php echo $getseller[0]['nickname']; ?></h5>
                            <ul class="breadcrumb p-0 mb-2"><li class="breadcrumb-item"><a href="#"><?php echo $getparent_catdetails[0]['cat_name'] ?></a></li><li class="breadcrumb-item"><a href="#"><?php echo $getmaster_catdetails[0]['cat_name'] ?></a></li><li class="breadcrumb-item active"><?php echo $getsbcatdetails[0]['cat_name']; ?></li></ul> 
                            <div>
                                <form>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" name="prod_name" id="prod_name" class="form-control" value="<?php echo $getproduct[0]['prod_name'];?>">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                            <div class="form-group">
                                                <label>Product Company</label>
                                                <input type="text" name="prod_company" id="prod_company" class="form-control" value="<?php echo $getproduct[0]['prod_company'];?>">       
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-xl-2">
                                            <div class="form-group">
                                                <label>HSN Code</label>
                                                <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="<?php echo $getproduct[0]['hsn_code'] ?>" onkeyup="letter_number('hsn_code')"> 
                                            </div>  
                                        </div>
                                        <div class="col-12 border-top pt-2 text-right"><input type="button" value="Submit" class="btn btn-primary" id="Basic_update"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
            <div id="inventry" class="card">             
                <ul class="nav nav-tabs inventory-tab border-right-0" role="tablist">
                    <?php for($i=0;$i<count($getinv);$i++){  $idname = $i+1; ?>
                    <li class="nav-item mb-0"><a class="nav-link border rounded-0 <?php if($i == 0){ echo 'active';} ?>" data-toggle="tab" href="#invtb<?php echo $idname ?>"><?php echo "Inventory ".$idname; ?></a></li>
                    <?php } ?>
                </ul>           
                <div class="tab-content invent-body pt-3 pr-3 pl-3 border-top-0">
                    <div class="alert alert-info">Please enter all prices inclusive of Taxes</div>
                    <?php for($i=0;$i<count($getinv);$i++){
                    $idname = $i+1;
                    $inv_id = $getinv[$i]['id']; ?>
                    <div class="tab-pane p-0 <?php if($i == 0){ echo 'active';} if($i !== 0){ echo 'fade';}?> " id="invtb<?php echo $idname ?>">                  
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 invent-tab-col">
                                <div class="card-header sec-card-head justify-content-between align-items-center p-0 mb-3 border-0">
                                    <div><h5 class="card-head-title"><?php echo "Inventory ".$idname; ?></h5></div>
                                    <?php if($getproduct[0]['isApproved'] == 1){ ?>
                                    <div class="btn-actions-pane-right">
                                        <div id="activity_btn">                         
                                            <label class="switch btn btn-primary">
                                                <input type="checkbox" id="activ_deact<?php echo $getinv[$i]['id'] ?>" <?php if($getinv[$i]['isActive'] == 1){echo "checked"; } ?> onchange="active_deactive('<?php echo $getinv[$i]['id'] ?>')" <?php if($getinv[$i]['isApproved'] == 0){echo "disabled"; } ?>>
                                                <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                            </label>                     
                                            <label class="switch btn btn-primary">
                                                <input type="checkbox" id="approve_inv<?php echo $getinv[$i]['id'] ?>" <?php if($getinv[$i]['isApproved'] == 1){echo "checked"; } ?> onchange="approve_disappove_inv('<?php echo $getinv[$i]['id'] ?>')">
                                                <span class="slider round"><span class="on">Approve</span><span class="off">Disapproved</span></span>
                                            </label>                     
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="alert alert-info">
                                    <h6><b>Important Note : </b></h6>
                                    <ul class="mb-0 pl-3">
                                        <li class="mb-1"><b>MRP :</b> This will be maximum retail price</li>
                                        <li class="mb-1"><b>Vendor Price (Dealer Price) :</b> This will be a price offerd by the vendor to the admin and visible only to the admin (and not to the end user)</li>
                                        <li class="mb-1"><b>Discount Price :</b> This will be a special price offered by the vendor to the admin for the sepcific duration, and this price will be visible only to the admin (and not to the end user)</li>
                                        <li>Please enter all prices inclusive of Taxes</li>
                                    </ul>
                                </div>
                                <div class="border-bottom row pb-2 mb-3">
                                    <div class="col-12">
                                        <h6 class="text-primary">Attributes</h6>
                                        <div class="row mx-0">
                                            <div class="col-md-12">
                                                <?php if($getinv[$i]['variant_name1'] != "") { if($i==0) { array_push($table_attribute, $getinv[$i]['variant_name1']); } $get_attr_name =  selectQuery(PRODATTR,"*","attr_for_template= '".$getinv[$i]['variant_name1']."'");} ?>
                                                <?php if($getinv[$i]['variant_name2'] != ""){ if($i==0) {  array_push($table_attribute, $getinv[$i]['variant_name2']); } $get_attr_name =  selectQuery(PRODATTR,"*","attr_for_template= '".$getinv[$i]['variant_name2']."'"); } ?>
                                                <?php if($getinv[$i]['variant_name3'] != "") { if($i==0) { array_push($table_attribute, $getinv[$i]['variant_name3']);} $get_attr_name =  selectQuery(PRODATTR,"*","and attr_for_template= '".$getinv[$i]['variant_name3']."'"); } ?>
                                            </div>             
                                            <?php if($getinv[$i]['variant_name1'] != ""){ 
                                            $str = '"variant_'.$getinv[$i]['id'].'_'.$getinv[$i]['variant_name1'].'",';
                                            $results = showQuery($getsbcatdetails[0]['template'],"field= '".$getinv[$i]['variant_name1']."'");
                                            $type = $results[0]['Type']; 
                                            $t = explode("(",$results[0]['Type']); ?> 
                                            <div class="form-group mb-2 mr-3">
                                                <label class="cc-mandatary-field"><?php echo getOriginalName($getinv[$i]['variant_name1']); ?> (<?php echo getAttributeCat($getinv[$i]['variant_name1']); ?>)</label>
                                                <input type="text" value="<?php echo $getinv[$i]['variant_value1']  ?>" class="form-control <?php if($t[0]=="int"){echo "numberinput";} ?>" id="variant_<?php echo $getinv[$i]['id'].'_'.$getinv[$i]['variant_name1']; ?>" data-id="<?php echo $getinv[$i]['variant_name1']; ?>" data-id1="<?php echo $get_attr_name[0]['attr_name']; ?>" placeholder="<?php echo $get_attr_name[0]['attr_name'] ?>" maxlength="100" <? if($t[0]=="int"){?> onkeyup="numbercheck('variant_<?php echo $i; ?>');" <?} ?> > 
                                            </div>
                                            <?php } else{ $str = $str.'"",'; } ?>
                                            <?php if($getinv[$i]['variant_name2'] != "") { 
                                            $str = $str.'"variant_'.$getinv[$i]['id'].'_'.$getinv[$i]['variant_name2'].'",';
                                            $results = showQuery($getsbcatdetails[0]['template'],"field= '".$getinv[$i]['variant_name2']."'");
                                            $type = $results[0]['Type']; 
                                            $t = explode("(",$results[0]['Type']); ?> 
                                            <div class="form-group mb-2 mr-3">
                                                <label class="cc-mandatary-field"><?php echo getOriginalName($getinv[$i]['variant_name2']); ?> (<?php echo getAttributeCat($getinv[$i]['variant_name2'])  ?>)</label>
                                                <input type="text" value="<?php echo $getinv[$i]['variant_value2'] ?>" class="form-control <?php if($t[0]=="int"){echo "numberinput";} ?>" id="variant_<?php echo $getinv[$i]['id'].'_'.$getinv[$i]['variant_name2']; ?>" data-id="<?php echo $getinv[$i]['variant_name2']; ?>" data-id1="<?php echo $get_attr_name[0]['attr_name']; ?>" placeholder="<?php echo $get_attr_name[0]['attr_name'] ?>" maxlength="100" <? if($t[0]=="int"){?> onkeyup="numbercheck('variant_<?php echo $i; ?>');" <?} ?> > 
                                            </div>
                                            <?php } else{ $str = $str.'"",'; } ?>  
                                            <?php if($getinv[$i]['variant_name3'] != ""){ 
                                            $str = $str.'"variant_'.$getinv[$i]['id'].'_'.$getinv[$i]['variant_name3'].'"';
                                            $results = showQuery($getsbcatdetails[0]['template'],"field= '".$getinv[$i]['variant_name3']."'");
                                            $type = $results[0]['Type']; 
                                            $t = explode("(",$results[0]['Type']); ?> 
                                            <div class="form-group mb-2 mr-3">
                                                <label class="cc-mandatary-field"><?php echo getOriginalName($getinv[$i]['variant_name3']); ?> (<?php echo getAttributeCat($getinv[$i]['variant_name3']) ?>)</label>
                                                <input type="text" value="<?php echo $getinv[$i]['variant_value3'] ?>" class="form-control <?php if($t[0]=="int"){echo "numberinput";} ?>" id="variant_<?php echo $getinv[$i]['id'].'_'.$getinv[$i]['variant_name3']; ?>" data-id="<?php echo $getinv[$i]['variant_name3']; ?>" data-id1="<?php echo $get_attr_name[0]['attr_name']; ?>" placeholder="<?php echo $get_attr_name[0]['attr_name'] ?>" maxlength="100" <? if($t[0]=="int"){?> onkeyup="numbercheck('variant_<?php echo $i; ?>');" <?} ?> > 
                                            </div>
                                            <?php } else {$str = $str.'""'; } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border-bottom pb-2 mb-3">
                                    <div class="col-12"><h6 class="text-primary">Price and Stock</h6></div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                                <div class="form-group mb-2">
                                                    <label class="cc-mandatary-field">SKU Code</label>
                                                    <input type="text" placeholder="SKU Code" class=" form-control" name="sku" autocomplete="off" id="sku<?php echo $getinv[$i]['id']; ?>" maxlength="20" onkeyup="letter_number('sku<?php echo $getinv[$i]['id']; ?>')" value="<?php echo $getinv[$i]['sku'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                                <div class="form-group mb-2">
                                                <label class="cc-mandatary-field">Quantity (Current Stock)</label>
                                                    <input type="text" placeholder="Stock" class=" form-control" name="quantity" autocomplete="off" id="quantity<?php echo $getinv[$i]['id']; ?>" maxlength="10" onkeyup="numbercheck('quantity<?php echo $getinv[$i]['id']; ?>')" value="<?php echo $getinv[$i]['stock']-$getinv[$i]['sold']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                                <div class="form-group mb-2">
                                                    <label class="cc-mandatary-field">TAX</label>
                                                    <input type="text" placeholder="TAX" name=tax class=" form-control" autocomplete="off" id="tax<?php echo $getinv[$i]['id']; ?>" maxlength="10" onkeyup="numbercheck('tax')" value="<?php echo $getinv[$i]['tax'] ?>">
                                                </div>
                                            </div> 
                                        </div>
                                <div class="row">
                                    <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group mb-2">
                                        <label>Admin Price</label>
                                        <input type="text" placeholder="Admin Price" name="admin_price" class=" form-control" autocomplete="off" id="admin_price<?php echo $getinv[$i]['id']; ?>" maxlength="10" onkeyup="numbercheck('Sale_price<?php echo $getinv[$i]['id']; ?>')" value="<?php echo $getinv[$i]['admin_price'] ?>">
                                    </div>
                                </div>
                                    <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group mb-2">
                                        <label class="cc-mandatary-field">MRP</label>
                                        <input type="text" placeholder="MRP" name="mrp" class=" form-control" autocomplete="off" id="mrp<?php echo $getinv[$i]['id']; ?>" maxlength="10" onkeyup="numbercheck('mrp')" value="<?php echo $getinv[$i]['mrp'] ?>">
                                    </div>
                                </div>
                                                               
                                <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group mb-2">
                                        <label class="cc-mandatary-field">Vendor Price (Dealer Price)</label>
                                        <input type="text" placeholder="Vendor Price" name="regular_price" class=" form-control" autocomplete="off" id="regular_price<?php echo $getinv[$i]['id']; ?>" maxlength="10" onkeyup="numbercheck('regular_price<?php echo $getinv[$i]['id']; ?>')" value="<?php  echo $getinv[$i]['vendor_reg_price'] ?>">
                                    </div>
                                </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row border-bottom pb-2 mb-3">
                                    <div class="col-12 d-flex align-items-center justify-content-between mb-2"><h6 class="text-primary mr-3">Discount Price (Optional)</h6><button type="button" class="btn btn-primary btn-sm disb-tog-btn" data-toggle="collapse" data-target=".salecoll">Enable</button></div>
                                    <div class="col-12 collapse salecoll">
                                        <div class="row">
                                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                                <div class="form-group mb-2">
                                                    <label>Discount Price</label>
                                                    <div class="input-group">
                                                        <input type="text" placeholder="Discount Price" name="Sale_price" class=" form-control" autocomplete="off" id="Sale_price<?php echo $getinv[$i]['id']; ?>"  maxlength="10" onkeyup="numbercheck('Sale_price<?php echo $getinv[$i]['id']; ?>')" value="<?php echo $getinv[$i]['vendor_sale_price'] ?>">
                                                        <div class="input-group-append" title="<span class='cc-font-weight-6'>Help</span>" data-toggle="popover" data-trigger="hover" data-content="If you want to put discount on this product for specific duration, then enter Price, Start Date, End Date" data-placement="top"><span class="input-group-text bg-white cc-cursor-pointer"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                                <div class="form-group mb-2">
                                                    <label>Discount Start Date</label>
                                                    <div class="input-group">
                                                        <input type="text" placeholder="Discount Start Date" name="Sale_start_date" class=" form-control invsalstartdate" autocomplete="off" id="Sale_start_date<?php echo $getinv[$i]['id']; ?>" value="<?php if($getinv[$i]['vendor_sale_start_date'] != "0000-00-00 00:00:00"){ echo date("d-m-Y H:i", strtotime($getinv[$i]['vendor_sale_start_date'])); } ?>" readonly>
                                                        <div class="input-group-append" title="<span class='cc-font-weight-6'>Help</span>" data-toggle="popover" data-trigger="hover" data-content="To delete value use delete button" data-placement="top"><span class="input-group-text bg-white cc-cursor-pointer"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                                <div class="form-group mb-2">
                                                    <label>Discount End Date</label>
                                                    <div class="input-group">
                                                        <input type="text" placeholder="Discount End Date" name="Sale_end_date" class=" form-control invsalenddate" autocomplete="off" id="Sale_end_date<?php echo $getinv[$i]['id']; ?>" value="<?php if($getinv[$i]['vendor_sale_end_date'] != "0000-00-00 00:00:00"){ echo date("d-m-Y H:i", strtotime($getinv[$i]['vendor_sale_end_date'])); } ?>" readonly>
                                                        <div class="input-group-append" title="<span class='cc-font-weight-6'>Help</span>" data-toggle="popover" data-trigger="hover" data-content="To delete value use delete button" data-placement="top"><span class="input-group-text bg-white cc-cursor-pointer"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border-bottom pb-2 mb-3">
                                    <div class="col-12"><h6 class="text-primary">Post Packaging</h6></div>
                                <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group mb-2">
                                        <label>Weight (In KG)</label>
                                        <input type="text" placeholder="Packaging Weight in kg" name="weight" class=" form-control" autocomplete="off" id="weight<?php echo $getinv[$i]['id']; ?>" value="<?php echo $getinv[$i]['weight'] ?>" maxlength="10" onkeyup="numbercheck('weight<?php echo $getinv[$i]['id']; ?>')">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group mb-2">
                                        <label>Length (In CM)</label>
                                        <input type="text" placeholder="Packaging Length in Cm" name="Length" class=" form-control" autocomplete="off" id="Length<?php echo $getinv[$i]['id']; ?>" value="<?php  echo $getinv[$i]['length'] ?>" maxlength="10" onkeyup="numbercheck('Length<?php echo $getinv[$i]['id']; ?>')">
                                    </div>
                                </div> 
                                <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group mb-2">
                                        <label>Height (In CM)</label>
                                        <input type="text" placeholder="Packaging Height in Cm" name="Height" class=" form-control" autocomplete="off" id="Height<?php echo $getinv[$i]['id']; ?>" value="<?php  echo $getinv[$i]['height'] ?>" maxlength="10" onkeyup="numbercheck('Height<?php echo $getinv[$i]['id']; ?>')">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group mb-2">
                                        <label>Width (In CM)</label>
                                        <input type="text" placeholder="Packaging Width in Cm" name="Width" class=" form-control" autocomplete="off" id="Width<?php echo $getinv[$i]['id']; ?>" value="<?php  echo $getinv[$i]['width'] ?>" maxlength="10" onkeyup="numbercheck('Width<?php echo $getinv[$i]['id']; ?>')">
                                    </div>
                                </div>
                                </div>
                                <div class="row border-bottom mb-3">
                                    <div class="col-12"><h6 class="text-primary">Conditions</h6></div>
                                <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label>Cancellation Available</label>
                                        <div class="py-2">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input class="cancelation_Available custom-control-input" id="available_yes<?php echo $getinv[$i]['id']; ?>" type="radio" name="cancelation_Available<?php echo $getinv[$i]['id']; ?>" value="1" <?php if($getinv[$i]['is_cancellation_avail'] == "1" ){ echo "checked";} ?>>
                                                <label class="custom-control-label" for="available_yes<?php echo $getinv[$i]['id']; ?>">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input class="cancelation_Available custom-control-input" id="available_no<?php echo $getinv[$i]['id']; ?>" type="radio" name="cancelation_Available<?php echo $getinv[$i]['id']; ?>" value="0" <?php if($getinv[$i]['is_cancellation_avail'] == "0" ){ echo "checked";} ?>>
                                                <label class="custom-control-label" for="available_no<?php echo $getinv[$i]['id']; ?>">NO</label>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label>Return Days ( 0 = No Return)</label>
                                        <input type="text" placeholder="Return days" name="Return_days" class=" form-control" autocomplete="off" id="Return_days<?php echo $getinv[$i]['id']; ?>"  onkeyup="numbercheck('Return_days<?php echo $getinv[$i]['id']; ?>')" maxlength="3" value="<?php echo $getinv[$i]['return_days'] ?>">
                                    </div>
                                </div>

                                 <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>COD Available</label>
                                        <div class="py-2"><div class="custom-control custom-radio custom-control-inline">
                                        <input class="cod_Available custom-control-input" id="cod_available_yes<?php echo $getinv[$i]['id']; ?>" type="radio" name="cod_Available<?php echo $getinv[$i]['id']; ?>" value="1" <?php if($getinv[$i]['is_cod_avail'] == "1" ) { echo "checked";} ?>>
                                        <label class="custom-control-label" for="cod_available_yes<?php echo $getinv[$i]['id']; ?>">Yes</label></div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        <input class="cod_Available custom-control-input" id="cod_available_no<?php echo $getinv[$i]['id']; ?>" type="radio" name="cod_Available<?php echo $getinv[$i]['id']; ?>" value="0" <?php if($getinv[$i]['is_cod_avail'] == "0" ) { echo "checked";} ?>>
                                        <label class="custom-control-label" for="cod_available_no<?php echo $getinv[$i]['id']; ?>">NO</label></div></div>
                                    </div>
                                </div>
                                </div>
                                <div class="mb-2">
                        
                                <div class="card-header sec-card-head justify-content-between align-items-center p-0 mb-3 border-0"><div><h2 class="card-head-title">Upload Image</h2></div>                     </div>
                                <div class="prod-thumb-col row mx-0">
                                   
                                        <?php $getimagesinv = selectQuery(PRODIMG,"id,img_name,prod_id,apply_to_all"," prod_id = '".$getinv[$i]['id']."' order by priority ASC,id DESC");
                                        if(count($getimagesinv)){
                                        for($j=0;$j<count($getimagesinv);$j++){ ?>
                                        <div class="p-2 mb-2 mr-2 position-relative iven-thumb-col border d-flex flex-wrap align-content-center rounded" id="img_<?php echo $getimagesinv[$j]['id'] ?>">
                                                <img src="<? echo SITEURL."/".$img_location."/".$getimagesinv[$j]['img_name']; ?>" alt="pro-upload-img" class="img-fluid rounded"/> 
                                                <span class="del-upload-pic text-center removeopt pro-attr-badge-action shadow-sm btn btn-danger p-1" onclick="del_prod_img('<?php echo $getimagesinv[$j]['id'] ?>','<?php echo $getimagesinv[$j]['img_name'] ?>')"><i class="fa fa-trash"></i></span>
                                            </div>                        
                                        <? } }  else {
                                        echo "<div class='text-muted'>No Image Available</div>";
                                        } ?>
                                  
                                </div>
                      
                              
                                </div>
                                  <div class="row border-top pt-2 text-right">                                    
                                    <div class="col-12">
                                        <button type="button" id="edit_price_variant<?php echo $getinv[$i]['id']; ?>" class="px-2 px-sm-3 btn btn-primary action_btn<?php echo $getinv[$i]['id']  ?>" onclick='edit_inv("<?php echo $inv_id ?>", <?php echo $str ?>)'>Save</button>
                                        <?php if($getproduct[0]['variation_available'] == 1  && (count($getinv)> 1 )){ ?> <button type="button" class="px-2 px-sm-3 removeopt btn btn-primary" onclick="del_inv('<?php echo $getinv[$i]['id'] ?>')">Delete</button><?php } ?>
                                    <button type="button" class="px-2 px-sm-3 btn btn-primary" onclick="open_seo_modal('<?php echo $getinv[$i]['id'] ?>')" >Add SEO</button> <a class="px-2 px-sm-3 btn btn-primary" href="uploadimg.php?prodid=<?php echo base64_encode($getinv[$i]['id']); ?>"> Upload Image</a> 
                                    
                                </div></div> 
                            </div>                            
                        </div>  
                    </div>
                    <?php } ?>
                </div>
            </div> 
            <div class="card mb-0">
                <div class="card-body pb-0">
                    <div class="row m-0">
                        <?php $results = showQuery($getsbcatdetails[0]['template']);
                        $arrcol = array();
                        $arrtype = array();
                        for($i=3;$i<count($results);$i++){
                            array_push($arrcol, $results[$i]['Field']);
                            array_push($arrtype, $results[$i]['Type']);
                        }
                        for($i=0;$i<sizeOf($arrcol);$i++){
                        $t = explode("(",$arrtype[$i]);
                        $m = explode(")",$t[1]);
                        if(is_array($table_attribute)){ 
                        if (!in_array($arrcol[$i], $table_attribute)){
                        $getvalue = selectQuery($getsbcatdetails[0]['template'],"*","prod_id= ".$getinv[0]['id']." ");?>
                        <div class="mr-3">
                            <div class="form-group">
                                <label><?php echo getOriginalName($arrcol[$i]); ?><br><small> (<?php echo getAttributeCat($arrcol[$i]) ?>)</small></label> 
                                <?php if($m[0]<300) { ?>
                                <input type="text" name="<?php echo $arrcol[$i]; ?>" id="spec<?php echo $i; ?>" class="spec form-control" maxlength="<?php echo $m[0]; ?>" <? if($t[0]=="int"){?> onkeyup="numbercheck('spec<?php echo $i; ?>');" <?} ?> required value="<?php echo $getvalue[0][$arrcol[$i]] ?>">
                                <?php } else{ ?>
                                <textarea class="spec form-control <?php if($t[0]=="int"){echo "numberinput";} ?>" name="<?php echo $arrcol[$i]; ?>" id="spec<?php echo $i; ?>" maxlength="<?php echo $m[0]; ?>" <? if($t[0]=="int"){?> onkeyup="numbercheck('spec<?php echo $i; ?>');" <?} ?> required><?php echo $getvalue[0][$arrcol[$i]]  ?></textarea>
                                <? } ?>
                            </div>
                        </div>
                        <?php } } }  
                        $getvalue = selectQuery($getsbcatdetails[0]['template'],"*","prod_id= ".$getinv[0]['id']." "); ?>
                        <div class="col-md-12 px-0">
                            <label>Description</label>
                            <textarea name="description" id="prod_description"><?php echo $getvalue[0]['highlight'] ?></textarea>
                        </div>                        
                    </div>
                </div>
                <div class="card-footer py-2 text-right"><input type="button" name="update" class="btn btn-primary" id="update" value="Update" onclick="updateinfo()"></div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<div class="modal" id="addinventseo">
    <div class="modal-dialog modal-lg"><div class="modal-content" id="productseo"></div></div>
</div>
<script src="<?php echo SITEURL  ?>/js/validation.js"></script>
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$(function() {
    $('#prod_description').summernote({
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontSizes', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
        ],
        fontSizes: ['8', '10', '12', '14', '16', '18', '20', '22', '24', '26' , '28', '30', '32'],
        height: 200
    });
});
function edit_inv(id,var1,var2,var3){
    var quantity = $("#quantity"+id).val();
    var sku = $("#sku"+id).val();
    var regular_price = $("#regular_price"+id).val();
    var Sale_price = $("#Sale_price"+id).val();
    var admin_price = $("#admin_price"+id).val();
    var Sale_start_date = $("#Sale_start_date"+id).val();
    var Sale_end_date = $("#Sale_end_date"+id).val();
    var tax = $("#tax"+id).val();
    var mrp = $("#mrp"+id).val();
    var weight = $("#weight"+id).val();
    var Length = $("#Length"+id).val();
    var Height = $("#Height"+id).val();
    var Width  = $("#Width"+id).val();
    var Cancellation_days =$("input[name='cancelation_Available"+id+"']:checked").val();
    var Return_days =$("#Return_days"+id).val(); 
    var cod_Available = $("input[name='cod_Available"+id+"']:checked").val();
    var prodtable = '<?php echo  $getsbcatdetails[0]['template'] ?>';
    var basic_name = '<?php echo  addslashes($getproduct[0]['prod_name']) ?>';
    var sub_cat = '<?php echo $getproduct[0]['sub_cat'] ?>'
    if(var1 != "" ){variant_1_name = $("#"+var1).attr('data-id'); variant_1_name1 = $("#"+var1).attr('data-id1');variant_1_val = $("#"+var1).val();} else {variant_1_name ="";variant_1_val=""; }
    if(var2 != "" ){variant_2_name = $("#"+var2).attr('data-id');variant_2_name1 = $("#"+var2).attr('data-id1');variant_2_val = $("#"+var2).val();} else {variant_2_name ="";variant_2_val=""; }
    if(var3 != "" ){variant_3_name = $("#"+var3).attr('data-id'); variant_3_name1 = $("#"+var3).attr('data-id1');variant_3_val = $("#"+var3).val();} else {variant_3_name ="";variant_3_val=""; }
    if(var1 != "" &&  variant_1_val == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill "+variant_1_name1+" detail").delay(3000).fadeOut();
    } else if(var2 != "" &&  variant_2_val == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill "+variant_2_name1+" detail").delay(3000).fadeOut();
    } else if(var3 != "" &&  variant_3_val == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill "+variant_3_name1+" detail").delay(3000).fadeOut();
    } else if(sku == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter SKU code").delay(3000).fadeOut()
    } else if(quantity == "" ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter quantity").delay(3000).fadeOut()
    } else if(tax == ""  ){
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please ente tax value").delay(3000).fadeOut()
    } else if(mrp == ""  || mrp == "0") {
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter MRP value").delay(3000).fadeOut()
    } else if(regular_price == ""  || regular_price == "0"){
      $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter vendor price").delay(3000).fadeOut()
    } else if((Sale_start_date == "") &&   Sale_price != "0" && Sale_price != ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select discount start date").delay(3000).fadeOut()
    } else if( (Sale_end_date == "")  && Sale_price != "0" && Sale_price != "") {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select discount end date").delay(3000).fadeOut()
    } else if(Sale_start_date != "" && Sale_end_date != "" && (Sale_price == "0" || Sale_price == "" )){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter discount price").delay(3000).fadeOut()
    } else {
        $("#edit_price_variant"+id).attr("disabled",true);
        var info ={basic_name:basic_name,variant_1_name:variant_1_name,variant_2_name:variant_2_name,variant_3_name:variant_3_name,inv_id:id,variant_1_val:variant_1_val,variant_2_val:variant_2_val,variant_3_val:variant_3_val,quantity:quantity,regular_price:regular_price,Sale_price:Sale_price,admin_price:admin_price,Sale_start_date:Sale_start_date,Sale_end_date:Sale_end_date,weight:weight,Length:Length,Height:Height,Width:Width,tax:tax,mrp:mrp,prodtable:prodtable,Cancellation_days:Cancellation_days,Return_days:Return_days,sku:sku,cod_Available:cod_Available,action:"inv_update"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php", 
            data:info,
            success:function(response){
                $("#edit_price_variant"+id).attr("disabled",false);
                if(response == 1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Inventory updated successfully").delay(3000).fadeOut()
                } else if(response == 2){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product with same SKU code allready exist").delay(3000).fadeOut()
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time ").delay(3000).fadeOut()
                }
            }
        });
    }
}
function updateinfo(invarray1){
    var prodtable = '<?php echo $getsbcatdetails[0]['template'] ?>';
    var prod_name  = '<?php echo addslashes($getproduct[0]['prod_name']) ?>';
    var prodid = <?php echo $prod_id; ?>;
    if(invarray1 == "" || invarray1 == undefined) {  invarray = '<?php echo implode(",",$invarray);  ?>'; }  else{ invarray = invarray1}
    prod_description = $("#prod_description").val();
    dispmsg = 0;
    var fldsel = [];
    $('[id^=spec]').each(function() {
        if ($.trim($(this).val()) != "") {
            var str = $(this).val();
            cmpstr = $(this).attr('name')+"|"+$(this).val()
            fldsel.push(cmpstr);
        } else { dispmsg = 1; }
    });
    if(dispmsg == 1 || $.trim(prod_description) == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill  all attribute Details").delay(3000).fadeOut()
    } else{
        var info = {specifications:fldsel,prodtable: prodtable,invarray:invarray,prodid: prodid,prod_description:prod_description,action:"update_attribute"}
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
              if(invarray1 == undefined){
                if(response == 1 ){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Attribute updated successfully").delay(3000).fadeOut();                    
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Try after some time").delay(3000).fadeOut()
                }
              }  
            }
        });
    } 
}
function approve_disappove_inv(v1){
    var requestedid=v1;
    var c = $("#approve_inv"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="approveded";} else {status = 0; res="not approved"; }
    var info = {requestedid:requestedid,status:status,action:"approve_inv"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Inventory " +res).delay(3000).fadeOut();
            $("#inventry").load( " #inventry" );
            } else{            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
function active_deactive(v1){
    var requestedid = v1;
    var c = $("#activ_deact"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="activated";} else {status = 0; res="deactivated"; }
    var info={requestedid:requestedid,status:status,action:"Active_deactive_inv"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Inventory " +res).delay(3000).fadeOut();
            } else{                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
$('.invsalstartdate').datetimepicker({
    ignoreReadonly: true, minDate:moment(), format: 'DD-MM-YYYY HH:mm', disabledTimeIntervals:false
}).on("dp.change", function (e){
    $(".invsalenddate").data("DateTimePicker").minDate(e.date);
}).on("dp.show", function (e) {
    $(".invsalstartdate").data("DateTimePicker").minDate(e.date);
});
$('.invsalenddate').datetimepicker({
    ignoreReadonly: true, minDate:moment(), format: 'DD-MM-YYYY HH:mm', disabledTimeIntervals:false
});
$("#next1").prop("disabled",false);
$("#Basic_update").click(function(){
    prod_company = $("#prod_company").val();
    hsn_code = $('#hsn_code').val();
    prod_name = $("#prod_name").val();
    var prodid = <?php echo $prod_id; ?>;
    if(hsn_code == ""){
        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Please enter HSN code").delay(3000).fadeOut()
    }
    else {
        var info = {prod_name:prod_name,prod_company:prod_company,hsn_code:hsn_code,prodid:prodid,action:"update_basic"}
        $.ajax({
            type:"POST",
            url:"<?php echo VENDORURL ?>product/ajaxdata.php",
            data:info,
            success:function(response){
                if(response == 1 ){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time ").delay(3000).fadeOut();
                }
            }    
        });
    }
});
$(".invent-body input").on("click", function(){ $(this).select(); });
function del_inv(inv_id){ msg = "Do u really want to delete this  Inventory?"; del_alertbox(msg,inv_id,"del_inventory_db"); }

function del_inventory_db(id,type) {
    template_name  = "<?php echo  $getsbcatdetails[0]['template'] ?>";
    info  = {prod_id:id,template_name:template_name  ,action:"Delete_inventory"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product inventory deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $( "#inventry").load(" #inventry" );
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
function open_seo_modal(prodid){
    info = {prodid:prodid,action:'AddSEO'}
    $.ajax({
        type:"POST",
        url: "<?php echo VENDORURL ?>product/ajaxdata.php",
        data:info,
        success:function(response){
            $("#productseo").replaceWith(response);
            $("#addinventseo").modal("show");
        }
    })
}
function addseodetails(prodid){
    var pagetitle = $("#pagetitle").val(); var keywords = $("#keywords").val(); var metadesc = $("#metadesc").val();
    if($.trim(pagetitle).length == 0){
        $('.seo_alert_msgs').fadeIn().html('Please enter page title').addClass("alert alert-danger").delay(5000).fadeOut();
    } else if($.trim(metadesc).length == 0){
        $('.seo_alert_msgs').fadeIn().html('Please enter meta description').addClass("alert alert-danger").delay(5000).fadeOut();
    } else if($.trim(keywords).length == 0){
        $('.seo_alert_msgs').fadeIn().html('Please enter keywords').addClass("alert alert-danger").delay(5000).fadeOut();
    } else {
        info = {prodid:prodid,pagetitle:pagetitle,keywords:keywords,metadesc:metadesc,action:'add_seo'}
        $.ajax({
            type:"POST",
            url: "<?php echo VENDORURL ?>product/ajaxdata.php",
            data:info,
            success:function(response){
                response=response.trim();
                if(response=="1"){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("SEO details updated successfully").delay(3000).fadeOut();
                    $("#addinventseo").modal("hide");
                } else if(response=="0"){
                 $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                }
            }
        })
    }        
}
function del_prod_img(imgid,type){ del_alertbox("Do you really want to delete this Image?", imgid,"del_image_db",type); }
function del_image_db(id,type){
    info = {imgid:id,action:"Delete_image",img_name:type}
    $.ajax({
        type:"POST",
        url:"<?php echo VENDORURL ?>product/ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Image deleted successfully").delay(3000).fadeOut();
                $("#img_"+id).hide();
                $("#del_popup").modal("hide"); 
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}
$(".disb-tog-btn").click(function(){
    $(this).text() === 'Enable' ? $(this).text('Disable') : $(this).text('Enable');
});
$('[data-toggle="popover"]').popover({html : true});
</script>
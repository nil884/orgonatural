<? include("../../includes/configuration.php"); include("../../classes/product.php");
$prod = new Product();
if($action == "get_category"){ ?>
<option value="">Select <?php echo ucwords($type) ?> Category</option>
<?php $getcat=selectQuery(PRODCAT,"id,template,cat_name","isActive='1' AND parent_id=".$cat." order by cat_name ASC");
if(count($getcat)){
for($i=0;$i<count($getcat);$i++) { ?>
<option value="<?php echo $getcat[$i]['id']; ?>" data-id="<?php echo $getcat[$i]['template'];?>"><?php echo $getcat[$i]['cat_name']; ?></option>
<? } } else { ?>
<option value="">Not Found</option>
<?php } ?>
<?php }
if($action == "checkavailable"){
    $chekcproductname = selectQuery(PRODINFO,"id","prod_name='".ucwords(addslashes($prodname))."'  and vendor= ".$_SESSION['seller']." ");
    if($chekcproductname[0]['id'] != 0 ){ echo 1;} else{ echo 0;}
}
if($action == "checkavailable_sku"){
    $chekcproductname = selectQuery(PRODINFO,"id","sku='".$sku."' ");
    if($chekcproductname[0]['id'] != 0 ){ echo 1;} else{ echo 0;}
}
if($action == "select_attribute_for_variation"){ ?>
<div class="alltemplate_attribute">
    <div class="card">
        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Step2. Select Specification for Price Variation</h5></div></div>
        <div class="card-body">
            <h6 class="mb-3">Select Specification</h6>
            <?php $getsbcatdetails = selectQuery(PRODCAT,"template","id=".$_REQUEST['subcategory']);
            if($getsbcatdetails[0]['template']!=""){
                $results = showQuery($getsbcatdetails[0]['template']);
                $arrcol = array();
                $arrtype = array();
                for($i = 3;$i<count($results);$i++){
                    array_push($arrcol, $results[$i]['Field']);
                    array_push($arrtype, $results[$i]['Type']);
                } ?>
                <div class="col-md-12">
                    <div class="row">
                        <?php for($i=0;$i<sizeOf($arrcol);$i++){    
                        $get_attr_name = selectQuery(PRODATTR,"attr_name","attr_for_template= '".$arrcol[$i]."'");?>
                        <div class="custom-control custom-checkbox mr-3 mb-3">
                            <input class="form-check-input custom-control-input" type="checkbox" value="<?php echo $get_attr_name[0]['attr_name'] ?>" name="attribute" id="prcvarchk<?php echo $i; ?>" data-id="<?php echo $arrcol[$i]; ?>"> 
                            <label class="custom-control-label" for="prcvarchk<?php echo $i; ?>"><?php echo getOriginalName($arrcol[$i]); ?> (<?php echo getAttributeCat($arrcol[$i]) ?>)</label>
                        </div>
                        <? } ?>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" name="check_variation" id="check_variation"  onclick="getttributeforvariation()">Select Attribute For Vairation</button>
            <?php } ?>    
        </div>
    </div>
</div>
<?php }
if($action == "clear_attribute_in_session"){
    unset($_SESSION['table_attribute']);
    unset($_SESSION['inventry_array']);
    unset($_SESSION['inventry_attribute_array']);
    echo 1;
}
if($action == "save_variation_attribute"){
    $_SESSION['table_attribute'] = $table_attribute;
    echo 1;
}
if($action == "getspecification" ){ 
    if($_SESSION['table_attribute'] == "") { $table_attribute = array(); } else { $table_attribute = $_SESSION['table_attribute']; } ?>
    <div class="specification">
    <div class="card">
    <?php ?>  
    <?php $getsbcatdetails = selectQuery(PRODCAT,"template","id=".$_REQUEST['subcategory']);
    if($getsbcatdetails[0]['template']!=""){
        $results = showQuery($getsbcatdetails[0]['template']);
        $arrcol = array();
        $arrtype = array();
        for($i=3;$i<count($results);$i++){
            array_push($arrcol, $results[$i]['Field']);
            array_push($arrtype, $results[$i]['Type']);
        }
        if(count($arrcol)) {
            echo '<div class="card-header sec-card-head justify-content-between align-items-center">
            <div><h5 class="card-head-title">Step3. Product Attribute Details</h5></div>
            </div><div class="card-body"><div class="row mx-0">'; 
            if( count($arrcol) == count($table_attribute)  ) {
                echo "No Attribute Available";
            }
            else {
            
            for($i=0;$i<sizeOf($arrcol);$i++){
            $t=explode("(",$arrtype[$i]);
            $m=explode(")",$t[1]); 
            if(is_array($table_attribute)) { 
                if(!in_array($arrcol[$i], $table_attribute)){?>
                <div class="mb-2 mr-3">
                    <label class="cc-mandatary-field"><?php echo getOriginalName($arrcol[$i]); ?> (<?php echo getAttributeCat($arrcol[$i])  ?>)</label>
                    <?php if($m[0]<300){ ?>
                    <input type="text" name="<?php echo $arrcol[$i]; ?>" id="spec<?php echo $i; ?>" class="spec form-control" maxlength="<?php echo $m[0]; ?>" <? if($t[0]=="int"){?> onkeyup="numbercheck('spec<?php echo $i; ?>');" <?}?> required>
                    <?php } else { ?>
                    <textarea class="spec form-control <?php if($t[0]=="int"){echo "numberinput";} ?>" name="<?php echo $arrcol[$i]; ?>" id="spec<?php echo $i; ?>" maxlength="<?php echo $m[0]; ?>" <? if($t[0]=="int"){?> onkeyup="numbercheck('spec<?php echo $i; ?>');" <? } ?> required></textarea>
                    <? } ?>
                </div>
                <?} } } } ?> </div></div> <? } } ?>
            </div>            
            <div class="card">
            <div class="card-header sec-card-head justify-content-between align-items-center">
            <div><h5 class="card-head-title">Step4. Inventory - <span class="text-primary">Please enter all prices inclusive of Taxes</span></h5></div> 
            </div>
                <div class="card-body pb-2">
                    <div class="alert alert-info">
                        <h6><b>Important Note : </b></h6>
                        <ul class="mb-0 pl-3">
                            <li class="mb-1"><b>MRP :</b> This will be maximum retail price</li>
                            <li class="mb-1"><b>Vendor Price (Dealer Price) :</b> This will be a price offerd by the vendor to the admin and visible only to the admin (and not to the end user)</li>
                            <li><b>Discount Price :</b> This will be a special price offered by the vendor to the admin for the sepcific duration, and this price will be visible only to the admin (and not to the end user)</li>
                        </ul>
                    </div>
                    <?php if(is_array($table_attribute)  && count($table_attribute)) { ?>
                    <div class="border-bottom pb-2 mb-3 row">
                    <div class="col-12"><h6 class="text-primary">Attributes</h6>
                    <div class="row mx-0">
                    <? for($i=0;$i<count($table_attribute);$i++){ 
                    $results = showQuery($getsbcatdetails[0]['template'],"field= '".$table_attribute[$i]."'");
                    $type = $results[0]['Type']; 
                    $t=explode("(",$results[0]['Type']); ?>
                        <div class="form-group mb-2 mr-3">
                            <div><label class="cc-mandatary-field"><?php echo getOriginalName($table_attribute[$i]); ?> (<?php echo getAttributeCat($table_attribute[$i]) ?>)</label></div>
                            <input type="text" class="form-control variant_<?php echo $i; ?> <?php if($t[0]=="int"){echo "numberinput";} ?>" id="variant_<?php echo $i; ?>" data-id="<?php echo $table_attribute[$i]; ?>"  data-id1="<?php echo getOriginalName($table_attribute[$i]); ?>" placeholder="<?php echo $get_attr_name[0]['attr_name'] ?>" maxlength="100" <? if($t[0]=="int"){?> onkeyup="numbercheck('variant_<?php echo $i; ?>');" <?} ?>> 
                        </div>
                    <?php } ?> </div></div></div> <? } ?>
                    <div class="border-bottom pb-2 mb-3 row">
                        <div class="col-12">
                        <div class="row">
                    <div class="col-12"><h6 class="text-primary">Price and Stock</h6></div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label class="cc-mandatary-field">SKU Code</label>
                            <input type="text" placeholder="SKU Code" class="form-control" name="sku" autocomplete="off" id="sku" maxlength="20" onkeyup="letter_number('sku')">
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label class="cc-mandatary-field">Quantity</label>
                            <input type="text" placeholder="Stock" class="form-control" name="quantity" autocomplete="off" id="quantity" maxlength="10" onkeyup="numbercheck('quantity')">
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label class="cc-mandatary-field">TAX</label>
                            <input type="text" placeholder="TAX" name=tax class="form-control" autocomplete="off" id="tax" maxlength="10" onkeyup="numbercheck('tax')">
                        </div>
                    </div>
                        </div>
                    <div class="row">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label class="cc-mandatary-field">MRP</label>
                            <input type="text" placeholder="MRP" name="mrp" class="form-control" autocomplete="off" id="mrp" maxlength="10" onkeyup="numbercheck('mrp')">
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label class="cc-mandatary-field">Vendor Price (Dealer Price)</label>
                            <input type="text" placeholder="Vendor Price" name="regular_price" class=" form-control" autocomplete="off" id="regular_price" maxlength="10" onkeyup="numbercheck('regular_price')">
                        </div>
                    </div>
                    </div>
                </div></div>
                <div class="row border-bottom pb-2 mb-3">    
                    <div class="col-12 d-flex align-items-center justify-content-between mb-2"><h6 class="text-primary mb-0 mr-3">Discount Price (Optional)</h6><button type="button" class="btn btn-primary btn-sm disb-tog-btn" data-toggle="collapse" data-target=".salecoll" onclick="chgdistbtntext(this)">Enable</button></div>
                    <div class="col-12 collapse salecoll">
                        <div class="row">
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                                <div class="form-group mb-2">
                                    <label>Discount Price</label>
                                    <div class="input-group">
                                    <input type="text" placeholder="Discount Price" name="Sale_price" class="form-control" autocomplete="off" id="Sale_price" maxlength="10" onkeyup="numbercheck('Sale_price')">
                                    <div class="input-group-append" data-toggle="modal" data-target="#sellinfo"><span class="input-group-text bg-white cc-cursor-pointer"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div></div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                                <div class="form-group mb-2">
                                    <label>Discount Start Date</label>
                                    <input type="text" placeholder="Discount Start Date" name="Sale_start_date" class="form-control" autocomplete="off" id="Sale_start_date" readonly>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                                <div class="form-group mb-2">
                                    <label>Discount End Date</label>
                                    <input type="text" placeholder="Discount End Date" name="Sale_end_date" class="form-control" autocomplete="off" id="Sale_end_date" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row border-bottom pb-2 mb-3">
                    <div class="col-12"><h6 class="text-primary">Post Packaging</h6></div>
                    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label>Weight (In KG)</label>
                            <input type="text" placeholder="Packaging Weight in kg" name="weight" class="form-control" autocomplete="off" id="weight" onkeyup="numbercheck('weight')" maxlength="10">
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label>Length (In CM)</label>
                            <input type="text" placeholder="Packaging Length in Cm" name="Length" class="form-control" autocomplete="off" id="Length" onkeyup="numbercheck('Length')" maxlength="10">
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label>Height (In CM)</label>
                            <input type="text" placeholder="Packaging Height in Cm" name="Height" class="form-control" autocomplete="off" id="Height" onkeyup="numbercheck('Height')" maxlength="10" >
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group mb-2">
                            <label>Width (In CM)</label>
                            <input type="text" placeholder="Packaging Width in Cm" name="Width" class="form-control" autocomplete="off" id="Width" onkeyup="numbercheck('Width')" maxlength="10" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"><h6 class="text-primary">Conditions</h6></div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <div><label class="pb-2">Cancellation Available</label></div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="cancelation_Available custom-control-input" id="available_yes" type="radio" name="cancelation_Available"  value="1"  >
                                <label class="custom-control-label" for="available_yes">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="cancelation_Available custom-control-input" id="available_no" type="radio" name="cancelation_Available"  value="0" checked>
                                <label class="custom-control-label" for="available_no">NO</label>
                            </div> 
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <label>Return Days ( 0 = No Return)</label>
                            <input type="text" placeholder="Return days" name="Return_days" class=" form-control" autocomplete="off" id="Return_days" onkeyup="numbercheck('Return_days')" maxlength="2" value="0">
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <div><label class="pb-2">COD Available</label></div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="cod_Available custom-control-input" id="cod_available_yes" type="radio" name="cod_Available"  value="1"  >
                                <label class="custom-control-label" for="cod_available_yes">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="cod_Available custom-control-input" id="cod_available_no" type="radio" name="cod_Available"  value="0" checked>
                                <label class="custom-control-label" for="cod_available_no">NO</label>
                            </div> 
                        </div>
                    </div>
                </div>
                </div>
                <?php if(is_array($table_attribute)  && count($table_attribute)) { ?>
                <div class="card-footer text-right py-2"><button type="button" class="btn btn-primary" id="add_more_price_variant" onclick = "addvariation()">Add More</button></div>
                
                
            </div>
            <div class="inventry"></div>
            <?php } ?>
<script> 
    $("#sku").keyup(function(){
        sku = $.trim($("#sku").val());
        var info = {sku:sku,action:"checkavailable_sku"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response = $.trim(response);
                $("#sku_exist").val(response);
            }
        });
    });
</script>
</div>
<?php }
if($action =="addinv_new"){
    if( $_SESSION['inventry_array'] == ""){
        $inventry_array = array();
        $inventry_attribute_array = array();
        $sku_array = array();
    }
    else {
        $inventry_array = $_SESSION['inventry_array'];
        $inventry_attribute_array =  $_SESSION['inventry_attribute_array'];
         $sku_array = $_SESSION['sku_array'];
    } if($variant_0_name  != "" and $variant_0_val != ""){
        $str = $variant_0_name."^".$variant_0_val;
    } if($variant_1_name  != "" and $varinat_1_val != ""){
        $str1 = $variant_1_name."^".$varinat_1_val;
    } if($variant_2_name  != "" and $varinat_2_val != ""){      
        $str2 = $variant_2_name."^".$varinat_2_val;
    }
    $attribute_array = $str."|".$str1."|".$str2;
    $invetry = $str."|".$str1."|".$str2."|".$quantity."|".$regular_price."|".$Sale_price."|".$Sale_start_date."|".$Sale_end_date."|".$weight."|".$Length."|".$Height."|".$Width."|".$mrp."|".$tax."|".$Cancellation_days."|".$Return_days."|".$sku."|".$cod_Available;
    if(!(in_array($sku, $sku_array))){
    if(!(in_array($attribute_array, $inventry_attribute_array))){
        array_push($inventry_attribute_array,$attribute_array);
        array_push($inventry_array,$invetry);
        array_push($sku_array,$sku);
        $_SESSION['inventry_array'] = $inventry_array ;
        $_SESSION['inventry_attribute_array'] =  $inventry_attribute_array;
        $_SESSION['sku_array'] =  $sku_array;
        echo 1;
    }
   else{ echo 2;}
}
else { echo 3; }
}
if($action == "getcount"){ echo count($_SESSION['inventry_array']); }
if($action == "view_inventry"){
$atrribute_array = $_SESSION['table_attribute'];
$inventry_array = $_SESSION['inventry_array']; ?>
<div class="inventry">
    <?php if(count($_SESSION['inventry_array'])){ ?>
    <div class="card">
        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Inventory Details</h5></div></div>
        <div class="card-body"> 
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr><?php for($i=0;$i<count($atrribute_array);$i++){ 
                            $get_attr_name = selectQuery(PRODATTR,"attr_name","attr_for_template= '".$atrribute_array[$i]."'");
                            ?><th><?php echo $get_attr_name[0]['attr_name']; ?> </th>  <?php } ?>
                            <th>SKU </th><th>Quantity</th><th>Tax</th><th>MRP</th><th>Vendor Price</th><th>Discount Price</th> <th>Discount Start Date</th><th>Discount End Date</th><th>Weight</th><th>Length</th><th>Height</th>
                            <th>Width</th> <th>Cancellation Available</th><th>Return Days</th><th>COD Available</th> <th>Delete</th></tr>
                        </thead>
                        <tbody class="text-muted">
                            <?php for($k=0;$k<count($inventry_array);$k++){
                            $getinventry = explode("|",$inventry_array[$k]); ?>
                            <tr class="row_<?php echo $k; ?>">
                                <td><?php $val1 = explode("^",$getinventry[0]); echo $val1[1] ?></td>
                                <? if($getinventry[1] != ""){ ?> <td><?php $val2 = explode("^",$getinventry[1]); echo $val2[1] ?></td><?php } ?>
                                <? if($getinventry[2] != ""){ ?> <td><?php $val3 = explode("^",$getinventry[2]); echo $val3[1] ?></td><?php } ?>
                                <td><?php if($getinventry[16] != ""){ echo $getinventry[16];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[3] != ""){ echo $getinventry[3];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[13] != ""){ echo $getinventry[13];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[12] != ""){ echo $getinventry[12];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[4] != ""){ echo $getinventry[4];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[5] != ""){ echo $getinventry[5];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[6] != ""){ echo $getinventry[6];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[7] != ""){ echo $getinventry[7];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[8] != ""){ echo $getinventry[8];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[9] != ""){ echo $getinventry[9];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[10] != ""){ echo $getinventry[10];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[11] != ""){ echo $getinventry[11];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[14] == 1){ echo "Yes";  } else { echo "NO"; } ?></td>
                                <td><?php if($getinventry[15] != ""){ echo $getinventry[15];} else { echo "NA"; } ?></td>
                                <td><?php if($getinventry[17] == 1){ echo "Yes";  } else { echo "NO"; } ?></td>
                                <td><button type="button" onclick="delete_inv('<?php echo $k ?>','Inventry')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o red" aria-hidden="true"></i></button></td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
<?php } ?> <?php } ?>
<?php
if($action == "remove_inv"){
    $inventry_array = $_SESSION['inventry_array'];
    $inventry_attribute_array = $_SESSION['inventry_attribute_array'];
    unset($inventry_array[$invid]);
    unset($inventry_attribute_array[$invid]);
    $_SESSION['inventry_array'] = array_values($inventry_array);
    $_SESSION['inventry_attribute_array'] = array_values($inventry_attribute_array);
    echo 1;
}
if($action == "inv_update"){
    $chekcproductsku = selectQuery(PRODINFO,"id","sku='".$sku."' and id <> '".$inv_id."' ");
    if(count($chekcproductsku)){ echo 2; }
    else {
        $todaydate = date("Y-m-d H:i:s");
        if($Sale_start_date != ""){$Sale_start_date = date("Y-m-d H:i:s", strtotime($Sale_start_date)); }
        if($Sale_end_date != ""){$Sale_end_date =  date("Y-m-d H:i:s", strtotime($Sale_end_date)); }
        $invetory = array('stock'=>  $quantity,'sold'=> 0, 'sku'=> $sku, 'mrp' => $mrp, 'tax' => $tax, 'vendor_reg_price' =>$regular_price, 'vendor_sale_price'=>$Sale_price, 'vendor_sale_start_date'=> $Sale_start_date, 'vendor_sale_end_date'=>$Sale_end_date, 'weight'=> $weight, 'length'=>$Length, 'height'=>$Height, 'width' =>$Width, 'is_cancellation_avail' =>  $Cancellation_days, 'Return_days' => $Return_days ,"is_cod_avail" => $cod_Available);
        if($admin_price != "" && $admin_price != "0"){ $invetory['final_price'] = $admin_price; }
        else { 
            if($Sale_price != "" && $Sale_start_date != "0000-00-00 00:00:00" && $Sale_end_date != "0000-00-00 00:00:00" && $Sale_start_date <= $todaydate &&  $todaydate <= $Sale_end_date){ 
            $final_price = $Sale_price; }
            else { $final_price = $mrp; }
            $invetory['final_price'] = $final_price; 
        }
        $specification = array();
        if($variant_1_name!= "" ){
            $invetory['variant_value1'] = ucwords(addslashes($variant_1_val));
            $specification[$variant_1_name] = ucwords(addslashes($variant_1_val));
        } if($variant_2_name!= "" ){
            $invetory['variant_value2'] = ucwords(addslashes($variant_2_val));
            $specification[$variant_2_name] = ucwords(addslashes($variant_2_val));
        } if($variant_3_name!= "" ){
            $invetory['variant_value3'] = ucwords(addslashes($variant_3_val));
            $specification[$variant_3_name] = ucwords(addslashes($variant_3_val));
        } if($variant_1_name != ""  || $variant_2_name!= "" || $variant_3_name!= "" ){
            $productData = $prod->getParentName($inv_id); 
            $basic_namenew = $productData;
        }
        else{
            $productData=$prod->getShortDetails($inv_id); 
            $basic_namenew = $productData[0]['prod_name'];
        }
        $product_name = trim(ucwords(addslashes($basic_namenew." ".$variant_1_val." ".$variant_2_val." ".$variant_3_val)));
        $url = $prod->createProdURL($product_name,$sub_cat,$inv_id);
        $invetory['prod_name'] = $product_name;
        $invetory['url_title'] = $url; 
        $upate_inv = updateQuery(PRODINFO, $invetory, "id=" . $inv_id);
        $update_table = updateQuery($prodtable, $specification, "prod_id =" . $inv_id);
        if($upate_inv){ echo 1; }
        else { echo 0; }
    }
}
if($action == "active_deactive"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('isActive'=>$status );
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
    if ($upategetsubcat) {
        $upategetsubcat = updateQuery(PRODINFO, $data, "parent_id=" . $requestid);
        echo 1;
    }
    else { echo 0; }
}
if($action == "Active_deactive_inv"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('isActive' => $status);
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
    if ($upategetsubcat){ echo 1; }
    else{ echo 0; }
}
if($action =="update_attribute"){
    $invarray = explode(",",$invarray);
    if(!empty($specifications)){
        for($i=0;$i<count($specifications);$i++){
            $split = explode("|",$specifications[$i]) ;
            $templatedata[$split['0']] = addslashes($split['1']);
        }
    }
    $templatedata['highlight'] = addslashes($prod_description);
    //$upategetsubcat = updateQuery($prodtable, $templatedata, "prod_id =" . $prodid);
    for($i=0;$i<count($invarray);$i++){ $upategetsubcat = updateQuery($prodtable, $templatedata, "prod_id =" . $invarray[$i]); }
    echo "1"; 
}

if($action == "addinv_edit"){
    $chekcproductsku = selectQuery(PRODINFO,"id","sku='".$sku."'");
    if(count($chekcproductsku)){ echo "2"; }
    else{
        $todaydate = date("Y-m-d H:i:s");
        if($Sale_start_date != ""){$Sale_start_date = date("Y-m-d H:i:s", strtotime($Sale_start_date)); }
        if($Sale_end_date != ""){$Sale_end_date =  date("Y-m-d H:i:s", strtotime($Sale_end_date)); }
        if($Sale_price != "" && $Sale_start_date != "0000-00-00 00:00:00" && $Sale_end_date != "0000-00-00 00:00:00" && $Sale_start_date <= $todaydate &&  $todaydate <=$Sale_end_date )
        { $final_price = $Sale_price; }
        else { $final_price = $mrp; }
        $check_main = selectQuery(PRODINFO,"isApproved,isActive","id=" . $prodid); 
        $invetory = array('parent_cat' => $parent_cat, 'master_cat'  => $master_cat, 'sub_cat' => $sub_cat, 'prod_company' => ucwords(addslashes($prod_company)), 'product_type' =>$product_type, 'hsn_code'=>$hsn_code, 'vendor' => $_SESSION['seller'], 'insert_date'=>date('Y-m-d H:i:s'), 'sku' =>$sku, 'stock'=>  $quantity, 'mrp' => $mrp, 'tax' => $tax, 'vendor_reg_price' =>$regular_price, 'vendor_sale_price'=>$Sale_price, 'final_price' =>   $final_price, 'vendor_sale_start_date'=>$Sale_start_date, 'vendor_sale_end_date'=>$Sale_end_date, 'weight'=> $weight, 'length'=>$Length, 'height'=>$Height, 'width' =>$Width, 'parent_id'=>$prodid, 'isApproved' => $check_main[0]['isApproved'], 'isActive' => $check_main[0]['isActive'], 'is_cancellation_avail' => $Cancellation_days, 'Return_days' => $Return_days ,'is_cod_avail' => $cod_Available);
        $get_vendor_details = selectQuery(VENDOR,"auto_approve_product","dealer_id=".$_SESSION['seller']); 
        if($get_vendor_details[0]['auto_approve_product'] == 1){
            $invetory['isApproved'] = 1;
            $invetory['isActive'] = 1;
            $invetory['approved_by'] = 'Auto';
            $invetory['approve_date'] = date('Y-m-d H:i:s');
        }
        $specification = array();
        if($variant_1_name!= "" ){
            $invetory['variant_value1'] = ucwords(addslashes($variant_1_val)); $invetory['variant_name1'] = $variant_1_name;
            $specification[$variant_1_name] = ucwords(addslashes($variant_1_val)); 
            $qury_string = "variant_name1 ='".$variant_1_name."' and  variant_value1 = '".ucwords(addslashes($variant_1_val))."'";
        }
        if($variant_2_name!= "" ){
            $invetory['variant_value2'] = $variant_2_val; $invetory['variant_name2'] = $variant_2_name;
            $specification[$variant_2_name] = $variant_2_val;
            $qury_string =  $qury_string." and variant_name2 ='".$variant_2_name."' and  variant_value2 = '".ucwords(addslashes($variant_2_val))."'";
        }
        if($variant_3_name!= "" ){
            $invetory['variant_value3'] = $variant_3_val; $invetory['variant_name3'] = $variant_3_name;
            $specification[$variant_3_name] = $variant_3_val;
            $qury_string =  $qury_string." and variant_name3 ='".$variant_3_name."' and  variant_value3 = '".ucwords(addslashes($variant_3_val))."'";
        }
        $product_name = trim(ucwords(addslashes($prod_name." ".$variant_1_val." ".$variant_2_val." ".$variant_3_val)));
        $url = $prod->createProdURL($product_name,$sub_cat);
        $invetory['prod_name'] = $product_name;
        $invetory['url_title'] = $url;
        $check_exist = selectQuery(PRODINFO,"count(id) as prod "," ".$qury_string." and vendor = '".$_SESSION['seller']."' and parent_id = '".$prodid."'  ");
        if($check_exist[0]['prod'] != 0){ echo "0";} else{
            $ins_inv = insertQuery(PRODINFO, $invetory);
            if($ins_inv){
                $specification['prod_id'] = $ins_inv;
                $ins_spec = insertQuery($prodtable, $specification);
                if($ins_spec){ echo $ins_inv; }
                $getimg = selectQuery(PRODIMG,"distinct img_name ","prod_id in (".$invarray.") and apply_to_all = '1' ");
                for($i=0;$i<count($getimg);$i++){
                    $imgdata = array("img_name"=> $getimg[$i]['img_name'],"prod_id" =>$ins_inv,'apply_to_all' => '1'  );
                    $ins_img = insertQuery(PRODIMG, $imgdata);
                }
            }
        }
    }
}
if($action == "update_basic"){
    $check_cat = selectQuery(PRODINFO,"id,sub_cat,variant_value1,variant_value2,variant_value3","id=" . $prodid); 

        $data = array("prod_company" => ucwords(addslashes($prod_company)), "hsn_code" => $hsn_code, "prod_name" => ucwords(addslashes($prod_name)),);
    $product_name = trim(ucwords(addslashes($prod_name)));
    $url = $prod->createProdURL($product_name,$check_cat[0]['sub_cat'],$check_cat[0]['id']);
    $data['url_title'] = $url;
   
    $data1 = array("prod_company" => ucwords(addslashes($prod_company)), "hsn_code" => $hsn_code,);
    $upatebasic = updateQuery(PRODINFO, $data, "id =" . $prodid); 
    $check_sub = selectQuery(PRODINFO,"id,sub_cat,variant_value1,variant_value2,variant_value3","parent_id=" . $prodid); 

    for($i=0;$i<count($check_sub);$i++){
        if(count($check_sub)){
            $product_name= trim(ucwords(addslashes($prod_name." ".$check_sub[$i]['variant_value1']." ".$check_sub[$i]['variant_value2']." ".$check_sub[$i]['variant_value3'])));
            $url=$prod->createProdURL($product_name,$check_sub[$i]['sub_cat'],$check_sub[$i]['id']);
            $data1['prod_name'] = $product_name;
            $data1['url_title'] = $url;
            $upatebasicsub = updateQuery(PRODINFO, $data1, "id =" . $check_sub[$i]['id']);
        }
    }
    if($upatebasic){ echo 1; } else{ echo 0; } 
}

if($action == "Delete_product"){
    $requestid = $_REQUEST['prod_id'];
    if($prod->deleteProd($requestid)){ echo 1; }
    else{ echo 0; }
    /* $getproduct_template =  selectQuery(PRODINFO,"sub_cat","id = ".$requestid."  ");
    $getsbcatdetails = selectQuery(PRODCAT,"template","id=".$getproduct_template[0]['sub_cat']);
    $template = $getsbcatdetails[0]['template'];
    $getinv = selectQuery(PRODINFO,"*","parent_id = ".$requestid);
    if(count($getinv) == 0 ){ $getinv = selectQuery(PRODINFO,"id","id = ".$prod_id." "); }
    for($i=0;$i<count($getinv);$i++){
    $deletetemp = deleteQuery ($template, "prod_id = ".$getinv[$i]['id']);
    $deletetimg = deleteQuery (PRODIMG, "prod_id = ".$getinv[$i]['id']);
    }
    $deleteinv = deleteQuery(PRODINFO,"parent_id=" . $requestid);
    $delemainprod = deleteQuery(PRODINFO, "id=" . $requestid);
    if($delemainprod){ echo 1; }
    else { echo 0; }*/
}

if($action == "Delete_inventory"){
    $requestid = $_REQUEST['prod_id'];
    /* $deleteinv = deleteQuery(PRODINFO, "id=" .$requestid);
    $deletetemp = deleteQuery ($template_name, "prod_id = ".$requestid);
    $deletetimg = deleteQuery (PRODIMG, "prod_id = ".$requestid);
    if($deleteinv){ echo 1; }
    else{ echo 0; }*/
    if($prod->deleteProd($requestid)){ echo 1; }
    else { echo 0; }
} 
if($action == "AddSEO"){
$prodid = $_REQUEST['prodid'];
$get_prod_details = selectQuery(PRODINFO,"prod_name,seo_title,seo_description,seo_keywords","id=".$prodid." "); ?>
<div class="modal-content" id="productseo">
    <div class="modal-header">
        <h4 class="modal-title" id="modal_title">Edit SEO Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body"> 
        <h6 class="mb-3"><?php echo $get_prod_details[0]['prod_name']; ?> </h6>
        <div class="seo_alert_msgs"></div>
        <div class="form-group">
            <div class="row">
                <label class="col-md-3 control-label lb1">Page Title</label>
                <div class="col-md-9">
                <input type="text" name="pagetitle" id="pagetitle" class="form-control" value="<?php  echo  $get_prod_details[0]['seo_title'];  ?>" maxlength="70" placeholder="Max. Character Limit 70"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-md-3 control-label lb1">Meta Description</label>
                <div class="col-md-9">
                <textarea name="metadesc" id="metadesc" class="form-control" maxlength="160" rows="5" placeholder="Max character limit 160"><?php  echo $get_prod_details[0]['seo_description']; ?></textarea> 
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <div class="row">
                <label class="col-md-3  control-label lb1">Meta Keywords</label>
                <div class="col-md-9 col-sm-8">
                    <textarea name="keywords" id="keywords" class="form-control" maxlength="255" rows="5" placeholder="Max Character Limit 255"><?php  echo $get_prod_details[0]['seo_keywords'];  ?> </textarea> 
                </div>
            </div>
        </div>       
    </div>
    <div class="modal-footer text-right py-2">
        <button type="button" class="btn btn-primary ml-auto" onclick="addseodetails('<?php echo $prodid; ?>')" id="edit_seo_btn">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
<? }
if($action == "add_seo"){
    $data=array("seo_title" => addslashes($pagetitle), "seo_description" => addslashes($metadesc), "seo_keywords" => addslashes($keywords),);
    $indata = updateQuery(PRODINFO,$data,"id='".$prodid."'");
    if($indata){ echo "1"; }
    else{ echo "0"; }
}
if($action=="upload image"){
    include("../../cropimg/create-thumbnail.php"); 
    include("../../cropimg/commonfunctions.php");
    $imgs_location=$_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $get_name = selectQuery(PRODINFO,"prod_name ","id = '".$prod_id."'");
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required']; $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height=$_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width=$_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width=$_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path=$_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path=$_POST['thumb5_path'];  $webp_quality = $_POST['webp_quality'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
        if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
          if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
        }
        try{
            $fn = replace_nonletter($get_name[0]['prod_name'])."-".rand(100,999);
           $fnameo =$fn.".".$ext;
            if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fnameo)){
                throw new Exception($_FILES["avatar"]["error"]);
            }else{
                if($img_extension=="webp"){
                    $fname = $fn.'.webp';
                    if(createwebp($target_path,$fnameo,$fname,$webp_quality)){ //if webp image created then delete old image
                        unlink($target_path.$fnameo);
                    }else{ $fname=  $fnameo;}
                }else{ $fname=  $fnameo; }
                if($crop_enabled==0){
                    list($width, $height, $type, $attr) = getimagesize($target_path.$fname);
                    if($default_image_width<$width){
                        $dest0 = $target_path.$fname;
                        if($width>$height)
                        createThumbnail($target_path.$fname, $dest0, $default_image_width);
                        else if($width<$height)
                        createThumbnail($target_path.$fname, $dest0, "", $default_image_height);
                        else
                        createThumbnail($target_path.$fname, $dest0, $default_image_width);
                    }
                }
                $data0 = array("img_name"=>$fname,"prod_id" => $prod_id );
                insertQuery(PRODIMG,$data0);
                if($thumbnail_required){
                    if($thumb1width){ $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
                    if($thumb2width){ $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
                    if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
                    if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
                    if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
                }
               echo "Upload Success";
            }
        }catch(exception $e){  echo 'Upload Failed :File did not upload: ' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']];   }
}
if($action == "Delete_image"){
    include("../../cropimg/commonfunctions.php");
    $del_img =  deleteQuery(PRODIMG,'id="'.$imgid.'"');
    if($del_img){
        $checkcount = selectQuery(PRODIMG,"count(id) as  allid ,prod_id ","img_name  = '".$_REQUEST['img_name']."'");
        if($checkcount[0]['allid'] == 0){ deleteimg('product',$_REQUEST['img_name'] ); }
        if($checkcount[0]['allid'] == 1){
            $data = array('apply_to_all' => 0);
            $update_table = updateQuery(PRODIMG, $data, "prod_id = '". $checkcount[0]['prod_id']."' ");
        }
        echo "1";
    }
    else{ echo "0"; }
}
if($action == "applyto_all"){
  $getall_inv = selectQuery(PRODINFO,"*","parent_id  = '".$parent_id."'   and   id <> '".$mainprod."'");
   for($i=0;$i<count($getall_inv);$i++){
       $data = array( 'prod_id' => $getall_inv[$i]['id'], 'img_name' => $img_name, 'apply_to_all' => 1,);
       insertQuery(PRODIMG,$data);
   }
   $array2 = array('apply_to_all' => 1,);
   $update_table = updateQuery(PRODIMG, $array2, "prod_id = '". $mainprod."' and img_name= '".$img_name."' ");
   if($update_table){ echo "1"; }else { echo "0"; }
} ?>
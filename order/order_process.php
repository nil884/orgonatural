<?php include("../includes/configuration.php");
include("../classes/order.php");
require_once('../classes/user.php');
require_once('../classes/product.php');
include_once "../classes/shiprocket.php";
ini_set("error_display",1);
$username = SHIPUSER; $pasword=SHIPPWD;
$ship = new shiprocket($username,$pasword);
$prod = new Product();  $user = new User(); $order = new Order($prod,$user);
$imgtype = "product";
include("../getimgpath.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=($pagetitle!=""?$pagetitle:SITE_TITLE); ?></title>
    <meta name="description" content="<?=($categorydetails[0]['metadescription']!=""?$categorydetails[0]['metadescription']:METADESCRIPTION); ?>">
    <meta name="keyword" content="<?=($categorydetails[0]['keywords']!=""?$categorydetails[0]['keywords']:METAKEYWORDS); ?>">
    <?php include "../commoncss.php"; ?>
</head>
<body class="bg-light">
<div class="main-wrap">
    <?php include "../menu.php";
    // $_POST['action']="cart";
    $isFreeShipping = $getconfigdetails[0]['free_shipping_on_order']; $freeShippingOn = $getconfigdetails[0]['free_shipping_on_order_cost'];
    $currentaction = $_POST['action'];
    if(!$_POST||$loguser==""){ echo "session error"; /*?><script>window.location="<?=SITEURL;?>/404.php"</script> <?*/ }
    $proddata = array(); $proddataForPin = array(); $proceedArr = array();
    if($currentaction=="Buy"){
        $product = $_POST['productid']; $quantity = $_POST['quantity']; $price = $_POST['price'];
        $productdata = $prod->getProductFullDetails($product);
        if($productdata['name']!=""){
            $sub = array("productid"=>$product,"quantities"=>$quantity,"perUnitPrice"=>$price,"name"=>$productdata['name'],"company"=>$productdata['company'],"vendorId"=>$productdata['vendorId'],"vendor"=>$productdata['vendor'],"image"=>$productdata['image'],"stock"=>$productdata['stock'],"variations"=>$productdata['variations'],"currentVariartions"=>$productdata['currentVariartions'],"tax"=>$productdata['tax'] ,"is_cod_avail" =>$productdata['is_cod_avail'] );
            $subFinal = array("productid"=>$product,"quantities"=>$quantity,"perUnitPrice"=>$price,"vendorId"=>$productdata['vendorId'],"vendor"=>$productdata['vendor'],"image"=>$productdata['image'],"stock"=>$productdata['stock'],"variations"=> addslashes($productdata['variations']),"currentVariartions"=> addslashes($productdata['currentVariartions']),"tax"=>$productdata['tax']);
            $sub1 = array("productid"=>$product,"quantities"=>$quantity,"perUnitPrice"=>$price,"vendorId"=>$productdata['vendorId'],"tax"=>$productdata['tax']);
            array_push($proddata,$sub); array_push($proddataForPin,$sub1); array_push($proceedArr,$subFinal);
        }
    } else{
        $getcart = selectQuery(CART,"prod_id,cart_id,quantity","user_id=".$loguser." and type='CART'");
        for($i=0;$i<count($getcart);$i++){
            $product = $getcart[$i]['prod_id']; $cart_id= $getcart[$i]['cart_id'];  $quantity= $getcart[$i]['quantity'];
            $productdata = $prod->getProductFullDetails($product);
            if($productdata['name']!=""){
                $sub = array("productid"=>$product,"quantities"=>$quantity,"perUnitPrice"=>$productdata['withoutGst'],"name"=>$productdata['name'],"company"=>$productdata['company'],"vendorId"=>$productdata['vendorId'],"vendor"=>$productdata['vendor'],"image"=>$productdata['image'],"stock"=>$productdata['stock'],"variations"=>$productdata['variations'],"currentVariartions"=>$productdata['currentVariartions'],"tax"=>$productdata['tax'],"is_cod_avail" =>$productdata['is_cod_avail'] );
                $subFinal = array("productid"=>$product,"quantities"=>$quantity,"perUnitPrice"=>$productdata['withoutGst'],"vendorId"=>$productdata['vendorId'],"vendor"=>$productdata['vendor'],"image"=>$productdata['image'],"stock"=>$productdata['stock'],"variations"=>addslashes($productdata['variations']),"currentVariartions"=>addslashes($productdata['currentVariartions']),"tax"=>$productdata['tax']);
                $sub1 = array("productid"=>$product,"quantities"=>$quantity,"perUnitPrice"=>$productdata['withoutGst'],"vendorId"=>$productdata['vendorId'],"tax"=>$productdata['tax']);
                array_push($proddata,$sub); array_push($proddataForPin,$sub1); array_push($proceedArr,$subFinal);
            }else{ $prod->removeFromCart($cart_id,$product); }
        }
    }    ?>
    <div class="bg-secondary cc-shadow-1">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ship-odr-process-tab">
                    <ul class="nav nav-tabs ship-add-tabs border-bottom-0" id="myTab" role="tablist">
                        <li class="nav-item"><a href="#deliveryaddress" class="nav-link active px-2 px-sm-4 cc-fw-5 border-top-0 border-left-0 border-right-0 cc-tw-5" id="address-tab" data-toggle="tab" role="tab" aria-controls="deliveryaddress" aria-selected="true">1. Delivery Address</a></li>
                        <li class="nav-item"><a href="#order" class="nav-link px-2 px-sm-4 cc-fw-5 border-top-0 border-left-0 border-right-0 disabled" id="order-tab" data-toggle="tab" role="tab" aria-controls="order" aria-selected="false">2. Order Summary</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="deliveryaddress" role="tabpanel" aria-labelledby="address-tab">
                        <input type="hidden" id="selected_shipping_id" value="<?=$_SESSION['shippingAdrId'];?>"/>
                        <input type="hidden" id="selected_pincode" value="<?=$_SESSION['userPincode'];?>"/>
                        <input type="hidden" id="isFreeShipping"/>
                        <div class="row">
                            <div class="col-md-12 col-lg-7 col-xl-6 mb-3 mb-lg-0 order-2 order-lg-1">
                                <div class="card card-body border-0 cc-shadow-1">
                                    <h2 class="mb-3 h5">Deliver To New Address</h2>
                                    <div class="alert alert-info">Please enter all possible details such as Flat / Plot Number, Society Name, Road Name / Number, Building Name, etc. accurately so that the order will reach to you accurately.</div>
                                    <form class="form">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field pt-0">Address Type</label>
                                            <div class="col-sm-9">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="adress_type custom-control-input" id="home" type="radio" name="adress_type" value="Home" checked>
                                                    <label class="custom-control-label" for="home">Home</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="adress_type custom-control-input" id="office" type="radio" name="adress_type" value="Office" >
                                                    <label class="custom-control-label" for="office">Office</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field">Full Name</label>
                                            <div class="col-sm-9"><input type="text" class="form-control fullname" id="fullname" onkeyup="fullnamechk('fullname')" maxlength="50"  autocomplete="off" placeholder="Enter Full Name"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field">Mobile No</label>
                                            <div class="col-sm-9"><input type="text" class="form-control mobile" id="mobile" onkeyup="mobnumbercheck('mobile')" maxlength="10" autocomplete="off" placeholder="Enter Mobile No"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field">Country</label>
                                            <div class="col-sm-9">
                                                    <select id="country" class="form-control">
                                                    <?php $get_country = selectQuery(COUNTRY,"name"," id <> '' order by name asc");
                                                    for($i=0;$i<count($get_country);$i++){ ?>
                                                    <option value="<?php echo $get_country[$i]['name'] ?>" <?php if($get_country[$i]['name'] == "INDIA"){ echo "selected"; } ?>><?php echo $get_country[$i]['name']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field">Pincode</label>
                                            <div class="col-sm-9"><input type="text" class="form-control pincode" onchange="getpincode()" maxlength="6" id="pincode" onkeyup="numbercheck('pincode')" autocomplete="off" placeholder="Enter Pincode"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field">Address</label>
                                            <div class="col-sm-9"><textarea class="form-control address" maxlength="200" autocomplete="off" placeholder="Enter Detailed Address"></textarea></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Landmark</label>
                                            <div class="col-sm-9"><input type="text" class="form-control location" maxlength="50" autocomplete="off" placeholder="Enter Landmark"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field">City</label>
                                            <div class="col-sm-9"><input type="text" class="form-control city" readonly autocomplete="off" placeholder="Enter City"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label cc-mandatary-field">State</label>
                                            <div class="col-sm-9"><input type="text" class="form-control state" readonly autocomplete="off" placeholder="Enter State"></div>
                                        </div>
                                        <div class="adrmsg"></div>
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label"></label>
                                            <div class="col-sm-9"><button type="button" class="btn btn-primary" onclick="addDelivery()">Deliver Here</button></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-5 col-xl-6 order-1 order-lg-2">
                                <? $addr = $user->getShippingAddress($loguser);
                                if(count($addr)){
                                for($i=0;$i<count($addr);$i++){?>
                                <div class="card user-del-address mb-3 border-0 cc-shadow-1">
                                    <div class="card-body">
                                        <div class="cc-primary-color"><?=$addr[$i]['address_type']; ?></div>
                                        <h6><?=$addr[$i]['address_name']; ?></h6>
                                        <div class="mb-1"><?=$addr[$i]['address']; ?>, <?=$addr[$i]['landmark']; ?>, <?=$addr[$i]['city']; ?>, <?=$addr[$i]['state']; ?>,<?=$addr[$i]['country']; ?> <?=$addr[$i]['pincode']; ?>.</div>
                                        <div class="mb-2">Contact No. <?=$addr[$i]['mobile_number']; ?></div>
                                        <button class="btn btn-primary mt-2" onclick="selectDelivery('<?=$addr[$i]['id']; ?>','<?=$addr[$i]['pincode']; ?>')">Deliver Here</button>
                                    </div>
                                </div>
                                <?} } else{ ?>
                                <div class="text-muted card card-body border-0 cc-shadow-1 h-100">
                                    <div class="text-center pt-3">
                                    <img src="<?php echo SITEURL; ?>/img/projectimage/address-not-found.svg" alt="address-not-found" width="120" class="m-auto">
                                    <p class="lead mt-3 text-muted">No address found for you</p></div></div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade order" id="order" role="tabpanel" aria-labelledby="order-tab">
                        <div class="row">
                            <div class="col-md-12 col-lg-8">
                                <div class="card p-3 cc-shadow-1 border-0 mb-3">
                                    <div class="shippingdetails"></div>
                                    <div><button type="button" class="btn btn-primary mt-2 px-3" onclick="changeadr()">Change Address</button></div>
                                </div>
                                <div class="order_msg"></div>
                                <? $blockbtn = 0; $codenable = 1;
                                if(count($proddata)){
                                    for($i=0;$i<count($proddata);$i++){
                                        $myprodid = $proddata[$i]['productid']; $stock = $proddata[$i]['stock'];
                                        $variations = $proddata[$i]['variations']; $currentvar = $proddata[$i]['currentVariartions'];
                                        if ($proddata[$i]['is_cod_avail'] == 0 ) { $codenable = 0;}
                                        $variationarr = array();  $varcnt = 0;
                                        foreach($variations as $key=>$val){ $variationarr[$key] = $currentvar[$varcnt];  $varcnt++; } ?>
                                        <div class="card border-0 mb-3 orderproccol prod prod<?=$i; ?>" id="prodbox<?=$myprodid; ?>" data-id="<?=$myprodid; ?>">
                                        <div class="card-body">
                                            <div class="codavail d-none"><?php echo $proddata[$i]['is_cod_avail']; ?> </div>
                                            <div class="row">
                                            <?   $total = $proddata[$i]['perUnitPrice']*$proddata[$i]['quantities'];
                                            $images = $proddata[$i]['image'];
                                            if(count($images)){ $img= SITEURL."/".$thumb3_path."/".$images[0]['img_name'];}
                                            else{ $img= SITEURL."/img/projectimage/product-default.png";  } ?>
                                            <div class="col-3 col-md-2 col-lg-2 col-xl-2"><div class="pro-list-thumb"><img src="<?=$img;?>" height="80" alt="order-item-thumb" class="img-fluid img-thumbnail"></div></div>
                                            <div class="col-9 col-md-5 col-lg-5 col-xl-6">
                                                <h2 class="mb-1 h6"><?=stripslashes($proddata[$i]['name']); ?></h2>
                                                <!--<div class="mb-1 cc-font-size-13"><span class="text-muted">Sold By</span> : <?=$proddata[$i]['vendor']; ?></div>-->
                                                <div class="order-last-price"><i class="fa fa-inr"></i> <?php echo $proddata[$i]['perUnitPrice']; ?>
                                                <input type="hidden" class="border-0 price price<?=$myprodid; ?>" id="price<?=$myprodid; ?>" value="<?=$total; ?>" readonly onchange="checkdeliveryByProd('<?=$myprodid; ?>','<?=$proddata[$i]['perUnitPrice']; ?>','quantity<?=$myprodid; ?>','<?=$proddata[$i]['vendorId']; ?>','<?=$proddata[$i]['tax']; ?>')"/></div>
                                                <div class="mb-2 cc-font-size-13">
                                                <? if(count($variationarr)){ ?>
                                                <? foreach($variationarr as $key=>$val){
                                                ?><span class="mr-2"><span class="text-muted"><?=$key;?></span> : <?=$val;?></span><? } ?>
                                                <? } ?></div>
                                                <div class="qtybox mr-2 float-left">
                                                    <?if($stock!=0){ ?>
                                                    <div class="setQty"><input type="number" name="quantity" class ="quantity" data-perprice="<?=$proddata[$i]['perUnitPrice']; ?>" data-pricebox="price<?=$myprodid; ?>" id="quantity<?=$myprodid; ?>" min="1" max="<?=($stock<5?$stock:5); ?>" data-val="<?=$proddata[$i]['quantities']; ?>" value="<?=$proddata[$i]['quantities']; ?>"></div>
                                                    <? } else{
                                                        $blockbtn = 1;
                                                        echo "Product is Out Of Stock";
                                                    }?>
                                                    <!--<span class="order-last-price quantity" id="quantity<?=$myprodid; ?>"><?=$proddata[$i]['quantities']; ?> </span>-->
                                                </div>
                                                <button type="button" class="btn btn-dark btn-sm rem-qty-btn" onclick="removeprod('<?=$currentaction; ?>','<?=$myprodid; ?>')">Remove</button>
                                                <div class="failmsg mt-2 failmsg<?=$myprodid; ?> text-danger"></div>
                                            </div>
                                            <div class="offset-3 mt-2 offset-sm-3 mt-md-0 offset-md-0 col-md-5 col-lg-5 col-xl-4 pl-3 pl-md-0">
                                                <table>
                                                    <tr><td class="pb-1">Deliver By</td><td class="pb-1"><span class="delivery" id="delivery<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none"><td class="pb-1">Courier By</td><td class="pb-1"><span class="courierid" id="courierid<?=$myprodid; ?>"></span><span class="courier" id="courier<?=$myprodid; ?>"></span></td></tr>
                                                    <tr><td class="pb-1">Taxable </td><td class="pb-1"><i class="fa fa-inr"></i> <span class="prodTaxableBasic" id="prodTaxableBasic<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none prodDiscountBox0<?=$myprodid; ?>"><td class="pb-1">Promo Code</td><td class="pb-1"><span class="prodDiscountCode" id="prodDiscountCode<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none prodDiscountBox1<?=$myprodid; ?>"><td class="pb-1">DiscountType</td><td class="pb-1"><span class="prodDiscountType" id="prodDiscountType<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none prodDiscountBox4<?=$myprodid; ?>"><td class="pb-1">Discount Min</td><td class="pb-1"><span class="discountMinOrderVal" id="discountMinOrderVal<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none prodDiscountBox2<?=$myprodid; ?>"><td class="pb-1">DiscountValue</td><td class="pb-1"><span class="prodDiscountValue" id="prodDiscountValue<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none prodDiscountBox3<?=$myprodid; ?>"><td class="pb-1">Discount</td><td class="pb-1"><i class="fa fa-inr"></i> <span>-</span><span class="prodDiscount" id="prodDiscount<?=$myprodid; ?>">0</span></td></tr>
                                                    <tr class="d-none"><td class="pb-1">Weight</td><td class="pb-1"><span>-</span><span class="weight" id="weight<?=$myprodid; ?>">0</span></td></tr>
                                                    <tr class="d-none"><td class="pb-1">Taxable</td><td class="pb-1"><i class="fa fa-inr"></i> <span class="prodTaxable" id="prodTaxable<?=$myprodid; ?>"></span></td></tr>
                                                    <tr id="cgstbox<?=$myprodid; ?>"><td class="pb-1">CGST(<span class="cgst1" id="cgst1<?=$myprodid; ?>"></span>%) </td><td class="pb-1"><i class="fa fa-inr"></i> <span class="cgst2" id="cgst2<?=$myprodid; ?>"></span></td></tr>
                                                    <tr id="sgstbox<?=$myprodid; ?>"><td class="pb-1">SGST(<span class="sgst1" id="sgst1<?=$myprodid; ?>"></span>%) </td><td class="pb-1"><i class="fa fa-inr"></i> <span class="sgst2" id="sgst2<?=$myprodid; ?>"></span></td></tr>
                                                    <tr id="igstbox<?=$myprodid; ?>"><td class="pb-1">IGST(<span class="igst1" id="igst1<?=$myprodid; ?>"></span>%)</td><td class="pb-1"><i class="fa fa-inr"></i> <span class="igst2" id="igst2<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none codChargesBox<?=$myprodid; ?>"><td class="pb-1">COD Charges</td><td class="pb-1"><i class="fa fa-inr"></i> <span class="codCharges" id="codCharges<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="d-none"><td class="pb-1 pr-3">Shipping Charges</td><td class="pb-1"><i class="fa fa-inr"></i> <span class="shipping" id="shipping<?=$myprodid; ?>"></span> <span class="shippingDisc" id="shippingDisc<?=$myprodid; ?>"></span></td></tr>
                                                    <tr class="h6"><td class="cc-fw-5">Total Payable</td><td><i class="fa fa-inr"></i> <span class="total" id="total<?=$myprodid; ?>"></span></td></tr>
                                                </table>
                                            </div>
                                            </div>
                                             </div>
                                            <div class="card-footer py-2">
                                                <div class="promobox promobox<?=$myprodid; ?>">
                                                <button class="btn btn-link p-0" onclick="showpromobox('aplPromobox<?=$myprodid; ?>')">Apply Promocode?</button>
                                                <div class="form-inline d-none aplPromobox aplPromobox<?=$myprodid; ?>">
                                                <input type="text" id="promovalue<?=$myprodid; ?>" class="form-control promovalue mr-2 mt-2">
                                                <button type="button" class="btn btn-primary px-3 mt-2 mr-2" onclick="applypromo('<?=$myprodid; ?>','promovalue<?=$myprodid; ?>')">Apply</button>
                                                <button type="button" class="btn btn-secondary px-3 mt-2 mr-2" onclick="showpromocode('<?=$myprodid; ?>')">See Promocodes</button>
                                                </div>
                                                </div>

                                            <div class="promomsg promomsg<?=$myprodid; ?>"></div>
                                                <div class="prodmsg mt-2 prodmsg<?=$myprodid; ?>"></div>
                                                </div>
                                        </div>
                                        <? } } else{ ?><div class="row">No items selected</div><? } ?>
                                        <!--<div class="row">
                                        <div class="col-md-4"><select id="promotype" class="form-control"><option value=""> Select Promo Type </option><option value="Promocode">Promocode</option><option value="Gift Card">Gift Card</option></select></div>
                                        <div class="col-md-4"><input type="text" id="promovalue" class="form-control"></div>
                                        <div class="col-md-4"><button type="button" class="btn btn-primary" onclick="applypromo()">Apply</button> </div>
                                        </div>-->
                            </div>
                            <div class="col-md-12 col-lg-4 mt-3 mt-md-0">
                                <div class="pay-del-aside">
                                    <div class="card border-0 cc-shadow-1 mb-3">
                                        <? if(count($proddata)){ ?>
                                        <div class="card-header cc-primary-back"><h6 class="mb-0 text-white">Price Details</h6></div>
                                        <div class="col-12 py-3">
                                            <table class="table mb-0">
                                                <tr><td class="pl-0 pt-0 border-top-0">Total Taxable</td><td class="pt-0 border-top-0 text-right"><i class="fa fa-inr"></i> <span class="taxable"></span></td></tr>
                                                <tr><td class="pl-0 pt-0 border-top-0">Total GST</td><td class="pt-0 border-top-0 text-right"><i class="fa fa-inr"></i> <span class="gst"></span></td></tr>
                                                <tr><td class="pl-0 pt-0 border-top-0">Total Shipping</td><td class="text-right border-top-0 pt-0"><i class="fa fa-inr"></i> <span class="totShip"></span>  <span class="totShipDisc"></span></td></tr>
                                                <tr class="totalCOD d-none"><td class="pl-0 pt-0 border-top-0">Total COD Charges</td><td class="pt-0 border-top-0 text-right"><i class="fa fa-inr"></i> <span class="totalcod"></span></td></tr>
                                                <tr class="isGift d-none"><td class="pl-0">Gift Card</td><td class="text-right"><span class="giftcard"></span><span class="giftAmt"></span></td>
                                                </tr>
                                                <tr class="cart-view-total-price h5"><td class="pl-0 pb-0 cc-fw-5">Total Payable</td><td class="pb-0 cc-fw-5 text-right"><i class="fa fa-inr"></i> <span class="totPay"></span><span class="totPayDisc"></span></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card border-0 cc-shadow-1 payemt-method">
                                        <div class="card-header cc-primary-back"><h6 class="mb-0 text-white">Select Payment Method</h6></div>
                                        <div class="card-body <?=($blockbtn==1?"d-none":"");?>">
                                            <? if(PAYU_ENABLE==1){ ?> <div class="pb-2 border-bottom d-flex align-items-center cc-cursor-pointer" onclick="proceed('PayU Money')"><img src="<?=SITEURL;?>/img/projectimage/payU.png" alt="payU" width="25" class="mr-2"/>Pay By PayU</div> <? }
                                            if(EASE_ENABLE==1){ ?> <div class="py-2 border-bottom d-flex align-items-center cc-cursor-pointer" onclick="proceed('Easebuzz')"><img src="<?=SITEURL;?>/img/projectimage/easebuzz.png" alt="easebuzz" width="25" class="mr-2"/>Pay By Easebuzz</div><? }
                                             if(INSTA_ENABLE==1){ ?> <div class="py-2 border-bottom d-flex align-items-center cc-cursor-pointer" onclick="proceed('Instamojo')"><img src="<?=SITEURL;?>/img/projectimage/instamojo.png" alt="instamojo" width="25" class="mr-2"/>Pay By Instamojo</div><? }
                                             if(RAZOR_ENABLE==1){ ?> <div class="py-2 border-bottom d-flex align-items-center cc-cursor-pointer" onclick="proceed('Razorpay')"><img src="<?=SITEURL;?>/img/projectimage/razorpay.jpg" alt="razorpay" width="25" class="mr-2"/>Pay By Razorpay</div><? } ?>
                                            <div class=" cod_button pt-2 align-items-center cc-cursor-pointer <?php if($codenable == 0) { echo "d-none"; } ?>" onclick="proceed('COD')"><img src="<?=SITEURL;?>/img/projectimage/cash-on-delivery.png" alt="cash-on-delivery" width="25" class="mr-2"/>Cash On Delivery</div>
                                        <!--   <? if(PAYU_ENABLE==1){ ?> <div class="pay-mod-col mb-1"><input type="radio" class="chk-pay-mode" id="paymode1" name="paymode" value="PayU Money" onchange="paymodechng()"><label class="chk-pay-label rounded w-100 text-left" for="paymode1"><img src="<?=SITEURL;?>/img/projectimage/payU.png" alt="payU" width="25" class="mr-2"/>Pay By PayU</label></div> <? }
                                              if(EASE_ENABLE==1){ ?>  <div class="pay-mod-col mb-1"><input type="radio" class="chk-pay-mode" id="paymode2" name="paymode" value="Easebuzz" onchange="paymodechng()"><label class="chk-pay-label rounded w-100 text-left" for="paymode2"><img src="<?=SITEURL;?>/img/projectimage/easebuzz.png" alt="easebuzz" width="25" class="mr-2 "/>Pay By Easebuzz</label></div><? }
                                              if(INSTA_ENABLE==1){ ?> <div class="pay-mod-col mb-1"><input type="radio" class="chk-pay-mode" id="paymode3" name="paymode" value="Instamojo" onchange="paymodechng()"><label class="chk-pay-label rounded w-100 text-left" for="paymode3"><img src="<?=SITEURL;?>/img/projectimage/instamojo.png" alt="instamojo" width="25" class="mr-2 "/>Pay By Instamojo</label></div><? }
                                              if(RAZOR_ENABLE==1){ ?> <div class="pay-mod-col mb-1"><input type="radio" class="chk-pay-mode" id="paymode4" name="paymode" value="Razorpay" onchange="paymodechng()"><label class="chk-pay-label rounded w-100 text-left" for="paymode4"><img src="<?=SITEURL;?>/img/projectimage/razorpay.jpg" alt="razorpay" width="25" class="mr-2 "/>Pay By Razorpay</label></div><? }?>
                                                <div class="cod_button pay-mod-col mb-1 <?php echo ($codenable==0?"d-none":""); ?>"><input type="radio" class="chk-pay-mode" id="paymode5" name="paymode" value="COD" onchange="paymodechng()"><label class="chk-pay-label rounded w-100 text-left" for="paymode5"><img src="<?=SITEURL;?>/img/projectimage/cash-on-delivery.png" alt="cash-on-delivery" width="25" class="mr-2 "/>Cash On Delivery</label></div>

                                                <button type="button" class="btn btn-primary mb-2" onclick="paynow('chk-pay-mode')">Proceed My Order</button>
                                                <div class="paybtn"></div> -->
                                        </div>
                                        <div class="footer-text <?=($blockbtn==1?"":"d-none");?>">There is out of stock product in your items. Remove that product to process further</div>
                                    </div>
                                    <? } ?>
                                    <!-- <div class="card-body">
                                        <h6 class="cc-fw-5">I have promocode/giftcard</h6>
                                        <div class="row">
                                            <div class="col-md-12 form-group"><select id="promotype" class="form-control"><option value="">Select Promo Type </option><option value="Promocode">Promocode</option><option value="Gift Card">Gift Card</option></select></div>
                                            <div class="col-md-12 form-group"><input type="text" id="promovalue" class="form-control"></div>
                                            <div class="couponmsg"></div>
                                            <div class="col-md-12"><button type="button" class="btn btn-primary btn-sm px-3" onclick="applypromo()">Apply</button>
                                            <button type="button" class="btn btn-secondary btn-sm px-3" onclick="showpromocode()">See Promocodes</button> </div>
                                        </div>
                                    </div>-->
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="couponModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title cc-fw-5" id="exampleModalLongTitle">Available Promocodes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-0"><div class="couponbody"></div></div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<div id="overlay"><div class="loader"></div></div>
<script src="<?=SITEURL;?>/js/validation.js"></script>
<script src="<?=SITEURL ?>/js/canvasjs.min.js"></script>
<script>
function paynow(payclass){
   paymode=$("."+payclass+":checked").val();
   if(paymode){proceed(paymode) }else{$(".paybtn").fadeIn().removeClass("text-success").addClass("text-danger").html("Select Any Payment Method To proceed").fadeOut(3000);}
}

function paymodechng(){
    paymode=$(".chk-pay-mode:checked").val();

    $(".price").each(function(){
        getid=$(this).attr("id");  $("#"+getid).change();
    })
}

$(".promovalue").val("");
function showpromobox(boxclass){
    $(".aplPromobox").addClass("d-none");  $("."+boxclass).removeClass("d-none");
}
function showpromocode(prodid){
   var allprods= prodid;
    $.ajax({
        type : "POST", url: siteurl+"/ajax/order_ajax.php",  data : {prodid:allprods,action:"getPromoCodes"},
        success: function(resr){
            $(".couponbody").html(resr); $("#couponModal").modal("show");
        }
    });
}
function applypromo(prodid,inputid){
    //promotype = $("#promotype option:selected").val();
    promovalue = $("#"+inputid).val();
    totalTaxable = $(".taxable").html();
    $.ajax({
        type : "POST", url: siteurl+"/ajax/order_ajax.php",
        data : { promovalue:promovalue,prodid:prodid,action:"applyPromocode" },
        success: function(resr){
            jsondata = JSON.parse(resr);
            if(jsondata['status']=="success"){
                var discountType=jsondata['discountType']; //Percentage/Price
                var discountValue=jsondata['discountValue'];
                var minOrderValueRequire=jsondata['minOrderValueRequire'];
                var minOrderValue= jsondata['minOrderValue'];
                var applicatbleOn= jsondata['applicatbleOn'];
                if((minOrderValueRequire==1&&parseFloat(totalTaxable)>=parseFloat(minOrderValue)) || minOrderValueRequire==0){
                    prodTaxable = $("#prodTaxableBasic"+prodid).html(); qty = $("#quantity"+prodid).val();
                    /*if(discountType=="Price"){ discVal = parseFloat(discountValue); discountedVal = parseFloat(prodTaxable)-parseFloat(discVal); }
                    else{discVal = Math.round((parseFloat(prodTaxable)/100)*parseFloat(discountValue)); discountedVal = parseFloat(prodTaxable)-parseFloat(discVal); }*/
                    if(discountType=="Price"){ discVal = parseFloat(discountValue)*qty;  discountedVal = parseFloat(prodTaxable)-parseFloat(discVal); }
                    else{
                        discVal = Math.round((parseFloat(prodTaxable)/100)*parseFloat(discountValue)); discountedVal =     parseFloat(prodTaxable)-parseFloat(discVal);
                    }
                    if(parseFloat(prodTaxable)>parseFloat(discVal)){
                        //$(".promobox").addClass("d-none");
                        $(".promobox"+prodid).addClass("d-none");
                        $(".promomsg"+prodid).fadeIn().removeClass("text-danger").addClass("text-success mb-0").html("Promocode "+promovalue+" Applied Successfully <button type='button' class='btn btn-sm btn-link text-danger' onclick='removePromocode("+prodid+")'>Remove <i class='fa fa-times'></i></button>");
                        $("#prodTaxable"+prodid).html(discountedVal);
                        $(".prodDiscountBox0"+prodid).removeClass("d-none"); $("#prodDiscountCode"+prodid).html(promovalue);
                        $("#prodDiscountType"+prodid).html(discountType);
                        $("#discountMinOrderVal"+prodid).html(minOrderValue);
                        $("#prodDiscountValue"+prodid).html(discountValue);
                        $(".prodDiscountBox3"+prodid).removeClass("d-none"); $("#prodDiscount"+prodid).html(discVal);
                        $("#price"+prodid).change();
                    } else{
                        $(".promomsg"+prodid).fadeIn().removeClass("text-success").addClass("text-danger").html("Selected Promocode Not Applicable For This Product").fadeOut(3000);
                    }
                }else{
                    $(".promomsg"+prodid).fadeIn().removeClass("text-success").addClass("text-danger").html("Minimum Order Value "+minOrderValue+" Required To Apply This Coupon").fadeOut(3000);
                }
            }else{
                $(".promomsg"+prodid).fadeIn().removeClass("text-success").addClass("text-danger").html(jsondata['message']).fadeOut(3000);
            }
        }
    });
}
function validateCouponCode(){
    taxable = 0;
    $(".prodTaxableBasic").each(function(){
        taxa = $(this).html();
        taxable = parseFloat(taxable)+parseFloat(taxa)
    })
    $(".prod").each(function(){
        prodid = $(this).attr("data-id");
        if($("#prodDiscountCode"+prodid).html().trim()!=""){
            promocode = $("#prodDiscountCode"+prodid).html();
            minhtml = $("#discountMinOrderVal"+prodid).html();
            minorderVal = (minhtml.trim()!=""&&minhtml.trim()!=0?minhtml:0);
            if(parseFloat(minorderVal)>parseFloat(taxable)){
                removePromocode(prodid);
                $(".promomsg"+prodid).removeClass("text-success").addClass("text-danger").html("Minimum Taxable Value "+minorderVal+" Required To Apply Promocode "+promocode).delay(7000).fadeOut();
            }
        }
    })
}
function removePromocode(prodid){
    //$(".promobox").removeClass("d-none");
    $(".promobox"+prodid).removeClass("d-none");
    $(".promomsg"+prodid).removeClass("alert alert-danger alert-success").html("");
    prodTaxable = $("#prodTaxableBasic"+prodid).html();
    $("#prodTaxable"+prodid).html(prodTaxable);
    $(".prodDiscountBox0"+prodid+",.aplPromobox,.prodDiscountBox3"+prodid).addClass("d-none"); $("#prodDiscountCode"+prodid).html("");
    $("#prodDiscountType"+prodid).html(""); $("#discountMinOrderVal"+prodid).html("");   $(".promovalue").val("");
    $("#prodDiscountValue"+prodid).html(0);
    $("#prodDiscount"+prodid).html(0);
    $("#price"+prodid).change();
}
var selectedAdr = $("#selected_shipping_id").val();
if(selectedAdr!=""){ checkproductsDelivery(); }
var siteurl="<?=SITEURL;?>";
function getpincode(){
    pincode = $(".pincode").val();
    if(pincode!=""){
        $.ajax({
            type : "POST", url: siteurl+"/ajax/order_ajax.php",
            data : {pincode:pincode,action:"pincodedetails"},
            success: function(resr){
                resdata=JSON.parse(resr);
                if(resdata['status']=="success"){ $(".city").val(resdata['city']);$(".state").val(resdata['state']);
                }else{
                    $(".adrmsg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html(resdata['message']).fadeOut(3000);
                    $(".city").val("");$(".state").val("");
                }
            }
        });
    }
}
function addDelivery(){
    adress_type = $(".adress_type:checked").val(); country = $("#country").val(); fullname = $(".fullname").val(); mobile = $(".mobile").val(); pincode = $(".pincode").val(); address = $(".address").val();
    addlocation = $(".location").val(); city = $(".city").val(); state = $(".state").val();
    if(fullname.trim()!=""&&mobile.trim()!=""&&mobile.length==10&&pincode.trim()!=""&&address.trim()!=""&&city.trim()!=""&&state.trim()!=""){
        $.ajax({
        type : "POST", url: siteurl+"/ajax/order_ajax.php",
        data : {adress_type:adress_type ,country:country,fullname:fullname,mobile:mobile,pincode:pincode,address:address,addlocation:addlocation,city:city,state:state,user:"<?=$loguser;?>",action:"addAddressAndSet"},
        success: function(resr) {
        if(resr==0){
        $(".adrmsg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html("Invalid Data.. Please enter valid details").fadeOut(3000);
        }else{ $("#selected_shipping_id").val(resr); $("#selected_pincode").val(pincode); checkproductsDelivery(); }
        }
        });
    } else{
        $(".adrmsg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html("Please Enter All Required Details").fadeOut(3000);
    }
}
function selectDelivery(id,mypin){
    $.ajax({ type: "POST",url: siteurl+"/ajax/order_ajax.php",
        data: {adrid:id,pincode:mypin,action:"setUserPincode"},
        success: function(resr){  $("#selected_shipping_id").val(id); $("#selected_pincode").val(mypin); checkproductsDelivery(); }
    });
}
function changeadr(){
    $.ajax({ type: "POST",url: siteurl+"/ajax/order_ajax.php",
        data: {action:"removeUserPincode"},
        success: function(resr){
            $("#selected_shipping_id").val(""); $("#selected_pincode").val("");
            $("#deliveryaddress").addClass("show active");
            $("#order").removeClass("show active");
            $("#order-tab").removeClass("active").addClass("disabled");
            $("#address-tab").addClass("active").removeClass("disabled");
        }
    });
}
function checkproductsDelivery(){
    var allprods = '<?php echo json_encode($proddataForPin); ?>';
    var jsonarr = JSON.parse(allprods); var resdone=0;
    checkdelivery(allprods);
}
function checkdelivery(jsondata){
    pincode = $("#selected_pincode").val(); shipping = $("#selected_shipping_id").val();
    var jsonarr = JSON.parse(jsondata);
    if(pincode!=""&&jsonarr.length!=0){
        showloader();
        $.ajax({
            type: "POST", url: siteurl+"/ajax/shipping_ajax.php",
            data: {pincode:pincode,shipping:shipping,action:"getshipingdetails"},
            success: function(resr){
                resdata = JSON.parse(resr);
                $(".shippingdetails").html('<div class="cc-primary-color">'+resdata['adress_type']+'</div><div class="cc-fw-6 mb-2">'+resdata['name']+'</div><div class="mb-1">'+resdata['address']+' , '+resdata['landmark']+' , '+resdata['city']+' , '+resdata['state']+' , '+resdata['country']+' '+resdata['pincode']+'</div><div class="mb-2"><span class="cc-fw-6">Contact No.</span> '+resdata['mobile']+'</div> ');
            }
        });
        $.ajax({
            type: "POST", url: siteurl+"/ajax/shipping_ajax.php",
            data: {jsondata:jsondata,pincode:pincode,shipping:shipping,action:"getTaxAndShippingBulk"},
            success: function(resr){
                resdata = JSON.parse(resr);
                isFreeShip = "OFF"; freeShippingOn = 0;
                for(i=0;i<resdata.length;i++){
                    status=resdata[i]['status']; product=resdata[i]['product'];
                    if(resdata[i]['isFreeShip']=="ON"||resdata[i]['isFreeShip']==1){isFreeShip="ON";freeShippingOn=resdata[i]['freeShippingOn']; }
                    if(status=="success"){ $(".failmsg"+product).html("").addClass("d-none");
                        $("#delivery"+product).html(resdata[i]['deliveryDate']);
                        $("#courier"+product).html(resdata[i]['courierName']);
                        $("#prodTaxable"+product).html(resdata[i]['taxable']);
                        $("#prodTaxableBasic"+product).html(resdata[i]['taxable']);
                        $("#weight"+product).html(resdata[i]['weight']);
                        $("#prodDiscount"+product).html(0);
                        $("#courierid"+product).html(resdata[i]['courierId']);
                       // $("#shipping"+product).html(resdata[i]['shippingCharges']);  by lalit
                       $("#shipping"+product).html(0);
                        $("#total"+product).html(resdata[i]['finalPayable']);
                        if(resdata[i]['cgst1']!=0){$("#cgstbox"+product).removeClass("d-none");  }else{$("#cgstbox"+product).addClass("d-none"); }  $("#cgst1"+product).html(resdata[i]['cgst1']);  $("#cgst2"+product).html(resdata[i]['cgst2']);
                        if(resdata[i]['sgst1']!=0){$("#sgstbox"+product).removeClass("d-none");  }else{$("#sgstbox"+product).addClass("d-none"); }  $("#sgst1"+product).html(resdata[i]['sgst1']); $("#sgst2"+product).html(resdata[i]['sgst2']);
                        if(resdata[i]['igst1']!=0){$("#igstbox"+product).removeClass("d-none");  }else{$("#igstbox"+product).addClass("d-none"); } $("#igst1"+product).html(resdata[i]['igst1']); $("#igst2"+product).html(resdata[i]['igst2']);
                        /* calculateTotal(isFreeShip,freeShippingOn); */
                    }else{
                        $("#delivery"+product).html("Not Available");  $("#courier"+product).html("-"); $("#prodTaxable"+product).html(resdata[i]['taxable']);  $("#prodDiscount"+product).html(0);     $("#prodTaxableBasic"+product).html(resdata[i]['taxable']);
                        $("#courierid"+product).html("-");
                        $("#shipping"+product).html(0);
                        $("#weight"+product).html(resdata[i]['weight']);
                        $("#total"+product).html(resdata[i]['finalPayable']);
                        if(resdata[i]['cgst1']!=0){$("#cgstbox"+product).removeClass("d-none");  }else{$("#cgstbox"+product).addClass("d-none"); }  $("#cgst1"+product).html(resdata[i]['cgst1']); $("#cgst2"+product).html(resdata[i]['cgst2']);
                        if(resdata[i]['sgst1']!=0){$("#sgstbox"+product).removeClass("d-none");  }else{$("#sgstbox"+product).addClass("d-none"); }  $("#sgst1"+product).html(resdata[i]['sgst1']); $("#sgst2"+product).html(resdata[i]['sgst2']);
                        if(resdata[i]['igst1']!=0){$("#igstbox"+product).removeClass("d-none");  }else{$("#igstbox"+product).addClass("d-none"); } $("#igst1"+product).html(resdata[i]['igst1']); $("#igst2"+product).html(resdata[i]['igst2']);
                        /*calculateTotal();*/ $(".failmsg"+product).html("Delivery Not Available On Selected Address").removeClass("d-none");
                    }
                }calculateTotal(isFreeShip,freeShippingOn);
                isActivatePayment(); hideloader();
                $("#deliveryaddress").removeClass("show active");
                $("#order").addClass("show active");
                $("#address-tab").removeClass("active").addClass("disabled");
                $("#order-tab").addClass("active").removeClass("disabled");
            }
        });
    }else{
        hideloader();
        $("#deliveryaddress").removeClass("show active");
        $("#order").addClass("show active");
        $("#address-tab").removeClass("active").addClass("disabled");
        $("#order-tab").addClass("active").removeClass("disabled");
    }
}
function checkdeliveryByProd(productid,perUnitPrice,quantities,vendorId,tax){
    quantity = $("#"+quantities).val();
    total = parseFloat(perUnitPrice)*parseFloat(quantity);
    pincode = $("#selected_pincode").val();
    var discount = $("#prodDiscount"+productid).html();
    var discountType = $("#prodDiscountType"+productid).html();
    var discountValue = $("#prodDiscountValue"+productid).html();
     paymode=$(".chk-pay-mode:checked").val();
     if(typeof(paymode)!="undefined"){paymentmode= paymode;  }else{paymentmode= "";}
    if(pincode!=""){
        showloader();
        $.ajax({
            type: "POST", url: siteurl+"/ajax/shipping_ajax.php",
            data: {prodid:productid,perUnitPrice:perUnitPrice,quantities:quantity,price:total,vendorId:vendorId,tax:tax,pincode:pincode,discount:discount,discountType:discountType,discountValue:discountValue,paymentmode:paymentmode,action:"getTaxAndShipping"},
            success: function(resr) {
                resdata = JSON.parse(resr);
                isFreeShip = resdata['isFreeShip']; freeShippingOn = resdata['freeShippingOn'];
                status = resdata['status'];  product = resdata['product'];
                if(status=="success"){
                    $(".prod").each(function(){
                        id = $(this).attr("data-id");
                        shippingchargs = $("#shipping"+id).html();
                        shipori = $("#shippingDisc"+id+" del").html();
                        if(shipori!=""){
                            $("#shipping"+id).html(shipori);
                            $("#shippingDisc"+id).html("");
                        }
                    })
                    $(".failmsg"+product).html("").addClass("d-none");
                    $("#delivery"+product).html(resdata['deliveryDate']);
                    $("#prodTaxable"+product).html(resdata['taxable']);
                    $("#prodDiscount"+product).html(resdata['discount']);
                    $("#prodTaxableBasic"+product).html(resdata['basic_taxable']);
                    $("#courier"+product).html(resdata['courierName']);
                    $("#courierid"+product).html(resdata['courierId']);
                   // $("#shipping"+product).html(resdata['shippingCharges']);  by lalit
                   $("#shipping"+product).html(0);
                   $("#weight"+product).html(resdata['weight']);
                     $("#total"+product).html(resdata['finalPayable']);
                    if(resdata['show_cod']==1){$(".codChargesBox"+product).removeClass("d-none"); $("#codCharges"+product).html(resdata['cod_charges']);  }
                    else{$(".codChargesBox"+product).addClass("d-none"); $("#codCharges"+product).html(0);}

                    if(resdata['cgst1']!=0){ $("#cgstbox"+product).removeClass("d-none"); } else{$("#cgstbox"+product).addClass("d-none"); } $("#cgst1"+product).html(resdata['cgst1']);  $("#cgst2"+product).html(resdata['cgst2']);
                    if(resdata['sgst1']!=0){$("#sgstbox"+product).removeClass("d-none");  }else{$("#sgstbox"+product).addClass("d-none"); } $("#sgst1"+product).html(resdata['sgst1']); $("#sgst2"+product).html(resdata['sgst2']);
                    if(resdata['igst1']!=0){$("#igstbox"+product).removeClass("d-none");  }else{$("#igstbox"+product).addClass("d-none"); } $("#igst1"+product).html(resdata['igst1']); $("#igst2"+product).html(resdata['igst2']);
                    calculateTotal(isFreeShip,freeShippingOn);
                }else{  $("#delivery"+product).html("Not Available"); $("#courier"+product).html("-"); $("#prodTaxable"+product).html(resdata['taxable']);
                    $("#prodTaxableBasic"+product).html(resdata['basic_taxable']);
                    $("#prodDiscount"+product).html(resdata['discount']);
                    $("#weight"+product).html(resdata['weight']);
                    $("#courierid"+product).html("-"); $("#shipping"+product).html(0);
                    $("#total"+product).html(resdata['finalPayable']);  alert(resdata['finalPayable']);
                     if(resdata['show_cod']==1){$("#codChargesBox"+product).removeClass("d-none"); $("#codCharges"+product).html(resdata['cod_charges']);  }
                    else{$("#codChargesBox"+product).addClass("d-none"); $("#codCharges"+product).html(0);}
                    if(resdata['cgst1']!=0){$("#cgstbox"+product).removeClass("d-none");  }else{$("#cgstbox"+product).addClass("d-none"); } $("#cgst1"+product).html(resdata['cgst1']);  $("#cgst2"+product).html(resdata['cgst2']);
                    if(resdata['sgst1']!=0){$("#sgstbox"+product).removeClass("d-none");  }else{$("#sgstbox"+product).addClass("d-none"); }  $("#sgst1"+product).html(resdata['sgst1']); $("#sgst2"+product).html(resdata['sgst2']);
                    if(resdata['igst1']!=0){$("#igstbox"+product).removeClass("d-none");  }else{$("#igstbox"+product).addClass("d-none"); } $("#igst1"+product).html(resdata['igst1']); $("#igst2"+product).html(resdata['igst2']);
                    calculateTotal(); $(".failmsg"+product).html("Delivery Not Available On Selected Address").removeClass("d-none");
                }
                isActivatePayment(); hideloader();
            }
        });
    }
}
function isActivatePayment(){
    stoppay = 0;
    $(".failmsg").each(function(){
        failtext = $(this).html();
        if(failtext.trim()!=""){ stoppay=1; }
    });
    if(stoppay==1){
        $(".payemt-method").addClass("d-none");
    } else{$(".payemt-method").removeClass("d-none");}
}

function checkcod(){
 codavail = 1;
    $(".codavail").each(function(){
        codavail = $(this).html();
        if(codavail.trim() == 0){ codavail =0; }
    });
    if(codavail == 0){
        $(".cod_button").addClass("d-none");
    } else{$(".cod_button").removeClass("d-none");}
}
function calculateTotal(isFreeShip="<?=$isFreeShipping; ?>",freeShippingOn="<?=$freeShippingOn;?>"){
    var base=0; var cgst = 0; var sgst = 0; var igst = 0; var shipping = 0; var isFree = 0;  var codcharges = 0; var weight = 0;var quantity = 0;
    pincode = $("#selected_pincode").val();
    $(".prodTaxable").each(function(){  currval=$(this).html(); if(currval!=""){ base=parseFloat(base)+parseFloat(currval); } })
    $(".cgst2").each(function(){ currval = $(this).html(); if(currval!=""){ cgst=parseFloat(cgst)+parseFloat(currval); } })
    $(".sgst2").each(function(){ currval = $(this).html(); if(currval!=""){ sgst=parseFloat(sgst)+parseFloat(currval); } })
    $(".igst2").each(function(){ currval = $(this).html(); if(currval!=""){ igst=parseFloat(igst)+parseFloat(currval); } })
    $(".weight").each(function(){ currval = $(this).html(); if(currval!=""){ weight=parseFloat(weight)+parseFloat(currval); } })
    $(".quantity").each(function(){ currval = $(this).html(); if(currval!=""){ quantity=parseFloat(quantity)+parseFloat(currval); } })
   /*  $(".shipping").each(function(){ currval = $(this).html();  if(currval!=""){ shipping = parseFloat(shipping)+parseFloat(currval); }});
    $(".shippingDisc del").each(function(){ currval = $(this).html();
    if(currval!=""){ shipping = parseFloat(shipping)+parseFloat(currval); }}) by lalit */ 
    var gst=parseFloat(cgst)+parseFloat(sgst)+parseFloat(igst);
   
    if((isFreeShip=="ON"||isFreeShip==1)&&parseFloat(base)>parseFloat(freeShippingOn)){ isFree=1; }
     $(".codCharges").each(function(){ currval = $(this).html(); if(currval!=""){ codcharges=parseFloat(codcharges)+parseFloat(currval); } })
    $(".totalcod").html(codcharges);
     if(codcharges!=0){
         $(".totalCOD").removeClass("d-none");
     }else{
        $(".totalCOD").addClass("d-none");
     }
    $(".prod").each(function(){
        id = $(this).attr("data-id"); fail = $(".failmsg"+id).html();
        if(fail.trim()==""){
            taxable = $("#prodTaxable"+id).html();  cgst2 = $("#cgst2"+id).html(); sgst2 = $("#sgst2"+id).html(); igst2 = $("#igst2"+id).html();
            codCharges=($("#codCharges"+id).html()==""?0:$("#codCharges"+id).html());
            withoutship = parseFloat(taxable)+parseFloat(cgst2)+parseFloat(sgst2)+parseFloat(igst2)+parseFloat(codCharges);
            shippingchargs = $("#shipping"+id).html();
            if(isFree==1){
            $("#isFreeShipping").val(isFree);
            if(shippingchargs!=0){ $("#shipping"+id).html(0.00);  $("#shippingDisc"+id).html("<del>"+shippingchargs+"</del>");}
            $("#total"+id).html(withoutship.toFixed(2));
            }else{
                $("#isFreeShipping").val(isFree);
                withshipping=parseFloat(withoutship)+parseFloat(shippingchargs)
                $("#total"+id).html(withshipping.toFixed(2));
            }
        }
    });
    $.ajax({
        type: "POST", url: siteurl+"/ajax/shipping_ajax.php",
        data: {action:"calculatingShipping",pincode:pincode,totalQty:quantity,totalWt:weight},
        success: function(resr) {
            resdata = JSON.parse(resr);
            status = resdata['status'];
          
            if(status=="success"){
                shipping = resdata['shipping'];
                delivery = resdata['delivery'];
               console.log("free shipping = "+isFree);
                if(isFree==1){
                    $(".totShip").html(0.00);
                    $(".totShipDisc").html("<del>"+shipping.toFixed(2)+"</del>");
                    var final = parseFloat(base)+parseFloat(gst)+parseFloat(codcharges);
                } else{
                    $(".totShip").html(shipping.toFixed(2));$(".totShipDisc,.shippingDisc").html("");
                    var final = parseFloat(base)+parseFloat(gst)+parseFloat(shipping)+parseFloat(codcharges);
                }
                $(".taxable").html(base); $(".gst").html(gst); $(".totPay").html(final); validateCouponCode();
            } else{
               
                msg = resdata['message'];
                shipping = resdata['shipping'];
               
                if(isFree==1){
                    $(".totShip").html(0.00);
                    $(".totShipDisc").html("<del>"+shipping.toFixed(2)+"</del>");
                    var final = parseFloat(base)+parseFloat(gst)+parseFloat(codcharges);
                } else{
                    $(".totShip").html(shipping.toFixed(2));$(".totShipDisc,.shippingDisc").html("");
                    var final = parseFloat(base)+parseFloat(gst)+parseFloat(shipping)+parseFloat(codcharges);
                }
                $(".taxable").html(base); $(".gst").html(gst); $(".totPay").html(final); validateCouponCode();
            }
        }
    });
  
   
   
}
function proceed(paymentmode){

    var allprods = '<?=json_encode($proceedArr); ?>';
    var jsonarr = JSON.parse(allprods);
    var finalorder={};
    finalorder['shipingAddress']= $("#selected_shipping_id").val();
    finalorder['totalTaxable']= $(".taxable").html();
    finalorder['totalGst']= $(".gst").html();
    finalorder['totalCodCharges']= $(".totalcod").html();
    finalorder['isFreeShipping']= $("#isFreeShipping").val();
    finalorder['totalShipping']=($("#isFreeShipping").val()=="1"?$(".totShipDisc del").html():$(".totShip").html()) ;
    finalorder['finalPayable']= $(".totPay").html();
    finalorder['items'] = [];
    for(var i=0;i<jsonarr.length;i++){
        var prodid = jsonarr[i]['productid'];
        price = $(".price"+prodid).val(); quantity = $("#quantity"+prodid).val(); delivery = $("#delivery"+prodid).html(); shipping = $("#shipping"+prodid).html(); shippingDisc = $("#shippingDisc"+prodid+ " del").html();
        cgst1 = $("#cgst1"+prodid).html(); cgst2 = $("#cgst2"+prodid).html(); sgst1 = $("#sgst1"+prodid).html(); sgst2 = $("#sgst2"+prodid).html(); igst1 = $("#igst1"+prodid).html(); igst2 = $("#igst2"+prodid).html();
        total = $("#total"+prodid).html(); courierby = $("#courier"+prodid).html(); courierid = $("#courierid"+prodid).html();
        prodTaxable = $("#prodTaxable"+prodid).html(); prodTaxableBasic = $("#prodTaxableBasic"+prodid).html(); prodDiscount = $("#prodDiscount"+prodid).html(); prodDiscountCode = $("#prodDiscountCode"+prodid).html();
        codcharges = $("#codCharges"+prodid).html();
        if(quantity){
            finalorder['items'][i]={};
            var itemar = finalorder['items'][i];
            itemar['productid']=prodid; itemar['perUnitPrice']=jsonarr[i]['perUnitPrice'];
            itemar['company']=jsonarr[i]['company']; itemar['vendorId']=jsonarr[i]['vendorId']; itemar['vendor']=jsonarr[i]['vendor']; itemar['image']=jsonarr[i]['image'];
            itemar['tax']=jsonarr[i]['tax']; itemar['quantity']=quantity; itemar['basictaxable']=prodTaxableBasic; itemar['discountCode']=prodDiscountCode;itemar['discount']=prodDiscount; itemar['taxable']=prodTaxable; itemar['delivery']=delivery; itemar['courierby']=courierby; itemar['courierid']=courierid;
            itemar['shipping']=($("#isFreeShipping").val()==1?shippingDisc:shipping); itemar['shippingDisc']=shippingDisc;
            itemar['cgst1']=cgst1; itemar['cgst2']=cgst2;itemar['sgst1']=sgst1;itemar['sgst2']=sgst2; itemar['igst1']=igst1;itemar['igst2']=igst2;itemar['codCharges']=codcharges;
            itemar['totalPayable']=total;
        }
    }
    finaljson=JSON.stringify(finalorder);
    $.ajax({
        type: "POST", url: siteurl+"/ajax/order_ajax.php",
        data: {action:"proceedOrder",paymentmode:paymentmode,items:finaljson},
        success: function(resr) {
            resdata = JSON.parse(resr);
            status = resdata['status'];
            msg = resdata['message'];
            if(status=="success"){
                resid = resdata['resid'];
                window.location=siteurl+"/payment/"+resid
            } else{
                prod = resdata['failProd'];
                if(prod){
                    $(".prodmsg"+prod).fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html(msg);
                } else{
                    $(".order_msg").fadeIn().removeClass("alert alert-success").addClass("alert alert-danger").html(msg).fadeOut(3000);
                    action = resdata['action'];
                    if(action){setTimeout(function(){ window.location=siteurl+"/"+action }, 3000);}
                }
            }
        }
    });
}
(function($){
    $.fn.spinner = function(){
        this.each(function(){
            var el = $(this);  attrid = el.attr("id"); dataval = el.attr("data-val");  $("#"+attrid).val(dataval)
            el.wrap('<span class="spinner"></span>');  el.before('<span class="sub rounded-left">-</span>'); el.after('<span class="add rounded-right">+</span>');
            el.parent().on('click', '.sub', function(){
                attrid = el.attr("id"); dataval = el.attr("data-val");
                perunitprice=el.attr("data-perprice"); pricebox=el.attr("data-pricebox");
                oldval = $("#"+attrid).val();
                if(parseInt(oldval) > parseInt(el.attr('min'))){
                    newval = parseInt(oldval)-1;
                    $("#"+attrid).val(newval);
                    price = parseFloat(perunitprice)*parseInt(newval);
                    $("#"+pricebox).val(price.toFixed(2)).change();
                }
            });
            el.parent().on('click', '.add', function(){
                attrid = el.attr("id"); dataval = el.attr("data-val");
                oldval = $("#"+attrid).val(); perunitprice=el.attr("data-perprice");
                pricebox = el.attr("data-pricebox");
                if (parseInt(oldval) < parseInt(el.attr('max'))){
                    newval = parseInt(oldval)+1;   $("#"+attrid).val(newval);
                    price = parseFloat(perunitprice)*parseInt(newval);
                    $("#"+pricebox).val(price.toFixed(2)).change();
                }
            });
        });
    };
})(jQuery);
$('input[type=number]').spinner();
function del_alertbox(msg,prodid,currnntReq,funct){
    $("#del_popup").modal("show");
    $("#del_message").html(msg);
    $("#popup_ok").attr("onclick", funct+"('" + prodid + "','" + currnntReq + "')");
}
function removeprod(currnntReq,prodid){ del_alertbox("Do you really want to remove this item ?", prodid,currnntReq,"remove_from_cart"); }
function remove_from_cart(prodid,currnntReq){
    if(currnntReq=="cart"){
        $.ajax({
            url: "<?=SITEURL ?>/ajax/product_ajax.php",
            type: 'POST',
            data: { prodid:prodid, action:'remove_from_cart' },
            success: function(response){
                $("#del_popup").modal("hide"); $("#prodbox"+prodid).remove(); calculateTotal(); isActivatePayment();
                checkcod();
            }
        });
    } else{ window.location="<?=SITEURL; ?>";}
}
function showloader(){ document.getElementById("overlay").style.display = "block"; }
function hideloader(){ document.getElementById("overlay").style.display = "none"; }
</script>
</body>
</html>
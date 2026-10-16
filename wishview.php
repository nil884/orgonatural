<?php include("includes/configuration.php");
include("classes/product.php");
$imgtype = "product";
include("getimgpath.php");
$total_price = 0;
$prod = new Product(); ?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <title>Wishlist : <?=SITE_TITLE ?></title>
    <meta name="description" content="<?=METADESCRIPTION; ?>">
    <meta name="keywords" content="<?=METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Wishlist : <?=SITE_TITLE ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Wishlist : <?=SITE_TITLE ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
</head>
<body class="bg-light">
<div class="main-wrap">
    <?php include "menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>" hreflang="en">Home</a></li><li class="breadcrumb-item active">Wishlist</li></ul></div></div></div>
    <div class="user-wish-pro"> 
        <div class="all_wishlist">
            <div class="pt-2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center justify-content-between">
                            <h2 class="mb-2 mb-md-0 h5">Wishlist Item : <?=count($_SESSION['wishitems'])." Item(s)"; ?></h2>
                            <?php if(count($_SESSION['wishitems']) && ($loguser != "")){ ?>
                            <div class="ml-auto"><input type="button" value="Create Quotation" class="btn btn-primary" id="quot_btn" onclick="create_quotation()"></div>
                            <?php } ?>
                            <div class="cartmsg"></div>
                        </div>
                        <?php $wishdetail = $_SESSION['wishitems'];
                        for($i=0;$i<count($wishdetail);$i++){ 
                        $getshortdetails = $prod->getProductFullDetails($wishdetail[$i]['prod_id']);
                        $prodname = $getshortdetails['name'];
                        if($prodname == "" ){ $delete_message = 1; } }
                        if($delete_message == 1){
                            echo '<div class="col-md-12"><div class="d-flex sec-card-head justify-content-between align-items-center border rounded py-2 px-3 mt-3">Some products added to your Wishlist earlier, are not available in stock now. Please click on delete to remove it from Your wishlist';
                            echo '<button class="delete-product cc-cursor-pointer btn btn-danger btn-sm thumb-hov-btn ml-3" onclick="remove_from_wishlist_delete()" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></button></div></div>';   
                        } ?>
                    </div>
                </div>
            </div>
            <div class="content py-3 py-sm-3 pt-md-4">
                <div class="container">
                    <div class="card">
                    <div class="row m-0">
                    <?php $wishdetail = $_SESSION['wishitems']; 
                    if(count($_SESSION['wishitems'])){ ?>
                    <div class="col-md-12 border-bottom pro-tab-head d-none d-md-block">
                        <div class="row cc-fw-7">
                            <div class="col-md-2 py-3 d-none d-md-block">Products</div>
                            <div class="col-md-10"><div class="row"><div class="col-md-6 col-lg-5 py-3 d-none d-md-block">Description</div><div class="col-md-3 col-lg-3 py-3 d-none d-md-block">Price</div><div class="col-md-3 col-lg-2 py-3 d-none d-md-block">Total</div><div class="col-md-1 col-lg-1 py-3 d-none d-lg-block">Cart</div></div></div>
                        </div>
                    </div> 
                    <? for($i=0;$i<count($wishdetail);$i++){ 
                    $getshortdetails = $prod->getProductFullDetails($wishdetail[$i]['prod_id']);
                    $variations = $getshortdetails['variations']; 
                    $currentvar = $getshortdetails['currentVariartions'];
                    $variationarr = array(); $varcnt = 0;
                    foreach($variations as $key=>$val){ $variationarr[$key]= $currentvar[$varcnt];  $varcnt++;  }
                    //$prodname=($getshortdetails[0]['parent_id']==0?$getshortdetails[0]['prod_name']:$prod->getParentName($wishdetail[$i]['prod_id']));
                    $prodname = $getshortdetails['name'];
                    $images = $getshortdetails['image'];
                    if(count($images)){ $img= SITEURL."/".$thumb3_path."/".$images[0]['img_name'];}
                    else{ $img= SITEURL."/img/projectimage/product-default.png";  }?>
                    <div class="col-md-12 pro-tab-column border-bottom py-3">
                        <?php if($prodname != ""){ ?>
                        <div class="row">
                            <div class="col-4 pr-0 px-md-3 col-sm-3 col-md-2 col-lg-2"><div class="pro-list-thumb"><img src="<?=$img;?>" class="rounded img-fluid img-thumbnail" alt="wish-thumb" title="<?php echo $prodname; ?>" height="100"></div></div>  
                            <div class="col-8 col-sm-9 col-md-10 col-lg-10 pro-tab-body position-relative">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-5 pro-tab-item-name">   
                                        <h2 class="prod-name h6"><a href="<?=SITEURL;?>/<?=getUrl("Product",$wishdetail[$i]['prod_id']); ?>" target="_blank" hreflang="en"><?php echo $prodname; ?></a></h2>
                                        <? if(count($variationarr)){ ?><div class="pro-tab-spec">
                                        <? foreach($variationarr as $key=>$val){
                                        ?><span class="mr-2"><span class="text-muted"><?=$key;?></span> : <?=$val;?></span><? } ?></div>
                                        <? } ?>
                                        <div class="wish-qty-col qtybox qtybox-sm mt-2">
                                            <?php $stock = $getshortdetails['stock']; ?>
                                            <span class="sub rounded-left">-</span><input type="number" class="quantity_no form-control mw-100 rounded-0" id="selected_quant_product-<?=$wishdetail[$i]['prod_id'] ?>" min="1" max="<?=($stock<5?$stock:5); ?>" value="<? echo $wishdetail[$i]['quantity']; ?>" onchange="setquan('<?php echo $i; ?>','<?php echo $wishdetail[$i]['prod_id'] ?>')"><span class="add rounded-right">+</span>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-3 col-lg-3 prod-total-price d-none d-md-block">
                                        <?php $total_price = $total_price + ($getshortdetails['price'] * $wishdetail[$i]['quantity']) ?>
                                        <span class="mr-1 h6"><i class="fa fa-inr"></i><?=$getshortdetails['price']; ?> </span><br>(Including <?php echo $getshortdetails['tax']; ?> % GST)
                                    </div>                                    
                                    <div class="col-8 col-md-3 col-lg-2 prod-total-price pt-2 pt-md-0 h6"><span class="mr-1"><i class="fa fa-inr"></i><?=$getshortdetails['price'] * $wishdetail[$i]['quantity'] ; ?></span></div>
                                    <div class="col-12 pt-2 pt-sm-2 pt-md-3 pt-lg-0 col-lg-2">
                                        <?php if($getshortdetails['stock'] != 0){ ?> 
                                        <button type="button" class="thumb-hov-btn btn btn-primary btn-sm mr-lg-3 pdod-wish cart_btn_<?=$wishdetail[$i]['prod_id'] ?>" <?php if(in_array($wishdetail[$i]['prod_id'],$cart_item_array_id )){echo "disabled";} ?> onclick="add_to_cart(<?=$wishdetail[$i]['prod_id'] ?>,<?php echo $wishdetail[$i]['quantity']?>)" data-toggle="tooltip" data-placement="top" title="Add to Cart"><i class="fa fa-shopping-bag" aria-hidden="true"></i> <span class="d-inline-block d-lg-none">Add to Cart</span></button> <?php }else { echo "<button type='text' class='btn btn-danger btn-sm mr-lg-3' disabled><i class='fa fa-shopping-bag' aria-hidden='true'></i> <span class='d-inline-block d-lg-none'>Out of Stock</span></button>"; }?> 
                                        <button class="delete-product cc-cursor-pointer btn btn-danger btn-sm thumb-hov-btn" onclick="remove_from_wishlist('<?=$wishdetail[$i]['prod_id'];?>')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? } ?>
                    </div>
                    <?php } } else{ echo "<div class='text-center col-md-10 offset-md-1 py-4'><img src='".SITEURL."/img/projectimage/empty-wishlist.png' alt='cart-empty' width='120' class='mb-3'><p class='h5 mb-4'>Your Wishlist is empty.<br>Favourite the products and buy them whenever you like!</p><a href='".SITEURL."' class='btn btn-primary py-2' hreflang='en'>Start Shopping</a></div>"; } ?>
                </div>
                    </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script src="<?=SITEURL;?>/js/product.js"></script>
<script>
var siteurl="<?=SITEURL; ?>"
function remove_from_wishlist(prodid){ del_alertbox("Do you really want to remove this item from wishlist?", prodid,"remove_from_wishlist_db"); }
function del_alertbox(msg,i,funct,type){
    $("#del_popup").modal("show"); 
    $("#del_popup .modal-dialog").addClass("modal-sm"); 
    $("#del_message").html(msg);
    $("#popup_ok").attr("onclick", funct+"('" + i + "','" + type + "')");
}
function remove_from_wishlist_db(prodid){
    $.ajax({
        url : "<?php echo SITEURL ?>/ajax/product_ajax.php",
        type : 'POST',
        data : {
            prodid : prodid,
            action :'remove_from_wishlist',
        },
        success : function(response){
            response =  $.trim(response)
            if(response){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Item removed successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $("#wishlist-count").load(" #wishlist-count");
                $(".all_wishlist").load( " .all_wishlist");
            }
        }
    });  
}
    
function custqtyhandlar(){
    $('.add').click(function(){	
        if($(this).prev().val() < 5){
            $(this).prev().val(+$(this).prev().val() + 1);
            $(this).prev().trigger("change");
        }
    });
    $('.sub').click(function(){	
    if ($(this).next().val() > 1){
                if($(this).next().val() > 1){
                $(this).next().val(+$(this).next().val() - 1);
                $(this).next().trigger("change");
            } 
        }	
    });
};
custqtyhandlar();
function setquan(arrayid,prod_id){
    no_of_unit = $("#selected_quant_product-"+prod_id).val();
    var info = {no_of_unit:no_of_unit,arrayid:arrayid,prod_id:prod_id,action:"decidequantity_wishlist"};
    $.ajax({
        type : "POST",
        url : "<?php echo SITEURL ?>/ajax/product_ajax.php",
        data : info,
        success:function(response){
            $(".user-wish-pro").load(location.href + " .all_wishlist", function(){ custqtyhandlar(); });
        }
    });
}
$(document).ready(function(){ $('[data-toggle="tooltip"]').tooltip(); });
function remove_from_wishlist(prodid){ del_alertbox("Do you really want to remove this item from wishlist?", prodid,"remove_from_wishlist_db"); }
<?php if(isset($loguser) && ($loguser != "")){ ?>
function create_quotation(){
    var mail_id = '<?php echo $logUserMail;  ?>';
    var msg = '<?php  if($getbuyer_details[0]['tax_no'] == "" || $getbuyer_details[0]['company_name'] == "" ) {
        echo '<br>You have Not added Tax Detail.Do u want to continue?';
    } ?>';
    var cmpname = "<br>Company name : <?php if($getbuyer_details[0]['company_name'] == "") { echo "Not Found"; } echo $getbuyer_details[0]['company_name'] ?>";
    var gstno = "<br>GST number : <?php if($getbuyer_details[0]['tax_no'] == "") { echo "Not Found"; } echo $getbuyer_details[0]['tax_no'] ?>" ;
    del_alertbox("<h5 class='mb-3'>Sending quotation email to</h5> Email : "+mail_id+cmpname+gstno+msg,'',"create_quotation_mail");
}
function create_quotation_mail(){
	$("#popup_ok").prop('disabled', true);
    $.ajax({
        type : "POST",
        url : "<?php echo SITEURL ?>/ajax/wishlist_quotation.php",
        success:function(response){
            response = $.trim(response);
            $("#popup_ok").prop('disabled', false);
            if(response == 1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Quotation Mail Sent Successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
            }
            else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after Some Time").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
            }
        }
    });
}
<?php } ?>
function remove_from_wishlist_delete(){ del_alertbox("Do you really want to remove ?", '',"remove_from_wishlist_delete_db"); }
function remove_from_wishlist_delete_db(){
    isstock = $("#isstock").val();
    if(isstock == 0){
    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please remove out of stock Product").delay(3000).fadeOut();
    } else {
        info = {action:'remove_deleted_wishlist' }
        $.ajax({
            type : "POST",
            url : "<?php echo SITEURL ?>/ajax/product_ajax.php",
            data : info,
            success:function(response){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Item removed Successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $( "#wishlist-count").load(" #wishlist-count");
                $( ".all_wishlist").load( " .all_wishlist");
            }
        });    
    }
}
</script>
</body>
</html>
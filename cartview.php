<?php include("includes/configuration.php");
include("classes/product.php");
$imgtype = "product";
include("getimgpath.php");
$prod = new Product();
$cartdetails = $_SESSION['shopping_cart']; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cartview : <?=SITE_TITLE ?></title>
    <meta name="description" content="<?=METADESCRIPTION; ?>">
    <meta name="keywords" content="<?=METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Cartview : <?=SITE_TITLE ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Cartview : <?=SITE_TITLE ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
</head>
<body class="bg-light">
<div class="main-wrap">
    <?php include "menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>" hreflang="en">Home</a></li><li class="breadcrumb-item active">Cart Details</li></ul></div></div></div>
    <div class="user-cart-pro"> 
        <div class="all_cartlist"> 
            <div class="pt-2">
                <div class="container">
                    <div class="row">
                        <div class="col-12"><h2 class="mb-2 mb-md-0 h5">Shopping Cart : <?=count($_SESSION['shopping_cart'])." Item(s)"; ?></h2></div>
                        <?php for($i=0;$i<count($cartdetails);$i++){ 
                        $getshortdetails = $prod->getProductFullDetails($cartdetails[$i]['prod_id']);
                        $prodname = $getshortdetails['name'];
                        if($prodname == "" ){ $delete_message = 1; } }
                        if($delete_message == 1){
                        echo '<div class="col-md-12"><div class="border rounded py-2 px-3 text-muted mt-3">Some products added to your cart earlier, are not available in stock now, so we have automatically removed them from your cart.</div></div>'; } ?>
                    </div>
                </div>
            </div>
            <div class="content py-3 py-sm-3 pt-md-4">
                <div class="container"> 
                    <div class="card">
                        <div class="col-lg-12">
                            <div class="row">
                                <?php $isstock = 1;
                                if(count($_SESSION['shopping_cart'])){
                                $total_price = 0; ?>
                                <div class="col-md-12 border-bottom pro-tab-head d-none d-md-block">
                                    <div class="row cc-fw-7">
                                        <div class="col-md-2 py-3 d-none d-md-block">Products</div>
                                        <div class="col-md-10"><div class="row"><div class="col-md-6 col-lg-6 py-3 d-none d-md-block">Description</div><div class="col-md-3 col-lg-3 py-3 d-none d-md-block">Price</div><div class="col-md-3 col-lg-2 py-3 d-none d-md-block">Total</div><div class="col-md-1 col-lg-1 py-3 d-none d-lg-block">Delete</div></div></div>
                                    </div>
                                </div> 
                                <? for($i=0;$i<count($cartdetails);$i++){
                                    $crow=$cartdetails[$i];
                                $getshortdetails = $prod->getProductFullDetails($crow['prod_id']);
                                $stock = $getshortdetails['stock'];
                                $status=selectQuery(PRODINFO,"isActive,parent_cat,master_cat,sub_cat","id=".$crow['prod_id']);
                                $parstatus=selectQuery(PRODCAT,"isActive","id=".$status[0]['parent_cat']);
                                $mststatus=selectQuery(PRODCAT,"isActive","id=".$status[0]['master_cat']);
                                $substatus=selectQuery(PRODCAT,"isActive","id=".$status[0]['sub_cat']);
                                if($status[0]['isActive']==0||$parstatus[0]['isActive']==0||$mststatus[0]['isActive']==0||$substatus[0]['isActive']==0){$pstatus=0;  }else{$pstatus=1;}
                                
                                $variations = $getshortdetails['variations']; 
                                $currentvar = $getshortdetails['currentVariartions'];
                                $variationarr = array(); $varcnt=0;
                                foreach($variations as $key=>$val){ $variationarr[$key]= $currentvar[$varcnt]; $varcnt++; }
                                //$prodname = ($getshortdetails[0]['parent_id']==0?$getshortdetails[0]['prod_name']:$prod->getParentName($crow['prod_id']));
                                $prodname = $getshortdetails['name'];
                                $images = $getshortdetails['image'];
                                if(count($images)){ $img= SITEURL."/".$thumb3_path."/".$images[0]['img_name'];}
                                else{ $img= SITEURL."/img/projectimage/product-default.png"; } ?>
                                <div class="col-12 pro-tab-column border-bottom py-3">
                                    <?php if($prodname != ""){ 
                                    if($crow['quantity'] > $stock||$pstatus==0){ $isstock = 0; } else ?>
                                    <div class="row">
                                        <div class="col-4 pr-0 px-md-3 col-sm-3 col-md-2 col-lg-2"><div class="pro-list-thumb"><a href="<?=SITEURL;?>/<?=getUrl("Product",$crow['prod_id']); ?>" target="_blank" hreflang="en"><img src="<?=$img;?>" class="rounded img-thumbnail img-fluid" alt="cart-thumb" title="<?php echo $prodname; ?>"></a></div></div>
                                        <div class="col-8 col-sm-9 col-md-10 col-lg-10 pro-tab-body position-relative"> 
                                            <div class="row">
                                                <div class="col-12 col-md-6 col-lg-6 pro-tab-item-name">   
                                                    <h2 class="prod-name h6"><a href="<?=SITEURL;?>/<?=getUrl("Product",$crow['prod_id']); ?>" target="_blank" hreflang="en"><?php echo $prodname; ?></a></h2>
                                                    <?php if($crow['quantity'] > $stock){ echo "<div class='text-danger mb-1'>Sorry the quantity you have added in cart is not available, current stock is only ".$stock."</div>"; } ?>
                                                    <? if(count($variationarr)){ ?><div class="pro-tab-spec">
                                                    <? foreach($variationarr as $key=>$val){
                                                    ?><span class="mr-2"><span class="text-muted"><?=$key;?></span> : <?=$val;?></span><? } ?></div><? } ?>
                                                    <div class="qtybox qtybox-sm mt-2">
                                                        <?php ?>
                                                        <span class="sub rounded-left">-</span><input type="number" class="quantity_no form-control mw-100 rounded-0" id="selected_quant_product-<?=$crow['prod_id'] ?>" min="1" max="<?=($stock<5?$stock:5); ?>" value="<? echo $crow['quantity']; ?>" onchange="setquan('<?php echo $i; ?>','<?php echo $crow['prod_id'] ?>')"><span class="add rounded-right">+</span>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-3 col-lg-3 prod-total-price d-none d-md-block">
                                                    <span class="h6"><i class="fa fa-inr"></i><?=$getshortdetails['price']; ?></span><br> (Including <?php echo $getshortdetails['tax']; ?> % GST) 
                                                    <?php $total_price = $total_price + ($getshortdetails['price'] * $crow['quantity']) ?>
                                                </div>
                                                <div class="col-12 col-md-3 col-lg-2 prod-total-price pt-2 pt-md-0 h6"><i class="fa fa-inr"></i><?=$getshortdetails['price'] * $crow['quantity'] ; ?></div>
                                                <div class="col-12 pt-2 pt-sm-2 pt-md-3 pt-lg-0 col-lg-1 ship-tabl-del">
                                                    <button type="button" class="delete-product cc-cursor-pointer btn btn-danger btn-sm thumb-hov-btn" onclick="remove_from_cart('<?=$crow['prod_id'];?>')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i> <span class="d-inline-block d-lg-none">Remove</span></button>
                                                </div>
                                                <span class="d-none">Quantity = <?php  echo $crow['quantity']; ?></span>
                                                <?php if($pstatus==0){ echo "<span class='text-danger'>This product is currently not available"; $isstock= 0;}else if($stock == 0){ echo "Out of stock"; $isstock= 0; } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" id="isstock" value= "<?php echo $isstock ?>">
                        <div class="col-md-6 offset-md-6 col-lg-4 offset-lg-8 offset-xl-9 col-xl-3 p-0 mt-3 mt-sm-3 mt-md-0">    
                            <div class="card mt-3 p-3"> 
                                <div class="row mb-2 cc-fw-5 cart-view-total-price h5">
                                    <div class="col-7 pb-2">Total Price</div>  
                                    <div class="col-5 pb-2 text-right"><i class="fa fa-inr"></i> <?php echo $total_price; ?></div> 
                                </div>                   
                                <?php if($_SESSION['shopping_cart'] != "" ){ 
                                if($loguser ==""){ ?>
                                <a class="btn btn-primary btn-block btn-lg" href="<?=SITEURL ?>/login" hreflang="en">Proceed To Checkout</a>
                                <?php } else{ ?>
                                <form method="POST" action="<?=SITEURL;?>/checkout"  id="cartsubmit"> 
                                    <input type="hidden" name="action" value="cart">
                                    <button type="button" class="btn btn-primary btn-block btn-lg" onclick="checkstock()">Proceed Further</button>
                                </form>
                                <?php } } ?>
                            </div>
                        </div>
                        <?php } else{ echo "<div class='text-center col-md-10 offset-md-1 py-4'><img src='".SITEURL."/img/projectimage/cart-empty.png' alt='cart-empty' width='120'><p class='h5 mb-4'>If you are not able to see some of your products added to the cart earlier,<br> It's because they are not available in the stock now</p><a href='".SITEURL."' class='btn btn-primary py-2' hreflang='en'>Start Shopping</a></div>"; } ?>
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
function remove_from_cart(prodid){ del_alertbox("Do you really want to remove this item from cart?", prodid,"remove_from_cart_db"); }
function del_alertbox(msg,i,funct,type){
    $("#del_popup").modal("show");
    $("#del_popup .modal-dialog").addClass("modal-sm");
    $("#del_message").html(msg);
    $("#popup_ok").attr("onclick", funct+"('" + i + "','" + type + "')");
}
function remove_from_cart_db(prodid){
    $.ajax({
        url : "<?php echo SITEURL ?>/ajax/product_ajax.php",
        type : 'POST',
        data : { prodid:prodid, action:'remove_from_cart', },
        success: function(response){
            response = $.trim(response)
            if(response){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Item Removed Successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $("#cart-count").load(" #cart-count");
                $(".all_cartlist").load(" .all_cartlist");
            }
        }
    });  
}
function custqtyhandlar(){
    $('.add').click(function (){	
        if($(this).prev().val() < 5){
            $(this).prev().val(+$(this).prev().val() + 1);
            $(this).prev().trigger("change");
		}
    });
    $('.sub').click(function (){	
        if($(this).next().val() > 1){
            if ($(this).next().val() > 1){
                $(this).next().val(+$(this).next().val() - 1);
                $(this).next().trigger("change");
            } 
		}	
    });
};
custqtyhandlar();
function setquan(arrayid,prod_id){
    no_of_unit = $("#selected_quant_product-"+prod_id).val();
    var info = {no_of_unit:no_of_unit,arrayid:arrayid,prod_id:prod_id,action:"decidequantity"};
    $.ajax({
        type : "POST",
        url : "<?php echo SITEURL ?>/ajax/product_ajax.php",
        data : info,
        success:function(response){
            $(".user-cart-pro").load(location.href + " .all_cartlist", function(){ custqtyhandlar(); });
        }
    });
}
function checkstock(){
    isstock = $("#isstock").val();
    if(isstock == 0){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Remove Out of stock Product").delay(3000).fadeOut();
    } else{
        info = {action:'remove_deleted_cart' }
        $.ajax({
            type : "POST",
            url : "<?php echo SITEURL ?>/ajax/product_ajax.php",
            data : info,
            success:function(response){ $("#cartsubmit").submit(); }
        });      
    }
} 
$(document).ready(function(){ $('[data-toggle="tooltip"]').tooltip(); });
</script>
</body>
</html>
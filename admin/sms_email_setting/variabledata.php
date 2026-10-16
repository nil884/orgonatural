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
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Variable</h5></div></div>
                <div class="card-body mail-temp-var"> 
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Common Variable</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$siteurl% => Website Base Url</li>
                            <li class="mb-2">%$sitename% => Website Name </li>
                            <li class="mb-2">%$smssitename% => Sms site name</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Vendor registration</h6> 
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$vendorname% => Vendor Name</li>
                            <li class="mb-2">%$vendoremail% => Vendor email</li>
                            <li class="mb-2">%$vendorcontact% => Vendor Contact No.</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Vendor Profile Complete</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$vendorname% => Vendor Name</li>
                            <li class="mb-2">%$vendoremail% => Vendor email</li>
                            <li class="mb-2">%$vendorcontact% => Vendor Contact No.</li>
                        </ul>
                    </div>                
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Vendor Payment Successful</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$invoiceid% => Invoice Id</li>
                            <li class="mb-2">%$plan% => Plan </li>
                            <li class="mb-2">%$plan_type% => Plan Type</li>
                            <li class="mb-2">%$txnid% => Transaction Id </li>
                            <li class="mb-2">%$amount% => Amount</li>
                            <li class="mb-2">%$vendornickname% => Vendor Nick Name</li>
                            <li class="mb-2">%$vendorname% => Vendor Name</li>
                            <li class="mb-2">%$vendoremail% => Vendor email</li>
                            <li class="mb-2">%$vendormobileno% => Vendor Contact No.</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Vendor Approved</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$vendorname% => Vendor Name</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Vendor Plan Expire</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$vendornickname% => Vendor Nick Name</li>
                            <li class="mb-2">%$vendorcurrentplan% => Vendor Current Plan</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Vendor Plan Upgrade After Current Plan expiry</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$vendornickname% => Vendor Nick Name</li>
                            <li class="mb-2">%$vendorcurrentplan% => Vendor Current Plan</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Buyer Registration</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$name% => Buyer Name</li>
                            <li class="mb-2">%$gender% => Buyer gender</li>
                            <li class="mb-2">%$mobile% => Buyer Mobile No</li>
                            <li class="mb-2">%$email% => Buyer email</li>
                            <li class="mb-2">%$userid% => base64_encode($insert)</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>User Subscribe</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$user_email% => $emailsub</li>
                            <li class="mb-2">%$subscription_id% => base64_encode($insert)</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Reset Password</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$userid% => base64_encode($userid)</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Enquiry on sms</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$name% => User name</li>
                            <li class="mb-2">%$mobile% => User mobile No</li>
                            <li class="mb-2">%$email% => User email-id</li>
                            <li class="mb-2">%$message% => Message</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Contact</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$name% => User name</li>
                            <li class="mb-2">%$mobile% => User mobile No</li>
                            <li class="mb-2">%$email% => User email-id</li>
                            <li class="mb-2">%$message% => Message</li>
                        </ul>
                    </div>                           
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Bulk order</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$name% => User name</li>
                            <li class="mb-2">%$mobile% => User mobile</li>
                            <li class="mb-2">%$email% => User email</li>
                            <li class="mb-2">%$Address% => User Address</li>
                            <li class="mb-2">%$Productname% => Product Name</li>
                            <li class="mb-2">%$qunatity% => Quantity</li>
                            <li class="mb-2">%$variation_detail% => Product Variation Detail</li>
                            <li class="mb-2">%$Expected_delivery_date% => Expected delivery date</li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Wishlist Quotation</h6> 
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$name% => User name</li>
                            <li class="mb-2">%$email% => User email </li>
                        </ul>
                    </div>                    
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Order</h6>
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$username% => User name</li>
                            <li class="mb-2">%$order_id% => Order Id</li>
                            <li class="mb-2">%$product_detail% => Product Detail</li>
                            <li class="mb-2">%$shipping_name% => Shipping Name</li> 
                            <li class="mb-2">%$address% => Shipping Adress</li>
                            <li class="mb-2">%$landmark% => Shipping Landmark</li>
                            <li class="mb-2">%$city% => Shipping City</li>
                            <li class="mb-2">%$state% => Shipping State</li>
                            <li class="mb-2">%$country% => Shipping Country</li>
                            <li class="mb-2">%$pincode% => Shipping Pincode</li>
                            <li class="mb-2">%$shipping_mobile% => Shipping mobile no</li>
                            <li class="mb-2">%$payment_mode% => Order Payment Mode</li>
                        </ul>
                        <hr> 
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$Product_image% => Product Image</li>
                            <li class="mb-2">%$product_name% => Product Name</li>
                            <li class="mb-2">%$quantity% =>Product Quantity</li>
                            <li class="mb-2">%$Total_amount% => Total Amount</li>
                            <li class="mb-2">%$vendor_name% => Vendor Name</li> 
                            <li class="mb-2">%$username% => Buyer Name</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Order Cancellation</h6>   
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$Product_image% =>Product Image</li>
                            <li class="mb-2">%$product_name% => Product Name</li>
                            <li class="mb-2">%$quantity% => Product Quantity</li>
                            <li class="mb-2">%$Total_amount% => Total Amount</li>
                            <li class="mb-2">%$vendor_name% => Vendor Name</li> 
                            <li class="mb-2">%$username% => Buyer Name</li>
                            <li class="mb-2">%$refundable_amount% => Refundable Amount </li>
                            <li class="mb-2">%$cancellationgenerator% => Cancellation Requested By</li>
                            <li class="mb-2">%$cancellationreason% => Cancellation Reason </li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Refund Mail</h6>
                        <ul class="pl-3 text-secondary"> 
                            <li class="mb-2">%$refund_amount% => Refund Amount </li>
                            <li class="mb-2">%$order_id% => Order Id </li>
                            <li class="mb-2">%$username% => Buyer Name</li>
                            <li class="mb-2">%$request_type% => Request Type (Return/Cancel)</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Return Request Mail</h6>
                        <ul class="pl-3 text-secondary"> 
                            <li class="mb-2">%$product_name% => Product Name </li>
                            <li class="mb-2">%$order_id% => Order Id </li>
                            <li class="mb-2">%$vendor_name% => Vendor Name</li> 
                            <li class="mb-2">%$username% => Buyer Name</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Return Initiate Mail</h6>
                        <ul class="pl-3 text-secondary"> 
                            <li class="mb-2">%$product_name% => Product Name</li>
                            <li class="mb-2">%$order_id% => Order Id</li> 
                            <li class="mb-2">%$vendor_name% => Vendor Name</li> 
                            <li class="mb-2">%$username% => Buyer Name</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb border-bottom mb-3">
                        <h6>Return Cancel Mail</h6>
                        <ul class="pl-3 text-secondary"> 
                            <li class="mb-2">%$product_name% => Product Name</li>
                            <li class="mb-2">%$order_id% => Order Id</li> 
                            <li class="mb-2">%$vendor_name% => Vendor Name</li> 
                            <li class="mb-2">%$username% => Buyer Name</li>
                        </ul>
                    </div>
                    <div class="mail-com-varb">
                        <h6>Requirement Request</h6>   
                        <ul class="pl-3 text-secondary">
                            <li class="mb-2">%$name% => User name,</li>
                            <li class="mb-2">%$mobile% => User Mobile,</li> 
                            <li class="mb-2">%$email% => User email,</li>
                            <li class="mb-2">%$message% => User Message,</li>
                            <li class="mb-2">%$requirement_prod_name% => Product Name,</li>
                        </ul>
                    </div>
                    <nav><ul class="pagination justify-content-end mb-0"></ul></nav>
                </div>
            </div>   
        </div> 
        <?php include '../footer.php'; ?>
    </div>
</div>
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
    $(document).on("click", ".pagination li.current-page:not(.active)", function(){
        return showPage(+$(this).text());
    });
    $("#next-page").on("click", function(){ return showPage(currentPage + 1); });
    $("#previous-page").on("click", function(){ return showPage(currentPage - 1); });
    $(".pagination").on("click", function(){ $("html,body").animate({ scrollTop: 0 }, 0); });
});
</script>
</body>
</html>      
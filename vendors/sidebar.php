<nav class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="<?=VENDORURL; ?>dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i><span class="title">Dashboard</span></a></li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="javascript:void(0);"><span class="fa fa-shopping-cart"></span> <span class="title">Products</span></a>
            <ul class="dropdownMenu list-unstyled">
                <li><a class="nav-link" href="<?=VENDORURL;?>product/index.php"><span class="title">Add Product</span></a></li><li><a class="nav-link" href="<?=VENDORURL;?>product/viewproduct.php"><span class="title">View Product</span></a></li>
                <?php  if($get_vendor_details[0]['bulk_import'] == 1 ) { ?>
                <li><a class="nav-link" href="<?=VENDORURL;?>product/bulkimport.php"><span class="title">Bulk Import</span></a></li>  <?  } ?>
                
                <li><a class="nav-link" href="<?=VENDORURL;?>product/bulkexport.php"><span class="title">Export Products</span></a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="javascript:void(0);"><span class="fa fa-list-alt"></span><span class="title">Orders</span></a>
            <ul class="dropdownMenu list-unstyled">
                <li><a class="nav-link" href="<?=VENDORURL;?>order/"><span class="title">Inprocess Order</span></a></li><li><a class="nav-link" href="<?=VENDORURL;?>order/delivered.php"><span class="title">Delivered Order</span></a></li><li><a class="nav-link" href="<?=VENDORURL;?>order/cancel.php"><span class="title">Cancelled Order</span></a></li><li><a class="nav-link" href="<?=VENDORURL;?>order/return.php"><span class="title">Returned Order</span></a></li>
                   <li><a class="nav-link" href="<?=VENDORURL;?>order/rto_orders.php"><span class="title">RTO Order</span></a></li>
                <li><a class="nav-link" href="<?=VENDORURL;?>order/unknown_status.php"><span class="title">Other Order</span></a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="javascript:void(0);"><span class="fa fa-address-book"></span> <span class="title">My Account</span></a>
            <ul class="dropdownMenu list-unstyled">
                <li><a class="nav-link" href="<?=VENDORURL;?>myaccount/index.php"><span class="title">Profile</span></a></li><li><a class="nav-link" href="<?=VENDORURL;?>myaccount/plandetails.php"><span class="title">My Plan</span></a></li><li><a class="nav-link" href="<?=VENDORURL;?>myaccount/loginhistory.php"><span class="title">Login History</span></a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="javascript:void(0);"><span class="fa fa-file-text"></span> <span class="title">Reports</span></a>
            <ul class="dropdownMenu list-unstyled">
                <li><a class="nav-link" href="<?=VENDORURL;?>reports/gst.php"><span class="title">GST</span></a></li><li><a class="nav-link" href="<?=VENDORURL;?>myaccount/disbursement.php"><span class="title">Disbursement</span></a></li>
            </ul>
        </li>
    </ul>
</nav>
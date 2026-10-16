<?php include("../includes/configuration.php"); include("../classes/user.php"); include("../classes/product.php"); include("../getimgpath.php"); $product_path = getimgconfigpaths('product'); $user = new User(); $prod = new Product(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Review : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
    <?php include "../commoncss.php" ?>
</head>
<body>
<div class="main-wrap"> 
<?php include "notification_account.php" ?>    
    <?php include "../menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">Product Review</li></ul></div></div></div>
    <div class="content py-3">
        <div class="container"> 
            <h2 class="mb-0 cc-fw-5 h5">Product Review</h2>
            <p class="mb-3 text-muted">View all your product reviews</p>
            <div class="card">
                <div class="row mx-0">
                    <?php include('sidebar.php')?>
                    <div class="col-sm-12 col-md-12 col-lg-9 py-3">
                        <h6 class="mb-3 cc-fw-5">Product Review</h6>
                        <?php $reviews = $user->getReview($_SESSION['reguser']);
                        if(count($reviews)){
                        for($i=0;$i<count($reviews);$i++){
                        $getshortdetails = $prod->getShortDetails($reviews[$i]['prod_id']);
                        $prodname = ($getshortdetails[0]['parent_id']==0?$getshortdetails[0]['prod_name']:$prod->getParentName($reviews[$i]['prod_id'])); $prodimg = $prod->getProductImageForDisplay($reviews[$i]['prod_id']);
                        if($prodimg[0]['img_name']){
                            $img = SITEURL."/".$product_path[0]['thumb2_path']."/".$prodimg[0]['img_name'];
                        }else{ $img = SITEURL."/img/projectimage/product-default.png"; }
                        $rate = $reviews[$i]['rate'];  ?>
                        <div class="media border-bottom ac-review-col py-3">
                            <div class="pro-list-thumb mr-3"><img src="<?=$img;?>" alt="rev-thumb" class="rounded img-fluid card p-1 "></div>
                            <div class="media-body">
                                <div class="rating-group review-star-count">
                                    <label aria-label="0 stars" class="rating-label <?=($rate>=0.0?'rating-icon-star':''); ?>" for="rating2-0"></label><label aria-label="0.5 stars" class="rating-label rating-label-half" for="rating2-05"><i class="rating-icon <?=($rate>=0.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label><label aria-label="1 star" class="rating-label" for="rating2-10"><i class="rating-icon <?=($rate>=1.0?'rating-icon-star':''); ?> fa fa-star"></i></label><label aria-label="1.5 stars" class="rating-label rating-label-half" for="rating2-15"><i class="rating-icon <?=($rate>=1.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label><label aria-label="2 stars" class="rating-label" for="rating2-20"><i class="rating-icon <?=($rate>=2.0?'rating-icon-star':''); ?> fa fa-star"></i></label><label aria-label="2.5 stars" class="rating-label rating-label-half" for="rating2-25"><i class="rating-icon <?=($rate>=2.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label><label aria-label="3 stars" class="rating-label" for="rating2-30"><i class="rating-icon <?=($rate>=3.0?'rating-icon-star':''); ?> fa fa-star"></i></label><label aria-label="3.5 stars" class="rating-label rating-label-half" for="rating2-35"><i class="rating-icon <?=($rate>=3.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label><label aria-label="4 stars" class="rating-label" for="rating2-40"><i class="rating-icon <?=($rate>=4.0?'rating-icon-star':''); ?> fa fa-star"></i></label><label aria-label="4.5 stars" class="rating-label rating-label-half" for="rating2-45"><i class="rating-icon <?=($rate>=4.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label><label aria-label="5 stars" class="rating-label" for="rating2-50"><i class="rating-icon <?=($rate==5.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                                </div>
                                <p class="mb-1 small cc-primary-color"><?php echo $prodname ?></p>
                                <h6 class="mb-2"><?=$reviews[$i]['review_title']; ?></h6>                                
                                <div class="mb-2 review-date">
                                    <span class="mr-2"><i class="fa fa-calendar-o mr-1"></i> <?=date("d M Y",strtotime($reviews[$i]['review_date']));?></span>
                                    <span><i class="fa fa-user mr-1"></i></span>
                                    <?php if($reviews[0]['isApproved']== 1 ){ echo "Approved"; } else{ echo "Pending for approval"; } ?>
                                </div><div class="text-muted"><?=$reviews[$i]['review']; ?></div>
                            </div>
                        </div>
                        <? } } else{ ?>
                        <div class="pt-4 pb-5 text-center">
                            <img src="<?php echo SITEURL; ?>/img/projectimage/empty-review.png" alt="empty-review" width="120">
                            <p class="lead mt-3 text-muted">We did not see your review comments yet,<br>post reviews, we love to hear you.</p>
                            <a href='<?=SITEURL;?>' class='btn btn-primary py-2'>Write a Review</a>
                        </div>
                        <?php } ?>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php" ?> 
<?php include "profilejs.php" ?> 
</body>
</html>
<? include("../includes/configuration.php");
include("../classes/product.php");
$prodid =
$prod = new Product();
$imgtype = "product";
include("getimgpath.php");
// Very important to set the page number first.
if(!(isset($_POST['pagenum']))){ $pagenum = 1; } else{ $pagenum = intval($_POST['pagenum']); }
$where = $_POST['where'];$prodid = $_POST['where']; 
//Number of results displayed per page 	by default its 10.
$page_limit = REVIEWCNTPAGE;
// Get the total number of rows in the table
$totalarticle = $prod->getReviewCount($prodid);
$cnt = $totalarticle;
//Calculate the last page based on total number of rows and rows per page.
if($cnt!=0){ $last = ceil($cnt/$page_limit); } else{$last=1;}
//this makes sure the page number isn't below one, or more than our maximum pages
if($pagenum < 1){ $pagenum = 1; } elseif($pagenum > $last){ $pagenum = $last; }
$lower_limit = ($pagenum!=1?(($pagenum - 1) * $page_limit):0);
if($cnt!=0){
    //$productids=$prod->getReview($,$lower_limit,$page_limit,$ordering,$finalprodfilter,$finaltempfilter,$template);
    $reviews=$prod->getReview($prodid,$lower_limit,$page_limit);
}else{ $reviews= array(); } ?>
<div class="col-md-12 d-none"><span class="page-list-count total-request"><?=$cnt; ?> Items</span></div>
<?php for($i=0;$i<count($reviews);$i++){
$rate=$reviews[$i]['rate'];  ?>
<div class="media border-bottom pt-3 pb-3">
    <span class="cust-rev-thumb rounded-circle cc-second-back mr-3 text-center"><?=substr($reviews[$i]['u_fname'],0,1); ?></span>
    <div class="media-body">
        <div class="row m-0">
            <div class="mb-2 text-muted cc-font-size-13 mr-2">
                <span class="mr-2"><i class="fa fa-user"></i> By <?=$reviews[$i]['u_fname']; ?></span>
                <span><i class="fa fa-calendar"></i> <?=date("d M Y",strtotime($reviews[$i]['review_date']));?></span>
            </div>
            <div class="rating-group review-star-count">
                <label aria-label="0 stars" class="rating-label <?=($rate>=0.0?'rating-icon-star':''); ?>" for="rating2-0"></label>
                <label aria-label="0.5 stars" class="rating-label rating-label-half" for="rating2-05"><i class="rating-icon <?=($rate>=0.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                <label aria-label="1 star" class="rating-label" for="rating2-10"><i class="rating-icon <?=($rate>=1.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                <label aria-label="1.5 stars" class="rating-label rating-label-half" for="rating2-15"><i class="rating-icon <?=($rate>=1.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                <label aria-label="2 stars" class="rating-label" for="rating2-20"><i class="rating-icon <?=($rate>=2.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                <label aria-label="2.5 stars" class="rating-label rating-label-half" for="rating2-25"><i class="rating-icon <?=($rate>=2.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                <label aria-label="3 stars" class="rating-label" for="rating2-30"><i class="rating-icon <?=($rate>=3.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                <label aria-label="3.5 stars" class="rating-label rating-label-half" for="rating2-35"><i class="rating-icon <?=($rate>=3.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                <label aria-label="4 stars" class="rating-label" for="rating2-40"><i class="rating-icon <?=($rate>=4.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                <label aria-label="4.5 stars" class="rating-label rating-label-half" for="rating2-45"><i class="rating-icon <?=($rate>=4.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                <label aria-label="5 stars" class="rating-label" for="rating2-50"><i class="rating-icon <?=($rate==5.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
            </div>                                
        </div>
        <h6><?=$reviews[$i]['review_title']; ?></h6>                            
        <div class="text-dark"><?=$reviews[$i]['review']; ?></div>
    </div>
</div>
<? } ?>
<div class="col-md-12 prod-itm-pagination">
    <? if($cnt!=0 && $last>1){  ?>
    <ul class="pagination mt-4 pt-2 justify-content-center text-center">
        <?php if (($pagenum-1) > 0){ ?>
        <li class="page-item"><a href="javascript:void(0);" class="page-link border-0 mx-1 mr-3 text-primary rounded-0" onclick="displayprod('<?=SITEURL; ?>','<?=$where;  ?>', '<?=1; ?>');">Previous</a></li>
        <li class="page-item"><a href="javascript:void(0);" class="page-link p-0 mx-1 rounded-circle pgn-btn pgn-handle-btn"  onclick="displayprod('<?=SITEURL; ?>','<?=$where; ?>', '<?=$pagenum-1; ?>');"><i class="fa fa-angle-left"></i></a></li>
        <?php }
        if($pagenum!=1) $prev = $pagenum-1;
        else $prev = $pagenum;
        if($pagenum!=$last)$next = $pagenum+1;
        else $next = $last;
        //Show page links
        for($i=$prev; $i<=$next; $i++){
        if ($i == $pagenum){ ?>
        <li class="page-item <?=($pagenum==$i?'active':''); ?>"><a href="javascript:void(0);" class="current page-link p-0 mx-1 rounded-circle pgn-btn"><?=$i ?></a></li>
        <?php } else{ ?>
        <li class="page-item <?=($pagenum==$i?'active':''); ?>"><a href="javascript:void(0);" class="page-link p-0 mx-1 rounded-circle pgn-btn" onclick="displayprod('<?=SITEURL; ?>','<?=addslashes($where);  ?>', '<?=$i; ?>');" ><?=$i ?></a></li>
        <?php }}
        if(($pagenum+1) <= $last){ ?>
        <li class="page-item"><a href="javascript:void(0);" class="page-link p-0 mx-1 rounded-circle pgn-btn pgn-handle-btn" onclick="displayprod('<?=SITEURL; ?>','<?= addslashes($where);  ?>', '<?=$pagenum+1; ?>');" class="links"><i class="fa fa-angle-right"></i></a></li>
        <?php } if(($pagenum) != $last){ ?>
        <li class="page-item"><a href="javascript:void(0);" class="page-link border-0 mx-1 ml-3 text-primary rounded-0" onclick="displayprod('<?=SITEURL; ?>','<?= addslashes($where);  ?>', '<?=$last; ?>');" class="links" >Next</a></li>
        <?php } ?>
    </ul>
    <? } ?>
</div>
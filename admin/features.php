<? include("../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Features</title>
    <? include "commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/backend/lightgallery.min.css">
</head>
<body class="is-collapsed">
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php';?>
    <div class="main-panel">
        <div class="dashbody fea-scroll-body pb-0">
            <div class="row">
                <? $data=$surun->getFeatureLog(SITEURL); ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card card-body mb-0 theme-feature-filter position-sticky">
                        <div class="back-filter py-1 d-block d-md-none cc-cursor-pointer mb-2" onclick="openfeafilter()"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Filters</div>
                        <? $categories = $data['categories']; $months=$data['months']; 
                        if(count($categories)){ ?>
                        <div id="accordion1">
                            <div class="filter-coll-list">
                                <div class="filter-head"><a class="card-link d-block py-2 border-bottom text-uppercase position-relative" data-toggle="collapse" href="#collapse0">Category</a></div>
                                <div id="collapse0" class="collapse show" data-parent="#accordion1">
                                    <div class="card-body pl-0 pb-1 pt-3 pr-0">
                                        <div class="filter-cat-body mb-3">
                                            <? for($i=0;$i<count($categories);$i++){?>
                                              <div class="custom-control custom-checkbox mb-2 cc-cursor-pointer"> 
                                                <input type="checkbox" name="sort0" class="custom-control-input sort0" id="category<?=$i; ?>" value="<?=$categories[$i]; ?>" onclick="filterdata()">
                                                <label class="custom-control-label cc-cursor-pointer" for="category<?=$i; ?>"><?=$categories[$i]; ?> (<?=$surun->getFeatureCount(SITEURL,'Category',$categories[$i]); ?>) <?=($surun->getFilterNewFeatureCount(SITEURL,'Category',$categories[$i])?'<span class="badge badge-danger">New</span>':''); ?></label>
                                              </div>
                                            <?} ?>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? } if(count($months)){ ?>
                        <div id="accordion2">
                            <div class="filter-coll-list">
                                <div class="filter-head"><a class="card-link d-block py-2 border-bottom text-uppercase position-relative" data-toggle="collapse" href="#collapse1">Published In</a></div>
                                <div id="collapse1" class="collapse show" data-parent="#accordion2">
                                    <div class="card-body pl-0 pb-1 pt-3 pr-0">
                                        <div class="filter-cat-body mb-3">
                                            <? for($i=0;$i<count($months);$i++){?>
                                              <div class="custom-control custom-checkbox mb-2 cc-cursor-pointer">
                                                <input type="checkbox" name="sort1" class="custom-control-input sort1" id="month<?=$i; ?>" value="<?=$months[$i]; ?>"  onclick="filterdata()">
                                                <label class="custom-control-label cc-cursor-pointer" for="month<?=$i; ?>"><?=$months[$i]; ?> (<?=$surun->getFeatureCount(SITEURL,'Month',$months[$i]); ?>) <?=($surun->getFilterNewFeatureCount(SITEURL,'Month',$months[$i])?'<span class="badge badge-danger">New</span>':''); ?></label>
                                              </div>
                                            <?} ?>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? } ?>
                    </div>
                </div>
                <div class="col-md-8 col-lg-9 pl-md-0">
                    <h2 class="h5 mb-3 cc-font-weight-5">Features List</h2>
                    <div class="content">
                        <? if(count($data['content'])){
                        for($i=0;$i<count($data['content']);$i++){
                        $r=$data['content'][$i]; ?>
                        <div class="card">
                            <? if($r['isnew']==1){?><div class="ribbon ribbon-top-left"><span class="bg-primary">New</span></div><? } ?>
                            <div class="card-body">
                                <div class="row">
                                    <? $imgs = $r['images']; 
                                    if(count($imgs)){ ?>
                                    <div class="col-12 col-lg-5">
                                        <div class="d-md-none mb-3">
                                            <div class="d-sm-flex"><span class="theme-cat bg-primary text-white rounded px-2 mb-2 d-inline-block mr-1">Category : <?=$r['category'] ?></span> <? if($r['isnew']==1){?><span class='bg-danger px-2 rounded mb-2 text-white'>New</span><? } ?> <div class="mb-1 mb-md-0 ml-auto">Entry Date: <?=$r['addedOn']; ?></div></div>
                                            <h5 class="cc-font-weight-5 mb-1"><?=$r['name']; ?></h5>
                                        </div>
                                        <div id="demo-<? echo $i; ?>" class="carousel slide newthemeslider mb-3 mb-lg-0" data-ride="carousel">
                                            <div class="carousel-inner lightgallery">
                                                <? for($im=0;$im<count($imgs);$im++){
                                                ?><div class="carousel-item fealist-img-thumb cc-cursor-pointer <? if($im == 0){ echo 'active'; } ?>" data-src="<?=($imgs[$im]['main']); ?>"><img src="<?=($imgs[$im]['thumb']?$imgs[$im]['thumb']:$imgs[$im]['main']); ?>" class="w-100 img-fluid img-thumbnail" alt="New Feature Thumb"></div><? } ?>
                                            </div>
                                            <a class="carousel-control-prev" href="#demo-<? echo $i; ?>" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                                            <a class="carousel-control-next" href="#demo-<? echo $i; ?>" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                                        </div>
                                    </div>
                                    <? } ?>
                                    <div class="col-12 <?=(count($imgs)?"col-lg-7":"col-lg-12") ?> ecom-theme-descr">
                                        <div class="d-none d-md-block">
                                            <div class="d-sm-flex"><span class="theme-cat bg-primary text-white rounded px-2 mb-2 d-inline-block mr-1">Category : <?=$r['category'] ?></span> <? if($r['isnew']==1){?><span class='bg-danger px-2 rounded mb-2 text-white'>New</span><? } ?> <div class="mb-1 mb-md-0 ml-auto">Entry Date: <?=$r['addedOn']; ?></div></div>
                                            <h5 class="cc-font-weight-5 mb-1"><?=$r['name']; ?></h5>
                                        </div>
                                        <div><?=$r['description']; ?></div>
                                        <a href="https://www.surun.in/merchant" class="btn btn-outline-primary btn-sm d-inline-block mt-2" target="_blank">Get Feature</a>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <? } } else{  ?>
                        <div class="card"><div class="card-body">Features Not Found </div></div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
        <? include "footer.php"; ?>
    </div>
</div>
<div class="featfilter p-3 text-center bg-primary text-white position-fixed w-100 cc-cursor-pointer cc-font-weight-6 d-block d-md-none" onclick="openfeafilter()"><i class="fa fa-filter"></i> FILTER</div>
<script src="<?php echo SITEURL; ?>/js/backend/utils.js"></script>
<script src="<?php echo SITEURL; ?>/js/backend/lightgallery-all.min.js"></script>
<script>
function filterdata(){
    var cat=[]; var months=[];
    $(".sort0:checked").each(function(){ cat.push($(this).val()); });
    $(".sort1:checked").each(function(){ months.push($(this).val()); });
    catstr = cat.join(",");
    monthstr = months.join(",");
    info = {categories:catstr,months:monthstr}
    $.ajax({
        type:"POST", url:"features-ajax.php", data:info,
        success:function(response){ $(".content").html(response); $('.lightgallery').lightGallery({ getCaptionFromTitleOrAlt: false, thumbnail: false, rotate: false, share: false, fullScreen: false, autoplayControls: false }); }
    })
}
$('.lightgallery').lightGallery({ getCaptionFromTitleOrAlt: false, thumbnail: false, rotate: false, share: false, fullScreen: false, autoplayControls: false });
function checkPosition(){
    if(window.matchMedia('(max-width: 767px)').matches){ $("body").removeClass("is-collapsed ecomfeabody"); } else { $("body").addClass("ecomfeabody is-collapsed"); }
}
checkPosition();
function openfeafilter(){ $(".theme-feature-filter").slideToggle(); }
</script>
</body>
</html>
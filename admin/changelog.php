<? include("../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Change Log</title>
    <? include "commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/backend/lightgallery.min.css">
</head>
<body class="is-collapsed">
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php';?>
    <div class="main-panel">
        <div class="dashbody fea-scroll-body">
            <div class="row">
                <? $data = $surun->getChangeLog(SITEURL); ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card card-body mb-0 theme-feature-filter position-sticky">
                        <div class="back-filter py-1 d-block d-md-none cc-cursor-pointer mb-2" onclick="openfeafilter()"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Filters</div>
                        <? $categories = $data['categories']; $months=$data['months'];  $types=$data['changeType']; 
                        if(count($categories)){?>
                        <div id="accordion1">
                            <div class="filter-coll-list">
                                <div class="filter-head"><a class="card-link d-block py-2 border-bottom text-uppercase position-relative" data-toggle="collapse" href="#collapse0">Category</a></div>
                                <div id="collapse0" class="collapse show" data-parent="#accordion1">
                                    <div class="card-body pl-0 pb-1 pt-3 pr-0">
                                        <div class="filter-cat-body mb-3">
                                            <? for($i=0;$i<count($categories);$i++){?>
                                            <div class="custom-control custom-checkbox mb-2 cc-cursor-pointer"><input type="checkbox" name="sort0" class="custom-control-input sort0" id="category<?=$i; ?>" value="<?=$categories[$i]; ?>" onclick="filterdata()"><label class="custom-control-label cc-cursor-pointer" for="category<?=$i; ?>"><?=$categories[$i]; ?> (<?=$surun->getChangeLogCount(SITEURL,'Category',$categories[$i]); ?>) <?=($surun->getFilterNewChangeCount(SITEURL,'Category',$categories[$i])?'<span class="badge badge-danger">New</span>':''); ?></label></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? } if(count($types)){ ?>
                        <div id="accordion2">
                            <div class="filter-coll-list">
                                <div class="filter-head"><a class="card-link d-block py-2 border-bottom text-uppercase position-relative" data-toggle="collapse" href="#collapse1">Change Type</a></div>
                                <div id="collapse1" class="collapse show" data-parent="#accordion2">
                                    <div class="card-body pl-0 pb-1 pt-3 pr-0">
                                        <div class="filter-cat-body mb-3">
                                            <? for($i=0;$i<count($types);$i++){?>
                                            <div class="custom-control custom-checkbox mb-2 cc-cursor-pointer"><input type="checkbox" name="sort2" class="custom-control-input sort2" id="types<?=$i; ?>" value="<?=$types[$i]; ?>" onclick="filterdata()"><label class="custom-control-label cc-cursor-pointer" for="types<?=$i; ?>"><?=$types[$i]; ?> (<?=$surun->getChangeLogCount(SITEURL,'ChangeType',$types[$i]); ?>) <?=($surun->getFilterNewChangeCount(SITEURL,'ChangeType',$types[$i])?'<span class="badge badge-danger">New</span>':''); ?></label></div>
                                            <?} ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? } if(count($months)){ ?>
                        <div id="accordion">
                            <div class="filter-coll-list">
                                <div class="filter-head"><a class="card-link d-block py-2 border-bottom text-uppercase position-relative" data-toggle="collapse" href="#collapse2">Published In</a></div>
                                <div id="collapse2" class="collapse show" data-parent="#accordion">
                                    <div class="card-body pl-0 pb-1 pt-3 pr-0">
                                        <div class="filter-cat-body mb-3">
                                            <? for($i=0;$i<count($months);$i++){?>
                                            <div class="custom-control custom-checkbox mb-2 cc-cursor-pointer"><input type="checkbox" name="sort1" class="custom-control-input sort1" id="month<?=$i; ?>" value="<?=$months[$i]; ?>"  onclick="filterdata()"><label class="custom-control-label cc-cursor-pointer" for="month<?=$i; ?>"><?=$months[$i]; ?> (<?=$surun->getChangeLogCount(SITEURL,'Month',$months[$i]); ?>) <?=($surun->getFilterNewChangeCount(SITEURL,'Month',$months[$i])?'<span class="badge badge-danger">New</span>':''); ?></label></div>
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
                    <h2 class="h5 mb-3 cc-font-weight-5">Change Log</h2>
                    <div class="content">
                        <? if(count($data['content'])){
                        for($i=0;$i<count($data['content']);$i++){
                        $r = $data['content'][$i]; ?>
                        <div class="card">
                            <? if($r['isnew']==1){?><div class="ribbon ribbon-top-left"><span class="bg-primary">New</span></div><? } ?>
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-12 ecom-theme-descr">
                                        <div class="logs">
                                            <div class="row d-flex justify-content-between border-bottom pb-1 mb-2"><div class="col-12 <? if($r['isnew']==1){ echo 'pl-5'; }; ?>"><h6 class="cc-font-weight-5 mb-1 d-inline-block"><span class="text-success cc-font-weight-6">SIS<?=$r['changeId']; ?></span> : <?=$r['name']; ?> (Category : <span class="category"><?=$r['category']; ?></span> | <?=$r['changeType']; ?> Change) <? if($r['isnew']==1){?><span class='badge badge-danger'>New</span><? } ?></h6> <div>(<?=$r['addedOn']; ?>)</div></div></div>
                                            <div><?=$r['description']; ?></div>
                                        </div>
                                        <? $imgs = $r['images']; 
                                        if(count($imgs)){ ?>
                                        <div class="chg-log-thumb">
                                            <div class="carousel-inner lightgallery d-none">
                                                <? for($im=0;$im<count($imgs);$im++){
                                                ?><div class="carousel-item fealist-img-thumb cc-cursor-pointer <? if($im == 0){ echo 'active'; } ?>" data-src="<?=($imgs[$im]['thumb']?$imgs[$im]['thumb']:$imgs[$im]['main']); ?>"><img src="<?=($imgs[$im]['thumb']?$imgs[$im]['thumb']:$imgs[$im]['main']); ?>" class="w-100 img-fluid img-thumbnail" alt="New Feature Thumb"></div><? } ?>
                                            </div>
                                            <span class="text-primary cc-cursor-pointer openchglogthumb">Support Images <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                                        </div>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? } } else{ ?>
                        <div class="col-12"><div class="card"><div class="card-body">Change Log Not Found</div></div></div>
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
    var cat=[]; var months=[]; var types=[];
    $(".sort0:checked").each(function(){ cat.push($(this).val()); });
    $(".sort1:checked").each(function(){ months.push($(this).val()); });
    $(".sort2:checked").each(function(){ types.push($(this).val()); });
    catstr = cat.join(",");
    monthstr = months.join(",");
    typesstr = types.join(",");
    info = { categories:catstr, months:monthstr, types:typesstr }
    $.ajax({
        type:"POST", url:"changelog-ajax.php", data:info,
        success:function(response){ 
            $(".content").html(response);
            $('.lightgallery').lightGallery({ getCaptionFromTitleOrAlt: false, thumbnail: false, rotate: false, share: false, fullScreen: false, autoplayControls: false });
            $(".openchglogthumb").click(function(){
                $(this).parent().find(".carousel-item:first-child").trigger("click");
            });
        }
    })
}
$('.lightgallery').lightGallery({ getCaptionFromTitleOrAlt: false, thumbnail: false, rotate: false, share: false, fullScreen: false, autoplayControls: false });
$(".openchglogthumb").click(function(){
    $(this).parent().find(".carousel-item:first-child").trigger("click");
});
function checkPosition(){
    if(window.matchMedia('(max-width: 767px)').matches){ $("body").removeClass("is-collapsed ecomfeabody"); } else { $("body").addClass("ecomfeabody is-collapsed"); }
}
checkPosition();
function openfeafilter(){ $(".theme-feature-filter").slideToggle(); }
</script>
</body>
</html>
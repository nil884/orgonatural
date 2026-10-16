<?php include "../includes/configuration.php";
include_once "../classes/surunapi.php";
$categories = $_POST['categories']; $months = $_POST['months']; $types = $_POST['types']; $srn = new surun();
$data = $srn->getChangeLog(SITEURL,$categories,$months,$types);
if(count($data['content'])){
    for($i=0;$i<count($data['content']);$i++){
    $r=$data['content'][$i]; ?>
    <div class="card">
        <? if($r['isnew']==1){?><div class="ribbon ribbon-top-left"><span class="bg-primary">New</span></div><? } ?>
        <div class="card-body pt-2">
            <div class="row">
                <div class="col-12 ecom-theme-descr">
                    <div class="logs">
                        <div class="row d-flex justify-content-between border-bottom pb-1 mb-2">
                            <div class="col-12 <? if($r['isnew']==1){ echo 'pl-5'; }; ?>"><h6 class="cc-font-weight-5 mb-1 d-inline-block"><span class="text-success cc-font-weight-6">SIS<?=$r['changeId']; ?></span> : <?=$r['name']; ?> (Category : <span class="category"><?=$r['category']; ?></span> | <?=$r['changeType']; ?> Change) <? if($r['isnew']==1){?><span class='badge badge-danger '>New</span><? } ?></h6> <div>(<?=$r['addedOn']; ?>)</div></div>
                        </div>
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
<div class="col-12"><div class="card"><div class="card-body">Change Log Not Found </div></div></div>
<? } ?>

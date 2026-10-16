<?php include "../includes/configuration.php";
include_once "../classes/surunapi.php";
$categories = $_POST['categories']; $months = $_POST['months']; $srn = new surun();
$data=$srn->getFeatureLog(SITEURL,$categories,$months);
if(count($data['content'])){
    for($i=0;$i<count($data['content']);$i++){
    $r = $data['content'][$i]; ?>
    <div class="card">
        <? if($r['isnew']==1){ ?><div class="ribbon ribbon-top-left"><span class="bg-primary">New</span></div><? } ?>
        <div class="card-body">
            <div class="row">
                <? $imgs = $r['images']; 
                if(count($imgs)){ ?>
                <div class="col-12 col-lg-5">
                    <div class="d-md-none mb-3">
                        <div class="d-sm-flex"><span class="theme-cat bg-primary text-white rounded px-2 mb-2 d-inline-block mr-1">Category : <?=$r['category'] ?></span> <? if($r['isnew']==1){ ?><span class='bg-danger px-2 rounded mb-2 text-white'>New</span><? } ?> <div class="mb-1 mb-md-0 ml-auto">Entry Date: <?=$r['addedOn']; ?></div></div>
                        <h5 class="cc-font-weight-5 mb-1"><?=$r['name']; ?></h5>
                    </div>
                    <div id="demo-<? echo $i; ?>" class="carousel slide newthemeslider mb-3 mb-lg-0" data-ride="carousel">
                        <div class="carousel-inner lightgallery">
                        <? for($im=0;$im<count($imgs);$im++){
                        ?><div class="carousel-item fealist-img-thumb cc-cursor-pointer <? if($im == 0){ echo 'active'; } ?>" data-src="<?=($imgs[$im]['thumb']?$imgs[$im]['thumb']:$imgs[$im]['main']); ?>"><img src="<?=($imgs[$im]['thumb']?$imgs[$im]['thumb']:$imgs[$im]['main']); ?>" class="w-100 img-fluid img-thumbnail" alt="New Feature Thumb"></div><? } ?>
                        </div>
                        <a class="carousel-control-prev" href="#demo-<? echo $i; ?>" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                        <a class="carousel-control-next" href="#demo-<? echo $i; ?>" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                    </div>
                </div>
                <? } ?>
                <div class="<?=(count($imgs)?"col-lg-7":"col-lg-12") ?> ecom-theme-descr">
                    <div class="d-none d-md-block">
                        <div class="d-sm-flex"><span class="theme-cat bg-primary text-white rounded px-2 mb-2 d-inline-block mr-1">Category : <?=$r['category'] ?></span> <? if($r['isnew']==1){ ?><span class='bg-danger px-2 rounded mb-2 text-white'>New</span><? } ?> <div class="mb-1 mb-md-0 ml-auto">Entry Date: <?=$r['addedOn']; ?></div></div>
                        <h5 class="cc-font-weight-5 mb-1"><?=$r['name']; ?></h5>
                    </div>
                    <div><?=$r['description']; ?></div>
                    <a href="https://www.surun.in/merchant" class="btn btn-outline-primary btn-sm d-inline-block mt-2" target="_blank">Get Feature</a>
                </div>
            </div> 
        </div>
    </div>
    <? } } else{ ?>
    <div class="card"><div class="card-body">Features Not Found </div></div>
<? } ?>
<?php include("includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Offers : <?=SITE_TITLE; ?></title>
    <meta name="description" content="<?=METADESCRIPTION; ?>">
    <meta name="keywords" content="<?=METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/jquery-ui.css">
</head>
<body>
<div class="main-wrap">
    <?php include "menu.php" ?>
    <div class="container pt-4 pb-4">        
        <div class="row">
            <?php $offcnts = selectQuery( OFFER, "offer_id,offer_name,img,offer_link", "isActive='1' order by priority ");
            for($i = 0; $i < count( $offcnts ); $i++){
            $offerpath = getimgconfigpaths('offer'); ?>
            <div class="col-md-4 mb-4">
                <div class="offer-col position-relative">
                    <a href="<? if($offcnts[$i]['offer_link'] != ""){ echo $offcnts[$i]['offer_link'];} else{ echo "#"; } ?>"  target="_blank" hreflang="en">  
                        <img class="img-fluid" src="<?php echo SITEURL."/".$offerpath[0]['imgs_location'] ?>/<?php if ( $offcnts[$i]['img'] != "" ){echo $offcnts[$i]['img'];} else{echo "No_image_available.png";}?>" alt="<?=$offcnts[$i]['offer_name'];?>">
                        <!-- <h4>
                        <?php if(strlen( $offcnts[$i]['offer_name'] ) > 50){
                            $arr = str_split($offcnts[$i]['offer_name']);
                            $str = "";
                            for( $j = 0; $j < 55; $j++){$str = $str . "" . $arr[$j];}
                            echo $str . "...";
                        } else {echo $offcnts[$i]['offer_name'];} ?>
                        </h4>-->
                    </a> 
                    <button onclick="view('<?php echo $offcnts[$i]['offer_id']; ?>')" class="btn btn-primary ofr-next-button rounded position-absolute"><i class="fa fa-eye" aria-hidden="true"></i></button>
                </div>      
            </div>
           <?php } ?>
        </div>
    </div>
</div>
<div class="modal fade offer-details-modal" id="offermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-4"><button type="button" class="close bg-dark text-white rounded-circle" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="viewoffer"></div></div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
function view(imgid){
    $.ajax({
        type:"POST",
        url:"ajax/common_ajax.php",
        data:{img_id:imgid,action:"getoffer_details"},
        success:function(response){
            $("#myModalLabel").html("Offer");
            $(".viewoffer").html(response);
            $("#offermodal").modal("show");
        }
    });
}
</script>   
</body>
</html>
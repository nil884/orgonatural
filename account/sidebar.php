<?php if( $_SESSION['reguser']== ""){ ?> <script> var x ='<?php echo SITEURL; ?>'; window.location.assign(x);</script> <?php } ?>
<div class="col-md-3 user-log-nav d-none d-lg-block border-right px-0">
    <div class="user-acc-aside">
        <?php $getconfingdetails = json_decode(getimgconfig('buyer profile'));
        $img_location = $getconfingdetails[0]->imgs_location; // Access Object data ?>
        <div class="media px-3 py-3">
            <div id="imgdata" class="user-account-pic rounded-circle mr-3"><img src="<?php echo SITEURL ?>/<?php if($getbuyer_details[0]['profile_pic']!= "" ){ echo $img_location."/".$getbuyer_details[0]['profile_pic']; } else{ echo "img/projectimage/picture.jpg"; } ?>" alt="Image" class="img-fluid"></div> 
            <div class="media-body align-self-center">
                <h6 class="cc-fw-5 text-capitalize"><?= $logUsername; ?>&nbsp;</h6>
                <label class="btn-upload btn btn-primary btn-sm mb-2" for="inputImage" title="Upload image file"><input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onchange="validateimg(this)"><span class="docs-tooltip" data-toggle="tooltip"><i class="fa fa-pencil"></i> <span class="d-none d-xl-inline-block">Edit</span></span></label>
                <?php if($getbuyer_details[0]['profile_pic']!= "" ){ ?> <button type="button" onclick="del_Profile_img('<?php echo $_SESSION['reguser'] ?>','<?php echo $getbuyer_details[0]['profile_pic']; ?>')" class="btn btn-danger btn-sm mb-2"><i class="fa fa-trash" aria-hidden="true"></i> <span class="d-none d-xl-inline-block">Remove</span></button> <?php } ?>
            </div>
        </div> 
        <div class="col-md-12"><div class="progress cc-display-none mt-2"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div><div class="alert cc-display-none" role="alert"></div></div>
        <div class="modal fade account-user-poc" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title" id="modalLabel">Crop the image</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                    <div class="modal-body"><div class="img-container"><img id="image" src="#" alt="uploadimg"></div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-primary" id="crop">Crop</button></div>
                </div>
            </div>
        </div>
        <div class="d-none"><img class="rounded" id="avatar" src="#" alt="uploadimg"><input type="file" class="sr-only" id="input" name="image" accept="image/*"></div>
        <div class="accnt-sideba-nav">
        <ul class="list-unstyled mb-0">
                <li><a href="<?php echo SITEURL; ?>/account"><i class="fa fa-user" aria-hidden="true"></i> My Profile</a> </li><li><a href="<?php echo SITEURL; ?>/account/bankdetails"><i class="fa fa-address-book" aria-hidden="true"></i> Bank Details</a></li><li><a href="<?php echo SITEURL; ?>/account/taxation"><i class="fa fa-percent" aria-hidden="true"></i>TAX Details</a></li><li><a href="<?php echo SITEURL; ?>/account/myreviews"><i class="fa fa-comment" aria-hidden="true"></i> Product Review</a></li>
                <li><a href="<?php echo SITEURL; ?>/account/myorders"><i class="fa fa-file-text" aria-hidden="true"></i> Recent Order</a></li><li><a href="<?php echo SITEURL; ?>/account/myaddresses"><i class="fa fa-map-marker" aria-hidden="true"></i>Order Address</a></li>
            </ul>
        </div>
    </div>
</div>
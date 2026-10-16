<?php include("../../includes/configuration.php");
    $css = cssparse('../../css/dynamic-style.css');
?>
<!doctype html>
<html lang='en'>
<head>
    <title>Config - Customize Option</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Styling Options</h5></div>
                </div>
                <div class="card-body">
                        <div id="mainmenu">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Header</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6">Header Background Color</label>
                                                <div class="col-sm-6 theme">
                                                    <input type="text" class="jscolor {styleElement:'sidebar'} form-control"  >
                                                    
                                            
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6">Link Color</label>
                                                <div class="col-sm-6 theme">
                                                    <input name="headlinkcolor" class="custHeaderLinkColor jscolor form-control" type="text" value="<?=$css["header ul li a"]["color"]; ?>">
                                                </div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6">Header link Hover</label>
                                                <div class="col-sm-6 theme">
                                                    <input name="headlinkcolorhover" class="custHeadLinkColorhover jscolor form-control" type="text" value="<?=$css["header ul li a:hover"]["color"]; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right" onclick="savebodydata()">Submit</button>
                            </div>
                        </div> 
                    
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/colorpicker/jscolor.js"></script>

</body>
</html>
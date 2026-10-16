<?php include("../../includes/configuration.php");
$css = cssparse('../../css/dynamic-style.css'); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Customize Option</title>
    <?php include('../commoncss.php') ?>
    <style>@font-face{font-family:'Adobe Blank';font-style:normal;font-weight:400;src:local('Adobe Blank'),local('AdobeBlank-Regular'),url(//fonts.gstatic.com/s/adobeblank/v5/ieV62YNKKGqPJteRQT8EQmob.woff2)format('woff2');}@font-face{font-family:'Product Sans';font-style:normal;font-weight:400;src:local('Product Sans'),local('ProductSans-Regular'),url(//fonts.gstatic.com/s/productsans/v9/pxiDypQkot1TnFhsFMOfGShVGdeOcEg.woff2)format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF;}@font-face{font-family:'Product Sans';font-style:normal;font-weight:400;src:local('Product Sans'),local('ProductSans-Regular'),url(//fonts.gstatic.com/s/productsans/v9/pxiDypQkot1TnFhsFMOfGShVF9eO.woff2)format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;src:local('Roboto Regular'),local('Roboto-Regular'),url(//fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu72xKOzY.woff2)format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F;}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;src:local('Roboto Regular'),local('Roboto-Regular'),url(//fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu5mxKOzY.woff2)format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;src:local('Roboto Regular'),local('Roboto-Regular'),url(//fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7mxKOzY.woff2)format('woff2');unicode-range:U+1F00-1FFF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;src:local('Roboto Regular'),local('Roboto-Regular'),url(//fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4WxKOzY.woff2)format('woff2');unicode-range:U+0370-03FF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;src:local('Roboto Regular'),local('Roboto-Regular'),url(//fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7WxKOzY.woff2)format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+1EA0-1EF9,U+20AB;}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;src:local('Roboto Regular'),local('Roboto-Regular'),url(//fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7GxKOzY.woff2)format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;src:local('Roboto Regular'),local('Roboto-Regular'),url(//fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4mxK.woff2)format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;src:local('Roboto Medium'),local('Roboto-Medium'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmEU9fCRc4EsA.woff2)format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F;}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;src:local('Roboto Medium'),local('Roboto-Medium'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmEU9fABc4EsA.woff2)format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;src:local('Roboto Medium'),local('Roboto-Medium'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmEU9fCBc4EsA.woff2)format('woff2');unicode-range:U+1F00-1FFF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;src:local('Roboto Medium'),local('Roboto-Medium'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmEU9fBxc4EsA.woff2)format('woff2');unicode-range:U+0370-03FF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;src:local('Roboto Medium'),local('Roboto-Medium'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmEU9fCxc4EsA.woff2)format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+1EA0-1EF9,U+20AB;}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;src:local('Roboto Medium'),local('Roboto-Medium'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmEU9fChc4EsA.woff2)format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;src:local('Roboto Medium'),local('Roboto-Medium'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmEU9fBBc4.woff2)format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;src:local('Roboto Bold'),local('Roboto-Bold'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfCRc4EsA.woff2)format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F;}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;src:local('Roboto Bold'),local('Roboto-Bold'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfABc4EsA.woff2)format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;src:local('Roboto Bold'),local('Roboto-Bold'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfCBc4EsA.woff2)format('woff2');unicode-range:U+1F00-1FFF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;src:local('Roboto Bold'),local('Roboto-Bold'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBxc4EsA.woff2)format('woff2');unicode-range:U+0370-03FF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;src:local('Roboto Bold'),local('Roboto-Bold'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfCxc4EsA.woff2)format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+1EA0-1EF9,U+20AB;}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;src:local('Roboto Bold'),local('Roboto-Bold'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfChc4EsA.woff2)format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF;}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;src:local('Roboto Bold'),local('Roboto-Bold'),url(//fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBBc4.woff2)format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:400;src:local('Roboto Mono Regular'),local('RobotoMono-Regular'),url(//fonts.gstatic.com/s/robotomono/v6/L0x5DF4xlVMF-BfR8bXMIjhGq3-OXg.woff2)format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:400;src:local('Roboto Mono Regular'),local('RobotoMono-Regular'),url(//fonts.gstatic.com/s/robotomono/v6/L0x5DF4xlVMF-BfR8bXMIjhPq3-OXg.woff2)format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:400;src:local('Roboto Mono Regular'),local('RobotoMono-Regular'),url(//fonts.gstatic.com/s/robotomono/v6/L0x5DF4xlVMF-BfR8bXMIjhHq3-OXg.woff2)format('woff2');unicode-range:U+1F00-1FFF;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:400;src:local('Roboto Mono Regular'),local('RobotoMono-Regular'),url(//fonts.gstatic.com/s/robotomono/v6/L0x5DF4xlVMF-BfR8bXMIjhIq3-OXg.woff2)format('woff2');unicode-range:U+0370-03FF;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:400;src:local('Roboto Mono Regular'),local('RobotoMono-Regular'),url(//fonts.gstatic.com/s/robotomono/v6/L0x5DF4xlVMF-BfR8bXMIjhEq3-OXg.woff2)format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+1EA0-1EF9,U+20AB;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:400;src:local('Roboto Mono Regular'),local('RobotoMono-Regular'),url(//fonts.gstatic.com/s/robotomono/v6/L0x5DF4xlVMF-BfR8bXMIjhFq3-OXg.woff2)format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:400;src:local('Roboto Mono Regular'),local('RobotoMono-Regular'),url(//fonts.gstatic.com/s/robotomono/v6/L0x5DF4xlVMF-BfR8bXMIjhLq38.woff2)format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:700;src:local('Roboto Mono Bold'),local('RobotoMono-Bold'),url(//fonts.gstatic.com/s/robotomono/v6/L0xkDF4xlVMF-BfR8bXMIjDwjmq8f7-7Ag.woff2)format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:700;src:local('Roboto Mono Bold'),local('RobotoMono-Bold'),url(//fonts.gstatic.com/s/robotomono/v6/L0xkDF4xlVMF-BfR8bXMIjDwjmq1f7-7Ag.woff2)format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:700;src:local('Roboto Mono Bold'),local('RobotoMono-Bold'),url(//fonts.gstatic.com/s/robotomono/v6/L0xkDF4xlVMF-BfR8bXMIjDwjmq9f7-7Ag.woff2)format('woff2');unicode-range:U+1F00-1FFF;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:700;src:local('Roboto Mono Bold'),local('RobotoMono-Bold'),url(//fonts.gstatic.com/s/robotomono/v6/L0xkDF4xlVMF-BfR8bXMIjDwjmqyf7-7Ag.woff2)format('woff2');unicode-range:U+0370-03FF;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:700;src:local('Roboto Mono Bold'),local('RobotoMono-Bold'),url(//fonts.gstatic.com/s/robotomono/v6/L0xkDF4xlVMF-BfR8bXMIjDwjmq-f7-7Ag.woff2)format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+1EA0-1EF9,U+20AB;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:700;src:local('Roboto Mono Bold'),local('RobotoMono-Bold'),url(//fonts.gstatic.com/s/robotomono/v6/L0xkDF4xlVMF-BfR8bXMIjDwjmq_f7-7Ag.woff2)format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF;}@font-face{font-family:'Roboto Mono';font-style:normal;font-weight:700;src:local('Roboto Mono Bold'),local('RobotoMono-Bold'),url(//fonts.gstatic.com/s/robotomono/v6/L0xkDF4xlVMF-BfR8bXMIjDwjmqxf78.woff2)format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local('Roboto-Thin'),url(//fonts.gstatic.com/l/font?kit=KFOkCnqEu92Fr1MmgWxKMTsRBPHDp7t6Tk2DOWA&skey=5473b731ec7fc9c1&v=v18)format('woff2');}</style>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Styling Options</h2></div></div>
                <div class="card-body pb-0">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#body">Body</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#mainmenu">Header</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#mainnav">Navigation</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#footer">Footer</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#invoice">Invoice</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#alert">Alert</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#notification">Notification</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#soclink">Social Links</a></li>
                    </ul>
                    <div class="alert alert-info">Hover = Mouse Over</div>
                    <div class="tab-content">
                        <div id="body" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12 form-horizontal">
                                    <?php
                                  /* echo "<pre>"; print_r($css); echo "</pre>"; */
                                    $fonrarr=getwebfont(); $familyarr= $fonrarr['items'];?>
                                    <div class="border-bottom mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Body Font Family</label>
                                                    <div class="col-sm-6 col-md-12 col-lg-6">
                                                        <select class="form-control bodyfont" onchange="getfont()">
                                                            <option value="">Select Font</option>
                                                            <? $currentfont=explode('"',$css["body"]["font-family"]);
                                                            $fontname=(count($currentfont)>1?$currentfont[1]:$currentfont[0]);
                                                            for($i=0;$i<count($familyarr);$i++){
                                                            $fontcategory= $familyarr[$i]['category'];
                                                            $variantsarr=$familyarr[$i]['variants'];
                                                            $filteredvars=array();
                                                            foreach($variantsarr as $value){
                                                            $value=str_replace("italic","i",$value);
                                                            if($value==="i"){ $value="400i";
                                                            }else if($value==="regular"){ $value="400";}
                                                            else{$value=$value;}
                                                            array_push($filteredvars,$value); } $variants=json_encode($filteredvars,JSON_UNESCAPED_SLASHES);$subsets=json_encode($familyarr[$i]['subsets'],JSON_UNESCAPED_SLASHES);
                                                            ?><option <?=($fontname==$familyarr[$i]['family']?"selected":"")?> data-variant='<?=$variants; ?>' data-subset='<?=$subsets; ?>'
                                                            style='font-family:"<?=$familyarr[$i]['family'].'"'.($fontcategory!=""?" ,".$fontcategory:""); ?>;'
                                                            value='"<?=$familyarr[$i]['family'].'"'.($fontcategory!=""?" ,".$fontcategory:""); ?>'><?=$familyarr[$i]['family'] ?></option> <?
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" class="form-control bodyfontVars">
                                                    <input type="hidden" class="form-control bodyfontSubsets">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Body Font Size</label>
                                                    <div class="col-sm-6 col-md-12 col-lg-6"><input type="number" class="form-control bfontSize" min="13" max="18" value="<?=str_replace("px","",$css["body"]["font-size"]); ?>"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Body Background Color</label> 
                                                    <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="bodybgcolor" class="bodybgcolor jscolor form-control" type="text" value="<?=$css["body"]["background-color"]; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Body Primary Text Color</label> 
                                                    <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="bodyprimtext" class="bodyprimtext jscolor form-control" type="text" value="<?=$css["body"]["color"]; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Body Secondary Text Color</label>
                                                    <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="bodysecondtext" class="bodysecondtext jscolor form-control" type="text" value="<?=$css[".text-muted"]["color"]; ?>"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Primary Highlighter Text Color</label>
                                                    <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="color2" class="custPrimaryColor jscolor form-control" type="text" id="pri_color_value" value="<?=$css[".cc-primary-color"]["color"]; ?>"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Primary Headings</label>
                                                    <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alpgtitcolor" class="alpgtitcolor jscolor form-control" type="text" value="<?=$css["h1,h2,h3,h4,h5,h6"]["color"]; ?>">
                                                    <div class="small">(All pages headings : h1, h2, h3, h4, h5, h6)</div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="row border-bottom mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Primary Button</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme" id="sp-light"><input name="btncolor" class="custPriBtn jscolor form-control" type="text" value="<?=$css[".btn-primary"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Primary Button Hover</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="pribtnhover" class="custPrimaryBtnHover jscolor form-control" type="text" id="pri_btn_hover" value="<?=$css[".btn-primary"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Secondary Button</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="pribtnhover" class="secondBtn jscolor form-control" type="text" id="sec_btn_hover" value="<?=$css[".btn-secondary"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Secondary Button Hover</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="seccolor" class="secondBtnHover jscolor form-control" type="text" value="<?=$css[".btn-secondary:hover"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Home Page Title Font Size</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input type="number" class="form-control hmsecfontsize" min="13" max="40" value="<?=str_replace("px","",$css[".hm-sec-font-size"]["font-size"]); ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Home Page Title Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="hmsecfontcolor" class="hmsecfontcolor jscolor form-control" type="text" value="<?=$css[".hm-sec-text-color"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-light p-3 border-top">
                                        <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                        <fieldset class="front-sec-devide bg-white pt-0">
                                            <legend class="fro-sec-head bg-primary mb-0">Home Page Section</legend>
                                            <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/home-section-setting-thumb.webp" class="img-fluid" alt="home-section-setting-thumb">
                                        </fieldset>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div id="mainmenu" class="tab-pane fade">                            
                            <div class="row">
                                <div class="col-md-12 border-bottom mb-3">
                                    <h6 class="cc-font-weight-5">Contact Header</h6>
                                    <!-- <div data-toggle="modal" data-target="#myModal">Click Here for Help?</div> -->                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="headcontbackcolor" class="headcontbackcolor jscolor form-control" type="text" value="<?=$css[".head-contact"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="headcontlinkcolor" class="headcontlinkcolor jscolor form-control" type="text" value="<?=$css[".head-contact li a"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Hover</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="headcontlinkcolorhover" class="headcontlinkcolorhover jscolor form-control" type="text" value="<?=$css[".head-contact li a:hover"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>                            
                            <div class="row border-bottom">
                                <div class="col-md-12">
                                    <h6 class="cc-font-weight-5">Header</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="headbackcolor" class="custHeaderBack jscolor form-control" type="text" value="<?=$css["header"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="headlinkcolor" class="custHeaderLinkColor jscolor form-control" type="text" value="<?=$css["header ul li a"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Hover</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="headlinkcolorhover" class="custHeadLinkColorhover jscolor form-control" type="text" value="<?=$css["header ul li a:hover"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Secondary Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="headbadgecolr" class="headbadgecolor jscolor form-control" type="text" value="<?=$css[".head-sec-back"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row bg-light p-3">                                
                                <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                <fieldset class="front-sec-devide mb-2 bg-white pt-0">
                                    <legend class="fro-sec-head bg-primary mb-0">Contact Header</legend>
                                    <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/contact-header.webp" class="img-fluid" alt="head-setting">
                                </fieldset>
                                <fieldset class="front-sec-devide bg-white pt-0">
                                    <legend class="fro-sec-head bg-primary mb-0">Header</legend>
                                    <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/header-setting-thumb.webp" class="img-fluid" alt="head-setting">
                                </fieldset>
                            </div>
                        </div>
                        <div id="mainnav" class="tab-pane fade">                            
                            <div class="row">
                                <div class="col-md-12">                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="navbackcolor" class="custNavBack jscolor form-control" type="text" value="<?=$css["nav"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Links Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="navlinkcolor" class="custNavLinkColor jscolor form-control" type="text" value="<?=$css[".cat-nav .navbar-nav > .nav-item > a"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Hover</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="navlinkcolorhover" class="custNavLinkColorhover jscolor form-control" type="text" value="<?=$css[".cat-nav .navbar-nav > .nav-item > a:hover"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Bold</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme">
                                                    <select class="form-control nav-link-bold" onchange="navtextbold(this)">
                                                        <option><?=$css[".cat-nav .navbar-nav > .nav-item > a"]["font-weight"]; ?></option>
                                                        <option>200</option>
                                                        <option>300</option>
                                                        <option>400</option>
                                                        <option>500</option>
                                                        <option>600</option>
                                                        <option>700</option>
                                                        <option>800</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Hover Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="navlinkBackhover" class="custNavLinkBackhover jscolor form-control" type="text" value="<?=$css[".cat-nav .navbar-nav > .nav-item > a:hover"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row bg-light p-3 border-top">
                                <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                <fieldset class="front-sec-devide bg-white">
                                    <legend class="fro-sec-head bg-primary mb-0">Navigation</legend>
                                    <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/nav-setting-thumb.webp" class="img-fluid" alt="nav-setting-thumb">
                                </fieldset>
                            </div>
                        </div>
                        <div id="footer" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="footbackcolor" class="custFooterBack jscolor form-control" type="text" value="<?=$css["footer"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Heading Font Size</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6"><input type="number" class="form-control fHeadFontSize" min="16" max="20" value="<?=str_replace("px","",$css["footer .fot-col-head"]["font-size"]); ?>"></div>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Heading Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="footheadcolor" class="custFootHeadColor jscolor form-control" type="text" value="<?=$css["footer .fot-col-head"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Heading Thickness</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme">
                                                    <select class="form-control foot-sec-head" onchange="fothedweight(this)">
                                                        <option><?=$css["footer .fot-col-head"]["font-weight"]; ?></option>
                                                        <option>300</option>
                                                        <option>400</option>
                                                        <option>500</option>
                                                        <option>600</option>
                                                        <option>700</option>
                                                        <option>800</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="foottextcolor" class="custFooterTextColor jscolor form-control" type="text" value="<?=$css["footer"]["color"]; ?>"></div>
                                            </div>    
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Link Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="foottextHoverColor" class="custFooterLinkHover jscolor form-control" type="text" value="<?=$css["footer-menu li a:hover"]["color"]; ?>"></div>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="row bg-light p-3 border-top">
                                        <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                        <fieldset class="front-sec-devide bg-white pt-0">
                                            <legend class="fro-sec-head bg-primary mb-0">Footer</legend>
                                            <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/footer-setting-thumb.webp" class="img-fluid" alt="footer-setting-thumb">
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="invoice" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Body Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="invsectextcolor" class="invsectextcolor jscolor form-control" type="text" value="<?=$css[".inv-bodycolor"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Primary Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="invprtextcolor" class="invprtextcolor jscolor form-control" type="text" value="<?=$css[".inv-primary-color"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Table Heading Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="invtabhead" class="invtabhead jscolor form-control" type="text" value="<?=$css[".inv-tabhead"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row bg-light p-3 border-top">
                                <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                <fieldset class="front-sec-devide bg-white pt-0">
                                    <legend class="fro-sec-head bg-primary mb-0">Tax Invoice</legend>
                                    <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/taxinvoice-setting-thumb.webp" class="img-fluid" alt="taxinvoice-setting-thumb">
                                </fieldset>
                            </div>
                        </div>  
                        <div id="alert" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Success Text</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertsuctxt" class="alertsuctxt jscolor form-control" type="text" value="<?=$css[".alert-success"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Success Background</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertsuc" class="alertsuc jscolor form-control" type="text" value="<?=$css[".alert-success"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Danger Text</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertdantxt" class="alertdantxt jscolor form-control" type="text" value="<?=$css[".alert-danger"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Danger Background</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertdan" class="alertdan jscolor form-control" type="text" value="<?=$css[".alert-danger"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>     
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Info Text</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertinfotxt" class="alertinfotxt jscolor form-control" type="text" value="<?=$css[".alert-info"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Info Background</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertinfo" class="alertinfo jscolor form-control" type="text" value="<?=$css[".alert-info"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Warning Text</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertwarntxt" class="alertwarntxt jscolor form-control" type="text" value="<?=$css[".alert-warning"]["color"]; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Alert Warning Background</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="alertwarn" class="alertwarn jscolor form-control" type="text" value="<?=$css[".alert-warning"]["background-color"]; ?>"></div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row bg-light p-3 border-top">
                                <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                <fieldset class="front-sec-devide bg-white pt-0">
                                    <legend class="fro-sec-head bg-primary mb-0">Alert Messages</legend>
                                    <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/alert-setting-thumb.webp" class="img-fluid" alt="alert-setting-thumb">
                                </fieldset>
                            </div>
                        </div>
                        <div id="notification" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Notification Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="notifbaccolor" class="notifbaccolor jscolor form-control" type="text" value="<?=$css[".notification-toggle"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Notification Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="notiftextcolor" class="notiftextcolor jscolor form-control" type="text" value="<?=$css[".notification-toggle a"]["color"]; ?>"></div>
                                            </div>   
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Notification Font Size</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="notiftextsize" class="notiftextsize form-control" type="text" value="<?=str_replace("px","",$css[".notification-toggle"]["font-size"]); ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row bg-light p-3 border-top">
                                <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                <fieldset class="front-sec-devide bg-white pt-0">
                                    <legend class="fro-sec-head bg-primary mb-0">Notification</legend>
                                    <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/notification-setting-thumb.webp" class="img-fluid" alt="notification-setting-thumb">
                                </fieldset>
                            </div>
                        </div>
                        <div id="soclink" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Facebook Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="fbbackcolor" class="fbbackcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-fb-link"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Facebook Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="fbtextcolor" class="fbtextcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-fb-link"]["color"]; ?>"></div>
                                            </div>   
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Facebook Background Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="fbbackhovercolor" class="fbbackhovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-fb-link:hover"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Facebook Text Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="fbtexthovercolor" class="fbtexthovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-fb-link:hover"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Linkdin Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="linkdinbackcolor" class="linkdinbackcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-link-link"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Linkdin Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="linkdintextcolor" class="linkdintextcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-link-link"]["color"]; ?>"></div>
                                            </div>   
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Linkdin Background Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="linkdinbackhovercolor" class="linkdinbackhovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-link-link:hover"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Linkdin Text Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="linkdintexthovercolor" class="linkdintexthovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-link-link:hover"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Pinterest Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="pinterbackcolor" class="pinterbackcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-pinter-link"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Pinterest Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="pintertextcolor" class="pintertextcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-pinter-link"]["color"]; ?>"></div>
                                            </div>   
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Pinterest Background Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="pinterbackhovercolor" class="pinterbackhovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-pinter-link:hover"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Pinterest Text Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="pintertexthovercolor" class="pintertexthovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-pinter-link:hover"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Instagram Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="instabackcolor" class="instabackcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-insta-link"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Instagram Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="instatextcolor" class="instatextcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-insta-link"]["color"]; ?>"></div>
                                            </div>   
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Instagram Background Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="instabackhovercolor" class="instabackhovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-insta-link:hover"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Instagram Text Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="instatexthovercolor" class="instatexthovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-insta-link:hover"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Youtube Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="youtubebackcolor" class="youtubebackcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-youtube-link"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Youtube Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="youtubetextcolor" class="youtubetextcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-youtube-link"]["color"]; ?>"></div>
                                            </div>   
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Youtube Background Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="youtubebackhovercolor" class="youtubebackhovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-youtube-link:hover"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Youtube Text Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="youtubetexthovercolor" class="youtubetexthovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-youtube-link:hover"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Twitter Background Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="twitbackcolor" class="twitbackcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-twit-link"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Twitter Text Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="twittextcolor" class="twittextcolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-twit-link"]["color"]; ?>"></div>
                                            </div>   
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Twitter Background Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="twitbackhovercolor" class="twitbackhovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-twit-link:hover"]["background-color"]; ?>"></div>
                                            </div>   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-6 col-md-12 col-lg-6">Twitter Text Hover Color</label>
                                                <div class="col-sm-6 col-md-12 col-lg-6 theme"><input name="twittexthovercolor" class="twittexthovercolor jscolor form-control" type="text" value="<?=$css[".socialLink ul li .soc-twit-link:hover"]["color"]; ?>"></div>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row bg-light p-3 border-top">
                                        <h6 class="mb-3 cc-font-weight-5">How It Works?</h6>
                                        <fieldset class="front-sec-devide bg-white">
                                            <legend class="fro-sec-head bg-primary mb-0">Social Links</legend>
                                            <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/social-setting-thumb.webp" class="img-fluid">
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="card-footer py-2 text-right"><button type="submit" class="btn btn-primary pull-right" onclick="savebodydata()">Submit</button></div>  
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<div class="modal" id="myModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Heading</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img src="<?php echo SITEURL; ?>/img/projectimage/helpscreenshot/head-contact.jpg" class="img-fluid" >
      </div>
    </div>
  </div>
</div>
<script src="<?php echo SITEURL ?>/js/colorpicker/jscolor.js"></script>
<script>
    function savebodydata(){
        headcontbackcolor = $(".headcontbackcolor").val();
        headcontlinkcolor = $(".headcontlinkcolor").val();
        headcontlinkcolorhover = $(".headcontlinkcolorhover").val();
        bodyfont = $(".bodyfont option:selected").val();
        bodyfontsize = $(".bodyfont option:selected").attr('data-variant');
        bodyfontSubs = $(".bodyfont option:selected").attr('data-subset');
        bodyfontVars = $(".bodyfontVars").val();
        bodyfontSubsets = $(".bodyfontSubsets").val();
        bodybgcolor = $(".bodybgcolor").val();
        bodyprimtext = $(".bodyprimtext").val();
        headfontVars = $(".headfontVars").val();
        headfontSubsets = $(".headfontSubsets").val();
        bfontSize = $(".bfontSize").val();
        fHeadFontSize = $(".fHeadFontSize").val();
        custPriBtn = $(".custPriBtn").attr("style");
        custPriBtnval = $(".custPriBtn").val();
        secondBtn = $(".secondBtn").attr("style");
        secondBtnHover = $(".secondBtnHover").attr("style");
        secondBtnBorder = $(".secondBtn").val();
        secondBtnBorderHover = $(".secondBtnHover").val();
        custHeaderBack = $(".custHeaderBack").attr("style");
        custHeaderLinkColor = $(".custHeaderLinkColor").val();
        custHeadLinkColorhover = $(".custHeadLinkColorhover").val();  
        headbadgecolor = $(".headbadgecolor").attr("style");
        custNavBack = $(".custNavBack").val();
        custNavLinkColor = $(".custNavLinkColor").val();
        custNavLinkColorhover = $(".custNavLinkColorhover").val();
        custNavLinkBackhover = $(".custNavLinkBackhover").val();
        custFooterBack = $(".custFooterBack").val();
        custPrimaryColor = $(".custPrimaryColor").val();
        alpgtitcolor = $(".alpgtitcolor").val();
        custSecondaryStyle = $(".custSecondaryColor").attr("style");
        custFootHeadColor = $(".custFootHeadColor").val();
        custFooterLinkHover = $(".custFooterLinkHover").val();
        footsechead = $ (".foot-sec-head option:selected").val();
        custFooterTextColor = $(".custFooterTextColor").val();
        custPrimaryBtnHover = $(".custPrimaryBtnHover").val();
        footerHeadingThickness = $(".foot-sec-head option:selected").val(); 
        navtextbold = $(".nav-link-bold option:selected").val();
        invprtextcolor = $(".invprtextcolor").val();
        invsectextcolor = $(".invsectextcolor").val();
        invtabhead = $(".invtabhead").attr("style");
        hmsecfontsize = $(".hmsecfontsize").val();
        bodysecondtext = $(".bodysecondtext").val();
        hmsecfontcolor = $(".hmsecfontcolor").val(); 
        alertsuc = $(".alertsuc").val();
        alertdan = $(".alertdan").val();
        alertinfo = $(".alertinfo").val();
        alertwarn = $(".alertwarn").val();
        alertsuctxt = $(".alertsuctxt").val();
        alertdantxt = $(".alertdantxt").val();
        alertinfotxt = $(".alertinfotxt").val();
        alertwarntxt = $(".alertwarntxt").val(); 
        notifbaccolor = $(".notifbaccolor").val();
        notiftextcolor = $(".notiftextcolor").val();
        notiftextsize = $(".notiftextsize").val();
        
        fbbackcolor = $(".fbbackcolor").val();
        fbtextcolor = $(".fbtextcolor").val();
        fbbackhovercolor = $(".fbbackhovercolor").val();
        fbtexthovercolor = $(".fbtexthovercolor").val();
        
        linkdinbackcolor = $(".linkdinbackcolor").val();
        linkdintextcolor = $(".linkdintextcolor").val();
        linkdinbackhovercolor = $(".linkdinbackhovercolor").val();
        linkdintexthovercolor = $(".linkdintexthovercolor").val();
        
        pinterbackcolor = $(".pinterbackcolor").val();
        pintertextcolor = $(".pintertextcolor").val();
        pinterbackhovercolor = $(".pinterbackhovercolor").val();
        pintertexthovercolor = $(".pintertexthovercolor").val();
        
        instabackcolor = $(".instabackcolor").val();
        instatextcolor = $(".instatextcolor").val();
        instabackhovercolor = $(".instabackhovercolor").val();
        instatexthovercolor = $(".instatexthovercolor").val();
        
        youtubebackcolor = $(".youtubebackcolor").val();
        youtubetextcolor = $(".youtubetextcolor").val();
        youtubebackhovercolor = $(".youtubebackhovercolor").val();
        youtubetexthovercolor = $(".youtubetexthovercolor").val();
        
        twitbackcolor = $(".twitbackcolor").val();
        twittextcolor = $(".twittextcolor").val();
        twitbackhovercolor = $(".twitbackhovercolor").val();
        twittexthovercolor = $(".twittexthovercolor").val();
        
        
        info = {bodyfont:bodyfont,bodyfontsize:bodyfontsize,bodyfontSubs:bodyfontSubs,bodyfontVars:bodyfontVars,bodyfontSubsets:bodyfontSubsets,bodyprimtext:bodyprimtext,headcontbackcolor:headcontbackcolor,headcontlinkcolor:headcontlinkcolor,headcontlinkcolorhover:headcontlinkcolorhover,headfontVars:headfontVars,headfontSubsets:headfontSubsets,bfontSize:bfontSize,footerHeadFont:fHeadFontSize,custPriBtn:custPriBtn,custPriBtnval:custPriBtnval,custHeaderBack:custHeaderBack,headbadgecolor:headbadgecolor,custNavBack:custNavBack,custNavLinkColor:custNavLinkColor,custNavLinkColorhover:custNavLinkColorhover,custNavLinkBackhover:custNavLinkBackhover,custFooterBack:custFooterBack,custHeaderLinkColor:custHeaderLinkColor,custPrimaryColor:custPrimaryColor,alpgtitcolor:alpgtitcolor,custSecondaryStyle:custSecondaryStyle,custFootHeadColor:custFootHeadColor,custFooterLinkHover:custFooterLinkHover,footerHeadingThickness:footerHeadingThickness,navtextbold:navtextbold,custHeadLinkColorhover:custHeadLinkColorhover,custFooterTextColor:custFooterTextColor,custPrimaryBtnHover:custPrimaryBtnHover,bodybgcolor:bodybgcolor,secondBtn:secondBtn,secondBtnHover:secondBtnHover,secondBtnBorder:secondBtnBorder,secondBtnBorderHover:secondBtnBorderHover,invprtextcolor:invprtextcolor,invsectextcolor:invsectextcolor,invtabhead:invtabhead,hmsecfontsize:hmsecfontsize,bodysecondtext:bodysecondtext,hmsecfontcolor:hmsecfontcolor,alertsuc:alertsuc,alertdan:alertdan,alertinfo:alertinfo,alertwarn:alertwarn,alertsuctxt:alertsuctxt,alertdantxt:alertdantxt,alertinfotxt:alertinfotxt,alertwarntxt:alertwarntxt,notifbaccolor:notifbaccolor,notiftextcolor:notiftextcolor,notiftextsize:notiftextsize,fbbackcolor:fbbackcolor,fbtextcolor:fbtextcolor,fbbackhovercolor:fbbackhovercolor,fbtexthovercolor:fbtexthovercolor,linkdinbackcolor:linkdinbackcolor,linkdintextcolor:linkdintextcolor,linkdinbackhovercolor:linkdinbackhovercolor,linkdintexthovercolor:linkdintexthovercolor,pinterbackcolor:pinterbackcolor,pintertextcolor:pintertextcolor,pinterbackhovercolor:pinterbackhovercolor,pintertexthovercolor:pintertexthovercolor,instabackcolor:instabackcolor,instatextcolor:instatextcolor,instabackhovercolor:instabackhovercolor,instatexthovercolor,youtubebackcolor:youtubebackcolor,youtubetextcolor:youtubetextcolor,youtubebackhovercolor:youtubebackhovercolor,youtubetexthovercolor:youtubetexthovercolor,twitbackcolor:twitbackcolor,twittextcolor:twittextcolor,twitbackhovercolor:twitbackhovercolor,twittexthovercolor:twittexthovercolor}
        $.ajax({
            type:"POST",
            url:"ajax_customize_option.php",
            data:info,
            success:function(response){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Style updated successfully").delay(3000).fadeOut(); 
            }
        });
    }
    function getfont(){
        fontname = $(".bodyfont option:selected").val();
        fontvariant = $(".bodyfont option:selected").attr("data-variant");
        fontsubset = $(".bodyfont option:selected").attr("data-subset");
        $(".bodyfontVars").val(fontvariant);
        $(".bodyfontSubsets").val(fontsubset);
    }
</script>
</body>
</html>
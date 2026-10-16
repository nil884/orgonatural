<?php include("includes/configuration.php");
$segment1 = $_GET['id'];
$blogcat_name = selectQuery(BLOGCAT,"cat_id,category_name","isActive='1' and url_title = '".$segment1."' ");
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]; ?>
<!doctype html>
<html itemscope itemtype="http://schema.org/WebPage" lang="en">
<head>
    <title>Blog : <?php echo SITE_TITLE;?></title>
    <meta name="keywords" content="<?php echo METAKEYWORDS;?>">
    <meta name="description" content ="<?php echo METADESCRIPTION;?>">
    <?php include('commoncss.php') ?>
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Blog : <?php echo SITE_TITLE;?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Blog : <?php echo SITE_TITLE;?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
</head>
<body class="bg-light">
<?php include 'menu.php' ?>
<div class="main-wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><ol class="breadcrumb pl-0 pb-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL; ?>" hreflang="en">Home</a></li><li class="breadcrumb-item">Blog</li><li class="breadcrumb-item active"><?php echo $blogcat_name[0]['category_name'] ?><li></ol></div>
        </div>
    </div>
    <div class="content pt-2 pt-sm-2 pt-md-4">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="dropdown d-block d-md-none mt-2 mb-3">
                        <button type="button" class="btn btn-primary dropdown-toggle px-4" data-toggle="dropdown">Blog Categories</button>
                        <div class="dropdown-menu border-0 cc-shadow-1">
                            <div class="nav">
                                <?php $blogcat = selectQuery(BLOGCAT,"url_title,category_name,cat_id","isActive='1' order by category_name ASC");
                                for($i=0;$i<count($blogcat);$i++){
                                $blog=selectQuery(BLOG,"count(id) as blogcatwisecnt","category='".$blogcat[$i]['cat_id']."' and isActive='1'");
                                if($blog[0]['blogcatwisecnt'] !=0) {?>
                                <a class="nav-link text-capitalize position-relative dropdown-item <?=($i==0?'active':''); ?>" href="<?=SITEURL ?>/bloglist/<?php echo $blogcat[$i]['url_title']."/"; ?>" hreflang="en"><?php echo $blogcat[$i]['category_name']; ?><span class="badge badge-success badge-pill float-right"><?php echo $blog[0]['blogcatwisecnt']; ?></span></a>
                                <? }  } ?>
                            </div>
                        </div>
                    </div> 
                    <div class="d-none d-md-block blogcategory">
                        <button type="button" class="btn btn-default btn-sm d-block d-sm-none">Categories <span class="caret"></span></button>
                        <h5 class="mb-4">Categories</h5>
                        <ul class="list-unstyled blog-cat-dropdown nav d-block">
                            <?php $blogcat = selectQuery(BLOGCAT,"url_title,category_name,cat_id","isActive='1' order by cat_id ASC");
                            for($i=0;$i<count($blogcat);$i++){
                            $blog=selectQuery(BLOG,"count(id) as blogcatwisecnt","category='".$blogcat[$i]['cat_id']."' and isActive='1'"); if($blog[0]['blogcatwisecnt'] !=0){ ?>
                            <li class="nav-item"><a class="nav-link text-capitalize position-relative <?=($i==0?'active':''); ?>" href="<?=SITEURL ?>/bloglist/<?php echo $blogcat[$i]['url_title']."/"; ?>" hreflang="en"><?php echo $blogcat[$i]['category_name']; ?><span class="badge badge-success badge-pill"><?php echo $blog[0]['blogcatwisecnt']; ?></span></a></li>
                            <? } } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-9 col-lg-9">
                    <h1 class="mb-4 h5"><?php echo $blogcat_name[0]['category_name'] ?></h1>
                    <div class="blog-div row mr-0"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>
<script>
$(".blogcategory .btn").click(function(){ $(".blog-cat-dropdown").slideToggle(); });
</script>
<script>
var siteurl = "<?=SITEURL; ?>"
var where = "<?= $blogcat_name[0]['cat_id'] ?>";
displayprod(siteurl,where,1)
function displayprod(siteurl,where, pageNum){
    $.ajax({
        type: "POST",
        url: siteurl+"/ajax/blog_ajax.php",
        data: {where:where,pagenum:pageNum,action:"pagination"},
        cache: false,beforeSend: function() {},
        success: function(html){ $(".blog-div").html(html); totalitem = $(".total-request").html();          $(".itmtocount").html(totalitem);  
        setTimeout(function(){ 
            lazyloader();
        }, 500);
        }
    });
}
</script> 
</body>
</html>
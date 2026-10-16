<?php include("includes/configuration.php");
$segment1 = $_GET['cat'];
$segment2 = $_GET['urltitle'];
$url_str = $segment1."/".$segment2; 
$getblog = selectQuery(BLOG,"activate_comments,id,category,page_title,keywords,metadescription,posted_by,title,posted_date,category,url_title,summary","isActive = '1' and url_title = '".$url_str."'");
$blogid = $getblog[0]['id'];  
$cat_name = selectQuery(BLOGCAT,"url_title,cat_id,category_name","cat_id='".$getblog[0]['category']."'");
$pagetitle = $getblog[0]['page_title'];
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]; ?>
<!doctype html>
<html itemscope itemtype="http://schema.org/WebPage" lang="en">
<head>
    <title><?php if($pagetitle != ""){ echo "Blog - ".$pagetitle." : ".SITE_TITLE; } else{ echo "Blog - ".$getblog[0]['title']." : ".SITE_TITLE; } ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="<?php if($getblog[0]['keywords'] != ""){ echo $getblog[0]['keywords'];} else{ echo METAKEYWORDS; } ?>">
    <meta name="description" content =" <?php if($getblog[0]['metadescription'] != ""){ echo $getblog[0]['metadescription'];} else{ echo METADESCRIPTION; } ?>">
    <?php include 'commoncss.php'; ?>
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php if($pagetitle != ""){ echo "Blog - ".$pagetitle." : ".SITE_TITLE; } else{ echo "Blog - ".$getblog[0]['title']." : ".SITE_TITLE; } ?>">
    <meta property="og:description" content="<?php if($getblog[0]['metadescription'] != ""){ echo $getblog[0]['metadescription'];} else{ echo METADESCRIPTION; } ?>">
    <meta property="og:image" content="<?=($blgig!=""?SITEURL."img/blogimg/".$blgig:(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png"))); ?>">
    <!--Twitter-->
    <meta property="twitter:card" content="<?=($blgig!=""?SITEURL."img/blogimg/".$blgig:(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png"))); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php if($pagetitle != ""){ echo "Blog - ".$pagetitle." : ".SITE_TITLE; } else{ echo "Blog - ".$getblog[0]['title']." : ".SITE_TITLE; } ?>">
    <meta property="twitter:description" content="<?php if($getblog[0]['metadescription'] != ""){ echo $getblog[0]['metadescription'];} else{ echo METADESCRIPTION; } ?>">
    <meta property="twitter:image" content="<?=($blgig!=""?SITEURL."img/blogimg/".$blgig:(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png"))); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
</head>
<body class="bg-light">
<div class="main-wrap">
    <?php include 'menu.php' ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12"><ol class="breadcrumb pl-0 pb-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL; ?>" hreflang="en">Home</a></li><li class="breadcrumb-item"><a href="<?php echo SITEURL; ?>/bloglist/<?php echo $cat_name[0]['url_title']."/"; ?>" hreflang="en"><?php echo "Blog - ".$cat_name[0]['category_name'] ?></a></li><li class="breadcrumb-item active"><?php echo $getblog[0]['title'] ?></li></ol></div>
        </div>
        <div class="content pt-3">
            <div class="row">
                <div class="col-md-12 col-lg-8 blog-detail">
                    <div class="card border-0 cc-shadow-1 mb-3" itemscope itemtype="http://schema.org/Blogposting">
                        <div class="card-body blog-description">
                            <div class="blog-heading"><h1 itemprop="headline" class="sing-blog-title mb-3"><?php echo $getblog[0]['title']; ?></h1></div>
                            <div class="blog-short-info mb-4"> 
                                <ul class="list-unstyled">
                                    <li class="blog-cat-type pl-0 mb-2 mb-md-0"><i class="fa fa-folder mr-1"></i> <?php echo "<span class='text-primary'>Category</span> : <span>".$cat_name[0]['category_name']."</span>"; ?></li>
                                    <li class="mb-2 mb-md-0"><i class="fa fa-calendar mr-1"></i> <span class='text-primary'>Posted Date</span> : <span itemprop="datePublished"><?php $posted_date = explode ("-" , $getblog[0]['posted_date']);
                                    $postdate = $posted_date[2]."-".$posted_date[1]."-".$posted_date[0];
                                    if($getblog[0]['posted_date'] == "0000-00-00 "){ echo "NA"; } else{ echo $postdate; } ?></span></li>
                                    <li itemprop="name"><i class="fa fa-user mr-1"></i> <span class='text-primary'>Posted By</span> : <?php if($getblog[0]['posted_by'] == ""){echo "NA";} else{ echo $getblog[0]['posted_by'];}?></li>
                                </ul>
                            </div>
                            <div itemprop="mainEntityOfPage" class="blog-summmery"><?php echo $getblog[0]['summary'];?></div>
                        </div>
                    </div>
                    <?php if($getblog[0]['activate_comments']==1){ ?>
                    <div class="card mb-3 border-0 cc-shadow-1">
                        <div class="card-body">
                            <h5 class="mb-3">Leave A Reply</h5>
                            <form class="bloglogin" id="formblog">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Full name" name="name" required>
                                    <div class="invalid-tooltip">Please enter name</div>
                                </div>
                                <input type="hidden" name="model" id="model" value="<?php echo $blogid; ?>">
                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea name="cmnt" placeholder="Enter Your Comment Max Limit 200 Character." class="form-control" maxlength="200" id="textareas" cols="5" required></textarea>
                                    <div class="invalid-tooltip">Please enter comment</div>
                                </div>
                                <div id="msg"></div>
                                <input type="button" name="Submit" id="addblog" class="btn btn-primary addit" onclick="addcomment()" value="Post Your Reply"/>
                            </form>
                        </div>
                    </div>
                    <div class="card border-0 cc-shadow-1">
                        <div class="card-body pb-2">
                            <h5 class="mb-3">Comments</h5>
                            <?php $comment = selectQuery(BLOGCMNT,"blog_id,comment_date,user_name,comment","isApproved='1' and blog_id=".$blogid." ");
                            if(count($comment)){
                            for($i=0;$i<count($comment);$i++){
                            if($comment[$i]['blog_id'] == $blogid){ ?>
                            <div class="media blog-comt-col mb-3 pb-3 border-bottom">
                                <?php $newDate = $comment[$i]['comment_date']; ?>
                                <span class="blg-cmt-thumb text-center d-inline-block rounded-circle bg-secondary mr-3"><?=substr($comment[$i]['user_name'],0,1) ?></span>
                                <div class="media-body">
                                    <div class="row m-0 align-items-center">
                                        <span class="mr-2"><?php echo $comment[$i]['user_name']; ?></span>
                                        <span class="small">(<?php echo date('d M Y h:i a', strtotime($newDate)); ?>)</span>
                                    </div>
                                    <div class='commentdesc mt-1'><?php echo $comment[$i]['comment']; ?></div>
                                </div>
                            </div>                      
                            <?php } } } else{
                            echo "<p>We have no comments yet... Take the initiative and post your views, we love to hear from you</p>"; } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-4 d-none d-lg-block">
                    <div class="card border-0 cc-shadow-1 sideblog-section">
                        <div class="card-body pb-0">
                            <h5 class="mb-4">Latest Blogs</h5>
                            <?php $getblog = selectQuery(BLOG,"id,posted_by,title,posted_date,category,url_title","isActive = '1'");
                            for($i=0;$i<count($getblog);$i++){   
                            $blogidimg = selectQuery(BLOGIMG,"img_name","blogid='".$getblog[$i]['id']."'");
                            $cat_name = selectQuery(BLOGCAT,"category_name","cat_id='".$getblog[$i]['category']."'"); ?>
                            <div class="media mb-3 pb-3 border-bottom">
                                <div class="last-blog-thumb mr-3">
                                <a href="<?=SITEURL ?>/blog/<?=$getblog[$i]['url_title'] ?>" hreflang="en" class="h-100 w-100"><img class="mh-100 mw-100 m-auto d-block imglazyloader rounded" src="<?php echo SITEURL; ?>/<?php if( isset($blogidimg[0]['img_name'])){ echo "img/blogimg/".$blogidimg[0]['img_name'];} else { echo "img/projectimage/no_image_available.jpg";} ?>" alt="Blog Images"></a></div>
                                <div class="media-body">
                                    <div class="cc-primary-color small mb-1">Category : <? echo $cat_name[0]['category_name'] ?></div>
                                    <h2 class="let-blog-title text-dark h6"><a href="<?=SITEURL ?>/blog/<?=$getblog[$i]['url_title'] ?>" hreflang="en"><?php echo $getblog[$i]['title']; ?></a></h2>
                                    <div class="cc-font-size-13">
                                        <span class="mr-2"><i class="fa fa-calendar"></i>
                                        <?php $posted_date = explode ("-" , $getblog[$i]['posted_date']);
                                        $postdate = $posted_date[2]."-".$posted_date[1]."-".$posted_date[0];
                                        if($getblog[$i]['posted_date'] == "0000-00-00 "){ echo "NA"; } else{ echo $postdate; } ?></span> 
                                        <span><i class="fa fa-user"></i> By <?php if($getblog[$i]['posted_by'] == "") { echo "NA"; } else{ echo $getblog[$i]['posted_by'];} ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="<?php echo SITEURL?>/js/validation.js"></script>
<script>
$("#addblog").click(function(event){
    //Fetch form to apply custom Bootstrap validation
    var form = $("#formblog")
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
});
function addcomment(){
    Summaryhtml = document.getElementById("textareas").value;
    var model = $("#model").val();
    var name = $("#name").val();
    if (name != "" &&  Summaryhtml != ""){
        var info = { Summaryhtml:Summaryhtml ,name: name , model:model, action:"addcomment"}
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL; ?>/ajax/blog_ajax.php",
            data: info,
            success: function(response){
                $(".addit").attr('disabled',false)
                if(response == 1) {
                    $('#msg').fadeIn().removeClass('alert alert-danger').addClass('alert alert-success').html('Your comment has been submitted and waiting for approval by an Admin').delay(3000).fadeOut();
                    $("#formblog").removeClass('was-validated');
                    $("#formblog").trigger('reset');
                } else{
                    $('#msg').fadeIn().removeClass('alert alert-success').addClass('alert alert-danger').html('Please try after some time').delay(3000).fadeOut();
                }
            }
        })
    }
}
</script>
</body>
</html>
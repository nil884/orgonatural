<?php include ("../includes/configuration.php");
    $action = $_REQUEST['action'];
    if($action == "addcomment"){
    $Summaryhtml = $_REQUEST['Summaryhtml'];
    $name=$_REQUEST['name'];
    $data=array(
        'blog_id'=>$_REQUEST['model'],
        'comment'=>addslashes($_REQUEST['Summaryhtml']),
        'user_name'=>ucwords($name),
        'comment_Date'=>date('Y-m-d H:i:s'),
       
    );
    $insert = insertQuery(BLOGCMNT,$data);
    if($insert){ echo "1";}
    else{ echo "0";}
}
if($action == "pagination"){
    if(!(isset($_POST['pagenum']))){ $pagenum = 1;
    }else { $pagenum = intval($_POST['pagenum']); }
    $where= $_POST['where'];$prodid = $_POST['where']; 
    //Number of results displayed per page  by default its 10.
    $page_limit = BLOGCNTPAGE;
    // Get the total number of rows in the table
    $totalarticle = selectQuery(BLOG,"id","category='".$where."' and isActive='1' ");
    $cnt = count($totalarticle);
    //Calculate the last page based on total number of rows and rows per page.
    if($cnt!=0){ $last = ceil($cnt/$page_limit); }else{$last=1;}
    //this makes sure the page number isn't below one, or more than our maximum pages
    if ($pagenum < 1){ $pagenum = 1; } elseif($pagenum > $last){ $pagenum = $last;}
    $lower_limit = ($pagenum!=1?(($pagenum - 1) * $page_limit):0);
    if($cnt!=0){
        //$productids=$prod->getReview($,$lower_limit,$page_limit,$ordering,$finalprodfilter,$finaltempfilter,$template);
        //$reviews=$prod->getReview($prodid,$lower_limit,$page_limit);
        if(isset($lower_limit)&&isset($page_limit)){ $limit=" Limit ".$lower_limit.",".$page_limit;}
        $getblog =selectQuery(BLOG,"id,posted_by,title,posted_date,category,url_title","category='".$where."' and isActive='1' ".$limit);
    }else{ $getblog= array(); }  
    for($i=0;$i<count($getblog);$i++){
    $blogidimg=selectQuery(BLOGIMG,"*","blogid='".$getblog[$i]['id']."'");
    $blogcat = selectQuery(BLOGCAT,"category_name","cat_id='".$getblog[$i]['category']."'");
    ?>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-4 mb-2 mb-sm-4 blog-col pr-0">
        <div class="blog-box card">
            <div class="row blog-com-row">
                <div class="blog-thumb position-relative col-4 col-sm-12 col-md-12 position-relative my-3 my-sm-0 pr-0 pr-sm-3">
                    <a href="<?=SITEURL ?>/blog/<?php echo $getblog[$i]['url_title'] ?>">
                        <img src="<?php echo SITEURL; ?>/img/<?php if($blogidimg[0]['img_name'] != ""){ echo "blogimg/".$blogidimg[0]['img_name'];} else{ echo "projectimage/no_image_available.jpg";} ?>" alt="Blog Images" class="img-fluid imglazyloader m-auto rounded"/>
                    </a>
                    <span class="blg-date cc-second-back d-none d-sm-block position-absolute bg-white rounded px-2 py-1">
                        <i class="fa fa-calendar"></i>
                        &nbsp; <?php $posted_date = explode ("-" , $getblog[$i]['posted_date']);
                        $postdate = $posted_date[2]."-".$posted_date[1]."-".$posted_date[0];
                        if($getblog[$i]['posted_date'] == "0000-00-00 "){ echo "NA"; } else{echo $postdate; } ?>
                    </span>
                </div>
                <div class="col-8 col-sm-12 col-md-12 pl-0 pl-sm-3">
                    <div class="card-body">
                        <div class="small text-primary mb-1"> Category : <?php echo  $blogcat[0]['category_name']; ?></div>
                        <h2 class="blog-title mt-0 mb-1 h6" title="/<?php echo $getblog[$i]['title']; ?>"><a href="<?=SITEURL ?>/blog/<?php echo$getblog[$i]['url_title']; ?>" class="d-block"><?=(strlen($getblog[$i]['title'])>44?substr($getblog[$i]['title'],0,45)."..":$getblog[$i]['title']); ?></a></h2>
                        <div class="blog-meta-details">
                            <span class="blg-date mr-2 d-block d-sm-none"><i class="fa fa-calendar"></i> <?php $posted_date = explode ("-" , $getblog[$i]['posted_date']);
                            $postdate = $posted_date[2]."-".$posted_date[1]."-".$posted_date[0];
                            if($getblog[$i]['posted_date'] == "0000-00-00 "){ echo "NA"; } else{echo $postdate; } ?>
                            </span>
                            <span class="blog-writer">
                            <i class="fa fa-user"></i> <span class="text-muted">By</span> <span><?php if($getblog[$i]['posted_by'] == "") { echo "NA"; } else { echo $getblog[$i]['posted_by'];} ?></span></span>
                        </div>
                        <!--div class="text-muted blog-description"><?php echo substr( $getblog[$i]['summary'],0,80) ?></div-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="col-md-12 d-none"><span class="page-list-count total-request"><?=$cnt; ?> Items</span></div>
    <div class="col-md-12 prod-itm-pagination">
        <? if($cnt!=0 && $last>1){  ?>
        <ul class="pagination mt-4 pt-2 justify-content-center text-center">
            <?php if (($pagenum-1) > 0){ ?>
            <li class="page-item"><a href="javascript:void(0);" class="page-link border-0 mx-1 mr-3 text-primary rounded-0" onclick="displayprod('<?=SITEURL; ?>','<?=$where; ?>', '<?=1; ?>');">Previous</a></li>
            <li class="page-item"><a href="javascript:void(0);" class="page-link p-0 mx-1 rounded-circle pgn-btn pgn-handle-btn"  onclick="displayprod('<?=SITEURL; ?>','<?=$where; ?>', '<?=$pagenum-1; ?>');"><i class="fa fa-angle-left"></i></a></li>
            <?php }
            if ($pagenum!=1) $prev= $pagenum-1;
            else $prev= $pagenum;
            if($pagenum!=$last)$next= $pagenum+1;
            else $next= $last;
            //Show page links
            for($i=$prev; $i<=$next; $i++){
            if ($i == $pagenum) { ?>
            <li class="page-item <?=($pagenum==$i?'active':''); ?>"><a href="javascript:void(0);" class="current page-link p-0 mx-1 rounded-circle pgn-btn"><?=$i ?></a></li>
            <?php } else { ?>
            <li class="page-item <?=($pagenum==$i?'active':''); ?>"><a href="javascript:void(0);" class="page-link p-0 mx-1 rounded-circle pgn-btn" onclick="displayprod('<?=SITEURL; ?>','<?=addslashes($where);  ?>', '<?=$i; ?>');" ><?=$i ?></a></li>
            <?php } } if(($pagenum+1) <= $last) { ?>
            <li class="page-item"><a href="javascript:void(0);" class="page-link p-0 mx-1 rounded-circle pgn-btn pgn-handle-btn" onclick="displayprod('<?=SITEURL; ?>','<?= addslashes($where); ?>', '<?=$pagenum+1; ?>');" class="links"><i class="fa fa-angle-right"></i></a></li>
            <?php } if(($pagenum) != $last){ ?>
            <li class="page-item"><a href="javascript:void(0);" class="page-link border-0 mx-1 ml-3 text-primary rounded-0" onclick="displayprod('<?=SITEURL; ?>','<?= addslashes($where);  ?>', '<?=$last; ?>');" class="links" >Next</a></li>
            <?php } ?>
        </ul>
        <? } ?>
    </div>
<?php } ?>
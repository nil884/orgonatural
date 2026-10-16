<?php $basicdetail = selectQuery(BLOG,"*","id='".base64_decode($_REQUEST['blogid'])."'");
$blogidimgsnv = selectQuery(BLOGIMG,"*","blogid='".base64_decode($_REQUEST['blogid'])."'");?>
<nav class="bg-white card navbar-light com-step-nav">
    <ul class="list-unstyled nav">
        <li class="nav-item"><a class="nav-link px-3" href="<?php echo ADMINURL; ?>blog/viewbasic.php?blogid=<?php echo $_REQUEST['blogid']; ?>"><?php if(count($basicdetail)){?><i class="fa fa-check-circle text-success mr-1" aria-hidden="true"></i><? } else{ ?><i class="fa fa-exclamation-circle text-danger mr-1" aria-hidden="true"></i><? } ?> Basic Details</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?php echo ADMINURL; ?>blog/blogimage.php?blogid=<?php echo $_REQUEST['blogid']; ?>"><?php if(count($blogidimgsnv)){ ?><i class="fa fa-check-circle text-success mr-1" aria-hidden="true"></i><? } else{ ?> <i class="fa fa-exclamation-circle text-danger mr-1" aria-hidden="true"></i> <? } ?> Images</a></li>
        <li class="nav-item"><a class="nav-link px-3 blgseo" href="seo.php?blogid=<?php echo $_REQUEST['blogid']; ?>"><?php if($basicdetail[0]['page_title']!="" && $basicdetail[0]['keywords']!="" && $basicdetail[0]['metadescription']!=""){ ?> <i class="fa fa-check-circle text-success mr-1" aria-hidden="true"></i> <? } else{ ?> <i class="fa fa-exclamation-circle text-danger mr-1" aria-hidden="true"></i><? } ?> SEO</a>
        </li>
    </ul>
 </nav>

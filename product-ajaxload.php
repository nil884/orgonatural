<? include("includes/configuration.php");
    include("classes/product.php");
    if($_SESSION['wishitems'] != ""){ $item_array_id= array_column($_SESSION['wishitems'] ,"prod_id" );}
    if($_SESSION['shopping_cart'] != ""){ $cart_item_array_id= array_column($_SESSION['shopping_cart'] ,"prod_id" );}
    $prod = new Product();
    $imgtype = "product";
    include("getimgpath.php");
    //Very important to set the page number first.
    if(!(isset($_POST['pagenum']))){ $pagenum = 1; } else{ $pagenum = intval($_POST['pagenum']); }
    $where = $_POST['where']; $ordering = $_POST['ordering']; $template = $_POST['template']; $minprice = $_POST['minprice']; $maxprice = $_POST['maxprice'];
    $filter = array();
    $filter = json_decode($_POST['filter'],true);
    $prodfilter=array(); $templatefilter=array(); $finalprodfilter = "";
    if(count($filter)){
        foreach($filter as $key=>$value){
           if($key=="company"||$key=="category"||$key=="subcategory"){}else if(count($value)){
                $str = implode("','",$value);
                array_push($templatefilter,"(t.".$key." IN ('".($str)."'))");
           }
        } 
    }
    $finaltempfilter = implode(" AND ",$templatefilter);
    if($minprice!=""&&$maxprice!=""){ array_push($prodfilter,"(".($finalprodfilter!=""?'p.':'p.')."final_price between ".$minprice." AND ".$maxprice.")"); }
    if(count($filter)){
        foreach($filter as $key=>$value){
            if($key=="company"&&count($value)){
                $str = implode("','",$value);
                array_push($prodfilter,"(".($finalprodfilter!=""?'p.':'p.')."prod_company IN ('".($str)."'))");
            }
             if($key=="category"&&count($value)){
                 $catarr=array();  $cstr = implode("','",$value);
                 $getcats=selectQuery(PRODCAT,"id","cat_name IN('".$cstr."')");
                 for($i=0;$i<count($getcats);$i++){array_push($catarr,$getcats[$i]['id']);   }
                $str = implode(",",$catarr);
                array_push($prodfilter,"(".($finalprodfilter!=""?'p.':'p.')."master_cat IN (".$str."))");
            } if($key=="subcategory"&&count($value)){
                 $catarr=array();  $cstr = implode("','",$value);
                 $getcats=selectQuery(PRODCAT,"id","cat_name IN('".$cstr."')");
                 for($i=0;$i<count($getcats);$i++){array_push($catarr,$getcats[$i]['id']);   }
                $str = implode(",",$catarr);
                array_push($prodfilter,"(".($finalprodfilter!=""?'p.':'p.')."sub_cat IN (".$str."))");
            }
        }
    }
    $finalprodfilter = implode(" AND ",$prodfilter);
    //Number of results displayed per page 	by default its 10.
    $page_limit = PRODCNTPAGE;
    //Get the total number of rows in the table

    $totalarticle = $prod->getcount($where,$finalprodfilter,$finaltempfilter,$template);
    $cnt = $totalarticle;
    //Calculate the last page based on total number of rows and rows per page.
    if($cnt!=0){ $last = ceil($cnt/$page_limit); }else{$last=1;}
    //this makes sure the page number isn't below one, or more than our maximum pages
    if($pagenum < 1){ $pagenum = 1; } elseif($pagenum > $last){ $pagenum = $last;}
    $lower_limit = ($pagenum!=1?(($pagenum - 1) * $page_limit):0);
    if($cnt!=0){ $productids = $prod->getProductsByGroup($where,$lower_limit,$page_limit,$ordering,$finalprodfilter,$finaltempfilter,$template);
    }else{ $productids = array(); }
    //$getarticle=selectQuery(ARTICLEDETAILS,"article_id,articleTitle,content,singleUserPrice,arcticleDate,publisher,category,urlTitle","isActive='1' AND isDel='0' ".$where." order by article_id DESC limit ". ($lower_limit)." ,  ". ($page_limit). " ");
?>
<div class="col-md-12 d-none"><span class="page-list-count total-request"><?=$cnt; ?> Items</span></div>
<? for($i=0;$i<count($productids);$i++){  ?>
<div class="col-6 col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-3 pr-0 pro-col-listing "><? include("product-view.php"); ?></div>
<? } ?>
<div class="col-md-12 prod-itm-pagination">
    <? if($cnt!=0 && $last>1){  ?>
    <ul class="list-unstyled mt-2 pt-2">
        <?php if(($pagenum-1) > 0){ ?>
        <li class="page-item"><a href="javascript:void(0);" class="page-link mx-1 rounded-0 text-center" onclick="displayprod('<?=SITEURL; ?>','<?=$where;  ?>', '<?=1; ?>');">First</a></li>
        <li class="page-item"><a href="javascript:void(0);" class="page-link p-0 mx-1 pgn-btn pgn-handle-btn rounded-0 text-center" onclick="displayprod('<?=SITEURL; ?>','<?=$where; ?>', '<?=$pagenum-1; ?>');"><i class="fa fa-angle-left"></i></a></li>
        <?php }
        // Increase page no limit form 1 to 4 i.e pagenum+1 to pagenum+4
        if($pagenum!=$last){
        	$next = $pagenum+4;
        	// Add bellow loop for less then 5 page count
        	if($next > $last)
        		$next = $last;
        }
        else $next= $last;
        // Increase previous page no limit form 1 to 4 i.e pagenum-1 to next-4
        if($pagenum!=1){ 
        	$prev= $next-4;
        	// Add bellow loop for less then 5 page count or prev arrow click manage
        	if($prev <= 0)
        		$prev = 1;
        }else $prev= $pagenum;
        //Show page links
        
        for($i=$prev; $i<=$next; $i++){
        if ($i == $pagenum){ ?>
        <li class="page-item <?=($pagenum==$i?'active':''); ?>"><a class="current page-link p-0 mx-1 pgn-btn rounded-0 text-center"><?=$i ?></a></li>
        <?php } else{ ?>
        <li class="page-item <?=($pagenum==$i?'active':''); ?>"><a class="page-link p-0 mx-1 pgn-btn text-center" onclick="displayprod('<?=SITEURL; ?>','<?=addslashes($where);  ?>', '<?=$i; ?>');" ><?=$i ?></a></li>
        <?php } }
        if(($pagenum+1) <= $last){ ?>
        <li class="page-item"><a href="javascript:void(0);" class="page-link p-0 mx-1 pgn-btn pgn-handle-btn text-center" onclick="displayprod('<?=SITEURL; ?>','<?= addslashes($where);  ?>', '<?=$pagenum+1; ?>');" class="links"><i class="fa fa-angle-right"></i></a></li>
        <?php } if(($pagenum) != $last){ ?>
        <li class="page-item"><a href="javascript:void(0);" class="page-link rounded-0 mx-1 text-center" onclick="displayprod('<?=SITEURL; ?>','<?= addslashes($where);  ?>', '<?=$last; ?>');" class="links">Last</a></li>
        <?php } ?>
    </ul>
    <? } ?>
</div>
<?php include "../../includes/configuration.php";
    $action = $_POST['action'];
    if($action == "getProdData"){
        $aplon = $_POST['applicableOn'];
        if($aplon == "Subcategory"){
        function get_cat_tree2($parent_id){
        $menu = "";
        $prod_cat=selectQuery(PRODCAT,"id,type,cat_name","parent_id=".$parent_id."  order by priority");
        if(count($prod_cat)){
            for($i=0;$i<count($prod_cat);$i++){
                $cat_id = $prod_cat[$i]['id'];
                $cat_name = $prod_cat[$i]['cat_name'];
                $template=$prod_cat[$i]['template']; $excelfile=$prod_cat[$i]['excelFile'];
                $cattype=$prod_cat[$i]['type'];
                //$activfun = "active(".$cat_id.",'checkbox".$cat_id."','".addslashes($cattype)."')";
                $activfun = 'active("'.$cat_id.'","checkbox'.$cat_id.'","'.addslashes($cattype).'")';
                $excelfunc= " onclick=createexcel('".$cat_id."','".$template."')";
                if($prod_cat[$i]['isActive'] == 1) { $checkvalue = "checked"; }else { $checkvalue = "";  }
                $checkbox="";
                if($cattype == "Parent"){
                    $internal = " masterlist"; $classd = 'parent-category mb-2';
                    $str = "'".$cat_id."','".addslashes($cat_name)."','Master'";
                }
                else if($cattype == 'Master'){ $internal = " sublist";  $classd = 'master-category sub-cat-body mb-2';$str = "'".$cat_id."','".addslashes($cat_name)."','Sub'"; $add_child = '<button type="button" class="btn btn-default"  onclick ="openmodel('.$str.')"><i class="fa fa-plus" aria-hidden="true"></i></button>' ;}
                else if($cattype == 'Sub'){ $classd = 'sub-category border-left border-right border-bottom';
                    $checkbox="<div class='custom-control custom-checkbox'><input type='checkbox' class='custom-control-input' name='products' value='".$cat_id."'  id='subcat_".$cat_id."'><label class='custom-control-label' for='subcat_".$cat_id."'>";
                    $label_end = "</label></div>";
                }
                $menu .="<div class='menu-catgory-panel ".$classd."' data-id='".$cat_id."'>
                <div class='mng-cat-head'>";
                if($cattype != 'Sub'){ $menu .="<div>"; }
                $menu .="<div class='menu-cat-title cc-cursor-pointer' onclick='toggle(".$cat_id.")' id=".$cat_id.">".$checkbox." ".$cat_name.$label_end."</div>";
                $menu .="<div class='cat-head-elements'></div>";
                if($cattype != 'Sub'){ $menu .=" </div> "; }
                $menu .=" </div> ";
                $menu .="<div class='menu-catgory cc-display-none".$internal."' >".get_cat_tree2($cat_id)."</div>";
                $menu .="</div>";
            }
        }
            return $menu;
        } ?>
        <div><h6 class="mt-3 mb-3 cc-font-weight-5">Open Tree Structure To Select Subcategory</h6><?php echo get_cat_tree2(0); ?> </div>
        <? }else if($aplon=="Product"){
        function get_cat_tree3($parent_id){
        $menu = "";
        $prod_cat=selectQuery(PRODCAT,"id,type,cat_name","parent_id=".$parent_id."  order by priority");
        if(count($prod_cat)){
            for($i=0;$i<count($prod_cat);$i++){
                $cat_id = $prod_cat[$i]['id'];
                $cat_name = $prod_cat[$i]['cat_name'];
                $template=$prod_cat[$i]['template']; $excelfile=$prod_cat[$i]['excelFile'];
                $cattype=$prod_cat[$i]['type'];
                //$activfun = "active(".$cat_id.",'checkbox".$cat_id."','".addslashes($cattype)."')";
                $activfun = 'active("'.$cat_id.'","checkbox'.$cat_id.'","'.addslashes($cattype).'")';
                $excelfunc =  " onclick=createexcel('".$cat_id."','".$template."')";
                if($prod_cat[$i]['isActive'] == 1){ $checkvalue = "checked"; } else{ $checkvalue = "";  }
                $checkbox=""; $cntstr="";
                if($cattype == "Parent"){
                    $internal = "masterlist"; $classd = 'parent-category mb-2';
                    $str = "'".$cat_id."','".addslashes($cat_name)."','Master'";
                }
                else if($cattype == 'Master'){ $internal = " sublist";  $classd = 'master-category sub-cat-body mb-2';$str = "'".$cat_id."','".addslashes($cat_name)."','Sub'";  $add_child = '<button type="button" class="btn btn-default"  onclick ="openmodel('.$str.')"><i class="fa fa-plus" aria-hidden="true"></i></button>' ; }
                else if($cattype == 'Sub'){ $classd = 'sub-category border-bottom cc-margin-bottom-0';
                    $getProdcnt=selectQuery(PRODINFO,"count(id) as prodcnt","parent_id=0 and sub_cat=".$cat_id." and isActive=1");
                    $cntstr=" (".$getProdcnt[0]['prodcnt']." products)";
                }
                $menu .="<div class='menu-catgory-panel ".$classd."' data-id='".$cat_id."' >
                <div class='mng-cat-head'> <div class='row'><div class='col-8 menu-cat-title snehalclass cc-cursor-pointer' onclick='toggle(".$cat_id.");".($cattype == 'Sub'?'getprod('.$cat_id.')':'')."' id=".$cat_id." >".$checkbox." ".$cat_name." ".$cntstr."</div>
                <div class='col-1'></div>
                <div class='text-right col-3 check-".$cat_id."'></div>
                <div class='cat-head-elements'></div></div></div><div class='menu-catgory cc-display-none border border-top-0 ".$internal."'>".get_cat_tree3($cat_id)."</div>
                </div>";
            }
        }
        return $menu;
    } ?>
    <div><h6 class="mt-3 mb-3 cc-font-weight-5">Open Tree Structure To Select Products</h6><?php echo get_cat_tree3(0); ?></div>
    <? }else if($aplon=="All"){} }
    if($action == "getProdDataForEdit"){
        $aplon = $_POST['applicableOn'];
        $couponId = $_POST['couponId'];
        $getCoupon = selectQuery(COUPON,"applicableOn,applicableId","couponId=".$couponId);
        $ids = $getCoupon[0]['applicableId'];
        $idarr = explode(",",$ids);
        if($aplon=="Subcategory"){
            function get_cat_tree2($parent_id,$ids=null){
            $idarr = explode(",",$ids);        
            $menu = "";
            $prod_cat = selectQuery(PRODCAT,"id,type,cat_name","parent_id=".$parent_id."  order by priority");
            if(count($prod_cat)){
                for($i=0;$i<count($prod_cat);$i++){
                    $cat_id = $prod_cat[$i]['id'];
                    $cat_name = $prod_cat[$i]['cat_name'];
                    $template = $prod_cat[$i]['template']; $excelfile=$prod_cat[$i]['excelFile'];
                    $cattype = $prod_cat[$i]['type'];
                    //$activfun = "active(".$cat_id.",'checkbox".$cat_id."','".addslashes($cattype)."')";
                    $activfun = 'active("'.$cat_id.'","checkbox'.$cat_id.'","'.addslashes($cattype).'")';
                    $excelfunc = " onclick=createexcel('".$cat_id."','".$template."')";
                    if($prod_cat[$i]['isActive'] == 1){ $checkvalue = "checked"; }else { $checkvalue = "";  }
                    $checkbox="";
                    if($cattype == "Parent"){
                        $internal = "masterlist "; $classd = 'parent-category mb-2';
                        $str = "'".$cat_id."','".addslashes($cat_name)."','Master'";
                    }
                    else if($cattype == 'Master'){ $internal = " sublist";  $classd='master-category sub-cat-body mb-2';$str = "'".$cat_id."','".addslashes($cat_name)."','Sub'"; $add_child = '<button type="button" class="btn btn-default"  onclick ="openmodel('.$str.')"><i class="fa fa-plus" aria-hidden="true"></i></button>' ;  }
                    else if($cattype == 'Sub'){ $classd = 'sub-category border-bottom cc-margin-bottom-0';
                        $checkbox="<input type='checkbox' class='mr-1' name='products' value='".$cat_id."' ".(in_array($cat_id,$idarr)?'checked':'').">   ";
                    }
                    $menu .="<div class='menu-catgory-panel ".$classd."'  data-id='".$cat_id."' >
                    <div class='mng-cat-head'> ";
                    $menu .="<div class='row'>";
                    $menu .="<div class='col-8 menu-cat-title snehalclass cc-cursor-pointer' onclick='toggle(".$cat_id.")' id=".$cat_id." >".$checkbox." ".$cat_name."</div>
                    ";
                    $menu .="<div class='cat-head-elements'></div>";
                    $menu .=" </div> ";
                    $menu .=" </div> ";
                    $menu .="<div class='menu-catgory cc-display-none row ".$internal."' >".get_cat_tree2($cat_id,$ids)."</div>";
                    $menu .="</div>";
                }
            }
            return $menu;
        } ?>
        <div><h6 class="mt-3 mb-3 cc-font-weight-5">Open Tree Structure To Select Subcategory</h6><?php echo get_cat_tree2(0,$ids); ?></div>
        <? }else if($aplon=="Product"){
        function get_cat_tree3($parent_id,$ids){
            $menu = ""; $idarr = explode(",",$ids);
            $prod_cat = selectQuery(PRODCAT,"id,type,cat_name","parent_id=".$parent_id."  order by priority");
            if(count($prod_cat)){
                for($i=0;$i<count($prod_cat);$i++){
                    $cat_id = $prod_cat[$i]['id']; $mystr = "";
                    $cat_name = $prod_cat[$i]['cat_name'];
                    $template = $prod_cat[$i]['template']; $excelfile=$prod_cat[$i]['excelFile'];
                    $cattype = $prod_cat[$i]['type'];
                    //$activfun = "active(".$cat_id.",'checkbox".$cat_id."','".addslashes($cattype)."')";
                    $activfun = 'active("'.$cat_id.'","checkbox'.$cat_id.'","'.addslashes($cattype).'")';
                    $excelfunc = " onclick=createexcel('".$cat_id."','".$template."')";
                    if($prod_cat[$i]['isActive'] == 1) { $checkvalue = "checked"; }else { $checkvalue = "";  }
                    $checkbox=""; $cntstr="";
                    if($cattype == "Parent"){
                        $internal = " masterlist "; $classd = 'parent-category mb-2';
                        $str = "'".$cat_id."','".addslashes($cat_name)."','Master'";
                    }
                    else if($cattype == 'Master'){ $internal = " sublist";  $classd = 'master-category sub-cat-body mb-2';$str = "'".$cat_id."','".addslashes($cat_name)."','Sub'"; $add_child = '<button type="button" class="btn btn-default" onclick ="openmodel('.$str.')"><i class="fa fa-plus" aria-hidden="true"></i></button>' ;  }
                    else if($cattype == 'Sub'){ $classd = 'sub-category border-bottom cc-margin-bottom-0';
                        $getProdcnt=selectQuery(PRODINFO,"count(id) as prodcnt","parent_id=0 and sub_cat=".$cat_id." and isActive=1");
                        $cntstr=" (".$getProdcnt[0]['prodcnt']." products)";
                        $getSelectedProducts=selectQuery(PRODINFO,"id,prod_name","parent_id=0 and sub_cat=".$cat_id);
                        for($p=0;$p<count($getSelectedProducts);$p++){
                            $str="<div class='col-md-6'><div class='custom-control custom-checkbox mb-2'><input type='checkbox' class='mr-1 custom-control-input products-".$getSelectedProducts[$p]['id']."' id='products-".$getSelectedProducts[$p]['id']."' name='products' value='".$getSelectedProducts[$p]['id']."' ".(in_array($getSelectedProducts[$p]['id'],$idarr)?'checked':'')."><label class='custom-control-label' for='customCheck'>".$getSelectedProducts[$p]['prod_name']."</label></div></div>";
                            $mystr.= $str;
                        }
                    } 
                    $menu .="<div class='menu-catgory-panel ".$classd."' data-id='".$cat_id."' >
                    <div class='mng-cat-head'><div class='row'><div class='col-8 menu-cat-title snehalclass cc-cursor-pointer' onclick='toggle(".$cat_id.");".($cattype == 'Sub'?'getprod('.$cat_id.')':'')."' id=".$cat_id." >".$checkbox." ".$cat_name." ".$cntstr."</div>
                    <div class='col-1'></div>
                    <div class='text-right col-3 check-".$cat_id."'></div>
                    <div class='cat-head-elements'></div></div></div><div class='menu-catgory cc-display-none border border-top-0 ".$internal."' >".$mystr.get_cat_tree3($cat_id,$ids)."</div>
                    </div>";
                }
            }
            return $menu;
        } ?>
        <div><h6 class="mt-3 mb-3 cc-font-weight-5">Open Tree Structure To Select Products</h6><?php echo get_cat_tree3(0,$ids); ?></div>
        <? }else if($aplon=="All"){} 
    }
if($action=="getSubCatProd"){
    $subcat = $_POST['subcatid'];
    $getProd = selectQuery(PRODINFO,"id,prod_name","parent_id=0 and sub_cat=".$subcat." and isActive=1");
    echo json_encode($getProd);
}
if($action=="checkCouponCode"){
    $couponcode = $_POST['couponcode'];
    $getcpcode = selectQuery(COUPON,"couponCode","couponCode='".strtoupper($couponcode)."'");
    echo count($getcpcode);
}
if($action=="addCoupon"){
    $couponcode = $_POST['couponcode'];
    $coupon_description = $_POST['coupon_description'];
    $discType = $_POST['discType'];
    $discvalue = $_POST['discvalue'];
    $fromdt = $_POST['fromdt'];
    $todt = $_POST['todt'];
    $minorder = $_POST['minorder'];
    $ordminval = $_POST['ordminval'];
    $userlimit = $_POST['userlimit'];
    $applyon = $_POST['applyon'];
    $prodstring = $_POST['prodstring'];
    $data=array("couponCode"=>strtoupper(addslashes($couponcode)),"description"=>addslashes($coupon_description),"validFrom"=>date("Y-m-d H:i:s",strtotime($fromdt)),"validTill"=>date("Y-m-d H:i:s",strtotime($todt)),"showToAll"=>$showAll,
    "discountType"=>$discType,"discountValue"=>$discvalue,"minOrderValueRequire"=>$minorder,"minOrderValue"=>$ordminval,"limitPerUser"=>$userlimit,"applicableOn"=>$applyon,"applicableId"=>$prodstring);
    $indata=insertQuery(COUPON,$data);
    echo $indata;
}
if($action=="editCoupon"){
    $couponId = $_POST['couponId'];
    $couponcode = $_POST['couponcode'];
    $coupon_description = $_POST['coupon_description'];
    $discType = $_POST['discType'];
    $discvalue = $_POST['discvalue'];
    $fromdt = $_POST['fromdt'];
    $todt = $_POST['todt'];
    $minorder = $_POST['minorder'];
    $ordminval = $_POST['ordminval'];
    $userlimit = $_POST['userlimit'];
    $applyon = $_POST['applyon'];
    $prodstring = $_POST['prodstring'];
    $showAll=$_POST['showAll'];
    $data = array("couponCode"=>strtoupper(addslashes($couponcode)),"description"=>addslashes($coupon_description),"validFrom"=>date("Y-m-d H:i:s",strtotime($fromdt)),"validTill"=>date("Y-m-d H:i:s",strtotime($todt)),"showToAll"=>$showAll,
    "discountType"=>$discType,"discountValue"=>$discvalue,"minOrderValueRequire"=>$minorder,"minOrderValue"=>$ordminval,"limitPerUser"=>$userlimit,"applicableOn"=>$applyon,"applicableId"=>$prodstring);
    $indata=updateQuery(COUPON,$data,"couponId=".$couponId);
    echo "1";
}
if($action=="delete_coupon"){
    $couponId = $_POST['couponId'];
    $indata = deleteQuery(COUPON,"couponId=".$couponId);
    if($indata){echo "1";}else{echo "0";}
} ?>
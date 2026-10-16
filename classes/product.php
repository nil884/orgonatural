<?
class Product{
    public function __construct(){}
    /* to delete product/inventory from product id*/
    public function deleteProd($id){
        $getproduct_template =  selectQuery(PRODINFO,"sub_cat","id = ".$id);
        $getvar = selectQuery(PRODINFO,"id","parent_id = ".$id);
        $getsbcatdetails = selectQuery(PRODCAT,"template","id=".$getproduct_template[0]['sub_cat']);
        $template = $getsbcatdetails[0]['template'];

        if(count($getvar)==0){//single prod
          deleteQuery ($template, "prod_id = ".$id);
          deleteQuery(PRODIMG, "prod_id = ".$id);
          deleteQuery(CART,"prod_id=" . $id);
        $del=  deleteQuery(PRODINFO,"id=" . $id);
        }else{  //variation
            for($i=0;$i<count($getvar);$i++){
                $varid=$getvar[$i]['id'];
                deleteQuery ($template, "prod_id = ".$varid);
                deleteQuery(PRODIMG, "prod_id = ".$varid);
              deleteQuery(PRODINFO,"id=" . $varid);
              deleteQuery(CART,"prod_id=" . $varid);
            }
          $del= deleteQuery(PRODINFO,"id=" . $id);
        }
           /* deleteQuery(PRODINFO,"parent_id=" . $id);
        $del=deleteQuery(PRODINFO, "id=" . $id);*/
        if($del){return 1;}else{return 0;}
    }

 /* this function is use to get template name from subcategory id */
   public function getTemplate($subcat){
         $chktemp=selectQuery(PRODCAT,"template","id=".$subcat);
          if(count($chktemp)){ return $chktemp[0]['template']; }
          else{ return ""; }
    }

  /* this function is usefull for bulk uplod purpose -   */
    public function checkprod($prodname,$seller,$id=NULL){
        $chkprod=selectQuery(PRODINFO,"prod_name","prod_name='".addslashes(trim($prodname))."' and vendor=".$seller);
          if(($id==0||$id=="")&&count($chkprod)){ return 0; }
          else{ return 1;}
    }

  /* this function is use to get parent category id of category */
  public function getParentCat($cat){
      $chkcat=selectQuery(PRODCAT,"parent_id","id=".$cat);
        return $chkcat[0]['parent_id'];
   }

    /* this function is use to get parent product of product from product id and vendor id */
   public function getParentProd($prod,$uploader){
      $chkcat=selectQuery(PRODINFO,"id","prod_name='".$prod."' and vendor='".$uploader."' and parent_id='0'");
        return $chkcat[0]['id'];
    }
    /* this function is use to get  product id of product from product name and vendor id */
   public function getProductId($prod,$uploader){
        $chkcat=selectQuery(PRODINFO,"id","prod_name='".$prod."' and vendor=".$uploader);
        return $chkcat[0]['id'];
    }

   /* this function is use to check is category is available or not */
   public function checkCat($cat){
        $chkcat=selectQuery(PRODCAT,"cat_name","id=".$cat);
          if(count($chkcat)){  return "Valid";}
          else{  return "Invalid";}
    }

   public function check_image($imgname,$mainprodid){
        $inimg=selectQuery(PRODIMG,"img_name","img_url='".$imgname."' and prod_id='".$mainprodid."'");
        if(count($inimg)){return 0;}else{return 1;}
    }

      public function getProductsGroupCount($where=NULL){
        if($where!=""){$where=" and ".$where;}
        $getdata=selectQuery(PRODINFO,"count(id) as prodcount","isActive=1 and isApproved=1 and parent_id=0 ".$where);
        if(count($getdata)){return $getdata[0]['prodcount'];}else{return 0;}
    }

    public function getcount($where=NULL,$prodfilter=NULL,$templatefilter=NULL,$template=NULL){
      if($where!=""){$where=" and ".($templatefilter!=""?"":"").$where;}
      if($prodfilter!=""){$where.=" and ".$prodfilter;}
      if($templatefilter!=""){$tempwhere=" and ".$templatefilter;}

      if($templatefilter==""){
         $getdata=simpleQuery("select p.id,p.final_price from ".PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id where p.final_price<>'' and  p.isActive='1' and p.isApproved='1' and p.parent_id=0 and pc.isActive='1' and mc.isActive='1' and sc.isActive='1' ".$where." UNION
          select p.parent_id as id, p.final_price from ".PRODINFO."  as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id where  p.isActive=1 and p.isApproved=1 and p.parent_id<>0  and pc.isActive='1' and mc.isActive='1' and sc.isActive='1' ".$where." group by p.parent_id");
      }else{
          $getdata=simpleQuery("select p.id,p.final_price from ".PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id JOIN ".$template." as t on p.id=t.prod_id where p.final_price<>'' and  p.isActive=1 and p.isApproved=1 and p.parent_id=0 and pc.isActive='1' and mc.isActive='1' and sc.isActive='1' ".$where.$tempwhere."
           UNION
           select p.parent_id as id, p.final_price from ".PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id JOIN ".$template." as t on p.id=t.prod_id where p.isActive=1 and p.isApproved=1 and p.parent_id<>0 and pc.isActive='1' and mc.isActive='1' and sc.isActive='1'  ".$where.$tempwhere." group by p.parent_id");
      }
        return count($getdata);
    }

    public function getSkuCount(){
     
      $getdata1=simpleQuery("select id from ".PRODINFO." where parent_id=0 and variation_available='0'"); 

      $getdata2=simpleQuery("select id from ".PRODINFO." where parent_id<>0 "); 
      return count($getdata1)+ count($getdata2);
    }
    
public function getReview($prodid,$limitfrom=NULL,$limitto=NULL){
  if(isset($limitfrom)&&isset($limitto)){ $limit=" Limit ".$limitfrom.",".$limitto;}
      $getdata=selectQuery(REVIEW." as r JOIN ".BUYER." as b on r.user_id=b.u_id" ,"b.u_fname,r.review_id,r.review_title,r.review,r.rate,r.review_date","r.main_prod_id=".$prodid." and r.isApproved='1' and r.isActive = '1' order by r.priority ASC".$limit);
    
        return $getdata;
    }
   /* this function is used to get products by groups passed as parameter */
    public function getProductsByGroup($where=NULL,$limitfrom=NULL,$limitto=NULL,$ordering=NULL,$prodfilter=NULL,$templatefilter=NULL,$template=NULL){
      $limit="";

      if($where!=""){$where=" and ".($templatefilter!=""?"":"").$where;}
      if($prodfilter!=""){$where.=" and ".$prodfilter;}
      if($templatefilter!=""){$tempwhere=" and ".$templatefilter;}
      if(isset($limitfrom)&&isset($limitto)){ $limit=" Limit ".$limitfrom.",".$limitto;}
      if($ordering!=""){$ordering=" order by ".($templatefilter!=""?"":"").$ordering;}
      $data=array();

      if($templatefilter==""){
      
           $getdata=simpleQuery("select p.id,p.final_price,p.parent_id,p.variation_available from ".PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id  where  p.isActive='1' and p.isApproved='1' and p.parent_id=0 and p.variation_available='0'  and pc.isActive='1' and mc.isActive='1' and sc.isActive='1' ".$where." UNION
          select p.id, p.final_price,p.parent_id,p.variation_available from ".PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id  where p.isActive='1' and p.isApproved='1' and p.parent_id<>0  and pc.isActive='1' and mc.isActive='1' and sc.isActive='1' ".$where." group by p.parent_id
       ".$ordering.$limit);
      }else{
           $getdata=simpleQuery("select p.id,p.final_price,p.parent_id,p.variation_available from ".PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id JOIN ".$template." as t on p.id=t.prod_id where p.isActive='1' and p.isApproved='1' and  p.parent_id=0 and p.variation_available='0'  and pc.isActive='1' and mc.isActive='1' and sc.isActive='1' ".$where.$tempwhere."
           UNION  select p.id, p.final_price,p.parent_id,p.variation_available from ".PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id  LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id  LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id JOIN ".$template." as t on p.id=t.prod_id where  p.parent_id<>0  and p.isActive='1' and p.isApproved='1'  and pc.isActive='1' and mc.isActive='1' and sc.isActive='1'  ".$where.$tempwhere." group by p.parent_id
           ".$ordering.$limit);
      }

         /* addon created for testing */
         for($i=0;$i<count($getdata);$i++){
             $getid=$getdata[$i]['id'];
             $parent_id=$getdata[$i]['parent_id'];
             if($parent_id!=0){
            $variations=$this->getProductVariationByGroup($parent_id);
            }else{$variations=$this->getProductVariationByGroup($getid);  }
            $currentvariations=$this->getProductVariation($getid);
          $productData=$this->getShortDetails($getid);
             $isvariation= $productData[0]['variation_available'];
            if($productData[0]['parent_id']==0){ $name=$productData[0]['prod_name']; }else{ $name=$this->getParentName($getid); }
           /*  if($isvariation==1){
                   $prodvariations=$this->getProductVariationForDisplay($getid);
                   $idtopass= $prodvariations[0]['id'];
                  $prodimg=$this->getProductImageForDisplay($idtopass);
                   $price= $this->getProductPrice($idtopass);
             }else{*/
                 $idtopass= $getid;
               $prodimg=$this->getProductImageForDisplay($idtopass);
               $price= $this->getProductPrice($idtopass);
            /* } */
             if(count($prodimg)){$img=$prodimg[0]['img_name'];}else{$img="";}
             $subarr=array("id"=>$idtopass,"parent"=>$parent_id,"name"=>$name,"image"=>$img,"priceon"=>$price['priceon'],"price"=>$price['price'],"priceWithGst"=>$price['priceWithGst'],"priceWithoutGst"=>$price['priceWithoutGst'],"off"=>$price['off'],"mrp"=>$price['mrp'],"stock" => $price['stock'] ,"variations"=>$variations,"currentVariations"=>$currentvariations  );
             array_push($data,$subarr);
         }
          /* addon eanhd for testing */
        if(count($data)){return $data;}else{return 0;}
    }
    
       public function getProductPrice($id){
       $getdata=selectQuery(PRODINFO,"final_price,mrp,vendor_sale_start_date,vendor_sale_end_date,admin_price,vendor_sale_price,stock,sold,blocked,tax","id='".$id."'");
       $salestart=$getdata[0]['vendor_sale_start_date'];$saleend=$getdata[0]['vendor_sale_end_date'];
        $admin_price=$getdata[0]['admin_price'];  $sale_price=$getdata[0]['vendor_sale_price']; $mrp=$getdata[0]['mrp'];$tax=$getdata[0]['tax'];
       // $taxonmrp=ceil(($mrp*($tax/(100+$tax))));
       $taxonmrp=($mrp*($tax/(100+$tax)));

        $mrpWithoutGst=round($mrp-$taxonmrp);
        if(($salestart!=""&&$saleend!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($salestart))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($saleend)))){
          // $priceWithGst= ceil($sale_price+round((($sale_price/100)*$tax),2));  $priceWithoutGst=$sale_price;
             $priceon="Vendor Price";
             $priceWithGst = $sale_price;
             $taxonsaleprice = round(($sale_price*($tax/(100+$tax))));
             $priceWithoutGst = round($sale_price-$taxonsaleprice);
 
         }
       else if($admin_price!=0&&$admin_price!=""){
           //$priceWithGst=ceil($admin_price+round((($admin_price/100)*$tax),2)); $priceWithoutGst=$admin_price;
             $priceon="Admin Price";
             $priceWithGst = $admin_price;
             $taxonadminprice=round(($admin_price*($tax/(100+$tax))));
             $priceWithoutGst = round($admin_price-$taxonadminprice);

       }else{

           $priceWithoutGst=round($mrp-$taxonmrp);  $priceWithGst= $mrp;  $priceon="MRP";    

       }
       if($priceWithGst!=$mrp){
         $disc= $mrp- $priceWithGst;  $perc= ($disc/$mrp)*100;
       }else{ $perc=0;
       }
        $data=array("priceon"=>$priceon,"price"=>$priceWithGst,"priceWithGst"=>$priceWithGst,"priceWithoutGst"=>$priceWithoutGst,"mrp"=>$mrp,"mrpwithoutgst"=>$mrpWithoutGst,"off"=>ceil($perc),"stock"=>$getdata[0]['stock']-($getdata[0]['sold']+$getdata[0]['blocked']),"tax"=>$getdata[0]['tax']);

       return $data;

    }
       /* public function getProductPrice($id){
       $getdata=selectQuery(PRODINFO,"final_price,mrp,vendor_sale_start_date,vendor_sale_end_date,admin_price,vendor_sale_price,stock,sold,tax","id='".$id."'");
       $salestart=$getdata[0]['vendor_sale_start_date'];$saleend=$getdata[0]['vendor_sale_end_date'];
        $admin_price=$getdata[0]['admin_price'];  $sale_price=$getdata[0]['vendor_sale_price']; $mrp=$getdata[0]['mrp'];$tax=$getdata[0]['tax'];
        $taxonmrp=ceil(($mrp*($tax/(100+$tax))));
        $mrpWithoutGst=ceil($mrp-$taxonmrp);
       if($admin_price!=0&&$admin_price!=""){
           $priceWithGst=ceil($admin_price+round((($admin_price/100)*$tax),2)); $priceWithoutGst=$admin_price;   $priceon="Admin Price";
       }else{
        
         if(($salestart!=""&&$saleend!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($salestart))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($saleend)))){
          $priceWithGst= ceil($sale_price+round((($sale_price/100)*$tax),2));  $priceWithoutGst=$sale_price;      $priceon="Vendor Price";
        }else{  $priceWithoutGst=ceil($mrp-$taxonmrp);  $priceWithGst= $mrp;  $priceon="MRP";    }
       }
       if($priceWithoutGst!=$mrpWithoutGst){
         $disc= $mrpWithoutGst- $priceWithoutGst;  $perc= ($disc/$mrpWithoutGst)*100;
       }else{ $perc=0;
       }
       $data=array("priceon"=>$priceon,"price"=>$priceWithoutGst,"priceWithGst"=>$priceWithGst,"priceWithoutGst"=>$priceWithoutGst,"mrp"=>$mrpWithoutGst,"off"=>ceil($perc),"stock"=>$getdata[0]['stock']-$getdata[0]['sold'],"tax"=>$getdata[0]['tax']);
       return $data;
    } */
  /*    public function getProductPrice($id){
       $getdata=selectQuery(PRODINFO,"final_price,mrp,vendor_sale_start_date,vendor_sale_end_date,admin_price,vendor_sale_price,stock,sold,tax","id='".$id."'");
       $salestart=$getdata[0]['vendor_sale_start_date'];$saleend=$getdata[0]['vendor_sale_end_date'];
        $admin_price=$getdata[0]['admin_price'];  $sale_price=$getdata[0]['vendor_sale_price']; $mrp=$getdata[0]['mrp'];$tax=$getdata[0]['tax'];

       if($admin_price!=0&&$admin_price!=""){
           $priceWithGst=ceil($admin_price+round((($admin_price/100)*$tax),2)); $priceWithoutGst=$admin_price;
       }else{
         if(($salestart!=""&&$saleend!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($salestart))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($saleend)))){
          $priceWithGst= ceil($sale_price+round((($sale_price/100)*$tax),2));  $priceWithoutGst=$sale_price;
        }else{  $priceWithGst= $mrp; $priceWithoutGst=$mrp;     }
       }
       if($priceWithGst!=$mrp){
         $disc= $mrp- $priceWithGst;  $perc= ($disc/$mrp)*100;
       }else{ $perc=0;
       }
       $data=array("price"=>$priceWithoutGst,"priceWithGst"=>$priceWithGst,"priceWithoutGst"=>$priceWithoutGst,"mrp"=>$mrp,"off"=>ceil($perc),"stock"=>$getdata[0]['stock']-$getdata[0]['sold'],"tax"=>$getdata[0]['tax']);
       return $data;
    }*/

    public function getVendorProductPriceForOrder($id){
      $getdata=selectQuery(PRODINFO,"vendor_reg_price,vendor_sale_price,vendor_sale_start_date,vendor_sale_end_date","id='".$id."'");
        $salestart=$getdata[0]['vendor_sale_start_date'];$saleend=$getdata[0]['vendor_sale_end_date'];  $sale_price=$getdata[0]['vendor_sale_price'];
      if(($salestart!=""&&$saleend!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($salestart))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($saleend)))){
          $price= $sale_price;
        }else{  $price= $getdata[0]['vendor_reg_price']; }
        return $price;
    }
    /* this function is used to get variations id of main product */
     public function getProductVariationForDisplay($id){
        $getdata=selectQuery(PRODINFO,"id","parent_id=".$id." order by id ASC");
        if(count($getdata)){return $getdata;}else{return 0;}
    }

     public function getProductImageForDisplay($id){
        $inimg=selectQuery(PRODIMG,"img_name","prod_id='".$id."' order by priority ASC,id DESC");
        
        return $inimg;
    }

    /* this function is used to show all variations of product as per group
     Ex Color=>red,blue
        Size : s,xl
    */
      public function getProductVariationByGroup($id){
        $data=array();
        $getdata=selectQuery(PRODINFO,"variation_available","id=".$id);
        if($getdata[0]['variation_available']==1){
           $getvars=selectQuery(PRODINFO,"variant_name1,variant_name2,variant_name3,variant_value1,variant_value2,variant_value3","parent_id=".$id." and isActive=1");
           for($i=0;$i<count($getvars);$i++){
            $variant1=($getvars[$i]['variant_name1']!=""?getOriginalName($getvars[$i]['variant_name1']):"");
            $variant2=($getvars[$i]['variant_name2']!=""?getOriginalName($getvars[$i]['variant_name2']):"");
            $variant3=($getvars[$i]['variant_name3']!=""?getOriginalName($getvars[$i]['variant_name3']):"");
            if($variant1!=""&&!array_key_exists($variant1, $data)){ $data[$variant1]=array(); }
            if($variant2!=""&&!array_key_exists($variant2, $data)){ $data[$variant2]=array(); }
            if($variant3!=""&&!array_key_exists($variant3, $data)){ $data[$variant3]=array(); }
             $variantval1=$getvars[$i]['variant_value1'];$variantval2=$getvars[$i]['variant_value2'];$variantval3=$getvars[$i]['variant_value3'];
             if($variantval1!=""){ array_push($data[$variant1],$variantval1); }
             if($variantval2!=""){ array_push($data[$variant2],$variantval2); }
             if($variantval3!=""){ array_push($data[$variant3],$variantval3); }
           }

        }else{
            $variant1=( isset($getdata[0]['variant_name1'])?getOriginalName($getdata[0]['variant_name1']):"");
            $variant2=( isset($getdata[0]['variant_name2'])?getOriginalName($getdata[0]['variant_name2']):"");
            $variant3=( isset($getdata[0]['variant_name3'])?getOriginalName($getdata[0]['variant_name3']):"");
            if($variant1!=""&&!array_key_exists($variant1, $data)){ $data[$variant1]=array(); $variantval1=$getdata[0]['variant_value1']; }
            if($variant2!=""&&!array_key_exists($variant2, $data)){ $data[$variant2]=array(); $variantval2=$getdata[0]['variant_value2']; }
            if($variant3!=""&&!array_key_exists($variant3, $data)){ $data[$variant3]=array(); $variantval3=$getdata[0]['variant_value3'];}
          
             if( isset($variantval1)){ array_push($data[$variant1],$variantval1); }
             if(isset($variantval2)){ array_push($data[$variant2],$variantval2); }
             if(isset($variantval3)){ array_push($data[$variant3],$variantval3); }
        }
         return $data;
    }

    public function getProductVariation($id){
      $data=array();
      
         $getvars=selectQuery(PRODINFO,"variant_name1,variant_name2,variant_name3,variant_value1,variant_value2,variant_value3","id=".$id." and isActive=1");
         for($i=0;$i<count($getvars);$i++){
          $variant1=($getvars[$i]['variant_name1']!=""?getOriginalName($getvars[$i]['variant_name1']):"");
          $variant2=($getvars[$i]['variant_name2']!=""?getOriginalName($getvars[$i]['variant_name2']):"");
          $variant3=($getvars[$i]['variant_name3']!=""?getOriginalName($getvars[$i]['variant_name3']):"");
          if($variant1!=""&&!array_key_exists($variant1, $data)){ $data[$variant1]=array(); }
          if($variant2!=""&&!array_key_exists($variant2, $data)){ $data[$variant2]=array(); }
          if($variant3!=""&&!array_key_exists($variant3, $data)){ $data[$variant3]=array(); }
           $variantval1=$getvars[$i]['variant_value1'];$variantval2=$getvars[$i]['variant_value2'];$variantval3=$getvars[$i]['variant_value3'];
           if($variantval1!=""){ array_push($data[$variant1],$variantval1); }
           if($variantval2!=""){ array_push($data[$variant2],$variantval2); }
           if($variantval3!=""){ array_push($data[$variant3],$variantval3); }
         }

       return $data;
  }

    /* get product id from url  */
    public function getProductIdFromURL($url){
        $getdata=selectQuery(PRODINFO,"id","url_title='".$url."'");
        if(count($getdata)){return $getdata[0]['id'];}else{return 0;}
    }

     public function getShortDetails($id){
        $getdata=selectQuery(PRODINFO,"prod_name,parent_id,variation_available,prod_company,parent_cat,sub_cat,master_cat,vendor,is_cod_avail","id=".$id);
        if(count($getdata)){return $getdata;}else{return 0;}
    }

     public function getParentName($id){
        $getdata=selectQuery(PRODINFO,"parent_id,prod_name","id=".$id);
        if($getdata[0]['parent_id']!=0){
          $getparent=selectQuery(PRODINFO,"prod_name","id=".$getdata[0]['parent_id']);
          return $getparent[0]['prod_name'];
        }else{ return $getdata[0]['prod_name']; }
    }

    /*public function getVariationsOfProd($id){
        $getdata=selectQuery(PRODINFO,"img_name","id='".$id."' order by id ASC");
        if(count($getdata)){return $getdata;}else{return 0;}
    }*/

     public function createCatURL($name,$parent){
        $parentdata=selectQuery(PRODCAT,"cat_name,parent_id","id=".$parent);
        $url=createurltitle($name);
        $checkurl=selectQuery(PRODCAT,"count(id) as urlcount","url_title='".$url."'");
        if($checkurl[0]['urlcount']==0){
           return $url;
        }else{
            $newname=$parentdata[0]['cat_name']." ".$name;
           return  $this->createCatURL($newname,$parentdata[0]['parent_id']);
        }
    }

    public function createProdURL($name,$subcat,$id=NULL){
         $parentdata=selectQuery(PRODCAT,"url_title","id=".$subcat);
        $url=createurltitle($name); $where="";
        if($id!=""&&$id!="0"){ $where=" AND id<>".$id; }
        $checkurl=selectQuery(PRODINFO,"count(id) as urlcount","url_title='".$url."'".$where);
        if($checkurl[0]['urlcount']==0){
            return $url;
        }else{
            $newname=$parentdata[0]['url_title']." ".$name;
           return $this->createProdURL($newname,$subcat);
        }
    }

    /* to get category deatils from url */
    public function getcategoryDetails($urltitle){
       $data=selectQuery(PRODCAT,"id,cat_name,parent_id,page_title,metadescription,keywords,template,url_title","url_title='".$urltitle."'");
        return $data;
    }

     public function getCategoryName($id){
       $data=selectQuery(PRODCAT,"cat_name","id=".$id);
        return $data[0]['cat_name'];
    }

     public function getProductSeo($id){
       $data=selectQuery(PRODINFO,"seo_title,seo_description,seo_keywords","id=".$id);
        return $data;
    }

     public function getProductFullDetails($id){
         $getshortdetails=$this->getShortDetails($id);
         $prodname=($getshortdetails[0]['parent_id']==0?$getshortdetails[0]['prod_name']:$this->getParentName($id));
         $variations=$this->getProductVariationByGroup($getshortdetails[0]['parent_id']==0?$id:$getshortdetails[0]['parent_id']);
         $price= $this->getProductPrice($id);
         $prodimg=$this->getProductImageForDisplay($id);
         $seo=$this->getProductSeo($id);
         $currentvariations=$this->getProductCurrentVariations($id);
         $gettemplatedata=$this->getTemplateData($id);
          $getvendor=$this->getVendor($id);
        $data=array("id"=>$id,"name"=>$prodname,"company"=>$getshortdetails[0]['prod_company'],"parent_cat_id" => $getshortdetails[0]['parent_cat'] , "parent_cat"=> $this->getCategoryName($getshortdetails[0]['parent_cat']) , "master_cat_id" => $getshortdetails[0]['master_cat'] , "master_cat"=>$this->getCategoryName($getshortdetails[0]['master_cat']), "sub_cat_id" => $getshortdetails[0]['sub_cat'] ,"sub_cat"=>$this->getCategoryName($getshortdetails[0]['sub_cat']),"vendorId"=>$getshortdetails[0]['vendor'],"vendor"=>$getvendor,"image"=>$prodimg,"price"=>$price['price'],"withGst"=>$price['priceWithGst'],"withoutGst"=>$price['priceWithoutGst'],"off"=>$price['off'],"mrp"=>$price['mrp'],"stock"=>$price['stock'],"tax"=>$price['tax'] ,"seo_title"=>$seo[0]['seo_title'],"seo_description"=>$seo[0]['seo_description'],"seo_keywords"=>$seo[0]['seo_keywords'],"variations"=>$variations,"currentVariartions"=>$currentvariations,"templateData"=>$gettemplatedata,"is_cod_avail"=>$getshortdetails[0]['is_cod_avail']);
        return $data;
    }

    public function getVendor($id){
        $getvendor=selectQuery(PRODINFO." as p JOIN ".VENDOR." as v on p.vendor=v.dealer_id","nickname","p.id=".$id);
         return $getvendor[0]['nickname'];
    }
     public function getVendorDetails($id){
        $getvendor=selectQuery(VENDOR,"nickname,city,dealer_name,city,email,personalcontactno,disbursement_cycle_days,vatno","dealer_id=".$id);
         return $getvendor;
    }

     public function getVendorFld($id,$fld){
        $getvendor=selectQuery(VENDOR,$fld,"dealer_id=".$id);
         return $getvendor[0][$fld];
    }

    public function getProductCurrentVariations($id){
          $data=selectQuery(PRODINFO,"variant_value1,variant_value2,variant_value3","id=".$id);
          $vararr=array();
          if($data[0]['variant_value1']!="") array_push($vararr,$data[0]['variant_value1']);
          if($data[0]['variant_value2']!="") array_push($vararr,$data[0]['variant_value2']);
          if($data[0]['variant_value3']!="") array_push($vararr,$data[0]['variant_value3']);
        return $vararr;
    }

     /* *********************get template data for product **************************** */
   public function getTemplateData($id){
       $templateData= array();
      $gettemplate=selectQuery(PRODINFO." as p JOIN ".PRODCAT." as c on p.sub_cat=c.id","template","p.id=".$id);
      $template=$gettemplate[0]['template'];
      $gettemplatedata=selectQuery($template,"*","prod_id=".$id);
      if(isset($gettemplatedata) && count($gettemplatedata)) {
      $retval=showQuery($template);
      $templateData=array("highlight"=>$gettemplatedata[0]['highlight']); 
      $tablcol=array();
      for($i=0;$i<count($retval);$i++){ array_push($tablcol,$retval[$i][0]);   }
          for($i=3;$i<count($tablcol);$i++){
          $attribute=$tablcol[$i];  $value=$gettemplatedata[0][$attribute];
         $category= getAttributeCat($attribute);
         if(!isset($templateData[$category])){
             $templateData[$category]=array();
         }
         $templateData[$category][$attribute]=$value;
      }
      }
      return $templateData;
            
   }

    /* *********************get filters for list page**************************** */
    public function getProductsFilters($filterFor,$id){
       $getbrands= selectQuery(PRODINFO,"distinct prod_company",$filterFor."=".$id." order by prod_company ASC");

      
       // $getminmax= selectQuery(PRODINFO,"MAX(final_price+((final_price/100)*tax)) as maxprice",$filterFor."=".$id."");
        $getminmax= selectQuery(PRODINFO,"MAX(final_price) as maxprice",$filterFor."=".$id."");

      
        $data=array("company"=>array(),"price"=>array(0,$getminmax[0]['maxprice']));
        for($i=0;$i<count($getbrands);$i++){
           array_push($data['company'],$getbrands[$i]['prod_company']);
        }
         switch($filterFor){
             case "parent_cat":
               $catarr=array(); $getmasters= selectQuery(PRODINFO,"distinct master_cat",$filterFor."=".$id);
                for($i=0;$i<count($getmasters);$i++){array_push($catarr,$getmasters[$i]['master_cat']);}
              $getcats=selectQuery(PRODCAT,"cat_name","id IN(".implode(',',$catarr).") order by cat_name ASC");
                 $data['category']=array();
                  for($i=0;$i<count($getcats);$i++){
                       array_push($data['category'],$getcats[$i]['cat_name']);
                    }
             break;
              case "master_cat":
               $catarr=array(); $getmasters= selectQuery(PRODINFO,"distinct sub_cat",$filterFor."=".$id);
                for($i=0;$i<count($getmasters);$i++){array_push($catarr,$getmasters[$i]['sub_cat']);}
              $getcats=selectQuery(PRODCAT,"cat_name","id IN(".implode(',',$catarr).") order by cat_name ASC");
                 $data['subcategory']=array();
                  for($i=0;$i<count($getcats);$i++){
                       array_push($data['subcategory'],$getcats[$i]['cat_name']);
                    }
             break;
             case "sub_cat":
               $gettemplate=selectQuery(PRODCAT,"template","id=".$id);
               $template=$gettemplate[0]['template'];
               $showdata=showQuery($template);
               for($i=3;$i<count($showdata);$i++){
                   $field=$showdata[$i]['Field'];
                  // $oriname=getOriginalName( $field );
                   $data[$field]=array();
                    $getdata=selectQuery($template,"distinct ".$field,$field."<>'' order by ".$field." ASC");
                    for($j=0;$j<count($getdata);$j++){
                      array_push($data[$field],$getdata[$j][$field]);
                    }
               }

             break;
         }
           if($filterFor=="prod_company"){unset($data['company']); }

        return $data;
    }  
    public function getProductsFilters_search($where){
      $getbrands = selectQuery(PRODINFO,"distinct prod_company","prod_company <> '' and ".$where." and isActive= '1' and isApproved='1'  order by prod_company ASC");
      $getminmax= selectQuery(PRODINFO,"MAX(final_price+((final_price/100)*tax)) as maxprice","final_price <> ''and isActive= '1' and isApproved='1' and   ".$where."");
      
      $data=array("company"=>array(),"price"=>array(0,$getminmax[0]['maxprice']));
      for($i=0;$i<count($getbrands);$i++){
         array_push($data['company'],$getbrands[$i]['prod_company']);
      }
      return $data;
    }


    /* *********************get variation product id on variation selected by user**************************** */
    public function getProductVariationID($prodid,$variations){
        $getdata=selectQuery(PRODINFO,"parent_id","id=".$prodid);
        $vararr=json_decode($variations,true);
        $wharearr=array();
        $where="";
        for($i=0;$i<count($vararr);$i++){
           $v=$vararr[$i];
           $str="(variant_value1='".$v."' OR variant_value2='".$v."' OR variant_value3='".$v."')";
           array_push($wharearr,$str);
        }
        if(count($wharearr)){
         $where=" AND (".implode(" AND ",$wharearr).")";
        }

       $getid=selectQuery(PRODINFO,"id","parent_id=".$getdata[0]['parent_id']." ".$where);
       return $getid[0]['id'];
    }
     /* *********************Review**************************** */
      public function checkUserReviewForProd($prodid,$userid){
        $getdata=selectQuery(REVIEW,"count(review_id) as userProdReviewCnt","main_prod_id=".$prodid." and user_id=".$userid);
         return $getdata[0]['userProdReviewCnt'];
    }

    public function addReview($main_prod_id,$prodid,$userid,$reviewtitle,$reviewcomment,$rate=NULL){
        $data=array("main_prod_id"=>$main_prod_id,  "prod_id"=>$prodid,"user_id"=>$userid,"review_title"=>trim(addslashes($reviewtitle)),"review"=>trim(addslashes($reviewcomment)),"rate"=>$rate,"review_date"=>date("Y-m-d H:i:s"));
        $indata=insertQuery(REVIEW,$data);
        return ($indata?1:0);
    }

      public function getReviewCount($prodid){
       $getdata=selectQuery(REVIEW,"count(review_id) as reviewcnt","main_prod_id=".$prodid." and isApproved='1' and isActive= '1' ");
        return $getdata[0]['reviewcnt'];
    }

     /*public function getReview($prodid,$minlimit,$maxlimit){
      $getdata=selectQuery(REVIEW." as r JOIN ".BUYER." as b on r.user_id=b.u_id" ,"b.u_fname,r.review_id,r.review_title,r.review,r.rate,r.review_date","r.prod_id=".$prodid." and r.isApproved='1' order by r.review_id DESC limit ".$minlimit.",".$maxlimit);
        return $getdata;
    }*/

    public function getDeliverydetails($prodid){
      $getdata=selectQuery(PRODINFO,"vendor,weight,length,height,width","id=".$prodid);
        return $getdata;
    }

     public function getselectedAttr($prodid,$fields){
      $getdata=selectQuery(PRODINFO,$fields,"id=".$prodid);
        return $getdata;
    }

    public function removeFromCart($cartid,$prodid){
     deleteQuery(CART,"cart_id=".$cartid);
    foreach($_SESSION['shopping_cart'] as $key =>$value){
        if($value['prod_id'] == $prodid){
            unset($_SESSION['shopping_cart'][$key]);
            $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
        }
    }
       
    }
    
     public function removeFromWishlist($cartid,$prodid){
     deleteQuery(CART,"cart_id=".$cartid);
    foreach($_SESSION['wishitems'] as $key =>$value){
        if($value['prod_id'] == $prodid){
            unset($_SESSION['wishitems'][$key]);
            $_SESSION['wishitems'] = array_values($_SESSION['wishitems']);
        }
    }
       
    }

     public function getBreadcrumb($type,$id){
        $data=array("parent_id"=>"","parent"=>"","parent_url"=>"","master_id"=>"","master"=>"","master_url"=>"","sub_id"=>"","sub"=>"","sub_url"=>"");
        if($type=="sub_cat"){
            $getsubcatdata=selectQuery(PRODCAT,"cat_name,parent_id,url_title","id=".$id);
            $subcat=$getsubcatdata[0]['url_title'];  $data['sub']=$getsubcatdata[0]['cat_name']; $data['sub_url']= $subcat;$data['sub_id']= $id;
             $getmasterdata=selectQuery(PRODCAT,"cat_name,parent_id,url_title","id=".$getsubcatdata[0]['parent_id']);
            $mastercat=$getmasterdata[0]['url_title'];  $data['master']=$getmasterdata[0]['cat_name'];  $data['master_url']= $mastercat; $data['master_id']= $getsubcatdata[0]['parent_id'];
             $getparentdata=selectQuery(PRODCAT,"cat_name,url_title","id=".$getmasterdata[0]['parent_id']);
            $parentcat=$getparentdata[0]['url_title'];  $data['parent']=$getparentdata[0]['cat_name'];  $data['parent_url']= $parentcat; $data['parent_id']= $getmasterdata[0]['parent_id'];
        }else  if($type=="master_cat"){
            $getmasterdata=selectQuery(PRODCAT,"cat_name,parent_id,url_title","id=".$id);
            $mastercat=$getmasterdata[0]['url_title'];  $data['master']=$getmasterdata[0]['cat_name'];  $data['master_url']= $mastercat;  $data['master_id']= $id;
             $getparentdata=selectQuery(PRODCAT,"cat_name,url_title","id=".$getmasterdata[0]['parent_id']);
            $parentcat=$getparentdata[0]['url_title'];  $data['parent']=$getparentdata[0]['cat_name'];  $data['parent_url']= $parentcat;  $data['parent_id']= $getmasterdata[0]['parent_id'];
        }else  if($type=="parent_cat"){
           $getparentdata=selectQuery(PRODCAT,"cat_name,url_title","id=".$id);
            $parentcat=$getparentdata[0]['url_title'];  $data['parent']=$getparentdata[0]['cat_name']; $data['parent_url']= $parentcat;    $data['parent_id']= $id;
        }
        return $data;
    }
}

?>
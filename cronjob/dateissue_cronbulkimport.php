<?php
 include("../includes/configuration.php");
 include("../cropimg/create-thumbnail.php");
 include("../cropimg/commonfunctions.php");
 include("../PHPExcel/classprod.php");
 include("../PHPExcel/Classes/PHPExcel/IOFactory.php");
 include("../classes/product.php");
 include("dateFormat.php");
  $clprod=new classprod();
  $prod=new Product();
  ini_set('memory_limit','-1');



  $getone= selectQuery(PRODCRON,"*","status='Initiate' order by fileId ASC LIMIT 1");
  if(count($getone)){
   $total=$getone[0]['totalRow']-1;
   $uploader=$getone[0]['uploadedBy'];
    $file="cronfiles/".$getone[0]['filename'];
    try {  $objPHPExcel = PHPExcel_IOFactory::load($file); }
    catch (Exception $e) {  die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage()); }
    //An excel file may contains many sheets, so you have to specify which one you need to read or work with.
        $sheet = $objPHPExcel->getSheet(0);
          $total_rows=$getone[0]['totalRow'];
        //It returns the highest number of rows

       $data=array( "status"=>"Running","cronstart"=>date("Y-m-d H:i:s"));
       updateQuery(PRODCRON,$data,"fileId=".$getone[0]['fileId']);
       $total_columns = $sheet->getHighestColumn();  $completedcnt=1;
      $headings = $sheet->rangeToArray('A1:' . $total_columns . 1,  NULL, TRUE,FALSE);
       $mainhead=$headings[0];
 //Loop through each row of the worksheet
    if($uploader!=0){
    	$get_vendor_details = selectQuery(VENDOR,"auto_approve_product","dealer_id=".$uploader); 
        for($row =2; $row <= $total_rows; $row++){
            //Read a single row of data and store it as a array.
            //This line of code selects range of the cells like A1:D1
            $single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, TRUE, FALSE);
            echo "<pre>";
            print_r($single_row);
            //Print each cell of the current row
             $ID=ucwords(trim($single_row[0][0]));   //Product/Service
                $isprod=ucwords(trim($single_row[0][1]));   //Product/Service
                $prodname=ucwords(trim($single_row[0][2]));
                $prodType=trim($single_row[0][3]);      //Main/Variation
                $cat=trim($single_row[0][4]);
                $subcat=trim($single_row[0][5]);
                $company=trim($single_row[0][6]);
                $hsnCode=trim($single_row[0][7]);
                 $isVariation=trim($single_row[0][8]);  //is vairation available 0/1
                $variationOn=trim($single_row[0][9]);   // a1|a2
                $variationvalues=trim($single_row[0][10]);
                  $sku=trim($single_row[0][11]);     //12^12
                $stock=trim($single_row[0][12]);     //12^12
                $mrp= trim($single_row[0][13]);
                $tax= trim($single_row[0][14]);
                $price= trim($single_row[0][15]);
                $sale_price= trim($single_row[0][16]);
                $sale_start= trim($single_row[0][17])!=""?date("Y-m-d",strtotime(trim($single_row[0][17]))):"";
                $sale_end= trim($single_row[0][18])!=""?date("Y-m-d",strtotime(trim($single_row[0][18]))):"";

                $weight= trim($single_row[0][19]);
                $length= trim($single_row[0][20]);
                $width= trim($single_row[0][21]);
                $height= trim($single_row[0][22]);
                $cancellation= trim($single_row[0][23]);
                $return= trim($single_row[0][24]);
                $cod= trim($single_row[0][25]);
                $seotitle= trim($single_row[0][26]);
                $seodesc= trim($single_row[0][27]);
                $seokeywords= trim($single_row[0][28]);
                $isActive= trim($single_row[0][29]);
                $isDelete= trim($single_row[0][30]);
                $img1= $single_row[0][31];
                $img2= $single_row[0][32];
                $img3= $single_row[0][33];
                $img4= $single_row[0][34];
                $proddesc=trim($single_row[0][35]);
                $prodbasic=array();   $templatefld=array();
                $variations=$clprod->exvariation2($variationvalues);  $varon= $clprod->exvariation2($variationOn);
                $template=$prod->getTemplate($subcat);
                //$format = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($sale_start));
                
                if($prodname!=""&&$cat!=""&&$subcat!=""&&$company!=""&&$template!=""){
                    if($prod->checkCat($cat)=="Invalid"){ $flag= 0; }
                    else if($prod->checkCat($subcat)=="Invalid"){$flag= 0;}
                     else {
                             $img1val=$img1;  $img2val=$img2; $img3val=$img3; $img4val=$img4;
                             $productname=ucwords(addslashes(trim($prodname)).($prodType=="Main"?" ":" ".trim(implode(" ",$variations))));
                         if($prod->checkprod($productname,$uploader,$ID)){
                            if($isDelete==0){

                                $url=$prod->createProdURL($productname,$subcat,$ID);
                            $action= ($ID!=0&&$ID!=""?"Update":"Add");
                             $prodbasic['prod_name']=trim($productname);
                              $prodbasic['url_title']=$url;
                              $prodbasic['parent_cat']=$prod->getParentCat($cat);
                              $prodbasic['master_cat']=$cat;
                              $prodbasic['sub_cat']=$subcat;
                              $prodbasic['prod_company']=ucwords($company);
                              $prodbasic['product_type']=$isprod;
                              $prodbasic['hsn_code']=$hsnCode;
                              $prodbasic['variation_available']=$isVariation;
                              $prodbasic['variant_name1']=(count($varon)>=1?trim($varon[0]):"");
                              $prodbasic['variant_name2']=(count($varon)>=2?trim($varon[1]):"");
                              $prodbasic['variant_name3']=(count($varon)>=3?trim($varon[2]):"");
                              $prodbasic['variant_value1']=(count($variations)>=1?trim($variations[0]):"");
                              $prodbasic['variant_value2']=(count($variations)>=2?trim($variations[1]):"");
                              $prodbasic['variant_value3']=(count($variations)>=3?trim($variations[2]):"");
                              $prodbasic['sku']=$sku;
                              $prodbasic['stock']=$stock;
                              $prodbasic['mrp']=$mrp;
                              $prodbasic['tax']=$tax;
                              $prodbasic['is_cancellation_avail']=$cancellation;
                              $prodbasic['return_days']=$return;
                              $prodbasic['is_cod_avail']=$cod;
                              $prodbasic['vendor_reg_price']=$price;
                              $prodbasic['vendor_sale_price']=$sale_price;
                              $prodbasic['vendor_sale_start_date']=$sale_start;
                              $prodbasic['vendor_sale_end_date']=$sale_end;
                               $prodbasic['seo_title']=$seotitle;
                              $prodbasic['seo_description']=$seodesc;
                              $prodbasic['seo_keywords']=$seokeywords;

                              $prodbasic['weight']=$weight;  $prodbasic['length']=$length; $prodbasic['height']=$height; $prodbasic['width']= $width;
                              $prodbasic['vendor']=$uploader;
                               $prodbasic['isActive']=$isActive;
                              if($action=="Add"){

                                   $prodbasic['parent_id']=($prodType=="Main"?0:$prod->getParentProd($prodname,$uploader));
                                  if($sale_start!=""&&$sale_end!=""){
                                  $prodbasic['final_price']=(($sale_start!=""&&$sale_end!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($sale_start))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($sale_end)))?$sale_price:$mrp);
                                  }else{
                                      $prodbasic['final_price']=$mrp;
                                  }
                                   $prodbasic['insert_date']=date("Y-m-d H:i:s");
                                  

                                   if($get_vendor_details[0]['auto_approve_product'] == 1){
                                        $prodbasic['isApproved']="1";
                                        $prodbasic['approved_by']= "Auto";
                                        $prodbasic['approve_date']= date('Y-m-d H:i:s');
                                   }
                                   
                                   $insertBasic=insertQuery(PRODINFO,$prodbasic);
                                   $mainprodid= $insertBasic;
                              }else{
                                 $mainprodid= $ID;
                                  $getadminprice=selectQuery(PRODINFO,"admin_price","id=".$mainprodid);
                                  $admprice=$getadminprice[0]['admin_price'];
                                  //$prodbasic['final_price']=($admprice!=""&&$admprice!="0"?$admprice:(($sale_start!=""&&$sale_end!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime(date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($sale_start))))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime(date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($sale_end)))))?$sale_price:$price));
                                  if($sale_start!=""&&$sale_end!=""){
                                   $prodbasic['final_price']=($admprice!=""&&$admprice!="0"?$admprice:(($sale_start!=""&&$sale_end!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($sale_start))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($sale_end)))?$sale_price:$price));
                                  }else{
                                      $prodbasic['final_price']=($admprice!=""&&$admprice!="0"?$admprice:$price);
                                  }
                                  updateQuery(PRODINFO,$prodbasic,"id=".$ID);
                              }

                               if($mainprodid){
                                /* Add Product template */
                                  $templatefld['prod_id']= $mainprodid;
                                    $templatefld['highlight']= addslashes($proddesc);
                                for($t=35;$t<count($mainhead);$t++){
                                    $fldname=str_replace(" ","",trim($mainhead[$t]));
                                   $where=" field= '".$fldname."'";
                                   $chk=showQuery($template,$where) ;
                                   if(count($chk)){
                                        $tmattrarrval=$single_row[0][$t];
                                      $templatefld[$fldname]= ucwords(addslashes($tmattrarrval));
                                   }
                                }
                               if(($prodType=="Main"&&$isVariation==0)||$prodType=="Variation"){

                                if($action=="Add"){ insertQuery($template,$templatefld); }
                                else{
                                   $data= selectQuery($template,"id","prod_id=".$mainprodid);
                                   if(count($data)){
                                    updateQuery($template,$templatefld,"prod_id=".$mainprodid);
                                   }else{
                                        $templatefld['prod_id']= $mainprodid;

                                       insertQuery($template,$templatefld);
                                   }
                                  }
                                 $getconfingdetails = getimgconfig('product');

                                 if($img1val!=""&&$prod->check_image($img1val,$mainprodid)){
                                   $imgname=$clprod->save_image($img1val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                        if($imgname!=""){
                                           $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid,"img_url"=>$img1val);
                                           insertQuery(PRODIMG,$imgdata);
                                        }
                                  }
                                  if($img2val!=""&&$prod->check_image($img2val,$mainprodid)){
                                      $imgname=$clprod->save_image($img2val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                      if($imgname!=""){  $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid ,"img_url"=>$img2val);
                                      insertQuery(PRODIMG,$imgdata);  }
                                  }
                                  if($img3val!=""&&$prod->check_image($img3val,$mainprodid)){
                                      $imgname=$clprod->save_image($img3val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                      if($imgname!=""){  $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid,"img_url"=>$img3val);
                                      insertQuery(PRODIMG,$imgdata);   }
                                  }
                                  if($img4val!=""&&$prod->check_image($img4val,$mainprodid)){
                                      $imgname=$clprod->save_image($img4val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                      if($imgname!=""){  $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid,"img_url"=>$img4val);
                                      insertQuery(PRODIMG,$imgdata);   }
                                  }
                               }
                             $flag= 1;
                           }else{  $flag= 0;  }

                        $completedcnt=$completedcnt+1;
                        }else{
                            /************************************** code for delete product********************** */
                          if($ID!=0||$ID!=""){$prod->deleteProd($ID);   }
                            $completedcnt=$completedcnt+1;
                        }
                        }else{$completedcnt=$completedcnt+1;     }
                     }

                }
                else{ $flag=0;}   //if else end here
               $completedcnt=$completedcnt;
                $crondata=array("completedRow"=>$completedcnt);
                if($total==$completedcnt){
                    $crondata['status']="Done";
                     $crondata['cronend']=date("Y-m-d H:i:s");
                }
              $in=updateQuery(PRODCRON,$crondata,"fileId=".$getone[0]['fileId']);
            } ///end here
        }else{
            //***********************************upload for admin  *********************************************************************************

             for($row =2; $row <= $total_rows; $row++){
            //Read a single row of data and store it as a array.
            //This line of code selects range of the cells like A1:D1
            $single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, TRUE, FALSE);

            //Print each cell of the current row
             $ID=ucwords(trim($single_row[0][0]));   //Product/Service
                $isprod=ucwords(trim($single_row[0][1]));   //Product/Service
                $prodname=ucwords(trim($single_row[0][2]));
                $prodType=trim($single_row[0][3]);      //Main/Variation
                $cat=trim($single_row[0][4]);
                $subcat=trim($single_row[0][5]);
                $company=trim($single_row[0][6]);
                $vendor=trim($single_row[0][7]);
                $hsnCode=trim($single_row[0][8]);
                 $isVariation=trim($single_row[0][9]);  //is vairation available 0/1
                $variationOn=trim($single_row[0][10]);   // a1|a2
                $variationvalues=trim($single_row[0][11]);
                $sku=trim($single_row[0][12]);     //12^12
                $stock=trim($single_row[0][13]);     //12^12
                $mrp= trim($single_row[0][14]);
                $tax= trim($single_row[0][15]);
                $price= trim($single_row[0][16]);
                $admin_price= trim($single_row[0][17]);
                $sale_price= trim($single_row[0][18]);
                $sale_start= trim($single_row[0][19])!=""?date("Y-m-d",strtotime(trim($single_row[0][19]))):"";
                $sale_end= trim($single_row[0][20])!=""?date("Y-m-d",strtotime(trim($single_row[0][20]))):"";

                $weight= trim($single_row[0][21]);
                $length= trim($single_row[0][22]);
                $width= trim($single_row[0][23]);
                $height= trim($single_row[0][24]);
                $cancellation= trim($single_row[0][25]);
                $return= trim($single_row[0][26]);
                $cod= trim($single_row[0][27]);
                $seotitle= trim($single_row[0][28]);
                $seodesc= trim($single_row[0][29]);
                $seokeywords= trim($single_row[0][30]);
                $isApproved= trim($single_row[0][31]);
                $newarrival= trim($single_row[0][32]);
                $trendong= trim($single_row[0][33]);
                $isactive= trim($single_row[0][34]);
                $isDelete= trim($single_row[0][35]);
               $img1= $single_row[0][36];
               $img2= $single_row[0][37];
               $img3= $single_row[0][38];
               $img4= $single_row[0][39];
              $proddesc=trim($single_row[0][40]);
                $prodbasic=array();   $templatefld=array();
                $variations=$clprod->exvariation2($variationvalues);  $varon= $clprod->exvariation2($variationOn);

                $template=$prod->getTemplate($subcat);
                if($prodname!=""&&$cat!=""&&$subcat!=""&&$company!=""&&$template!=""){
                    if($prod->checkCat($cat)=="Invalid"){ $flag= 0; }
                    else if($prod->checkCat($subcat)=="Invalid"){$flag= 0;}
                     else {
                           if($isDelete==0){
                            $getvendor=selectQuery(VENDOR,"dealer_id","nickname='".$vendor."'");
                            $uploader=$getvendor[0]['dealer_id'];
                            $img1val=$img1;  $img2val=$img2; $img3val=$img3; $img4val=$img4;
                            $productname=ucwords(addslashes(trim($prodname)).($prodType=="Main"?" ":" ".trim(implode(" ",$variations))));
                            $url=$prod->createProdURL($productname,$subcat,$ID);
                            // $productname=trim(ucwords($prodname.($prodType=="Main"?"":" ".trim(implode(" ",$variations)))));
                            $action= ($ID!=0&&$ID!=""?"Update":"Add");
                             $prodbasic['prod_name']=addslashes(trim($productname));
                             $prodbasic['url_title']=$url;
                             $prodbasic['parent_cat']=$prod->getParentCat($cat);
                             $prodbasic['master_cat']=$cat;
                             $prodbasic['sub_cat']=$subcat;
                             $prodbasic['prod_company']=ucwords($company);
                             $prodbasic['product_type']=$isprod;
                             $prodbasic['hsn_code']=$hsnCode;
                             $prodbasic['variation_available']=$isVariation;
                             $prodbasic['variant_name1']=(count($varon)>=1?trim($varon[0]):"");
                             $prodbasic['variant_name2']=(count($varon)>=2?trim($varon[1]):"");
                             $prodbasic['variant_name3']=(count($varon)>=3?trim($varon[2]):"");
                             $prodbasic['variant_value1']=(count($variations)>=1?trim($variations[0]):"");
                             $prodbasic['variant_value2']=(count($variations)>=2?trim($variations[1]):"");
                             $prodbasic['variant_value3']=(count($variations)>=3?trim($variations[2]):"");
                              $prodbasic['sku']=$sku; 
                              $prodbasic['stock']=$stock;
                              $prodbasic['mrp']=$mrp;
                              $prodbasic['tax']=$tax;
                              $prodbasic['is_cancellation_avail']=($cancellation!=""?$cancellation:1);
                              $prodbasic['return_days']=($return!=""?$return:0);
                              $prodbasic['is_cod_avail']=($cod!=""?$cod:0);
                              $prodbasic['vendor_reg_price']=$price;
                               $prodbasic['admin_price']=$admin_price;
                              $prodbasic['vendor_sale_price']=$sale_price;
                              $prodbasic['vendor_sale_start_date']=$sale_start;
                              $prodbasic['vendor_sale_end_date']=$sale_end;
                               $prodbasic['seo_title']=$seotitle;
                              $prodbasic['seo_description']=$seodesc;
                              $prodbasic['seo_keywords']=$seokeywords;
                              $prodbasic['final_price']=($admin_price!=0&&$admin_price!=""?$admin_price:(($sale_start!=""&&$sale_end!="")&&(date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($sale_start))&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($sale_end)))?$sale_price:$mrp));
                              $prodbasic['weight']=$weight;  $prodbasic['length']=$length; $prodbasic['height']=$height; $prodbasic['width']= $width;
                              $prodbasic['isActive']=$isactive; $prodbasic['isApproved']=$isApproved;
                              $prodbasic['new_arrival']=$newarrival; $prodbasic['trending_now']=$trendong;

                              if($action=="Add"){

                                  $prodbasic['parent_id']=($prodType=="Main"?0:$prod->getParentProd($prodname,$uploader));
                                  // $insertBasic=insertQuery(PRODINFO,$prodbasic);
                                  // $mainprodid= $insertBasic;
                                  $mainprodid= "";
                              }else{
                                  $mainprodid= $ID;
                                    updateQuery(PRODINFO,$prodbasic,"id=".$ID);
                              }

                               if($mainprodid){
                                /* Add Product template */
                                  $templatefld['prod_id']= $mainprodid;
                                    $templatefld['highlight']= addslashes($proddesc);
                                for($t=40;$t<count($mainhead);$t++){
                                    $fldname=str_replace(" ","",trim($mainhead[$t]));
                                   $where=" field= '".$fldname."'";
                                   $chk=showQuery($template,$where) ;
                                   if(count($chk)){
                                        $tmattrarrval=$single_row[0][$t];
                                      $templatefld[$fldname]= ucwords(addslashes($tmattrarrval));
                                   }
                                }
                               if(($prodType=="Main"&&$isVariation==0)||$prodType=="Variation"){

                                if($action=="Add"){ //insertQuery($template,$templatefld);
                                }
                                else{ updateQuery($template,$templatefld,"prod_id=".$mainprodid); }
                                 $getconfingdetails = getimgconfig('product');

                                 if($img1val!=""&&$prod->check_image($img1val,$mainprodid)){

                                      $imgname=$clprod->save_image($img1val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                        if($imgname!=""){
                                           $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid,"img_url"=>$img1val);
                                           insertQuery(PRODIMG,$imgdata);
                                        }
                                  }
                                  if($img2val!=""&&$prod->check_image($img2val,$mainprodid)){
                                      $imgname=$clprod->save_image($img2val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                      if($imgname!=""){  $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid ,"img_url"=>$img2val);
                                      insertQuery(PRODIMG,$imgdata);  }
                                  }
                                  if($img3val!=""&&$prod->check_image($img3val,$mainprodid)){
                                      $imgname=$clprod->save_image($img3val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                      if($imgname!=""){  $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid,"img_url"=>$img3val);
                                      insertQuery(PRODIMG,$imgdata);   }
                                  }
                                  if($img4val!=""&&$prod->check_image($img4val,$mainprodid)){
                                      $imgname=$clprod->save_image($img4val,$mainprodid,$getconfingdetails,replace_nonletter($productname));
                                      if($imgname!=""){  $imgdata=array("img_name"=>$imgname,"prod_id"=>$mainprodid,"img_url"=>$img4val);
                                      insertQuery(PRODIMG,$imgdata);   }
                                  }
                               }

                             $flag= 1;
                           }else{  $flag= 0;  }

                        $completedcnt=$completedcnt+1;
                        }else{
                            /************************************** code for delete product********************** */
                            if($ID!=0||$ID!=""){$prod->deleteProd($ID);   }
                            $completedcnt=$completedcnt+1;
                        }
                     }

                }
                else{ $flag=0;}   //if else end here
               $completedcnt=$completedcnt;
                $crondata=array("completedRow"=>$completedcnt);
                if($total==$completedcnt){
                    $crondata['status']="Done";
                     $crondata['cronend']=date("Y-m-d H:i:s");
                }
              updateQuery(PRODCRON,$crondata,"fileId=".$getone[0]['fileId']);
            }

        }
      }else{ echo "No Cron in Queue"; }
   ?>
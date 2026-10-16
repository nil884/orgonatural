<?php
    include("../../includes/configuration.php");
    include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');

        $tablename=$_REQUEST['template'];
         $getcat=selectQuery(PRODSUBCAT,"*","template='".$tablename."'");

       $getprodcat=selectQuery(PRODCAT,"*","pc_id=".$getcat[0]['pc_id']);
        $category=$getprodcat[0]['category_name'];
        $subcategory=$getcat[0]['subcat_name'];
        $retval=showQuery($tablename);
        $tablcol=array();
        for($i=0;$i<count($retval);$i++)
        {
            $inarra=$retval[$i][0];

            array_push($tablcol,$inarra);
        }
        $fileName = str_replace(" ","-", $getcat[0]['subcat_name'])."-excel";

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$colarr=array('Book Name In English','Book Name In Other Language','Category','Subcategory','Book Language','Publication In English','Publication In Other Language','Author Name In English','Author Name In Other Language','MRP','Tag','Seller Nickname','Seller Code','Quantity','Price','Offer Price','Fullfilled','Image1','Image2','Image3','Image4','Image5','Image6','Image7','Image8','Image9','Image10','Book Highlights','Shipping Template');
    for($i=5;$i<count($tablcol);$i++)
    {
        $inarra=getOriginalName($tablcol[$i]);

        array_push($colarr,$inarra);
    }

 $row="1";
for($i=0;$i<count($colarr);$i++)
{
    $col=$i;
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $colarr[$i]);
}
  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 2, $category);
  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, $subcategory);

// Set worksheet title
$objPHPExcel->getActiveSheet()->setTitle($getcat[0]['subcat_name']);

  /* ---------------------------------Second sheet------------------------------------------------------------------- */
                        $objPHPExcel->createSheet(1); //Setting index when creating
                        $objPHPExcel->setActiveSheetIndex(1);


                            /* Set read More values */
                                    $row="1";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Field Name");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "value/hint");

                                   /* Category */
                                    $row="2";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Category Name");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $category);

                                     /*Sub Category */
                                    $row="3";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Sub Category Name");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $subcategory);


                                      /*Sub Category */
                                    $row="4";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Seller Nickname & Code");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "To see seller nickname, go to read more section from your panel");

                                       $row="5";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Price");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Selling Price Value should be without commas");

                                      $row="6";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Fullfilled");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Yes/No");

                                      $row="7";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Shipping Template");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Get available shipping templates from read more section from your panel");


                                      $row="8";
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Images");
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Adding Images
Go to https://snag.gy/
Upload image on that website. Automatically it will generate a link .Copy paste that link to sheet in image column");


                                   $objPHPExcel->getActiveSheet()->setTitle("Instructions");

                   /* ---------------------------------Second sheet------------------------------------------------------------------- */
                            $objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../../templatexcels/' . $fileName . '.xlsx');
$data=array(
"excel_name"=> $fileName.".xlsx"
);
 $getcat=updateQuery(PRODSUBCAT,$data,"psc_id=".$getcat[0]['psc_id']);
    echo "1";
?>
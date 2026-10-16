<?php
ob_start();
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
function cellColor($col, $row, $color)
{
    global $objPHPExcel;
    $cells = $columnLetter = PHPExcel_Cell::stringFromColumnIndex($col) . $row;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => $color
        )
    ));
}
/* ********************************Function 1 create blank excel sheet on template creation*********************************************************************** */
// excel will be create when admin create/update template for subcategory in category management
function createExcel($url, $tablename)
{
    global $objPHPExcel;
    $path = $_SERVER['HTTP_HOST'] . "/templatexcels";

    $target_path = getRelativePath($url, $path) . "/";
    $getcat = selectQuery(PRODCAT, "id,parent_id,cat_name", "template='" . $tablename . "'");

    $getprodcat = selectQuery(PRODCAT, "id,cat_name", "id=" . $getcat[0]['parent_id']);
    $category = $getprodcat[0]['cat_name'];
    $subcategory = $getcat[0]['cat_name'];
    $categoryId = $getcat[0]['parent_id'];
    $subcategoryId = $getcat[0]['id'];
    $retval = showQuery($tablename);
    $tablcol = array();
    for ($i = 0; $i < count($retval); $i++) {array_push($tablcol, $retval[$i][0]);}

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");
    $attrsa = array();
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $colarr = array('Product ID', 'Product Type', 'Product Name', 'Main/Variation', 'Category', 'Subcategory', 'Company', 'HSN Code', 'Variation Available?', 'Variation On', 'Variation Value', 'SKU', 'Stock', 'MRP', 'Tax(%)', 'Vendor Price', 'Discount Price', 'Discount Start Date(YYYY-MM-DD)', 'Discount End Date(YYYY-MM-DD)', 'Weight(In Kg)', 'Length(In cm)', 'Width(In cm)', 'Height(In cm)', 'Cancellation Required?', 'Return Days', 'COD Available', 'SEO Title', 'SEO Description', 'SEO Keywords', 'Is Active', 'Is Delete', 'Image1', 'Image2', 'Image3', 'Image4', 'Description');
    for ($i = 3; $i < count($tablcol); $i++) {
        array_push($colarr, $tablcol[$i]. "(".getOriginalName($tablcol[$i]).")");
        array_push($attrsa, $tablcol[$i]);
    }
    $mystt = (count($attrsa) ? implode(',', $attrsa) : '');

    $row = "1";
    for ($i = 0; $i < count($colarr); $i++) {
        $col = $i;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $colarr[$i]);
    }
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 0);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 2, $categoryId);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 2, $subcategoryId);

    // Set worksheet title
    $objPHPExcel->getActiveSheet()->setTitle(substr($subcategory, 0, 30));

    /* ---------------------------------Second sheet------------------------------------------------------------------- */
    $objPHPExcel->createSheet(1); //Setting index when creating
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(150);
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);

    /* Set read More values */
    $row = "1";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Field Name");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Value/Hint (Double Click in cell for more readable format)");
    cellColor(0, $row, "7FC451"); cellColor(1, $row, "7FC451");
    /* ID */
    $row = "3";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product ID");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Each New Product, Enter Value = 0");

    /* Type */
    $row = "5";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Type");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Product, Enter Value = Product");

    /* Name */
    $row = "7";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Name");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If Its Main Product, Enter The Product Name\r\rRules For Variation =\r1) If Its Variation, Enter The Name Of Main Product As It Is WITHOUT ADDING ANYTHING TO IT");
    cellColor(0, $row, "F4511E");

    /* variation */
    $row = "9";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Main/Variation");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If Its Main Product, Enter Value = Main\r\rRules For Variation =\r1) If Its Variation Product of main product, Enter Value = Variation");
    cellColor(0, $row, "F4511E");

    /* Category */
    $row = "11";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Category");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $categoryId . " - Please Dont Change This Value, And Apply For All Product In Excel");

    /* Sub-Category */
    $row = "13";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Sub-Category");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $subcategoryId . " - Please Dont Change This Value, And Apply For All Product In Excel");

    /* Company */
    $row = "15";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Company");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Company Name Or Manufacturer Name Of The Product");

    /* HSN */
    $row = "17";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "HSN Code");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter HSN Code Of Product");

    /*is variation */
    $row = "19";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation Available");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If No Variation Available, Enter Value = 0\r\rRules For Variation =\r1) In Case If Variation Available For Main Product, Enter Value = 1\r(Only In Front Of Main Product & In Front Of All Variations, Enter Value = 0)");
    cellColor(0, $row, "F4511E");


    $row = "21";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation On");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) In Case Of Main Product Which DO NOT Have Variation = Keep It Blank\r2) In Case Of Main Product Which Have Variations = Keep It Blank\r\rRules For Variation =\r1) In Case Of Variations = Enter Attributes IDs Separated Using Pipe Symbol.\r\rExample =\r1) If You Have Variations On Color And Size Then Combine Both Attributes Using Pipe Symbol & Write - Attr_1|Attr_2\r(Here Attr_1 & Attr_2 Are Used As Sample For Color & Size, Please Make Sure You Use Correct Attribute IDs)\r\rIMPORTANT =\rENTER ONLY ATTRIBUTE IDs AS SHOWN IN ABOVE EXAMPLE (WITHOUT ATTRIBUTE NAMES - Attr_1|Attr_2)\rSTRICTLY DO NOT ENTER LIKE Attr_1(Color)|Attr_2(Size)");
    cellColor(0, $row, "F4511E");

    $row = "23";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation Values");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) For Main Product - Keep This Blank\r\rRules For Variation =\r1) For Variations - Enter Value For Variation, Else Keep Blank If Not Applicable\r\rExample =\r1) As Per Above Example, If You Have Variations On Color (Attr_1) And Size (Attr_2) Then Write - Red|XL");
    cellColor(0, $row, "F4511E");

    /* SKU Code */
    $row = "25";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SKU Code");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SKU Code Of Product");

    /* Stock */
    $row = "27";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Stock");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter The Stock Of Product Or Variation.\r\rRules For Main Product =\r1)If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Stock\r\rRules For Variation =\r1) For Variations - Write Stock Of Respective Variation");
    cellColor(0, $row, "F4511E");

    /* MRP */
    $row = "29";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "MPR");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Max Retail Price of product (Compulsory Field)");

    /* Tax Percentage */
    $row = "31";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Tax Percetage");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Tax Percetage (Number Only)\rDont Add Pecentage Symbol (% Sign)\rExample =\r12% Should Be Entered As = 12");

    /* Vendor Percentage */
    $row = "33";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Vendor Price");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Vendor Price Value Without Comma\rExample =\rVendor Price 2,522 Should Be Entered As 2522\r\rRules For Main Product =\r1) For Main Product - If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Vendor Price\r\rRules For Variation =\r1) For Variations - Write Vendor Price Of Respective Variation");
    cellColor(0, $row, "F4511E");

    /* Sale Price */
    $row = "35";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Discount Price");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Discount Price Value Should Be Without Commas. Else Keep Blank If Not Available.\r\rRules For Main Product =\r1)If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Discount Price (If Applicable)\r\rRules For Variation =\r1) For Variations - Write Discount Price Of Respective Variation (If Applicable)");
    cellColor(0, $row, "F4511E");

    /* Sale Start & end */
    $row = "37";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Discount Start and End Date");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Discount Start and End Date in YYYY-MM-DD format. Keep blank if not available\r\rRules For Main Product =\r1)If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Discount Start And End Date (If Applicable)\r\rRules For Variation =\r1) For Variations - Write Discount Start And End Date Of Respective Variation (If Applicable)");
    cellColor(0, $row, "F4511E");

    /* Weight,Length,Width,Height  */
    $row = "39";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Weight,Length,Width,Height");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Respective Post Packaging Values\r\rJust Enter Numeric Values Without Kg, Gm, CM etc\r\r Example =\r1) 1.5Kg Should Be Entered As = 1.5 \r2) 5gm Should Be Entered As = 0.005");
    cellColor(0, $row, "F4511E");

    /* Cancellation */
    $row = "41";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Cancellation");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) If Cancellation Is Available On Product, Enter Value = 1\r\r2) If Cancellation Is NOT Available On Product, Enter Value = 0");

    /* Return Days */
    $row = "43";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Return Days");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) Enter Return Days\r\r2) If No Return Is Allowed, Enter Value = 0 ");

    /* COD  */
    $row = "45";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "COD");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) If COD Option Is Available On Product, Enter Value = 1\r\r2) If COD Option Is NOT Available On Product, Enter Value = 0");

    /* SEO Title */
    $row = "47";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Title");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Title (Keep Blank If Not Available)");

    /* SEO Title */
    $row = "49";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Description");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Description (Keep Blank If Not Available)");

    /* SEO Title */
    $row = "51";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Keywords");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Keywords (Keep Blank If Not Available)");

    /* Active  */
    $row = "53";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Is Active");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) For Activate Product, Enter Value = 1\r\r2) To De-Activate Product, Enter Value = 0");

    /* Delete  */
    $row = "55";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Is Delete");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) For Deleting Product, Enter Value = 1\r\r2) For No Action, Enter Value = 0");

    $row = "57";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Images");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Adding Images, Enter Valid Image Link To Sheet In Image Column.\r\rExample = \rImage1 - https://linkpicture.com/q/3d-render-image.jpg\r\rRules For Images =\r1) The Link Should Have Image Extension At The End");
    cellColor(0, $row, "F4511E");

    /* Product Description */
    $row = "59";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Description");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Product Description");

    $rowcnt = 59;
    for ($i = 0; $i < count($attrsa); $i++) {
        $row = $rowcnt + 2;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $attrsa[$i] . " (You can use this fields as a variation fields)");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, getOriginalName($attrsa[$i]) . "(This Attribute Used In Subcategory -" . getAttributeCat($attrsa[$i]) . ")");
        $rowcnt = $row;
    }

    $objPHPExcel->getActiveSheet()->setTitle("Instructions");

    /* ---------------------------------Second sheet------------------------------------------------------------------- */
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($target_path . $tablename . '.xlsx');
    return $tablename . '.xlsx';
}

/* ********************************Function 2 create  excel sheet for vendor export data*********************************************************************** */
// excel file will be created when vendor export products from vendor panel

function createExcelForExport($tablename, $url, $seller = null){
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
    foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->getStyle('A1:BZ1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A1:BZ1')->getFill()->getStartColor()->setARGB('7FC451');


    $getcat = selectQuery(PRODCAT, "id,parent_id,cat_name", "template='" . $tablename . "'");

    $getprodcat = selectQuery(PRODCAT, "id,cat_name", "id=" . $getcat[0]['parent_id']);
    $category = $getprodcat[0]['cat_name'];
    $subcategory = $getcat[0]['cat_name'];
    $categoryId = $getcat[0]['parent_id'];
    $subcategoryId = $getcat[0]['id'];
    $retval = showQuery($tablename);
    $tablcol = array();
    for ($i = 0; $i < count($retval); $i++) {array_push($tablcol, $retval[$i][0]);}

    $where = "";
    if ($seller) {$where = " and vendor=" . $seller;}
    $path = $_SERVER['HTTP_HOST'] . "/templatexcels";
    $getproduct = selectQuery(PRODINFO, "*", "sub_cat='" . $getcat[0]['id'] . "' " . $where . " order by id ASC");

    $target_path = getRelativePath($url, $path) . "/";

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");
    $attrsa = array();
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $colarr = array('Product ID', 'Product Type', 'Product Name', 'Main/Variation', 'Category', 'Subcategory', 'Company', 'HSN Code', 'Variation Available?', 'Variation On', 'Variation Value', 'SKU', 'Stock', 'MRP', 'Tax(%)', 'Vendor Price', 'Discount Price', 'Discount Start Date(YYYY-MM-DD)', 'Discount End Date(YYYY-MM-DD)', 'Weight(In kg)', 'Length(In cm)', 'Width(In cm)', 'Height(In cm)', 'Cancellation Required?', 'Return Days', 'COD Available', 'SEO Title', 'SEO Description', 'SEO Keywords', 'Is Active', 'Is Delete', 'Image1', 'Image2', 'Image3', 'Image4', 'Description');

    for ($i = 3; $i < count($tablcol); $i++) {
          array_push($colarr, $tablcol[$i]. "(".getOriginalName($tablcol[$i]).")");
        array_push($attrsa, $tablcol[$i]);
    }
    $mystt = (count($attrsa) ? implode(',', $attrsa) : '');
    /*for($i=0;$i<count($colarr);$i++){
    $rowcnt=0;
    $cell = \PHPExcel_Cell::stringFromColumnIndex($i) . $rowcnt;
    if($i==0||$i==4||$i==5||$i==10){
    $objPHPExcel->getActiveSheet()->getStyle($cell.'1:'.$cell.$endcount)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    }else{
    $objPHPExcel->getActiveSheet()->getStyle($cell.'1:'.$cell.$endcount)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    }
    }*/

    //  $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
    $row = "1";
    for ($i = 0; $i < count($colarr); $i++) {
        $col = $i;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $colarr[$i]);
    }
    for ($i = 0; $i < count($getproduct); $i++) {
        $row++;
        if ($getproduct[$i]['parent_id'] == 0) {$prodname = $getproduct[$i]['prod_name'];
            $isMainVar = "Main";} else {
            $getparentdata = selectQuery(PRODINFO, "prod_name", "id='" . $getproduct[$i]['parent_id'] . "'");
            $prodname = $getparentdata[0]['prod_name'];
            $isMainVar = "Variation";
        }
        $getimages = selectQuery(PRODIMG, "img_url", "prod_id='" . $getproduct[$i]['id'] . "'");
        $varidMainarra = array();
        if ($getproduct[$i]['variant_name1'] != "") {
            array_push($varidMainarra, $getproduct[$i]['variant_name1']);
        }

        if ($getproduct[$i]['variant_name2'] != "") {
            array_push($varidMainarra, $getproduct[$i]['variant_name2']);
        }

        if ($getproduct[$i]['variant_name3'] != "") {
            array_push($varidMainarra, $getproduct[$i]['variant_name3']);
        }

        $varidvalues = array();
        if ($getproduct[$i]['variant_value1'] != "") {
            array_push($varidvalues, $getproduct[$i]['variant_value1']);
        }

        if ($getproduct[$i]['variant_value2'] != "") {
            array_push($varidvalues, $getproduct[$i]['variant_value2']);
        }

        if ($getproduct[$i]['variant_value3'] != "") {
            array_push($varidvalues, $getproduct[$i]['variant_value3']);
        }

        $gettemplatedata = selectQuery($tablename, "*", "prod_id=" . $getproduct[$i]['id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $getproduct[$i]['id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $getproduct[$i]['product_type']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $prodname);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $isMainVar);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $getproduct[$i]['master_cat']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $getproduct[$i]['sub_cat']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $getproduct[$i]['prod_company']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $getproduct[$i]['hsn_code']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $getproduct[$i]['variation_available']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, implode("|", $varidMainarra));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, implode("|", $varidvalues));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $getproduct[$i]['sku']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $getproduct[$i]['stock']-$getproduct[$i]['sold']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $getproduct[$i]['mrp']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $getproduct[$i]['tax']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $getproduct[$i]['vendor_reg_price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $getproduct[$i]['vendor_sale_price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, ($getproduct[$i]['vendor_sale_start_date'] != "0000-00-00 00:00:00" ? date("Y-m-d", strtotime($getproduct[$i]['vendor_sale_start_date'])) : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, ($getproduct[$i]['vendor_sale_end_date'] != "0000-00-00 00:00:00" ? date("Y-m-d", strtotime($getproduct[$i]['vendor_sale_end_date'])) : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $getproduct[$i]['weight']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $getproduct[$i]['length']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $getproduct[$i]['width']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $getproduct[$i]['height']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $getproduct[$i]['is_cancellation_avail']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $getproduct[$i]['return_days']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $getproduct[$i]['is_cod_avail']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $getproduct[$i]['seo_title']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $getproduct[$i]['seo_description']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $getproduct[$i]['seo_keywords']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $getproduct[$i]['isActive']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30, $row, 0);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $row, (count($getimages) >= 1 ? $getimages[0]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(32, $row, (count($getimages) >= 2 ? $getimages[1]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(33, $row, (count($getimages) >= 3 ? $getimages[2]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $row, (count($getimages) >= 4 ? $getimages[3]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(35, $row, (count($gettemplatedata) ? $gettemplatedata[0]['highlight'] : ""));
        $lastcnt = 35;
        for ($t = 0; $t < count($attrsa); $t++) {$lastcnt++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($lastcnt, $row, (count($gettemplatedata) ? $gettemplatedata[0][$attrsa[$t]] : ""));
        }
    }

    // Set worksheet title
    $objPHPExcel->getActiveSheet()->setTitle("data");

    /* ---------------------------------Second sheet------------------------------------------------------------------- */
    $objPHPExcel->createSheet(1); //Setting index when creating
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(150);
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);

    /* Set read More values */
    $row = "1";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Field Name");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Value/Hint (Double Click in cell for more readable format)");
    cellColor(0, $row, "007BFF"); cellColor(1, $row, "007BFF");
    /* ID */
    $row = "3";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product ID");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For New Product Enter Value = 0");

    /* Type */
    $row = "5";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Type");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Product, Enter Value = Product");

    /* Name */
    $row = "7";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Name");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If Its Main Product, Enter The Product Name\r\rRules For Variation =\r1) If Its Variation, Enter The Name Of Main Product As It Is WITHOUT ADDING ANYTHING TO IT");
    cellColor(0, $row, "F4511E");

    /* variation */
    $row = "9";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Main/Variation");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If Its Main Product, Enter Value = Main\r\rRules For Variation =\r1) If Its Variation Product of main product, Enter Value = Variation");
    cellColor(0, $row, "F4511E");

    /* Category */
    $row = "11";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Category");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $categoryId . " (Category ID For -".$category.") - Please Dont Change This Value. And Apply For All Product In Excel");

    /* Sub-Category */
    $row = "13";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Sub-Category");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $subcategoryId . " (Sub-Category ID For -".$subcategory.") - Please Dont Change This Value. And Apply For All Product In Excel");

    /* Company */
    $row = "15";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Company");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Company Name Or Manufacturer Name Of The Product");

    /* HSN */
    $row = "17";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "HSN Code");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter HSN Code Of Product");

    /*is variation */
    $row = "19";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation Available");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If No Variation Available, Enter Value = 0\r\rRules For Variation =\r1) In Case If Variation Available For Main Product, Enter Value = 1\r(Only In Front Of Main Product & In Front Of All Variations, Enter Value = 0)");
    cellColor(0, $row, "F4511E");


    $row = "21";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation On");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) In Case Of Main Product Which DO NOT Have Variation = Keep It Blank\r2) In Case Of Main Product Which Have Variations = Keep It Blank\r\rRules For Variation =\r1) In Case Of Variations = Enter Attributes IDs Separated Using Pipe Symbol.\r\rExample =\r1) If You Have Variations On Color And Size Then Combine Both Attributes Using Pipe Symbol & Write - Attr_1|Attr_2\r(Here Attr_1 & Attr_2 Are Used As Sample For Color & Size, Please Make Sure You Use Correct Attribute IDs)\r\rIMPORTANT =\rENTER ONLY ATTRIBUTE IDs AS SHOWN IN ABOVE EXAMPLE (WITHOUT ATTRIBUTE NAMES - Attr_1|Attr_2)\rSTRICTLY DO NOT ENTER LIKE Attr_1(Color)|Attr_2(Size)");
    cellColor(0, $row, "F4511E");

    $row = "23";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation Values");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) For Main Product - Keep This Blank\r\rRules For Variation =\r1) For Variations - Enter Value For Variation, Else Keep Blank If Not Applicable\r\rExample =\r1) As Per Above Example, If You Have Variations On Color (Attr_1) And Size (Attr_2) Then Write - Red|XL");
    cellColor(0, $row, "F4511E");

    /* SKU Code */
    $row = "25";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SKU Code");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SKU Code Of Product");

    /* Stock */
    $row = "27";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Stock");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter The Stock Of Product Or Variation.\r\rRules For Main Product =\r1) If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Stock\r\rRules For Variation =\r1) For Variations - Write Stock Of Respective Variation");
    cellColor(0, $row, "F4511E");

    /* MRP */
    $row = "29";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "MPR");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Max Retail Price of product (Compulsory Field)");

    /* Tax Percentage */
    $row = "31";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Tax Percetage");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Tax Percetage (Number Only)\rDont Add Pecentage Symbol (% Sign)\rExample =\r12% Should Be Entered As = 12");

    /* Vendor Percentage */
    $row = "33";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Vendor Price");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Vendor Price Value Without Comma\rExample =\rVendor Price 2,522 Should Be Entered As 2522\r\rRules For Main Product =\r1) For Main Product - If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Vendor Price\r\rRules For Variation =\r1) For Variations - Write Vendor Price Of Respective Variation");
    cellColor(0, $row, "F4511E");

    /* Sale Price */
    $row = "35";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Discount Price");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Discount Price Value Should Be Without Commas. Else Keep Blank If Not Available.\r\rRules For Main Product =\r1) For Main Product - If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Discount Price (If Applicable)\r\rRules For Variation =\r1) For Variations - Write Discount Price Of Respective Variation (If Applicable)");
    cellColor(0, $row, "F4511E");

    /* Sale Start & end */
    $row = "37";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Discount Start and End Date");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Discount Start and End Date in YYYY-MM-DD format. Keep blank if not available\r\rRules For Main Product =\r1) If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Discount Start And End Date (If Applicable)\r\rRules For Variation =\r1) For Variations - Write Discount Start And End Date Of Respective Variation (If Applicable)");
    cellColor(0, $row, "F4511E");

    /* Weight,Length,Width,Height  */
    $row = "39";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Weight,Length,Width,Height");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Respective Post Packaging Values\r\rJust Enter Numeric Values Without Kg, Gm, CM etc\r\r Example =\r1) 1.5Kg Should Be Entered As = 1.5 \r2) 5gm Should Be Entered As = 0.005");
    cellColor(0, $row, "F4511E");

    /* Cancellation */
    $row = "41";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Cancellation");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) If Cancellation Is Available On Product, Enter Value = 1\r\r2) If Cancellation Is NOT Available On Product, Enter Value = 0");

    /* Return Days */
    $row = "43";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Return Days");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) Enter Return Days\r\r2) If No Return Is Allowed, Enter Value = 0 ");

    /* COD  */
    $row = "45";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "COD");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) If COD Option Is Available On Product, Enter Value = 1\r\r2) If COD Option Is NOT Available On Product, Enter Value = 0");

    /* SEO Title */
    $row = "47";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Title");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Title (Keep Blank If Not Available)");

    /* SEO Title */
    $row = "49";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Description");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Description (Keep Blank If Not Available)");

    /* SEO Title */
    $row = "51";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Keywords");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Keywords (Keep Blank If Not Available)");

    /* Active  */
    $row = "53";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Is Active");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) For Activate Product, Enter Value = 1\r\r2) To De-Activate Product, Enter Value = 0");

    /* Delete  */
    $row = "55";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Is Delete");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) For Deleting Product, Enter Value = 1\r\r2) For No Action, Enter Value = 0");

    $row = "57";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Images");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Adding Images, Enter Valid Image Link To Sheet In Image Column.\r\rExample = \rImage1 - https://linkpicture.com/q/3d-render-image.jpg\r\rRules For Images =\r1) The Link Should Have Image Extension At The End");
    cellColor(0, $row, "F4511E");

    /* Product Description */
    $row = "59";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Description");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Product Description");

    $rowcnt = 59;

    for ($i = 0; $i < count($attrsa); $i++) {
        $row = $rowcnt + 2;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $attrsa[$i] . " (You can use this fields as a variation fields)");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, getOriginalName($attrsa[$i]) . " (This Attribute Used In Subcategory -" . getAttributeCat($attrsa[$i]) . ")");
        $rowcnt = $row;
    }

    $objPHPExcel->getActiveSheet()->setTitle("Instructions");

    /* ---------------------------------Second sheet------------------------------------------------------------------- */
    $objPHPExcel->setActiveSheetIndex(0);
      ob_end_clean();       
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $tablename . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      ob_end_clean();
     $objWriter->save("php://output");
    return $tablename . '.xlsx';
}

/* ********************************function 3 Excel for admin ************************************************************ */
// excel file will be created when admin export products from admin panel
function createExcelForExportAdmin($tablename, $url){
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
    foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->getStyle('A1:BZ1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A1:BZ1')->getFill()->getStartColor()->setARGB('7FC451');

    $getcat = selectQuery(PRODCAT, "id,parent_id,cat_name", "template='" . $tablename . "'");

    $getprodcat = selectQuery(PRODCAT, "id,cat_name", "id=" . $getcat[0]['parent_id']);
    $category = $getprodcat[0]['cat_name'];
    $subcategory = $getcat[0]['cat_name'];
    $categoryId = $getcat[0]['parent_id'];
    $subcategoryId = $getcat[0]['id'];
    $retval = showQuery($tablename);
    $tablcol = array();
    for ($i = 0; $i < count($retval); $i++) {array_push($tablcol, $retval[$i][0]);}

    $where = "";
    if ($seller) {$where = " and vendor=" . $seller;}
    $path = $_SERVER['HTTP_HOST'] . "/templatexcels";
    $getproduct = selectQuery(PRODINFO, "*", "sub_cat='" . $getcat[0]['id'] . "' " . $where . " order by id ASC");

    $target_path = getRelativePath($url, $path) . "/";

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");
    $attrsa = array();
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $colarr = array('Product ID', 'Product Type', 'Product Name', 'Main/Variation', 'Category', 'Subcategory', 'Company', 'Vendor', 'HSN Code', 'Variation Available?', 'Variation On', 'Variation Value', 'SKU', 'Stock', 'MRP', 'Tax', 'Vendor Price', 'Admin Price', 'Discount Price', 'Discount Start Date(YYYY-MM-DD)', 'Discount End Date(YYYY-MM-DD)', 'Weight(In kg)', 'Length(In cm)', 'Width(In cm)', 'Height(In cm)', 'Cancellation Required?', 'Return Days', 'COD Available', 'SEO Title', 'SEO Description', 'SEO Keywords', 'Is Approved', 'New Arrival', 'Trending Now', 'Is Active', 'Is Delete', 'Image1', 'Image2', 'Image3', 'Image4', 'Description');
    for ($i = 3; $i < count($tablcol); $i++) {   array_push($colarr, $tablcol[$i]. "(".getOriginalName($tablcol[$i]).")");    
        array_push($attrsa, $tablcol[$i]);}
    $endcount = count($getproduct) + 1;
    /* for($i=0;$i<count($colarr);$i++){
    $rowcnt=0;
    $cell = \PHPExcel_Cell::stringFromColumnIndex($i) . $rowcnt;
    if($i==0||$i==4||$i==5||$i==10){

    $objPHPExcel->getActiveSheet()->getStyle($cell.'1:'.$cell.$endcount)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    }else{
    $objPHPExcel->getActiveSheet()->getStyle($cell.'1:'.$cell.$endcount)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    }
    }*/

    //   $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);

    $mystt = (count($attrsa) ? implode(',', $attrsa) : '');

    $row = "1";
    for ($i = 0; $i < count($colarr); $i++) {
        $col = $i;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $colarr[$i]);
    }
    for ($i = 0; $i < count($getproduct); $i++) {
        $getvendor = selectQuery(VENDOR, "nickname", "dealer_id=" . $getproduct[$i]['vendor']);
        $row++;
        if ($getproduct[$i]['parent_id'] == 0) {$prodname = $getproduct[$i]['prod_name'];
            $isMainVar = "Main";} else {
            $getparentdata = selectQuery(PRODINFO, "prod_name", "id='" . $getproduct[$i]['parent_id'] . "'");
            $prodname = $getparentdata[0]['prod_name'];
            $isMainVar = "Variation";
        }
        $getimages = selectQuery(PRODIMG, "img_url", "prod_id='" . $getproduct[$i]['id'] . "'");
        $varidMainarra = array();
        if ($getproduct[$i]['variant_name1'] != "") { array_push($varidMainarra, $getproduct[$i]['variant_name1']); }
        if ($getproduct[$i]['variant_name2'] != "") { array_push($varidMainarra, $getproduct[$i]['variant_name2']); }
        if ($getproduct[$i]['variant_name3'] != "") { array_push($varidMainarra, $getproduct[$i]['variant_name3']); }

        $varidvalues = array();
        if ($getproduct[$i]['variant_value1'] != "") { array_push($varidvalues, $getproduct[$i]['variant_value1']); }
        if ($getproduct[$i]['variant_value2'] != "") {array_push($varidvalues, $getproduct[$i]['variant_value2']); }
        if ($getproduct[$i]['variant_value3'] != "") { array_push($varidvalues, $getproduct[$i]['variant_value3']); }

        $gettemplatedata = selectQuery($tablename, "*", "prod_id=" . $getproduct[$i]['id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $getproduct[$i]['id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $getproduct[$i]['product_type']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $prodname);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $isMainVar);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $getproduct[$i]['master_cat']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $getproduct[$i]['sub_cat']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $getproduct[$i]['prod_company']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $getvendor[0]['nickname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $getproduct[$i]['hsn_code']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $getproduct[$i]['variation_available']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, implode("|", $varidMainarra));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, implode("|", $varidvalues));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $getproduct[$i]['sku']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $getproduct[$i]['stock']-$getproduct[$i]['sold']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $getproduct[$i]['mrp']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $getproduct[$i]['tax']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $getproduct[$i]['vendor_reg_price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $getproduct[$i]['admin_price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $getproduct[$i]['vendor_sale_price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, ($getproduct[$i]['vendor_sale_start_date'] != "0000-00-00 00:00:00" ? date("Y-m-d", strtotime($getproduct[$i]['vendor_sale_start_date'])) : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, ($getproduct[$i]['vendor_sale_end_date'] != "0000-00-00 00:00:00" ? date("Y-m-d", strtotime($getproduct[$i]['vendor_sale_end_date'])) : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $getproduct[$i]['weight']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $getproduct[$i]['length']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $getproduct[$i]['width']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $getproduct[$i]['height']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $getproduct[$i]['is_cancellation_avail']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $getproduct[$i]['return_days']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $getproduct[$i]['is_cod_avail']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $getproduct[$i]['seo_title']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $getproduct[$i]['seo_description']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30, $row, $getproduct[$i]['seo_keywords']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $row, $getproduct[$i]['isApproved']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(32, $row, $getproduct[$i]['new_arrival']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(33, $row, $getproduct[$i]['trending_now']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $row, $getproduct[$i]['isActive']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(35, $row, 0);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(36, $row, (count($getimages) >= 1 ? $getimages[0]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(37, $row, (count($getimages) >= 2 ? $getimages[1]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38, $row, (count($getimages) >= 3 ? $getimages[2]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(39, $row, (count($getimages) >= 4 ? $getimages[3]['img_url'] : ""));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(40, $row, (count($gettemplatedata) ? $gettemplatedata[0]['highlight'] : ""));
        $lastcnt = 40;
        for ($t = 0; $t < count($attrsa); $t++) {$lastcnt++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($lastcnt, $row, (count($gettemplatedata) ? $gettemplatedata[0][$attrsa[$t]] : ""));
        }

    }

    // Set worksheet title
    $objPHPExcel->getActiveSheet()->setTitle("data");

    /* ---------------------------------Second sheet------------------------------------------------------------------- */
    $objPHPExcel->createSheet(1); //Setting index when creating
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(150);
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);


    /* Set read More values */
    $row = "1";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Field Name");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Value/Hint (Double Click in cell for more readable format)");
    cellColor(0, $row, "7FC451");  cellColor(1, $row, "7FC451");


    /* ID */
    $row = "3";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product ID");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Each New Product, Enter Value = 0");

    /* Type */
    $row = "5";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Type");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Product, Enter Value = Product");

    /* Name */
    $row = "7";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Name");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If Its Main Product, Enter The Product Name\r\rRules For Variation =\r1) If Its Variation, Enter The Name Of Main Product As It Is WITHOUT ADDING ANYTHING TO IT");
    cellColor(0, $row, "F4511E");

    /* variation */
    $row = "9";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Main/Variation");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If Its Main Product, Enter Value = Main\r\rRules For Variation =\r1) If Its Variation Product of main product, Enter Value = Variation");
    cellColor(0, $row, "F4511E");

    /* Category */
    $row = "11";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Category");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $categoryId . " (Category ID For -".$category.") - Please Dont Change This Value. And Apply For All Product In Excel");

    /* Sub-Category */
    $row = "13";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Sub-Category");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $subcategoryId . " (Sub-Category ID For -".$subcategory.") - Please Dont Change This Value. And Apply For All Product In Excel");

    /* Company */
    $row = "15";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Company");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Company Name Or Manufacturer Name Of The Product");

    /* HSN */
    $row = "17";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "HSN Code");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter HSN Code Of Product");

    /*is variation */
    $row = "19";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation Available");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) If No Variation Available, Enter Value = 0\r\rRules For Variation =\r1) In Case If Variation Available For Main Product, Enter Value = 1\r(Only In Front Of Main Product & In Front Of All Variations, Enter Value = 0)");
    cellColor(0, $row, "F4511E");

    $row = "21";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation On");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) In Case Of Main Product Which DO NOT Have Variation = Keep It Blank\r2) In Case Of Main Product Which Have Variations = Keep It Blank\r\rRules For Variation =\r1) In Case Of Variations = Enter Attributes IDs Separated Using Pipe Symbol.\r\rExample =\r1) If You Have Variations On Color And Size Then Combine Both Attributes Using Pipe Symbol & Write - Attr_1|Attr_2\r(Here Attr_1 & Attr_2 Are Used As Sample For Color & Size, Please Make Sure You Use Correct Attribute IDs)\r\rIMPORTANT =\rENTER ONLY ATTRIBUTE IDs AS SHOWN IN ABOVE EXAMPLE (WITHOUT ATTRIBUTE NAMES - Attr_1|Attr_2)\rSTRICTLY DO NOT ENTER LIKE Attr_1(Color)|Attr_2(Size)");
    cellColor(0, $row, "F4511E");

    $row = "23";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Variation Values");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Rules For Main Product =\r1) For Main Product - Keep This Blank\r\rRules For Variation =\r1) For Variations - Enter Value For Variation, Else Keep Blank If Not Applicable\r\rExample =\r1) As Per Above Example, If You Have Variations On Color (Attr_1) And Size (Attr_2) Then Write - Red|XL");
    cellColor(0, $row, "F4511E");

    /* SKU Code */
    $row = "25";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SKU Code");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SKU Code Of Product");

    /* Stock */
    $row = "27";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Stock");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter The Stock Of Product Or Variation.\r\rRules For Main Product =\r1)If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Stock\r\rRules For Variation =\r1) For Variations - Write Stock Of Respective Variation");
    cellColor(0, $row, "F4511E");

    /* MRP */
    $row = "29";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "MPR");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Max Retail Price of product (Compulsory Field)");

    /* Tax Percentage */
    $row = "31";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Tax Percetage");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Tax Percetage (Number Only)\rDont Add Pecentage Symbol (% Sign)\rExample =\r12% Should Be Entered As = 12");
    cellColor(0, $row, "F4511E");
    /* Vendor Percentage */
    $row = "33";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Vendor Price");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Vendor Price Value Without Comma\rExample =\rVendor Price 2,522 Should Be Entered As 2522\r\rRules For Main Product =\r1) For Main Product - If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Vendor Price\r\rRules For Variation =\r1) For Variations - Write Vendor Price Of Respective Variation");
    cellColor(0, $row, "F4511E");

    /* Sale Price */
    $row = "35";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Discount Price");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Discount Price Value Should Be Without Commas. Else Keep Blank If Not Available.\r\rRules For Main Product =\r1) If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Discount Price (If Applicable)\r\rRules For Variation =\r1) For Variations - Write Discount Price Of Respective Variation (If Applicable)");
    cellColor(0, $row, "F4511E");

    /* Sale Start & end */
    $row = "37";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Discount Start and End Date");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Discount Start and End Date in YYYY-MM-DD format. Keep Blank If Not Available\r\rRules For Main Product =\r1) If Variations Are Avaialble Of Main Product Then Keep It Blank, Else Write Discount Start And End Date (If Applicable)\r\rRules For Variation =\r1) For Variations - Write Discount Start And End Date Of Respective Variation (If Applicable)");
    cellColor(0, $row, "F4511E");

    /* Weight,Length,Width,Height  */
    $row = "39";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Weight,Length,Width,Height");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Respective Post Packaging Values\r\rJust Enter Numeric Values Without Kg, Gm, CM etc\r\r Example =\r1) 1.5Kg Should Be Entered As = 1.5 \r2) 5gm Should Be Entered As = 0.005");
    cellColor(0, $row, "F4511E");
    /* Cancellation */
    $row = "41";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Cancellation");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) If Cancellation Is Available On Product, Enter Value = 1\r\r2) If Cancellation Is NOT Available On Product, Enter Value = 0");

    /* Return Days */
    $row = "43";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Return Days");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) Enter Return Days\r\r2) If No Return Is Allowed, Enter Value = 0 ");

    /* COD  */
    $row = "45";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "COD");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) If COD Option Is Available On Product, Enter Value = 1\r\r2) If COD Option Is NOT Available On Product, Enter Value = 0");

    /* SEO Title */
    $row = "47";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Title");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Title (Keep Blank If Not Available)");

    /* SEO Title */
    $row = "49";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Description");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Description (Keep Blank If Not Available)");

    /* SEO Title */
    $row = "51";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "SEO Keywords");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter SEO Keywords (Keep Blank If Not Available)");

    /* Active  */
    $row = "53";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Is Active");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) For Activate Product, Enter Value = 1\r\r2) To De-Activate Product, Enter Value = 0");

    /* Delete  */
    $row = "55";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Is Delete");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "1) For Deleting Product, Enter Value = 1\r\r2) For No Action, Enter Value = 0");

    $row = "57";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Images");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "For Adding Images, Enter Valid Image Link To Sheet In Image Column.\r\rExample = \rImage1 - https://linkpicture.com/q/3d-render-image.jpg\r\rRules For Images =\r1) The Link Should Have Image Extension At The End");
    cellColor(0, $row, "F4511E");
    /* Product Description */
    $row = "59";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "Product Description");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, "Enter Product Description");

    /* Product Description */
    $rowcnt = "59";
    for ($i = 0; $i < count($attrsa); $i++) {
        $row = $rowcnt + 2;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $attrsa[$i] . " (You can use this fields as a variation fields)");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, getOriginalName($attrsa[$i]) . " (This Attribute Used In Subcategory - " . getAttributeCat($attrsa[$i]) . ") \rEnter The Desired Attribute Data");
        $rowcnt = $row;
        cellColor(0, $row, "F4511E");
    }

    $objPHPExcel->getActiveSheet()->setTitle("Instructions");

    /* ---------------------------------Second sheet------------------------------------------------------------------- */
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $tablename . '.xlsx"');
    ob_end_clean();
    $objWriter->save("php://output");
  // $objWriter->save($tablename.'.xlsx');
    return $tablename . '.xlsx';
}

?>



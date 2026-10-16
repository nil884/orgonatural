<?php include '../../includes/configuration.php'; require '../../phpSpreadsheet/autoload.php';
ini_set("display_errors",1); set_time_limit(0); ini_set('memory_limit','2048M'); 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
$vendor_data = $_POST['vendor_data']; $vendor_doc = $_POST['vendor_doc']; $buyer_data = $_POST['buyer_data']; 
$buyer_doc = $_POST['buyer_doc']; $buyer_address = $_POST['buyer_address']; 
$prod_data = $_POST['prod_data']; 
$prod_images = $_POST['prod_images']; $prodcat_data = $_POST['prodcat_data']; $prodcats_images = $_POST['prodcats_images']; 
$cart_data = $_POST['cart_data']; $order_data = $_POST['order_data'];
$filename = 'backup'.date("YmdHis").'.zip';
/* function to create backup of images */
function custom_copy($src, $dst){ 
    // open the source directory
    $dir = opendir($src); 
    // Make the destination directory if not exist
    @mkdir($dst); 
    // Loop through the files in source directory
    while($file = readdir($dir)){ 
        if(( $file != '.' ) && ( $file != '..' )){ 
            if( is_dir($src . '/' . $file) ){ 
                // Recursively calling custom copy function
                // for sub directory 
                custom_copy($src . '/' . $file, $dst . '/' . $file); 
            } else{ 
                copy($src . '/' . $file, $dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir);
} 
/* Remove all files and folders from folder */
$dir = '../../backup/';
recursiveDelete($dir);
function recursiveDelete($str){
    if(is_file($str)){ return @unlink($str); }
    elseif (is_dir($str)) {
        $scan = glob(rtrim($str,'/').'/*');
        foreach($scan as $index=>$path){ recursiveDelete($path); }
        return @rmdir($str);
    }
}
if(!is_dir($dir)){ @mkdir($dir);} 
/************************** Create backup of images************************* */
//custom_copy($src, $dst);
if($prod_images=='yes'){ custom_copy('../../img/productimg','../../backup/product-images');}  
if($vendor_doc=='yes'){ custom_copy('../../img/vendordocs_images','../../backup/vendor-documents');} 
if($buyer_doc=='yes'){ custom_copy('../../img/buyer_profile','../../backup/buyer-profilepics');} 
if($prodcats_images=='yes'){ custom_copy('../../img/categoryimage','../../backup/productcategory-images');} 
/* *************************End of Create backup of images************************* */
/* *************************Generate excel files*********************************** */
$spreadsheet = new Spreadsheet();
$spreadsheet1 = new Spreadsheet();
$spreadsheet2 = new Spreadsheet();
$spreadsheet3 = new Spreadsheet();
$spreadsheet4 = new Spreadsheet();
$spreadsheet5 = new Spreadsheet();
$spreadsheet6 = new Spreadsheet();
/* ***************************** Product Excel ********************************************** */
if($prod_data=='yes'){
$master = selectQuery(PRODCAT, 'id,cat_name,url_title', 'type="Master"');
for($m=0;$m<count($master);$m++){
    $sheetno = $m; $shittitle = $m+1;
    if($m!=0){$spreadsheet->createSheet();} 
    //Zero based, so set the second tab as active sheet
    $spreadsheet->setActiveSheetIndex($sheetno);
    $spreadsheet->getActiveSheet()->setTitle($master[$m]['url_title']);  
    //$spreadsheet->getActiveSheet()->setTitle('category-'. $shittitle); 
    $sheet = $spreadsheet->getActiveSheet();
    $columns = [
        'A' => 'Product ID','B' => 'Product Name','C' => 'Parent Category',
        'D' => 'Master Category','E' => 'Sub Category',
        'F' => 'Parent Product ID', 'G' => 'Company',
        'H' => 'Dealer','I' => 'SKU',
        'J' => 'HSN Code','K' => 'Variation On',
        'L' => 'Variation Values','M' => 'Stock',
        'N' => 'Tax','O' => 'MRP','P' => 'Vendor Price',
        'Q' => 'Admin Price','R' => 'Weight',
        'S' => 'Length','T' => 'Height',
        'U' => 'Width','V' => 'Is Cancellation Applicable',
        'W' => 'Is COD Applicable','X' => 'COD Charges',
        'Y' => 'Return Days', 'Z' => 'Product Description'
      ];
      $cnt = 26;
      $getsubformast = selectQuery(PRODCAT, 'id,cat_name,template', 'parent_id=' . $master[$m]['id']);
      for($i=0;$i<count($getsubformast);$i++){
        $gettemplate0 = showQuery($getsubformast[$i]['template']);
        foreach($gettemplate0 as $key=>$val){
          $fldname = getOriginalName($val['Field']);
            if($fldname){
                $searchfor = $fldname.' ('.$val['Field'].')';
                $pos = array_search($searchfor,$columns);
                if($pos==''){
                    $loc = $cnt + 1; $cor = Coordinate::stringFromColumnIndex($loc);
                    $columns[$cor] = $fldname.' ('.$val['Field'].')';
                    $cnt++;
                }
            }
        }
    }
    $imgfrom = $cnt+1;
    for($i=$imgfrom;$i<$imgfrom+6;$i++){
        $loc = $i; $cor = Coordinate::stringFromColumnIndex($loc); $columns[$cor] = 'Product Image';
    }
    sleep(5);
    foreach ($columns as $index => $column){
        $sheet->setCellValue($index."1", $column);
    } 
    $getproduct = selectQuery(PRODINFO, "*",'master_cat='.$master[$m]['id']);
    if(count($getproduct)){
        for($j=0;$j<count($getproduct);$j++){
            $sr=$j+1;
            $rindex = $sr+1;
            $row = $getproduct[$j];
            $getparentcat = selectQuery(PRODCAT, 'id,cat_name', 'id=' . $row['parent_cat']);
            $getmastercat = selectQuery(PRODCAT, 'id,cat_name', 'id=' . $row['master_cat']);
            $getsubcat = selectQuery(PRODCAT, 'id,cat_name,template', 'id=' . $row['sub_cat']);
            $getvendor = selectQuery(VENDOR, 'dealer_name', 'dealer_id=' . $row['vendor']);
            $gettemplate = selectQuery($getsubcat[0]['template'], '*', 'prod_id=' . $row['id']);
            $getimages = selectQuery(PRODIMG, "img_name", "prod_id=" . $row['id']);
            $tempc = count($gettemplate);
            $varidMainarra = array();
            if($row['variant_name1'] != ""){array_push($varidMainarra, getOriginalName($row['variant_name1']));}
            if($row['variant_name2'] != ""){array_push($varidMainarra, getOriginalName($row['variant_name2']));}
            if($row['variant_name3'] != ""){array_push($varidMainarra, getOriginalName($row['variant_name3']));}
            $varidvalues = array();
            if($row['variant_value1'] != ""){array_push($varidvalues, $row['variant_value1']);}
            if($row['variant_value2'] != ""){array_push($varidvalues, $row['variant_value2']);}
            if($row['variant_value3'] != ""){array_push($varidvalues, $row['variant_value3']);}
            $sheet->setCellValue("A".$rindex, $row['id']);
            $sheet->setCellValue("B".$rindex, $row['prod_name']);
            $sheet->setCellValue("C".$rindex, $getparentcat[0]['cat_name']);
            $sheet->setCellValue("D".$rindex, $getmastercat[0]['cat_name']);
            $sheet->setCellValue("E".$rindex, $getsubcat[0]['cat_name']);
            $sheet->setCellValue("F".$rindex, $row['parent_id']);
            $sheet->setCellValue("G".$rindex, $row['prod_company']); 
            $sheet->setCellValue("H".$rindex, $getvendor[0]['dealer_name']);  
            $sheet->setCellValue("I".$rindex, $row['sku']); 
            $sheet->setCellValue("J".$rindex, $row['hsn_code']); 
            $sheet->setCellValue("K".$rindex, implode("|", $varidMainarra)); 
            $sheet->setCellValue("L".$rindex, implode("|", $varidvalues)); 
            $sheet->setCellValue("M".$rindex, ($tempc!=0?($row['stock']-$row['sold']-$row['block']):'')); 
            $sheet->setCellValue("N".$rindex, $row['tax']); 
            $sheet->setCellValue("O".$rindex, ($row['mrp']!=0?$row['mrp']:'')); 
            $sheet->setCellValue("P".$rindex, ($row['vendor_reg_price']!=0?$row['vendor_reg_price']:'')); 
            $sheet->setCellValue("Q".$rindex, ($row['admin_price']!=0?$row['admin_price']:'')); 
            $sheet->setCellValue("R".$rindex, ($tempc!=0?$row['weight']:'')); 
            $sheet->setCellValue("S".$rindex, ($tempc!=0?$row['lenght']:'')); 
            $sheet->setCellValue("T".$rindex, ($tempc!=0?$row['height']:''));
            $sheet->setCellValue("U".$rindex, ($tempc!=0?$row['weight']:'')); 
            $sheet->setCellValue("V".$rindex, ($tempc!=0?$row['is_cancelation_avail']:'')); 
            $sheet->setCellValue("W".$rindex, ($tempc!=0?$row['is_cod_avail']:'')); 
            $sheet->setCellValue("X".$rindex, ($tempc!=0?($row['is_cod_avail']!=0?$row['cod_charges']:''):'')); 
            $sheet->setCellValue("Y".$rindex, ($tempc!=0?$row['return_days']:'')); 
            $sheet->setCellValue("Z".$rindex, ($tempc!=0?$gettemplate[0]['highlight']:'')); 
            if($tempc){
               foreach($gettemplate[0] as $key1=>$val1){
                    if(getOriginalName($key1)){
                        $searchfor1 = getOriginalName($key1).' ('.$key1.')';
                        $pos1 = array_search($searchfor1,$columns);
                        if($pos1){ $sheet->setCellValue($pos1.$rindex, $val1); }
                    }
                }
            }
            for($im=0;$im<count($getimages);$im++){
                $loc = $imgfrom + $im;
                $cor = Coordinate::stringFromColumnIndex($loc);  
                $sheet->setCellValue($cor.$rindex, $getimages[$im]['img_name']);  
            } 
        } 
    }  
}
$spreadsheet->setActiveSheetIndex(0);
$writer = new Xlsx($spreadsheet);
$writer->save('../../backup/products.xlsx');
}
/***************************** End Of Product Excel *********************************************/
/****************************** Users Excel ***********************************************/
if($buyer_data=='yes'){
    $spreadsheet1->getActiveSheet()->setTitle('Users');  
    //$spreadsheet->getActiveSheet()->setTitle('category-'. $shittitle); 
    $sheet1 = $spreadsheet1->getActiveSheet();
    $columns = [
    'A' => 'User ID','B' => 'First Name','C' => 'last Name',
    'D' => 'Gender','E' => 'Mobile No',
    'F' => 'Email', 'G' => 'Registration Source',
    'H' => 'Registration Date','I' => 'Password',
    'J' => 'Tax No','K' => 'Company Name',
    'L' => 'Company Address','M' => 'Bank Name',
    'N' => 'Account Name','O' => 'Account No',
    'P' => 'IFSC','Q' => 'UPI','R' => 'Profile Pic',
    'S' => 'Status'];
    foreach ($columns as $index => $column){ $sheet1->setCellValue($index."1", $column); }
    $buyer = selectQuery(BUYER, '*', '1');
    for($m=0;$m<count($buyer);$m++){
        $sr = $m+1;
        $rindex = $sr+1;
        $row = $buyer[$m];
        $sheet1->setCellValue("A".$rindex, $row['u_id']);
        $sheet1->setCellValue("B".$rindex, $row['u_fname']);
        $sheet1->setCellValue("C".$rindex, $row['u_lname']);
        $sheet1->setCellValue("D".$rindex, $row['u_gender']);
        $sheet1->setCellValue("E".$rindex, $row['u_mobile']);
        $sheet1->setCellValue("F".$rindex, $row['u_email']);
        $sheet1->setCellValue("G".$rindex, ($row['source']?$row['source']:'website')); 
        $sheet1->setCellValue("H".$rindex, $row['reg_date']);  
        $sheet1->setCellValue("I".$rindex, $row['password']); 
        $sheet1->setCellValue("J".$rindex, $row['tax_no']); 
        $sheet1->setCellValue("K".$rindex, $row['company_name']); 
        $sheet1->setCellValue("L".$rindex, $row['company_address']); 
        $sheet1->setCellValue("M".$rindex, $row['bank_name']); 
        $sheet1->setCellValue("N".$rindex, $row['account_name']); 
        $sheet1->setCellValue("O".$rindex, $row['account_no']); 
        $sheet1->setCellValue("P".$rindex, $row['ifsc_code']); 
        $sheet1->setCellValue("Q".$rindex, $row['upi_id']); 
        $sheet1->setCellValue("R".$rindex, $row['profile_pic']); 
        $sheet1->setCellValue("S".$rindex, ($row['isActive']==1?'Active':'Not Active')); 
    }
    $writer1 = new Xlsx($spreadsheet1);
    $writer1->save('../../backup/users.xlsx');
}
/***************************************** Users Excel**************************************************************/
/* ***************************** User Addresses Excel***********************************************/
if($buyer_address=='yes'){
    $spreadsheet2->getActiveSheet()->setTitle('User Addresses');  
    // $spreadsheet->getActiveSheet()->setTitle('category-'. $shittitle); 
    $sheet2 = $spreadsheet2->getActiveSheet();
    $columns = [
    'A' => 'Address ID','B' => 'User Id','C' => 'Address Type',
    'D' => 'Contact Person Name','E' => 'Mobile No',
    'F' => 'Address', 'G' => 'Landmark',
    'H' => 'City','I' => 'State',
    'J' => 'Country','K' => 'Pincode'];
    foreach($columns as $index => $column){ $sheet2->setCellValue($index."1", $column); }
    $address = selectQuery(ADDRESS, '*', '1');
    for($m=0;$m<count($address);$m++){
        $sr=$m+1;
        $rindex = $sr+1;
        $row = $address[$m];
        $sheet2->setCellValue("A".$rindex, $row['id']);
        $sheet2->setCellValue("B".$rindex, $row['user_id']);
        $sheet2->setCellValue("C".$rindex, $row['address_type']);
        $sheet2->setCellValue("D".$rindex, $row['address_name']);
        $sheet2->setCellValue("E".$rindex, $row['mobile_number']);
        $sheet2->setCellValue("F".$rindex, $row['address']);
        $sheet2->setCellValue("G".$rindex, $row['landmark']); 
        $sheet2->setCellValue("H".$rindex, $row['city']);  
        $sheet2->setCellValue("I".$rindex, $row['state']); 
        $sheet2->setCellValue("J".$rindex, $row['country']); 
        $sheet2->setCellValue("K".$rindex, $row['pincode']); 
    }
    $writer2 = new Xlsx($spreadsheet2);
    $writer2->save('../../backup/users addresses.xlsx');
}
/*****************************************Users Addresses Excel************************************************************* */
/* ***************************** Product Categories Excel ********************************************** */
if($prodcat_data=='yes'){
    $spreadsheet3->getActiveSheet()->setTitle('Product Categories');  
    // $spreadsheet->getActiveSheet()->setTitle('category-'. $shittitle); 
    $sheet3 = $spreadsheet3->getActiveSheet();
    $columns = [
    'A' => 'Category ID','B' => 'Category Type','C' => 'Category Name',
    'D' => 'Parent Category ID','E' => 'Parent Category Name',
    'F' => 'Category Image', 'G' => 'Status'];
    foreach($columns as $index => $column){ $sheet3->setCellValue($index."1", $column); }
    $cats = selectQuery(PRODCAT, '*', '1');
    for($m=0;$m<count($cats);$m++){
        $sr = $m+1; $rindex = $sr+1; $row = $cats[$m];
        if($row['parent_id']!=0){
            $pcats = selectQuery(PRODCAT, 'cat_name', 'id='.$row['parent_id']);
            $parent = $pcats[0]['cat_name'];
        } else{ $parent = ''; }
        $sheet3->setCellValue("A".$rindex, $row['id']);
        $sheet3->setCellValue("B".$rindex, $row['type']);
        $sheet3->setCellValue("C".$rindex, $row['cat_name']);
        $sheet3->setCellValue("D".$rindex, ($row['parent_id']!=0?$row['parent_id']:''));
        $sheet3->setCellValue("E".$rindex, $parent);
        $sheet3->setCellValue("F".$rindex, $row['img_name']);
        $sheet3->setCellValue("G".$rindex, ($row['isActive']==1?'Active':'Not Active'));
    }
    $writer3 = new Xlsx($spreadsheet3);
    $writer3->save('../../backup/product categories.xlsx');
}
/***************************************** Product Caterogies Excel ************************************************************* */
/* ***************************** Vendor Excel ********************************************** */
if($vendor_data=='yes'){
    $spreadsheet4->getActiveSheet()->setTitle('Vendors');  
    //$spreadsheet->getActiveSheet()->setTitle('category-'. $shittitle); 
    $sheet4 = $spreadsheet4->getActiveSheet();
    $columns = [
    'A' => 'Vendor ID','B' => 'Name','C' => 'Nickname','D' => 'Email',
    'E' => 'Contact No','F' => 'Alternate Contact No',
    'G' => 'Company/Shop', 'H' => 'Address', 'I' => 'Locality', 'J' => 'City'
    , 'K' => 'State', 'L' => 'Country', 'M' => 'Pincode', 'N' => 'Office Contact No', 'O' => 'Office Email',
    'P' => 'PAN No','Q' => 'PAN Document','R' => 'TAN No','S' => 'TAN Document','T' => 'Reg. No'
    , 'U' => 'Reg. Document', 'V' => 'GST No', 'W' => 'GST Document', 'X' => 'Cancelled Cheque', 'Y' => 'Photo', 'Z' => 'Bank Name', 'AA' => 'Branch'
    , 'AB' => 'Beneficiary Name', 'AC' => 'Account No', 'AD' => 'IFSC', 'AE' => 'Password', 'AF' => 'Disbustment Cycle Days', 'AG' => 'Disbustment Auto pay', 'AH' => 'Auto Approve Product'
    , 'AI' => 'Bulk Import Products', 'AJ' => 'Is Approved','AK' => 'Status'];
    foreach($columns as $index => $column){ $sheet4->setCellValue($index."1", $column); }
    $vendors = selectQuery(VENDOR, '*', '1');
    for($m=0;$m<count($vendors);$m++){
        $sr = $m+1;
        $rindex = $sr+1;
        $row = $vendors[$m];
        $sheet4->setCellValue("A".$rindex, $row['dealer_id']);
        $sheet4->setCellValue("B".$rindex, $row['dealer_name']);
        $sheet4->setCellValue("C".$rindex, $row['nickname']);
        $sheet4->setCellValue("D".$rindex, $row['email']);
        $sheet4->setCellValue("E".$rindex, $row['personalcontactno']);
        $sheet4->setCellValue("F".$rindex, $row['extracontactno']);
        $sheet4->setCellValue("G".$rindex, $row['shopname']);
        $sheet4->setCellValue("H".$rindex, $row['officeadress']);
        $sheet4->setCellValue("I".$rindex, $row['locality']);
        $sheet4->setCellValue("J".$rindex, $row['city']);
        $sheet4->setCellValue("K".$rindex, $row['state']);
        $sheet4->setCellValue("L".$rindex, $row['country']);
        $sheet4->setCellValue("M".$rindex, $row['pin']);
        $sheet4->setCellValue("N".$rindex, $row['officecontactno']);
        $sheet4->setCellValue("O".$rindex, $row['communicationemail']);
        $sheet4->setCellValue("P".$rindex, $row['panno']);
        $sheet4->setCellValue("Q".$rindex, $row['pandoc']);
        $sheet4->setCellValue("R".$rindex, $row['tanno']);
        $sheet4->setCellValue("S".$rindex, $row['tandoc']);
        $sheet4->setCellValue("T".$rindex, $row['regno']);
        $sheet4->setCellValue("U".$rindex, $row['regdoc']);
        $sheet4->setCellValue("V".$rindex, $row['vatno']);
        $sheet4->setCellValue("W".$rindex, $row['vatdoc']);
        $sheet4->setCellValue("X".$rindex, $row['chequedoc']);
        $sheet4->setCellValue("Y".$rindex, $row['otherdoc']);
        $sheet4->setCellValue("Z".$rindex, $row['bnkname']);
        $sheet4->setCellValue("AA".$rindex, $row['branchname']);
        $sheet4->setCellValue("AB".$rindex, $row['beneficiary']);
        $sheet4->setCellValue("AC".$rindex, $row['acntno']);
        $sheet4->setCellValue("AD".$rindex, $row['IFSCcode']);
        $sheet4->setCellValue("AE".$rindex, $row['password']);
        $sheet4->setCellValue("AF".$rindex, $row['disbursement_cycle_days']);
        $sheet4->setCellValue("AG".$rindex, $row['disburstment_auto_pay']);
        $sheet4->setCellValue("AH".$rindex, $row['auto_approve_product']);
        $sheet4->setCellValue("AI".$rindex, $row['bulk_import']);
        $sheet4->setCellValue("AJ".$rindex, ($row['isApproved']==1?'Approved':'Not Approved'));
        $sheet4->setCellValue("AK".$rindex, ($row['isActive']==1?'Active':'Not Active')); 
    }
    $writer4 = new Xlsx($spreadsheet4);
    $writer4->save('../../backup/vendors.xlsx');
}
/***************************************** Vendor Excel ************************************************************* */
/****************************** Product Categories Excel ********************************************** */
if($cart_data=='yes'){
    $spreadsheet5->getActiveSheet()->setTitle('Cart And Wishlist');  
    // $spreadsheet->getActiveSheet()->setTitle('category-'. $shittitle); 
    $sheet5 = $spreadsheet5->getActiveSheet();
    $columns = [
    'A' => 'Type','B' => 'User ID','C' => 'User Name',
    'D' => 'Product ID','E' => 'Product Name',
    'F' => 'Quantity', 'G' => 'Added On'];
    foreach($columns as $index => $column){ $sheet5->setCellValue($index."1", $column); }
    $cats = selectQuery(PRODCAT, '*', '1');
    for($m=0;$m<count($cats);$m++){
        $sr = $m+1; $rindex = $sr+1; $row = $cats[$m];
        $buyer = selectQuery(BUYER,'CONCAT(u_fname," ",u_lname) as username','u_id='.$row['user_id']);
        $prod = selectQuery(PRODINFO,'prod_name','id='.$row['product_id']);
        $sheet5->setCellValue("A".$rindex, $row['type']);
        $sheet5->setCellValue("B".$rindex, $row['user_id']);
        $sheet5->setCellValue("C".$rindex, $buyer[0]['username']);
        $sheet5->setCellValue("D".$rindex, $row['product_id']);
        $sheet5->setCellValue("E".$rindex, $prod[0]['prod_name']);
        $sheet5->setCellValue("F".$rindex, $row['quantity']);
        $sheet5->setCellValue("G".$rindex, $row['added_on']);
    }
    $writer5 = new Xlsx($spreadsheet5);
    $writer5->save('../../backup/cart and wishlist.xlsx');
}
/* **************************************** Cart and wishlist Excel ************************************************************* */
/* ***************************** Order Excel ********************************************** */
if($order_data=='yes'){
    $spreadsheet6->getActiveSheet()->setTitle('Orders');  
    //$spreadsheet->getActiveSheet()->setTitle('category-'. $shittitle); 
    $sheet6 = $spreadsheet6->getActiveSheet();
    $columns = [
    'A'=>'ID' ,'B' => 'Order Type','C' => 'Transaction ID','D' => 'Transaction Date',
    'E' => 'Order No','F' => 'User ID', 'G' => 'User Name', 'H' => 'Payment Mode', 'I' => 'Total Taxable Amount',
    'J' => 'Shipping Charges', 'K'=>'Total GST', 'L'=>'Discount Type', 'M'=>'Discount Code', 'N'=>'Discount Amount',
    'O'=>'COD Charges', 'P'=>'Total Payable', 'Q'=>'Payment ID', 'R'=>'Payment Status', 'S'=>'User Address ID',
    'T'=> 'Shipping Address Type','U'=> 'Shipping Address Name','V'=> 'Shipping Mobile No','W'=> 'Shipping Address','X'=> 'Shipping Address Landmark','Y'=> 'Shipping Address City','Z'=> 'Shipping Address State','AA'=> 'Shipping Address Country','AB'=> 'Shipping Address Pincode'];
    foreach($columns as $index => $column){ $sheet6->setCellValue($index."1", $column); }
    $cats = selectQuery(ORDER, '*', '1');
    for($m=0;$m<count($cats);$m++){
        $sr = $m+1;
        $rindex = $sr+1;
        $row = $cats[$m];
        $buyer = selectQuery(BUYER,'CONCAT(u_fname," ",u_lname) as username','u_id='.$row['user_id']);
        $sheet6->setCellValue("A".$rindex, $row['id']);
        $sheet6->setCellValue("B".$rindex, $row['orderType']);
        $sheet6->setCellValue("C".$rindex, $row['transaction_id']);
        $sheet6->setCellValue("D".$rindex, $row['order_date']);
        $sheet6->setCellValue("E".$rindex, $row['order_id']);
        $sheet6->setCellValue("F".$rindex, $row['user_id']);
        $sheet6->setCellValue("G".$rindex, $buyer[0]['username']);
        $sheet6->setCellValue("H".$rindex, $row['payment_mode']);
        $sheet6->setCellValue("I".$rindex, $row['total_taxable_amount']);
        $sheet6->setCellValue("J".$rindex, ($row['isFreeShipping']==1?'':$row['total_shipping_charges']));
        $sheet6->setCellValue("K".$rindex, $row['total_gst']);
        $sheet6->setCellValue("L".$rindex, $row['discount_type']);
        $sheet6->setCellValue("M".$rindex, $row['discount_code']);
        $sheet6->setCellValue("N".$rindex, $row['discount_amount']);
        $sheet6->setCellValue("O".$rindex, $row['total_cod_charges']);
        $sheet6->setCellValue("P".$rindex, $row['payable_amount']);
        $sheet6->setCellValue("Q".$rindex, $row['payment_id']);
        $sheet6->setCellValue("R".$rindex, $row['payment_status']);
        $sheet6->setCellValue("S".$rindex, $row['shippingAddr_id']);
        $sheet6->setCellValue("T".$rindex, $row['shippingAddr_type']);
        $sheet6->setCellValue("U".$rindex, $row['shippingAddr_name']);
        $sheet6->setCellValue("V".$rindex, $row['shippingAddr_mobile']);
        $sheet6->setCellValue("W".$rindex, $row['shippingAddr_address']);
        $sheet6->setCellValue("X".$rindex, $row['shippingAddr_landmark']);
        $sheet6->setCellValue("Y".$rindex, $row['shippingAddr_city']);
        $sheet6->setCellValue("Z".$rindex, $row['shippingAddr_state']);
        $sheet6->setCellValue("AA".$rindex, $row['shippingAddr_country']);
        $sheet6->setCellValue("AB".$rindex, $row['shippingAddr_pincode']);   
    }
    /* 2nd sheet */
    $spreadsheet6->createSheet(); 
    // Zero based, so set the second tab as active sheet
    $spreadsheet6->setActiveSheetIndex(1);
    $spreadsheet6->getActiveSheet()->setTitle('Order Items');  
    $sheet6 = $spreadsheet6->getActiveSheet();
    $columns = [
    'A' => 'Order ID','B' => 'Order No','C' => 'Order Status',
    'D' => 'Product ID','E' => 'Product Name',
    'F' => 'Vendor ID', 'G' => 'Vendor Name',
    'H' => 'Price','I' => 'Tax Percentage',
    'J' => 'Quantity','K' => 'Taxable',
    'L' => 'Discount Type','M' => 'Discount Code',
    'N' => 'Discount Amount','O' => 'CGST %','P' => 'CGST Amount',
    'Q' => 'SGST %','R' => 'SGST Amount',
    'S' => 'IGST %','T' => 'IGST Amount',
    'U' => 'Shipping Charges','V' => 'COD Charges',
    'W' => 'Payable','X' => 'Shipping Mode',
    'Y' => 'Courier By', 'Z' => 'Shipping Order ID',
    'AA' => 'Shipping Shipment ID', 'AB' => 'AWB No', 'AC' => 'Manifest', 'AD' => 'Label',
    'AE' => 'Delivered On',
    'AF' => 'Tracking ID','AG' => 'Tracking Link','AH' => 'Tracking Last Location',
    'AI' => 'Canceled By','AJ' => 'Cancelation Date','AK' => 'Cancelation Reason','AL' => 'Return Valid Till',
    'AM' => 'Return Requested','AN' => 'Return Request Date', 'AO' => 'Reason For Return', 'AP' => 'Return Order ID',
    'AQ' => 'Return Shipment ID','AR' => 'Return AWB No', 'AS' => 'Return Shipping By',
    'AT' => 'Return Courier By','AU' => 'Return Tracking ID','AV' => 'Reason Tracking Link','AW' => 'Return Tracking Location',
    'AX' => 'Return Delivered Date', 'AY' => 'Vendor Disbustment Date',
    'AZ' => 'Is Refund Applicable','BA' => 'Refundable Amount','BB' => 'Refund Status',
    'BC' => 'Refund Date','BD' => 'Refund ID','BE' => 'Refund Response'];
    foreach ($columns as $index => $column){ $sheet6->setCellValue($index."1", $column); }
    $subord = selectQuery(ORDERSUB, '*', '1');
    for($m=0;$m<count($subord);$m++){
      $sr=$m+1;
      $rindex = $sr+1;
      $row = $subord[$m];
      $getorderno = selectQuery(ORDER, 'order_id', 'id='.$row['order_id']);
      $sheet6->setCellValue("A".$rindex, $row['order_id']);
      $sheet6->setCellValue("B".$rindex, $getorderno[0]['order_id']);
      $sheet6->setCellValue("C".$rindex, $row['order_current_Status']);
      $sheet6->setCellValue("D".$rindex, $row['product_id']);
      $sheet6->setCellValue("E".$rindex, $row['product_name']);
      $sheet6->setCellValue("F".$rindex, $row['vendor']);
      $sheet6->setCellValue("G".$rindex, $row['vendor_name']);
      $sheet6->setCellValue("H".$rindex, $row['user_per_unit_withoutgst_price']);
      $sheet6->setCellValue("I".$rindex, $row['tax_percentage']);
      $sheet6->setCellValue("J".$rindex, $row['tax_percentage']);
      $sheet6->setCellValue("K".$rindex, $row['taxable_without_gst']);
      $sheet6->setCellValue("L".$rindex, $row['discount_type']);
      $sheet6->setCellValue("M".$rindex, $row['discount_code']);
      $sheet6->setCellValue("N".$rindex, $row['discount_amount']);
      $sheet6->setCellValue("O".$rindex, $row['cgst1']);
      $sheet6->setCellValue("P".$rindex, $row['cgst2']);
      $sheet6->setCellValue("Q".$rindex, $row['sgst1']);
      $sheet6->setCellValue("R".$rindex, $row['sgst2']);
      $sheet6->setCellValue("S".$rindex, $row['igst1']);
      $sheet6->setCellValue("T".$rindex, $row['igst2']);
      $sheet6->setCellValue("U".$rindex,  ($row['isFreeShipping']==1?'0':$row['total_shipping_charges']));
      $sheet6->setCellValue("V".$rindex, $row['cod_charges']);
      $sheet6->setCellValue("W".$rindex, $row['total_payable']);
      $sheet6->setCellValue("X".$rindex, $row['shipping_by']);
      $sheet6->setCellValue("Y".$rindex, $row['selected_courier_name']);
      $sheet6->setCellValue("Z".$rindex, $row['shipping_order_id']);
      $sheet6->setCellValue("AA".$rindex, $row['shipping_shipment_id']);
      $sheet6->setCellValue("AB".$rindex, $row['shipping_awb_no']);
      $sheet6->setCellValue("AC".$rindex, $row['manifest']);
      $sheet6->setCellValue("AD".$rindex, $row['label']);
      $sheet6->setCellValue("AE".$rindex, $row['delivered_on']);
      $sheet6->setCellValue("AF".$rindex, $row['tracking_id']);
      $sheet6->setCellValue("AG".$rindex, $row['tracking_link']);
      $sheet6->setCellValue("AH".$rindex, $row['tracking_location']);
      $sheet6->setCellValue("AI".$rindex, $row['cancelled_by']);
      $sheet6->setCellValue("AJ".$rindex, $row['cancelation_date']);
      $sheet6->setCellValue("AK".$rindex, $row['cancel_reason']);
      $sheet6->setCellValue("AL".$rindex, $row['return_valid_till']);
      $sheet6->setCellValue("AM".$rindex, ($row['is_returned']==1?'Yes':'No'));
      $sheet6->setCellValue("AN".$rindex, $row['return_request_date']);
      $sheet6->setCellValue("AO".$rindex, (isset($row['return_reason'])?$row['return_reason']:''));
      $sheet6->setCellValue("AP".$rindex, (isset($row['return_order_id'])?$row['return_order_id']:''));
      $sheet6->setCellValue("AQ".$rindex, (isset($row['return_shipment_id'])?$row['return_shipment_id']:''));
      $sheet6->setCellValue("AR".$rindex, (isset($row['return_awb_no'])?$row['return_awb_no']:''));
      $sheet6->setCellValue("AS".$rindex, (isset($row['return_shipping_by'])?$row['return_shipping_by']:''));
      $sheet6->setCellValue("AT".$rindex, (isset($row['return_selected_courier_name'])?$row['return_selected_courier_name']:''));
      $sheet6->setCellValue("AU".$rindex, (isset($row['return_tracking_id'])?$row['return_tracking_id']:''));
      $sheet6->setCellValue("AV".$rindex, (isset($row['return_tracking_link'])?$row['return_tracking_link']:''));
      $sheet6->setCellValue("AW".$rindex, (isset($row['return_tracking_location'])?$row['return_tracking_location']:''));
      $sheet6->setCellValue("AX".$rindex, (isset($row['return_delivered_on'])?$row['return_delivered_on']:''));
      $sheet6->setCellValue("AY".$rindex, $row['disbustment_date']);
      $sheet6->setCellValue("AZ".$rindex, ($row['is_refund_appilicable']==1?'Yes':'No'));
      $sheet6->setCellValue("BA".$rindex, $row['refundable_amount']);
      $sheet6->setCellValue("BB".$rindex, $row['refund_status']);
      $sheet6->setCellValue("BC".$rindex, $row['refund_date']);
      $sheet6->setCellValue("BD".$rindex, $row['refund_id']);
      $sheet6->setCellValue("BE".$rindex, $row['refund_response']);
    }       
    $spreadsheet6->setActiveSheetIndex(0);
    $writer6 = new Xlsx($spreadsheet6);
    $writer6->save('../../backup/order.xlsx');
}
/***************************************** Order Excel ************************************************************* */

zipData('../../backup', '../../backupzip/'.$filename);
//Here the magic happens :)
function zipData($source, $destination){
    if(extension_loaded('zip') === true){
        if(file_exists($source) === true){
            $zip = new ZipArchive();
            if($zip->open($destination, ZIPARCHIVE::CREATE) === true){
                $source = realpath($source);
                if(is_dir($source) === true){
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                    foreach($files as $file){
                        $file = realpath($file);
                        if(is_dir($file) === true){
                            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        } else if(is_file($file) === true){
                            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                    }
                } else if (is_file($source) === true) {
                    $zip->addFromString(basename($source), file_get_contents($source));
                }
            }
           
            return $zip->close();
        }
    }
    return false;
} 
echo '../../backupzip/'.$filename;
?>
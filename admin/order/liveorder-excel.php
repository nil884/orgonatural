<?php
require '../../includes/configuration.php';
require '../../vendor/autoload.php';
ini_set("display_errors",1);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); 
$orderdata0 = $order->getVendorOrders("","'Generated','New Order','New','Invoiced'");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
//$sheet->setCellValue('A1', 'Hello World !');
$columns = [
    'A' => 'Sr.No',
    'B' => 'Order ID',
    'C' => 'Buyer Name',
    'D' => 'Mobile',
    'E' => 'Address',
    'F' => 'Pincode',
    'G' => 'Amount',
    'H' => 'Payment Status'
  ];
  
  foreach ($columns as $index => $column) {
    $sheet->setCellValue($index."1", $column);
  }

  if(count($orderdata0)){
    for($j=0;$j<count($orderdata0);$j++){
        $sr=$j+1;
        $rindex = $sr+1;
        $row = $orderdata0[$j];
        $orderdata = $order->getOrderFullDetails($row['transaction_id']);
        $orderid = $row['order_id'];
        $buyer = ($row['user_id']?$row['uname']:$row['shippingAddr_name']);
        $status = ($orderdata['payment_mode']=="COD"?$orderdata['payment_mode']:"Online Paid");
         $address = $orderdata['address'].' '.$orderdata['landmark']."  ".$orderdata['city']." ".$orderdata['state']." ".$orderdata['country'];
        $pincode = $orderdata['pincode']; 
        $sheet->setCellValue("A".$rindex, $sr);
        $sheet->setCellValue("B".$rindex, $orderid);
        $sheet->setCellValue("C".$rindex, $buyer);
        $sheet->setCellValue("D".$rindex, $orderdata['shipping_mobile']);
         $sheet->setCellValue("E".$rindex, $address);
        $sheet->setCellValue("F".$rindex, $pincode);
        $sheet->setCellValue("G".$rindex, $orderdata['payable_amount']); 
        $sheet->setCellValue("H".$rindex, $status);  
    }
}      

$writer = new Xlsx($spreadsheet);
//$writer->save('hello world.xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="live orders.xlsx');
$writer->save('php://output');

?>
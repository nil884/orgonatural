<?php include "../../includes/configuration.php";
$isActive = $getconfigdetails[0]['isActive_oneSignal'];
$appid = $getconfigdetails[0]['oneSignal_appId'];
$apikey = $getconfigdetails[0]['oneSignal_apiKey'];
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://onesignal.com/api/v1/notifications?app_id=".$appid."&limit=50&offset=0",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array( "authorization: Basic ".$apikey, ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$data = json_decode($response, true);

$total = $data['total_count']; $total = $data['total_count']; $notification = $data['notifications']; 
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Push Notification History</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body id="bodload">
<div class="page-body-wrapper" id="prod-list-col">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Push Notification History</h5></div>
                <div class="card-body">
                    <div id="tablereload">
                        <table class="example table table-bordered product-data-table w-100" id="Vendor-product">
                            <thead><tr><th>#</th><th>Time</th><th>Heading</th><th>Message</th><th>Send Count</th></tr></thead>
                            <tbody id="loaddatatabel">
                                <?php for($i=0;$i<count($notification);$i++){
                                $heading = $notification[$i]['headings']['en'];
                                $content = $notification[$i]['contents']['en'];
                                $completed_at = ($notification[$i]['completed_at']?$notification[$i]['completed_at']:$notification[$i]['queued_at']);
                                $successful = $notification[$i]['successful'];
                                //echo "<pre>"; print_r($notification[$i]);
                                ?>
                                <tr>
                                    <td><?php echo $i+1 ?></td>
                                    <td><?php $dt = new DateTime("@".$completed_at);
                                    $dt->setTimeZone(new DateTimeZone('Asia/Kolkata'));
                                    echo $dt->format('d-m-Y h:i a'); ?></td>
                                    <td><?php echo $heading; ?></td>
                                    <td><?php echo $content; ?></td>
                                    <td><?php echo $successful; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
loaddatatable();
function loaddatatable(){ $('.product-data-table').DataTable({ "scrollX": true }); }
</script>
</body>
</html>
<?php include("../../includes/configuration.php");
//$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
function timezone_list() {
    static $timezones = null;
    if ($timezones === null) {
        $timezones = [];
        $offsets = [];
        $now = new DateTime('now', new DateTimeZone('UTC'));
        foreach(DateTimeZone::listIdentifiers() as $timezone){
            $now->setTimezone(new DateTimeZone($timezone));
            $offsets[] = $offset = $now->getOffset();
            //$timezones[$timezone] = '(' . format_GMT_offset($offset) . ') ' . format_timezone_name($timezone);
            $timezones[$timezone] = $timezone.' (' . format_GMT_offset($offset) . ') ';
        }
        array_multisort($offsets, $timezones);
    }
   return $timezones;
}
function format_GMT_offset($offset){
    $hours = intval($offset / 3600);
    $minutes = abs(intval($offset % 3600 / 60));
    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
}
function format_timezone_name($name){
    $name = str_replace('/', ', ', $name);
    $name = str_replace('_', ' ', $name);
    $name = str_replace('St ', 'St. ', $name);
    return $name;
}
$list=timezone_list(); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Timezone</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Set Website Timezone</h2></div></div>
                <div class="card-body pb-2">
                    <form>
                        <div class="row">
                            <div class="col-lg-7 col-xl-8">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Timezone</label>
                                    <div class="col-md-6 col-sm-9 col-lg-7 col-xl-5">
                                        <select name="timezoneval" id="timezoneval" class="form-control">
                                            <option value="">Select Timezone</option>
                                            <? foreach($list as $key=> $value){
                                            ?> <option value="<?=$key; ?>" <?=($getconfigdetails[0]['default_timezone']==$key?"selected":""); ?>><?=$value; ?></option><? } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top pt-2">
                            <div class="col-12 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#submit").on("click", function(){
        var default_timezone = $("#timezoneval option:selected").val();
        if (default_timezone != ""){
            var info = { default_timezone: default_timezone, action:'default_timezone' };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    if (response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if (response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else {
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter analytics ID").delay(3000).fadeOut();
        }
    });
});
</script>
</body>
</html>
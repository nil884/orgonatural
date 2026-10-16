<?php include("../../includes/configuration.php");
    $clientid = $_REQUEST['clientid'];
    $getproject = selectQuery(SUPPORTSTAFF,"*","emp_id='".$clientid."' "); 
?>
<div class="form-group">
    <?php $arr = array();
    for($i=0;$i<count($getproject);$i++) {
        $projarr= explode(",",$getproject[$i]['projects']);
        foreach($projarr as $value){
            array_push($arr,$value);
        }
    }
    $unique = array_unique($arr);
    sort($unique);
    ?>
    <label for="project" class="cc-mandatary-field">Project</label>
    <select class="form-control" id="project" onchange="checkproject();">
        <option value="">Select</option>
        <?php for($i=0;$i<=count($unique);$i++) {
        if($unique[$i]!="") { ?>
        <option value="<?php echo $unique[$i]; ?>"><?php echo $unique[$i]; ?></option>
        <? } } ?>
    </select>
</div>

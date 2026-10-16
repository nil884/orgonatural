<?php include("../../includes/configuration.php");
    include("../../classes/product.php");
    include("../../classes/user.php");
    $imgtype = "product";
    include("../../getimgpath.php");
    $prod = new Product();
    $user = new User();
    $prodid = $_REQUEST['reviewid'];
    $reviewall = selectQuery(REVIEW,"*","main_prod_id=".$prodid." and isApproved='1'  order by priority" );
?>

<div class="allrev table-responsive">
    <table class="table table-striped mb-0">
        <thead><tr><th>User name</th><th>Review Details</th><th>Date</th><th>Rating</th><th>Active/De-active</th><th>Delete</th></tr></thead>
        <tbody class="all_review">
            <?php if(count($reviewall)) {
            for($i=0;$i<count($reviewall);$i++){
            $udetail = $user->getUserDetails("u_fname,u_lname",$reviewall[$i]['user_id']);
            $reviewid = $reviewall[$i]['review_id'];  $active=$reviewall[$i]['isActive'];
            $getproddetails = $prod->getShortDetails($reviewall[$i]['prod_id']);
            $prodimg = $prod->getProductImageForDisplay($reviewall[$i]['prod_id']); ?>
            <tr id="<?php echo $reviewall[$i]['review_id'] ?>">
                <td><?=$udetail[0]['u_fname']." ".$udetail[0]['u_lname'];?> </td>
                <td><h6><?=$reviewall [$i]['review_title'];?></h6><div class="text-muted"><?=$reviewall [$i]['review'];?></div></td>
                <td><?=date("d M Y h:i a",strtotime($reviewall [$i]['review_date']));?></td>
                <td><?=$reviewall [$i]['rate'];?></td>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="act_deact<?php echo $reviewid ?>" class="custom-control-input"  <?php if($active == 1) { echo "checked value='1'";} else { echo "value='0'";} ?> onchange="act_deact('<?php echo $reviewid ?>','<?php echo $prodid; ?>')">
                        <label class="custom-control-label" for="act_deact<?php echo $reviewid ?>"></label>
                    </div>
                </td>
                <td class="td1"><button type="button" onclick="del('<?=$reviewid; ?>','<?php echo $prodid; ?>')" id="deletebtn<?=$reviewid; ?>" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td>
            </tr>
            <?php } } else{ echo "<tr><td colspan='8'>No Review Found</td></tr>"; } ?>
        </tbody>
    </table>
</div>
<script>

    $("table .all_review").sortable({
    update: function() {
        str = "";
        $(this).children().each(function(index) { str += $(this).attr('id') + ","; });
        str = str.replace(/,\s*$/, "");
        var info = { action: "setPriority",str: str };
        $.ajax({
            data: info,
            type: 'POST',
            url: 'ajax.php',
            success: function(result) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
            }
        })
    }
     });  

function act_deact(v1,prodid){
    var requestedid = v1;
    var c = $("#act_deact"+requestedid).val();
    if(c==0){ status = 1; res="activated"; $("#act_deact"+requestedid).val('1');} else {status = 0; res="deactivated"; $("#act_deact"+requestedid).val('0'); }
    var info={requestedid:requestedid,status:status,action:"active_deactive"};
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Review " +res).delay(3000).fadeOut();
                showmod(prodid);       
            }
            else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
</script>
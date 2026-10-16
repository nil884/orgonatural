<footer class="footer"><div class="col-md-12"><div class="row"><div class="col-md-6 col-sm-6 col-6">Copyright &#169; <?php echo date("Y"); ?> <?php echo SITENAME; ?></div><div class="col-md-6 col-sm-6 col-6 text-right">Website Intelligence By <a href="https://www.surun.in/" target="_blank">Surun Infocore System</a></div></div></div></footer>
<!--Delete Popup-->
<div class="modal delete-modal" id="del_popup"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body"><p class="cc-margin-bottom-0" id="del_message"></p></div><div class="modal-footer"><button type="button" class="btn btn-primary btn-sm" id="popup_ok">Ok</button><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button></div></div></div></div>
<div class="alert_msgs"></div>
<div class="overlap"></div>
<script src="<?php echo SITEURL; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/popper.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>
<script>
//Open related dropdown click on sidebar links
$(document).ready(function(){
    var path = window.location.href; 
    var target = $(".sidebar ul li a[href='"+path+"']");
    target.addClass('menuactive');
    target.parents("ul").show();
});
//if you click on anything except the modal itself or the "open modal" link, close the modal
$(document).click(function(event){
    if(!$(event.target).closest(".sidebar,.menubtn,.ecomfeabody").length){ $("body").removeClass("is-collapsed"); }
});
//Toogle dropdown menu on click of a link in sidebar
$('.sidebar li:has(ul)').addClass('dropdown');
$(".sidebar li a").on("click", function(){
    var sdlinkt = $(this);
    if(!sdlinkt.parent().hasClass("open")){
        sdlinkt.parent().parent().children("li.open").children(".dropdownMenu").slideUp(200),
        sdlinkt.parent().parent().children("li.open").removeClass("open"),
        sdlinkt.parent().children(".dropdownMenu").slideDown(200),
        sdlinkt.parent().addClass("open");
    } else{
        sdlinkt.parent().children(".dropdownMenu").slideUp(200);
        sdlinkt.parent().removeClass("open");
    }
});
//Add is-collapsed class to body for sidebar(navigation) collaps on click of Menu Button in header
$(".menubtn").click(function(){
    $("body").toggleClass("is-collapsed");$('.drop-menu').slideUp();
    /*if($("body").hasClass("is-collapsed")){
        $(".dataTables_scrollHeadInner").css("width", "100%");
    }*/
});
function del_alertbox(msg,i,funct,type){
    $("#del_popup").modal("show"); 
    $("#del_message").html(msg);
    $("#popup_ok").attr("onclick", funct+"('" + i + "','" + type + "')");
}
//Back button function
function goBack(){ window.history.back(-1); }
    
</script>
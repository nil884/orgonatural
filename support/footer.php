<footer class="footer">
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm-6 col-md-6">
                Copyright &#169; <?php echo date("Y"); ?> <?php echo SITENAME; ?>
            </div>
            <div class="col-sm-6 col-md-6 webintelligence">
                Website Intelligence By - <a href="https://www.surun.in/" target="_blank">Surun Infocore System</a>
            </div>
        </div>
    </div>
</footer>
<div class="overlap"></div>
<script src="<?php echo SITEURL; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/popper.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>
<script>
 function goBack() {        
    var prevurl = document.referrer;
    var n = prevurl.lastIndexOf('/');
    var result = prevurl.substring(n + 1);
    if(result == "searchresult.php" ){
        window.history.go(-2);
    } else{
        window.history.back();
    }
}

$(function(){
    var current = location.pathname;
    $('#myNavbar .nav li a').each(function(){
        var $this = $(this);
        if($this.attr('href').indexOf(current) !== -1){
            $this.addClass('activeborder');
        }
    })
});

$("#search").click(function(){
    $('#search').tablesorter();
});
    
//Toogle dropdown menu on click of a link in sidebar
$('.sidebar li:has(ul)').addClass('dropdown');
$(".sidebar li a").on("click", function(){
    var t = $(this);
    if(!t.parent().hasClass("open")){
        t.parent().parent().children("li.open").children(".dropdownMenu").slideUp(200),
        t.parent().parent().children("li.open").removeClass("open"),
        t.parent().children(".dropdownMenu").slideDown(200),
        t.parent().addClass("open");
    } else{
        t.parent().children(".dropdownMenu").slideUp(200);
        t.parent().removeClass("open");
    }
});
//Add is-collapsed class to body for sidebar(navigation) collaps on click of Menu Button in header
$(".menubtn").click(function(){$("body").toggleClass("is-collapsed");$('.drop-menu').slideUp();});
$(document).click(function(event) {
    if (!$(event.target).closest(".sidebar,.menubtn").length) {$("body").removeClass("is-collapsed");}
});
</script>
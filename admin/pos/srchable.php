<? include_once("../../includes/configuration.php");
$fld1 = explode(",",$_POST['fld1']); //headings
$fld2 = $_POST['fld2']; //database_table
$fld3 = $_POST['fld3']; //fields
$fldallsr = explode(",",base64_decode($fld3)); //headings
$fld4 = $_POST['fld4']; //where_condition
$fld5 = $_POST['fld5'];$fld6=$_POST['fld6']; ?>
<div class="poptable">
   
        <div class="table-overflow">
            <table class="table find-table">
                <thead class="header">
                    <tr>
                        <? $_SESSION['tabfindcnt']=300; $srablecnt = 0;
                        foreach($fld1 as $headings){ $srablecnt++; $dataid = $fldallsr[$srablecnt];
                        $_SESSION['tabfindcnt']+=1; ?>
                        <th class="border-top-0"><div><label><?=$headings; ?></label><input type="text" class="srchablefld srchablefld<?=$srablecnt; ?> form-control" data-id="<?=$dataid; ?>" onkeyup="findvaluessrchable(event,'srchablefld','<?=SITEURL; ?>')" tabindex="<?=$_SESSION['tabfindcnt']; ?>"></div></th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody class="srchabledata"></tbody>
            </table>
            <table id="header-fixed"></table>
        </div>
    
</div>
<script>
var fld1="<?=$fld2; ?>"; var fld2="<?=$fld3; ?>";var fld3="<?=$fld4; ?>";var fld4="<?=$fld5; ?>";var fld5="<?=$fld6; ?>";
opensrchmodal();
function opensrchmodal(){
      var srchtab = "<?=$_SESSION['tabfindcnt']; ?>";
     $.ajax({
        url : "ajax/searchable.php",  type : "post",  data : {action:"getallRecords",fld1:fld1,fld2:fld2,fld3:fld3,fld4:fld4,fld5:fld5},
        success : function(res3){
            if(res3!=""){
                $(".srchabledata").html(""); var res = JSON.parse(res3);
                for(var i =0;i<res.length;i++){
                    srchtab = parseInt(srchtab)+1;
                    var func="selectvalsrchable('"+res[i][0]+"')";  var func1="selectvalonkeysrchable(event,'"+res[i][0]+"')"
                    var str="<tr tabindex='"+srchtab+"' onclick="+func+" onkeyup="+func1+">";
                    for(var j=1;j<res[i].length;j++){ str+="<td class='py-2 cc-cursor-pointer'><i class='fa fa-angle-double-right mr-1' aria-hidden='true'></i> "+res[i][j]+"</td>";}str+="</tr>"; $(".srchabledata").append(str); }
            } else{ alerttoast();$(".msgs").html("No Record Found"); }
        }
    });
    $(".srchablefld").attr("disabled",false); $(".srchablefld1").focus(); $("#srchablefldmodal").modal("show");
}
function selectvalonkeysrchable(e,groupid){ if(e.keyCode==13){ selectvalsrchable(groupid);} }
function getreqvalsrchable(groupid){ selectvalsrchable(groupid); }
function selectvalsrchable(grpid){
    $.ajax({ url : "ajax/searchable.php", type : "post", data : { action:"getrecordjsononfind", groupid:grpid, fld1:fld1, fld2:fld2, fld3:fld3, fld4:fld4, fld5:fld5},
    success : function(res3){
    if(res3!=""){ var res = JSON.parse(res3); $("."+fld4).val(res[0]); $("#srchablefldmodal").modal("hide");$("."+fld4).focus();
    } else{alerttoast(); $(".msgs").html("Select"); }
    }});
}
function findvaluessrchable(e,findClass,siteurl){
    var srchtab = "<?=$_SESSION['tabfindcnt']; ?>";
    if(e.keyCode == '40'){ $(".srchabledata tr:first-child").addClass("tractive").focus(); }
    else if(e.keyCode == '38'){ alert("up") }
    else{
        var recarra = new Array();
        elements = {}
        $("."+findClass).each(function(){ var thisval=$(this).val().trim(); var thisname=$(this).attr("data-id"); elements[thisname] = thisval; });
        $.ajax({
            url : "ajax/searchable.php", type : "post", data : { action:"getrecordforfind", fld1:fld1, fld2:fld2, fld3:fld3, fld4:fld4, fld5:fld5, "fldvalues":JSON.stringify( elements)},
            success : function(res3){
                if(res3!=""){
                    $(".srchabledata").html(""); var res = JSON.parse(res3);
                    for(var i =0;i<res.length;i++){
                    srchtab = parseInt(srchtab)+1; var func="selectvalsrchable('"+res[i][0]+"')";
                    var func1 = "selectvalonkeysrchable(event,'"+res[i][0]+"')";
                    var str = "<tr tabindex='"+srchtab+"' onclick="+func+" onkeyup="+func1+">";
                    for(var j=1;j<res[i].length;j++){ str+="<td class='py-2 cc-cursor-pointer'><i class='fa fa-angle-double-right mr-1' aria-hidden='true'></i> "+res[i][j]+"</td>"; } str+="</tr>";   $(".srchabledata").append(str);
                }} else{ alerttoast(); $(".msgs").html("No Record Found"); }
            }
        });
    }
}
$('.srchabledata').on('focus', 'tr', function(){
    $this = $(this); $this.addClass('tractive').siblings().removeClass();
    $this.closest('.srchabledata').scrollTop($this.index() * $this.outerHeight());
}).on('keydown', 'tr', function(e){
    $this = $(this); if (e.keyCode == 40) {$this.next().focus();return false;
    } else if (e.keyCode == 38) { $this.prev().focus(); return false; }
}).find('li').first().focus();
</script>
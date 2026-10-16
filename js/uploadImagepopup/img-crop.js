$('#selectFile').click(function() {
    $(".avatar-input[type='file']").trigger('click');
})

function ifradioselected(){
    if ($("input[name='todo']:checked").size()==0) { alert("Select Crop/Resize Option!");return false;}
    else $(".avatar-save").click();
}

$('input:radio[name="todo"]').change(function(){
    if($(this).val()=="resize")
        $("#todochoice").val("resize");
    else
        $("#todochoice").val("crop");
});

function closse(){
    $(".close").click();
}

function removeimg(i){
    alertbox("confirm","Do you want to delete this?","Delete Image?",i);
    /* $("#img"+i).remove();   */
}

function del_ok(i){
    $.ajax({
        method: "POST",
        url: "delimageajax.php",
        data: {model:i},
        success: function(response){
            closealertbox();
            $("#avatar-view").show();
            $('img[src *="'+i+'"]').closest ('div.packageMngThumb').remove();
            location.reload();
        }
    })
}

function editimg(i){
    edit=i;
    $("#crop-avatar").css("display","block");
    $("#avatar-view").click();
}

function ifradioselected(){
    if ($("#todochoice").val()=="" || $("#img_numm").val()=="0" || $("#config_error").val()==1){
        $("#config_error_msg").html("Error in configuration!");
        setTimeout(function(){
        $("#config_error_msg").html("");
        }, 2000);
        return false;
    }
    else{
        $("#cropped_img_width").val(cropboxdimension_width);
        $("#cropped_img_height").val(cropboxdimension_height);
        if(edit!=0)
        $("#cropped_img_num").val(edit);
        else
        var length=  $(".imgdivclass").length;
        if(length != 0) {
            var getlastimgid=  $(".imgdivclass").last().attr("id");
            var getno = getlastimgid.slice(-1)
            var lastimg_name = $("#img"+getno).attr('data-id');
            var right_text = lastimg_name.substring(0, lastimg_name.indexOf(".png"));
            var lastChar = right_text[right_text.length -1];
            var val=1
            var add1  = parseInt(lastChar) + val;
        }
        else {
        add1  = 1
        }
        $("#cropped_img_num").val(add1);
        $(".avatar-save").click();
    }
}
function readURL_fordrag(file){
    if (file){
        var reader = new FileReader();
        reader.onload = function (e){
            $('#filesInfo').html('<img id="resizepreview" src="'+e.target.result+'">');
        }
        reader.readAsDataURL(file);
    }
    resizeAndPreview(file);
}

function readURL(input){
    $("#dropped").val(0);
    if (input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function (e){
            $('#filesInfo').html('<img id="resizepreview" src="'+e.target.result+'">');
        }
        reader.readAsDataURL(input.files[0]);
    }
    if (window.File && window.FileReader && window.FileList && window.Blob){
        var files = document.getElementById('avatarInput').files;
        for(var i = 0; i < files.length; i++) {
            resizeAndPreview(files[i]);
        }
    }
}

function resizeAndPreview(file){
    var reader = new FileReader();
    reader.onloadend = function() {
    var tempImg = new Image();
    tempImg.src = reader.result;
    tempImg.onload = function() {
        var MAX_WIDTH = MAX_RESIZE_WIDTH;
        var MAX_HEIGHT = MAX_RESIZE_HEIGHT;
        var tempW = tempImg.width;
        var tempH = tempImg.height;
        var oriw=tempW;
        var orih=tempH;
        var ratio = Math.min(180 / tempW, 180 / tempH);
        var displaywidth=tempW*ratio;
        var displayheight=tempH*ratio;
        if (tempW > tempH) {
            if (tempW > MAX_WIDTH) {
                tempH *= MAX_WIDTH / tempW;
                tempW = MAX_WIDTH;
            }
        } else {
            if (tempH > MAX_HEIGHT) {
                tempW *= MAX_HEIGHT / tempH;
                tempH = MAX_HEIGHT;
            }
        }
        var canvas = document.createElement('canvas');
        canvas.width = tempW;
        canvas.height = tempH;
        var ctx = canvas.getContext("2d");
        ctx.beginPath();
        ctx.rect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "white";
        ctx.fill();
        ctx.drawImage(this,(canvas.width-tempW)/2, (canvas.height-tempH)/2, tempW, tempH);
        var dataURL = canvas.toDataURL("image/jpeg");
        $("#dataURL").val(dataURL);
        $('#filesInfo').css("width",displaywidth+'px');
        $('#filesInfo').css("height",displayheight+'px');
        $('#filesInfo img').css("width",displaywidth+'px');
        $('#filesInfo img').css("height",displayheight+'px');
        // $('#filesInfo img').css("position",'absolute');
        $('#filesInfo img').css("margin",'auto');
        $('#filesInfo img').css("top",'0px');
        $('#filesInfo img').css("bottom",'0px');
        $('#filesInfo img').css("left",'0px');
        $('#filesInfo img').css("right",'0px');
        $("#tempw").val(tempW);
        $("#temph").val(tempH);
        }
    }
    reader.readAsDataURL(file);
}
function closealertbox(){
    $(".deleteAlert").modal("hide");
    $("#popup_container").css("display","none");
    $("#popup_message").text("");
    $("#popup_title").text("");
    $("#popup_cancel").css("display","none");
    $("#popup_ok").attr("onclick","");
}
function alertbox(confirm_alert,msg,title,i){
    $(".deleteAlert").modal("show");
    $("#popup_container").css("display","block");
    $("#popup_message").text(msg);
    $("#popup_title").text(title);
    if(confirm_alert=="confirm"){
        $("#popup_cancel").css("display","inline");
        $("#popup_ok").attr("onclick","del_ok('"+i+"')");
    }
    else{
        $("#popup_cancel").css("display","none");
        $("#popup_ok").attr("onclick","");
    }
}

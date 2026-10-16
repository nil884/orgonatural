jsonarr = JSON.parse(imgconf);
var crop_enabled = jsonarr[0]['crop_enabled'];
var crop_height = jsonarr[0]['crop_height'];var crop_width = jsonarr[0]['crop_width'];
var default_image_height = jsonarr[0]['default_image_height']; var default_image_width = jsonarr[0]['default_image_width'];
var img_extension = jsonarr[0]['img_extension'];
function gcd(a, b){ return (b == 0) ? a : gcd (b, a%b); }
var r = gcd (crop_width, crop_height);
aspect1 = parseInt(crop_width)/r; aspect2 = parseInt(crop_height)/r;
var img_type = jsonarr[0]['img_type']; var max_image_count = jsonarr[0]['max_image_count']; var max_image_size=jsonarr[0]['max_image_size']; var imgs_location = jsonarr[0]['imgs_location']; var thumbnail_required = jsonarr[0]['thumbnail_required']; var thumb1_width = jsonarr[0]['thumb1_width']; var thumb2_width = jsonarr[0]['thumb2_width']; var thumb3_width = jsonarr[0]['thumb3_width']; var thumb4_width = jsonarr[0]['thumb4_width']; var thumb5_width = jsonarr[0]['thumb5_width']; var thumb1_path = jsonarr[0]['thumb1_path']; var thumb2_path = jsonarr[0]['thumb2_path']; var thumb3_path = jsonarr[0]['thumb3_path'];  var thumb4_path = jsonarr[0]['thumb4_path']; var thumb5_path = jsonarr[0]['thumb5_path']; var avatar = document.getElementById('avatar'); var image = document.getElementById('image');var input = document.getElementById('inputImage');
 //var input = document.getElementsByClassName('inputImage');
/*var input = document.getElementById('aspratio');*/
var $progress = $('.progress'); var $progressBar = $('.progress-bar');  var $alert = $('.alert'); var $modal = $('#modal'); var cropper;
var img_ratio = aspect1+":"+ aspect2;
window.addEventListener('DOMContentLoaded', function(){
    $('[data-toggle="tooltip"]').tooltip();
    input.addEventListener('change', function(e){
        var files = e.target.files;
        var done = function (url){
            input.value = ''; image.src = url; $alert.hide(); $modal.modal('show');
        };
        var reader;var file; var url;
        if(files && files.length > 0){
            file = files[0];
            if(URL){ done(URL.createObjectURL(file));
            } else if(FileReader){ 
                reader = new FileReader();
                reader.onload = function(e){ done(reader.result);};
                reader.readAsDataURL(file);
            }
        }
    });
    $modal.on('shown.bs.modal', function(){
        cropper = new Cropper(image, {
            aspectRatio: parseInt(aspect1)/parseInt(aspect2), restore: false, guides: false, center: false,autoCrop:(crop_enabled==0?false:true),
            dragMode: 'move',
            cropBoxMovable: false,
            cropBoxResizable: false,
            toggleDragModeOnDblclick: false,
        });
    }).on('hidden.bs.modal', function(){ cropper.destroy();cropper = null; });
});
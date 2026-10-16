 function namechk(id){
   var name = $('#'+id).val();
   var alphanumers = /^[a-zA-Z]+$/;
   if(!alphanumers.test(name)){ var newstr = name.slice(0, -1); $("#"+id).val(newstr); }
}
function fullnamechk(id){
    var name = $('#'+id).val();
    var alphanumers = /^[a-zA-Z ]+$/;
    if(!alphanumers.test(name)){ var newstr = name.slice(0, -1); $("#"+id).val(newstr); }
}
function numbercheck(id){
    var inp=$("#"+id).val();
    if(isNaN(inp)){ var newstr = inp.slice(0, -1); $("#"+id).val(newstr); }
}
 function mobnumbercheck(id){
    var inp = $("#"+id).val();
    var last = inp.charAt(0);
    if(isNaN(inp)){ var newstr = inp.slice(0, -1); $("#"+id).val(newstr); }
    if(last!=7&&last!=8&&last!=9&&inp.length!= 10){ $("#"+id).val(""); }
}
function mailchk(id){
    var email = $("#"+id).val();
    var patt = new RegExp(/^[a-zA-Z0-9.\-_]+@[0-9a-zA-Z.\-]+\.[a-zA-Z \t]{1,4}$/);
    var res = patt.test(email);
    if(res){ } else{ $("#"+id).val(""); }
}
function charcheck(id){
    var name = $('#'+id).val();
    var alphanumers = /^[a-zA-Z\s-, ]+$/;
    if(!alphanumers.test(name)){ var newstr = name.slice(0, -1); $("#"+id).val(newstr); }
}
function letter_number(id){
    var name = $('#'+id).val();
    var alphanumers = /^[A-Za-z0-9]+$/;
    if(!alphanumers.test(name)){ var newstr = name.slice(0, -1); $("#"+id).val(newstr); }
}
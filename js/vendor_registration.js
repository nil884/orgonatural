//Start, Back cursor if previous field is blank
$("#shopname").click(function(){
    var profid = $("#name2").val();
    if(profid == "")
    {
        $("#name2").focus();
    }
});
$("#country").click(function(){
    var shopname = $("#shopname").val();
    if(shopname == "")
    {
        $("#shopname").focus();
    }
});
$("#state").click(function(){
    var country = $("#country").val();
    if(country == "")
    {
        $("#country").focus();
    }
});
$("#city").click(function(){
    var state = $("#state").val();
    if(state == "")
    {
        $("#state").focus();
    }
});
$("#Adress").click(function(){
    var city = $("#city").val();
    if(city == "")
    {
        $("#city").focus();
    }
});
$("#Pin").click(function(){
    var Adress = $("#Adress").val();
    if(Adress == "")
    {
        $("#Adress").focus();
    }
});
$("#officeemail").click(function(){
    var Pin = $("#Pin").val();
    if(Pin == "")
    {
        $("#Pin").focus();
    }
});
$("#officemob").click(function(){
    var officeemail = $("#officeemail").val();
    if(officeemail == "")
    {
        $("#officeemail").focus();
    }
});
$("#panno").click(function(){
    var panno = $("#panno").val();
    if(panno == "")
    {
        $("#panno").focus();
    }
});
$("#tan").click(function(){
    var panno = $("#panno").val();
    if(panno == "")
    {
        $("#panno").focus();
    }
});
$("#branchname").click(function(){
    var bnkname = $("#bnkname").val();
    if(bnkname == "")
    {
        $("#bnkname").focus();
    }
});
$("#beneficiary").click(function(){
    var branchname = $("#branchname").val();
    if(branchname == "")
    {
        $("#branchname").focus();
    }
});
$("#acnt_no").click(function(){
    var beneficiary = $("#beneficiary").val();
    if(beneficiary == "")
    {
        $("#beneficiary").focus();
    }
});
$("#acnttype").change(function(){
    var acnt_no = $("#acnt_no").val();
    if(acnt_no == "")
    {
        $("#acnt_no").focus();
        $("#acnt_no").css("border","1px solid red");
    }
});
$("#ifsc").click(function(){
   var acnttype = $("#acnttype option:selected").val();
    if(acnttype == "")
    {
        $("#acnttype").focus();
        $("#acnttype").css("border","1px solid red");
    }
});

$("#plan").click(function(){
  var ifsc = $("#ifsc").val();
    if(ifsc == "")
    {
        $("#ifsc").focus();
        $("#ifsc").css("border","1px solid red");
    }
});
//End, Back cursor if previous field is blank
//Start, Onkeyup Validaton
function check1(id)
{
    var contactNot=$("#contactNot").val();
    var inp=$("#"+id).val();
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(contactNot);
    if(isSplChar)
    {
        var no_spl_char = contactNot.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#contactNot").val(no_spl_char);
    }
    if(isNaN(inp))
    {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
    }
    else if(!(contactNot.charAt(0)=="9" || contactNot.charAt(0)=="8" || contactNot.charAt(0)=="7"))
    {
       $("#contactNot").css("border","1px solid red");
       $("#altmoberr").html("Number should start with 9,8 or 7").addClass("text-danger");
    }
    else if(contactNot.length < 10)
    {
       $("#contactNot").css("border","1px solid red");
       $("#altmoberr").html("Please check the mobile number").addClass("text-danger");
    }
    else
    {
        $("#contactNot").css("border-color","grey");
        $("#altmoberr").html("").removeClass("text-danger");
    }
}
function check2(id)
{
    var profid = $("#name2").val();
    var alphanumeric = /^[0-9a-zA-Z]+$/;
    var onlyalphabets = /[^\w\s]/gi;
     var inp=$("#"+id).val();
    var last=inp.slice(-1);
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(profid);
    if(isSplChar)
    {
        var no_spl_char = profid.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#name2").val(no_spl_char);
    }
    if(inp.length==1&&isNaN(last)==false)
    {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
        $("#profileerr").html("First Character Must Be Character").addClass("text-danger");
    }
    else if(profid.length<=4)
    {
       $("#name2").css("border","1px solid red");
       $("#profileerr").html("Enter minimum 5 character....").addClass("text-danger");
    }
    else if(profid.length>=5)
    {
       $("#name2").css("border-color","grey");
       $("#profileerr").html("").removeClass("text-danger");
    }
    else if(onlyalphabets.test(profid) == true)
    {
      $("#name2").css("border","1px solid red");
      $("#profileerr").html("Only alphabets allowed....").addClass("text-danger");
    }
    else
    {
       $("#name2").css("border-color","grey");
       $("#profileerr").html("").removeClass("text-danger");
    }
}

function check3(id)
{
    var shopname=$("#shopname").val();
    var onlyalphabets = /[^\w\s]/gi;
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
      restrictspecialchars = /[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(shopname);
    if(isSplChar)
    {
        var no_spl_char = shopname.replace(/[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#shopname").val(no_spl_char);
    }
    if (onlyalphabets.test(shopname) == true)
    {
       $("#shopname").css("border","1px solid red");
       $("#companyerr").html("Only alphabets allowed").addClass("text-danger");
    }
    else if(shopname.length <=2)
    {
       $("#shopname").css("border","1px solid red");
       $("#companyerr").html("Enter minimum 3 character....").addClass("text-danger");
    }
    else
    {
        $("#shopname").css("border-color","grey");
        $("#companyerr").html("").removeClass("text-danger");
    }
   /* restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(shopname);
    if(isSplChar)
    {
        var no_spl_char = shopname.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#shopname").val(no_spl_char);
    }
   if(isNaN(last)==false)
   {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
   }
   else if(shopname.length <=4)
   {
       $("#shopname").css("border","1px solid red");
       $("#companyerr").html("Enter minimum 5 character....").addClass("text-danger");
   }
   else if(onlyalphabets.test(shopname) == true)
   {
       $("#shopname").css("border","1px solid red");
       $("#companyerr").html("Only alphabets allowed....").addClass("text-danger");
   }
   else
   {
      $("#shopname").css("border-color","grey");
      $("#companyerr").html("").removeClass("text-danger");
   }*/
}
function check4(id)
{  
    var onlyalphabets = /[^\w\s]/gi;
    var country=$("#country").val();
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(country);
    if(isSplChar)
    {
        var no_spl_char = country.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#country").val(no_spl_char);
    }
    if(isNaN(last)==false)
    {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
    }
    else if(country.length <=2)
    {
       $("#country").css("border","1px solid red");
       $("#countryerr").html("Enter minimum 3 character....").addClass("text-danger");
    }
    else if (onlyalphabets.test(country) == true)
    {
       $("#country").css("border","1px solid red");
       $("#countryerr").html("Only alphabets allowed").addClass("text-danger");
    }
    else
    {
        $("#country").css("border-color","grey");
        $("#countryerr").html("").removeClass("text-danger");
    }
}
function check5(id)
{   
    
    var onlyalphabets = /[^\w\s]/gi;
    var state=$("#state").val();
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
    var isSplChar = restrictspecialchars.test(state);
    if(isSplChar)
    {
        var no_spl_char = state.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#state").val(no_spl_char);
    }
   /* if(isNaN(last)==false)
    {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
    } */
    else if(state.length <=2)
    {
       $("#state").css("border","1px solid red");
       $("#stateerr").html("Enter minimum 3 character....").addClass("text-danger");
    }
    else if (onlyalphabets.test(state) == true)
    {
       $("#state").css("border","1px solid red");
       $("#stateerr").html("Only alphabets allowed").addClass("text-danger");
    }
    else
    {
        $("#state").css("border-color","grey");
        $("#stateerr").html("").removeClass("text-danger");
    }
}
function check6(id)
{
    var onlyalphabets = /[^\w\s]/gi;
    var city=$("#city").val();
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
    var isSplChar = restrictspecialchars.test(city);
    if(isSplChar)
    {
        var no_spl_char = city.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#city").val(no_spl_char);
    }
    if(isNaN(last)==false)
    {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
    }
    else if(city.length <=2)
    {
       $("#city").css("border","1px solid red");
       $("#cityerr").html("Enter minimum 3 character....").addClass("text-danger");
    }
    else if (onlyalphabets.test(city) == true)
    {
       $("#city").css("border","1px solid red");
       $("#cityerr").html("Only alphabets allowed").addClass("text-danger");
    }
    else
    {
        $("#city").css("border-color","grey");
        $("#cityerr").html("").removeClass("text-danger");
    }
}
function check7()
{
     var Adress=$("#Adress").val();
     if(Adress.length <=3)
    {
      $("#Adress").css("border","1px solid red");
      $("#officeadderr").html("Enter minimum 5 character....").addClass("text-danger");
    }
    else
    {
      $("#Adress").css("border-color","grey");
      $("#officeadderr").html("").removeClass("text-danger");
    }
}
function check8(id)
{
    var pin=$("#Pin").val();
    var inp=$("#"+id).val();
    if(isNaN(inp))
    {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
    }
    if(pin.length!=6)
    {
       $("#Pin").css("border","1px solid red");
       $("#pinerr").html("Pincode must be 6 digit").addClass("text-danger");
    }
    else
    {
       $("#Pin").css("border-color","grey");
       $("#pinerr").html("").removeClass("text-danger");
    }
}
function check9()
{
    var officemail=$("#officeemail").val();
    restrictspecialchars = /[`~!#$%^&*()|+\-=?;:'",<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(officemail);
    if(isSplChar)
    {
        var no_spl_char = officemail.replace(/[`~!#$%^&*()|+\-=?;:'",<>\{\}\[\]\\\/]/gi, '');
        $("#officeemail").val(no_spl_char);
    }
    if(officemail=="")
    {
       $("#officeemail").css("border","1px solid red");
       $("#offemailerr").html("Please enter email").addClass("text-danger");
    }
    else if(!(validateEmail(officemail)))
    {
      $("#officeemail").css("border","1px solid red");
      $("#offemailerr").html("Please enter correct email").addClass("text-danger");
    }
    else
    {
      $("#officeemail").css("border-color","grey");
      $("#offemailerr").html("").removeClass("text-danger");
    }
}
function check10()
{
    var officecontactNo=$("#officemob").val();
    if(!(officecontactNo.charAt(0)=="9" || officecontactNo.charAt(0)=="8" || officecontactNo.charAt(0)=="7"))
    {
       $("#officemob").css("border","1px solid red");
       $("#offmoberr").html("Number should start with 9,8 or 7").addClass("text-danger");
    }
    else if(officecontactNo.length!=10)
    {
       $("#officemob").css("border","1px solid red");
       $("#offmoberr").html("Please check the mobile number").addClass("text-danger");
    }
    else
    {
       $("#officemob").css("border-color","grey");
       $("#offmoberr").html("").addClass("text-danger");
    }
}
function check11()
{
    var pan1 = $("#panno").val();
    var panregExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/;
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(pan1);
    if(isSplChar)
    {
        var no_spl_char = pan1.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#panno").val(no_spl_char);
    }
    if (!(pan1.match(panregExp)))
    {
      $("#panno").css("border","1px solid red");
      $("#panerr").html("Not a valid PAN number").addClass("text-danger");
    }
    else if (pan1.length != 10 )
    {
        $("#panno").css("border","1px solid red");
        $("#panerr").html("Please enter 10 digits for a valid PAN number").addClass("text-danger");
    }
    else
    {
        $("#panno").css("border-color","grey");
        $("#panerr").html("").removeClass("text-danger");
    }
}
function check12()
{
    var tanno=$("#tan").val();
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(tanno);
    if(isSplChar)
    {
        var no_spl_char = tanno.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#tan").val(no_spl_char);
    }
}
function check13()
{
    var regno=$("#regno").val();
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(regno);
    if(isSplChar)
    {
        var no_spl_char = regno.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#regno").val(no_spl_char);
    }
}
function check14()
{
    var vatno=$("#vatno").val();
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(vatno);
    if(isSplChar)
    {
        var no_spl_char = vatno.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#vatno").val(no_spl_char);
    }
}
function check15(id)
{
    var bnkname=$("#bnkname").val();
    var onlyalphabets = /[^\w\s]/gi;
    restrictspecialchars = /[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
    var isSplChar = restrictspecialchars.test(bnkname);
    if(isSplChar)
    {
        var no_spl_char = bnkname.replace(/[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#bnkname").val(no_spl_char);
    }
    if (onlyalphabets.test(bnkname) == true)
    {
       $("#bnkname").css("border","1px solid red");
       $("#bankerr").html("Only alphabets allowed").addClass("text-danger");
    }
    else if(bnkname.length <=2)
    {
       $("#bnkname").css("border","1px solid red");
       $("#bankerr").html("Enter minimum 3 character....").addClass("text-danger");
    }
    else
    {
        $("#bnkname").css("border-color","grey");
        $("#bankerr").html("").removeClass("text-danger");
    }
 }
function check16(id)
{
    var branchname = $("#branchname").val();
    var onlyalphabets = /[^\w\s]/gi;
    restrictspecialchars = /[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
    var isSplChar = restrictspecialchars.test(branchname);
    if(isSplChar)
    {
        var no_spl_char = branchname.replace(/[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#branchname").val(no_spl_char);
    }
    if (onlyalphabets.test(branchname) == true)
    {
       $("#branchname").css("border","1px solid red");
       $("#brancherr").html("Only alphabets allowed").addClass("text-danger");
    }
    else if(branchname.length <=2)
    {
       $("#branchname").css("border","1px solid red");
       $("#brancherr").html("Enter minimum 3 character....").addClass("text-danger");
    }
    else
    {
        $("#branchname").css("border-color","grey");
        $("#brancherr").html("").removeClass("text-danger");
    }
}
function check17(id)
{
    var beneficiary=$("#beneficiary").val();
    var onlyalphabets = /[^\w\s]/gi;
    restrictspecialchars = /[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
    var isSplChar = restrictspecialchars.test(beneficiary);
    if(isSplChar)
    {
        var no_spl_char = beneficiary.replace(/[1234567890`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#beneficiary").val(no_spl_char);
    }
    if (onlyalphabets.test(beneficiary) == true)
    {
       $("#beneficiary").css("border","1px solid red");
       $("#beneferr").html("Only alphabets allowed").addClass("text-danger");
    }
    else if(beneficiary.length <=2)
    {
       $("#beneficiary").css("border","1px solid red");
       $("#beneferr").html("Enter minimum 3 character....").addClass("text-danger");
    }
    else
    {
        $("#beneficiary").css("border-color","grey");
        $("#beneferr").html("").removeClass("text-danger");
    }
}
function check18(id)
{
   var acnt_no=$("#acnt_no").val();
   var inp=$("#"+id).val();
   restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
   if(isNaN(inp))
   {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
   }
    var isSplChar = restrictspecialchars.test(acnt_no);
    if(isSplChar)
    {
        var no_spl_char = acnt_no.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#acnt_no").val(no_spl_char);
    }
    if(acnt_no!="")
    {
      $("#acnt_no").css("border-color","grey");
      $("#accerr").html("").removeClass("text-danger");
    }
}
function check19(acnttype)
{
    var acnttype = $("#acnttype option:selected").val();
    if(acnttype!="")
    {
      $("#acnttype").css("border-color","grey");
      $("#acctypeerr").html("").removeClass("text-danger");
    }
}
function check20(id)
{
    var ifsc = $("#ifsc").val();
    var onlyalphabets = /[^\w\s]/gi;
    var inp=$("#"+id).val();
    var last=inp.slice(-1);
    restrictspecialchars = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = restrictspecialchars.test(ifsc);
    if(isSplChar)
    {
        var no_spl_char = ifsc.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $("#ifsc").val(no_spl_char);
    }
    else
    {
        $("#ifsc").css("border-color","grey");
        $("#ifscerr").html("").removeClass("text-danger");
    }
}
//End, Onkeyup Validaton

function validateEmail(email1)
{
  var ret = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return ret.test(email1);
}



function localityvalidate(){
   
   locality =  $("#locality").val();
   if(locality != ""){
    $("#localityerr").html("").removeClass("text-danger");
    $("#locality").css("border-color","grey");
   }
}
/*function displayprod(siteurl,where, pageNum) {
    ordering = $(".sortnav li.selected").attr("data-val");
    filter = "";
    totalfilters = $(".totalfilters").val();
    var filterarr = [];
    for(i=0;i<totalfilters;i++){
        sortcl = "sort"+i;
        var filterval = [];
        atrname = $("."+sortcl+"name").val();
        $("."+sortcl+":checked").each(function(){
            chval = this.value;
            filterval.push(chval);
        });
        filterarr.push(filterval)
    }
    console.log(filterarr.length);
    console.log(filterarr);
    $.ajax({
        type: "GET",
        url: siteurl+"/product-ajaxload.php",
        data: "where=" + where + "&pagenum=" + pageNum + "&ordering=" +ordering+ "&filter=" +filterarr,
        cache: false,beforeSend: function() {},
        success: function(html){ $(".products").html(html); }
    });
}*/
function displayprod(siteurl, where, pageNum) {
    ordering = $(".sortnav li.selected").attr("data-val");
    minprice = $("#minamount").val();
    maxprice = $("#maxamount").val();
    filter = "";
    totalfilters = $(".totalfilters").val();
    var filterarr = {};
    for (i = 0; i < totalfilters; i++) {
        sortcl = "sort" + i;
        var filterval = [];
        atrname = $("." + sortcl + "name").val();
        filterarr[atrname] = [];
        cnt = 0
        $("." + sortcl + ":checked").each(function() {
            chval = this.value;
            filterarr[atrname][cnt] = chval;
            cnt++;
        });
    }
    filter = JSON.stringify(filterarr);
    $.ajax({
        type: "POST",
        url: siteurl + "/product-ajaxload.php",
        data: { where: where, pagenum: pageNum, minprice: minprice, maxprice: maxprice, ordering: ordering, template: template, filter: filter },
        cache: false,
        beforeSend: function() {},
        success: function(html) {
            $(".products").html(html);
            totalitem = $(".total-request").html();
            $(".itmtocount").html(totalitem);
            setTimeout(function() { lazyloader(); }, 500);

            /*            function fixprothumbheight(){
                            var proouterheights = new Array();
                            $('.prod-pic-fig').each(function(){	
                                $(this).css('min-height', 'auto');
                                proouterheights.push($(this).height());
                            });
                            var max = Math.max.apply(Math, proouterheights);
                            $('.prod-pic-fig').each(function(){ $(this).css('min-height', max + 'px'); });	
                        }
                        $(window).resize(function(){
                            fixprothumbheight();
                            setTimeout(function(){ fixprothumbheight(); }, 500);
                        });*/
            $(".sort-collapsed .sortnav li").click(function() {
                $(".sortnav").slideToggle();
                $("body").removeClass("sort-collapsed");
            });
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }
    });
}

function sortprod(sortby, sortclass) {
    $(".sortnav li").removeClass("selected");
    $(".sortnav li").removeClass("text-primary");
    $("." + sortclass).addClass('selected text-primary');
    displayprod(siteurl, where, 1)
}
$("#slider-range").mouseup(function() { displayprod(siteurl, where, 1); })

function add_to_cart(prodid, qunatity) {
    if (qunatity != "") { qunatity = qunatity; } else { qunatity = $("#selected_quant_product-" + prodid).val(); }
    $.ajax({
        url: siteurl + "/ajax/product_ajax.php",
        type: 'POST',
        data: { prodid: prodid, qunatity: qunatity, action: "Add_to_cart" },
        success: function(response) {
            if (response == "Exist") {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product Already Exist in cart").delay(3000).fadeOut();
            } else if (response == 0) {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Try After Some Time").delay(3000).fadeOut();
            } else if (response == 1) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product Added To cart").delay(3000).fadeOut();
                $("#cart-count").load(" #cart-count");
                $(".cart_btn_" + prodid).attr("disabled", true);
            }
        }
    });
}

function add_to_wishlist(prodid, qunatity) {
    if (qunatity != "") { qunatity = qunatity; } else { qunatity = $("#selected_quant_product-" + prodid).val(); }
    $.ajax({
        url: siteurl + "/ajax/product_ajax.php",
        type: 'POST',
        data: { prodid: prodid, qunatity: qunatity, action: "addtowishlist" },
        success: function(response) {
            if (response == "Exist") {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product Already Exist in Wishlist").delay(3000).fadeOut();
            } else if (response == 0) {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Try After Some Time").delay(3000).fadeOut();
            } else if (response == 1) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product Added To Wishlist").delay(3000).fadeOut();
                $(".like-btn").load(" .like-btn");
                $("#wishlist-count").load(" #wishlist-count");
                $(".wishlist_btn_" + prodid).attr("disabled", true);
            }
        }
    });
}

function add_to_compare(prodid, action) {
    var pathname = window.location.pathname;
    var c = $(".compare_" + prodid).val();
    //var ischeck= $(".compare_"+prodid+":checked").val();
    if (c == 0) { $(".compare_" + prodid).val('1'); } else { $(".compare_" + prodid).val('0'); }
    var c = $(".compare_" + prodid).val();
    if (c == "1" || action == "add_to_compare") { action = "add_to_compare"; } else { action = "remove_from_compare" }
    $.ajax({
        url: siteurl + "/ajax/product_ajax.php",
        type: 'POST',
        data: { prodid: prodid, action: action },
        success: function(response) {
            response = $.trim(response)
            if (response == "Exist") {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Product Already Exist in cart").delay(3000).fadeOut();
            } else if (response == "Diffrent") {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please add similar type of product for comparison").delay(3000).fadeOut();
                $(".compare_" + prodid).val('0');
            } else if (response == "limit") {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("You can not add more product for comparison").delay(3000).fadeOut();
                $(".compare_" + prodid).val('0');
            } else if (!isNaN(response) && action == "add_to_compare") {
                if (pathname == "/compare") { location.href = siteurl + "/compare"; }
                $(".showcmpcnt").show();
                $(".showcmpcnt").html("<a class='colorw1 text-white' href='" + siteurl + "/compare' target='_blank'><i class='fa fa-filter mr-1'></i> Compare (" + response + ")</a> <button type='button' class='btn py-0 text-white border-0 clearcompbtn' onclick='clearcmp()'> <i class='fa fa-times' aria-hidden='true'></i></button>");
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product is Added for comparison").delay(3000).fadeOut();
                $(".compare_" + prodid).val('1');
                $(".compare_" + prodid).val('1').parents(".pro-compare").addClass("disabled");
            } else if (!isNaN(response) && action == "remove_from_compare" && response > 0) {
                if (pathname == "/compare") { location.href = siteurl + "/compare"; }
                $(".showcmpcnt").show();
                $(".showcmpcnt").html("<a class='colorw1 text-white' href='" + siteurl + "/compare' target='_blank'><i class='fa fa-filter mr-1'></i> Compare (" + response + ")</a> <button type='button' class='btn py-0 text-white border-0 clearcompbtn' onclick='clearcmp()'> <i class='fa fa-times' aria-hidden='true'></i></button>");
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product is removed for comparison").delay(3000).fadeOut();
                $(".compare_" + prodid).val('0');
                $(".compare_" + prodid).val('0').parents(".pro-compare").removeClass("disabled");
            } else if (!isNaN(response) && action == "remove_from_compare" && response == 0) {
                if (pathname == "/compare") { location.href = siteurl + "/compare"; }
                if (pathname == "/compare") { location.href = siteurl + "/compare"; }
                $(".showcmpcnt").hide();
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product is removed for comparison").delay(3000).fadeOut();
                $(".compare_" + prodid).parents(".thumb-hov-btn").removeClass("cc-second-back").addClass("btn-primary btn-default");
                $(".compare_" + prodid).val('0');
                $(".compare_" + prodid).val('0').parents(".pro-compare").removeClass("disabled");
            }
        }
    });
}

/* function chngprod(prodboxid, prodid, parent, variationBy, variation) {

    $.ajax({
        url: siteurl + "/ajax/product_ajax.php",
        type: 'POST',
        data: { prodid: prodid, parent: parent, variationBy: variationBy, variation: variation, action: "getvariationProduct" },
        success: function(response) {

            $("#" + prodboxid).replaceWith(response);
        }
    });
} */

/* function chngprod(prodboxid, prodid, parent, variationBy) {

    $.ajax({
        url: siteurl + "/ajax/product_ajax.php",
        type: 'POST',
        data: { prodid: prodid, parent: parent, variationBy: variationBy, action: "getvariationProduct" },
        success: function(response) {

            $("#" + prodboxid).replaceWith(response);
        }
    });
} */

function changeprod(sel) {

    var thisval = $(sel).attr("id")
    prodboxid = $("#" + thisval + " option:selected").attr("data-box");
    prodid = $("#" + thisval + " option:selected").attr("data-id");
    parent = $("#" + thisval + " option:selected").attr("data-parent");
    variationBy = $("#" + thisval + " option:selected").attr("data-var");
    $.ajax({
        url: siteurl + "/ajax/product_ajax.php",
        type: 'POST',
        data: { prodid: prodid, parent: parent, variationBy: variationBy, action: "getvariationProduct" },
        success: function(response) {

            $("#" + prodboxid).replaceWith(response);
        }
    });

}
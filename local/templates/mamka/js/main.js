function e(e) {
    (!window.event || 37 != event.keyCode && 39 != event.keyCode) && (e.value = e.value.replace(/\D/g, ""))
}

$(document).ready(function () {
    $(".sidebar-one-levlel-button").click(function () {
        $(this).toggleClass("active").next().slideToggle(300);
    });
    $(".modal-open").click(function () {
        $("#" + $(this).data("modal")).fadeToggle(300);
        $("#modal-backing").fadeToggle(300);
    });
    $(".close-modal").click(function () {
        $(this).parent().fadeOut(300);
        $("#modal-backing").fadeOut(300);
    });
    $("#modal-backing").click(function () {
        $("#call-you-modal").fadeOut(300);
        $("#modal-backing").fadeOut(300);
    });
    $("#menu-button").click(function () {
        $("#main-nav").toggleClass("active");
        $("#menu-button").toggleClass("active");
    });
    $(".tab-button").click(function () {
        if (!$(this).hasClass("active")) {
            var e = $(this).parent().children(".tab-button"), t = $(this).parent().parent().children(".tabs-contents").children(".tab-content");
            $(e).removeClass("active"), $(t).removeClass("active"), $(this).addClass("active");
            for (var n = 0; n < e.length; n++)$(e[n]).hasClass("active") && $(t[n]).addClass("active")
        }
    });
    $("#sidebar-categories a.with-subcategory").click(function (e) {
        e.preventDefault();
        $(this).toggleClass("active").next().slideToggle(300);
    });

    var maps = $('._load_map');
    if (maps.length > 0)
    {
        $(window).on('scroll', function(){
            maps.each(function(i, item) {
                var map = $(item);
                if (map.hasClass('_loaded')) return;
                if ($(window).scrollTop() + $(window).height() < map.offset().top) return;

                map.append('<iframe src="'+map.attr('data-src')+'" style="'+map.attr('data-style')+'"></iframe>');
                map.addClass('_loaded');
            });
        });
        if ($('body').height() < $(window).height())
        {
            setTimeout(function(){
                $(window).trigger('scroll');
            }, 1000);
        }
    }
    $('._add_to_basket_form').on('submit', function(){
        var $form = $(this);
        var $card = $form.parents('.card').eq(0);
        var $btn = $form;
        if ($form.find('[type=submit]').length > 0)
        {
            $btn = $form.find('[type=submit]');
        }
        var callbackSuccess = function(message) {
            if (typeof(message) === "undefined") {
                message = "?????????? ?????????????? ????????????????????";
            }
            var picture = $card.find('._picture').attr('src');
            var price = $card.find('._price').text();
            var name = $card.find('._name').text();
            var quantity = 1;
            addToBasket(picture, price, name, quantity);
        }
        var callbackError = function(error) {
            alert(error);
        }
        var params = {
            url: $(this).attr('action') || '',
            data: $(this).serialize(),
            method: $(this).attr('method') || 'POST',
            dataType: $(this).data('type') || 'json',
        };
        if (params.dataType == 'json') {
            params.success = function(res) {
                if (res.STATUS == "ERROR") {
                    callbackError(res.MESSAGE);
                } else {
                    callbackSuccess(res.MESSAGE);
                }
            }
        } else {
            params.success = function(res) {
                callbackSuccess(res);
            }
        }
        params.error = function(a) {
            callbackError('????????????: ' + a.status + ' ' + a.statusText);
        }
        $.ajax(params);
        return false;
    });
    $('.lazyload').lazyload();
});
$(window.document).on("DOMContentLoaded", function () {
    $('main').css({'min-height': $('#header').outerHeight()+$('#breadcrumbs').outerHeight()+$('#footer').outerHeight()});
    $('input[name=phone]').mask('+7 (999) 999-99-99');
});

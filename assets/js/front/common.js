import $ from 'jquery';
var $docEl = $('html, body'),
    $wrap = $('.wrapper'),
    scrollTop;
$.lockBody = function() {
    if(window.pageYOffset) {
        scrollTop = window.pageYOffset;

       /* $wrap.css({
            top: - (scrollTop)
        });*/
    }

    $docEl.css({
        height: "100%",
        overflow: "hidden"
    });
};

$.unlockBody = function() {
    $docEl.css({
        height: "",
        overflow: ""
    });

    /*$wrap.css({
        top: ''
    });*/

    window.scrollTo(0, scrollTop);
    window.setTimeout(function () {
        scrollTop = null;
    }, 0);

};

$('li').click(function () {
    if ($(this).data('location'))
        window.location.href = $(this).data('location');
});

$('.in_basket').click(function () {
    if ($(this).data('product-location'))
        window.location.href = $(this).data('product-location')
});

$('.toogle-dropdown').click(function () {
    if ($('.dropdown').css('display') === 'none')
        $('.dropdown').fadeIn();
    else
        $('.dropdown').fadeOut();
});

/*$('.toogle-dropdown-footer').click(function () {
    if ($('.dropdown-footer').css('display') === 'none')
        $('.dropdown-footer').fadeIn();
    else
        $('.dropdown-footer').fadeOut();
});*/

$('.basket-container').click(function () {
    window.location.href = '/cart';
});

$('.additional-cart').click(function () {
    window.location.href = '/cart';
});

$('.checkout').click(function () {
    window.location.href = '/cart/makeOrder'
});

$('.login-button').click(function () {
    $(this).parent().submit();
});

$('#menu').click(function () {
    let nav = $('.menu-nav');
    let allowScroll = $('.menu-nav > ul');
    let body = $('body');
    if(nav.css('display') === 'none'){
        nav.slideDown();
        $.lockBody();
    } else {
        nav.slideUp();
        $.unlockBody();
    }
    /*if(!/iPad|iPhone|iPod/.test(navigator.userAgent))
        body.css("overflow") === "hidden" ? body.css('overflow','auto') : body.css('overflow','hidden');
    else
        body.css('position') === 'fixed' ?  body.css({position: 'static'}) : body.css({top: 0, left: 0, right:0, bottom: 0,position: 'fixed' })*/
});

$('.toggle-mobile').click(function () {
    $(this).siblings().each(function () {
        let li = $(this).slideToggle();
    })
});

$('.social').click(function () {
    window.location.href = $(this).data('login-social');
});

$('#user').click(function () {
   window.location.href = $(this).data('user');
});

$('.warn-button').click(function () {
   window.location.href = '/category-varenye-raki';
});

$('.back-to-main').click(function () {
    window.location.href = '/';
});

$('.user-tab').click(function () {
   window.location.href = $(this).data('path');
});

$('#mobile-basket').click(function () {
    window.location.href = '/cart'
});

$('#mobile-logo').click(function () {
    window.location.href = '/';
});
$('.header-raki-logo img').click( function () {
   window.location.href = '/';
});

$('#user-mobile').click(function () {
    window.location.href = $(this).data('url');
});

$(document).ready(function () {
    if($('.products-row').length) {
        let children = $('.products-row').children();
        if (children.length < 4 && window.screen.height > 1024) {
            console.log(children.length);
            $('footer').css('position', 'absolute');
        }
    }
});
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

$(document).on('click','.in_basket',function () {
    if ($(this).data('product-location'))
        window.location.href = $(this).data('product-location')
});

$('.toogle-dropdown').click(function () {
    if ($('.dropdown').css('display') === 'none' ) {
        $('.toogle-dropdown i').css('transform', 'rotate(90deg)').css('padding-bottom','0.5rem');
        $('.dropdown').slideDown();
    }
    else {
        $('.toogle-dropdown i').css('transform', 'rotate(0)');
        $('.dropdown').slideUp();
    }
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
$('.add-basket').on('click', function () {
    $('#exampleModalCenter').css('display','block');
    $('#exampleModalCenter').animate({
        opacity : 1,
        top: 0,
    },500);
    if (window.screen.width < 992) {
        $('#exampleModalCenter').append('')
    }
    setTimeout(swipeOutOfScreen,2000);
    setTimeout(displayNone,2500);
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

$('#another-category').click(function () {
   window.location.href = '/category-varenye-raki';
});

$('img[data-back]').click(function () {
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

/*$(document).ready(function () {
    if($('.products-row').length) {
        let children = $('.products-row').children();
        if (children.length < 4 && window.screen.height > 1024) {
            console.log(children.length);
            $('footer').css('position', 'absolute');
        }
    }
});*/
function swipeOutOfScreen() {
    $('#exampleModalCenter').animate({
        opacity: 0,
        top: '-100px'
    });
}
function displayNone() {
    $('#exampleModalCenter').css('display','none').css('top','-100px');
}

/*$('.additional-nav-container').on('click','.additional-nav-item',function () {
    window.location.href = $(this).data('url');
});*/

$('.additional-nav-item').click(function () {
    window.location.href = $(this).data('url');
});

$('.readmore').click(function () {
    $('#main-text-container').find('p').css({overflow: 'visible', height: 'max-content'});
    $(this).remove();
});

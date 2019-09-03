import $ from 'jquery';
const bodyScrollLock = require('body-scroll-lock');
const disableBodyScroll = bodyScrollLock.disableBodyScroll;
const enableBodyScroll = bodyScrollLock.enableBodyScroll;

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

$('#basket-container').click(function () {
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
        nav.fadeIn();
        disableBodyScroll(allowScroll);
    } else {
        enableBodyScroll(allowScroll);
        nav.fadeOut();
    }
    //body.css("overflow") === "hidden" ? body.css('overflow','auto') : body.css('overflow','hidden');
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
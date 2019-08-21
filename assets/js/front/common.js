import $ from 'jquery';


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

$('.toogle-dropdown-footer').click(function () {
    if ($('.dropdown-footer').css('display') === 'none')
        $('.dropdown-footer').fadeIn();
    else
        $('.dropdown-footer').fadeOut();
});

$('#basket-container').click(function () {
    window.location.href = '/cart';
});

$('.checkout').click(function () {
    window.location.href = '/cart/makeOrder'
});

$('.login-button').click(function () {
    $(this).parent().submit();
});

$('#menu').click(function () {
    console.log('d');
    let nav = $('.menu-nav');
    nav.css('display') === 'none' ? nav.fadeIn() : nav.fadeOut();
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
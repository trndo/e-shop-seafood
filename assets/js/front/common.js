import $ from 'jquery';

$('li').click(function () {
   if($(this).data('location'))
       window.location.href = $(this).data('location');
});

$('.in_basket').click(function () {
   if($(this).data('product-location'))
       window.location.href = $(this).data('product-location')
});

$('.toogle-dropdown').click(function () {
    if($('.dropdown').css('display') === 'none')
        $('.dropdown').fadeIn();
    else
        $('.dropdown').fadeOut();
});

$('#basket-container').click(function () {
    window.location.href = '/cart';
});

$('.checkout').click(function () {
   window.location.href = '/makeOrder'
});
import $ from 'jquery';

$('li').click(function () {
   if($(this).data('location'))
       window.location.href = $(this).data('location');
});

$('.in_basket').click(function () {
   if($(this).data('product-location'))
       window.location.href = $(this).data('product-location')
});
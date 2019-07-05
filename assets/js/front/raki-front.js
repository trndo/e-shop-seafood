import '../../css/lipinskie-raki/main.css';
import '../../css/lipinskie-raki/small-page.css'
import '../../css/lipinskie-raki/products.css'
import '../../css/lipinskie-raki/product.css';
import 'simplebar/dist/simplebar.css';
import $ from 'jquery';
import SimpleBar from 'simplebar';

$(document).ready(function () {
        if($('.cart-container').length)
        new SimpleBar($('.cart-container')[0],{
          autoHide: false});

    $('.to_basket').on('click',function () {
        let type = $(this).data('type');
        let slug = $(this).data('name');
        let quantity = $('.quantity').val();

        $.ajax({
           type: 'POST',
           url: '/addToCart',
           data: {
               type: type,
               slug: slug,
               quantity: quantity
           },
           success: function (res) {
                console.log(res);
           }
        });
    });

$(document).on('click','.delete-from-cart',function () {
        let slug = $(this).data('name');
        $.ajax({
            type: 'DELETE',
            url: '/removeFromCart',
            data: {
                slug: slug
            },
            success: function (res) {
                console.log(res)
            }
        });
    });
});

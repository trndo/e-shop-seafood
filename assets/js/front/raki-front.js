import '../../css/lipinskie-raki/main.css';
import '../../css/lipinskie-raki/small-page.css'
import '../../css/lipinskie-raki/products.css'
import 'simplebar/dist/simplebar.css';
import $ from 'jquery';
import SimpleBar from 'simplebar';

$(document).ready(function () {
    new SimpleBar($('.cart-container')[0],{
        autoHide: false});

    $('.in_basket').on('click',function () {
        let type = $(this).data('type');
        let slug = $(this).data('name');

        $.ajax({
           type: 'POST',
           url: '/addToCart',
           data: {
               type: type,
               slug: slug
           },
           success: function (res) {
                $('.cartItems').html(res);
           }
        });
    })
});
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
               $('.sum').text(res.totalSum+' ₴');
           }
        });
    });

$(document).on('click','.delete-from-cart',function () {
        let slug = $(this).data('name');
        let item = $(this).parent();
        $.ajax({
            type: 'DELETE',
            url: '/removeFromCart',
            data: {
                slug: slug
            },
            success: function (res) {
                item.remove();
                console.log(res.status+' '+res.totalSum);
                $('.sum').text(res.totalSum+' ₴');
            }
        });
    });
$(document).on('click','.plus',function () {

    let input = $(this).siblings('.quantity-res').children();
    let name = $(this).parent().siblings('.order-product-name').data('name');

    if (input.val() == 10) {
        alert('Mojno zakazat tolko 10 edenic etoy posizui');
        return;
    }
    let val = Number(input.val());
    $.ajax({
        type: "POST",
        url: "/changeQuantity",
        data: {
            quantity: val + 1,
            name: name
        },
        success: function (res) {
            if (res.status === false) {
                alert(res.message);
            }
            else {
                input.val(val + 1);
                $('.sum').text(res.totalSum+' ₴');
            }

        }
    });

});

$(document).on('click','.minus',function () {
    let input = $(this).siblings('.quantity-res').children();
    let name = $(this).parent().siblings('.order-product-name').data('name');

    if(input.val() == 1)
        return;
    let val = Number(input.val());

    $.ajax({
        type: "POST",
        url: "/changeQuantity",
        data: {
            quantity: val - 1,
            name: name
        },
        success: function (res) {
            input.val(val - 1);
            $('.sum').text(res.totalSum+' ₴');
        }
    });
});
$(document).on('keyup','.quantity-res',function () {

} )
});


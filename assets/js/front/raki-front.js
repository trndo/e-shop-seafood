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
        let item = $(this).parent();
        $.ajax({
            type: 'DELETE',
            url: '/removeFromCart',
            data: {
                slug: slug
            },
            success: function (res) {
                item.remove();
            }
        });
    });
$(document).on('click','.plus',function () {

    let input = $(this).siblings('.quantity-res').children();
    let name = $(this).parent().siblings('.order-product-name').data('name');
    if ( input.val() == 10) {
        alert('Mojno zakazat tolko 10 edenic etoy posizui');
        return;
    }
    let val = Number(input.val());
    if (changeQuantity(val + 1,name)) {
        input.val(val + 1);
    }
});

$(document).on('click','.minus',function () {
    let input = $(this).siblings('.quantity-res').children();
    let name = $(this).parent().siblings('.order-product-name').data('name');
    if(input.val() == 1)
        return;
    let val = Number(input.val());
    input.val(val - 1);
    changeQuantity(val - 1,name);
})

});

function changeQuantity(quantity,name) {
    let result = '';
    $.ajax({
        type: "POST",
        url: "/changeQuantity",
        async: false,
        data: {
            quantity: quantity,
            name: name
        },
        success: function (res) {
            if (res.status === false) {
                alert(res.message);
                result = res.status;
            }
            else {
                result = res.status;
            }

        }
    });
    return result;
}


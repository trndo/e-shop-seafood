import '../../css/lipinskie-raki/main.css';
import '../../css/lipinskie-raki/small-page.css'
import '../../css/lipinskie-raki/products.css'
import '../../css/lipinskie-raki/product.css';
import '../../css/lipinskie-raki/media-query/main-media.css';
import 'simplebar/dist/simplebar.css';
import $ from 'jquery';
import SimpleBar from 'simplebar';

$(document).ready(function () {
        if($('.cart-container').length)
        new SimpleBar($('.cart-container')[0],{
          autoHide: false});

    if($('.menu-nav > ul').length)
        new SimpleBar($('.menu-nav > ul')[0],{
            autoHide: false});

    $('.add-basket').on('click',function () {
        let type = $(this).data('type');
        let id = $(this).data('name') ;

        if (checked == null && type === 'receipt') {
            alert('Vyberi razmer!');
            return ;
        }

        let quantity = $('.quantity-res > input').val();

        $.ajax({
            type: 'POST',
            url: '/addToCart',
            data: {
                size: checked,
                id: size ? type+'-'+id+'-'+size : type+'-'+id,
                quantity: quantity
            },
            success: function (res) {
                console.log(res);
                $('.sum').text(res.totalSum+' ₴');
            }
        });

    });

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
                    id: slug
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
        let name = $(this).parent().siblings('.item-name').data('name');

        let val = Number(input.val());
        $.ajax({
            type: "POST",
            url: "/changeQuantity",
            data: {
                quantity: val + 1,
                id: name
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
        let name = $(this).parent().siblings('.item-name').data('name');

        if(input.val() == 1)
            return;
        let val = Number(input.val());

        $.ajax({
            type: "POST",
            url: "/changeQuantity",
            data: {
                quantity: val - 1,
                id: name
            },
            success: function (res) {
                input.val(val - 1);
                $('.sum').text(res.totalSum+' ₴');
            }
        });
    });
    $(document).on('keyup','.quantity-res > input',function (e) {
        let input = $(this);
        let name = $(this).parents('.quantity').siblings('.order-product-name').data('name');
        let val = Number(input.val());

        if( val == '')
            return;

        // if (val > 10) {
        //     alert('Max 10');
        //     input.val(10);
        //     val = 10;
        // }

        if(val < 0) {
            alert('Min 1');
            input.val(1);
            val = 1;
        }

        $.ajax({
            type: "POST",
            url: "/changeQuantity",
            data: {
                quantity: val,
                id: name
            },
            success: function (res) {
                if (res.status === false) {
                    alert('ostalos '+res.rest);
                    input.val(res.rest)
                }
                else {
                    input.val(val);
                    $('.sum').text(res.totalSum+' ₴');
                }
            }
        });
    });
    $('.quantity-res > input').bind('cut copy paste',function (e) {
        e.preventDefault();
    });

    $(document).on('blur','.quantity-res > input',function (e) {
        let input = $(this);
        let name = $(this).parents('.quantity').siblings('.order-product-name').data('name');
        let val = Number(input.val());
        if(val == ''){
            $.ajax({
                type: "POST",
                url: "/changeQuantity",
                data: {
                    quantity: 1,
                    id: name
                },
                success: function (res) {
                    if (res.status === false) {
                        alert('ostalos '+res.rest);
                        input.val(res.rest)
                    }
                    else {
                        input.val(1);
                        $('.sum').text(res.totalSum+' ₴');
                    }
                }
            });
        }
    });
    let size = null;
    let related = $('.receipt-name').data('related');
    let checked = related ? related : null;
    $(document).on('click','.size-block',function (e) {
        $('.size-block-checked').each(function () {
           $(this).removeClass('size-block-checked');
        });
        $(this).addClass('size-block-checked');
        checked = $(this).data('name') ;
        size = $(this).data('size');
    })


});




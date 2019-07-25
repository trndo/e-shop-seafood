import '../../css/lipinskie-raki/main.css';
import '../../css/lipinskie-raki/small-page.css'
import '../../css/lipinskie-raki/products.css'
import '../../css/lipinskie-raki/product.css';
import '../../css/lipinskie-raki/media-query/main-media.css';
import '../../css/lipinskie-raki/media-query/small-page-query.css';
import 'simplebar/dist/simplebar.css';
import $ from 'jquery';
import SimpleBar from 'simplebar';
import 'slick-carousel';

$(document).ready(function () {
    if($('.cart-container').length)
        new SimpleBar($('.cart-container')[0],{
          autoHide: false});

    if($('.menu-nav > ul').length)
        new SimpleBar($('.menu-nav > ul')[0],{
            autoHide: false});
    if(window.screen.width < 376) {
        $('.additional-nav-container').slick({
            infinite: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: false,
        })
    }

    $('.add-basket').on('click',function () {
        let type = $(this).data('type');
        let id = $(this).data('name') ;

        if (checked == null && type === 'receipt') {
            alert('Vyberi razmer!');
            return ;
        }

        let quantity = $('.item-res > input').val();

        $.ajax({
            type: 'POST',
            url: '/addToCart',
            data: {
                size: checked,
                id: size ? type+'-'+id+'-'+size : type+'-'+id,
                quantity: quantity
            },
            success: function (res) {
                if (res.status){
                    $('.sum').text(res.totalSum+' ₴');
                    console.log(res);
                } else {
                    console.log('ne-ok');
                    alert('Ostalos-'+res.rest+''+res.unit);
                    $('.item-res > input').val(res.rest);
                }


            }
        });

    });

    // $('.to_basket').on('click',function () {
    //     let type = $(this).data('type');
    //     let slug = $(this).data('name');
    //     let quantity = $('.quantity').val();
    //
    //     $.ajax({
    //        type: 'POST',
    //        url: '/addToCart',
    //        data: {
    //            type: type,
    //            slug: slug,
    //            quantity: quantity
    //        },
    //        success: function (res) {
    //             console.log(res);
    //            $('.sum').text(res.totalSum+' ₴');
    //        }
    //     });
    // });



    $(document).on('click','.item-plus',function () {

        let input = $(this).siblings('.item-res').children();
        let val = Number(input.val());

        input.val(val + 0.5);
    });

    $(document).on('click','.item-minus',function () {
        let input = $(this).siblings('.item-res').children();

        if(input.val() == 1)
            return;
        let val = Number(input.val());
        input.val(val - 0.5);
    });
    $(document).on('keyup','.item-res > input',function (e) {
        let input = $(this);
        let val = Number(input.val());

        if( val == '')
            return;

        // if (val > 10) {
        //     alert('Max 10');
        //     input.val(10);
        //     val = 10;
        // }

        if(val < 0) {
            input.val(1);
        }
    });
    $('.item-res > input').bind('cut copy paste',function (e) {
        e.preventDefault();
    });

    $(document).on('blur','.item-res > input',function (e) {
        let input = $(this);
        let val = Number(input.val());
        if(val == '')
            input.val(1);
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
    });

    $('.show-modal').on('click', function () {
        $('#overlay').show();
    });

    $('.choose-type').on('click', function () {
        let orderType = $(this).data('order');

        $.ajax({
            type: 'POST',
            url: '/chooseOrder',
            dataType: 'application/json',
            data: JSON.stringify({
                orderType: orderType === "today"
            }),
            success: function (res) {
                $('#overlay').hide();
                $('.order-type').addClass('add-basket').removeClass('show-modal').removeClass('order-type');
                $('.adder').trigger('click');
            }
        });
    });

});




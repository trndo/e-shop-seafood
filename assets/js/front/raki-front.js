import '../../css/lipinskie-raki/main.css';
import '../../css/lipinskie-raki/small-page.css'
import '../../css/lipinskie-raki/products.css'
import '../../css/lipinskie-raki/product.css';
import '../../css/lipinskie-raki/user.css';
import '../../css/lipinskie-raki/media-query/main-media.css';
import '../../css/lipinskie-raki/media-query/small-page-query.css';
import '../../css/lipinskie-raki/media-query/user-media.css';
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
    if(window.screen.width < 500) {
        $('.additional-nav-container').slick({
            infinite: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: false,
            arrows: false
        })
    }


    $(document).on('click','.add-basket',function () {
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
                id: checked ? type+'-'+id+'-'+checked : type+'-'+id,
                quantity: quantity
            },
            success: function (res) {
                if (res.status){
                    $('.sum').text(res.totalSum+' ₴');
                    console.log(res);
                } else {
                    console.log('ne-ok');
                    alert(res.message);
                    if(res.rest != 0)
                        $('.item-res > input').val(res.rest);
                    else
                        $('.item-res > input').val(1);
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

    let related = $('.receipt-name').data('related');
    let checked = related ? related : null;
    $(document).on('click','.size-block',function (e) {
        $('.size-block-checked').each(function () {
           $(this).removeClass('size-block-checked');
        });
        $(this).addClass('size-block-checked');
        checked = $(this).data('name') ;
    });

    $('.show-modal').on('click', function () {
        console.log('i am in show modal');
        let name = $(this).data('name');
        let type = $(this).data('type');
        $('#overlay').toggle();
        replaceButton = ' <div class="custom-button add-basket" data-name="'+name+'" data-type="'+type+'">Добавить в козину</div>';
    });

    let replaceButton = '';

    $('.choose-type').on('click', function () {
        let orderType = $(this).data('order');

        $.ajax({
            type: 'POST',
            url: '/chooseOrder',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                orderType: orderType === "today"
            }),
            success: function (res) {
                $('.order-type').replaceWith(replaceButton);
                $('.add-basket').trigger('click');
            },
            error: function (res) {
                console.log(res);
            }
        });
        console.log('i am in ajax');
        $('#overlay').toggle();
    });

});




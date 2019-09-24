import '../../css/lipinskie-raki/main.css';
import '../../css/lipinskie-raki/small-page.css'
import '../../css/lipinskie-raki/products.css'
import '../../css/lipinskie-raki/product.css';
import '../../css/lipinskie-raki/user.css';
import '../../css/lipinskie-raki/media-query/main-media.css';
import '../../css/lipinskie-raki/media-query/products-media.css';
import '../../css/lipinskie-raki/media-query/product-media.css';
import '../../css/lipinskie-raki/media-query/small-page-query.css';
import '../../css/lipinskie-raki/media-query/user-media.css';
import 'simplebar/dist/simplebar.css';
import $ from 'jquery';
import SimpleBar from 'simplebar';
import 'slick-carousel';
import 'jquery-mask-plugin';
import 'jquery-validation'

$(document).ready(function () {
    if ($('.cart-container').length)
        new SimpleBar($('.cart-container')[0], {
            autoHide: false
        });

   /* if ($('.menu-nav > ul').length && !/iPad|iPhone|iPod/.test(navigator.userAgent))
        new SimpleBar($('.menu-nav > ul')[0], {
            autoHide: false
        });*/
    if ($('.history-container').length)
        new SimpleBar($('.history-container')[0], {
            autoHide: false
        });
    if (window.screen.width < 500) {
        $('.additional-nav-container').slick({
            infinite: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: false,
            arrows: false
        })
    }
    if(window.screen.width <= 991 && window.screen.width >= 768){
        $('.additional-products-row').slick({
            infinite: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: false,
            arrows: false
        })
    }

    $('.phone').mask('+38(000)000-00-00', {placeholder: "+38(___)___-__-__"});

    $(document).on('click', '.add-basket', function () {
        let type = $(this).data('type');
        let id = $(this).data('name');

        if (checked == null && type === 'receipt') {
            showModal('Выбери размер!');
            return;
        }

        let quantity = roundHalf($('.item-res > input').val());
        $('.item-res > input').val(quantity);
        $.ajax({
            type: 'POST',
            url: '/addToCart',
            data: {
                id: checked ? type + '-' + id + '-' + checked : type + '-' + id,
                quantity: quantity
            },
            success: function (res) {
                if (res.status) {
                    $('.sum').text(res.totalSum + ' ₴');
                    $('.additional-cart').text(res.totalSum + ' ₴');
                    $('.mob-basket').text(res.totalSum + ' ₴');
                } else {
                    showModal(res.message);
                    $('.item-res > input').val(1);
                }
            }
        });

    });

    $(document).on('click', '.item-plus', function () {

        let input = $(this).siblings('.item-res').children();
        let val = roundHalf(Number(input.val()));

        input.val(val + 0.5);
    });

    function roundHalf(num) {
        return Math.round(num*2)/2;
    }

    $(document).on('click', '.item-minus', function () {
        let input = $(this).siblings('.item-res').children();

        let val = roundHalf(Number(input.val()));
        if (val <= 1){
            input.val(1);
            return;
        }
        input.val(val - 0.5);
    });
    $(document).on('keyup', '.item-res > input', function (e) {
        let input = $(this);
        let val = Number(input.val());

        if (val == '')
            return;

        if (val < 0) {
            input.val(1);
        }
    });
    $('.item-res > input').bind('cut copy paste', function (e) {
        e.preventDefault();
    });

    $(document).on('blur', '.item-res > input', function (e) {
        let input = $(this);
        let val = input.val();
        val === '' ? input.val(1) : input.val(roundHalf(val));
    });

    let related = $('.receipt-name').data('related');
    let checked = related ? related : null;
    $(document).on('click', '.size-block', function (e) {
        $('.size-block-checked').each(function () {
            $(this).removeClass('size-block-checked');
        });
        $(this).addClass('size-block-checked');
        checked = $(this).data('name');
        let price = Number($(this).data('size-price'));
        let receiptPrice = Number($('.receipt-price').data('price'));
        let newPrice = price + receiptPrice;
        $('#price').text(newPrice);

    });

    $('.show-modal').on('click', function () {
        console.log('i am in show modal');
        let name = $(this).data('name');
        let type = $(this).data('type');
        $('#overlay').toggle();
        replaceButton = ' <div class="custom-button add-basket" data-name="' + name + '" data-type="' + type + '">Добавить в корзину</div>';
    });
    let replaceButton = '';

    function getBlock(type, id){
        return '<div class="product-to-basket">\n' +
        '<div class="quantity">\n' +
        '<span data- class="plus item-plus">+</span>\n' +
        '<div class="quantity-res item-res">\n' +
        '<input type="number" value="1">\n' +
        '</div>\n' +
        '<span class="minus item-minus">-</span>\n' +
        '</div>\n' +
        '<div class="custom-button add-basket" data-name="'+id+'" data-type="'+type+'">\n' +
        'Добавить в корзину\n' +
        '</div></div>'
    }

    function getSizes(info,block,orderType){
        $.ajax('/api/getSizes',{
            type: 'POST',
            data: {
                receipt: info.data('name'),
                orderType: orderType
            },
            success:function (res) {
                if(res) {
                    console.log(res);
                    console.log(info.parent());
                    info.parent().replaceWith(res);
                    if($('.receipt-sizes-container').length)
                        $('.product-price').after(block);
                }
                else {
                    info.parent().remove();
                    console.log('too bad')
                }
            }
        })
    }

    $(document).on('click','.choose-type', function () {
        let orderType = $(this).data('order');
        let info = $(this).parent();
        $.ajax({
            type: 'POST',
            url: '/chooseOrder',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                orderType: orderType === "today"
            }),
            success: function (res) {
                // $('.order-type').replaceWith(replaceButton);
                // $('.add-basket').trigger('click');
                let block = getBlock(info.data('type'),info.data('name'));
                if(info.data('type') === 'receipt' && !checked)
                    getSizes(info,block,orderType);
                else {
                    info.parent().remove();
                    $('.product-price').after(block);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    });

    let counter = 8;

    $(document).on('click', '#down', function () {
        let category = $('.category').data('category');
        $.ajax({
            'type': "GET",
            'url': "/category-" + category + "/loadMore",
            'data': {
                counter: counter,
            },
            success: function (res) {
                // $('#down').remove();
                let elements = $(res).filter('.loading').length;
                $('.products-row').append(res);
                counter += 9;
                console.log('chlen');

                if (elements !== 9) {
                    $('#down').remove();
                }
            }
        });
    });

    $('#close-modal').click(function () {
        $('.modal-window').fadeToggle();
        $('#overlay').fadeToggle();
    });

    $(".user_info_update").validate({
        rules: {
            "user_info_update[name]": {
                required: true,
                minlength: 2
            },
            "user_info_update[email]": {
                required: true,
                email: true
            },
            "user_info_update[phone]": {
                required: true,
            },
            "user_info_update[address]": {
                required: false,
            }
        },
        messages: {
            "user_info_update[name]": {
                required: 'Заполните поле!',
                minlength: 'Длина - более 2 символов!'
            },
            "user_info_update[email]": {
                required: 'Заполните поле!',
                email: 'Неправильный email!'
            },
            "user_info_update[phone]": {
                required: 'Заполните поле!',
            },

        },
        focusInvalid: true,
        errorClass: "validation-mess",
        errorElement: 'small',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).addClass('invalid-input');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('invalid-input');
        }
    });

    $(".login-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 5
            }
        },
        messages: {
            email: {
                required: 'Заполните поле!',
                email: 'Неправильный email!'
            },
            password: {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            }
        },
        focusInvalid: true,
        errorClass: 'validation-mess',
        errorElement: 'small',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).addClass('invalid-input');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('invalid-input');
        }
    });

    let wasInModal = false;
    $(".user-registration").validate({
        rules: {
            "user_registration[name]": {
                required: true,
            },
            'user_registration[email]': {
                required: true,
                email: true
            },
            'user_registration[password][first]': {
                required: true,
                minlength: 5
            },
            'user_registration[password][second]': {
                required: true,
                minlength: 5
            }
        },
        messages: {
            'user_registration[name]': {
                required: 'Заполните поле!',
            },
            'user_registration[email]': {
                required: 'Заполните поле!',
                email: 'Неправильный email!'
            },
            'user_registration[password][first]': {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            },
            'user_registration[password][second]': {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            },
        },
        focusInvalid: true,
        errorClass: 'validation-mess',
        errorElement: 'small',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).addClass('invalid-input');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('invalid-input');
        },
        submitHandler: function (form) {
           if(wasInModal)
               form.submit();
           showModal(null);
        }
    });

    $(".enter-email").validate({
        rules: {
            'reset_password[email]': {
                required: true,
                email: true
            }
        },
        messages: {
            'reset_password[email]': {
                required: 'Заполните поле!',
                email: 'Неправильный email!'
            },
        },
        focusInvalid: true,
        errorClass: 'validation-mess',
        errorElement: 'small',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).addClass('invalid-input');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('invalid-input');
        }
    });

    $(".forgot-pass").validate({
        rules: {
            'reset_password[password][first]': {
                required: true,
                minlength: 5
            },
            'reset_password[password][second]': {
                required: true,
                minlength: 5
            }
        },
        messages: {
            'reset_password[password][first]': {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            },
            'reset_password[password][second]': {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            }
        },
        focusInvalid: true,
        errorClass: 'validation-mess',
        errorElement: 'small',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).addClass('invalid-input');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('invalid-input');
        }
    });

    $(".reset-password").validate({
        rules: {
            'reset_password[oldPassword]': {
                required: true
            },
            'reset_password[password][first]': {
                required: true,
                minlength: 5
            },
            'reset_password[password][second]': {
                required: true,
                minlength: 5
            }
        },
        messages: {
            'reset_password[oldPassword]': {
                required: 'Заполните поле!'
            },
            'reset_password[password][first]': {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            },
            'reset_password[password][second]': {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            }
        },
        focusInvalid: true,
        errorClass: 'validation-mess',
        errorElement: 'small',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).addClass('invalid-input');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('invalid-input');
        }
    });

    $(".order-form").validate({
        rules: {
            'order[name]': {
                required: true,
                minlength: 5
            },
            'order[surname]': {
                required: false
            },
            'order[phoneNumber]': {
                required: true
            },
            'order[email]': {
                required: true,
                email: true
            },
            'order[orderDate]': {
                required: true,
            },
            'order[orderTime]': {
                required: true,
            },
            'order[deliveryType]': {
                required: true,
            },
            'order[comment]': {
                required: false,
            }
        },
        messages: {
            'order[name]': {
                required: 'Заполните поле!',
                minlength: 'Минимальная длина - 5!'
            },
            'order[surname]': {
                required: 'Заполните поле!'
            },
            'order[phoneNumber]': {
                required: 'Заполните поле!'
            },
            'order[email]': {
                required: 'Заполните поле!',
                email: 'Неправильный email!'
            },
            'order[orderDate]': {
                required: 'Заполните поле!',
            },
            'order[orderTime]': {
                required: 'Заполните поле!',
            },
            'order[deliveryType]': {
                required: 'Заполните поле!',
            }
        },
        focusInvalid: true,
        errorClass: 'validation-mess',
        errorElement: 'small',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).addClass('invalid-input');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('invalid-input');
        }
    });

    function showModal(text){
        let modal = $('.modal-window');
        if(text !== null)
            modal.children('.modal-title').text(text);
        $('#overlay').fadeToggle();
        modal.css('display','flex');
        console.log('show modal!');
    }

    let messageIndex = 1;
    let interval;
    $(document).ready(function () {
       startMessageSlider();
    });
    $('.ellipse').click(function () {
       clearInterval(interval);
       $('.ellipse-active').removeClass('ellipse-active');
       $(this).addClass('ellipse-active');
       let id = $(this).attr('id');
       id = Number(id.split('-')[1]);
       $('#mess-'+messageIndex).fadeOut(400,function () {
           messageIndex = id;
           $('#mess-'+id).fadeIn();
           startMessageSlider();
       })

    });

    function startMessageSlider(){
       interval = setInterval(changeMessage,5000);
    }

    function changeMessage() {
        $('#mess-'+messageIndex).fadeOut(400,function () {
            $('#ell-'+messageIndex).removeClass('ellipse-active');
            if(messageIndex === 5)
                messageIndex = 0;
            $('#mess-'+(++messageIndex)).fadeIn();
            $('#ell-'+messageIndex).addClass('ellipse-active');
        });

    }

    $('.code').click(function () {
        let code =$('.referral-input').val();
        $('#user_registration_friendUniqueId').val(code);
        wasInModal = true;
        $('.user-registration').submit();
    });
    $('.no-code').click(function () {
        wasInModal = true;
        $('.user-registration').submit();
    });
    /*$('#user_registration_save').click(function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        showModal(null);
        console.log('submit!');
    });*/

    $('#close-more').click(function () {
        $('#overlay').fadeToggle();
    });
    $('div[data-show="show"]').click(function () {
        $('#overlay').fadeToggle();
        //here will be ajax
    })
});






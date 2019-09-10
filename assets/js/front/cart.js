import $ from "jquery";

$('#close-modal').click(function () {
    console.log('here');
    $('.modal-window').toggle();
    $('#overlay').toggle();
});
$(document).on('click','.cart-plus',function () {
    let input = $(this).siblings('.cart-res').children();
    let name = $(this).parent().siblings('.item-name').data('name');

    let val = Number(input.val());
    $.ajax({
        type: "POST",
        url: "/changeQuantity",
        data: {
            quantity: val + 0.5,
            id: name
        },
        success: function (res) {
            if (res.status === false) {
                showModal(res.message);
            }
            else {
                input.val(val + 0.5);
                $('.sum').text(res.totalSum+' ₴');
            }

        }
    });

});

$(document).on('click','.cart-minus',function () {
    let input = $(this).siblings('.cart-res').children();
    let name = $(this).parent().siblings('.item-name').data('name');

    if(input.val() == 1)
        return;
    let val = Number(input.val());

    $.ajax({
        type: "POST",
        url: "/changeQuantity",
        data: {
            quantity: val - 0.5,
            id: name
        },
        success: function (res) {
            input.val(val - 0.5);
            $('.sum').text(res.totalSum+' ₴');
            $('.mob-basket').text(res.totalSum + ' ₴');
        }
    });
});
$(document).on('keyup','.cart-res > input',function (e) {
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
                showModal(res.message);
                if(res.rest != 0)
                    input.val(res.rest);
            }
            else {
                input.val(val);
                $('.sum').text(res.totalSum+' ₴');
                $('.mob-basket').text(res.totalSum + ' ₴');
            }
        }
    });
});
$('.cart-res > input').bind('cut copy paste',function (e) {
    e.preventDefault();
});

$(document).on('blur','.cart-res > input',function (e) {
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
                    showModal(res.message);
                    if(res.rest != 0)
                        input.val(res.rest);
                }
                else {
                    input.val(1);
                    $('.sum').text(res.totalSum+' ₴');
                    $('.mob-basket').text(res.totalSum + ' ₴');
                }
            }
        });
    }
});
function showModal(text){
    let modal = $('.modal-window');
    modal.children('.attention-title').text(text);
    $('#overlay').toggle();
    modal.toggle();
}

$(document).on('click','.delete-from-cart',function () {
    let name = $(this).data('name');
    let item = $(this).parent();
    $.ajax({
        type: 'DELETE',
        url: '/removeFromCart',
        data: {
            id: name
        },
        success: function (res) {
            item.remove();
            console.log(res.status+' '+res.totalSum);
            $('.sum').text(res.totalSum+' ₴');
        }
    });
});
require('../css/sb-admin-2.css');
const jQuery = require('jquery');
require('bootstrap');
import bsCustomFileInput from 'bs-custom-file-input';
import './plugins/jquery.abacus.min';
import 'jquery-mask-plugin';

let totalSum = $('#order_info_totalPrice').val();
$('#order_info_totalPrice').abacus(totalSum);

(function ($) {
    "use strict"; // Start of use strict
    $(document).ready(function () {
        bsCustomFileInput.init()

    });

    $('input[type=number]').change(function () {

        let totalPrice = $('#order_info_totalPrice');
        let sum = 0;

        $('input[type=number]').each(function () {
            let product = $(this).parent().prev();
            let quantity = Number($(this).val());
            let productPrice = Number(product.val());
            let receiptPrice = Number(product.prev().val());

            if (receiptPrice !== null && product.hasClass('receipt_product') === true) {

                console.log(receiptPrice, productPrice);
                sum += (Math.ceil(quantity) * receiptPrice) + (quantity * productPrice);
                console.log(sum)
            } else {
                sum += quantity * productPrice;
                console.log('a' + sum)
            }
        });
        console.log(sum);
        totalSum = totalPrice.val(sum);
        totalPrice.abacus(totalSum.val());
    });

    $('.fa-trash-alt').click(function () {
        let id = $(this).data('id');
        let trash = $(this);

        $.ajax({
            type: "DELETE",
            url: "/lipadmin/deleteOrderDetail/" + id,
            success: function (res) {
                $('.orderTotalPrice').text('Cумма: ' + res.totalPrice);
                console.log(res.totalPrice);
                trash.parent().remove();


            }
        })
    });

    $('.search-name').click(function () {
        $(this).parents('form').submit();
    });

    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        }
        ;
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        }
        ;
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

    $('.phone').mask('+38(000)000-00-00', {placeholder: "+38(___)___-__-__"});

    $(document).on('click', '.category-adjust', function () {
        let categoryId = $(this).data('id');
        console.log(categoryId);
        $.ajax({
            type: "POST",
            url: "/orderAdjustment/showItems",
            data: {
                categoryId: categoryId
            }, success(res) {
                $('tbody').append(res);
            }
        })
    })

})(jQuery); // End of use strict